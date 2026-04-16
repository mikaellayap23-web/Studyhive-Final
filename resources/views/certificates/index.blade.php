<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Certificates - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modules.css') }}">
</head>
<body>
    <x-sidebar />

    <div class="main-content">
        <header>
            <div class="container">
                <div class="header-content">
                    <h1 style="font-size: 1.25rem; font-weight: 600; color: #2d3748;">My Certificates</h1>
                </div>
            </div>
        </header>

        <main>
            <div class="container">
                <div class="page-header">
                    <h1>My Certificates</h1>
                </div>

                @if($certificates->count() > 0)
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.25rem;">
                        @foreach($certificates as $certificate)
                            <div style="background: #fff; border: 1px solid #e2e8e4; border-radius: 12px; padding: 1.5rem; transition: box-shadow 0.2s;">
                                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                                    <div style="width: 42px; height: 42px; background: #e8f5e9; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#2d5a3d" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 style="font-size: 1rem; font-weight: 600; color: #2d3748; margin: 0;">{{ $certificate->module->title }}</h3>
                                        <span style="font-size: 0.75rem; color: #706f6c;">{{ $certificate->certificate_number }}</span>
                                    </div>
                                </div>

                                <div style="display: flex; gap: 1rem; margin-bottom: 1rem; font-size: 0.85rem; color: #706f6c;">
                                    <span>Issued: {{ $certificate->issue_date->format('M d, Y') }}</span>
                                    @if($certificate->metadata['assessment_score'] ?? null)
                                        <span>Score: {{ $certificate->metadata['assessment_score'] }}%</span>
                                    @endif
                                </div>

                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="{{ route('certificates.show', $certificate) }}" style="flex: 1; text-align: center; padding: 0.5rem; background: #f8faf9; border: 1px solid #e2e8e4; border-radius: 6px; color: #2d5a3d; text-decoration: none; font-size: 0.85rem; font-weight: 500;">
                                        View
                                    </a>
                                    <a href="{{ route('certificates.download', $certificate) }}" style="flex: 1; text-align: center; padding: 0.5rem; background: #2d5a3d; border-radius: 6px; color: #fff; text-decoration: none; font-size: 0.85rem; font-weight: 500;">
                                        Download PDF
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 3rem; background: #fff; border: 1px solid #e2e8e4; border-radius: 12px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#c0c0c0" stroke-width="1.5" style="margin: 0 auto 1rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                        <h3 style="color: #2d3748; margin-bottom: 0.5rem;">No Certificates Yet</h3>
                        <p style="color: #706f6c;">Complete modules to earn certificates. Read all materials and pass the assessment.</p>
                    </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>
