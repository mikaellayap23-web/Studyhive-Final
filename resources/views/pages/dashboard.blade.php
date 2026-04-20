<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
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
                    <h1 style="font-size: 1.25rem; font-weight: 600; color: #2d3748;">Dashboard</h1>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main>
            <div class="container">
                <!-- Welcome Section -->
                <div class="welcome-section">
                    <h1>Welcome, {{ auth()->user()->first_name }}!</h1>
                    <p>Here's what's happening with your account today.</p>
                    <span class="status-badge status-{{ auth()->user()->status }}">
                        {{ ucfirst(auth()->user()->status) }}
                    </span>
                </div>

                <!-- Stats Cards -->
                <div class="stats-grid">
                    @if(auth()->user()->role === 'admin')
                        <!-- Total Users -->
                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <span class="stat-label">Total Users</span>
                            </div>
                            <div class="stat-value">{{ $stats['total_users'] }}</div>
                        </div>

                        <!-- Pending Users -->
                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="stat-label">Pending Users</span>
                            </div>
                            <div class="stat-value">{{ $stats['pending_users'] }}</div>
                        </div>

                        <!-- Modules -->
                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                </div>
                                <span class="stat-label">Modules</span>
                            </div>
                            <div class="stat-value">{{ $stats['modules'] }}</div>
                        </div>

                        <!-- Announcements -->
                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                    </svg>
                                </div>
                                <span class="stat-label">Announcements</span>
                            </div>
                            <div class="stat-value">{{ $stats['announcements'] }}</div>
                        </div>

                    @elseif(auth()->user()->role === 'teacher')
                        <!-- Enrolled Students -->
                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <span class="stat-label">Enrolled Students</span>
                            </div>
                            <div class="stat-value">{{ $stats['enrolled_students'] }}</div>
                        </div>

                        <!-- Modules -->
                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                </div>
                                <span class="stat-label">Modules</span>
                            </div>
                            <div class="stat-value">{{ $stats['modules'] }}</div>
                        </div>

                        <!-- Assessments -->
                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                    </svg>
                                </div>
                                <span class="stat-label">Assessments</span>
                            </div>
                            <div class="stat-value">{{ $stats['assessments'] }}</div>
                        </div>

                        <!-- Announcements -->
                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                    </svg>
                                </div>
                                <span class="stat-label">Announcements</span>
                            </div>
                            <div class="stat-value">{{ $stats['announcements'] }}</div>
                        </div>

                    @else
                        <!-- Total Modules -->
                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <span class="stat-label">Total Modules</span>
                            </div>
                            <div class="stat-value">{{ $stats['total_modules'] }}</div>
                        </div>

                        <!-- Enrolled Modules -->
                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="stat-label">Enrolled</span>
                            </div>
                            <div class="stat-value">{{ $stats['enrolled_modules'] }}</div>
                        </div>

                        <!-- Completed Modules -->
                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="stat-label">Completed</span>
                            </div>
                            <div class="stat-value">{{ $stats['completed_modules'] }}</div>
                        </div>

                        <!-- Announcements -->
                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                    </svg>
                                </div>
                                <span class="stat-label">Announcements</span>
                            </div>
                            <div class="stat-value">{{ $stats['announcements'] }}</div>
                        </div>
                    @endif
                </div>

                <!-- Admin Dashboard -->
                @if(auth()->user()->role === 'admin')
                    <h2 class="section-title">Audit Trail</h2>
                    <div class="card" style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                            <thead>
                                <tr style="border-bottom: 2px solid #e2e8e4;">
                                    <th style="padding: 0.75rem; text-align: left; color: #4a5568; font-weight: 600;">Date</th>
                                    <th style="padding: 0.75rem; text-align: left; color: #4a5568; font-weight: 600;">User</th>
                                    <th style="padding: 0.75rem; text-align: left; color: #4a5568; font-weight: 600;">Action</th>
                                    <th style="padding: 0.75rem; text-align: left; color: #4a5568; font-weight: 600;">Target</th>
                                    <th style="padding: 0.75rem; text-align: left; color: #4a5568; font-weight: 600;">Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $logPath = storage_path('logs/audit.log');
                                    $auditEntries = [];
                                    if (file_exists($logPath)) {
                                        $lines = file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                                        foreach ($lines as $line) {
                                            $data = json_decode($line, true);
                                            if ($data && isset($data['action'])) {
                                                $auditEntries[] = $data;
                                            }
                                        }
                                        $auditEntries = array_reverse($auditEntries);
                                    }
                                    $recentEntries = array_slice($auditEntries, 0, 10);
                                @endphp

                                @forelse($recentEntries as $entry)
                                    <tr style="border-bottom: 1px solid #f0f0f0;">
                                        <td style="padding: 0.75rem; white-space: nowrap; color: #706f6c;">{{ $entry['timestamp'] }}</td>
                                        <td style="padding: 0.75rem;">{{ $entry['user_name'] }}</td>
                                        <td style="padding: 0.75rem;">
                                            @php
                                                $colors = [
                                                    'created' => '#16a34a',
                                                    'updated' => '#2563eb',
                                                    'deleted' => '#dc2626',
                                                    'approved' => '#16a34a',
                                                    'rejected' => '#dc2626',
                                                    'soft deleted' => '#f59e0b',
                                                    'restored' => '#8b5cf6',
                                                    'permanently deleted' => '#dc2626',
                                                ];
                                                $color = $colors[strtolower($entry['action'])] ?? '#4a5568';
                                            @endphp
                                            <span style="color: {{ $color }}; font-weight: 600;">{{ $entry['action'] }}</span>
                                        </td>
                                        <td style="padding: 0.75rem;">{{ $entry['target'] }}</td>
                                        <td style="padding: 0.75rem; color: #706f6c;">{{ $entry['details'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="padding: 2rem; text-align: center; color: #706f6c;">
                                            No audit activity yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if(count($recentEntries) > 0)
                            <div style="padding: 1rem; text-align: center; border-top: 1px solid #e2e8e4;">
                                <a href="{{ route('profile') }}" class="btn btn-secondary btn-sm">View Full Activity in Profile</a>
                            </div>
                        @endif
                    </div>

                <!-- Teacher Dashboard -->
                @elseif(auth()->user()->role === 'teacher')

                <!-- Student Dashboard -->
                @else
                    <!-- Student Stats Row 1 -->
                    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                        <!-- Current Progress -->
                        <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <div class="stat-header">
                                <div class="stat-icon" style="color: white;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <span class="stat-label" style="color: rgba(255,255,255,0.9);">Overall Progress</span>
                            </div>
                            <div class="stat-value" style="font-size: 2.5rem;">{{ $stats['overall_progress'] ?? 0 }}%</div>
                            <p style="margin: 0.5rem 0 0; color: rgba(255,255,255,0.8); font-size: 0.875rem;">
                                {{ $stats['completed_modules'] }} of {{ $stats['total_modules'] }} modules completed
                            </p>
                        </div>

                        <!-- Enrolled Modules -->
                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="stat-label">Enrolled</span>
                            </div>
                            <div class="stat-value">{{ $stats['enrolled_modules'] }}</div>
                            <p style="margin: 0.5rem 0 0; color: #718096; font-size: 0.875rem;">active enrollments</p>
                        </div>

                        <!-- Completed Modules -->
                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="stat-label">Completed</span>
                            </div>
                            <div class="stat-value">{{ $stats['completed_modules'] }}</div>
                            <p style="margin: 0.5rem 0 0; color: #718096; font-size: 0.875rem;">modules finished</p>
                        </div>

                        <!-- Announcements -->
                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                    </svg>
                                </div>
                                <span class="stat-label">Announcements</span>
                            </div>
                            <div class="stat-value">{{ $stats['announcements'] }}</div>
                            <p style="margin: 0.5rem 0 0; color: #718096; font-size: 0.875rem;">total posts</p>
                        </div>
                    </div>

                    <!-- Recent Activity Section -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
                        <!-- Upcoming Assessments -->
                        @if(isset($stats['upcoming_assessments']) && count($stats['upcoming_assessments']) > 0)
                            <div class="card" style="grid-column: span 1;">
                                <div style="padding: 1rem; border-bottom: 1px solid #e2e8e4; display: flex; justify-content: space-between; align-items: center;">
                                    <h3 style="margin: 0; font-size: 1.125rem; color: #2d3748;">Assessments Ready</h3>
                                    <span style="color: #059669; font-size: 0.875rem; font-weight: 600;">{{ count($stats['upcoming_assessments']) }}</span>
                                </div>
                                <div style="padding: 0.5rem;">
                                    @foreach($stats['upcoming_assessments'] as $assessment)
                                        <div style="padding: 0.75rem; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center;">
                                            <div>
                                                <div style="font-weight: 500; color: #2d3748;">{{ $assessment['assessment_title'] }}</div>
                                                <div style="font-size: 0.875rem; color: #718096;">{{ $assessment['module_title'] }}</div>
                                            </div>
                                            <a href="{{ route('assessments.take', $assessment['assessment_id']) }}" class="btn btn-primary btn-sm" style="padding: 0.375rem 0.75rem; font-size: 0.875rem;">
                                                Start
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Recent Grades -->
                        @if(isset($stats['recent_grades']) && count($stats['recent_grades']) > 0)
                            <div class="card" style="grid-column: span 1;">
                                <div style="padding: 1rem; border-bottom: 1px solid #e2e8e4; display: flex; justify-content: space-between; align-items: center;">
                                    <h3 style="margin: 0; font-size: 1.125rem; color: #2d3748;">Recent Results</h3>
                                    <a href="{{ route('assessments.index') }}" style="color: #3b82f6; text-decoration: none; font-size: 0.875rem;">View all →</a>
                                </div>
                                <div style="padding: 0.5rem;">
                                    @foreach($stats['recent_grades'] as $grade)
                                        <div style="padding: 0.75rem; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center;">
                                            <div>
                                                <div style="font-weight: 500; color: #2d3748;">{{ $grade['assessment_title'] }}</div>
                                                <div style="font-size: 0.875rem; color: #718096;">{{ $grade['module_title'] }} • Attempt #{{ $grade['attempt'] }}</div>
                                            </div>
                                            <div style="text-align: right;">
                                                <div style="font-size: 1.125rem; font-weight: 700; color: {{ $grade['percentage'] >= $grade['assessment_passing_score'] ?? 75 ? '#16a34a' : '#dc2626' }};">
                                                    {{ number_format($grade['percentage'], 1) }}%
                                                </div>
                                                <div style="font-size: 0.75rem; color: #718096; text-transform: capitalize;">{{ $grade['status'] }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Quick Actions -->
                        <div class="card" style="grid-column: span 1;">
                            <div style="padding: 1rem; border-bottom: 1px solid #e2e8e4;">
                                <h3 style="margin: 0; font-size: 1.125rem; color: #2d3748;">Quick Actions</h3>
                            </div>
                            <div style="padding: 0.5rem;">
                                <a href="{{ route('modules.my') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; text-decoration: none; color: #2d3748; transition: background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: #3b82f6;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <span>Continue Learning</span>
                                </a>
                                <a href="{{ route('modules.all') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; text-decoration: none; color: #2d3748; transition: background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: #10b981;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                    <span>Browse All Modules</span>
                                </a>
                                <a href="{{ route('certificates.index') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; text-decoration: none; color: #2d3748; transition: background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: #f59e0b;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9a2 2 0 10-4 0v5a2 2 0 01-2 2h6m-6-4h4m4 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <span>View Certificates</span>
                                </a>
                                <a href="{{ route('profile') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; text-decoration: none; color: #2d3748; transition: background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: #6366f1;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>Profile Settings</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Welcome Section for Students -->
                    <div style="margin-top: 2rem;">
                        <div class="welcome-section" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); padding: 1.5rem; border-radius: 8px; border-left: 4px solid #3b82f6;">
                            <h2 style="margin: 0 0 0.5rem; font-size: 1.25rem; color: #1e40af;">Welcome back, {{ auth()->user()->first_name }}!</h2>
                            <p style="margin: 0; color: #3b82f6; font-size: 0.95rem;">
                                You've completed <strong>{{ $stats['completed_modules'] }} of {{ $stats['total_modules'] }}</strong> modules ({{ $stats['overall_progress'] ?? 0 }}%).
                                @if(count($stats['upcoming_assessments'] ?? []) > 0)
                                    You have <strong>{{ count($stats['upcoming_assessments']) }}</strong> assessment{{ count($stats['upcoming_assessments']) > 1 ? 's' : '' }} waiting for you.
                                @endif
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>
