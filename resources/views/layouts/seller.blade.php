<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Seller Dashboard - OneStall Cargo')</title>
    <!-- Inter font for sleek tech UI -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f5f9; color: #1e293b; }
        
        /* Sidebar Item Base */
        .sidebar-item { 
            display: flex; 
            align-items: center; 
            height: 44px;
            padding: 0 14px;
            border-radius: 12px; 
            color: #64748b; 
            font-size: 13px; 
            font-weight: 600; 
            transition: all 0.2s ease; 
            cursor: pointer;
            text-decoration: none;
            margin-bottom: 4px;
        }
        
        .sidebar-item:hover { 
            background-color: #f1f5f9; 
            color: #0f172a; 
        }
        
        .sidebar-item.active { 
            background-color: #eef2ff; 
            color: #4338ca; 
            font-weight: 700;
        }

        .icon-box {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .topbar-input { background: transparent; border: none; outline: none; width: 100%; font-size: 13px; color: #475569; }
        .topbar-input::placeholder { color: #94a3b8; font-weight: 500; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="h-screen flex overflow-hidden bg-[#f3f5f9]" x-data="{ sidebarHover: false, isPinned: false, mobileSidebarOpen: false }">
    
    <!-- Mobile Overlay -->
    <div x-show="mobileSidebarOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/50 z-40 md:hidden" style="display: none;" @click="mobileSidebarOpen = false"></div>

    <!-- Hover-to-Expand Left Sidebar -->
    <aside 
        @mouseenter="sidebarHover = true" 
        @mouseleave="sidebarHover = false"
        :class="{
            'w-64 shadow-2xl z-50 translate-x-0 fixed inset-y-0 left-0 md:relative': (sidebarHover || isPinned || mobileSidebarOpen),
            'w-64 -translate-x-full fixed inset-y-0 left-0 md:relative md:translate-x-0 md:w-20 z-30': !(sidebarHover || isPinned || mobileSidebarOpen)
        }"
        class="bg-white border-r border-gray-200 flex flex-col py-5 shrink-0 transition-all duration-300 ease-in-out"
        style="height: 100vh;">

        
        <!-- Pin / Lock Sidebar Toggle Button -->
        <button 
            @click="isPinned = !isPinned" 
            class="absolute -right-3 top-6 md:flex hidden w-6 h-6 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-500 hover:text-[#4338ca] shadow-md z-50 transition-all duration-300"
            :class="isPinned ? 'rotate-180 bg-[#eef2ff] text-[#4338ca] border-[#c7d2fe]' : ''"
            :title="isPinned ? 'Unpin Sidebar' : 'Pin Sidebar Open'">
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
        </button>

        <!-- Logo Section -->
        <div class="px-4 mb-6 flex items-center h-10 transition-all overflow-hidden" :class="(sidebarHover || isPinned || mobileSidebarOpen) ? 'justify-start' : 'justify-center'">
            <a href="{{ route('seller.dashboard') }}" class="flex items-center hover:scale-105 transition-transform">
                <img src="{{ asset('images/logo.jpg') }}" alt="OneStall Cargo" class="object-contain rounded transition-all duration-300" :class="(sidebarHover || isPinned || mobileSidebarOpen) ? 'h-10 max-w-[140px]' : 'h-8 w-8'">
            </a>
        </div>
        
        <!-- Navigation List -->
        <nav class="flex flex-col flex-1 w-full px-3 overflow-y-auto overflow-x-hidden">
            
            <a href="{{ route('seller.dashboard') }}" class="sidebar-item {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}" title="Dashboard">
                <div class="icon-box">
                    <i class="fa-solid fa-border-all text-base"></i>
                </div>
                <span x-show="sidebarHover || isPinned || mobileSidebarOpen" x-transition.opacity class="ml-3 whitespace-nowrap">Dashboard</span>
            </a>

            <a href="{{ route('seller.shipments.index') }}" class="sidebar-item {{ request()->routeIs('seller.shipments.*') ? 'active' : '' }}" title="Orders">
                <div class="icon-box relative">
                    <i class="fa-solid fa-box text-base"></i>
                    @php $newCount = \App\Models\Shipment::where('user_id', Auth::id())->whereIn('status', ['new', 'Manifested', 'Booked', 'booked'])->count(); @endphp
                    @if($newCount > 0)
                        <span class="absolute top-0 right-0 w-2 h-2 rounded-full bg-[#4338ca]"></span>
                    @endif
                </div>
                <span x-show="sidebarHover || isPinned || mobileSidebarOpen" x-transition.opacity class="ml-3 whitespace-nowrap flex-1 flex justify-between items-center">
                    <span>Orders</span>
                    @if($newCount > 0)
                        <span class="px-2 py-0.5 text-[10px] font-bold bg-[#eef2ff] text-[#4338ca] rounded-full">{{ $newCount }}</span>
                    @endif
                </span>
            </a>

            <a href="{{ route('seller.book') }}" class="sidebar-item {{ request()->routeIs('seller.book') ? 'active' : '' }}" title="Add Order">
                <div class="icon-box">
                    <i class="fa-solid fa-square-plus text-base"></i>
                </div>
                <span x-show="sidebarHover || isPinned || mobileSidebarOpen" x-transition.opacity class="ml-3 whitespace-nowrap">Add Order</span>
            </a>

            <a href="{{ route('seller.ndr') }}" class="sidebar-item {{ request()->routeIs('seller.ndr') ? 'active' : '' }}" title="NDR Management">
                <div class="icon-box">
                    <i class="fa-solid fa-arrow-rotate-left text-base"></i>
                </div>
                <span x-show="sidebarHover || isPinned || mobileSidebarOpen" x-transition.opacity class="ml-3 whitespace-nowrap">NDR Management</span>
            </a>

            <a href="{{ route('seller.weight') }}" class="sidebar-item {{ request()->routeIs('seller.weight') ? 'active' : '' }}" title="Weight Discrepancies">
                <div class="icon-box">
                    <i class="fa-solid fa-scale-balanced text-base"></i>
                </div>
                <span x-show="sidebarHover || isPinned || mobileSidebarOpen" x-transition.opacity class="ml-3 whitespace-nowrap">Weight Discrepancies</span>
            </a>

            <a href="{{ route('seller.weight.freeze') }}" class="sidebar-item {{ request()->routeIs('seller.weight.freeze') ? 'active' : '' }}" title="Weight Freeze">
                <div class="icon-box">
                    <i class="fa-solid fa-snowflake text-base"></i>
                </div>
                <span x-show="sidebarHover || isPinned || mobileSidebarOpen" x-transition.opacity class="ml-3 whitespace-nowrap">Weight Freeze</span>
            </a>

            <a href="{{ route('seller.tools') }}" class="sidebar-item {{ request()->routeIs('seller.tools') ? 'active' : '' }}" title="Tools">
                <div class="icon-box">
                    <i class="fa-solid fa-wrench text-base"></i>
                </div>
                <span x-show="sidebarHover || isPinned || mobileSidebarOpen" x-transition.opacity class="ml-3 whitespace-nowrap">Tools & Rate Calc</span>
            </a>

            <a href="{{ route('seller.wallet') }}" class="sidebar-item {{ request()->routeIs('seller.wallet') ? 'active' : '' }}" title="Billing">
                <div class="icon-box">
                    <i class="fa-solid fa-wallet text-base"></i>
                </div>
                <span x-show="sidebarHover || isPinned || mobileSidebarOpen" x-transition.opacity class="ml-3 whitespace-nowrap">Billing & Wallet</span>
            </a>

            <a href="{{ route('seller.settings') }}" class="sidebar-item {{ request()->routeIs('seller.settings') ? 'active' : '' }}" title="Settings">
                <div class="icon-box">
                    <i class="fa-solid fa-gear text-base"></i>
                </div>
                <span x-show="sidebarHover || isPinned || mobileSidebarOpen" x-transition.opacity class="ml-3 whitespace-nowrap">Settings</span>
            </a>

        </nav>
        
        <!-- Bottom Help Section -->
        <div class="px-3 mt-auto pt-3 border-t border-gray-100">
            <a href="{{ route('contact') }}" target="_blank" class="sidebar-item" title="Support">
                <div class="icon-box">
                    <i class="fa-regular fa-circle-question text-base"></i>
                </div>
                <span x-show="sidebarHover || isPinned || mobileSidebarOpen" x-transition.opacity class="ml-3 whitespace-nowrap">Support & Help</span>
            </a>
        </div>
    </aside>

    <!-- Main Container -->
    <div class="flex-1 flex flex-col h-full overflow-hidden relative">
        
        <!-- Premium Top Navbar -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 shrink-0 z-20">
            
            <!-- Mobile Menu Toggle & Brand (Mobile only) -->
            <div class="flex items-center gap-3 md:hidden">
                <button @click="mobileSidebarOpen = true" class="text-gray-500 hover:text-[#4338ca] focus:outline-none transition-colors">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-8 object-contain">
            </div>

            <!-- Left: Global Search (Hidden on Mobile) -->
            <div class="hidden md:flex items-center w-full max-w-md ml-4">
                <div class="flex items-center bg-gray-50/80 border border-gray-200 rounded-lg px-3 py-1.5 w-full focus-within:bg-white focus-within:border-[#4338ca] focus-within:ring-2 focus-within:ring-[#eef2ff] transition-all">
                    <i class="fa-solid fa-magnifying-glass text-gray-400 mr-2 text-sm"></i>
                    <input type="text" class="topbar-input bg-transparent w-full border-none focus:ring-0 text-[13px] text-gray-800 placeholder-gray-400 outline-none" placeholder="Search by AWB / Order ID...">
                    <div class="ml-2 flex items-center justify-center bg-white text-gray-500 rounded px-1.5 py-0.5 text-[10px] font-bold border border-gray-200 shadow-sm whitespace-nowrap">Ctrl K</div>
                </div>
            </div>

            <!-- Right: Wallet, Activity, Profile -->
            <div class="flex items-center gap-2 sm:gap-4 ml-auto">
                
                <!-- Wallet Button -->
                <div class="flex items-center bg-[#eef2ff] border border-[#c7d2fe] rounded-lg pl-2 sm:pl-3 pr-1 py-1 h-9 sm:h-10">
                    <i class="fa-solid fa-wallet text-[#4338ca] mr-2"></i>
                    <div class="flex flex-col mr-2 sm:mr-3 leading-none">
                        <span class="hidden sm:block text-[9px] font-bold text-gray-500 uppercase">Usage Balance</span>
                        <span class="text-xs sm:text-sm font-extrabold text-gray-900">&#8377; {{ number_format(Auth::user()->wallet_balance ?? 0, 2) }}</span>
                    </div>
                    <!-- Trigger Recharge -->
                    <a href="{{ route('seller.wallet') }}" class="w-6 h-6 sm:w-7 sm:h-7 rounded-md bg-[#1e1b4b] text-white flex items-center justify-center hover:bg-black transition">
                        <i class="fa-solid fa-plus text-[10px] sm:text-xs font-bold"></i>
                    </a>
                </div>

                <div class="hidden sm:block w-px h-6 bg-gray-200 mx-1"></div>

                <!-- Activity Bell -->
                <button class="flex items-center gap-1 sm:gap-2 text-gray-600 hover:text-gray-900 font-semibold text-sm transition px-2 outline-none">
                    <i class="fa-regular fa-bell text-base sm:text-sm"></i> 
                    <span class="hidden sm:inline">Activity</span> 
                    <i class="fa-solid fa-chevron-down text-[10px] hidden sm:inline"></i>
                </button>

                <!-- User Profile & Logout Dropdown -->
                <div class="relative" x-data="{ openProfile: false }">
                    <div @click="openProfile = !openProfile" class="flex items-center gap-2 ml-1 sm:ml-2 pl-2 sm:pl-4 border-l border-gray-200 cursor-pointer select-none">
                        <div class="w-8 h-8 rounded-full bg-[#eef2ff] border border-[#c7d2fe] flex items-center justify-center text-[#4338ca] font-bold text-xs shrink-0">
                            {{ strtoupper(substr(Auth::user()->company_name ?? Auth::user()->name ?? 'S', 0, 1)) }}
                        </div>
                        <span class="text-sm font-semibold text-gray-700 hidden lg:block">{{ Auth::user()->company_name ?? Auth::user()->name ?? 'Company' }}</span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 hidden lg:block"></i>
                    </div>

                    <div x-show="openProfile" @click.away="openProfile = false" class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-2xl shadow-xl py-2 z-50 text-left" style="display: none;" x-transition>
                        
                        <!-- Header inside dropdown -->
                        <div class="px-4 py-3 border-b border-gray-100 mb-2">
                            <p class="text-xs font-bold text-gray-900 truncate">{{ Auth::user()->name ?? 'User' }}</p>
                            <p class="text-[10px] text-gray-500 truncate mt-0.5">{{ Auth::user()->email ?? '' }}</p>
                        </div>

                        <a href="{{ route('seller.settings') }}?view=company" class="flex items-center px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 hover:text-[#4338ca] transition">
                            <i class="fa-solid fa-user-tie w-5 text-gray-400"></i> Manage Profile
                        </a>
                        <a href="{{ route('seller.settings') }}" class="flex items-center px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 hover:text-[#4338ca] transition mb-2">
                            <i class="fa-solid fa-gear w-5 text-gray-400"></i> Global Settings
                        </a>
                        
                        <form action="{{ route('logout') }}" method="POST" class="border-t border-gray-100 pt-2">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 hover:text-red-700 transition outline-none flex items-center">
                                <i class="fa-solid fa-arrow-right-from-bracket w-5 text-red-400"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto relative">
            <div class="p-6 md:p-8 max-w-[1600px] mx-auto">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-lg font-semibold bg-green-50 border border-green-200 text-green-700 flex items-center shadow-sm">
                        <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 p-4 rounded-lg font-semibold bg-red-50 border border-red-200 text-red-700 flex items-center shadow-sm">
                        <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
