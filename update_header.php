<?php
$file = __DIR__ . '/resources/views/layouts/seller.blade.php';
$content = file_get_contents($file);

$newHeader = <<<'HTML'
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

                    <div x-show="openProfile" @click.away="openProfile = false" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-xl py-2 z-50 text-left" style="display: none;" x-transition>
                        <a href="{{ route('seller.settings') }}" class="block px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50"><i class="fa-solid fa-user-gear mr-2 text-gray-400"></i> Account Settings</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 outline-none"><i class="fa-solid fa-arrow-right-from-bracket mr-2 text-red-400"></i> Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>
HTML;

$content = preg_replace('/<!-- Premium Top Navbar -->.*?<\/header>/is', ltrim($newHeader), $content);
file_put_contents($file, $content);
echo "Header updated!\n";
