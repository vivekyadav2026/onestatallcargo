@extends('layouts.rider')
@section('content')
<div class="space-y-4">
    <!-- Header Card -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-200 text-center relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-[var(--theme-active)] to-indigo-600"></div>
        
        <div class="relative z-10">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" class="w-24 h-24 rounded-full object-cover mx-auto shadow-xl border-4 border-white mb-3">
            @else
                <div class="w-24 h-24 bg-white text-[var(--theme-active)] text-4xl font-black rounded-full flex items-center justify-center mx-auto shadow-xl border-4 border-[var(--theme-active)] mb-3">
                    {{ substr($user->name, 0, 1) }}
                </div>
            @endif
            <h2 class="text-2xl font-black text-gray-900">{{ $user->name }}</h2>
            <p class="text-xs text-[var(--theme-active)] font-black uppercase tracking-widest mt-1 bg-indigo-50 inline-block px-3 py-1 rounded-full">{{ str_replace('_', ' ', $user->role) }}</p>
            <p class="text-sm text-gray-600 mt-2 font-bold"><i class="fa-solid fa-phone mr-1"></i> {{ $user->phone ?? 'No Phone' }} &nbsp;|&nbsp; <i class="fa-solid fa-envelope mr-1"></i> {{ $user->email }}</p>
        </div>
    </div>
    
    <!-- Vehicle & Hub Info -->
    @if($rider)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3 border-b border-gray-100 pb-2">Duty Information</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <div class="text-[10px] text-gray-500 uppercase font-bold">Vehicle Type</div>
                <div class="text-sm font-black text-gray-900">{{ $rider->vehicle_type ?? 'N/A' }}</div>
            </div>
            <div>
                <div class="text-[10px] text-gray-500 uppercase font-bold">Vehicle Number</div>
                <div class="text-sm font-black text-gray-900">{{ $rider->vehicle_number ?? 'N/A' }}</div>
            </div>
            <div class="col-span-2">
                <div class="text-[10px] text-gray-500 uppercase font-bold">Assigned Hub</div>
                <div class="text-sm font-black text-gray-900">{{ $rider->hub->name ?? 'Central HQ' }} <span class="text-xs text-gray-500 font-normal">({{ $rider->hub->city ?? 'Default' }})</span></div>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistics -->
    <div class="grid grid-cols-2 gap-3">
        <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100 text-center shadow-sm">
            <i class="fa-solid fa-box-check text-2xl text-blue-500 mb-2"></i>
            <div class="text-2xl font-black text-blue-900">{{ $totalDeliveries }}</div>
            <div class="text-[9px] font-bold text-blue-600 uppercase tracking-wider">Lifetime Deliveries</div>
        </div>
        <div class="bg-purple-50 p-4 rounded-2xl border border-purple-100 text-center shadow-sm">
            <i class="fa-solid fa-truck-ramp-box text-2xl text-purple-500 mb-2"></i>
            <div class="text-2xl font-black text-purple-900">{{ $totalPickups }}</div>
            <div class="text-[9px] font-bold text-purple-600 uppercase tracking-wider">Lifetime Pickups</div>
        </div>
    </div>
    
    <!-- Today's Performance -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3 border-b border-gray-100 pb-2">Today's Performance</h3>
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                    <i class="fa-solid fa-check-double text-lg"></i>
                </div>
                <div>
                    <div class="text-[10px] text-gray-500 font-bold uppercase">Deliveries Done Today</div>
                    <div class="text-lg font-black text-gray-900">{{ $todayDeliveries }}</div>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600">
                    <i class="fa-solid fa-indian-rupee-sign text-lg"></i>
                </div>
                <div>
                    <div class="text-[10px] text-gray-500 font-bold uppercase">COD Collected Today</div>
                    <div class="text-lg font-black text-gray-900">&#8377;{{ number_format($todayCodCollected, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="mt-3 bg-gray-50 p-2 text-center rounded-lg border border-gray-100">
            <p class="text-[9px] text-gray-500 font-bold italic">Deposit the collected COD amount to your Hub Manager by end of day.</p>
        </div>
    </div>

    <!-- Navigation -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <a href="{{ route('rider.history') }}" class="flex justify-between items-center p-4 border-b border-gray-100 hover:bg-gray-50 transition">
            <span class="font-bold text-sm text-gray-700"><i class="fa-solid fa-clock-rotate-left text-[var(--theme-active)] mr-2 w-5 text-center"></i> Task History</span>
            <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
        </a>
        <a href="{{ route('rider.settings') }}" class="flex justify-between items-center p-4 border-b border-gray-100 hover:bg-gray-50 transition">
            <span class="font-bold text-sm text-gray-700"><i class="fa-solid fa-gear text-[var(--theme-active)] mr-2 w-5 text-center"></i> Account Settings</span>
            <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
        </a>
        <form action="{{ route('logout') }}" method="POST" class="p-4 hover:bg-red-50 transition cursor-pointer" onclick="this.submit()">
            @csrf
            <span class="font-bold text-sm text-red-600"><i class="fa-solid fa-power-off mr-2 w-5 text-center"></i> Secure Logout</span>
        </form>
    </div>
</div>
@endsection