<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\Module;
use App\Models\ModuleProgress;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ModuleFilterService
{
    /**
     * Get modules for admin role.
     */
    public function getForAdmin(Request $request): Builder
    {
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

        return $query->orderBy('order')->orderBy('created_at', 'desc');
    }

    /**
     * Get modules for teacher role (assigned or created).
     */
    public function getForTeacher(Request $request, User $teacher): Builder
    {
        $query = Module::withoutTrashed()
            ->with(['user', 'assignedTeacher'])
            ->where(function ($q) use ($teacher) {
                $q->where('assigned_teacher_id', $teacher->id)
                    ->orWhere('user_id', $teacher->id);
            });

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $query->orderBy('order')->orderBy('created_at', 'desc');
    }

    /**
     * Get all modules for teacher (view all, manage only own).
     */
    public function getAllForTeacher(Request $request): Builder
    {
        $query = Module::withoutTrashed()->with(['user', 'assignedTeacher']);

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $query->orderBy('order')->orderBy('created_at', 'desc');
    }

    /**
     * Get published modules for student role.
     */
    public function getForStudent(Request $request, User $student): Collection
    {
        $query = Module::withoutTrashed()
            ->with(['user', 'assignedTeacher', 'assessment.submissions' => function ($q) use ($student) {
                $q->where('user_id', $student->id);
            }])
            ->where('status', 'published');

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }
        if ($request->filled('teacher')) {
            $query->where('assigned_teacher_id', $request->teacher);
        }

        $modules = $query->orderBy('order')->orderBy('created_at', 'desc')->get();

        // Attach enrollment and completion status
        $moduleIds = $modules->pluck('id');
        $enrolledModuleIds = Enrollment::where('user_id', $student->id)
            ->whereIn('module_id', $moduleIds)
            ->pluck('module_id')
            ->flip();
        $progressByModule = ModuleProgress::where('user_id', $student->id)
            ->whereIn('module_id', $moduleIds)
            ->get()
            ->keyBy('module_id');

        $modules->each(function ($module) use ($enrolledModuleIds, $progressByModule) {
            $module->is_enrolled = $enrolledModuleIds->has($module->id);
            $progress = $progressByModule->get($module->id);
            $progressComplete = $progress && $progress->progress >= 100;
            $assessmentPassed = true;
            if ($module->assessment) {
                $assessmentPassed = $module->assessment->submissions->where('status', 'passed')->isNotEmpty();
            }
            $module->is_completed = $progressComplete && $assessmentPassed;
        });

        return $modules;
    }

    /**
     * Get enrolled modules for student.
     */
    public function getEnrolledForStudent(Request $request, User $student): Builder
    {
        $query = Module::withoutTrashed()
            ->with(['user', 'assignedTeacher'])
            ->where('status', 'published')
            ->whereHas('enrollments', function ($q) use ($student) {
                $q->where('user_id', $student->id);
            });

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        return $query->orderBy('order')->orderBy('created_at', 'desc');
    }
}
