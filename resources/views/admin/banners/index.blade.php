@extends('layouts.admin')

@section('title', 'Manage Promo Banners - OneStall Admin')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto" x-data="{
    title: '{{ addslashes($banner->title ?? '₹500 FREE Shipping Credits') }}',
    subtitle: '{{ addslashes($banner->subtitle ?? 'are sitting in your wallet.') }}',
    coupon: '{{ addslashes($banner->coupon_code ?? '') }}',
    btnText: '{{ addslashes($banner->button_text ?? 'Get My Free Credits') }}',
    bgClass: '{{ $banner->bg_gradient ?? 'from-[#1d4ed8] via-[#2563eb] to-[#3b82f6]' }}'
}">
    
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-2">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Seller Dashboard Banner Manager</h1>
            <p class="text-xs text-gray-500 mt-1">Configure live promotional banners displayed to sellers on their dashboard</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-700 font-bold rounded-xl border border-green-200 text-sm mb-4">
            <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 text-red-700 font-bold rounded-xl border border-red-200 text-sm mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Live Preview Card -->
    <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-sm space-y-3">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Live Preview</h3>
        
        <div class="bg-gradient-to-r rounded-2xl p-6 text-white shadow-md relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6" :class="bgClass">
            <div class="flex items-center gap-6">
                <div class="w-14 h-14 bg-white/20 border border-white/30 rounded-2xl flex items-center justify-center shrink-0 shadow-inner">
                    <i class="fa-solid fa-gift text-2xl text-white"></i>
                </div>
                <div>
                    <h2 class="text-lg md:text-xl font-black tracking-tight text-white mb-1" x-text="title"></h2>
                    <p class="text-xs text-blue-100 font-medium" x-text="subtitle"></p>
                </div>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <div x-show="coupon" class="bg-white text-gray-900 px-3 py-1.5 rounded-xl text-xs font-bold flex items-center gap-2 border border-blue-100">
                    <span class="text-gray-400 font-normal">Use code</span>
                    <span class="font-mono text-blue-700 tracking-wider" x-text="coupon"></span>
                </div>
                <a href="#" class="px-4 py-2 bg-black hover:bg-gray-900 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-1.5">
                    <span x-text="btnText"></span> <i class="fa-solid fa-bolt text-yellow-400"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Banner Form -->
    <form action="{{ route('admin.banners.store') }}" method="POST" class="bg-white rounded-3xl p-6 border border-gray-200 shadow-sm space-y-6">
        @csrf

        <div class="flex items-center justify-between border-b pb-4 border-gray-100">
            <h3 class="text-base font-extrabold text-gray-900">Banner Details</h3>
            
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ $banner->is_active ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded border-gray-300 accent-[#4338ca]">
                <span class="text-xs font-bold text-gray-700">Banner Active (Visible on Seller Dashboard)</span>
            </label>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-1 md:col-span-2">
                <label class="block text-xs font-bold text-gray-700">Banner Heading / Title *</label>
                <input type="text" name="title" x-model="title" value="{{ old('title', $banner->title) }}" required placeholder="e.g. ₹500 FREE Shipping Credits" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-medium focus:border-blue-500 outline-none">
            </div>

            <div class="space-y-1 md:col-span-2">
                <label class="block text-xs font-bold text-gray-700">Subtitle / Description *</label>
                <input type="text" name="subtitle" x-model="subtitle" value="{{ old('subtitle', $banner->subtitle) }}" required placeholder="e.g. are sitting in your wallet. Make first recharge to unlock." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-medium focus:border-blue-500 outline-none">
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-bold text-gray-700">Coupon Code <span class="text-gray-400 font-normal">(Optional)</span></label>
                <input type="text" name="coupon_code" x-model="coupon" value="{{ old('coupon_code', $banner->coupon_code) }}" placeholder="e.g. FIRST1000" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-medium focus:border-blue-500 outline-none">
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-bold text-gray-700">Button Text *</label>
                <input type="text" name="button_text" x-model="btnText" value="{{ old('button_text', $banner->button_text) }}" required placeholder="e.g. Get My Free Credits" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-medium focus:border-blue-500 outline-none">
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-bold text-gray-700">Button Link URL *</label>
                <input type="text" name="button_link" value="{{ old('button_link', $banner->button_link) }}" required placeholder="e.g. /seller/wallet" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-medium focus:border-blue-500 outline-none">
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-bold text-gray-700">Gradient Background Style *</label>
                <select name="bg_gradient" x-model="bgClass" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-medium focus:border-blue-500 outline-none">
                    <option value="from-[#1d4ed8] via-[#2563eb] to-[#3b82f6]" {{ $banner->bg_gradient == 'from-[#1d4ed8] via-[#2563eb] to-[#3b82f6]' ? 'selected' : '' }}>Royal Blue Gradient</option>
                    <option value="from-[#0f172a] via-[#1e1b4b] to-[#4338ca]" {{ $banner->bg_gradient == 'from-[#0f172a] via-[#1e1b4b] to-[#4338ca]' ? 'selected' : '' }}>Midnight Indigo Gradient</option>
                    <option value="from-[#15803d] via-[#16a34a] to-[#22c55e]" {{ $banner->bg_gradient == 'from-[#15803d] via-[#16a34a] to-[#22c55e]' ? 'selected' : '' }}>Emerald Green Gradient</option>
                    <option value="from-[#b91c1c] via-[#dc2626] to-[#f87171]" {{ $banner->bg_gradient == 'from-[#b91c1c] via-[#dc2626] to-[#f87171]' ? 'selected' : '' }}>Ruby Red Gradient</option>
                    <option value="from-[#c2410c] via-[#ea580c] to-[#fb923c]" {{ $banner->bg_gradient == 'from-[#c2410c] via-[#ea580c] to-[#fb923c]' ? 'selected' : '' }}>Sunset Orange Gradient</option>
                </select>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="px-6 py-3 bg-[#1e1b4b] hover:bg-black text-white font-extrabold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i> Update Promo Banner
            </button>
        </div>
    </form>
</div>
@endsection
