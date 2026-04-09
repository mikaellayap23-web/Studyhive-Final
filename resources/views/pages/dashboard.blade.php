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
                    <div class="cards-grid">
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
                            $recentEntries = array_slice($auditEntries, 0, 4);
                        @endphp

                        @forelse($recentEntries as $entry)
                            <a href="{{ route('admin.audit-trail') }}" class="card" style="text-decoration: none; color: inherit;">
                                <div class="card-icon">
                                    @php
                                        $icons = [
                                            'Created' => '<path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />',
                                            'Updated' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />',
                                            'Deleted' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />',
                                            'Approved' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
                                            'Rejected' => '<path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />',
                                        ];
                                        $icon = $icons[$entry['action']] ?? '<path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />';
                                    @endphp
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        {!! $icon !!}
                                    </svg>
                                </div>
                                <h3>{{ $entry['action'] }} - {{ $entry['target'] }}</h3>
                                <p>{{ $entry['details'] }}</p>
                                <span style="font-size: 0.75rem; color: #706f6c; margin-top: 0.5rem; display: block;">{{ $entry['timestamp'] }}</span>
                            </a>
                        @empty
                            <div class="card" style="grid-column: 1 / -1; text-align: center; padding: 2rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="width: 48px; height: 48px; margin: 0 auto 1rem; opacity: 0.4;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3>No Audit Activity</h3>
                                <p>Admin activity will appear here once actions are taken.</p>
                                <a href="{{ route('admin.audit-trail') }}" class="btn btn-primary" style="margin-top: 1rem; display: inline-block; width: auto;">
                                    View Full Audit Trail
                                </a>
                            </div>
                        @endforelse

                        @if(count($recentEntries) > 0)
                            <a href="{{ route('admin.audit-trail') }}" class="card" style="text-decoration: none; color: inherit; display: flex; align-items: center; justify-content: center; background-color: #f8faf9;">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 32px; height: 32px; margin: 0 auto 0.5rem; stroke: #2d5a3d;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3>View Full Audit Trail</h3>
                                    <p>See all admin activity and logs.</p>
                                </div>
                            </a>
                        @endif
                    </div>

                <!-- Teacher Dashboard -->
                @elseif(auth()->user()->role === 'teacher')

                <!-- Student Dashboard -->
                @else
                @endif
            </div>
        </main>
    </div>
</body>
</html>
