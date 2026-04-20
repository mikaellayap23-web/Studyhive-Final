<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $module->title }} - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modules.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <style>
        .module-detail {
            max-width: 900px;
            margin: 0 auto;
        }
        .module-detail-header {
            background: white;
            border: 1px solid #e2e8e4;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 1.5rem;
        }
        .module-detail-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 1rem;
        }
        .module-detail-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .module-detail-body {
            background: white;
            border: 1px solid #e2e8e4;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 1.5rem;
        }
        .module-detail-section {
            margin-bottom: 2rem;
        }
        .module-detail-section:last-child {
            margin-bottom: 0;
        }
        .module-detail-section h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.75rem;
        }
        .module-detail-section p {
            color: #4a5568;
            line-height: 1.7;
        }
        .enrollment-notice {
            background: #fffaf0;
            border: 1px solid #f6ad55;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .enrollment-notice h3 {
            color: #c05621;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .enrollment-notice p {
            color: #744210;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
        .blurred-content {
            filter: blur(4px);
            pointer-events: none;
            user-select: none;
        }
        .status-badge {
            display: inline-block;
            font-size: 0.7rem;
            padding: 0.15rem 0.5rem;
            border-radius: 9999px;
            font-weight: 600;
            text-transform: capitalize;
        }
        .status-published {
            background-color: #c6f6d5;
            color: #22543d;
        }
        .status-draft {
            background-color: #feebc8;
            color: #744210;
        }
        .pdf-viewer-container {
            position: relative;
            width: 100%;
            height: 700px;
            border: 1px solid #e2e8e4;
            border-radius: 8px;
            overflow: auto;
            background: #f8faf9;
        }
        .pdf-pages-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            gap: 20px;
        }
        .pdf-page-wrapper {
            position: relative;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 10px;
            max-width: 100%;
        }
        .pdf-page-canvas {
            width: 100%;
            height: auto;
            display: block;
        }
        .page-viewed-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #28a745;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 10;
        }
        .pdf-progress-container {
            margin-top: 15px;
            padding: 10px;
            background: #f8faf9;
            border-radius: 8px;
        }
        .pdf-progress-text {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 14px;
        }
        .pdf-progress-bar {
            height: 8px;
            background-color: #e2e8e4;
            border-radius: 4px;
            overflow: hidden;
        }
        .pdf-progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: width 0.3s ease;
        }
        .progress-card {
            background: white;
            border: 1px solid #e2e8e4;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <x-sidebar />

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header>
            <div class="container">
                <div class="header-content">
                    <h1 class="page-title">{{ $module->title }}</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <div class="container">
                <div class="module-detail">
                    <!-- Header -->
                    <div class="module-detail-header">
                        <h2 class="module-detail-title">{{ $module->title }}</h2>
                        <div class="module-detail-meta">
                            <span class="status-badge status-{{ $module->status }}">
                                {{ ucfirst($module->status) }}
                            </span>
                            <span style="color: #718096; font-size: 0.875rem;">
                                <strong>Created by:</strong> {{ $module->user->first_name }} {{ $module->user->last_name }}
                            </span>
                            @if($module->assignedTeacher)
                                <span style="color: #718096; font-size: 0.875rem;">
                                    <strong>Teacher:</strong> {{ $module->assignedTeacher->first_name }} {{ $module->assignedTeacher->last_name }}
                                </span>
                            @endif
                            <span style="color: #718096; font-size: 0.875rem;">
                                <strong>Order:</strong> {{ $module->order }}
                            </span>
                            <span style="color: #718096; font-size: 0.875rem;">
                                <strong>Published:</strong> {{ $module->created_at->format('M d, Y') }}
                            </span>
                        </div>
                        <div style="display: flex; gap: 0.75rem; margin-top: 1rem; flex-wrap: wrap;">
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'teacher')
                                @if(auth()->user()->role === 'admin' || $module->assigned_teacher_id === auth()->id())
                                    <a href="{{ route('modules.students', $module->id) }}" class="btn btn-primary btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                                            <circle cx="9" cy="7" r="4"/>
                                            <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                                            <path d="M16 3.13a4 4 0 010 7.75"/>
                                        </svg>
                                        Manage Students
                                    </a>
                                @endif
                            @endif
                            @if(auth()->user()->role === 'student')
                                @if($isEnrolled)
                                    <span style="color: #48bb78; font-size: 0.875rem; font-weight: 600; display: flex; align-items: center; gap: 0.25rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                                            <polyline points="22 4 12 14.01 9 11.01"/>
                                        </svg>
                                        Enrolled
                                    </span>
                                @elseif($moduleCompleted)
                                    <span style="color: #16a34a; font-size: 0.875rem; font-weight: 600; display: flex; align-items: center; gap: 0.25rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                                            <polyline points="22 4 12 14.01 9 11.01"/>
                                        </svg>
                                        Completed
                                    </span>
                                @else
                                    <form action="{{ route('modules.enroll', $module->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                                                <circle cx="8.5" cy="7" r="4"/>
                                                <line x1="20" y1="8" x2="20" y2="14"/>
                                                <line x1="23" y1="11" x2="17" y2="11"/>
                                            </svg>
                                            Enroll to Access
                                        </button>
                                    </form>
                                @endif
                            @endif
                            <a href="{{ auth()->user()->role === 'student' ? route('modules.all') : route('modules.index') }}" class="btn btn-secondary btn-sm">
                                Back to Modules
                            </a>
                        </div>
                    </div>

                    @if(auth()->user()->role === 'student' && !$isEnrolled && !$moduleCompleted)
                        <!-- Enrollment Notice for Non-Enrolled Students -->
                        <div class="enrollment-notice">
                            <h3>
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 0.5rem;">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="16" x2="12" y2="12"/>
                                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                                </svg>
                                Enrollment Required
                            </h3>
                            <p>You need to enroll in this module to access the full content. Enrolling is free and gives you access to all learning materials.</p>
                            <form action="{{ route('modules.enroll', $module->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    Enroll Now
                                </button>
                            </form>
                        </div>
                    @elseif(auth()->user()->role === 'student' && $moduleCompleted)
                        <!-- Completed Module Notice -->
                        <div class="enrollment-notice" style="background: #f0fdf4; border-color: #bbf7d0;">
                            <h3 style="color: #166534;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 0.5rem;">
                                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                                    <polyline points="22 4 12 14.01 9 11.01"/>
                                </svg>
                                Module Completed
                            </h3>
                            <p style="color: #15803d;">You have already completed this module. You can view the module details but materials and assessments are no longer accessible.</p>
                        </div>
                    @endif

                    <!-- Description -->
                    <div class="module-detail-body">
                        <div class="module-detail-section">
                            <h3>Description</h3>
                            <p>{{ $module->description ?? 'No description available.' }}</p>
                        </div>
                    </div>

                    <!-- Content (Full or Blurred) -->
                    <div class="module-detail-body">
                        <div class="module-detail-section">
                            <h3>Module Content</h3>
                            @if(auth()->user()->role === 'student' && !$isEnrolled)
                                <div class="blurred-content">
                                    <p>This is a preview of the module content. Enroll to access the complete learning materials, including detailed lessons, resources, and downloadable files.</p>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                </div>
                                <p style="text-align: center; color: #718096; font-size: 0.875rem; margin-top: 1rem;">
                                    🔒 Enroll to unlock full content
                                </p>
                            @else
                                <p>{{ $module->content ?? 'No content available for this module.' }}</p>
                             @endif
                         </div>
                     </div>

                     @if($module->prerequisite)
                         <div class="module-detail-body" style="background: #fef3c7; border-color: #fcd34d;">
                             <div class="module-detail-section">
                                 <h3 style="color: #92400e; display: flex; align-items: center; gap: 0.5rem;">
                                     <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                         <circle cx="12" cy="12" r="10"/>
                                         <line x1="12" y1="8" x2="12" y2="12"/>
                                         <line x1="12" y1="16" x2="12.01" y2="16"/>
                                     </svg>
                                     Prerequisite Required
                                 </h3>
                                 <p style="color: #78350f;">
                                     You must complete <strong>{{ $module->prerequisite->title }}</strong> before you can enroll in this module.
                                     @if($isEnrolled)
                                         <span style="display: block; margin-top: 0.5rem; color: #b45309;">
                                             ⚠️ You are enrolled but missing the prerequisite. Contact your instructor if you believe this is an error.
                                         </span>
                                     @endif
                                 </p>
                             </div>
                         </div>
                     @endif

                     @if(auth()->user()->role === 'student' && !$isEnrolled)
                                    <p style="color: #718096; font-size: 0.875rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 0.25rem;">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                            <path d="M7 11V7a5 5 0 0110 0v4"/>
                                        </svg>
                                        Materials are available after enrollment
                                    </p>
                                @elseif(auth()->user()->role === 'student' && $moduleCompleted)
                                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 1.5rem; text-align: center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: #16a34a; margin: 0 auto 0.75rem;">
                                            <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                                            <polyline points="22 4 12 14.01 9 11.01"/>
                                        </svg>
                                        <h4 style="margin: 0 0 0.5rem 0; color: #166534;">Module Completed</h4>
                                        <p style="color: #15803d; font-size: 0.875rem;">You have completed this module. Materials are no longer accessible.</p>
                                    </div>
                                @elseif(auth()->user()->role === 'student' && $isEnrolled)
                                    @php
                                        $fileExt = strtolower(pathinfo($module->file_path, PATHINFO_EXTENSION));
                                        $isPdf = $fileExt === 'pdf';
                                    @endphp

                                    @if($isPdf)
                                        <!-- PDF Viewer with Progress Tracking -->
                                        <div class="pdf-viewer-container" id="pdfViewerContainer">
                                            <div id="pdfPagesContainer" class="pdf-pages-container"></div>
                                        </div>

                                        <div class="pdf-progress-container" id="pdfProgressContainer">
                                            <div class="pdf-progress-text">
                                                <span>PDF Progress</span>
                                                <span id="pdfProgressText">0%</span>
                                            </div>
                                            <div class="pdf-progress-bar">
                                                <div class="pdf-progress-fill" id="pdfProgressFill" style="width: 0%;"></div>
                                            </div>
                                        </div>
                                    @else
                                        <p style="color: #718096; font-size: 0.875rem;">
                                            This module has materials in a format that is not yet supported. Please contact your teacher for access.
                                        </p>
                                    @endif
                                @else
                                    <p style="color: #718096; font-size: 0.875rem;">
                                        File: {{ basename($module->file_path) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Assessment Section -->
                    @php
                        $assessment = $module->assessment;
                    @endphp
                    
                    @if($assessment)
                        <div class="module-detail-body">
                            <div class="module-detail-section">
                                <h3>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 0.5rem;">
                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                    </svg>
                                    Assessment
                                </h3>

                                @if(auth()->user()->role === 'student' && $moduleCompleted)
                                    {{-- Completed module - hide assessment --}}
                                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 1.5rem; text-align: center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: #16a34a; margin: 0 auto 0.75rem;">
                                            <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                                            <polyline points="22 4 12 14.01 9 11.01"/>
                                        </svg>
                                        <h4 style="margin: 0 0 0.5rem 0; color: #166534;">Module Completed</h4>
                                        <p style="color: #15803d; font-size: 0.875rem;">You have already completed this assessment. Results are no longer accessible.</p>
                                    </div>
                                @elseif(auth()->user()->role === 'student' && !$isEnrolled)
                                    {{-- Show lock message for non-enrolled students --}}
                                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; text-align: center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: #94a3b8; margin: 0 auto 1rem;">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                            <path d="M7 11V7a5 5 0 0110 0v4"/>
                                        </svg>
                                        <p style="color: #475569; font-weight: 600; margin-bottom: 0.5rem;">Assessment Available</p>
                                        <p style="color: #64748b; font-size: 0.875rem; margin-bottom: 1rem;">Enroll in this module to access the assessment</p>
                                        <form action="{{ route('modules.enroll', $module->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">
                                                Enroll Now
                                            </button>
                                        </form>
                                    </div>
                                @elseif(auth()->user()->role === 'student' && $isEnrolled)
                                    {{-- Show take assessment button for enrolled students --}}
                                    @if($assessment->is_published)
                                        @php
                                            $latestSubmission = $assessment->latestSubmission(auth()->user());
                                            $canTake = $assessment->canUserTake(auth()->user());
                                            $remainingAttempts = $assessment->getRemainingAttempts(auth()->user());
                                            $hasUnlimitedAttempts = $remainingAttempts === -1;
                                            $progressPercent = $moduleProgress ? $moduleProgress->progress : 0;
                                            $canAccessAssessment = $progressPercent >= 100;
                                        @endphp

                                        @if($latestSubmission && !$canTake)
                                            {{-- Student used all attempts - show best result --}}
                                            @php
                                                $bestSubmission = $assessment->bestSubmission(auth()->user());
                                            @endphp
                                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem;">
                                                <h4 style="margin: 0 0 0.5rem 0; color: #475569;">{{ $assessment->title }}</h4>
                                                <p style="color: #64748b; font-size: 0.875rem; margin-bottom: 1rem;">You have used all your attempts for this assessment.</p>
                                                <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem; font-size: 0.875rem; color: #64748b;">
                                                    <span>📊 Best Score: <strong style="color: {{ $bestSubmission->percentage >= $assessment->passing_score ? '#16a34a' : '#dc2626' }}">{{ number_format($bestSubmission->percentage, 1) }}%</strong></span>
                                                    <span>📝 Attempts: {{ $assessment->getUserAttempts(auth()->user()) }}/{{ $assessment->max_attempts }}</span>
                                                </div>
                                                <a href="{{ route('assessments.results', $bestSubmission->id) }}" class="btn btn-secondary">
                                                    View Results
                                                </a>
                                            </div>
                                        @elseif(!$canAccessAssessment)
                                            {{-- Progress not 100% - lock assessment --}}
                                            <div style="background: #fef3c7; border: 1px solid #fcd34d; border-radius: 8px; padding: 1.5rem; text-align: center;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: #d97706; margin: 0 auto 0.75rem;">
                                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                                    <path d="M7 11V7a5 5 0 0110 0v4"/>
                                                </svg>
                                                <h4 style="margin: 0 0 0.5rem 0; color: #92400e;">Assessment Locked</h4>
                                                <p style="color: #78350f; font-size: 0.875rem; margin-bottom: 0.5rem;">Complete the module materials to unlock the assessment.</p>
                                                <p style="color: #78350f; font-size: 0.875rem; font-weight: 600;">Current Progress: {{ $progressPercent }}%</p>
                                            </div>
                                        @elseif($latestSubmission)
                                            {{-- Student has taken but can retake --}}
                                            <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 1.5rem;">
                                                <h4 style="margin: 0 0 0.5rem 0; color: #0369a1;">{{ $assessment->title }}</h4>
                                                @if($assessment->description)
                                                    <p style="color: #64748b; font-size: 0.875rem; margin-bottom: 1rem;">{{ $assessment->description }}</p>
                                                @endif
                                                <div style="background: #fef3c7; border: 1px solid #fcd34d; border-radius: 6px; padding: 0.75rem; margin-bottom: 1rem; font-size: 0.875rem; color: #92400e;">
                                                    <strong>Last Attempt:</strong> {{ number_format($latestSubmission->percentage, 1) }}%
                                                    ({{ $latestSubmission->status === 'passed' ? '✓ Passed' : '✗ Needs Improvement' }})
                                                    @if(!$hasUnlimitedAttempts)
                                                        | <strong>Attempts Remaining:</strong> {{ $remainingAttempts }}
                                                    @endif
                                                </div>
                                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                                    <a href="{{ route('assessments.take', $assessment->id) }}" class="btn btn-primary">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 0.5rem;">
                                                            <path d="M23 4v6h-6"/>
                                                            <path d="M20.49 15a9 9 0 11-2.12-9.36L23 10"/>
                                                        </svg>
                                                        Retake Assessment
                                                    </a>
                                                    <a href="{{ route('assessments.results', $latestSubmission->id) }}" class="btn btn-secondary">
                                                        View Results
                                                    </a>
                                                </div>
                                            </div>
                                        @else
                                            {{-- First attempt --}}
                                            <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 1.5rem;">
                                                <h4 style="margin: 0 0 0.5rem 0; color: #0369a1;">{{ $assessment->title }}</h4>
                                                @if($assessment->description)
                                                    <p style="color: #64748b; font-size: 0.875rem; margin-bottom: 1rem;">{{ $assessment->description }}</p>
                                                @endif
                                                <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem; font-size: 0.875rem; color: #64748b;">
                                                    <span>⏱️ {{ $assessment->duration_minutes }} minutes</span>
                                                    <span>✓ Passing: {{ $assessment->passing_score }}%</span>
                                                    <span>📝 {{ count($assessment->questions) }} questions</span>
                                                    @if(!$hasUnlimitedAttempts)
                                                        <span>📊 Max Attempts: {{ $assessment->max_attempts }}</span>
                                                    @else
                                                        <span>📊 Unlimited Attempts</span>
                                                    @endif
                                                </div>
                                                <a href="{{ route('assessments.take', $assessment->id) }}" class="btn btn-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 0.5rem;">
                                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                                        <polyline points="14 2 14 8 20 8"/>
                                                    </svg>
                                                    Take Assessment
                                                </a>
                                            </div>
                                        @endif
                                    @else
                                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; text-align: center;">
                                            <p style="color: #64748b;">Assessment is not yet available</p>
                                        </div>
                                    @endif
                                @else
                                    {{-- Show assessment info for admin/teacher --}}
                                    @if($assessment)
                                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem;">
                                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                                                <div>
                                                    <h4 style="margin: 0 0 0.5rem 0; color: #1e293b;">{{ $assessment->title }}</h4>
                                                    @if($assessment->description)
                                                        <p style="color: #64748b; font-size: 0.875rem; margin: 0 0 1rem 0;">{{ $assessment->description }}</p>
                                                    @endif
                                                    <div style="display: flex; gap: 1rem; flex-wrap: wrap; font-size: 0.875rem; color: #64748b;">
                                                        <span>⏱️ {{ $assessment->duration_minutes }} minutes</span>
                                                        <span>✓ Passing: {{ $assessment->passing_score }}%</span>
                                                        <span>📝 {{ count($assessment->questions) }} questions</span>
                                                        <span class="status-badge {{ $assessment->is_published ? 'status-published' : 'status-draft' }}" style="background: {{ $assessment->is_published ? '#dcfce7' : '#f1f5f9' }}; color: {{ $assessment->is_published ? '#166534' : '#475569' }}; padding: 0.25rem 0.5rem; border-radius: 9999px;">
                                                            {{ $assessment->is_published ? 'Published' : 'Draft' }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div style="display: flex; gap: 0.5rem;">
                                                    @if($assessment->canEdit(auth()->user()))
                                                        <a href="{{ route('modules.edit', $module->id) }}" class="btn btn-warning btn-sm">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                            </svg>
                                                            Edit Assessment
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('assessments.submissions', $assessment->id) }}" class="btn btn-primary btn-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                                            <polyline points="14 2 14 8 20 8"/>
                                                        </svg>
                                                        View Submissions
                                                    </a>
                         </div>
                     </div>
                     @if(auth()->user()->role === 'student' && $isEnrolled)
                         <div style="margin-top: 1rem; text-align: right;">
                             <a href="{{ route('modules.print', $module->id) }}" class="btn btn-secondary btn-sm" target="_blank">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 0.25rem;">
                                     <path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/>
                                     <path d="M6 14h12v8H6z"/>
                                 </svg>
                                 Print Summary
                             </a>
                         </div>
                     @endif
                 </div>
                                    @else
                                        <p style="color: #64748b; font-style: italic;">No assessment created for this module yet.</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    @if(auth()->user()->role === 'student' && $isEnrolled && $module->file_path && strtolower(pathinfo($module->file_path, PATHINFO_EXTENSION)) === 'pdf')
    <script>
        // PDF.js worker configuration
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

        // State management
        const state = {
            pdfDoc: null,
            totalPages: 0,
            viewedSet: new Set(),
            maxPageReached: 0,  // Track the furthest page reached (never decreases)
            pdfCompleted: false,
            currentPage: 1,
            moduleId: {{ $module->id }},
            enrollmentId: null,
            trackingLocked: false  // Prevent concurrent AJAX calls
        };

        // DOM Elements
        const elements = {
            pagesContainer: document.getElementById('pdfPagesContainer'),
            progressText: document.getElementById('pdfProgressText'),
            progressFill: document.getElementById('pdfProgressFill'),
            progressContainer: document.getElementById('pdfProgressContainer')
        };

        // Load existing progress
        fetch(`{{ route('modules.progress', $module->id) }}`, {
            headers: {
                'Accept': 'application/json',
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.enrolled) {
                    state.pdfCompleted = data.pdf_completed;
                    state.totalPages = data.pdf_total_pages;
                    state.maxPageReached = data.pdf_current_page || 0;

                    // Update progress display based on max page reached
                    updateProgress(state.maxPageReached, data.pdf_total_pages);

                    if (data.pdf_completed) {
                        showCompletionMessage();
                    }
                }
            })
            .catch(err => console.error('Error loading progress:', err));

        // Load PDF
        if (elements.pagesContainer) {
            const pdfUrl = '{{ asset('storage/' . $module->file_path) }}';
            console.log('Loading PDF from URL:', pdfUrl);
            
            pdfjsLib.getDocument(pdfUrl).promise
                .then(function(pdf) {
                    state.pdfDoc = pdf;
                    state.totalPages = pdf.numPages;
                    console.log('PDF loaded successfully, total pages:', state.totalPages);
                    
                    // Render first 3 pages immediately
                    for (let num = 1; num <= Math.min(3, pdf.numPages); num++) {
                        renderPage(num);
                    }

                    // Render remaining pages after a delay
                    setTimeout(() => {
                        for (let num = 4; num <= pdf.numPages; num++) {
                            renderPage(num);
                        }
                    }, 500);

                    // Start checking visible pages
                    setTimeout(checkVisiblePages, 1000);
                })
                .catch(function(error) {
                    console.error('Error loading PDF:', error);
                    elements.pagesContainer.innerHTML = `<div class="alert alert-danger m-3">
                        <strong>Error loading PDF:</strong> ${error.message || 'Unknown error'}<br>
                        <small>Please check the browser console for more details or try downloading the file instead.</small>
                    </div>`;
                });
        }

        // Render a single page
        function renderPage(num) {
            if (!state.pdfDoc) return;

            state.pdfDoc.getPage(num).then(function(page) {
                const container = document.getElementById('pdfViewerContainer');
                const containerWidth = container ? container.clientWidth - 60 : 800;
                const viewport = page.getViewport({ scale: 1 });
                const scale = containerWidth / viewport.width;
                const scaledViewport = page.getViewport({ scale: scale });

                const pageWrapper = document.createElement('div');
                pageWrapper.className = 'pdf-page-wrapper';
                pageWrapper.id = `pdf-page-${num}`;

                const canvas = document.createElement('canvas');
                canvas.className = 'pdf-page-canvas';
                canvas.height = scaledViewport.height;
                canvas.width = scaledViewport.width;
                pageWrapper.appendChild(canvas);

                // Add checkmark if already viewed
                if (state.viewedSet.has(num)) {
                    const badge = document.createElement('div');
                    badge.className = 'page-viewed-badge';
                    badge.id = `page-badge-${num}`;
                    badge.innerHTML = '<i class="fas fa-check"></i>';
                    pageWrapper.appendChild(badge);
                }

                elements.pagesContainer.appendChild(pageWrapper);

                page.render({
                    canvasContext: canvas.getContext('2d'),
                    viewport: scaledViewport
                });

                // Setup intersection observer to track when page is viewed
                // Only triggers tracking ONCE - subsequent views are ignored
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        // Only track if page is visible AND hasn't been tracked yet
                        // This ensures scrolling UP doesn't affect progress
                        if (entry.isIntersecting && !state.viewedSet.has(num) && !state.pdfCompleted) {
                            trackPageView(num);

                            // Add checkmark badge (only if not already present)
                            if (!document.getElementById(`page-badge-${num}`)) {
                                const badge = document.createElement('div');
                                badge.className = 'page-viewed-badge';
                                badge.id = `page-badge-${num}`;
                                badge.innerHTML = '<i class="fas fa-check"></i>';
                                pageWrapper.appendChild(badge);
                            }
                        }
                    });
                }, { threshold: 0.5 });

                observer.observe(pageWrapper);
            });
        }

        // Track page view via AJAX (only fires ONCE per page, never re-tracks)
        function trackPageView(pageNum) {
            // If already tracked or completed, ignore
            if (state.viewedSet.has(pageNum) || state.pdfCompleted || state.trackingLocked) return;

            // Mark as tracked immediately to prevent duplicate calls
            state.viewedSet.add(pageNum);
            state.trackingLocked = true;

            // Update max page reached (only increases, never decreases)
            if (pageNum > state.maxPageReached) {
                state.maxPageReached = pageNum;
            }

            // Update UI immediately
            updateProgress(state.maxPageReached, state.totalPages);

            // Send to server
            fetch(`{{ route('modules.progress.pdf', $module->id) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    page: pageNum,
                    total_pages: state.totalPages
                })
            })
            .then(response => response.json())
            .then(data => {
                state.trackingLocked = false;
                if (data.success) {
                    // Update from server response
                    if (data.pdf_completed && !state.pdfCompleted) {
                        state.pdfCompleted = true;
                        showCompletionMessage();
                    }
                }
            })
            .catch(err => {
                state.trackingLocked = false;
                console.error('Error tracking progress:', err);
            });
        }

        // Check which pages are visible (only tracks pages that haven't been viewed yet)
        function checkVisiblePages() {
            if (state.pdfCompleted || !elements.pagesContainer) return;

            const container = document.getElementById('pdfViewerContainer');
            if (!container) return;

            const containerRect = container.getBoundingClientRect();

            for (let num = 1; num <= state.totalPages; num++) {
                const pageElement = document.getElementById(`pdf-page-${num}`);
                if (!pageElement) continue;

                // Skip already tracked pages
                if (state.viewedSet.has(num)) continue;

                const pageRect = pageElement.getBoundingClientRect();
                const isVisible = (
                    pageRect.top < containerRect.bottom &&
                    pageRect.bottom > containerRect.top
                );

                if (isVisible) {
                    trackPageView(num);

                    // Add badge if not present
                    if (!document.getElementById(`page-badge-${num}`)) {
                        const badge = document.createElement('div');
                        badge.className = 'page-viewed-badge';
                        badge.id = `page-badge-${num}`;
                        badge.innerHTML = '<i class="fas fa-check"></i>';
                        pageElement.appendChild(badge);
                    }
                }
            }
        }

        // Update progress display (based on maxPageReached, never decreases)
        function updateProgress(maxReached, totalPages) {
            const percent = totalPages > 0 ? Math.round((maxReached / totalPages) * 100) : 0;
            
            if (elements.progressText) {
                elements.progressText.textContent = `${maxReached}/${totalPages} pages (${percent}%)`;
            }
            if (elements.progressFill) {
                elements.progressFill.style.width = percent + '%';
            }
        }

        // Show completion message
        function showCompletionMessage() {
            const toast = document.createElement('div');
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #48bb78;
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                z-index: 9999;
                animation: slideIn 0.3s ease;
            `;
            toast.innerHTML = '<strong>🎉 Congratulations!</strong><br>You have completed viewing this module.';
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // Scroll event listener
        document.getElementById('pdfViewerContainer')?.addEventListener('scroll', function() {
            checkVisiblePages();
        });

        // Add slide-in animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        `;
        document.head.appendChild(style);
    </script>
    @endif
</body>
</html>
