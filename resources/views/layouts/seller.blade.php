<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Seller Dashboard - OneStall Cargo')</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { --gold: #FFD700; --gold-deep: #D4AF37; --theme-bg: #ffffff; --theme-active: #FFD700; }
        body { font-family: 'Nunito', sans-serif; background-color: #f8fafc; color: #0f172a; }
        .sidebar-item { display: flex; align-items: center; gap: 12px; padding: 10px 16px; border-radius: 12px; color: #64748b; font-size: 13px; font-weight: 700; transition: all 0.2s ease; }
        .sidebar-item:hover { background-color: #f1f5f9; color: #0f172a; }
        .sidebar-item.active { background-color: rgba(255, 215, 0, 0.15); color: #b45309; }
    </style>
</head>
<body x-data="{ sidebarOpen: false }" class="h-screen flex overflow-hidden">
    
    <!-- Mobile Sidebar -->
    <div x-show="sidebarOpen" class="relative z-40 md:hidden" style="display: none;">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>
        <div class="fixed inset-0 z-40 flex">
            <div class="relative flex w-full max-w-xs flex-1 flex-col bg-white border-r border-gray-200">
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button type="button" @click="sidebarOpen = false" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:ring-2 focus:ring-white">
                        <i class="fa-solid fa-xmark text-white text-xl"></i>
                    </button>
                </div>
                <!-- Content same as desktop -->
            </div>
        </div>
    </div>

    <!-- Desktop sidebar -->
    <div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0 z-30 transition-all duration-300">
        <div class="flex flex-col flex-1 overflow-y-auto bg-white border-r border-gray-200 shadow-sm">
            <div class="flex items-center px-6 mb-6 mt-6 gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-[var(--gold)] flex items-center justify-center text-gray-900 font-bold">O</div>
                <div class="flex flex-col"><span class="font-extrabold text-gray-900 text-base">OneStall Cargo</span><span class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Merchant Portal</span></div>
            </div>

            <nav class="flex-1 space-y-1 px-4 pb-4">
                <a href="{{ route('seller.dashboard') }}" class="sidebar-item {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-house w-4 text-center"></i> <span>Dashboard</span></a>
                
                <div class="px-2 mt-6 mb-2"><div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Shipments</div></div>
                <a href="{{ route('seller.book') }}" class="sidebar-item {{ request()->routeIs('seller.book') ? 'active' : '' }}"><i class="fa-solid fa-plus-circle w-4 text-center"></i> <span>Book Shipment</span></a>
                <a href="{{ route('seller.bulk') }}" class="sidebar-item {{ request()->routeIs('seller.bulk') ? 'active' : '' }}"><i class="fa-solid fa-file-csv w-4 text-center"></i> <span>Bulk Booking</span></a>
                <a href="{{ route('seller.shipments.index') }}" class="sidebar-item {{ request()->routeIs('seller.shipments.*') ? 'active' : '' }}"><i class="fa-solid fa-boxes-stacked w-4 text-center"></i> <span>All Shipments</span></a>
                <a href="{{ route('seller.ndr') }}" class="sidebar-item {{ request()->routeIs('seller.ndr') ? 'active' : '' }}"><i class="fa-solid fa-triangle-exclamation w-4 text-center"></i> <span>NDR Action</span></a>
                
                <div class="px-2 mt-6 mb-2"><div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Finance</div></div>
                <a href="#" class="sidebar-item"><i class="fa-solid fa-wallet w-4 text-center"></i> <span>Wallet & Billing</span></a>
                <a href="#" class="sidebar-item"><i class="fa-solid fa-money-bill-transfer w-4 text-center"></i> <span>COD Remittance</span></a>
            </nav>
            
            <div class="p-4 border-t border-gray-100 shrink-0">
                <div class="flex items-center">
                    <div class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-gray-900 text-[var(--gold)] font-bold">{{ substr(Auth::user()->name ?? 'S', 0, 1) }}</div>
                    <div class="ml-3">
                        <p class="text-sm font-bold text-gray-900">{{ Auth::user()->name ?? 'Seller' }}</p>
                        <form action="{{ route('logout') }}" method="POST">@csrf <button type="submit" class="text-xs text-red-500 font-bold hover:text-red-700">Logout</button></form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="md:pl-64 flex flex-1 flex-col transition-all duration-300 h-full overflow-hidden">
        <div class="flex h-16 shrink-0 bg-white shadow-sm border-b border-gray-200">
            <button type="button" @click="sidebarOpen = true" class="px-4 text-gray-500 md:hidden border-r border-gray-200"><i class="fa-solid fa-bars text-xl"></i></button>
            <div class="flex flex-1 justify-between px-6 items-center">
                <h2 class="text-lg font-bold text-gray-800 hidden sm:block">Seller Dashboard</h2>
                <div class="font-bold text-sm text-gray-700 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">Wallet: <span class="text-green-600">₹{{ number_format(Auth::user()->wallet_balance ?? 0, 2) }}</span></div>
            </div>
        </div>
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 md:p-8">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl font-bold" style="background-color: #f0fdf4; border: 1px solid #16a34a; color: #16a34a;"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl font-bold" style="background-color: #fef2f2; border: 1px solid #dc2626; color: #dc2626;"><i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>


