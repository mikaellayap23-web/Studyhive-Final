<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Assessments - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modules.css') }}">
    <style>
        .assessment-meta {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: #64748b;
            flex-wrap: wrap;
        }
        .assessment-meta span {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .status-published {
            background: #dcfce7;
            color: #166534;
        }
        .status-draft {
            background: #f1f5f9;
            color: #475569;
        }
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
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
                    <h1 class="page-title">Assessments</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <div class="container">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert" style="background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af;">
                        {{ session('info') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-error">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <!-- Page Header -->
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'teacher')
                    <div class="page-header">
                        <h1>Assessment Management</h1>
                        <a href="{{ route('assessments.create') }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14M5 12h14"/>
                            </svg>
                            Create Assessment
                        </a>
                    </div>
                @else
                    <div class="page-header">
                        <h1>My Assessments</h1>
                    </div>
                @endif

                <!-- Assessments Grid -->
                <div class="modules-grid">
                    @forelse($assessments as $assessment)
                        <div class="module-card">
                            <div class="module-header">
                                <div>
                                    <h3 class="module-title">{{ $assessment->title }}</h3>
                                    <div class="assessment-meta">
                                        <span>Module: {{ $assessment->module->title }}</span>
                                        <span>|</span>
                                        <span>{{ $assessment->duration_minutes }} mins</span>
                                        <span>|</span>
                                        <span>Pass: {{ $assessment->passing_score }}%</span>
                                        <span class="status-badge {{ $assessment->is_published ? 'status-published' : 'status-draft' }}">
                                            {{ $assessment->is_published ? 'Published' : 'Draft' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="module-body">
                                <p class="module-description">{{ Str::limit($assessment->description, 100) ?? 'No description' }}</p>
                                <div class="module-footer">
                                    <span class="module-date">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle;">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                            <line x1="16" y1="2" x2="16" y2="6"/>
                                            <line x1="8" y1="2" x2="8" y2="6"/>
                                            <line x1="3" y1="10" x2="21" y2="10"/>
                                        </svg>
                                        {{ $assessment->created_at->format('M d, Y') }}
                                    </span>
                                    
                                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'teacher')
                                        <div class="module-actions">
                                            <a href="{{ route('assessments.submissions', $assessment->id) }}" class="btn btn-primary btn-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                                    <polyline points="14 2 14 8 20 8"/>
                                                </svg>
                                                Submissions
                                            </a>
                                            @if($assessment->canEdit(auth()->user()))
                                                <a href="{{ route('assessments.edit', $assessment->id) }}" class="btn btn-warning btn-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                    </svg>
                                                    Edit
                                                </a>
                                                <form action="{{ route('assessments.destroy', $assessment->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this assessment?')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @else
                                        {{-- Student view --}}
                                        <a href="{{ route('assessments.take', $assessment->id) }}" class="btn btn-primary btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                                <polyline points="14 2 14 8 20 8"/>
                                            </svg>
                                            Take Assessment
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state" style="grid-column: 1 / -1;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <h3>No Assessments</h3>
                            <p>There are no assessments available at the moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
</body>
</html>
