<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentSubmission;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    /**
     * Export student grades (for teachers/admin).
     */
    public function grades(Request $request)
    {
        $user = Auth::user();

        // Authorization: admin or teacher
        if (! in_array($user->role, ['admin', 'teacher'])) {
            abort(403);
        }

        $fileName = 'grades_'.now()->format('Y-m-d_H-i-s').'.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename={$fileName}"];

        $callback = function () use ($user, $request) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header row
            fputcsv($file, [
                'Student Name',
                'Student Email',
                'Module',
                'Assessment',
                'Score',
                'Percentage',
                'Status',
                'Attempt',
                'Max Attempts',
                'Submitted At',
            ]);

            // Build query based on role
            $query = AssessmentSubmission::query()
                ->with(['user', 'assessment.module'])
                ->orderBy('created_at', 'desc');

            // Filter by role
            if ($user->role === 'teacher') {
                $query->whereHas('assessment.module', function ($q) use ($user) {
                    $q->where('assigned_teacher_id', $user->id);
                });
            }

            // Apply filters if provided
            if ($request->filled('module_id')) {
                $query->whereHas('assessment', function ($q) use ($request) {
                    $q->where('module_id', $request->module_id);
                });
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $submissions = $query->get();

            foreach ($submissions as $submission) {
                fputcsv($file, [
                    $submission->user->first_name.' '.$submission->user->last_name,
                    $submission->user->email,
                    $submission->assessment->module->title,
                    $submission->assessment->title,
                    number_format($submission->score, 1),
                    number_format($submission->percentage, 1).'%',
                    ucfirst($submission->status),
                    $submission->attempt_number,
                    $submission->assessment->max_attempts,
                    $submission->submitted_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export module completion reports (for teachers/admin).
     */
    public function completion(Request $request)
    {
        $user = Auth::user();

        // Authorization: admin or teacher
        if (! in_array($user->role, ['admin', 'teacher'])) {
            abort(403);
        }

        $fileName = 'completion_report_'.now()->format('Y-m-d_H-i-s').'.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename={$fileName}"];

        $callback = function () use ($user, $request) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header row
            fputcsv($file, [
                'Student Name',
                'Student Email',
                'Module',
                'Enrollment Date',
                'Progress',
                'Completed',
                'Completed Date',
                'Assessment Passed',
                'Assessment Score',
                'Certificate Issued',
            ]);

            // Build query
            $query = Enrollment::query()
                ->with(['user', 'module.assessment', 'progress'])
                ->orderBy('created_at', 'desc');

            // Filter by role (teachers only see their modules)
            if ($user->role === 'teacher') {
                $query->whereHas('module', function ($q) use ($user) {
                    $q->where('assigned_teacher_id', $user->id);
                });
            }

            // Apply filters
            if ($request->filled('module_id')) {
                $query->where('module_id', $request->module_id);
            }
            if ($request->filled('status')) {
                if ($request->status === 'completed') {
                    $query->whereHas('progress', function ($q) {
                        $q->where('progress', '>=', 100);
                    });
                } elseif ($request->status === 'incomplete') {
                    $query->whereHas('progress', function ($q) {
                        $q->where('progress', '<', 100);
                    })->orWhereDoesntHave('progress');
                }
            }

            $enrollments = $query->get();

            foreach ($enrollments as $enrollment) {
                $progress = $enrollment->progress->first();
                $isCompleted = $progress && $progress->progress >= 100;
                $assessmentStatus = null;
                $assessmentScore = null;

                if ($enrollment->module->assessment) {
                    $submission = AssessmentSubmission::where('user_id', $enrollment->user_id)
                        ->where('assessment_id', $enrollment->module->assessment->id)
                        ->orderBy('attempt_number', 'desc')
                        ->first();
                    if ($submission) {
                        $assessmentStatus = $submission->status;
                        $assessmentScore = $submission->percentage.'%';
                    }
                }

                $certificate = Certificate::where('user_id', $enrollment->user_id)
                    ->where('module_id', $enrollment->module_id)
                    ->first();

                fputcsv($file, [
                    $enrollment->user->first_name.' '.$enrollment->user->last_name,
                    $enrollment->user->email,
                    $enrollment->module->title,
                    $enrollment->created_at->format('Y-m-d'),
                    $progress ? $progress->progress.'%' : '0%',
                    $isCompleted ? 'Yes' : 'No',
                    $isCompleted && $progress ? $progress->updated_at->format('Y-m-d') : '-',
                    $assessmentStatus ? ucfirst($assessmentStatus) : 'N/A',
                    $assessmentScore ?? 'N/A',
                    $certificate ? 'Yes' : 'No',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export assessment results (for assessment creator/admin).
     */
    public function assessmentResults(Assessment $assessment)
    {
        $user = Auth::user();

        // Check authorization
        if ($user->role !== 'admin' && $assessment->created_by !== $user->id) {
            abort(403);
        }

        $fileName = 'assessment_results_'.$assessment->title.'_'.now()->format('Y-m-d_H-i-s').'.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename={$fileName}"];

        $callback = function () use ($assessment) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header row
            fputcsv($file, [
                'Student Name',
                'Student Email',
                'Attempt',
                'Score',
                'Percentage',
                'Status',
                'Submitted At',
            ]);

            $submissions = AssessmentSubmission::where('assessment_id', $assessment->id)
                ->with('user')
                ->orderBy('attempt_number', 'asc')
                ->get();

            foreach ($submissions as $submission) {
                fputcsv($file, [
                    $submission->user->first_name.' '.$submission->user->last_name,
                    $submission->user->email,
                    $submission->attempt_number,
                    number_format($submission->score, 1),
                    number_format($submission->percentage, 1).'%',
                    ucfirst($submission->status),
                    $submission->submitted_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
