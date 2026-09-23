@extends('layouts.admin')
@section('title', 'Couriers API - OneStall Cargo')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Couriers API Configuration</h1>
            <p class="text-sm text-gray-500 mt-1">Manage API keys and integration settings for 3rd-party logistics partners.</p>
        </div>
    </div>

    <!-- Add Courier Form -->
    <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm">
        <h2 class="font-bold text-gray-900 mb-4 border-b pb-2"><i class="fa-solid fa-plug mr-2"></i> Connect New Courier API</h2>
        <form action="{{ route('admin.couriers.store') }}" method="POST" class="flex flex-col md:flex-row gap-4">
            @csrf
            <div class="flex-1">
                <label class="block text-xs font-bold text-gray-700 mb-1">Courier Name</label>
                <input type="text" name="name" placeholder="e.g. Xpressbees" required class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:border-blue-500 outline-none">
            </div>
            <div class="flex-1">
                <label class="block text-xs font-bold text-gray-700 mb-1">API Code / Secret</label>
                <input type="text" name="api_code" placeholder="e.g. xb_secret_019" required class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:border-blue-500 outline-none">
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-6 py-2 bg-[#1e293b] text-white rounded-xl font-bold shadow-md hover:bg-black transition text-sm w-full md:w-auto h-[38px]">
                    <i class="fa-solid fa-link mr-1"></i> Add Partner
                </button>
            </div>
        </form>
    </div>

    <!-- Active Couriers List -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($couriers as $courier)
        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm space-y-4">
            <div class="flex justify-between items-center border-b border-gray-100 pb-4">
                <div class="font-bold text-lg text-gray-900">{{ $courier->name }} API</div>
                
                @if($courier->is_active)
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-50 text-green-700">Active</span>
                @else
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-50 text-red-700">Disabled</span>
                @endif
            </div>
            
            <div class="space-y-3 pt-2">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Production Token</label>
                    <input type="password" value="{{ $courier->api_code }}" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm bg-gray-50 text-gray-500" readonly>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 text-right">
                <form action="{{ route('admin.couriers.toggle', $courier->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-xs font-bold px-4 py-2 rounded-xl border {{ $courier->is_active ? 'border-red-200 text-red-600 hover:bg-red-50' : 'border-green-200 text-green-600 hover:bg-green-50' }} transition-colors">
                        <i class="fa-solid fa-power-off"></i> {{ $courier->is_active ? 'Disable API' : 'Enable API' }}
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
