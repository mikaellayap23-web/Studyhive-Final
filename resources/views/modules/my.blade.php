<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Modules - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modules.css') }}">
    <style>
        .progress-bar-container {
            margin-top: 0.75rem;
            padding: 0.5rem;
            background: #f8faf9;
            border-radius: 6px;
        }
        .progress-bar-wrapper {
            height: 8px;
            background-color: #e2e8e4;
            border-radius: 4px;
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: width 0.3s ease;
        }
        .progress-text {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            color: #718096;
            margin-bottom: 0.25rem;
        }
        .completion-badge {
            display: inline-block;
            background: #48bb78;
            color: white;
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-weight: 600;
            margin-top: 0.5rem;
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
                    <h1 class="page-title">My Modules</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <div class="container">
                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info">
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
                <div class="page-header">
                    @if(auth()->user()->role === 'student')
                        <h1>My Enrolled Modules</h1>
                        <p style="color: #718096; font-size: 0.9rem;">Access your enrolled learning modules</p>
                    @else
                        <h1>My Modules</h1>
                        <div style="display: flex; gap: 0.5rem;">
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('modules.trashed') }}" class="btn btn-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Trash
                                </a>
                            @endif
                            <a href="{{ route('modules.create') }}" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 5v14M5 12h14"/>
                                </svg>
                                Add Module
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Filters -->
                <form method="GET" action="{{ route('modules.my') }}" style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem; flex-wrap: wrap; align-items: flex-end;">
                    <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 200px;">
                        <label for="filter_search" style="font-size: 0.8rem; margin-bottom: 0.25rem;">Search</label>
                        <input type="text" id="filter_search" name="search" value="{{ request('search') }}" placeholder="Search by title" style="padding: 0.5rem 0.75rem; border: 1px solid #dfe3e8; border-radius: 6px; font-size: 0.875rem; font-family: inherit; background-color: #fafbfc;">
                    </div>
                    @if(auth()->user()->role !== 'student')
                        <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 150px;">
                            <label for="filter_status" style="font-size: 0.8rem; margin-bottom: 0.25rem;">Status</label>
                            <select id="filter_status" name="status" style="padding: 0.5rem 0.75rem; border: 1px solid #dfe3e8; border-radius: 6px; font-size: 0.875rem; font-family: inherit; background-color: #fafbfc;">
                                <option value="">All Statuses</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 150px;">
                            <label for="filter_teacher" style="font-size: 0.8rem; margin-bottom: 0.25rem;">Teacher</label>
                            <select id="filter_teacher" name="teacher" style="padding: 0.5rem 0.75rem; border: 1px solid #dfe3e8; border-radius: 6px; font-size: 0.875rem; font-family: inherit; background-color: #fafbfc;">
                                <option value="">All Teachers</option>
                                @php
                                    $teachers = \App\Models\User::where('role', 'teacher')->orderBy('first_name')->get();
                                @endphp
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ request('teacher') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->first_name }} {{ $teacher->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <button type="submit" class="btn btn-secondary btn-sm" style="align-self: flex-end;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 0.25rem;">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>
                        Filter
                    </button>
                    @if(request('search') || request('status') || request('teacher'))
                        <a href="{{ route('modules.my') }}" class="btn btn-secondary btn-sm" style="align-self: flex-end;">Clear</a>
                    @endif
                </form>

                <!-- Modules Grid -->
                <div class="modules-grid">
                    @forelse($modules as $module)
                        <div class="module-card">
                            @if($module->image_path)
                                <div class="module-image">
                                    <img src="{{ asset('storage/' . $module->image_path) }}" alt="{{ $module->title }}">
                                </div>
                            @endif
                            <div class="module-header">
                                <div>
                                    <h3 class="module-title">{{ $module->title }}</h3>
                                    <div class="module-meta">
                                        <span class="order-badge">{{ $module->order }}</span>
                                        <span class="module-author">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                                                <circle cx="12" cy="7" r="4"/>
                                            </svg>
                                            {{ $module->user->first_name }} {{ $module->user->last_name }}
                                        </span>
                                        @if($module->assignedTeacher)
                                            <span class="module-teacher" title="Assigned Teacher">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                                                    <circle cx="9" cy="7" r="4"/>
                                                    <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                                                    <path d="M16 3.13a4 4 0 010 7.75"/>
                                                </svg>
                                                {{ $module->assignedTeacher->first_name }} {{ $module->assignedTeacher->last_name }}
                                            </span>
                                        @endif
                                        <span class="status-badge status-{{ $module->status }}">
                                            {{ ucfirst($module->status) }}
                                        </span>
                                        @if(auth()->user()->role === 'student')
                                            <span class="enrolled-badge" style="background-color: #48bb78; color: white; font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 9999px; font-weight: 600;">
                                                Enrolled
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="module-body">
                                <p class="module-description">{{ Str::limit($module->description, 100) }}</p>
                                
                                @if(auth()->user()->role === 'student')
                                    <div class="progress-bar-container">
                                        <div class="progress-text">
                                            <span>Progress</span>
                                            <span>{{ $module->progress }}%</span>
                                        </div>
                                        <div class="progress-bar-wrapper">
                                            <div class="progress-bar-fill" style="width: {{ $module->progress }}%;"></div>
                                        </div>
                                        @if($module->pdf_completed)
                                            <span class="completion-badge">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 0.25rem;">
                                                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                                                    <polyline points="22 4 12 14.01 9 11.01"/>
                                                </svg>
                                                Material Viewed
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                
                                <div class="module-footer">
                                    <span class="module-date">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle;">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                            <line x1="16" y1="2" x2="16" y2="6"/>
                                            <line x1="8" y1="2" x2="8" y2="6"/>
                                            <line x1="3" y1="10" x2="21" y2="10"/>
                                        </svg>
                                        {{ $module->created_at->format('M d, Y') }}
                                    </span>
                                    <div class="module-actions">
                                        @if(auth()->user()->role === 'student')
                                            <a href="{{ route('modules.show', $module->id) }}" class="btn btn-primary btn-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                    <path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                View Module
                                            </a>
                                            <form action="{{ route('modules.unenroll', $module->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline btn-sm" onclick="return confirm('Are you sure you want to unenroll from this module?')">
                                                    Unenroll
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('modules.show', $module->id) }}" class="btn btn-secondary btn-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>
                                                Preview
                                            </a>
                                            @if($module->canManage(auth()->user()))
                                                <a href="{{ route('modules.edit', $module->id) }}" class="btn btn-primary btn-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                    </svg>
                                                    Edit
                                                </a>
                                                <form action="{{ route('modules.destroy', $module->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this module? It will be moved to trash.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state" style="grid-column: 1 / -1;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            @if(auth()->user()->role === 'student')
                                <h3>No Enrolled Modules</h3>
                                <p>You haven't enrolled in any modules yet. Browse available modules to get started!</p>
                                <a href="{{ route('modules.all') }}" class="btn btn-primary" style="margin-top: 1rem;">
                                    Browse Modules
                                </a>
                            @else
                                <h3>No Modules Assigned</h3>
                                <p>You don't have any modules assigned to you yet.</p>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
</body>
</html>
