@extends('layouts.admin')
@section('title', 'Integrations & Notifications - OneStall Cargo')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">System Integrations</h1>
            <p class="text-sm text-gray-500 mt-1">Configure E-commerce Webhooks, Payment Gateways, and Notification APIs.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-700 font-bold rounded-xl border border-green-200 text-sm">
            <i class="fa-solid fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.integrations.save') }}" method="POST" class="space-y-8">
        @csrf

        <!-- Notification System (4.20) -->
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <h2 class="font-extrabold text-gray-800"><i class="fa-solid fa-bell text-yellow-500 mr-2"></i> Notification System (SMS / WhatsApp)</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Twilio / MSG91 API Key (SMS)</label>
                    <input type="text" value="msg91_live_xxxxxxxxxx" class="w-full text-sm font-bold px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:border-gray-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">WhatsApp Business API Token</label>
                    <input type="password" value="wa_live_xxxxxxxxxx" class="w-full text-sm font-bold px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:border-gray-500">
                </div>
                <div class="md:col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" checked class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                        <span class="text-sm font-bold text-gray-700">Enable automated WhatsApp updates to customers on 'Out for Delivery' and 'Delivered' events</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Payment Gateway (4.19) -->
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <h2 class="font-extrabold text-gray-800"><i class="fa-solid fa-credit-card text-blue-500 mr-2"></i> Payment Gateway (Wallet Recharge)</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Razorpay Key ID</label>
                    <input type="text" value="rzp_live_xxxxxxxxxx" class="w-full text-sm font-bold px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:border-gray-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Razorpay Key Secret</label>
                    <input type="password" value="rzp_secret_xxxxxxxxxx" class="w-full text-sm font-bold px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:border-gray-500">
                </div>
            </div>
        </div>

        <!-- E-commerce Global Webhooks (4.5) -->
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <h2 class="font-extrabold text-gray-800"><i class="fa-solid fa-cart-shopping text-purple-500 mr-2"></i> E-Commerce Platform Settings</h2>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-600 mb-6">Enable platform-wide sync for Shopify and WooCommerce plugins. Sellers will configure their individual store URLs in their own dashboards.</p>
                
                <div class="space-y-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" checked class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                        <span class="text-sm font-bold text-gray-700">Enable Shopify App Integration</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" checked class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                        <span class="text-sm font-bold text-gray-700">Enable WooCommerce REST API Sync</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-8 py-3 bg-[#1e293b] text-white font-bold rounded-xl shadow hover:bg-black transition">
                Save All Integrations
            </button>
        </div>
    </form>
</div>
@endsection
