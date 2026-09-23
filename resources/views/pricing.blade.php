@extends('layouts.app')
@section('title', 'Pricing - OneStall Cargo')

@section('content')
<div class="bg-gray-50 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl">Transparent Shipping Rates</h1>
        <p class="mt-4 text-xl text-gray-500 max-w-2xl mx-auto">No hidden fees, no complex contracts. Just simple, predictable pricing based on weight and distance.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 -mt-16">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Local -->
        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden transform hover:-translate-y-1 transition duration-300">
            <div class="p-8 bg-blue-50 border-b border-blue-100">
                <h3 class="text-xl font-extrabold text-blue-900">Local (Intra-city)</h3>
                <p class="text-blue-700 text-sm mt-2">Delivery within the same city.</p>
                <div class="mt-4 flex items-baseline text-5xl font-extrabold text-gray-900">
                    ₹35
                    <span class="ml-1 text-xl font-medium text-gray-500">/ 500g</span>
                </div>
            </div>
            <div class="p-8">
                <ul class="space-y-4 text-sm text-gray-600">
                    <li class="flex items-center"><i class="fa-solid fa-check text-green-500 mr-3"></i> Same-day or Next-day pickup</li>
                    <li class="flex items-center"><i class="fa-solid fa-check text-green-500 mr-3"></i> Real-time tracking</li>
                    <li class="flex items-center"><i class="fa-solid fa-check text-green-500 mr-3"></i> +₹30 per extra 500g</li>
                </ul>
            </div>
        </div>

        <!-- Zonal -->
        <div class="bg-[#1e293b] rounded-3xl shadow-xl border border-gray-800 overflow-hidden transform hover:-translate-y-1 transition duration-300 relative scale-105 z-10">
            <div class="absolute top-0 inset-x-0 h-2 bg-[#FFD700]"></div>
            <div class="p-8 border-b border-gray-800">
                <span class="bg-[#FFD700] text-gray-900 text-[10px] font-bold uppercase tracking-widest py-1 px-3 rounded-full mb-4 inline-block">Most Popular</span>
                <h3 class="text-xl font-extrabold text-white">Zonal (Intra-state)</h3>
                <p class="text-gray-400 text-sm mt-2">Delivery within the same state or adjacent regions.</p>
                <div class="mt-4 flex items-baseline text-5xl font-extrabold text-white">
                    ₹45
                    <span class="ml-1 text-xl font-medium text-gray-400">/ 500g</span>
                </div>
            </div>
            <div class="p-8">
                <ul class="space-y-4 text-sm text-gray-300">
                    <li class="flex items-center"><i class="fa-solid fa-check text-[#FFD700] mr-3"></i> 2-3 Day Delivery</li>
                    <li class="flex items-center"><i class="fa-solid fa-check text-[#FFD700] mr-3"></i> Free API Access</li>
                    <li class="flex items-center"><i class="fa-solid fa-check text-[#FFD700] mr-3"></i> +₹40 per extra 500g</li>
                    <li class="flex items-center"><i class="fa-solid fa-check text-[#FFD700] mr-3"></i> COD enabled (2% fee)</li>
                </ul>
                <a href="{{ route('register') }}" class="mt-8 block w-full bg-[#FFD700] text-gray-900 font-extrabold text-center py-3 rounded-xl hover:bg-[#E5C100] transition">Create Free Account</a>
            </div>
        </div>

        <!-- National -->
        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden transform hover:-translate-y-1 transition duration-300">
            <div class="p-8 bg-purple-50 border-b border-purple-100">
                <h3 class="text-xl font-extrabold text-purple-900">National</h3>
                <p class="text-purple-700 text-sm mt-2">Cross-country delivery.</p>
                <div class="mt-4 flex items-baseline text-5xl font-extrabold text-gray-900">
                    ₹65
                    <span class="ml-1 text-xl font-medium text-gray-500">/ 500g</span>
                </div>
            </div>
            <div class="p-8">
                <ul class="space-y-4 text-sm text-gray-600">
                    <li class="flex items-center"><i class="fa-solid fa-check text-green-500 mr-3"></i> Surface & Air options</li>
                    <li class="flex items-center"><i class="fa-solid fa-check text-green-500 mr-3"></i> Multi-hub routing</li>
                    <li class="flex items-center"><i class="fa-solid fa-check text-green-500 mr-3"></i> +₹60 per extra 500g</li>
                </ul>
            </div>
        </div>

    </div>
</div>
@endsection
