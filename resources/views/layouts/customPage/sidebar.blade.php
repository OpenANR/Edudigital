<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Edudigital')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex flex-col md:flex-row text-slate-800 font-sans">

    <!-- Mobile Top Navigation Bar -->
    <header class="md:hidden flex items-center justify-between bg-slate-900 text-white px-4 py-3 shadow-md shrink-0">
        <div class="flex items-center space-x-2">
            <img src="{{ asset('logo/logoSmk.png') }}" alt="Logo SMK" class="h-8 w-8 object-contain">
            <span class="font-bold tracking-wider">EduDigital</span>
        </div>
        <button id="mobile-menu-btn" class="p-1 text-slate-300 hover:text-white focus:outline-none" aria-label="Toggle Menu">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </header>

    <!-- Sidebar Navigation Drawer -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col bg-slate-900 text-slate-300 transition-all duration-300 ease-in-out md:static md:translate-x-0 md:shadow-lg">
        <!-- Sidebar Brand / Logo -->
        <div class="relative flex h-16 items-center justify-center px-4 border-b border-slate-800 shrink-0 w-full">
            <div class="flex items-center justify-center space-x-2 overflow-hidden w-full">
                <img src="{{ asset('logo/logoSmk.png') }}" alt="Logo SMK" class="h-8 w-8 object-contain shrink-0">
                <span class="text-base font-bold tracking-wider text-white brand-text truncate">EduDigital</span>
            </div>
            <!-- Toggle Collapse Button on Desktop -->
            <button id="toggle-sidebar-btn" class="hidden md:block absolute right-4 p-1 rounded hover:bg-slate-800 text-slate-400 hover:text-white focus:outline-none transition-all duration-300">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="toggle-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 space-y-1 px-3 py-6 overflow-y-auto">
            <!-- Dashboard Link -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="ml-3 nav-label">Dashboard</span>
            </a>

            <!-- Kelola Jurusan Link -->
            <a href="{{ route('admin.manageDepartment') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.manageDepartment') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span class="ml-3 nav-label">Kelola Jurusan</span>
            </a>

            <!-- Kelola Siswa Link -->
            <a href="{{ route('admin.manageStudent') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.manageStudent') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="ml-3 nav-label">Kelola Siswa</span>
            </a>

            <!-- Input Nilai Link -->
            <a href="{{ route('admin.manageScore') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.manageScore') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span class="ml-3 nav-label">Input Nilai</span>
            </a>
        </nav>

        <!-- User Information & Logout -->
        <div class="p-4 border-t border-slate-800 shrink-0">
            <div class="flex items-center px-2 mb-3 profile-container">
                <div class="flex-shrink-0">
                    <span class="inline-block h-9 w-9 rounded-full bg-slate-800 text-center leading-9 text-lg">👤</span>
                </div>
                <div class="ml-3 user-details overflow-hidden">
                    <p class="text-sm font-semibold text-white leading-tight">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-400 capitalize">{{ Auth::user()->role }}</p>
                </div>
            </div>
            
            <form action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="flex w-full items-center px-3 py-2.5 text-sm font-medium text-red-400 hover:bg-slate-800 hover:text-red-300 rounded-lg transition">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="ml-3 nav-label">Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Overlay when sidebar is open on mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm hidden transition-opacity md:hidden"></div>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden min-h-0">
        <main class="flex-grow overflow-y-auto p-4 md:p-8">
            @yield('content')
        </main>
    </div>

    <!-- Sidebar Scripts -->
    <script>
        // Apply collapsed state before rendering to prevent layout flashing
        const applySidebarState = () => {
            const sidebar = document.getElementById('sidebar');
            const toggleIcon = document.getElementById('toggle-icon');
            const toggleBtn = document.getElementById('toggle-sidebar-btn');
            const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
            
            if (isCollapsed && window.innerWidth >= 768) {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-20');
                
                document.querySelectorAll('.nav-label, .brand-text, .user-details').forEach(el => el.classList.add('hidden'));
                
                // Center nav links & logout button
                document.querySelectorAll('aside nav a, aside form button').forEach(el => {
                    el.classList.add('justify-center');
                });
                
                // Center profile container
                document.querySelectorAll('.profile-container').forEach(el => {
                    el.classList.add('justify-center');
                    el.classList.remove('px-2');
                });

                // Move toggle button to border to prevent overlapping centered logo
                if (toggleBtn) {
                    toggleBtn.classList.remove('right-4', 'rounded');
                    toggleBtn.classList.add('right-0', 'translate-x-1/2', 'bg-slate-900', 'border', 'border-slate-700', 'rounded-full', 'shadow-md');
                }
                
                if (toggleIcon) {
                    toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />';
                }
            }
        };

        // Apply immediately
        applySidebarState();

        document.addEventListener('DOMContentLoaded', () => {
            const menuBtn = document.getElementById('mobile-menu-btn');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const toggleBtn = document.getElementById('toggle-sidebar-btn');
            const toggleIcon = document.getElementById('toggle-icon');

            // Apply state on DOMContentLoaded as well to ensure correctness
            applySidebarState();

            // Mobile Menu Toggle
            const toggleSidebarMobile = () => {
                const isOpen = sidebar.classList.contains('translate-x-0');
                if (isOpen) {
                    sidebar.classList.remove('translate-x-0');
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                } else {
                    sidebar.classList.remove('-translate-x-full');
                    sidebar.classList.add('translate-x-0');
                    overlay.classList.remove('hidden');
                }
            };

            if (menuBtn) menuBtn.addEventListener('click', toggleSidebarMobile);
            if (overlay) overlay.addEventListener('click', toggleSidebarMobile);

            // Desktop Collapse Toggle
            if (toggleBtn) {
                toggleBtn.addEventListener('click', () => {
                    const isCollapsed = sidebar.classList.contains('w-20');
                    if (isCollapsed) {
                        // Expand Sidebar
                        sidebar.classList.remove('w-20');
                        sidebar.classList.add('w-64');
                        localStorage.setItem('sidebar-collapsed', 'false');
                        
                        document.querySelectorAll('.nav-label, .brand-text, .user-details').forEach(el => el.classList.remove('hidden'));
                        
                        // Left-align nav links & logout button
                        document.querySelectorAll('aside nav a, aside form button').forEach(el => {
                            el.classList.remove('justify-center');
                        });
                        
                        // Left-align profile container
                        document.querySelectorAll('.profile-container').forEach(el => {
                            el.classList.remove('justify-center');
                            el.classList.add('px-2');
                        });

                        // Restore toggle button position
                        toggleBtn.classList.remove('right-0', 'translate-x-1/2', 'bg-slate-900', 'border', 'border-slate-700', 'rounded-full', 'shadow-md');
                        toggleBtn.classList.add('right-4', 'rounded');

                        if (toggleIcon) {
                            toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />';
                        }
                    } else {
                        // Collapse Sidebar
                        sidebar.classList.remove('w-64');
                        sidebar.classList.add('w-20');
                        localStorage.setItem('sidebar-collapsed', 'true');
                        
                        document.querySelectorAll('.nav-label, .brand-text, .user-details').forEach(el => el.classList.add('hidden'));
                        
                        // Center nav links & logout button
                        document.querySelectorAll('aside nav a, aside form button').forEach(el => {
                            el.classList.add('justify-center');
                        });
                        
                        // Center profile container
                        document.querySelectorAll('.profile-container').forEach(el => {
                            el.classList.add('justify-center');
                            el.classList.remove('px-2');
                        });

                        // Move toggle button to border
                        toggleBtn.classList.remove('right-4', 'rounded');
                        toggleBtn.classList.add('right-0', 'translate-x-1/2', 'bg-slate-900', 'border', 'border-slate-700', 'rounded-full', 'shadow-md');

                        if (toggleIcon) {
                            toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />';
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>