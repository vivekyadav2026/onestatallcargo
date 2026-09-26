@extends('layouts.rider')
@section('content')
<div class="space-y-4">
    <div class="bg-gradient-to-r from-yellow-500 to-yellow-400 p-6 rounded-3xl text-white shadow-md relative overflow-hidden">
        <i class="fa-solid fa-coins absolute -right-4 -bottom-4 text-7xl opacity-20"></i>
        <div class="text-sm font-bold uppercase tracking-widest text-yellow-100">Total Collected Today</div>
        <div class="text-4xl font-black mt-2">â‚¹{{ number_format($totalCollected, 2) }}</div>
    </div>
    
    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-100 font-bold text-gray-900 bg-gray-50">COD Deliveries</div>
        <div class="divide-y divide-gray-100">
            @forelse($codShipments as $cod)
            <div class="p-4 flex justify-between items-center hover:bg-gray-50 transition">
                <div>
                    <div class="text-sm font-bold text-gray-900">{{ $cod->awb_number }}</div>
                    <div class="text-[10px] font-bold text-gray-400 uppercase mt-1">{{ $cod->updated_at->format('h:i A') }}</div>
                </div>
                <div class="text-green-600 font-bold bg-green-50 px-3 py-1.5 rounded-lg text-sm">+ â‚¹{{ number_format($cod->invoice_value, 2) }}</div>
            </div>
            @empty
            <div class="p-8 text-center text-gray-400">
                <i class="fa-solid fa-wallet text-4xl mb-2 text-gray-300"></i>
                <p class="font-bold text-sm">No COD collected today.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
