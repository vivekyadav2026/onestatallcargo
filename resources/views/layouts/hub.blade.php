<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Franchise Hub - OneStall Cargo</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root { --gold: #FFD700; --gold-deep: #FDB931; }
    </style>
</head>
<body class="bg-gray-50 font-[Nunito] flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
    
    <!-- Mobile Sidebar overlay -->
    <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-20 lg:hidden" style="display: none;"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-64 bg-[#1e293b] text-white flex flex-col h-full shrink-0 shadow-2xl transition-transform duration-300 lg:static lg:translate-x-0">
        <div class="p-6 flex items-center justify-between gap-3 border-b border-gray-700/50">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-10 rounded bg-white p-1">
                <div>
                    <div class="font-extrabold text-sm leading-tight text-[var(--gold)]">HUB PORTAL</div>
                </div>
            </div>
            <!-- Close button for mobile -->
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                <i class="fa-solid fa-xmark fa-lg"></i>
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
            <div class="text-[10px] font-extrabold text-gray-500 uppercase tracking-widest mb-3 ml-2 mt-4">Operations</div>
            
            
            <a href="{{ route('hub.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-bold transition">
                <i class="fa-solid fa-qrcode w-5"></i> Scanner Desk
            </a>
            <a href="{{ route('hub.bagging.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-bold transition">
                <i class="fa-solid fa-boxes-packing w-5"></i> Dispatch & Bagging
            </a>
            <a href="{{ route('hub.fleet.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-bold transition">
                <i class="fa-solid fa-motorcycle w-5"></i> Fleet Management
            </a>
            <a href="{{ route('hub.assignments.pickups') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-bold transition">
                <i class="fa-solid fa-truck-pickup w-5"></i> Pickup Assignments
            </a>
            <a href="{{ route('hub.assignments.deliveries') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-bold transition">
                <i class="fa-solid fa-box-open w-5"></i> Delivery Assignments
            </a>
            <a href="{{ route('hub.ndr.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-bold transition">
                <i class="fa-solid fa-rotate-left w-5"></i> NDR & RTO
            </a>
            
            <!-- <div class="text-[10px] font-extrabold text-gray-500 uppercase tracking-widest mb-3 ml-2 mt-8">Business</div>
            
            <a href="{{ route('hub.wallet.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-bold transition">
                <i class="fa-solid fa-wallet w-5"></i> Wallet & Payout
            </a> -->

        </div>
        
        <div class="px-4 pb-2">
            <a href="{{ route('hub.profile') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-bold transition">
                <i class="fa-solid fa-user-gear w-5"></i> Profile & Settings
            </a>
        </div>
        
        <div class="p-4 border-t border-gray-700/50">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white rounded-xl font-bold transition">
                    <i class="fa-solid fa-power-off"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden w-full lg:w-auto">
        
        <!-- Topbar -->
        <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 lg:px-8 shrink-0 shadow-sm z-10">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden text-gray-600 hover:text-gray-900 focus:outline-none">
                    <i class="fa-solid fa-bars fa-xl"></i>
                </button>
                <h2 class="text-lg lg:text-xl font-extrabold text-gray-900 truncate max-w-[150px] lg:max-w-xs">{{ Auth::user()->name }}</h2>
                <span class="hidden sm:inline-block px-2.5 py-1 bg-blue-50 text-blue-700 text-[10px] font-bold uppercase rounded-full">Franchise Partner</span>
            </div>
            <div class="text-xs lg:text-sm font-bold text-gray-500 flex items-center gap-1 lg:gap-2">
                <i class="fa-solid fa-location-dot"></i> <span class="hidden sm:inline">Your Hub Network</span>
            </div>
        </header>
        
        <!-- Page Content -->
        <div class="flex-1 overflow-y-auto bg-gray-50 p-4 lg:p-8">
            @yield('content')
        </div>
        
    </main>
</body>
</html>

