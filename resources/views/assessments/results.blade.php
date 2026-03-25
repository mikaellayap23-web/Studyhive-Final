<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Assessment Results - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modules.css') }}">
    <style>
        .results-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .score-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            border: 8px solid;
        }
        .score-circle.passed {
            border-color: #10b981;
            background: #f0fdf4;
        }
        .score-circle.failed {
            border-color: #ef4444;
            background: #fef2f2;
        }
        .score-percentage {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
        }
        .score-label {
            font-size: 0.875rem;
            color: #64748b;
        }
        .status-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
        .status-passed {
            background: #dcfce7;
            color: #166534;
        }
        .status-failed {
            background: #fee2e2;
            color: #991b1b;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }
        .stat-item {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
        }
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
        }
        .stat-label {
            font-size: 0.875rem;
            color: #64748b;
            margin-top: 0.25rem;
        }
        .question-review {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        .question-review.correct {
            border-left: 4px solid #10b981;
        }
        .question-review.incorrect {
            border-left: 4px solid #ef4444;
        }
        .answer-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }
        .answer-indicator.correct {
            background: #dcfce7;
            color: #166534;
        }
        .answer-indicator.incorrect {
            background: #fee2e2;
            color: #991b1b;
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
                    <h1 class="page-title">Assessment Results</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <div class="container">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Results Summary -->
                <div class="results-card">
                    <div class="score-circle {{ $submission->isPassing() ? 'passed' : 'failed' }}">
                        <span class="score-percentage">{{ number_format($submission->percentage, 1) }}%</span>
                        <span class="score-label">Score</span>
                    </div>
                    
                    <span class="status-badge {{ $submission->status === 'passed' ? 'status-passed' : 'status-failed' }}">
                        {{ $submission->status === 'passed' ? 'PASSED' : 'FAILED' }}
                    </span>
                    
                    <p style="color: #64748b; margin-top: 1rem;">
                        Passing Score: {{ $submission->assessment->passing_score }}%
                    </p>
                    
                    <!-- Attempt Information -->
                    <div style="margin-top: 1rem; padding: 1rem; background: #f8fafc; border-radius: 8px;">
                        <div style="font-size: 0.875rem; color: #64748b; margin-bottom: 0.5rem;">
                            <strong>Attempt {{ $submission->attempt_number }}</strong>
                            @php
                                $remainingAttempts = $submission->assessment->getRemainingAttempts(auth()->user());
                                $hasUnlimitedAttempts = $remainingAttempts === -1;
                            @endphp
                            @if(!$hasUnlimitedAttempts)
                                <span>of {{ $submission->assessment->max_attempts }}</span>
                            @else
                                <span>(Unlimited Attempts)</span>
                            @endif
                        </div>
                        @if(!$hasUnlimitedAttempts)
                            <div style="font-size: 0.875rem; color: #64748b;">
                                <strong>Attempts Remaining:</strong> {{ max(0, $remainingAttempts) }}
                            </div>
                        @endif
                    </div>

                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value">{{ number_format($submission->score, 1) }}</div>
                            <div class="stat-label">Points Earned</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ number_format($submission->total_points, 1) }}</div>
                            <div class="stat-label">Total Points</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ count($submission->answers) }}</div>
                            <div class="stat-label">Questions Answered</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $submission->submitted_at->format('M d, Y h:i A') }}</div>
                            <div class="stat-label">Submitted At</div>
                        </div>
                    </div>
                </div>

                <!-- Question Review -->
                <h2 style="margin-bottom: 1rem;">Answer Review</h2>
                
                @foreach($submission->assessment->questions as $index => $question)
                    @php
                        $userAnswer = $submission->answers[$question['id']] ?? null;
                        $correctAnswer = $question['correct_answer'];
                        $isCorrect = $userAnswer == $correctAnswer;
                    @endphp
                    
                    <div class="question-review {{ $isCorrect ? 'correct' : 'incorrect' }}">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                            <div>
                                <span style="font-size: 0.875rem; color: #64748b;">Question {{ $index + 1 }}</span>
                                <h4 style="margin: 0.25rem 0;">{{ $question['text'] }}</h4>
                            </div>
                            <span style="font-size: 0.875rem; color: #64748b;">
                                ({{ $question['points'] ?? 1 }} point{{ (isset($question['points']) && $question['points'] != 1) ? 's' : '' }})
                            </span>
                        </div>
                        
                        <div style="margin-top: 1rem;">
                            <div style="margin-bottom: 0.5rem;">
                                <strong>Your Answer:</strong>
                                @if($userAnswer !== null)
                                    <span class="answer-indicator {{ $isCorrect ? 'correct' : 'incorrect' }}">
                                        {{ $isCorrect ? '✓' : '✗' }}
                                        {{ $question['options'][$userAnswer] ?? 'No answer' }}
                                    </span>
                                @else
                                    <span class="answer-indicator incorrect">No answer</span>
                                @endif
                            </div>
                            
                            @if(!$isCorrect)
                                <div>
                                    <strong>Correct Answer:</strong>
                                    <span class="answer-indicator correct">
                                        ✓ {{ $question['options'][$correctAnswer] }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

                <div style="text-align: center; margin-top: 2rem; display: flex; gap: 1rem; justify-content: center;">
                    @php
                        $canRetake = $submission->assessment->canUserTake(auth()->user());
                    @endphp
                    
                    @if($canRetake)
                        <a href="{{ route('assessments.take', $submission->assessment->id) }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 0.5rem;">
                                <path d="M23 4v6h-6"/>
                                <path d="M20.49 15a9 9 0 11-2.12-9.36L23 10"/>
                            </svg>
                            Retake Assessment
                        </a>
                    @else
                        <div style="color: #64748b; font-size: 0.875rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 0.5rem;">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="16" x2="12" y2="12"/>
                                <line x1="12" y1="8" x2="12.01" y2="8"/>
                            </svg>
                            No more attempts remaining
                        </div>
                    @endif
                    
                    <a href="{{ route('modules.my') }}" class="btn btn-secondary">Back to My Modules</a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
