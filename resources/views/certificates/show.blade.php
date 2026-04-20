<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Certificate - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
</head>
<body>
    <x-sidebar />

    <div class="main-content">
        <header>
            <div class="container">
                <div class="header-content">
                    <h1 style="font-size: 1.25rem; font-weight: 600; color: #2d3748;">Certificate Details</h1>
                </div>
            </div>
        </header>

        <main>
            <div class="container">
                <div style="margin-bottom: 1.5rem;">
                    <a href="{{ route('certificates.index') }}" style="color: #2d5a3d; text-decoration: none; font-size: 0.9rem;">
                        &larr; Back to My Certificates
                    </a>
                </div>

                <div class="profile-card" style="max-width: 700px;">
                    <div class="profile-card-header">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                        <h2>{{ $certificate->title }}</h2>
                    </div>

                    <div class="info-grid">
                        <div class="info-item">
                            <label>Certificate Number</label>
                            <span style="font-family: monospace;">{{ $certificate->certificate_number }}</span>
                        </div>
                        <div class="info-item">
                            <label>Status</label>
                            <span class="status-badge status-active">Issued</span>
                        </div>
                        <div class="info-item">
                            <label>Student</label>
                            <span>{{ $certificate->user->first_name }} {{ $certificate->user->last_name }}</span>
                        </div>
                        <div class="info-item">
                            <label>Module</label>
                            <span>{{ $certificate->module->title }}</span>
                        </div>
                        <div class="info-item">
                            <label>Issue Date</label>
                            <span>{{ $certificate->issue_date->format('M d, Y') }}</span>
                        </div>
                        @if($certificate->metadata['assessment_score'] ?? null)
                        <div class="info-item">
                            <label>Assessment Score</label>
                            <span>{{ $certificate->metadata['assessment_score'] }}%</span>
                        </div>
                        @endif
                        @if($certificate->metadata['teacher_name'] ?? null)
                        <div class="info-item">
                            <label>Instructor</label>
                            <span>{{ $certificate->metadata['teacher_name'] }}</span>
                        </div>
                        @endif
                    </div>

                     <div style="margin-top: 1.5rem; display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        <a href="{{ route('certificates.print', $certificate->id) }}" class="btn btn-secondary" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 0.25rem;">
                                <path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/>
                                <path d="M6 14h12v8H6z"/>
                            </svg>
                            Print
                        </a>
                        <a href="{{ route('certificates.download', $certificate) }}" style="padding: 0.625rem 1.25rem; background: #2d5a3d; color: #fff; text-decoration: none; border-radius: 8px; font-size: 0.9rem; font-weight: 500;">
                            Download PDF
                        </a>
                        <a href="{{ route('certificates.verify', ['certificate_number' => $certificate->certificate_number]) }}" target="_blank" style="padding: 0.625rem 1.25rem; background: #f8faf9; border: 1px solid #e2e8e4; color: #2d5a3d; text-decoration: none; border-radius: 8px; font-size: 0.9rem; font-weight: 500;">
                            Verify Certificate
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
