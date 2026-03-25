<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Take Assessment - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modules.css') }}">
    <style>
        .assessment-header {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .assessment-info {
            display: flex;
            gap: 2rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }
        .info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
        }
        .question-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        .question-number {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 0.5rem;
        }
        .question-text {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #1e293b;
        }
        .options-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .option-label {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .option-label:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }
        .option-label input[type="radio"] {
            width: 1.25rem;
            height: 1.25rem;
            cursor: pointer;
        }
        .option-label input[type="radio"]:checked + .option-text {
            font-weight: 600;
            color: #2563eb;
        }
        .option-label:has(input[type="radio"]:checked) {
            border-color: #2563eb;
            background: #eff6ff;
        }
        .timer {
            position: sticky;
            top: 1rem;
            background: #1e293b;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .timer.warning {
            background: #dc2626;
            animation: pulse 1s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }
        .btn-submit {
            background: #2563eb;
            color: white;
            border: none;
            padding: 0.875rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-submit:hover {
            background: #1d4ed8;
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
                    <h1 class="page-title">{{ $assessment->title }}</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <div class="container">
                <!-- Timer -->
                <div class="timer" id="timer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 0.5rem;">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <span id="time-remaining">{{ gmdate('i:s', $assessment->duration_minutes * 60) }}</span>
                </div>

                <!-- Assessment Info -->
                <div class="assessment-header">
                    <h2 style="margin: 0 0 0.5rem 0;">{{ $assessment->title }}</h2>
                    @if($assessment->description)
                        <p style="color: #64748b; margin: 0 0 1rem 0;">{{ $assessment->description }}</p>
                    @endif
                    
                    <!-- Attempt Information -->
                    @php
                        $attemptNumber = $latestSubmission ? $latestSubmission->attempt_number + 1 : 1;
                        $remainingAttempts = $assessment->getRemainingAttempts(auth()->user());
                        $hasUnlimitedAttempts = $remainingAttempts === -1;
                    @endphp
                    
                    <div style="background: #fef3c7; border: 1px solid #fcd34d; border-radius: 8px; padding: 1rem; margin-bottom: 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; color: #92400e; font-weight: 600; margin-bottom: 0.5rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="16" x2="12" y2="12"/>
                                <line x1="12" y1="8" x2="12.01" y2="8"/>
                            </svg>
                            Attempt {{ $attemptNumber }}
                            @if(!$hasUnlimitedAttempts)
                                <span style="color: #92400e; font-weight: normal;">of {{ $assessment->max_attempts }}</span>
                            @else
                                <span style="color: #92400e; font-weight: normal;">(Unlimited Attempts)</span>
                            @endif
                        </div>
                        @if($latestSubmission)
                            <div style="font-size: 0.875rem; color: #92400e;">
                                <strong>Previous Score:</strong> {{ number_format($latestSubmission->percentage, 1) }}% 
                                ({{ $latestSubmission->status === 'passed' ? '✓ Passed' : '✗ Failed' }})
                            </div>
                        @endif
                        @if(!$hasUnlimitedAttempts && $remainingAttempts > 0)
                            <div style="font-size: 0.875rem; color: #92400e; margin-top: 0.5rem;">
                                <strong>Attempts Remaining:</strong> {{ $remainingAttempts }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="assessment-info">
                        <div class="info-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                            <span>Duration: {{ $assessment->duration_minutes }} minutes</span>
                        </div>
                        <div class="info-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                            <span>Passing Score: {{ $assessment->passing_score }}%</span>
                        </div>
                        <div class="info-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                            </svg>
                            <span>{{ count($assessment->questions) }} Questions</span>
                        </div>
                    </div>
                </div>

                <!-- Assessment Form -->
                <form action="{{ route('assessments.submit', $assessment->id) }}" method="POST" id="assessment-form">
                    @csrf
                    
                    <div id="questions-container">
                        @foreach($assessment->questions as $index => $question)
                            <div class="question-card">
                                <div class="question-number">Question {{ $index + 1 }} ({{ $question['points'] ?? 1 }} point{{ (isset($question['points']) && $question['points'] != 1) ? 's' : '' }})</div>
                                <div class="question-text">{{ $question['text'] }}</div>
                                <div class="options-list">
                                    @foreach($question['options'] as $optionIndex => $option)
                                        <label class="option-label">
                                            <input type="radio" name="answers[{{ $question['id'] }}]" value="{{ $optionIndex }}" required>
                                            <span class="option-text">{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="if(confirm('Are you sure you want to leave? Your progress will be lost.')) window.location.href='{{ route('modules.my') }}'">Cancel</button>
                        <button type="submit" class="btn-submit" onclick="return confirm('Are you sure you want to submit your answers?')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 0.5rem;">
                                <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                            Submit Assessment
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        // Timer functionality
        let duration = {{ $assessment->duration_minutes * 60 }};
        const timerElement = document.getElementById('time-remaining');
        const timerContainer = document.getElementById('timer');
        
        const timer = setInterval(function() {
            duration--;
            
            const minutes = Math.floor(duration / 60);
            const seconds = duration % 60;
            
            timerElement.textContent = 
                (minutes < 10 ? '0' : '') + minutes + ':' + 
                (seconds < 10 ? '0' : '') + seconds;
            
            // Warning when less than 5 minutes
            if (duration < 300) {
                timerContainer.classList.add('warning');
            }
            
            if (duration <= 0) {
                clearInterval(timer);
                alert('Time is up! Your assessment will be submitted automatically.');
                document.getElementById('assessment-form').submit();
            }
        }, 1000);
        
        // Warn before leaving
        window.addEventListener('beforeunload', function(e) {
            if (duration > 0) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    </script>
</body>
</html>
