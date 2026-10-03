@extends('layouts.hub')

@section('title', 'Support Tickets - OneStall Cargo')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Help & Support</h1>
            <p class="text-sm text-gray-500 mt-1">Raise tickets and get assistance from the Admin team</p>
        </div>
        <button class="w-full sm:w-auto px-5 py-2.5 bg-[var(--gold)] text-gray-900 font-bold rounded-xl shadow-sm hover:bg-yellow-500 transition text-sm" onclick="alert('Ticket creation will be enabled in the next update.')">
            <i class="fa-solid fa-plus mr-1"></i> New Ticket
        </button>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6 sm:p-12 text-center text-gray-400">
            <div class="text-5xl mb-4 text-gray-200"><i class="fa-solid fa-headset"></i></div>
            <h3 class="text-lg font-bold text-gray-700 mb-1">No Active Support Tickets</h3>
            <p class="text-sm">You haven't raised any support tickets yet.</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100 flex items-start gap-4">
            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-blue-500 text-xl shrink-0 shadow-sm">
                <i class="fa-solid fa-phone"></i>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 mb-1">Priority Helpline</h4>
                <p class="text-sm text-gray-600 mb-2">For urgent NDR resolutions and dispatch issues, call the central helpline.</p>
                <div class="font-mono font-bold text-blue-700">1800-123-4567</div>
            </div>
        </div>
        <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200 flex items-start gap-4">
            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-gray-600 text-xl shrink-0 shadow-sm">
                <i class="fa-solid fa-envelope"></i>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 mb-1">Email Support</h4>
                <p class="text-sm text-gray-600 mb-2">Send us an email for general inquiries, finance reports, and documentation.</p>
                <div class="font-mono font-bold text-gray-700">support@onestatallcargo.com</div>
            </div>
        </div>
    </div>
</div>
@endsection
