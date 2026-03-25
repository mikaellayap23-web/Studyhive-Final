<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Students - {{ $module->title }} - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modules.css') }}">
    <style>
        .students-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .students-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .students-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        @media (max-width: 900px) {
            .students-grid {
                grid-template-columns: 1fr;
            }
        }
        .students-card {
            background: white;
            border: 1px solid #e2e8e4;
            border-radius: 8px;
            padding: 1.5rem;
        }
        .students-card h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .students-card h3 svg {
            width: 20px;
            height: 20px;
        }
        .student-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .student-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            border: 1px solid #e2e8e4;
            border-radius: 6px;
            margin-bottom: 0.5rem;
            background: #f8faf9;
        }
        .student-item:last-child {
            margin-bottom: 0;
        }
        .student-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        .student-name {
            font-weight: 600;
            color: #2d3748;
        }
        .student-username {
            font-size: 0.75rem;
            color: #718096;
        }
        .student-count {
            background: #e8f5e9;
            color: #2d5a3d;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
        }
        .add-student-form {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .add-student-form select {
            flex: 1;
            padding: 0.5rem;
            border: 1px solid #e2e8e4;
            border-radius: 6px;
            font-size: 0.875rem;
        }
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #718096;
        }
        .empty-state svg {
            width: 48px;
            height: 48px;
            margin-bottom: 1rem;
            opacity: 0.5;
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
                    <h1 class="page-title">Manage Students</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <div class="container">
                <div class="students-container">
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

                    @if($errors->any())
                        <div class="alert alert-error">
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <!-- Page Header -->
                    <div class="students-header">
                        <div>
                            <h2 style="font-size: 1.5rem; color: #2d3748; margin-bottom: 0.25rem;">{{ $module->title }}</h2>
                            <p style="color: #718096; font-size: 0.875rem;">Manage enrolled students for this module</p>
                        </div>
                        <a href="{{ route('modules.show', $module->id) }}" class="btn btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 0.5rem;">
                                <path d="M19 16V8a2 2 0 00-2-2h-2"/>
                                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                            </svg>
                            Back to Module
                        </a>
                    </div>

                    <div class="students-grid">
                        <!-- Enrolled Students -->
                        <div class="students-card">
                            <h3>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                                    <path d="M16 3.13a4 4 0 010 7.75"/>
                                </svg>
                                Enrolled Students
                                <span class="student-count">{{ $enrolledStudents->count() }}</span>
                            </h3>

                            @if($enrolledStudents->count() > 0)
                                <ul class="student-list">
                                    @foreach($enrolledStudents as $enrollment)
                                        <li class="student-item">
                                            <div class="student-info">
                                                <span class="student-name">{{ $enrollment->user->first_name }} {{ $enrollment->user->last_name }}</span>
                                                <span class="student-username">@{{ $enrollment->user->username }}</span>
                                            </div>
                                            <form action="{{ route('modules.students.remove', [$module->id, $enrollment->user->id]) }}" method="POST" onsubmit="return confirm('Remove {{ $enrollment->user->first_name }} {{ $enrollment->user->last_name }} from this module?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Remove
                                                </button>
                                            </form>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="empty-state">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                                        <circle cx="9" cy="7" r="4"/>
                                        <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                                        <path d="M16 3.13a4 4 0 010 7.75"/>
                                    </svg>
                                    <p>No students enrolled yet</p>
                                </div>
                            @endif
                        </div>

                        <!-- Add Students -->
                        <div class="students-card">
                            <h3>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                                    <circle cx="8.5" cy="7" r="4"/>
                                    <line x1="20" y1="8" x2="20" y2="14"/>
                                    <line x1="23" y1="11" x2="17" y2="11"/>
                                </svg>
                                Add Student
                            </h3>

                            <form action="{{ route('modules.students.add', $module->id) }}" method="POST" class="add-student-form">
                                @csrf
                                <select name="student_id" required>
                                    <option value="">-- Select Student --</option>
                                    @foreach($students as $student)
                                        @if(!$student->is_enrolled)
                                            <option value="{{ $student->id }}">
                                                {{ $student->first_name }} {{ $student->last_name }} (@{{ $student->username }})
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 5v14M5 12h14"/>
                                    </svg>
                                    Add
                                </button>
                            </form>

                            @if($students->where('is_enrolled', false)->count() === 0)
                                <div class="empty-state">
                                    <p>All students are already enrolled</p>
                                </div>
                            @else
                                <div style="margin-top: 1rem; padding: 1rem; background: #f8faf9; border-radius: 6px;">
                                    <h4 style="font-size: 0.875rem; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem;">All Students</h4>
                                    <ul class="student-list" style="max-height: 300px; overflow-y: auto;">
                                        @foreach($students as $student)
                                            <li class="student-item" style="{{ $student->is_enrolled ? 'opacity: 0.6;' : '' }}">
                                                <div class="student-info">
                                                    <span class="student-name">{{ $student->first_name }} {{ $student->last_name }}</span>
                                                    <span class="student-username">@{{ $student->username }}</span>
                                                </div>
                                                @if($student->is_enrolled)
                                                    <span style="font-size: 0.7rem; color: #48bb78; font-weight: 600;">Enrolled</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
