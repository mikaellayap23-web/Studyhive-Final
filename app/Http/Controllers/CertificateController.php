<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function __construct(private CertificateService $certificateService)
    {
    }

    /**
     * Student: list their certificates.
     */
    public function index()
    {
        $certificates = Certificate::where('user_id', Auth::id())
            ->with('module')
            ->orderBy('issue_date', 'desc')
            ->get();

        return view('certificates.index', compact('certificates'));
    }

    /**
     * View a single certificate.
     */
    public function show(Certificate $certificate)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && $certificate->user_id !== $user->id) {
            abort(403);
        }

        $certificate->load(['user', 'module.assignedTeacher']);

        return view('certificates.show', compact('certificate'));
    }

    /**
     * Download certificate PDF.
     */
    public function download(Certificate $certificate)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && $certificate->user_id !== $user->id) {
            abort(403);
        }

        // Regenerate PDF if missing
        if (!$certificate->pdf_path || !Storage::disk('public')->exists($certificate->pdf_path)) {
            $this->certificateService->generatePdf($certificate);
            $certificate->refresh();
        }

        return Storage::disk('public')->download(
            $certificate->pdf_path,
            $certificate->certificate_number . '.pdf'
        );
    }

    /**
     * Public: verify certificate by number.
     */
    public function verify(Request $request)
    {
        $certificate = null;
        $searched = false;

        if ($request->filled('certificate_number')) {
            $searched = true;
            $certificate = $this->certificateService->verifyCertificate($request->certificate_number);
        }

        return view('certificates.verify', compact('certificate', 'searched'));
    }

    /**
     * Admin: list all certificates.
     */
    public function adminIndex(Request $request)
    {
        $query = Certificate::with(['user', 'module']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('certificate_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        $certificates = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('certificates.admin-index', compact('certificates'));
    }
}
