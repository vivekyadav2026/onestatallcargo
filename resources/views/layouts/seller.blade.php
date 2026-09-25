<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Seller Dashboard - OneStall Cargo')</title>
    <!-- Use Inter font for premium tech feel -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f5f9; color: #1e293b; }
        
        /* Sidebar Item Base */
        .sidebar-item { display: flex; align-items: center; border-radius: 12px; color: #64748b; font-size: 14px; font-weight: 600; transition: all 0.2s; margin-bottom: 8px; cursor: pointer; }
        .sidebar-item:hover { background-color: #e2e8f0; color: #0f172a; }
        .sidebar-item.active { background-color: #eef2ff; color: #4338ca; }
        
        /* Collapsed Mode */
        .sidebar-collapsed .sidebar-item { justify-content: center; width: 44px; height: 44px; margin-left: auto; margin-right: auto; }
        .sidebar-collapsed .sidebar-text { display: none; }
        
        /* Expanded Mode */
        .sidebar-expanded .sidebar-item { justify-content: flex-start; width: 100%; height: 44px; padding: 0 14px; gap: 12px; }
        .sidebar-expanded .sidebar-text { display: block; }

        .topbar-input { background: transparent; border: none; outline: none; width: 100%; font-size: 13px; color: #475569; }
        .topbar-input::placeholder { color: #94a3b8; font-weight: 500; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="h-screen flex overflow-hidden" x-data="{ sidebarExpanded: true }">
    
    <!-- Minimal Left Sidebar with Toggle Support -->
    <aside :class="sidebarExpanded ? 'w-64 sidebar-expanded' : 'w-[72px] sidebar-collapsed'" class="bg-white border-r border-gray-200 flex flex-col py-4 z-30 shrink-0 transition-all duration-300 relative">
        
        <!-- Toggle Button -->
        <button @click="sidebarExpanded = !sidebarExpanded" class="absolute -right-3 top-6 w-6 h-6 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-500 hover:text-[#4338ca] shadow-sm z-40 transition-transform" :class="sidebarExpanded ? 'rotate-180' : ''">
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
        </button>

        <!-- Logo -->
        <div class="px-3 mb-8 flex items-center" :class="sidebarExpanded ? 'justify-start ml-2' : 'justify-center'">
            <a href="{{ route('seller.dashboard') }}" class="w-10 h-10 rounded-xl bg-[#0f172a] text-white flex items-center justify-center font-bold text-xl shadow-sm shrink-0">
                N
            </a>
            <span x-show="sidebarExpanded" class="ml-3 font-extrabold text-[#0f172a] text-lg tracking-tight whitespace-nowrap" style="display:none;">OneStall</span>
        </div>
        
        <!-- Navigation Icons -->
        <nav class="flex flex-col flex-1 w-full px-3 overflow-y-auto overflow-x-hidden">
            <a href="{{ route('seller.dashboard') }}" class="sidebar-item {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}" title="Dashboard">
                <i class="fa-solid fa-border-all text-lg w-5 text-center"></i>
                <span class="sidebar-text whitespace-nowrap">Dashboard</span>
            </a>
            <a href="{{ route('seller.shipments.index') }}" class="sidebar-item {{ request()->routeIs('seller.shipments.*') ? 'active' : '' }}" title="Orders">
                <i class="fa-solid fa-box text-lg w-5 text-center"></i>
                <span class="sidebar-text whitespace-nowrap">Orders</span>
            </a>
            <a href="{{ route('seller.book') }}" class="sidebar-item {{ request()->routeIs('seller.book') ? 'active' : '' }}" title="Book Shipment">
                <i class="fa-solid fa-plus text-lg w-5 text-center"></i>
                <span class="sidebar-text whitespace-nowrap">Book Shipment</span>
            </a>
            <a href="{{ route('seller.ndr') }}" class="sidebar-item {{ request()->routeIs('seller.ndr') ? 'active' : '' }}" title="NDR Management">
                <i class="fa-solid fa-rotate-left text-lg w-5 text-center"></i>
                <span class="sidebar-text whitespace-nowrap">NDR Management</span>
            </a>
            <a href="{{ route('seller.tools') }}" class="sidebar-item {{ request()->routeIs('seller.tools') ? 'active' : '' }}" title="Tools & Calculator">
                <i class="fa-solid fa-wrench text-lg w-5 text-center"></i>
                <span class="sidebar-text whitespace-nowrap">Tools</span>
            </a>
            <a href="{{ route('seller.wallet') }}" class="sidebar-item {{ request()->routeIs('seller.wallet') ? 'active' : '' }}" title="Billing & Wallet">
                <i class="fa-solid fa-indian-rupee-sign text-lg w-5 text-center"></i>
                <span class="sidebar-text whitespace-nowrap">Billing</span>
            </a>
            <a href="{{ route('seller.settings') }}" class="sidebar-item {{ request()->routeIs('seller.settings') ? 'active' : '' }}" title="Settings">
                <i class="fa-solid fa-gear text-lg w-5 text-center"></i>
                <span class="sidebar-text whitespace-nowrap">Settings</span>
            </a>
        </nav>
        
        <!-- Bottom Help Icon -->
        <div class="px-3 mt-auto">
            <a href="#" class="sidebar-item" title="Support">
                <i class="fa-regular fa-circle-question text-lg w-5 text-center"></i>
                <span class="sidebar-text whitespace-nowrap">Support & Help</span>
            </a>
        </div>
    </aside>

    <!-- Main Container -->
    <div class="flex-1 flex flex-col h-full overflow-hidden relative">
        
        <!-- Premium Top Navbar -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shrink-0 z-20">
            
            <!-- Left: Global Search -->
            <div class="flex items-center w-full max-w-md">
                <div class="flex items-center bg-white border border-gray-300 rounded-lg px-3 py-2 w-full focus-within:border-[#4338ca] focus-within:ring-1 focus-within:ring-[#4338ca] transition-all">
                    <i class="fa-solid fa-magnifying-glass text-gray-400 mr-2 text-sm"></i>
                    <input type="text" class="topbar-input" placeholder="Search by AWB / Order ID / Buyer Mobile No">
                    <div class="ml-2 flex items-center justify-center bg-gray-100 text-gray-500 rounded px-1.5 py-0.5 text-[10px] font-bold border border-gray-200">?K</div>
                </div>
            </div>

            <!-- Right: Wallet, Activity, Profile -->
            <div class="flex items-center gap-4">
                
                <!-- Wallet Button -->
                <div class="flex items-center bg-[#eef2ff] border border-[#c7d2fe] rounded-lg pl-3 pr-1 py-1 h-10">
                    <i class="fa-solid fa-wallet text-[#4338ca] mr-2"></i>
                    <div class="flex flex-col mr-3 leading-none">
                        <span class="text-[9px] font-bold text-gray-500 uppercase">Available Usage Balance</span>
                        <span class="text-sm font-extrabold text-gray-900">&#8377; {{ number_format(Auth::user()->wallet_balance ?? 0, 2) }} <i class="fa-solid fa-circle-info text-gray-400 text-[10px] ml-0.5"></i></span>
                    </div>
                    <!-- Trigger Recharge Modal/Page -->
                    <a href="{{ route('seller.wallet') }}" class="w-7 h-7 rounded-md bg-[#1e1b4b] text-white flex items-center justify-center hover:bg-black transition">
                        <i class="fa-solid fa-plus text-xs font-bold"></i>
                    </a>
                </div>

                <div class="w-px h-6 bg-gray-200 mx-1"></div>

                <!-- Activity Bell -->
                <button class="flex items-center gap-2 text-gray-600 hover:text-gray-900 font-semibold text-sm transition">
                    <i class="fa-regular fa-bell"></i> Activity <i class="fa-solid fa-chevron-down text-[10px]"></i>
                </button>

                <!-- User Profile -->
                <div class="flex items-center gap-2 ml-2 pl-4 border-l border-gray-200 cursor-pointer">
                    <div class="w-8 h-8 rounded-full bg-gray-100 border border-gray-300 flex items-center justify-center text-gray-600">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 hidden sm:block">{{ Auth::user()->company_name ?? Auth::user()->name ?? 'Company' }}</span>
                    <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 hidden sm:block"></i>
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
