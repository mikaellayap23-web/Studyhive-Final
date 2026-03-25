<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Enrollment;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ModuleController extends Controller
{
    /**
     * Display modules.
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Admin sees all modules
            $modules = Module::with(['user', 'assignedTeacher'])->orderBy('order')->orderBy('created_at', 'desc')->get();
        } elseif ($user->role === 'teacher') {
            // Teacher sees modules they created OR are assigned to them
            $modules = Module::with(['user', 'assignedTeacher'])
                ->where(function ($query) use ($user) {
                    $query->where('user_id', $user->id)  // Modules they created
                          ->orWhere('assigned_teacher_id', $user->id);  // OR assigned to them
                })
                ->orderBy('order')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Student sees only published modules
            $modules = Module::with(['user', 'assignedTeacher'])
                ->where('status', 'published')
                ->orderBy('order')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('modules.index', compact('modules'));
    }

    /**
     * Display all available modules for students (for enrollment).
     */
    public function allModules()
    {
        $user = Auth::user();

        if ($user->role !== 'student') {
            return redirect()->route('modules.index');
        }

        // Get all published modules with enrollment status
        $modules = Module::with(['user', 'assignedTeacher'])
            ->where('status', 'published')
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($module) use ($user) {
                $module->is_enrolled = Enrollment::where('user_id', $user->id)
                    ->where('module_id', $module->id)
                    ->exists();
                return $module;
            });

        return view('modules.all', compact('modules'));
    }

    /**
     * Display student's enrolled modules.
     */
    public function myModules()
    {
        $user = Auth::user();

        if ($user->role !== 'student') {
            return redirect()->route('modules.index');
        }

        // Get modules the student is enrolled in
        $modules = Module::with(['user', 'assignedTeacher'])
            ->where('status', 'published')
            ->whereHas('enrollments', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get();

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
            'file' => ['nullable', 'file', 'max:10240'], // 10MB max
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
            'assessment.is_published' => ['boolean'],
        ]);

        $data = [
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'order' => $validated['order'],
        ];

        // Only assign teacher if admin is creating and selected a teacher
        if (Auth::user()->role === 'admin' && !empty($validated['assigned_teacher_id'])) {
            $data['assigned_teacher_id'] = $validated['assigned_teacher_id'];
        } elseif (Auth::user()->role === 'teacher') {
            // Auto-assign the teacher when they create a module
            $data['assigned_teacher_id'] = Auth::id();
        }

        // Handle file upload
        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('modules', 'public');
        }

        $module = Module::create($data);

        // Create assessment if requested
        if ($request->input('create_assessment') === '1' && !empty($validated['assessment'])) {
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
        // Only admin, the creator, or the assigned teacher can edit
        $currentUser = Auth::user();
        $isAssignedTeacher = $module->assigned_teacher_id === $currentUser->id;
        
        if ($currentUser->role !== 'admin' && Auth::id() !== $module->user_id && !$isAssignedTeacher) {
            abort(403);
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
        // Only admin, the creator, or the assigned teacher can update
        $currentUser = Auth::user();
        $isAssignedTeacher = $module->assigned_teacher_id === $currentUser->id;

        if ($currentUser->role !== 'admin' && Auth::id() !== $module->user_id && !$isAssignedTeacher) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'max:10240'],
            'status' => ['required', 'in:draft,published'],
            'order' => ['required', 'integer', 'min:0'],
            'assigned_teacher_id' => ['nullable', 'exists:users,id'],
            'update_assessment' => ['nullable', 'in:0,1'],
            'assessment' => ['nullable', 'array'],
            'assessment.title' => ['required_if:update_assessment,1', 'string', 'max:255'],
            'assessment.duration_minutes' => ['required_if:update_assessment,1', 'integer', 'min:1'],
            'assessment.passing_score' => ['required_if:update_assessment,1', 'integer', 'min:0', 'max:100'],
            'assessment.max_attempts' => ['required_if:update_assessment,1', 'integer', 'min:0'],
            'assessment.questions' => ['required_if:update_assessment,1', 'json'],
            'assessment.is_published' => ['boolean'],
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

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file
            if ($module->file_path) {
                Storage::disk('public')->delete($module->file_path);
            }
            $data['file_path'] = $request->file('file')->store('modules', 'public');
        }

        $module->update($data);

        // Update assessment if requested
        if ($request->input('update_assessment') === '1' && !empty($validated['assessment'])) {
            if ($module->assessment) {
                // Update existing assessment
                $module->assessment->update([
                    'title' => $validated['assessment']['title'],
                    'questions' => json_decode($validated['assessment']['questions'], true),
                    'duration_minutes' => $validated['assessment']['duration_minutes'],
                    'passing_score' => $validated['assessment']['passing_score'],
                    'max_attempts' => $validated['assessment']['max_attempts'],
                    'is_published' => $request->input('assessment.is_published') ? true : false,
                ]);
            } else {
                // Create new assessment
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
                ]);
            }

            return redirect()->route('modules.index')->with('success', 'Module and assessment updated successfully!');
        }

        return redirect()->route('modules.index')->with('success', 'Module updated successfully!');
    }

    /**
     * Remove the specified module.
     */
    public function destroy(Module $module)
    {
        // Only admin, the creator, or the assigned teacher can delete
        $currentUser = Auth::user();
        $isAssignedTeacher = $module->assigned_teacher_id === $currentUser->id;
        
        if ($currentUser->role !== 'admin' && Auth::id() !== $module->user_id && !$isAssignedTeacher) {
            abort(403);
        }

        // Delete file if exists
        if ($module->file_path) {
            Storage::disk('public')->delete($module->file_path);
        }

        $module->delete();

        return redirect()->route('modules.index')->with('success', 'Module deleted successfully!');
    }

    /**
     * View enrolled students for a module.
     */
    public function students(Module $module)
    {
        $currentUser = Auth::user();
        
        // Only admin or assigned teacher can manage students
        if ($currentUser->role !== 'admin' && $module->assigned_teacher_id !== $currentUser->id) {
            abort(403);
        }

        $students = User::where('role', 'student')
            ->where('status', 'active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get()
            ->map(function ($student) use ($module) {
                $student->is_enrolled = Enrollment::where('user_id', $student->id)
                    ->where('module_id', $module->id)
                    ->exists();
                return $student;
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
        if ($currentUser->role !== 'admin' && $module->assigned_teacher_id !== $currentUser->id) {
            abort(403);
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
        if ($currentUser->role !== 'admin' && $module->assigned_teacher_id !== $currentUser->id) {
            abort(403);
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
        if ($user->role === 'student') {
            $isEnrolled = Enrollment::where('user_id', $user->id)
                ->where('module_id', $module->id)
                ->exists();
        }

        // Students can only view published modules
        if ($user->role === 'student' && $module->status !== 'published') {
            abort(403);
        }

        return view('modules.show', compact('module', 'isEnrolled'));
    }
}
