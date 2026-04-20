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
                @endif
            </div>
        </main>
    </div>
</body>
</html>
