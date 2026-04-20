<aside class="sidebar">
    @php
        $unreadAnnouncementsCount = \App\Models\Announcement::where('user_id', '!=', auth()->id())
            ->whereDoesntHave('reads', function ($query) {
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

            @if(auth()->user()->role === 'student')
                <div class="nav-dropdown" id="profileDropdown">
                    <button class="nav-dropdown-toggle" onclick="toggleDropdown('profileDropdown')">
                        <div class="toggle-content">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profile
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="chevron">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="nav-dropdown-menu">
                        <a href="{{ route('profile') }}" class="nav-dropdown-item {{ request()->routeIs('profile') ? 'active' : '' }}">
                            My Profile
                        </a>
                        <a href="{{ route('certificates.index') }}" class="nav-dropdown-item {{ request()->routeIs('certificates.*') ? 'active' : '' }}">
                            Certificates
                        </a>
                    </div>
                </div>
            @else
                <a href="{{ route('profile') }}" class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profile
                </a>
            @endif
        </div>

        <!-- Admin Menu -->
        @if(auth()->user()->role === 'admin')
            <div class="nav-section">
                <div class="nav-section-title">Management</div>

                <div class="nav-dropdown" id="adminUsersDropdown">
                    <button class="nav-dropdown-toggle" onclick="toggleDropdown('adminUsersDropdown')">
                        <div class="toggle-content">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            User Management
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="chevron">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="nav-dropdown-menu">
                        <a href="{{ route('admin.users.index') }}" class="nav-dropdown-item {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                            Users
                        </a>
                        <a href="{{ route('admin.certificates.index') }}" class="nav-dropdown-item {{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}">
                            Certificates
                        </a>
                    </div>
                </div>

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

                <!-- Help & Tour -->
                <button onclick="openHelpModal()" class="nav-link" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    Help & Tour
                </button>

                <!-- Dark Mode Toggle -->
                <button id="darkModeToggle" onclick="toggleDarkMode()" class="nav-link" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                    <svg id="darkModeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
                    </svg>
                    <span id="darkModeText">Dark Mode</span>
                </button>
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

                <!-- Help & Tour -->
                <button onclick="openHelpModal()" class="nav-link" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    Help & Tour
                </button>

                <!-- Dark Mode Toggle -->
                <button id="darkModeToggle" onclick="toggleDarkMode()" class="nav-link" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                    <svg id="darkModeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
                    </svg>
                    <span id="darkModeText">Dark Mode</span>
                </button>
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

                <!-- Help & Tour -->
                <button onclick="openHelpModal()" class="nav-link" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    Help & Tour
                </button>

                <!-- Dark Mode Toggle -->
                <button id="darkModeToggle" onclick="toggleDarkMode()" class="nav-link" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                    <svg id="darkModeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
                    </svg>
                    <span id="darkModeText">Dark Mode</span>
                </button>
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

 <!-- Help Modal -->
 <div id="helpModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 10000; align-items: center; justify-content: center;" onclick="closeHelpModal()">
     <div style="background: white; max-width: 650px; width: 90%; max-height: 90vh; overflow-y: auto; border-radius: 12px; padding: 2rem; position: relative; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);" onclick="event.stopPropagation()">
         <button onclick="closeHelpModal()" style="position: absolute; top: 0.75rem; right: 0.75rem; background: none; border: none; font-size: 1.75rem; cursor: pointer; color: #6b7280; line-height: 1;">&times;</button>
         
         <h2 style="margin: 0 0 1rem; font-size: 1.5rem; font-weight: 700; color: #1e293b;">📚 Studyhive Help & Tour</h2>
         
         <div style="color: #4b5563; line-height: 1.7;">
             <p style="margin-bottom: 1rem;">Welcome to Studyhive, your Learning Management System for TESDA CSS NC II. Here's a quick tour:</p>
             
             <h4 style="margin: 1.5rem 0 0.75rem; color: #1e293b; font-weight: 600;">🎯 For Students</h4>
             <ul style="margin: 0 0 1rem 1.25rem; padding: 0;">
                 <li><strong>Enroll in modules:</strong> Browse "All Modules", click enroll. Complete prerequisites first if any.</li>
                 <li><strong>Study materials:</strong> View PDFs; your progress is tracked automatically.</li>
                 <li><strong>Take assessments:</strong> After completing a module (100% progress), you can take the assessment. You need to pass to get a certificate.</li>
                 <li><strong>Certificates:</strong> Automatically issued upon passing both progress and assessment. View under Profile → Certificates.</li>
             </ul>

             <h4 style="margin: 1.5rem 0 0.75rem; color: #1e293b; font-weight: 600;">👨‍🏫 For Teachers</h4>
             <ul style="margin: 0 0 1rem 1.25rem; padding: 0;">
                 <li><strong>Create & manage modules:</strong> Add modules, upload PDFs, set order, assign yourself as teacher.</li>
                 <li><strong>Create assessments:</strong> Link assessments to your modules with questions, passing scores, and attempts.</li>
                 <li><strong>Monitor students:</strong> View submissions, grades, and module enrollment.</li>
                 <li><strong>Export results:</strong> Download Excel reports from the Submissions page.</li>
             </ul>

             <h4 style="margin: 1.5rem 0 0.75rem; color: #1e293b; font-weight: 600;">🔐 For Admins</h4>
             <ul style="margin: 0 0 1rem 1.25rem; padding: 0;">
                 <li><strong>User management:</strong> Approve, reject, create users.</li>
                 <li><strong>Bulk actions:</strong> Export grades/completion reports, send bulk emails.</li>
                 <li><strong>All modules & certificates:</strong> Full control over content and approvals.</li>
                 <li><strong>Audit trail:</strong> Track system activity on your dashboard.</li>
             </ul>

             <div style="margin-top: 1.5rem; padding: 1rem; background: #f0f9ff; border-radius: 8px; border-left: 4px solid #3b82f6;">
                 <strong>💡 Tip:</strong> Use Dark Mode from the sidebar to reduce eye strain during long sessions!
             </div>

             <p style="margin-top: 1rem;"><em>Need more help? Contact your system administrator.</em></p>
         </div>

         <div style="text-align: right; margin-top: 1.5rem;">
             <button onclick="closeHelpModal()" class="btn btn-primary">Got it!</button>
         </div>
     </div>
 </div>

 <!-- Dark Mode Toggle Script -->
 <script>
 function toggleDropdown(dropdownId) {
     const dropdown = document.getElementById(dropdownId);
     if (dropdown) {
         dropdown.classList.toggle('open');
     }
 }

 function toggleDarkMode() {
     const html = document.documentElement;
     const isDark = html.classList.toggle('dark-mode');
     localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
     updateDarkModeIcon(isDark);
 }

 function updateDarkModeIcon(isDark) {
     const icon = document.getElementById('darkModeIcon');
     const text = document.getElementById('darkModeText');
     if (isDark) {
         icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />';
         text.textContent = 'Light Mode';
     } else {
         icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />';
         text.textContent = 'Dark Mode';
     }
 }

 // Load saved preference
 (function() {
     const saved = localStorage.getItem('darkMode');
     const html = document.documentElement;
     if (saved === 'enabled') {
         html.classList.add('dark-mode');
         updateDarkModeIcon(true);
     }
 })();

 function openHelpModal() {
     document.getElementById('helpModal').style.display = 'flex';
 }

 function closeHelpModal() {
     document.getElementById('helpModal').style.display = 'none';
 }
 </script>
