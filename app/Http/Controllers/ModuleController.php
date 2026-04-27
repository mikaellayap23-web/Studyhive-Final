<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Enrollment;
use App\Models\Module;
use App\Models\ModuleProgress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ModuleController extends Controller
{
    /**
     * Display modules (excluding trashed).
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $baseQuery = Module::withoutTrashed()->with(['user', 'assignedTeacher']);

        // Apply role-based filters
        if ($user->role === 'student') {
            $baseQuery->where('status', 'published');
        }
        // Teachers and admins see all modules (subject to other filters)

        // Apply filters
        if ($request->filled('search')) {
            $baseQuery->where('title', 'like', "%{$request->search}%");
        }
        if ($request->filled('teacher')) {
            $baseQuery->where('assigned_teacher_id', $request->teacher);
        }
        if ($request->filled('status')) {
            $baseQuery->where('status', $request->status);
        }

        $modules = $baseQuery->orderBy('order')->orderBy('created_at', 'desc')->get();

        return view('modules.index', compact('modules'));
    }

    /**
     * Display available modules based on role:
     * - Student: all published modules (for enrollment)
     * - Teacher: modules they're assigned to or created
     * - Admin: all modules with full control
     */
    public function allModules(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $baseQuery = Module::withoutTrashed()
            ->with(['user', 'assignedTeacher']);

        // Apply role-based filters
        if ($user->role === 'student') {
            $baseQuery->where('status', 'published')
                ->with(['assessment.submissions' => function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                }]);
        }
        // Teachers see all modules (view-only unless assigned/created) - no additional filter needed here
        // Admins see all modules - no additional filter needed

        // Apply filters
        if ($request->filled('search')) {
            $baseQuery->where('title', 'like', "%{$request->search}%");
        }
        if ($request->filled('teacher')) {
            $baseQuery->where('assigned_teacher_id', $request->teacher);
        }
        if ($request->filled('status') && $user->role !== 'student') { // Students only see published anyway
            $baseQuery->where('status', $request->status);
        }

        // Special handling for teacher role - only show assigned/created modules
        if ($user->role === 'teacher') {
            $baseQuery->where(function ($q) use ($user) {
                $q->where('assigned_teacher_id', $user->id)
                    ->orWhere('user_id', $user->id);
            });
        }

        $modules = $baseQuery->orderBy('order')->orderBy('created_at', 'desc')->get();

        // Process student-specific data
        if ($user->role === 'student') {
            $moduleIds = $modules->pluck('id');
            $enrolledModuleIds = Enrollment::where('user_id', $user->id)
                ->whereIn('module_id', $moduleIds)
                ->pluck('module_id')
                ->flip();
            $progressByModule = ModuleProgress::where('user_id', $user->id)
                ->whereIn('module_id', $moduleIds)
                ->get()
                ->keyBy('module_id');

            $modules->each(function ($module) use ($enrolledModuleIds, $progressByModule) {
                $module->is_enrolled = $enrolledModuleIds->has($module->id);

                $progress = $progressByModule->get($module->id);
                $progressComplete = $progress && $progress->progress >= 100;
                $assessmentPassed = true;
                if ($module->assessment) {
                    $assessmentPassed = $module->assessment->submissions
                        ->where('status', 'passed')
                        ->isNotEmpty();
                }
                $module->is_completed = $progressComplete && $assessmentPassed;
            });
        }

        return view('modules.all', compact('modules'));
    }

    /**
     * Display modules based on role:
     * - Admin: all modules (full control)
     * - Teacher: modules they're assigned to or created
     * - Student: modules they're enrolled in
     */
    public function myModules(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $baseQuery = Module::withoutTrashed()->with(['user', 'assignedTeacher']);

        // Apply role-based filters
        if ($user->role === 'admin') {
            // Admin sees all modules
        } elseif ($user->role === 'teacher') {
            // Teacher sees only modules they're assigned to or created
            $baseQuery->where(function ($q) use ($user) {
                $q->where('assigned_teacher_id', $user->id)
                    ->orWhere('user_id', $user->id);
            });
        } else {
            // Student sees modules they're enrolled in
            $baseQuery->where('status', 'published')
                ->whereHas('enrollments', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                });
        }

        // Apply filters
        if ($request->filled('search')) {
            $baseQuery->where('title', 'like', "%{$request->search}%");
        }
        if ($request->filled('teacher') && $user->role !== 'student') { // Students filter handled elsewhere
            $baseQuery->where('assigned_teacher_id', $request->teacher);
        }
        if ($request->filled('status') && $user->role === 'admin') { // Only admin can filter by status in myModules
            $baseQuery->where('status', $request->status);
        }

        $modules = $baseQuery->orderBy('order')->orderBy('created_at', 'desc')->get();

        // Process student-specific data
        if ($user->role === 'student') {
            $myModuleIds = $modules->pluck('id');
            $myProgressByModule = ModuleProgress::where('user_id', $user->id)
                ->whereIn('module_id', $myModuleIds)
                ->get()
                ->keyBy('module_id');

            $modules->each(function ($module) use ($myProgressByModule) {
                $progress = $myProgressByModule->get($module->id);
                $module->progress = $progress ? $progress->progress : 0;
                $module->pdf_completed = $progress ? $progress->pdf_completed : false;
            });
        }

        return view('modules.my', compact('modules'));
    }

    /**
     * Show form to create a new module.
     */
    public function create()
    {
        $teachers = User::where('role', 'teacher')->where('status', 'active')->get();

        return view('modules.create', compact('teachers'));
    }

    /**
     * Store a newly created module.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:102400'],
            'status' => ['required', 'in:draft,published'],
            'order' => ['required', 'integer', 'min:0'],
            'assigned_teacher_id' => ['nullable', 'exists:users,id'],
            'prerequisite_module_id' => ['nullable', 'exists:modules,id'],
            'create_assessment' => ['nullable', 'in:0,1'],
            'assessment' => ['nullable', 'array'],
            'assessment.title' => ['required_if:create_assessment,1', 'string', 'max:255'],
            'assessment.duration_minutes' => ['required_if:create_assessment,1', 'integer', 'min:1'],
            'assessment.passing_score' => ['required_if:create_assessment,1', 'integer', 'min:0', 'max:100'],
            'assessment.max_attempts' => ['required_if:create_assessment,1', 'integer', 'min:0'],
            'assessment.questions' => ['required_if:create_assessment,1', 'json'],
            'assessment.is_published' => ['nullable', 'in:0,1'],
            'assessment.show_correct_answer' => ['nullable', 'in:0,1'],
        ]);

        $data = [
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'order' => $validated['order'],
            'prerequisite_module_id' => $validated['prerequisite_module_id'] ?? null,
        ];

        // Only assign teacher if admin is creating and selected a teacher
        if (Auth::user()->role === 'admin' && ! empty($validated['assigned_teacher_id'])) {
            $data['assigned_teacher_id'] = $validated['assigned_teacher_id'];
        } elseif (Auth::user()->role === 'teacher') {
            // Auto-assign the teacher when they create a module
            $data['assigned_teacher_id'] = Auth::id();
        }

        // Handle image upload (required)
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('modules/images', 'public');
        }

        // Handle file upload
        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('modules', 'public');
        }

        $module = Module::create($data);

        // Create assessment if requested
        if ($request->input('create_assessment') === '1' && ! empty($validated['assessment'])) {
            Assessment::create([
                'module_id' => $module->id,
                'created_by' => Auth::id(),
                'title' => $validated['assessment']['title'],
                'description' => null,
                'questions' => json_decode($validated['assessment']['questions'], true),
                'duration_minutes' => $validated['assessment']['duration_minutes'],
                'passing_score' => $validated['assessment']['passing_score'],
                'max_attempts' => $validated['assessment']['max_attempts'],
                'is_published' => $request->input('assessment.is_published') ? true : false,
                'show_correct_answer' => $request->input('assessment.show_correct_answer') ? true : false,
            ]);

            return redirect()->route('modules.index')->with('success', 'Module and assessment created successfully!');
        }

        return redirect()->route('modules.index')->with('success', 'Module created successfully!');
    }

    /**
     * Show the form for editing the specified module.
     */
    public function edit(Module $module)
    {
        // Only admin or the assigned teacher can edit
        $currentUser = Auth::user();

        if (! $module->canManage($currentUser)) {
            abort(403, 'You do not have permission to edit this module.');
        }

        // Load assessment relationship
        $module->load('assessment');

        $teachers = User::where('role', 'teacher')->where('status', 'active')->get();

        return view('modules.edit', compact('module', 'teachers'));
    }

    /**
     * Update the specified module.
     */
    public function update(Request $request, Module $module)
    {
        // Only admin or the assigned teacher can update
        $currentUser = Auth::user();

        if (! $module->canManage($currentUser)) {
            abort(403, 'You do not have permission to update this module.');
        }

        $saveType = $request->input('save_type', 'module');

        if ($saveType === 'assessment') {
            // Validate both module and assessment
            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
                'file' => ['nullable', 'file', 'mimes:pdf', 'max:102400'],
                'status' => ['required', 'in:draft,published'],
                'order' => ['required', 'integer', 'min:0'],
                'assigned_teacher_id' => ['nullable', 'exists:users,id'],
                'prerequisite_module_id' => ['nullable', 'exists:modules,id'],
                'update_assessment' => ['required', 'in:1'],
                'assessment' => ['required', 'array'],
                'assessment.title' => ['required', 'string', 'max:255'],
                'assessment.duration_minutes' => ['required', 'integer', 'min:1'],
                'assessment.passing_score' => ['required', 'integer', 'min:0', 'max:100'],
                'assessment.max_attempts' => ['required', 'integer', 'min:0'],
                'assessment.questions' => ['required', 'json'],
                'assessment.is_published' => ['nullable', 'in:0,1'],
                'assessment.show_correct_answer' => ['nullable', 'in:0,1'],
            ]);

            $data = [
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'],
                'order' => $validated['order'],
                'prerequisite_module_id' => $validated['prerequisite_module_id'] ?? null,
            ];

            // Only admin can assign/change teacher
            if ($currentUser->role === 'admin') {
                $data['assigned_teacher_id'] = $validated['assigned_teacher_id'] ?? null;
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                if ($module->image_path) {
                    Storage::disk('public')->delete($module->image_path);
                }
                $data['image_path'] = $request->file('image')->store('modules/images', 'public');
            }

            // Handle file upload
            if ($request->hasFile('file')) {
                if ($module->file_path) {
                    // Delete from whichever disk it exists (backwards compatibility)
                    if (Storage::disk('local')->exists($module->file_path)) {
                        Storage::disk('local')->delete($module->file_path);
                    } elseif (Storage::disk('public')->exists($module->file_path)) {
                        Storage::disk('public')->delete($module->file_path);
                    }
                }
                $data['file_path'] = $request->file('file')->store('modules', 'local');
            }

            $module->update($data);

            // Update or create assessment
            if ($module->assessment) {
                $module->assessment->update([
                    'title' => $validated['assessment']['title'],
                    'questions' => json_decode($validated['assessment']['questions'], true),
                    'duration_minutes' => $validated['assessment']['duration_minutes'],
                    'passing_score' => $validated['assessment']['passing_score'],
                    'max_attempts' => $validated['assessment']['max_attempts'],
                    'is_published' => $request->input('assessment.is_published') ? true : false,
                    'show_correct_answer' => $request->input('assessment.show_correct_answer') ? true : false,
                ]);
            } else {
                Assessment::create([
                    'module_id' => $module->id,
                    'created_by' => Auth::id(),
                    'title' => $validated['assessment']['title'],
                    'description' => null,
                    'questions' => json_decode($validated['assessment']['questions'], true),
                    'duration_minutes' => $validated['assessment']['duration_minutes'],
                    'passing_score' => $validated['assessment']['passing_score'],
                    'max_attempts' => $validated['assessment']['max_attempts'],
                    'is_published' => $request->input('assessment.is_published') ? true : false,
                    'show_correct_answer' => $request->input('assessment.show_correct_answer') ? true : false,
                ]);
            }

            return redirect()->route('modules.index')->with('success', 'Module and assessment saved successfully!');
        }

        // Save module only (skip assessment validation)
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:102400'],
            'status' => ['required', 'in:draft,published'],
            'order' => ['required', 'integer', 'min:0'],
            'assigned_teacher_id' => ['nullable', 'exists:users,id'],
            'prerequisite_module_id' => ['nullable', 'exists:modules,id'],
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'order' => $validated['order'],
            'prerequisite_module_id' => $validated['prerequisite_module_id'] ?? null,
        ];

        // Only admin can assign/change teacher
        if ($currentUser->role === 'admin') {
            $data['assigned_teacher_id'] = $validated['assigned_teacher_id'] ?? null;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            if ($module->image_path) {
                Storage::disk('public')->delete($module->image_path);
            }
            $data['image_path'] = $request->file('image')->store('modules/images', 'public');
        }

        // Handle file upload
        if ($request->hasFile('file')) {
            if ($module->file_path) {
                // Delete from whichever disk it exists (backwards compatibility)
                if (Storage::disk('local')->exists($module->file_path)) {
                    Storage::disk('local')->delete($module->file_path);
                } elseif (Storage::disk('public')->exists($module->file_path)) {
                    Storage::disk('public')->delete($module->file_path);
                }
            }
            $data['file_path'] = $request->file('file')->store('modules', 'local');
        }

        $module->update($data);

        return redirect()->route('modules.index')->with('success', 'Module updated successfully!');
    }

    /**
     * Duplicate an existing module.
     */
    public function duplicate(Module $module)
    {
        $currentUser = Auth::user();

        // Only admin or assigned teacher can duplicate
        if (! $module->canManage($currentUser)) {
            abort(403, 'You do not have permission to duplicate this module.');
        }

        // Begin transaction
        \DB::beginTransaction();

        try {
            // Create new module with copied data
            $newModule = $module->replicate();
            $newModule->title = $module->title.' (Copy)';
            $newModule->status = 'draft'; // Always draft for duplicates
            $newModule->user_id = Auth::id();
            $newModule->assigned_teacher_id = $module->assigned_teacher_id; // Keep same teacher
            $newModule->prerequisite_module_id = $module->prerequisite_module_id; // Keep same prerequisite
            $newModule->save();

            // Duplicate image if exists
            if ($module->image_path && Storage::disk('public')->exists($module->image_path)) {
                $ext = pathinfo($module->image_path, PATHINFO_EXTENSION);
                $newImagePath = 'modules/images/'.uniqid().'_'.time().'.'.$ext;
                Storage::disk('public')->copy($module->image_path, $newImagePath);
                $newModule->image_path = $newImagePath;
                $newModule->save();
            }

            // Duplicate file if exists
            if ($module->file_path && Storage::disk('local')->exists($module->file_path)) {
                $ext = pathinfo($module->file_path, PATHINFO_EXTENSION);
                $newFilePath = 'modules/'.uniqid().'_'.time().'.'.$ext;
                Storage::disk('local')->copy($module->file_path, $newFilePath);
                $newModule->file_path = $newFilePath;
                $newModule->save();
            }

            // Duplicate assessment if exists
            if ($module->assessment) {
                $newAssessment = $module->assessment->replicate();
                $newAssessment->module_id = $newModule->id;
                $newAssessment->created_by = Auth::id();
                $newAssessment->save();
            }

            \DB::commit();

            return redirect()->route('modules.edit', $newModule->id)
                ->with('success', 'Module duplicated successfully! You can now edit the new copy.');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Module duplication failed: '.$e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to duplicate module. Please try again.');
        }
    }

    /**
     * Soft delete the specified module.
     */
    public function destroy(Module $module)
    {
        // Only admin can soft delete modules
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Only administrators can delete modules.');
        }

        // Soft delete the module (files are kept for potential restore)
        $module->delete();

        return redirect()->route('modules.index')->with('success', 'Module moved to trash successfully!');
    }

    /**
     * Display trashed modules (soft deleted).
     */
    public function trashed(Request $request)
    {
        // Only admin can view trashed modules
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $query = Module::onlyTrashed()->with(['user', 'assignedTeacher']);

        // Apply filters
        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }
        if ($request->filled('teacher')) {
            $query->where('assigned_teacher_id', $request->teacher);
        }

        $modules = $query->orderBy('deleted_at', 'desc')->get();

        return view('modules.trashed', compact('modules'));
    }

    /**
     * Restore a soft deleted module.
     */
    public function restore($id)
    {
        // Only admin can restore
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $module = Module::onlyTrashed()->findOrFail($id);

        // Restore the module
        $module->restore();

        return redirect()->route('modules.trashed')->with('success', 'Module restored successfully!');
    }

    /**
     * Permanently delete a module (force delete).
     */
    public function forceDelete($id)
    {
        // Only admin can force delete
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $module = Module::onlyTrashed()->findOrFail($id);

        // Delete physical files permanently (check both local and public disks for backwards compatibility)
        if ($module->file_path) {
            if (Storage::disk('local')->exists($module->file_path)) {
                Storage::disk('local')->delete($module->file_path);
            } elseif (Storage::disk('public')->exists($module->file_path)) {
                Storage::disk('public')->delete($module->file_path);
            }
        }
        if ($module->image_path) {
            Storage::disk('public')->delete($module->image_path);
        }

        // Delete related records
        if ($module->assessment) {
            // Delete assessment submissions first
            $module->assessment->submissions()->delete();
            $module->assessment->delete();
        }

        // Delete enrollments and progress
        Enrollment::where('module_id', $module->id)->delete();
        ModuleProgress::where('module_id', $module->id)->delete();

        // Force delete the module
        $module->forceDelete();

        return redirect()->route('modules.trashed')->with('success', 'Module permanently deleted!');
    }

    /**
     * Bulk restore multiple modules.
     */
    public function bulkRestore(Request $request)
    {
        // Only admin can bulk restore
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'module_ids' => ['required', 'array'],
            'module_ids.*' => ['exists:modules,id'],
        ]);

        $count = Module::onlyTrashed()
            ->whereIn('id', $validated['module_ids'])
            ->restore();

        return redirect()->route('modules.trashed')->with('success', "{$count} module(s) restored successfully!");
    }

    /**
     * Bulk force delete multiple modules.
     */
    public function bulkForceDelete(Request $request)
    {
        // Only admin can bulk force delete
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'module_ids' => ['required', 'array'],
            'module_ids.*' => ['exists:modules,id'],
        ]);

        $modules = Module::onlyTrashed()->whereIn('id', $validated['module_ids'])->get();
        $count = 0;

        foreach ($modules as $module) {
            // Delete files (check both disks for backwards compatibility)
            if ($module->file_path) {
                if (Storage::disk('local')->exists($module->file_path)) {
                    Storage::disk('local')->delete($module->file_path);
                } elseif (Storage::disk('public')->exists($module->file_path)) {
                    Storage::disk('public')->delete($module->file_path);
                }
            }
            if ($module->image_path) {
                Storage::disk('public')->delete($module->image_path);
            }

            // Delete related records
            if ($module->assessment) {
                $module->assessment->submissions()->delete();
                $module->assessment->delete();
            }

            Enrollment::where('module_id', $module->id)->delete();
            ModuleProgress::where('module_id', $module->id)->delete();

            // Force delete
            $module->forceDelete();
            $count++;
        }

        return redirect()->route('modules.trashed')->with('success', "{$count} module(s) permanently deleted!");
    }

    /**
     * View enrolled students for a module.
     */
    public function students(Module $module)
    {
        $currentUser = Auth::user();

        // Only admin or assigned teacher can manage students
        if (! $module->canManage($currentUser)) {
            abort(403, 'You do not have permission to manage students for this module.');
        }

        $students = User::where('role', 'student')
            ->where('status', 'active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        // Batch load enrollment status
        $enrolledStudentIds = Enrollment::where('module_id', $module->id)
            ->pluck('user_id')
            ->flip();

        $students->each(function ($student) use ($enrolledStudentIds) {
            $student->is_enrolled = $enrolledStudentIds->has($student->id);
        });

        $enrolledStudents = $module->enrolledStudents()->with('user')->get();

        return view('modules.students', compact('module', 'students', 'enrolledStudents'));
    }

    /**
     * Enroll a student in a module.
     */
    public function addStudent(Request $request, Module $module)
    {
        $currentUser = Auth::user();

        // Only admin or assigned teacher can add students
        if (! $module->canManage($currentUser)) {
            abort(403, 'You do not have permission to add students to this module.');
        }

        $validated = $request->validate([
            'student_id' => ['required', 'exists:users,id'],
        ]);

        // Check if already enrolled
        $exists = Enrollment::where('user_id', $validated['student_id'])
            ->where('module_id', $module->id)
            ->exists();

        if ($exists) {
            return redirect()->route('modules.students', $module->id)
                ->with('info', 'Student is already enrolled in this module.');
        }

        Enrollment::create([
            'user_id' => $validated['student_id'],
            'module_id' => $module->id,
        ]);

        return redirect()->route('modules.students', $module->id)
            ->with('success', 'Student enrolled successfully!');
    }

    /**
     * Remove a student from a module.
     */
    public function removeStudent(Module $module, $studentId)
    {
        $currentUser = Auth::user();

        // Only admin or assigned teacher can remove students
        if (! $module->canManage($currentUser)) {
            abort(403, 'You do not have permission to remove students from this module.');
        }

        Enrollment::where('user_id', $studentId)
            ->where('module_id', $module->id)
            ->delete();

        return redirect()->route('modules.students', $module->id)
            ->with('success', 'Student removed from module successfully!');
    }

    /**
     * Display a specific module.
     */
    public function show(Module $module)
    {
        /** @var User $user */
        $user = Auth::user();

        // Load assessment relationship, creator/teacher, AND prerequisite to avoid N+1
        $module->load(['assessment', 'user', 'assignedTeacher', 'prerequisite']);

        // Check if student is enrolled
        $isEnrolled = false;
        $enrollment = null;
        $moduleProgress = null;
        $moduleCompleted = false;
        $prerequisiteMet = true; // Default to true if no prerequisite

        if ($user->role === 'student') {
            $enrollment = Enrollment::where('user_id', $user->id)
                ->where('module_id', $module->id)
                ->first();
            $isEnrolled = $enrollment ? true : false;

            // Get module progress
            if ($enrollment) {
                $moduleProgress = ModuleProgress::where('user_id', $user->id)
                    ->where('module_id', $module->id)
                    ->first();

                // Check if module is completed (progress 100% + assessment passed if exists)
                $progressComplete = $moduleProgress && $moduleProgress->progress >= 100;
                $assessmentPassed = true;
                if ($module->assessment) {
                    $assessmentPassed = $module->assessment->submissions()
                        ->where('user_id', $user->id)
                        ->where('status', 'passed')
                        ->exists();
                }
                $moduleCompleted = $progressComplete && $assessmentPassed;
            }

            // Check if prerequisite is met (if module has a prerequisite)
            if ($module->prerequisite) {
                $prerequisiteProgress = ModuleProgress::where('user_id', $user->id)
                    ->where('module_id', $module->prerequisite_module_id)
                    ->first();

                $prerequisiteCompleted = $prerequisiteProgress && $prerequisiteProgress->progress >= 100;

                // Also check assessment if prerequisite module has one
                if ($prerequisiteCompleted && $module->prerequisite->assessment) {
                    $prerequisiteCompleted = $module->prerequisite->assessment->submissions()
                        ->where('user_id', $user->id)
                        ->where('status', 'passed')
                        ->exists();
                }

                $prerequisiteMet = $prerequisiteCompleted;
            }
        }

        // Students can only view published modules
        if ($user->role === 'student' && $module->status !== 'published') {
            abort(403);
        }

        return view('modules.show', compact('module', 'isEnrolled', 'enrollment', 'moduleProgress', 'moduleCompleted', 'prerequisiteMet'));
    }

    /**
     * Print-friendly module summary.
     */
    public function print(Module $module)
    {
        /** @var User $user */
        $user = Auth::user();

        // Students can only view published modules
        if ($user->role === 'student' && $module->status !== 'published') {
            abort(403);
        }

        // Load relationships
        $module->load(['assessment.submissions' => function ($q) use ($user) {
            $q->where('user_id', $user->id);
        }, 'assignedTeacher']);

        $enrollment = null;
        $progress = null;
        if ($user->role === 'student') {
            $enrollment = Enrollment::where('user_id', $user->id)
                ->where('module_id', $module->id)
                ->first();

            if ($enrollment) {
                $progress = ModuleProgress::where('user_id', $user->id)
                    ->where('module_id', $module->id)
                    ->first();
            }
        }

        return view('modules.print', compact('module', 'enrollment', 'progress'));
    }

    /**
     * Serve protected module file (PDF) to authorized users.
     */
    public function serveFile(Module $module)
    {
        $user = Auth::user();

        // Admins can access any file
        if ($user->role === 'admin') {
            // authorized
        } elseif ($user->role === 'teacher') {
            // Teachers can access if assigned to this module or they created it
            if ($module->assigned_teacher_id !== $user->id && $module->user_id !== $user->id) {
                abort(403, 'You do not have permission to access this file.');
            }
        } elseif ($user->role === 'student') {
            // Students must be enrolled to access file
            $enrolled = Enrollment::where('user_id', $user->id)
                ->where('module_id', $module->id)
                ->exists();
            if (! $enrolled) {
                abort(403, 'You must be enrolled in this module to access the file.');
            }
        } else {
            abort(403);
        }

        if (! $module->file_path) {
            abort(404, 'File not found.');
        }

        // Determine which disk contains the file (support both legacy public and new local storage)
        $filePath = $module->file_path;
        $exists = Storage::disk('local')->exists($filePath);
        $disk = 'local';

        if (! $exists) {
            $exists = Storage::disk('public')->exists($filePath);
            $disk = 'public';
        }

        if (! $exists) {
            abort(404, 'File not found.');
        }

        return Storage::disk($disk)->response($filePath);
    }
}
