<?php

namespace App\Http\Controllers;

use App\Models\AssessmentSubmission;
use App\Models\Enrollment;
use App\Models\Module;
use App\Models\ModuleProgress;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    /**
     * Enroll in a module.
     */
    public function enroll(Module $module)
    {
        $user = Auth::user();

        // Only students can enroll
        if ($user->role !== 'student') {
            abort(403);
        }

        // Only allow enrollment in published modules
        if ($module->status !== 'published') {
            return redirect()->route('modules.all')
                ->with('error', 'You cannot enroll in an unpublished module.');
        }

        // Check if prerequisite is set and completed
        if ($module->prerequisite_module_id) {
            $prerequisite = Module::find($module->prerequisite_module_id);
            if ($prerequisite) {
                $prerequisiteProgress = ModuleProgress::where('user_id', $user->id)
                    ->where('module_id', $prerequisite->id)
                    ->first();

                $prerequisiteCompleted = $prerequisiteProgress && $prerequisiteProgress->progress >= 100;

                // Also check if assessment passed if exists
                if ($prerequisite->assessment) {
                    $passedSubmission = AssessmentSubmission::where('user_id', $user->id)
                        ->where('assessment_id', $prerequisite->assessment->id)
                        ->where('status', 'passed')
                        ->exists();
                    $prerequisiteCompleted = $prerequisiteCompleted && $passedSubmission;
                }

                if (! $prerequisiteCompleted) {
                    return redirect()->route('modules.all')
                        ->with('error', 'You must complete the prerequisite module: '.$prerequisite->title.' before enrolling in this module.');
                }
            }
        }

        // Check if student has any unfinished modules (progress < 100% OR assessment not passed)
        $hasUnfinished = Module::where('status', 'published')
            ->whereHas('enrollments', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where(function ($query) use ($user) {
                $query->whereHas('progress', function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                        ->where('progress', '<', 100);
                })
                    ->orWhereHas('assessment', function ($q) use ($user) {
                        $q->whereDoesntHave('submissions', function ($sq) use ($user) {
                            $sq->where('user_id', $user->id)
                                ->where('status', 'passed');
                        });
                    });
            })
            ->exists();

        if ($hasUnfinished) {
            return redirect()->route('modules.my')
                ->with('error', 'You must complete your current module (view all materials and pass the assessment) before enrolling in a new one.');
        }

        // Use firstOrCreate to prevent duplicate entry errors
        $enrollment = Enrollment::firstOrCreate([
            'user_id' => $user->id,
            'module_id' => $module->id,
        ]);

        if ($enrollment->wasRecentlyCreated) {
            return redirect()->route('modules.my')
                ->with('success', 'Successfully enrolled in the module!');
        }

        return redirect()->route('modules.my')
            ->with('info', 'You are already enrolled in this module.');
    }

    /**
     * Unenroll from a module.
     */
    public function unenroll(Module $module)
    {
        // Only students can unenroll
        if (Auth::user()->role !== 'student') {
            abort(403);
        }

        Enrollment::where('user_id', Auth::id())
            ->where('module_id', $module->id)
            ->delete();

        // Also delete progress
        ModuleProgress::where('user_id', Auth::id())
            ->where('module_id', $module->id)
            ->delete();

        return redirect()->route('modules.all')
            ->with('success', 'Successfully unenrolled from the module.');
    }
}
