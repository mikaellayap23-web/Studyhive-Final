<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentSubmission;
use App\Models\Module;
use App\Models\User;
use App\Services\CertificateService;
use App\Services\GradeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    /**
     * Display assessments for the current user's role.
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Admin sees all assessments
            $assessments = Assessment::with(['module', 'creator'])->orderBy('created_at', 'desc')->get();
        } elseif ($user->role === 'teacher') {
            // Teacher sees only assessments they created
            $assessments = Assessment::with(['module', 'creator'])
                ->where('created_by', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Student sees assessments from enrolled modules
            $assessments = Assessment::with(['module', 'creator'])
                ->where('is_published', true)
                ->whereHas('module', function ($query) use ($user) {
                    $query->whereHas('enrollments', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('assessments.index', compact('assessments'));
    }

    /**
     * Show form to create a new assessment.
     */
    public function create()
    {
        /** @var User $user */
        $user = Auth::user();

        // Only admin and teachers can create assessments
        if ($user->role === 'student') {
            abort(403);
        }

        // Get modules that don't have an assessment yet
        if ($user->role === 'admin') {
            $modules = Module::whereDoesntHave('assessment')->get();
        } else {
            // Teachers can only create assessments for modules assigned to them
            $modules = Module::whereDoesntHave('assessment')
                ->where('assigned_teacher_id', $user->id)
                ->get();
        }

        return view('assessments.create', compact('modules'));
    }

    /**
     * Store a newly created assessment.
     */
    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Only admin and teachers can create assessments
        if ($user->role === 'student') {
            abort(403);
        }

        $validated = $request->validate([
            'module_id' => ['required', 'exists:modules,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'questions' => ['required', 'json'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'passing_score' => ['required', 'integer', 'min:0', 'max:100'],
            'max_attempts' => ['required', 'integer', 'min:0'],
            'is_published' => ['boolean'],
        ]);

        // Verify teacher can create assessment for this module
        if ($user->role === 'teacher') {
            $module = Module::find($validated['module_id']);
            if ($module->assigned_teacher_id !== $user->id) {
                abort(403, 'You can only create assessments for modules assigned to you.');
            }
        }

        Assessment::create([
            'module_id' => $validated['module_id'],
            'created_by' => $user->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'questions' => json_decode($validated['questions'], true),
            'duration_minutes' => $validated['duration_minutes'],
            'passing_score' => $validated['passing_score'],
            'max_attempts' => $validated['max_attempts'],
            'is_published' => $validated['is_published'] ?? false,
        ]);

        return redirect()->route('assessments.index')->with('success', 'Assessment created successfully!');
    }

    /**
     * Show the form for editing the specified assessment.
     */
    public function edit(Assessment $assessment)
    {
        /** @var User $user */
        $user = Auth::user();

        // Check if user can edit this assessment
        if (! $assessment->canEdit($user)) {
            abort(403, 'You are not authorized to edit this assessment.');
        }

        $modules = Module::pluck('title', 'id');

        return view('assessments.edit', compact('assessment', 'modules'));
    }

    /**
     * Update the specified assessment.
     */
    public function update(Request $request, Assessment $assessment)
    {
        /** @var User $user */
        $user = Auth::user();

        // Check if user can edit this assessment
        if (! $assessment->canEdit($user)) {
            abort(403, 'You are not authorized to edit this assessment.');
        }

        $validated = $request->validate([
            'module_id' => ['required', 'exists:modules,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'questions' => ['required', 'json'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'passing_score' => ['required', 'integer', 'min:0', 'max:100'],
            'max_attempts' => ['required', 'integer', 'min:0'],
            'is_published' => ['boolean'],
        ]);

        $assessment->update([
            'module_id' => $validated['module_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'questions' => json_decode($validated['questions'], true),
            'duration_minutes' => $validated['duration_minutes'],
            'passing_score' => $validated['passing_score'],
            'max_attempts' => $validated['max_attempts'],
            'is_published' => $validated['is_published'] ?? false,
        ]);

        return redirect()->route('assessments.index')->with('success', 'Assessment updated successfully!');
    }

    /**
     * Remove the specified assessment.
     */
    public function destroy(Assessment $assessment)
    {
        /** @var User $user */
        $user = Auth::user();

        // Check if user can delete this assessment
        if (! $assessment->canEdit($user)) {
            abort(403, 'You are not authorized to delete this assessment.');
        }

        $assessment->delete();

        return redirect()->route('assessments.index')->with('success', 'Assessment deleted successfully!');
    }

    /**
     * Show the assessment taking page for students.
     */
    public function take(Assessment $assessment)
    {
        /** @var User $user */
        $user = Auth::user();

        // Only students can take assessments
        if ($user->role !== 'student') {
            abort(403);
        }

        // Check if assessment is published
        if (! $assessment->is_published) {
            abort(403, 'This assessment is not available yet.');
        }

        // Check if student is enrolled in the module
        $isEnrolled = $assessment->module->enrollments()
            ->where('user_id', $user->id)
            ->exists();

        if (! $isEnrolled) {
            abort(403, 'You must be enrolled in this module to take the assessment.');
        }

        // Check if student has attempts remaining
        if (! $assessment->canUserTake($user)) {
            // Show their best result instead
            $bestSubmission = $assessment->bestSubmission($user);
            if ($bestSubmission) {
                return redirect()->route('assessments.results', $bestSubmission->id)
                    ->with('info', 'You have used all your attempts for this assessment. Here is your best result.');
            }

            return redirect()->route('modules.my')
                ->with('info', 'You have used all your attempts for this assessment.');
        }

        // Get latest submission if exists
        $latestSubmission = $assessment->latestSubmission($user);

        // Randomize ONLY the question order (NOT the options)
        $questions = $assessment->questions;
        shuffle($questions);

        return view('assessments.take', compact('assessment', 'latestSubmission', 'questions'));
    }

    /**
     * Submit the assessment using GradeService.
     */
    public function submit(Request $request, Assessment $assessment)
    {
        /** @var User $user */
        $user = Auth::user();

        // Only students can submit assessments
        if ($user->role !== 'student') {
            abort(403);
        }

        // Check if student has attempts remaining
        if (! $assessment->canUserTake($user)) {
            $bestSubmission = $assessment->bestSubmission($user);
            if ($bestSubmission) {
                return redirect()->route('assessments.results', $bestSubmission->id)
                    ->with('info', 'You have used all your attempts for this assessment.');
            }

            return redirect()->route('modules.my')
                ->with('info', 'You have used all your attempts for this assessment.');
        }

        $request->validate([
            'answers' => ['required', 'array'],
        ]);

        // Use GradeService for processing with transaction
        $gradeService = app(GradeService::class);
        $submission = $gradeService->processSubmission($assessment, $user->id);

        // Auto-issue certificate if passed
        if ($submission->status === 'passed') {
            $certService = app(CertificateService::class);
            $module = $assessment->module;
            if ($module && $certService->isModuleCompleted($user, $module)) {
                $certService->generateCertificate($user, $module);
            }
        }

        return redirect()->route('assessments.results', $submission->id);
    }

    /**
     * Show assessment results.
     */
    public function results(AssessmentSubmission $submission)
    {
        /** @var User $user */
        $user = Auth::user();

        // Only the student who submitted or admin/teacher can view results
        if ($user->id !== $submission->user_id && $user->role !== 'admin' && $user->role !== 'teacher') {
            abort(403);
        }

        return view('assessments.results', compact('submission'));
    }

    /**
     * Print-friendly assessment results.
     */
    public function printResults(AssessmentSubmission $submission)
    {
        /** @var User $user */
        $user = Auth::user();

        // Only the student who submitted or admin/teacher can view results
        if ($user->id !== $submission->user_id && $user->role !== 'admin' && $user->role !== 'teacher') {
            abort(403);
        }

        $submission->load(['assessment.questions', 'user', 'assessment.module']);

        return view('assessments.print-results', compact('submission'));
    }

    /**
     * View all submissions for an assessment (for teachers/admin).
     */
    public function submissions(Assessment $assessment)
    {
        /** @var User $user */
        $user = Auth::user();

        // Only admin or the assessment creator can view submissions
        if ($user->role !== 'admin' && $assessment->created_by !== $user->id) {
            abort(403, 'You are not authorized to view these submissions.');
        }

        $submissions = AssessmentSubmission::with('user')
            ->where('assessment_id', $assessment->id)
            ->orderBy('submitted_at', 'desc')
            ->get();

        return view('assessments.submissions', compact('assessment', 'submissions'));
    }
}
