<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    /**
     * Enroll in a module.
     */
    public function enroll(Module $module)
    {
        // Only students can enroll
        if (Auth::user()->role !== 'student') {
            abort(403);
        }

        // Check if already enrolled
        $exists = Enrollment::where('user_id', Auth::id())
            ->where('module_id', $module->id)
            ->exists();

        if ($exists) {
            return redirect()->route('modules.my')
                ->with('info', 'You are already enrolled in this module.');
        }

        Enrollment::create([
            'user_id' => Auth::id(),
            'module_id' => $module->id,
        ]);

        return redirect()->route('modules.my')
            ->with('success', 'Successfully enrolled in the module!');
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

        return redirect()->route('modules.all')
            ->with('success', 'Successfully unenrolled from the module.');
    }
}
