@extends('layouts.admin')
@section('title', 'Couriers API - OneStall Cargo')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Couriers API Configuration</h1>
            <p class="text-sm text-gray-500 mt-1">Manage secure API credentials for 3rd-party logistics partners (Delhivery, XpressBees, etc).</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-700 font-bold rounded-xl border border-green-200 text-sm">
            <i class="fa-solid fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="p-4 bg-red-50 text-red-700 font-bold rounded-xl border border-red-200 text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Add Courier Form -->
    <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm">
        <h2 class="font-bold text-gray-900 mb-4 border-b pb-2"><i class="fa-solid fa-plug mr-2"></i> Connect New Courier API</h2>
        <form action="{{ route('admin.couriers.store') }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Courier Name</label>
                    <input type="text" name="name" placeholder="e.g. Delhivery" required class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Environment Mode</label>
                    <select name="mode" required class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:border-blue-500 outline-none">
                        <option value="sandbox">Sandbox (Testing)</option>
                        <option value="production">Production (Live)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Base API URL</label>
                    <input type="url" name="credentials[api_url]" placeholder="https://track.delhivery.com" required class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">API Key / Token (Will be Encrypted)</label>
                    <input type="password" name="credentials[api_key]" placeholder="Paste secret token here" required class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:border-blue-500 outline-none">
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="px-6 py-2.5 bg-[#1e293b] text-white rounded-xl font-bold shadow-md hover:bg-black transition text-sm">
                    <i class="fa-solid fa-link mr-1"></i> Add Partner & Encrypt Keys
                </button>
            </div>
        </form>
    </div>

    <!-- Active Couriers List -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($couriers as $courier)
        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm space-y-4">
            <div class="flex justify-between items-center border-b border-gray-100 pb-4">
                <div class="font-bold text-lg text-gray-900 flex items-center">
                    {{ $courier->name }} 
                    @if($courier->mode === 'production')
                        <span class="ml-2 px-2 py-0.5 rounded text-[10px] bg-red-100 text-red-700 font-bold uppercase">LIVE</span>
                    @else
                        <span class="ml-2 px-2 py-0.5 rounded text-[10px] bg-yellow-100 text-yellow-700 font-bold uppercase">TEST</span>
                    @endif
                </div>
                
                @if($courier->is_active)
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-50 text-green-700 border border-green-200">Active</span>
                @else
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-500 border border-gray-200">Disabled</span>
                @endif
            </div>
            
            <div class="space-y-3 pt-2">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">API Endpoint</label>
                    <div class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm bg-gray-50 text-gray-600 truncate">
                        {{ $courier->api_credentials['api_url'] ?? 'N/A' }}
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Secret Key</label>
                    <div class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm bg-gray-50 text-gray-400 font-mono">
                        ******** (Encrypted in DB)
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 text-right">
                <form action="{{ route('admin.couriers.toggle', $courier->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-xs font-bold px-4 py-2 rounded-xl border {{ $courier->is_active ? 'border-red-200 text-red-600 hover:bg-red-50' : 'border-green-200 text-green-600 hover:bg-green-50' }} transition-colors shadow-sm">
                        <i class="fa-solid fa-power-off"></i> {{ $courier->is_active ? 'Disable Service' : 'Enable Service' }}
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
