<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>All Modules - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modules.css') }}">
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
                    <h1 class="page-title">All Modules</h1>
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

                @if(session('info'))
                    <div class="alert alert-info">
                        {{ session('info') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
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
                    <h1>Browse Modules</h1>
                    <p style="color: #718096; font-size: 0.9rem;">Explore and enroll in available learning modules</p>
                </div>

                <!-- Filters -->
                <form method="GET" action="{{ route('modules.all') }}" style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem; flex-wrap: wrap; align-items: flex-end;">
                    <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 200px;">
                        <label for="filter_search" style="font-size: 0.8rem; margin-bottom: 0.25rem;">Search</label>
                        <input type="text" id="filter_search" name="search" value="{{ request('search') }}" placeholder="Search by title" style="padding: 0.5rem 0.75rem; border: 1px solid #dfe3e8; border-radius: 6px; font-size: 0.875rem; font-family: inherit; background-color: #fafbfc;">
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
                    <button type="submit" class="btn btn-secondary btn-sm" style="align-self: flex-end;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 0.25rem;">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>
                        Filter
                    </button>
                    @if(request('search') || request('teacher'))
                        <a href="{{ route('modules.all') }}" class="btn btn-secondary btn-sm" style="align-self: flex-end;">Clear</a>
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
                                        @if($module->is_enrolled)
                                            <span class="enrolled-badge" style="background-color: #48bb78; color: white; font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 9999px; font-weight: 600;">
                                                Enrolled
                                            </span>
                                        @endif
                                        @if($module->is_completed)
                                            <span class="enrolled-badge" style="background-color: #16a34a; color: white; font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 9999px; font-weight: 600;">
                                                Completed
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="module-body">
                                <p class="module-description">{{ Str::limit($module->description, 100) }}</p>
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
                                        <a href="{{ route('modules.show', $module->id) }}" class="btn btn-secondary btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                            Preview
                                        </a>
                                        @if(!$module->is_enrolled && !$module->is_completed)
                                            <form action="{{ route('modules.enroll', $module->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                                                        <circle cx="8.5" cy="7" r="4"/>
                                                        <line x1="20" y1="8" x2="20" y2="14"/>
                                                        <line x1="23" y1="11" x2="17" y2="11"/>
                                                    </svg>
                                                    Enroll
                                                </button>
                                            </form>
                                        @elseif($module->is_completed)
                                            <span style="color: #16a34a; font-size: 0.75rem; font-weight: 600; display: flex; align-items: center; gap: 0.25rem;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                                                    <polyline points="22 4 12 14.01 9 11.01"/>
                                                </svg>
                                                Completed
                                            </span>
                                        @else
                                            <form action="{{ route('modules.unenroll', $module->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline btn-sm" onclick="return confirm('Are you sure you want to unenroll from this module?')">
                                                    Unenroll
                                                </button>
                                            </form>
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
                            <h3>No Modules Available</h3>
                            <p>There are no modules available to enroll in at the moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
</body>
</html>
