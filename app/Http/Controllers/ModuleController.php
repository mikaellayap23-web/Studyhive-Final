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

        if ($user->role === 'admin') {
            // Admin sees all non-trashed modules
            $query = Module::withoutTrashed()->with(['user', 'assignedTeacher']);

            // Apply filters
            if ($request->filled('search')) {
                $query->where('title', 'like', "%{$request->search}%");
            }
            if ($request->filled('teacher')) {
                $query->where('assigned_teacher_id', $request->teacher);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $modules = $query->orderBy('order')->orderBy('created_at', 'desc')->get();
        } elseif ($user->role === 'teacher') {
            // Teacher sees all non-trashed modules
            $query = Module::withoutTrashed()->with(['user', 'assignedTeacher']);

            // Apply filters
            if ($request->filled('search')) {
                $query->where('title', 'like', "%{$request->search}%");
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $modules = $query->orderBy('order')->orderBy('created_at', 'desc')->get();
        } else {
            // Student sees only published non-trashed modules
            $query = Module::withoutTrashed()->with(['user', 'assignedTeacher'])->where('status', 'published');

            if ($request->filled('search')) {
                $query->where('title', 'like', "%{$request->search}%");
            }

            $modules = $query->orderBy('order')->orderBy('created_at', 'desc')->get();
        }

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

        if ($user->role === 'student') {
            // Get all published modules that are NOT trashed
            $query = Module::withoutTrashed()
                ->with(['user', 'assignedTeacher', 'assessment.submissions' => function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                }])
                ->where('status', 'published')
                ->orderBy('order')
                ->orderBy('created_at', 'desc');

            if ($request->filled('search')) {
                $query->where('title', 'like', "%{$request->search}%");
            }
            if ($request->filled('teacher')) {
                $query->where('assigned_teacher_id', $request->teacher);
            }

            $modules = $query->get();

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
        } elseif ($user->role === 'teacher') {
            // Teacher sees ALL modules (view-only unless assigned/created)
            $query = Module::withoutTrashed()
                ->with(['user', 'assignedTeacher']);

            if ($request->filled('search')) {
                $query->where('title', 'like', "%{$request->search}%");
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $modules = $query->orderBy('order')->orderBy('created_at', 'desc')->get();
        } else {
            // Admin sees all modules with full control
            $query = Module::withoutTrashed()->with(['user', 'assignedTeacher']);

            if ($request->filled('search')) {
                $query->where('title', 'like', "%{$request->search}%");
            }
            if ($request->filled('teacher')) {
                $query->where('assigned_teacher_id', $request->teacher);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $modules = $query->orderBy('order')->orderBy('created_at', 'desc')->get();
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

        if ($user->role === 'admin') {
            // Admin sees all non-trashed modules
            $query = Module::withoutTrashed()->with(['user', 'assignedTeacher']);

            if ($request->filled('search')) {
                $query->where('title', 'like', "%{$request->search}%");
            }
            if ($request->filled('teacher')) {
                $query->where('assigned_teacher_id', $request->teacher);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $modules = $query->orderBy('order')->orderBy('created_at', 'desc')->get();
        } elseif ($user->role === 'teacher') {
            // Teacher sees only modules they're assigned to or created
            $query = Module::withoutTrashed()
                ->with(['user', 'assignedTeacher'])
                ->where(function ($q) use ($user) {
                    $q->where('assigned_teacher_id', $user->id)
                        ->orWhere('user_id', $user->id);
                });

            if ($request->filled('search')) {
                $query->where('title', 'like', "%{$request->search}%");
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $modules = $query->orderBy('order')->orderBy('created_at', 'desc')->get();
        } else {
            // Student sees modules they're enrolled in
            $query = Module::withoutTrashed()
                ->with(['user', 'assignedTeacher'])
                ->where('status', 'published')
                ->whereHas('enrollments', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->orderBy('order')
                ->orderBy('created_at', 'desc');

            if ($request->filled('search')) {
                $query->where('title', 'like', "%{$request->search}%");
            }

            $modules = $query->get();

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
                    Storage::disk('public')->delete($module->file_path);
                }
                $data['file_path'] = $request->file('file')->store('modules', 'public');
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
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'order' => $validated['order'],
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
                Storage::disk('public')->delete($module->file_path);
            }
            $data['file_path'] = $request->file('file')->store('modules', 'public');
        }

        $module->update($data);

        return redirect()->route('modules.index')->with('success', 'Module updated successfully!');
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

        // Delete physical files permanently
        if ($module->file_path) {
            Storage::disk('public')->delete($module->file_path);
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
            // Delete files
            if ($module->file_path) {
                Storage::disk('public')->delete($module->file_path);
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
     * View a specific module.
     */
    public function show(Module $module)
    {
        /** @var User $user */
        $user = Auth::user();

        // Load assessment relationship
        $module->load('assessment');

        // Check if student is enrolled
        $isEnrolled = false;
        $enrollment = null;
        $moduleProgress = null;
        $moduleCompleted = false;
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
        }

        // Students can only view published modules
        if ($user->role === 'student' && $module->status !== 'published') {
            abort(403);
        }

        return view('modules.show', compact('module', 'isEnrolled', 'enrollment', 'moduleProgress', 'moduleCompleted'));
    }
}
