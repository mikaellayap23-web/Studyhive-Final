<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Print Summary - {{ $module->title }} - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modules.css') }}">
    <style>
        @media print {
            .no-print { display: none; }
            .print-only { display: block !important; }
        }
        .print-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            background: white;
        }
        .print-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e2e8e4;
        }
        .print-header h1 {
            color: #2d5a3d;
            margin-bottom: 0.5rem;
        }
        .print-meta {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
            font-size: 0.875rem;
            color: #718096;
        }
        .print-section {
            margin-bottom: 1.5rem;
        }
        .print-section h2 {
            font-size: 1.25rem;
            color: #2d3748;
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e2e8e4;
        }
        .print-field {
            margin-bottom: 0.75rem;
        }
        .print-label {
            font-weight: 600;
            color: #4a5568;
            display: inline-block;
            width: 180px;
        }
        .print-value {
            color: #2d3748;
        }
        .print-footer {
            margin-top: 3rem;
            text-align: center;
            font-size: 0.875rem;
            color: #a0aec0;
        }
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .status-published { background: #c6f6d5; color: #22543d; }
        .status-draft { background: #feebc8; color: #744210; }
        .status-completed { background: #c6f6d5; color: #22543d; }
        .status-incomplete { background: #fed7d7; color: #742a2a; }
    </style>
</head>
<body>
    <div class="print-container">
        <div class="print-header">
            <h1>Module Summary</h1>
            <p>{{ $module->title }}</p>
            <div class="print-meta">
                <span>Generated: {{ now()->format('F j, Y g:i A') }}</span>
                <span>Student: {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
            </div>
        </div>

        <div class="print-section">
            <h2>Module Details</h2>
            <div class="print-field">
                <span class="print-label">Title:</span>
                <span class="print-value">{{ $module->title }}</span>
            </div>
            <div class="print-field">
                <span class="print-label">Description:</span>
                <span class="print-value">{{ $module->description ?? 'N/A' }}</span>
            </div>
            <div class="print-field">
                <span class="print-label">Status:</span>
                <span class="status-badge status-{{ $module->status }}">{{ ucfirst($module->status) }}</span>
            </div>
            <div class="print-field">
                <span class="print-label">Created By:</span>
                <span class="print-value">{{ $module->user->first_name }} {{ $module->user->last_name }}</span>
            </div>
            @if($module->assignedTeacher)
            <div class="print-field">
                <span class="print-label">Assigned Teacher:</span>
                <span class="print-value">{{ $module->assignedTeacher->first_name }} {{ $module->assignedTeacher->last_name }}</span>
            </div>
            @endif
            <div class="print-field">
                <span class="print-label">Order:</span>
                <span class="print-value">{{ $module->order }}</span>
            </div>
        </div>

        @if($enrollment)
        <div class="print-section">
            <h2>Enrollment Information</h2>
            <div class="print-field">
                <span class="print-label">Enrolled On:</span>
                <span class="print-value">{{ $enrollment->created_at->format('F j, Y') }}</span>
            </div>
            @if($progress)
            <div class="print-field">
                <span class="print-label">Progress:</span>
                <span class="print-value">{{ $progress->progress }}%</span>
            </div>
            <div class="print-field">
                <span class="print-label">PDF Completion:</span>
                <span class="print-value">{{ $progress->pdf_completed ? 'Completed' : 'Incomplete' }}</span>
            </div>
            @endif
        </div>
        @endif

        @if($module->assessment)
        <div class="print-section">
            <h2>Assessment</h2>
            <div class="print-field">
                <span class="print-label">Title:</span>
                <span class="print-value">{{ $module->assessment->title }}</span>
            </div>
            <div class="print-field">
                <span class="print-label">Duration:</span>
                <span class="print-value">{{ $module->assessment->duration_minutes }} minutes</span>
            </div>
            <div class="print-field">
                <span class="print-label">Passing Score:</span>
                <span class="print-value">{{ $module->assessment->passing_score }}%</span>
            </div>
        </div>
        @endif

        <div class="print-footer no-print">
            <p>This document was generated from Studyhive &copy; {{ date('Y') }}</p>
        </div>

        <div class="print-footer print-only">
            <p>Generated on {{ now()->toDateTimeString() }}</p>
        </div>
    </div>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>