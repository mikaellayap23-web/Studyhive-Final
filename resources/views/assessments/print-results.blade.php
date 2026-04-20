<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Assessment Results - {{ $submission->assessment->title }} - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <style>
        :root {
            primary: #3b82f6;
            text: #1e293b;
            gray: #64748b;
            border: #e2e8e0;
            success: #16a34a;
            danger: #dc2626;
            warning: #f59e0b;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #1e293b;
            background: white;
            padding: 2rem;
            max-width: 900px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 3px solid;
        }
        .header.passed { border-color: #16a34a; }
        .header.failed { border-color: #dc2626; }
        .header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .header.passed h1 { color: #166534; }
        .header.failed h1 { color: #991b1b; }
        .header p {
            color: #64748b;
            font-size: 0.875rem;
        }
        .print-actions {
            position: fixed;
            top: 1rem;
            right: 1rem;
            display: flex;
            gap: 0.5rem;
        }
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            cursor: pointer;
            border: none;
        }
        .btn-primary {
            background: #3b82f6;
            color: white;
        }
        .btn-secondary {
            background: #e2e8e0;
            color: #1e293b;
        }
        .card {
            background: white;
            border: 1px solid #e2e8e0;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .score-display {
            text-align: center;
        }
        .score-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            border: 6px solid;
        }
        .score-circle.passed {
            border-color: #16a34a;
            background: #f0fdf4;
        }
        .score-circle.failed {
            border-color: #dc2626;
            background: #fef2f2;
        }
        .score-percentage {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
        }
        .status-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.875rem;
        }
        .status-passed {
            background: #dcfce7;
            color: #166534;
        }
        .status-failed {
            background: #fee2e2;
            color: #991b1b;
        }
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        .stats-table th,
        .stats-table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e2e8e0;
        }
        .stats-table th {
            background: #f8fafc;
            font-weight: 600;
            color: #64748b;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .question-review {
            border: 1px solid #e2e8e0;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        .question-review.correct {
            border-left: 4px solid #16a34a;
        }
        .question-review.incorrect {
            border-left: 4px solid #dc2626;
        }
        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }
        .question-number {
            font-size: 0.875rem;
            color: #64748b;
            font-weight: 600;
        }
        .question-text {
            font-weight: 500;
            color: #1e293b;
        }
        .points {
            font-size: 0.875rem;
            color: #64748b;
        }
        .answer-box {
            padding: 0.75rem;
            border-radius: 6px;
            margin-top: 0.5rem;
        }
        .answer-box.correct {
            background: #f0fdf4;
            color: #166534;
        }
        .answer-box.incorrect {
            background: #fef2f2;
            color: #991b1b;
        }
        .footer {
            margin-top: 3rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8e0;
            text-align: center;
            font-size: 0.875rem;
            color: #64748b;
        }
        @media print {
            .print-actions {
                display: none;
            }
            body {
                padding: 0;
            }
            .card {
                break-inside: avoid;
            }
            .question-review {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="print-actions">
        <button onclick="window.print()" class="btn btn-primary">Print</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="header {{ $submission->isPassing() ? 'passed' : 'failed' }}">
        <h1>{{ $submission->isPassing() ? 'Assessment Passed' : 'Assessment Results' }}</h1>
        <p>{{ $submission->assessment->title }} • {{ $submission->submitted_at->format('F j, Y') }}</p>
    </div>

    <div class="card score-display">
        <div class="score-circle {{ $submission->isPassing() ? 'passed' : 'failed' }}">
            <span class="score-percentage">{{ number_format($submission->percentage, 1) }}%</span>
        </div>
        <span class="status-badge {{ $submission->status === 'passed' ? 'status-passed' : 'status-failed' }}">
            {{ strtoupper($submission->status) }}
        </span>
        <p style="margin-top: 0.5rem; color: #64748b;">
            Passing Score: {{ $submission->assessment->passing_score }}%
        </p>
    </div>

    <div class="card">
        <h2 style="font-size: 1.125rem; margin-bottom: 1rem;">Statistics</h2>
        <table class="stats-table">
            <tr>
                <th>Score</th>
                <td>{{ number_format($submission->score, 1) }} / {{ number_format($submission->total_points, 1) }} points</td>
            </tr>
            <tr>
                <th>Questions</th>
                <td>{{ count($submission->answers) }} answered</td>
            </tr>
            <tr>
                <th>Attempt</th>
                <td>#{{ $submission->attempt_number }} of {{ $submission->assessment->max_attempts == 0 ? 'Unlimited' : $submission->assessment->max_attempts }}</td>
            </tr>
            <tr>
                <th>Module</th>
                <td>{{ $submission->assessment->module->title }}</td>
            </tr>
            <tr>
                <th>Submitted</th>
                <td>{{ $submission->submitted_at->format('F j, Y g:i A') }}</td>
            </tr>
        </table>
    </div>

    <div class="card">
        <h2 style="font-size: 1.125rem; margin-bottom: 1rem;">Answer Review</h2>
        
        @php
            $hasUsedAllAttempts = !$submission->assessment->canUserTake($submission->user);
            $showCorrectAnswers = $submission->assessment->show_correct_answer && $hasUsedAllAttempts;
        @endphp

        @foreach($submission->assessment->questions as $index => $question)
            @php
                $userAnswer = $submission->answers[$question['id']] ?? null;
                $correctAnswer = $question['correct_answer'];
                $isCorrect = $userAnswer == $correctAnswer;
            @endphp

            <div class="question-review {{ $isCorrect ? 'correct' : 'incorrect' }}">
                <div class="question-header">
                    <div class="question-number">Question {{ $index + 1 }}</div>
                    @if(isset($question['points']))
                        <div class="points">{{ $question['points'] }} point{{ $question['points'] != 1 ? 's' : '' }}</div>
                    @endif
                </div>
                <div class="question-text">{{ $question['text'] }}</div>
                <div style="margin-top: 0.75rem;">
                    <div class="answer-box {{ $isCorrect ? 'correct' : 'incorrect' }}">
                        <strong>Your Answer:</strong> 
                        @if($userAnswer !== null)
                            {{ $userAnswer !== '' ? $question['options'][$userAnswer] ?? 'N/A' : 'No answer' }}
                            @if($isCorrect)
                                ✓
                            @else
                                ✗
                            @endif
                        @else
                            No answer
                        @endif
                    </div>
                    @if(!$isCorrect && $showCorrectAnswers)
                        <div class="answer-box correct" style="margin-top: 0.5rem;">
                            <strong>Correct Answer:</strong> {{ $question['options'][$correctAnswer] ?? 'N/A' }}
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="footer">
        <p>This result was generated by Studyhive LMS • TESDA CSS NC II</p>
    </div>
</body>
</html>