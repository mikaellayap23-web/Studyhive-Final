<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Audit Trail - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user_management.css') }}">
</head>
<body>
    <x-sidebar />

    <div class="main-content">
        <header>
            <div class="container">
                <div class="header-content">
                    <h1 class="page-title">Audit Trail</h1>
                </div>
            </div>
        </header>

        <main>
            <div class="container">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="page-header">
                    <h1>Audit Trail</h1>
                </div>

                @if(count($entries) > 0)
                    <div class="card">
                        <div class="card-header">
                            <h2>Admin Activity Log</h2>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Timestamp</th>
                                            <th>Admin</th>
                                            <th>Action</th>
                                            <th>Target</th>
                                            <th>Details</th>
                                            <th>IP Address</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($entries as $entry)
                                            <tr>
                                                <td style="white-space: nowrap;">{{ $entry['timestamp'] }}</td>
                                                <td>
                                                    {{ $entry['user_name'] }}
                                                    <span class="badge badge-{{ $entry['user_role'] }}">{{ ucfirst($entry['user_role']) }}</span>
                                                </td>
                                                <td>
                                                    @php
                                                        $colors = [
                                                            'created' => '#16a34a',
                                                            'updated' => '#2563eb',
                                                            'deleted' => '#dc2626',
                                                            'approved' => '#16a34a',
                                                            'rejected' => '#dc2626',
                                                        ];
                                                        $color = $colors[strtolower($entry['action'])] ?? '#4a5568';
                                                    @endphp
                                                    <span style="color: {{ $color }}; font-weight: 600; text-transform: capitalize;">
                                                        {{ $entry['action'] }}
                                                    </span>
                                                </td>
                                                <td>{{ $entry['target'] }}</td>
                                                <td>{{ $entry['details'] }}</td>
                                                <td style="font-family: monospace; font-size: 0.8rem;">{{ $entry['ip_address'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3>No Audit Entries</h3>
                        <p>Admin activity will appear here once actions are taken.</p>
                    </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>
