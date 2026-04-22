<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Assessment;
use App\Models\AssessmentSubmission;
use App\Models\Enrollment;
use App\Models\Module;
use App\Models\ModuleProgress;
use App\Models\User;
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
                'active_students' => User::where('role', 'student')->where('status', 'active')->count(),
                'active_teachers' => User::where('role', 'teacher')->where('status', 'active')->count(),
            ];
        } elseif ($user->role === 'teacher') {
            $stats = [
                'enrolled_students' => Enrollment::whereHas('module', function ($q) use ($user) {
                    $q->where('assigned_teacher_id', $user->id);
                })->distinct('user_id')->count(),
                'modules' => Module::where('assigned_teacher_id', $user->id)->count(),
                'assessments' => Assessment::whereHas('module', function ($q) use ($user) {
                    $q->where('assigned_teacher_id', $user->id);
                })->count(),
                'announcements' => Announcement::where('user_id', $user->id)->count(),
            ];
        } else {
            // Student dashboard with detailed stats
            $enrolledCount = Enrollment::where('user_id', $user->id)->count();
            $completedCount = ModuleProgress::where('user_id', $user->id)
                ->where('progress', '>=', 100)
                ->count();

            // Get enrolled modules with progress
            $enrolledModules = Enrollment::where('user_id', $user->id)
                ->with(['module.assessment' => function ($q) {
                    $q->where('is_published', true);
                }])
                ->get();

            // Calculate overall completion percentage
            $totalPublishedModules = Module::where('status', 'published')->count();
            $overallProgress = $totalPublishedModules > 0 ? round(($completedCount / $totalPublishedModules) * 100) : 0;

            // Get upcoming/available assessments for enrolled modules
            $upcomingAssessments = [];
            $recentGrades = [];

            foreach ($enrolledModules as $enrollment) {
                $module = $enrollment->module;
                if (! $module) {
                    continue;
                }
                if ($module->assessment && $module->assessment->is_published) {
                    // Check if student can take assessment (progress 100% and has attempts)
                    $progress = ModuleProgress::where('user_id', $user->id)
                        ->where('module_id', $module->id)
                        ->first();

                    $canAccess = $progress && $progress->progress >= 100;
                    $latestSubmission = AssessmentSubmission::where('user_id', $user->id)
                        ->where('assessment_id', $module->assessment->id)
                        ->orderBy('attempt_number', 'desc')
                        ->first();

                    if ($canAccess && $latestSubmission) {
                        $recentGrades[] = [
                            'assessment_title' => $module->assessment->title,
                            'module_title' => $module->title,
                            'percentage' => $latestSubmission->percentage,
                            'status' => $latestSubmission->status,
                            'attempt' => $latestSubmission->attempt_number,
                            'submitted_at' => $latestSubmission->submitted_at,
                            'assessment_passing_score' => $module->assessment->passing_score,
                        ];
                    } elseif ($canAccess && ! $latestSubmission) {
                        $upcomingAssessments[] = [
                            'assessment_id' => $module->assessment->id,
                            'assessment_title' => $module->assessment->title,
                            'module_title' => $module->title,
                            'module_id' => $module->id,
                        ];
                    }
                }
            }

            // Sort recent grades by date
            usort($recentGrades, function ($a, $b) {
                return strtotime($b['submitted_at']) - strtotime($a['submitted_at']);
            });

            // Keep only top 5
            $recentGrades = array_slice($recentGrades, 0, 5);

            $stats = [
                'total_modules' => $totalPublishedModules,
                'enrolled_modules' => $enrolledCount,
                'completed_modules' => $completedCount,
                'announcements' => Announcement::count(),
                'overall_progress' => $overallProgress,
                'upcoming_assessments' => $upcomingAssessments,
                'recent_grades' => $recentGrades,
            ];
        }

        return view('pages.dashboard', compact('stats'));
    }
}
