<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50 theme-navy">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Console - Testwise')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --bg-main: #F8FAFC;
            --bg-card: #FFFFFF;
            --border-color: #E2E8F0;
            --border-hard: #CBD5E1;
            --text-main: #0F172A;
            --text-muted: #64748B;
            --text-faint: #94A3B8;

            --gold: #F59E0B;
            --gold-deep: #D97706;
            --teal: #0D9488;
            --teal-soft: #F0FDFA;
            --rose: #E11D48;
            --rose-soft: #FFF1F2;
            --radius: 16px;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);

            /* Sidebar custom variables */
            --theme-bg: #1E293B; /* Slate 800 */
            --theme-text: #94A3B8;
            --theme-active: #F59E0B;
            --theme-active-text: #FFFFFF;
            --theme-hover: rgba(255, 255, 255, 0.1);
            --theme-hover-text: #FFFFFF;
        }

        body {
            background-color: var(--bg-main) !important;
            color: var(--text-main);
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        h1, h2, h3, h4, .dash-head h1, .display {
            font-family: 'Space Grotesk', sans-serif;
            letter-spacing: -.01em;
            font-weight: 700;
        }

        nav a {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 600;
        }

        /* Forms Layout & Labels styling */
        form label:not(.inline-flex):not(.flex-row),
        .field label:not(.inline-flex):not(.flex-row) {
            display: flex;
            justify-content: space-between;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 7px;
            font-family: 'Inter', sans-serif;
        }

        /* Global inputs & form elements styling overrides */
        input[type="text"], input[type="number"], input[type="email"], input[type="password"], input[type="date"], input[type="time"], input[type="search"], select, textarea {
            background-color: var(--bg-card);
            border: 1px solid var(--border-hard);
            border-radius: 10px;
            padding: 9px 13px;
            font-size: 13px;
            color: var(--text-main);
            box-shadow: none;
            transition: all 0.2s ease;
            width: 100%;
        }

        input[type="text"]:focus, input[type="number"]:focus, input[type="email"]:focus, input[type="password"]:focus, input[type="date"]:focus, input[type="time"]:focus, input[type="search"]:focus, select:focus, textarea:focus {
            border-color: var(--gold);
            outline: none;
            box-shadow: 0 0 0 3px rgba(217, 154, 43, 0.15);
            background-color: #FFFFFF;
        }

        /* Buttons styling */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            border-radius: 10px;
            padding: 9px 18px;
            font-size: 13px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-gold, .btn-primary {
            background-color: var(--gold) !important;
            color: #17233F !important;
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .btn-gold:hover, .btn-primary:hover {
            background-color: var(--gold-deep) !important;
            color: #ffffff !important;
        }

        .btn-secondary {
            background-color: #FFFFFF;
            color: var(--text-main);
            border: 1px solid var(--border-hard);
        }
        .btn-secondary:hover {
            background-color: var(--bg-main);
            border-color: var(--text-muted);
        }

        /* Table & Panels styling */
        .panel, .card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .sidebar-expanded { width: 16rem !important; }
        .sidebar-collapsed { width: 4.5rem !important; }

        .main-expanded { padding-left: 16rem !important; }
        .main-collapsed { padding-left: 4.5rem !important; }
        
        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 8px;
            color: var(--theme-text);
            font-size: 14px;
            transition: all 0.2s;
        }
        .sidebar-item:hover {
            background-color: var(--theme-hover);
            color: var(--theme-hover-text);
        }
        .sidebar-item.active {
            background-color: var(--theme-hover);
            color: var(--theme-active);
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .main-expanded, .main-collapsed { padding-left: 0 !important; }
            .sidebar-expanded, .sidebar-collapsed { width: 16rem !important; }
        }
    </style>
</head>
<body class="h-full font-sans antialiased text-gray-900" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">

    <!-- Mobile sidebar off-canvas -->
    <div x-show="sidebarOpen" class="relative z-40 md:hidden" role="dialog" aria-modal="true" style="display: none;">
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>

        <div class="fixed inset-0 z-40 flex">
            <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative flex w-full max-w-xs flex-1 flex-col pb-4 pt-5" style="background-color: var(--theme-bg);">
                
                <div class="absolute right-0 top-0 -mr-12 pt-2">
                    <button type="button" @click="sidebarOpen = false" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <i class="fa-solid fa-xmark text-white text-xl"></i>
                    </button>
                </div>

                <div class="flex flex-shrink-0 items-center justify-between px-4 mb-6">
                    <div class="text-white flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center p-1 shadow-sm shrink-0">
                            <div class="w-full h-full bg-[var(--gold)] rounded-md flex items-center justify-center text-[var(--theme-bg)] font-bold">A</div>
                        </div>
                        <div class="flex flex-col">
                            <span class="truncate font-extrabold text-base tracking-tight leading-tight" style="color: white !important;">Testwise</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider" style="color: var(--theme-active) !important;">Admin Console</span>
                        </div>
                    </div>
                </div>

                <div class="mt-2 h-0 flex-1 overflow-y-auto">
                    <nav class="space-y-1 px-2">
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-chart-pie w-4 text-center"></i>
                            <span>Overview</span>
                        </a>

                        <div class="px-2 mt-6 mb-2">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-[var(--theme-text)]">Content Management</div>
                        </div>

                        <a href="{{ route('admin.courses') }}" class="sidebar-item {{ request()->routeIs('admin.courses') ? 'active' : '' }}">
                            <i class="fa-solid fa-graduation-cap w-4 text-center"></i>
                            <span class="sidebar-text">Courses / Exams</span>
                        </a>

                        <a href="{{ route('admin.subjects') }}" class="sidebar-item {{ request()->routeIs('admin.subjects') ? 'active' : '' }}">
                            <i class="fa-solid fa-layer-group w-4 text-center"></i>
                            <span>Subjects</span>
                        </a>
                        <a href="{{ route('admin.chapters') }}" class="sidebar-item {{ request()->routeIs('admin.chapters') ? 'active' : '' }}">
                            <i class="fa-solid fa-book w-4 text-center"></i>
                            <span>Chapters & Notes</span>
                        </a>
                        <a href="{{ route('admin.questions') }}" class="sidebar-item {{ request()->routeIs('admin.questions') ? 'active' : '' }}">
                            <i class="fa-solid fa-clipboard-list w-4 text-center"></i>
                            <span>Question Bank</span>
                        </a>
                        <a href="{{ route('admin.mock-tests') }}" class="sidebar-item {{ request()->routeIs('admin.mock-tests') ? 'active' : '' }}">
                            <i class="fa-solid fa-clock-rotate-left w-4 text-center"></i>
                            <span>Mock Tests</span>
                        </a>

                        <div class="px-2 mt-6 mb-2">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-[var(--theme-text)]">User Management</div>
                        </div>

                        <a href="{{ route('admin.students') }}" class="sidebar-item {{ request()->routeIs('admin.students') ? 'active' : '' }}">
                            <i class="fa-solid fa-users w-4 text-center"></i>
                            <span>Students Directory</span>
                        </a>
                        <a href="{{ route('admin.payments') }}" class="sidebar-item {{ request()->routeIs('admin.payments') ? 'active' : '' }}">
                            <i class="fa-solid fa-wallet w-4 text-center"></i>
                            <span>Payments & Access</span>
                        </a>
                        <a href="{{ route('admin.certificates') }}" class="sidebar-item {{ request()->routeIs('admin.certificates') ? 'active' : '' }}">
                            <i class="fa-solid fa-award w-4 text-center"></i>
                            <span>Certificates</span>
                        </a>
                    </nav>
                </div>
                
                <div class="flex flex-shrink-0 border-t border-white/10 p-4">
                    <div class="flex items-center">
                        <div class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[var(--theme-active)] text-[var(--theme-active-text)] font-bold shrink-0">
                            A
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-white truncate max-w-[130px]" style="color: white !important;">Administrator</p>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs font-medium text-[var(--theme-text)] hover:text-white transition-colors">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Static sidebar -->
    <div :class="sidebarCollapsed ? 'sidebar-collapsed' : 'sidebar-expanded'" class="sidebar-expanded md:w-64 hidden md:flex md:fixed md:inset-y-0 md:flex-col transition-all duration-300 z-30">
        <div class="flex min-h-0 flex-1 flex-col" style="background-color: var(--theme-bg);">
            <div class="flex flex-1 flex-col overflow-y-auto pt-5 pb-4">
                <div class="flex flex-shrink-0 items-center justify-between px-4 mb-6">
                    <div class="text-white flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center p-1 shadow-sm shrink-0">
                            <div class="w-full h-full bg-[var(--gold)] rounded-md flex items-center justify-center text-[var(--theme-bg)] font-bold">A</div>
                        </div>
                        <div x-show="!sidebarCollapsed" class="flex flex-col">
                            <span class="truncate font-extrabold text-base tracking-tight leading-tight" style="color: white !important;">Testwise</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider" style="color: var(--theme-active) !important;">Admin Console</span>
                        </div>
                    </div>
                </div>

                <div class="px-4 mb-2">
                    <div class="text-[10px] font-bold uppercase tracking-widest text-[var(--theme-text)]">Main Navigation</div>
                </div>

                <nav class="mt-2 flex-1 space-y-1 px-2">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-pie w-4 text-center"></i>
                        <span>Overview</span>
                    </a>

                    <div class="px-2 mt-6 mb-2">
                        <div class="text-[10px] font-bold uppercase tracking-widest text-[var(--theme-text)]">Content Management</div>
                    </div>

                    <a href="{{ route('admin.courses') }}" class="sidebar-item {{ request()->routeIs('admin.courses') ? 'active' : '' }}">
                        <i class="fa-solid fa-graduation-cap w-4 text-center"></i>
                        <span class="sidebar-text" x-show="!sidebarCollapsed">Courses / Exams</span>
                    </a>

                    <a href="{{ route('admin.subjects') }}" class="sidebar-item {{ request()->routeIs('admin.subjects') ? 'active' : '' }}">
                        <i class="fa-solid fa-layer-group w-4 text-center"></i>
                        <span>Subjects</span>
                    </a>
                    <a href="{{ route('admin.chapters') }}" class="sidebar-item {{ request()->routeIs('admin.chapters') ? 'active' : '' }}">
                        <i class="fa-solid fa-book w-4 text-center"></i>
                        <span>Chapters & Notes</span>
                    </a>
                    <a href="{{ route('admin.questions') }}" class="sidebar-item {{ request()->routeIs('admin.questions') ? 'active' : '' }}">
                        <i class="fa-solid fa-clipboard-list w-4 text-center"></i>
                        <span>Question Bank</span>
                    </a>
                    <a href="{{ route('admin.mock-tests') }}" class="sidebar-item {{ request()->routeIs('admin.mock-tests') ? 'active' : '' }}">
                        <i class="fa-solid fa-clock-rotate-left w-4 text-center"></i>
                        <span>Mock Tests</span>
                    </a>

                    <div class="px-2 mt-6 mb-2">
                        <div class="text-[10px] font-bold uppercase tracking-widest text-[var(--theme-text)]">User Management</div>
                    </div>

                    <a href="{{ route('admin.students') }}" class="sidebar-item {{ request()->routeIs('admin.students') ? 'active' : '' }}">
                        <i class="fa-solid fa-users w-4 text-center"></i>
                        <span>Students Directory</span>
                    </a>
                    <a href="{{ route('admin.payments') }}" class="sidebar-item {{ request()->routeIs('admin.payments') ? 'active' : '' }}">
                        <i class="fa-solid fa-wallet w-4 text-center"></i>
                        <span>Payments & Access</span>
                    </a>
                    <a href="{{ route('admin.certificates') }}" class="sidebar-item {{ request()->routeIs('admin.certificates') ? 'active' : '' }}">
                        <i class="fa-solid fa-award w-4 text-center"></i>
                        <span>Certificates</span>
                    </a>
                </nav>
            </div>
            
            <div class="flex flex-shrink-0 border-t border-white/10 p-4">
                <div class="group block w-full flex-shrink-0">
                    <div class="flex items-center" :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[var(--theme-active)] text-[var(--theme-active-text)] font-bold shrink-0">
                            A
                        </div>
                        <div x-show="!sidebarCollapsed" class="ml-3">
                            <p class="text-sm font-medium text-white truncate max-w-[130px]" style="color: white !important;">Administrator</p>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs font-medium text-[var(--theme-text)] hover:text-white transition-colors">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div :class="sidebarCollapsed ? 'main-collapsed' : 'main-expanded'" class="main-expanded md:pl-64 flex flex-1 flex-col transition-all duration-300">
        <div class="sticky top-0 z-10 flex h-16 flex-shrink-0 bg-white shadow-sm border-b border-gray-200">
            <button type="button" @click="sidebarOpen = true" class="border-r border-gray-200 px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 md:hidden">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <div class="flex flex-1 justify-between px-4 items-center">
                <div class="flex items-center">
                    <h2 class="text-lg font-bold text-gray-800 hidden sm:block" style="color: var(--text-main) !important;">MP Police GD 2026 Admin</h2>
                </div>
            </div>
        </div>

        <main class="flex-1">
            <div class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-xl" style="background-color: var(--teal-soft); border: 1px solid var(--teal); color: var(--teal);">
                            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                        </div>
                    @endif
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <!-- Alpine JS for sidebar state (mock implementation for pure CSS layout) -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
