<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Module;
use App\Models\ModuleProgress;
use Illuminate\Http\Request;
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

        // Optimized single query: find any unfinished module the student is enrolled in
        $hasUnfinished = Module::where('status', 'published')
            ->whereHas('enrollments', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where(function ($query) use ($user) {
                // Module progress is less than 100%
                $query->whereHas('progress', function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                        ->where('progress', '<', 100);
                })
                // Or module has an assessment that student hasn't passed
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
