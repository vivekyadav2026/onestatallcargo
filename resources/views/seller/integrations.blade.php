@extends('layouts.seller')
@section('title', 'E-commerce Integrations - OneStall Cargo')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">E-commerce Integrations</h1>
            <p class="text-sm text-gray-500 mt-1">Connect your online store to automate order fetching and status syncing.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-700 font-bold rounded-xl border border-green-200 text-sm">
            <i class="fa-solid fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Shopify -->
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 bg-[#95BF47] text-white flex justify-between items-center">
                <h2 class="font-extrabold text-xl"><i class="fa-brands fa-shopify mr-2 text-2xl"></i> Shopify</h2>
                <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-bold">Active</span>
            </div>
            <div class="p-6 flex-1">
                <p class="text-sm text-gray-600 mb-6">Install our Shopify app to automatically pull new unfulfilled orders directly into OneStall Cargo.</p>
                <form action="{{ route('seller.integrations.save') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Store URL (.myshopify.com)</label>
                        <input type="text" placeholder="your-store.myshopify.com" class="w-full text-sm font-bold px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Admin API Access Token</label>
                        <input type="password" placeholder="shpat_..." class="w-full text-sm font-bold px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:border-green-500">
                    </div>
                    <button type="submit" class="w-full py-3 bg-[#95BF47] text-white font-bold rounded-xl shadow hover:bg-[#86ac3f] transition mt-4">
                        Connect Shopify
                    </button>
                </form>
            </div>
        </div>

        <!-- WooCommerce -->
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 bg-[#96588a] text-white flex justify-between items-center">
                <h2 class="font-extrabold text-xl"><i class="fa-brands fa-wordpress mr-2 text-2xl"></i> WooCommerce</h2>
                <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-bold">Active</span>
            </div>
            <div class="p-6 flex-1">
                <p class="text-sm text-gray-600 mb-6">Connect your WordPress WooCommerce store via REST API keys to automate tracking updates.</p>
                <form action="{{ route('seller.integrations.save') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Store Base URL</label>
                        <input type="text" placeholder="https://yourwebsite.com" class="w-full text-sm font-bold px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:border-purple-500">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Consumer Key</label>
                            <input type="text" placeholder="ck_..." class="w-full text-sm font-bold px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Consumer Secret</label>
                            <input type="password" placeholder="cs_..." class="w-full text-sm font-bold px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:border-purple-500">
                        </div>
                    </div>
                    <button type="submit" class="w-full py-3 bg-[#96588a] text-white font-bold rounded-xl shadow hover:bg-[#7b4670] transition mt-4">
                        Connect WooCommerce
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
