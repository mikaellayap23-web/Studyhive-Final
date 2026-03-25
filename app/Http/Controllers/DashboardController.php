<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Announcement;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        $stats = [];

        if ($user->role === 'admin') {
            $stats = [
                'total_users' => User::count(),
                'pending_users' => User::where('status', 'pending')->count(),
                'modules' => 0, // You can add module model later
                'announcements' => Announcement::count(),
            ];
        } elseif ($user->role === 'teacher') {
            $stats = [
                'enrolled_students' => User::where('role', 'student')->where('status', 'active')->count(),
                'modules' => 0, // You can add module model later
                'assessments' => 0, // You can add assessment model later
                'announcements' => Announcement::count(),
            ];
        } else {
            $stats = [
                'total_modules' => 0, // You can add module model later
                'completed_modules' => 0, // You can add completion tracking later
                'ongoing_modules' => 0, // You can add completion tracking later
                'announcements' => Announcement::count(),
            ];
        }

        return view('pages.dashboard', compact('stats'));
    }
}
