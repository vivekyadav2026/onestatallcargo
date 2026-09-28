@extends('layouts.rider')
@section('title', 'COD Collection')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="bg-[#D4AF37] rounded-2xl p-6 shadow-md relative overflow-hidden">
        <div class="relative z-10 text-white">
            <div class="text-sm font-extrabold tracking-wider uppercase mb-1">Total Collected Today</div>
            <div class="text-4xl font-black mt-2">₹{{ number_format($totalCollected ?? 0, 2) }}</div>
        </div>
        <i class="fa-solid fa-coins absolute -bottom-4 -right-4 text-7xl text-white opacity-20"></i>
    </div>

    <!-- COD List -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-4">
        <h3 class="font-bold text-gray-900 mb-4">COD Deliveries</h3>
        
        <div class="space-y-4">
            @forelse($codShipments ?? [] as $cod)
            <div class="p-4 border border-gray-100 rounded-xl bg-gray-50 flex justify-between items-center">
                <div>
                    <div class="font-extrabold text-gray-900">{{ $cod->awb_number }}</div>
                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($cod->updated_at)->format('h:i A') }}</div>
                </div>
                <div class="text-green-600 font-bold bg-green-50 px-3 py-1.5 rounded-lg text-sm">+ ₹{{ number_format($cod->invoice_value ?? $cod->total_amount ?? 0, 2) }}</div>
            </div>
            @empty
            <div class="text-center py-10 text-gray-400">
                <i class="fa-solid fa-wallet text-4xl mb-3 text-gray-200"></i>
                <p class="text-sm font-bold">No COD collected today.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
