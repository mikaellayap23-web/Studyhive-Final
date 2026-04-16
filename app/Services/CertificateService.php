<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\AssessmentSubmission;
use App\Models\Certificate;
use App\Models\Module;
use App\Models\ModuleProgress;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CertificateService
{
    /**
     * Generate a certificate for a student who completed a module.
     * Idempotent — returns existing certificate if one already exists.
     */
    public function generateCertificate(User $user, Module $module): ?Certificate
    {
        // Return existing certificate if already issued
        $existing = Certificate::where('user_id', $user->id)
            ->where('module_id', $module->id)
            ->first();

        if ($existing) {
            return $existing;
        }

        // Verify module is actually completed
        if (!$this->isModuleCompleted($user, $module)) {
            return null;
        }

        // Gather metadata
        $metadata = [
            'student_name' => $user->first_name . ' ' . $user->last_name,
            'module_title' => $module->title,
            'completed_at' => now()->toDateTimeString(),
        ];

        // Get teacher name if assigned
        if ($module->assigned_teacher_id) {
            $teacher = User::find($module->assigned_teacher_id);
            if ($teacher) {
                $metadata['teacher_name'] = $teacher->first_name . ' ' . $teacher->last_name;
            }
        }

        // Get best passing assessment score
        if ($module->assessment) {
            $bestSubmission = AssessmentSubmission::where('assessment_id', $module->assessment->id)
                ->where('user_id', $user->id)
                ->where('status', 'passed')
                ->orderBy('percentage', 'desc')
                ->first();

            if ($bestSubmission) {
                $metadata['assessment_score'] = round($bestSubmission->percentage, 1);
                $metadata['assessment_title'] = $module->assessment->title;
            }
        }

        $certificate = Certificate::create([
            'user_id' => $user->id,
            'module_id' => $module->id,
            'certificate_number' => Certificate::generateCertificateNumber(),
            'title' => 'Certificate of Completion - ' . $module->title,
            'issue_date' => now(),
            'status' => 'issued',
            'metadata' => $metadata,
        ]);

        // Generate PDF
        $this->generatePdf($certificate);

        return $certificate;
    }

    /**
     * Generate the PDF for a certificate.
     */
    public function generatePdf(Certificate $certificate): string
    {
        $certificate->load(['user', 'module.assignedTeacher']);

        $data = [
            'certificate' => $certificate,
            'student_name' => $certificate->user->first_name . ' ' . $certificate->user->last_name,
            'module_title' => $certificate->module->title,
            'certificate_number' => $certificate->certificate_number,
            'issue_date' => $certificate->issue_date->format('F d, Y'),
            'teacher_name' => $certificate->metadata['teacher_name'] ?? null,
            'assessment_score' => $certificate->metadata['assessment_score'] ?? null,
            'verify_url' => url('/certificates/verify?certificate_number=' . $certificate->certificate_number),
        ];

        $pdf = Pdf::loadView('certificates.pdf.default', $data);
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
        ]);

        $filename = 'certificates/' . $certificate->certificate_number . '.pdf';
        Storage::disk('public')->put($filename, $pdf->output());

        $certificate->update(['pdf_path' => $filename]);

        return $filename;
    }

    /**
     * Verify a certificate by its number.
     */
    public function verifyCertificate(string $certificateNumber): ?Certificate
    {
        return Certificate::where('certificate_number', $certificateNumber)
            ->where('status', 'issued')
            ->with(['user', 'module'])
            ->first();
    }

    /**
     * Check if a student has completed a module (progress >= 100 AND assessment passed).
     */
    public function isModuleCompleted(User $user, Module $module): bool
    {
        // Check progress
        $progress = ModuleProgress::where('user_id', $user->id)
            ->where('module_id', $module->id)
            ->first();

        if (!$progress || $progress->progress < 100) {
            return false;
        }

        // Check assessment (if module has one)
        $module->load('assessment');
        if ($module->assessment) {
            $passed = AssessmentSubmission::where('assessment_id', $module->assessment->id)
                ->where('user_id', $user->id)
                ->where('status', 'passed')
                ->exists();

            if (!$passed) {
                return false;
            }
        }

        return true;
    }
}
