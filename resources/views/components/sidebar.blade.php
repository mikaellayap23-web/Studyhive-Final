<aside class="sidebar">
    @php
        $unreadAnnouncementsCount = \App\Models\Announcement::whereDoesntHave('reads', function ($query) {
            $query->where('user_id', auth()->id());
        })->count();
    @endphp

    <!-- User Profile -->
    <div class="sidebar-profile">
        <div class="sidebar-avatar">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
        </div>
        <div class="sidebar-user-name">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</div>
        <div class="sidebar-user-role">{{ ucfirst(auth()->user()->role) }}</div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <!-- Common Links for All Roles -->
        <div class="nav-section">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>

            <a href="{{ route('profile') }}" class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Profile
            </a>
        </div>

        <!-- Admin Menu -->
        @if(auth()->user()->role === 'admin')
            <div class="nav-section">
                <div class="nav-section-title">Management</div>

                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    User Management
                </a>

                <div class="nav-dropdown" id="adminModulesDropdown">
                    <button class="nav-dropdown-toggle" onclick="toggleDropdown('adminModulesDropdown')">
                        <div class="toggle-content">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            Module Management
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="chevron">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="nav-dropdown-menu">
                        <a href="{{ route('modules.index') }}" class="nav-dropdown-item {{ request()->routeIs('modules.index') ? 'active' : '' }}">
                            All Modules
                        </a>
                        <a href="{{ route('modules.my') }}" class="nav-dropdown-item {{ request()->routeIs('modules.my') ? 'active' : '' }}">
                            My Modules
                        </a>
                    </div>
                </div>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Communication</div>

                <a href="{{ route('announcements.index') }}" class="nav-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                    Announcements
                    @if($unreadAnnouncementsCount > 0)
                        <span class="notification-badge">{{ $unreadAnnouncementsCount }}</span>
                    @endif
                </a>
            </div>

        <!-- Teacher Menu -->
        @elseif(auth()->user()->role === 'teacher')
            <div class="nav-section">
                <div class="nav-section-title">Management</div>

                <div class="nav-dropdown" id="teacherModulesDropdown">
                    <button class="nav-dropdown-toggle" onclick="toggleDropdown('teacherModulesDropdown')">
                        <div class="toggle-content">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            Module Management
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="chevron">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="nav-dropdown-menu">
                        <a href="{{ route('modules.index') }}" class="nav-dropdown-item {{ request()->routeIs('modules.index') ? 'active' : '' }}">
                            All Modules
                        </a>
                        <a href="{{ route('modules.my') }}" class="nav-dropdown-item {{ request()->routeIs('modules.my') ? 'active' : '' }}">
                            My Modules
                        </a>
                    </div>
                </div>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Communication</div>

                <a href="{{ route('announcements.index') }}" class="nav-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                    Announcements
                    @if($unreadAnnouncementsCount > 0)
                        <span class="notification-badge">{{ $unreadAnnouncementsCount }}</span>
                    @endif
                </a>
            </div>

        <!-- Student Menu -->
        @else
            <div class="nav-section">
                <div class="nav-section-title">Learning</div>

                <div class="nav-dropdown" id="modulesDropdown">
                    <button class="nav-dropdown-toggle" onclick="toggleDropdown('modulesDropdown')">
                        <div class="toggle-content">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Modules
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="chevron">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="nav-dropdown-menu">
                        <a href="{{ route('modules.all') }}" class="nav-dropdown-item {{ request()->routeIs('modules.all') ? 'active' : '' }}">
                            All Modules
                        </a>
                        <a href="{{ route('modules.my') }}" class="nav-dropdown-item {{ request()->routeIs('modules.my') ? 'active' : '' }}">
                            My Modules
                        </a>
                    </div>
                </div>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Communication</div>

                <a href="{{ route('announcements.index') }}" class="nav-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                    Announcements
                    @if($unreadAnnouncementsCount > 0)
                        <span class="notification-badge">{{ $unreadAnnouncementsCount }}</span>
                    @endif
                </a>
            </div>
        @endif
    </nav>

    <!-- Logout Button -->
    <div class="sidebar-logout">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>

<script>
function toggleDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    if (dropdown) {
        dropdown.classList.toggle('open');
    }
}
</script>
