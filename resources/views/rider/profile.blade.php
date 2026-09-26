@extends('layouts.rider')
@section('content')
<div class="space-y-4">
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-200 text-center">
        <div class="w-20 h-20 bg-[var(--theme-active)] text-white text-3xl font-black rounded-full flex items-center justify-center mx-auto shadow-md mb-4">{{ substr($user->name, 0, 1) }}</div>
        <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
        <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">{{ str_replace('_', ' ', $user->role) }}</p>
        <p class="text-sm text-gray-600 mt-2"><i class="fa-solid fa-phone mr-1"></i> {{ $user->phone ?? 'No Phone' }}</p>
    </div>
    
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100 text-center">
            <i class="fa-solid fa-box-check text-2xl text-blue-500 mb-2"></i>
            <div class="text-2xl font-black text-blue-900">{{ $totalDeliveries }}</div>
            <div class="text-[10px] font-bold text-blue-600 uppercase tracking-wider">Total Delivered</div>
        </div>
        <div class="bg-green-50 p-4 rounded-2xl border border-green-100 text-center">
            <i class="fa-solid fa-location-crosshairs text-2xl text-green-500 mb-2"></i>
            <div class="text-sm font-black text-green-900 mt-1">Active</div>
            <div class="text-[10px] font-bold text-green-600 uppercase tracking-wider">GPS Tracking</div>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
        <a href="{{ route('rider.history') }}" class="flex justify-between items-center p-4 border-b border-gray-100 hover:bg-gray-50">
            <span class="font-bold text-sm text-gray-700"><i class="fa-solid fa-clock-rotate-left mr-2 w-4 text-center"></i> History</span>
            <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
        </a>
        <a href="{{ route('rider.settings') }}" class="flex justify-between items-center p-4 border-b border-gray-100 hover:bg-gray-50">
            <span class="font-bold text-sm text-gray-700"><i class="fa-solid fa-gear mr-2 w-4 text-center"></i> Settings</span>
            <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
        </a>
        <form action="{{ route('logout') }}" method="POST" class="p-4 hover:bg-red-50 transition cursor-pointer" onclick="this.submit()">
            @csrf
            <span class="font-bold text-sm text-red-600"><i class="fa-solid fa-arrow-right-from-bracket mr-2 w-4 text-center"></i> Logout</span>
        </form>
    </div>
</div>
@endsection
