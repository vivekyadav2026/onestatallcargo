@extends('layouts.hub')

@section('title', 'WhatsApp Integration - OneStall Cargo')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">WhatsApp Integration</h1>
            <p class="text-sm text-gray-500 mt-1">Manage automated WhatsApp alerts and messaging settings</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-green-100 shadow-sm p-4 sm:p-8 text-center max-w-3xl mx-auto mt-10">
        <div class="w-20 h-20 bg-green-50 text-green-500 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">
            <i class="fa-brands fa-whatsapp"></i>
        </div>
        <h2 class="text-xl font-bold text-gray-900 mb-2">WhatsApp Business API Setup</h2>
        <p class="text-gray-500 text-sm mb-8">Connect your WhatsApp Business account to send automated tracking updates, NDR alerts, and delivery confirmations to customers directly from your Hub.</p>
        
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                <i class="fa-solid fa-truck-fast text-gray-400 text-xl mb-2"></i>
                <h4 class="font-bold text-gray-800 text-xs uppercase mb-1">Dispatch Alerts</h4>
                <p class="text-[10px] text-gray-500">Notify customers when out for delivery</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                <i class="fa-solid fa-phone-slash text-gray-400 text-xl mb-2"></i>
                <h4 class="font-bold text-gray-800 text-xs uppercase mb-1">NDR Management</h4>
                <p class="text-[10px] text-gray-500">Automated messages for failed attempts</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                <i class="fa-solid fa-map-location-dot text-gray-400 text-xl mb-2"></i>
                <h4 class="font-bold text-gray-800 text-xs uppercase mb-1">Live Tracking</h4>
                <p class="text-[10px] text-gray-500">Share tracking links via WhatsApp</p>
            </div>
        </div>

        <button class="w-full sm:w-auto px-8 py-3 bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl shadow-md transition" onclick="alert('Please contact Admin to activate WhatsApp API for your Franchise account.')">
            Connect WhatsApp Account
        </button>
    </div>
</div>
@endsection
