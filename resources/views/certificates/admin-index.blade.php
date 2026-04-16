<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Certificates - Admin - Studyhive</title>
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
                    <h1 class="page-title">Certificate Management</h1>
                </div>
            </div>
        </header>

        <main>
            <div class="container">
                <div class="page-header">
                    <h1>All Certificates</h1>
                </div>

                <form method="GET" action="{{ route('admin.certificates.index') }}" style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem; align-items: flex-end;">
                    <div class="form-group" style="margin-bottom: 0; flex: 1;">
                        <label for="search" style="font-size: 0.8rem; margin-bottom: 0.25rem;">Search</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Search by name or certificate number" style="padding: 0.5rem 0.75rem; border: 1px solid #dfe3e8; border-radius: 6px; font-size: 0.875rem; font-family: inherit; width: 100%;">
                    </div>
                    <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
                    @if(request('search'))
                        <a href="{{ route('admin.certificates.index') }}" class="btn btn-secondary btn-sm">Clear</a>
                    @endif
                </form>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Certificate No.</th>
                                        <th>Student</th>
                                        <th>Module</th>
                                        <th>Issue Date</th>
                                        <th>Score</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($certificates as $certificate)
                                        <tr>
                                            <td style="font-family: monospace; font-size: 0.8rem;">{{ $certificate->certificate_number }}</td>
                                            <td>{{ $certificate->user->first_name }} {{ $certificate->user->last_name }}</td>
                                            <td>{{ $certificate->module->title }}</td>
                                            <td>{{ $certificate->issue_date->format('M d, Y') }}</td>
                                            <td>{{ $certificate->metadata['assessment_score'] ?? '-' }}{{ ($certificate->metadata['assessment_score'] ?? false) ? '%' : '' }}</td>
                                            <td>
                                                <a href="{{ route('certificates.download', $certificate) }}" class="btn btn-primary btn-sm" style="font-size: 0.75rem;">Download</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">
                                                <div class="empty-state">
                                                    <p>No certificates issued yet.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($certificates->hasPages())
                            <div style="margin-top: 1rem; padding: 0 1rem 1rem;">
                                {{ $certificates->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
