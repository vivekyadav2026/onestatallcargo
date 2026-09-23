<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Console - OneStall Cargo')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --gold: #FFD700; --gold-deep: #D4AF37; --bg-body: #f8fafc;
            --text-main: #0f172a; --theme-bg: #1e293b; --theme-text: #94a3b8;
            --theme-active: #FFD700; --theme-active-text: #0f172a;
        }
        body { font-family: 'Nunito', sans-serif; background-color: var(--bg-body); color: var(--text-main); }
        .sidebar-item { display: flex; align-items: center; gap: 12px; padding: 10px 16px; border-radius: 12px; color: var(--theme-text); font-size: 13px; font-weight: 700; transition: all 0.2s ease; }
        .sidebar-item:hover { background-color: rgba(255, 255, 255, 0.05); color: white; }
        .sidebar-item.active { background-color: rgba(255, 215, 0, 0.1); color: var(--theme-active); }
        .sidebar-item.active i { color: var(--theme-active); }
    </style>
</head>
<body x-data="{ sidebarOpen: false }" class="h-screen bg-gray-50 flex overflow-hidden">
    
    <!-- Mobile Sidebar -->
    <div x-show="sidebarOpen" class="relative z-40 md:hidden" style="display: none;">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>
        <div class="fixed inset-0 z-40 flex">
            <div class="relative flex w-full max-w-xs flex-1 flex-col" style="background-color: var(--theme-bg);">
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button type="button" @click="sidebarOpen = false" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:ring-2 focus:ring-white">
                        <i class="fa-solid fa-xmark text-white text-xl"></i>
                    </button>
                </div>

                <div class="h-0 flex-1 overflow-y-auto pt-5 pb-4">
                    <div class="flex items-center px-4 mb-6 text-white gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-white p-1 flex items-center justify-center"><div class="w-full h-full bg-[var(--gold)] rounded-md flex items-center justify-center text-gray-900 font-bold">O</div></div>
                        <div class="flex flex-col"><span class="font-extrabold text-base">OneStall Cargo</span><span class="text-[10px] text-[var(--gold)] font-bold uppercase tracking-wider">Admin Console</span></div>
                    </div>

                    <nav class="mt-5 space-y-1 px-2">
                        <div class="px-2 mb-2"><div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Main Navigation</div></div>
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-chart-pie w-4 text-center"></i> <span>Overview</span></a>

                        <div class="px-2 mt-6 mb-2"><div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Operations</div></div>
                        <a href="{{ route('admin.shipments.index') }}" class="sidebar-item {{ request()->routeIs('admin.shipments.*') ? 'active' : '' }}"><i class="fa-solid fa-box w-4 text-center"></i> <span>Shipments</span></a>
                        <a href="{{ route('admin.pickups.index') }}" class="sidebar-item {{ request()->routeIs('admin.pickups.*') ? 'active' : '' }}"><i class="fa-solid fa-truck-fast w-4 text-center"></i> <span>Pickups</span></a>
                        <a href="{{ route('admin.ndr.index') }}" class="sidebar-item {{ request()->routeIs('admin.ndr.*') ? 'active' : '' }}"><i class="fa-solid fa-triangle-exclamation w-4 text-center"></i> <span>NDR & RTO</span></a>
                        <a href="{{ route('admin.evidence.index') }}" class="sidebar-item {{ request()->routeIs('admin.evidence.*') ? 'active' : '' }}"><i class="fa-solid fa-video w-4 text-center"></i> <span>Evidence DB</span></a>

                        <div class="px-2 mt-6 mb-2"><div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Network</div></div>
                        <a href="{{ route('admin.hubs.index') }}" class="sidebar-item {{ request()->routeIs('admin.hubs.*') ? 'active' : '' }}"><i class="fa-solid fa-building w-4 text-center"></i> <span>Hubs / Franchise</span></a>
                        <a href="{{ route('admin.couriers.index') }}" class="sidebar-item {{ request()->routeIs('admin.couriers.*') ? 'active' : '' }}"><i class="fa-solid fa-network-wired w-4 text-center"></i> <span>Couriers API</span></a>

                        <div class="px-2 mt-6 mb-2"><div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Business</div></div>
                        <a href="{{ route('admin.sellers.index') }}" class="sidebar-item {{ request()->routeIs('admin.sellers.*') ? 'active' : '' }}"><i class="fa-solid fa-users w-4 text-center"></i> <span>Sellers Directory</span></a>
                        <a href="{{ route('admin.rates.index') }}" class="sidebar-item {{ request()->routeIs('admin.rates.*') ? 'active' : '' }}"><i class="fa-solid fa-indian-rupee-sign w-4 text-center"></i> <span>Rate Engine</span></a>
                        <a href="{{ route('admin.billing.index') }}" class="sidebar-item {{ request()->routeIs('admin.billing.*') ? 'active' : '' }}"><i class="fa-solid fa-file-invoice-dollar w-4 text-center"></i> <span>Billing & COD</span></a>

                        <div class="px-2 mt-6 mb-2"><div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">System</div></div>
                        <a href="{{ route('admin.reports.index') }}" class="sidebar-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"><i class="fa-solid fa-file-invoice w-4 text-center"></i> <span>Reports</span></a>
                        <a href="{{ route('admin.roles.index') }}" class="sidebar-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"><i class="fa-solid fa-user-shield w-4 text-center"></i> <span>Roles & Permissions</span></a>
                    </nav>
                </div>
                
                <div class="p-4 border-t border-white/10">
                    <form action="{{ route('logout') }}" method="POST">@csrf <button type="submit" class="w-full text-left sidebar-item hover:text-white"><i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i> <span>Logout</span></button></form>
                </div>
            </div>
            <div class="w-14 shrink-0"></div>
        </div>
    </div>

    <!-- Desktop sidebar -->
    <div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0 z-30 transition-all duration-300">
        <div class="flex flex-col flex-1 overflow-y-auto" style="background-color: var(--theme-bg);">
            <div class="flex items-center px-4 mb-6 mt-5 text-white gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-white p-1 flex items-center justify-center"><div class="w-full h-full bg-[var(--gold)] rounded-md flex items-center justify-center text-gray-900 font-bold">O</div></div>
                <div class="flex flex-col"><span class="font-extrabold text-base">OneStall Cargo</span><span class="text-[10px] text-[var(--gold)] font-bold uppercase tracking-wider">Admin Console</span></div>
            </div>

            <nav class="flex-1 space-y-1 px-2 pb-4">
                <div class="px-2 mb-2"><div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Main Navigation</div></div>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-chart-pie w-4 text-center"></i> <span>Overview</span></a>

                <div class="px-2 mt-6 mb-2"><div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Operations</div></div>
                <a href="{{ route('admin.shipments.index') }}" class="sidebar-item {{ request()->routeIs('admin.shipments.*') ? 'active' : '' }}"><i class="fa-solid fa-box w-4 text-center"></i> <span>Shipments</span></a>
                <a href="{{ route('admin.pickups.index') }}" class="sidebar-item {{ request()->routeIs('admin.pickups.*') ? 'active' : '' }}"><i class="fa-solid fa-truck-fast w-4 text-center"></i> <span>Pickups</span></a>
                <a href="{{ route('admin.ndr.index') }}" class="sidebar-item {{ request()->routeIs('admin.ndr.*') ? 'active' : '' }}"><i class="fa-solid fa-triangle-exclamation w-4 text-center"></i> <span>NDR & RTO</span></a>
                <a href="{{ route('admin.evidence.index') }}" class="sidebar-item {{ request()->routeIs('admin.evidence.*') ? 'active' : '' }}"><i class="fa-solid fa-video w-4 text-center"></i> <span>Evidence DB</span></a>

                <div class="px-2 mt-6 mb-2"><div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Network</div></div>
                <a href="{{ route('admin.hubs.index') }}" class="sidebar-item {{ request()->routeIs('admin.hubs.*') ? 'active' : '' }}"><i class="fa-solid fa-building w-4 text-center"></i> <span>Hubs / Franchise</span></a>
                <a href="{{ route('admin.couriers.index') }}" class="sidebar-item {{ request()->routeIs('admin.couriers.*') ? 'active' : '' }}"><i class="fa-solid fa-network-wired w-4 text-center"></i> <span>Couriers API</span></a>

                <div class="px-2 mt-6 mb-2"><div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Business</div></div>
                <a href="{{ route('admin.sellers.index') }}" class="sidebar-item {{ request()->routeIs('admin.sellers.*') ? 'active' : '' }}"><i class="fa-solid fa-users w-4 text-center"></i> <span>Sellers Directory</span></a>
                <a href="{{ route('admin.rates.index') }}" class="sidebar-item {{ request()->routeIs('admin.rates.*') ? 'active' : '' }}"><i class="fa-solid fa-indian-rupee-sign w-4 text-center"></i> <span>Rate Engine</span></a>
                <a href="{{ route('admin.billing.index') }}" class="sidebar-item {{ request()->routeIs('admin.billing.*') ? 'active' : '' }}"><i class="fa-solid fa-file-invoice-dollar w-4 text-center"></i> <span>Billing & COD</span></a>

                <div class="px-2 mt-6 mb-2"><div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">System</div></div>
                <a href="{{ route('admin.reports.index') }}" class="sidebar-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"><i class="fa-solid fa-file-invoice w-4 text-center"></i> <span>Reports</span></a>
                        <a href="{{ route('admin.roles.index') }}" class="sidebar-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"><i class="fa-solid fa-user-shield w-4 text-center"></i> <span>Roles & Permissions</span></a>
            </nav>
            
            <div class="p-4 border-t border-white/10 shrink-0">
                <div class="flex items-center">
                    <div class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[var(--theme-active)] text-[var(--theme-active-text)] font-bold">{{ substr(Auth::user()->name ?? 'A', 0, 1) }}</div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-white">{{ Auth::user()->name ?? 'Administrator' }}</p>
                        <form action="{{ route('logout') }}" method="POST">@csrf <button type="submit" class="text-xs text-gray-400 hover:text-white"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</button></form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="md:pl-64 flex flex-1 flex-col transition-all duration-300 h-full overflow-hidden">
        <div class="flex h-16 shrink-0 bg-white shadow-sm border-b border-gray-200">
            <button type="button" @click="sidebarOpen = true" class="px-4 text-gray-500 md:hidden border-r border-gray-200"><i class="fa-solid fa-bars text-xl"></i></button>
            <div class="flex flex-1 justify-between px-4 items-center">
                <h2 class="text-lg font-bold text-gray-800 hidden sm:block">OneStall Cargo Command Center</h2>
            </div>
        </div>
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 md:p-8">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl" style="background-color: #f0fdf4; border: 1px solid #16a34a; color: #16a34a;"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
            @endif
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>

