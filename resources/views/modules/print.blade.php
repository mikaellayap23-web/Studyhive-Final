<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $module->title }} - Module Summary - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <style>
        :root {
            primary: #3b82f6;
            text: #1e293b;
            gray: #64748b;
            border: #e2e8f0;
            success: #16a34a;
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
            border-bottom: 3px solid #3b82f6;
        }
        .header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .header p {
            color: #64748b;
            font-size: 0.875rem;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .info-card {
            padding: 1rem;
            border: 1px solid #e2e8e0;
            border-radius: 8px;
        }
        .info-card h3 {
            font-size: 0.875rem;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }
        .info-card p {
            font-size: 1rem;
            color: #1e293b;
        }
        .section {
            margin-bottom: 2rem;
        }
        .section h2 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8e0;
        }
        .progress-bar {
            height: 12px;
            background: #e2e8e0;
            border-radius: 6px;
            overflow: hidden;
            margin: 1rem 0;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3b82f6, #10b981);
            transition: width 0.3s;
        }
        .status-badge {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-published { background: #dcfce7; color: #166534; }
        .status-draft { background: #fef3c7; color: #92400e; }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-incomplete { background: #fee2e2; color: #991b1b; }
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
            .info-card {
                break-inside: avoid;
            }
            .section {
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

    <div class="header">
        <h1>{{ $module->title }}</h1>
        <p>Module Summary • Generated on {{ now()->format('F j, Y') }}</p>
    </div>

    <div class="info-grid">
        <div class="info-card">
            <h3>Status</h3>
            <p>
                @if($module->status === 'published')
                    <span class="status-badge status-published">Published</span>
                @else
                    <span class="status-badge status-draft">Draft</span>
                @endif
            </p>
        </div>
        <div class="info-card">
            <h3>Created By</h3>
            <p>{{ $module->user->first_name }} {{ $module->user->last_name }}</p>
        </div>
        @if($module->assignedTeacher)
        <div class="info-card">
            <h3>Assigned Teacher</h3>
            <p>{{ $module->assignedTeacher->first_name }} {{ $module->assignedTeacher->last_name }}</p>
        </div>
        @endif
        <div class="info-card">
            <h3>Order</h3>
            <p>{{ $module->order }}</p>
        </div>
        <div class="info-card">
            <h3>Created Date</h3>
            <p>{{ $module->created_at->format('F j, Y') }}</p>
        </div>
        <div class="info-card">
            <h3>Your Progress</h3>
            <p>
                @if($enrollment)
                    @if($progress && $progress->progress >= 100)
                        <span class="status-badge status-completed">Completed ({{ $progress->progress }}%)</span>
                    @else
                        <span class="status-badge status-incomplete">{{ $progress ? $progress->progress : 0 }}% Complete</span>
                    @endif
                @else
                    <span style="color: #64748b;">Not Enrolled</span>
                @endif
            </p>
        </div>
    </div>

    <div class="section">
        <h2>Description</h2>
        <p>{{ $module->description ?? 'No description available.' }}</p>
    </div>

    @if($module->content)
    <div class="section">
        <h2>Learning Content</h2>
        <p>{{ $module->content }}</p>
    </div>
    @endif

    @if($module->file_path)
    <div class="section">
        <h2>Module Materials</h2>
        <p>📄 {{ basename($module->file_path) }}</p>
    </div>
    @endif

    @if($module->assessment)
    <div class="section">
        <h2>Assessment Information</h2>
        <p><strong>Title:</strong> {{ $module->assessment->title }}</p>
        @if($module->assessment->description)
            <p><strong>Description:</strong> {{ $module->assessment->description }}</p>
        @endif
        <p><strong>Duration:</strong> {{ $module->assessment->duration_minutes }} minutes</p>
        <p><strong>Passing Score:</strong> {{ $module->assessment->passing_score }}%</p>
        <p><strong>Questions:</strong> {{ count($module->assessment->questions) }}</p>
        <p><strong>Max Attempts:</strong> {{ $module->assessment->max_attempts }}</p>
        
        @if($enrollment)
            @php
                $submission = $module->assessment->submissions->first();
            @endphp
            @if($submission)
                <div style="margin-top: 1rem; padding: 1rem; background: #f8fafc; border-radius: 8px;">
                    <h3 style="font-size: 1rem; margin-bottom: 0.5rem;">Your Submission</h3>
                    <p><strong>Score:</strong> {{ number_format($submission->percentage, 1) }}% ({{ $submission->status }})</p>
                    <p><strong>Attempt:</strong> {{ $submission->attempt_number }}</p>
                    <p><strong>Date:</strong> {{ $submission->submitted_at->format('F j, Y g:i A') }}</p>
                </div>
            @endif
        @endif
    </div>
    @endif

    <div class="footer">
        <p>This document was generated by Studyhive LMS • TESDA CSS NC II</p>
    </div>
</body>
</html>