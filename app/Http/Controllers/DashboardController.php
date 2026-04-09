<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Enrollment;
use App\Models\Module;
use App\Models\ModuleProgress;
use App\Models\User;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $stats = [];

        if ($user->role === 'admin') {
            $stats = [
                'total_users' => User::count(),
                'pending_users' => User::where('status', 'pending')->count(),
                'modules' => Module::count(),
                'announcements' => Announcement::count(),
            ];
        } elseif ($user->role === 'teacher') {
            $stats = [
                'enrolled_students' => Enrollment::distinct('user_id')->count(),
                'modules' => Module::where('assigned_teacher_id', $user->id)->count(),
                'assessments' => Assessment::whereHas('module', function ($q) use ($user) {
                    $q->where('assigned_teacher_id', $user->id);
                })->count(),
                'announcements' => Announcement::count(),
            ];
        } else {
            $enrolledCount = Enrollment::where('user_id', $user->id)->count();
            $completedCount = ModuleProgress::where('user_id', $user->id)
                ->where('progress', '>=', 100)
                ->count();

            $stats = [
                'total_modules' => Module::where('status', 'published')->count(),
                'enrolled_modules' => $enrolledCount,
                'completed_modules' => $completedCount,
                'announcements' => Announcement::count(),
            ];
        }

        return view('pages.dashboard', compact('stats'));
    }
}
