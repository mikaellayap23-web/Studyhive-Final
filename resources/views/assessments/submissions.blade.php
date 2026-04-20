<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Assessment Submissions - Studyhive</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        th {
            background: #f8fafc;
            font-weight: 600;
            color: #475569;
            font-size: 0.875rem;
            text-transform: uppercase;
        }
        tr:hover {
            background: #f8fafc;
        }
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.75rem;
        }
        .status-passed {
            background: #dcfce7;
            color: #166534;
        }
        .status-failed {
            background: #fee2e2;
            color: #991b1b;
        }
        .score-cell {
            font-weight: 600;
            color: #1e293b;
        }
        .btn-view {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 0.375rem 0.75rem;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            font-size: 0.875rem;
        }
        .btn-view:hover {
            background: #2563eb;
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
                    <h1 class="page-title">Submissions</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <div class="container">
                <div class="assessment-header">
                    <h2 style="margin: 0 0 0.5rem 0;">{{ $assessment->title }}</h2>
                    <p style="color: #64748b; margin: 0;">
                        Module: {{ $assessment->module->title }} | 
                        Duration: {{ $assessment->duration_minutes }} mins | 
                        Passing Score: {{ $assessment->passing_score }}%
                    </p>
                </div>

            <div class="card" style="background: white; border: 1px solid #e2e8e4; border-radius: 8px; overflow: hidden;">
                <div style="padding: 1rem; background: #f8fafc; border-bottom: 1px solid #e2e8e4;">
                    @php
                        $totalSubmissions = $submissions->count();
                        $passedCount = $submissions->where('status', 'passed')->count();
                        $avgScore = $submissions->avg('percentage');
                        $highest = $submissions->max('percentage');
                        $lowest = $submissions->min('percentage');
                    @endphp
                    <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; font-size: 0.875rem; color: #475569;">
                        <div><strong>{{ $totalSubmissions }}</strong> Submissions</div>
                        <div><strong>{{ $passedCount }}</strong> Passed</div>
                        <div><strong>{{ number_format($avgScore, 1) }}%</strong> Average</div>
                        <div><strong>{{ number_format($highest, 1) }}%</strong> Highest</div>
                        <div><strong>{{ number_format($lowest, 1) }}%</strong> Lowest</div>
                        <div><strong>{{ $totalSubmissions > 0 ? number_format(($passedCount/$totalSubmissions)*100, 1) : 0 }}%</strong> Pass Rate</div>
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Attempt</th>
                            <th>Date Submitted</th>
                            <th>Score</th>
                            <th>Percentage</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                        <tbody>
                            @forelse($submissions as $submission)
                                <tr>
                                    <td>
                                        {{ $submission->user->first_name }} {{ $submission->user->last_name }}
                                        <br>
                                        <small style="color: #64748b;">{{ $submission->user->email }}</small>
                                    </td>
                                    <td>
                                        <span style="font-weight: 600; color: #475569;">
                                            #{{ $submission->attempt_number }}
                                        </span>
                                    </td>
                                    <td>{{ $submission->submitted_at->format('M d, Y h:i A') }}</td>
                                    <td class="score-cell">
                                        {{ number_format($submission->score, 1) }} / {{ number_format($submission->total_points, 1) }}
                                    </td>
                                    <td class="score-cell">{{ number_format($submission->percentage, 1) }}%</td>
                                    <td>
                                        <span class="status-badge {{ $submission->status === 'passed' ? 'status-passed' : 'status-failed' }}">
                                            {{ strtoupper($submission->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('assessments.results', $submission->id) }}" class="btn-view">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 3rem; color: #64748b;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="display: block; margin: 0 auto 1rem;">
                                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                            <polyline points="14 2 14 8 20 8"/>
                                        </svg>
                                        No submissions yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                 </div>

                 <div style="margin-top: 1.5rem; display: flex; gap: 0.75rem;">
                     <a href="{{ route('exports.assessment.results', $assessment) }}" class="btn btn-secondary">
                         <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 0.25rem;">
                             <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 4v12"/>
                         </svg>
                         Export to Excel
                     </a>
                     <a href="{{ route('assessments.index') }}" class="btn btn-secondary">Back to Assessments</a>
                 </div>
            </div>
        </main>
    </div>
</body>
</html>
