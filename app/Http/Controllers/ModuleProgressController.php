<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Module;
use App\Models\ModuleProgress;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModuleProgressController extends Controller
{
    /**
     * Track PDF page view for a student's module enrollment.
     */
    public function trackPdfPage(Request $request, Module $module)
    {
        $user = Auth::user();

        if ($user->role !== 'student') {
            abort(403, 'Unauthorized');
        }

        // Students can only track progress on published modules
        if ($module->status !== 'published') {
            abort(403, 'Module not available');
        }

        $validated = $request->validate([
            'page' => ['required', 'integer', 'min:1'],
            'total_pages' => ['nullable', 'integer', 'min:1'],
        ]);

        // Get or create enrollment first
        $enrollment = Enrollment::firstOrCreate(
            ['user_id' => $user->id, 'module_id' => $module->id]
        );

        // Get or create progress record
        $progress = ModuleProgress::firstOrCreate(
            ['user_id' => $user->id, 'module_id' => $module->id],
            ['pdf_total_pages' => 0, 'pdf_current_page' => 0, 'pdf_completed' => false, 'progress' => 0]
        );

        // If total pages not set, save it now
        if ($progress->pdf_total_pages == 0 && ! empty($validated['total_pages'])) {
            $progress->pdf_total_pages = $validated['total_pages'];
            $progress->save();
        }

        // Update current page ONLY if this page is further than what was previously reached
        // This ensures pdf_current_page NEVER decreases (scrolling up won't affect it)
        if ($validated['page'] > $progress->pdf_current_page) {
            $progress->pdf_current_page = $validated['page'];
            $progress->save();
        }

        // Calculate progress based on maximum page reached (never decreases)
        $viewedCount = min($progress->pdf_current_page, $progress->pdf_total_pages);
        $totalPages = $progress->pdf_total_pages;

        // Check if PDF is completed
        $pdfCompleted = ($totalPages > 0 && $viewedCount >= $totalPages);

        if ($pdfCompleted && ! $progress->pdf_completed) {
            $progress->pdf_completed = true;
        }

        // Recalculate overall progress
        $this->updateProgress($progress, $module);

        // Auto-issue certificate if module is now completed
        if ($progress->progress >= 100) {
            $certService = app(CertificateService::class);
            $user = Auth::user();
            if ($certService->isModuleCompleted($user, $module)) {
                $certService->generateCertificate($user, $module);
            }
        }

        return response()->json([
            'success' => true,
            'viewed_count' => $viewedCount,
            'total_pages' => $totalPages,
            'pdf_completed' => $progress->pdf_completed,
            'progress' => $progress->progress,
        ]);
    }

    /**
     * Update overall module progress for a progress record.
     */
    protected function updateProgress(ModuleProgress $progress, Module $module)
    {
        $totalMaterials = 0;
        $completedMaterials = 0;

        // Check PDF (module has a file)
        if ($module->file_path) {
            $totalMaterials++;
            if ($progress->pdf_completed) {
                $completedMaterials++;
            }
        }

        // Calculate progress percentage
        $progressPercentage = $totalMaterials > 0 ? round(($completedMaterials / $totalMaterials) * 100) : 0;

        $progress->progress = $progressPercentage;
        $progress->save();
    }

    /**
     * Get current progress for a module.
     */
    public function getProgress(Module $module)
    {
        $user = Auth::user();

        if ($user->role !== 'student') {
            abort(403, 'Unauthorized');
        }

        $progress = ModuleProgress::where('user_id', $user->id)
            ->where('module_id', $module->id)
            ->first();

        if (! $progress) {
            return response()->json([
                'success' => true,
                'enrolled' => false,
                'progress' => 0,
                'pdf_completed' => false,
                'pdf_total_pages' => 0,
                'pdf_current_page' => 0,
            ]);
        }

        return response()->json([
            'success' => true,
            'enrolled' => true,
            'progress' => $progress->progress,
            'pdf_completed' => $progress->pdf_completed,
            'pdf_total_pages' => $progress->pdf_total_pages,
            'pdf_current_page' => $progress->pdf_current_page,
            'viewed_pages' => $progress->pdf_current_page,
        ]);
    }
}
