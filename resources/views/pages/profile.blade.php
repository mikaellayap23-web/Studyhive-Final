<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
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
                    <h1 style="font-size: 1.25rem; font-weight: 600; color: #2d3748;">Profile</h1>
                </div>
            </div>
        </header>

        <!-- Profile Content -->
        <main>
            <div class="container">
                <div class="profile-container">
                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="alert-error">
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div class="profile-cards">
                        <!-- Personal Information Card -->
                        <div class="profile-card">
                            <div class="profile-card-header">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <h2>Personal Information</h2>
                            </div>

                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf
                                @method('PUT')

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="first_name">First Name</label>
                                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', auth()->user()->first_name) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', auth()->user()->last_name) }}" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" id="username" name="username" value="{{ old('username', auth()->user()->username) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input type="email" id="email" value="{{ auth()->user()->email }}" readonly>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                    <button type="reset" class="btn btn-secondary">Cancel</button>
                                </div>
                            </form>
                        </div>

                        <!-- Account Information Card -->
                        <div class="profile-card">
                            <div class="profile-card-header">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <h2>Account Information</h2>
                            </div>

                            <div class="info-grid">
                                <div class="info-item">
                                    <label>Role</label>
                                    <span>{{ ucfirst(auth()->user()->role) }}</span>
                                </div>

                                <div class="info-item">
                                    <label>Status</label>
                                    <span class="status-badge status-{{ auth()->user()->status }}">
                                        {{ ucfirst(auth()->user()->status) }}
                                    </span>
                                </div>

                                <div class="info-item">
                                    <label>Member Since</label>
                                    <span>{{ auth()->user()->created_at->format('M d, Y') }}</span>
                                </div>

                                <div class="info-item">
                                    <label>Last Updated</label>
                                    <span>{{ auth()->user()->updated_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Change Password Card -->
                        <div class="profile-card">
                            <div class="profile-card-header">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <h2>Change Password</h2>
                            </div>

                            <form method="POST" action="{{ route('profile.password') }}">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="current_password">Current Password</label>
                                    <input type="password" id="current_password" name="current_password" required>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="new_password">New Password</label>
                                        <input type="password" id="new_password" name="new_password" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="new_password_confirmation">Confirm New Password</label>
                                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Update Password</button>
                                </div>
                            </form>
                        </div>

                        <!-- Audit Trail Card -->
                        @if(count($auditEntries) > 0)
                        <div class="profile-card">
                            <div class="profile-card-header">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h2>Activity Log</h2>
                            </div>

                            <div style="overflow-x: auto;">
                                <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                                    <thead>
                                        <tr style="border-bottom: 2px solid #e2e8e4;">
                                            <th style="padding: 0.75rem; text-align: left; color: #4a5568; font-weight: 600;">Date</th>
                                            <th style="padding: 0.75rem; text-align: left; color: #4a5568; font-weight: 600;">Action</th>
                                            <th style="padding: 0.75rem; text-align: left; color: #4a5568; font-weight: 600;">Target</th>
                                            <th style="padding: 0.75rem; text-align: left; color: #4a5568; font-weight: 600;">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($auditEntries as $entry)
                                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                                <td style="padding: 0.75rem; white-space: nowrap; color: #706f6c;">{{ $entry['timestamp'] }}</td>
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
                                                            'completed' => '#16a34a',
                                                        ];
                                                        $color = $colors[strtolower($entry['action'])] ?? '#4a5568';
                                                    @endphp
                                                    <span style="color: {{ $color }}; font-weight: 600;">{{ $entry['action'] }}</span>
                                                </td>
                                                <td style="padding: 0.75rem;">{{ $entry['target'] }}</td>
                                                <td style="padding: 0.75rem; color: #706f6c;">{{ $entry['details'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
