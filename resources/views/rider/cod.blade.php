@extends('layouts.rider')
@section('title', 'COD Collection')

@section('content')
<div class="space-y-6" x-data="{ selectedDate: '{{ $selectedDate }}' }">
    <!-- Filter -->
    <div class="flex justify-between items-center px-2">
        <h2 class="text-xl font-black text-gray-900">COD Ledger</h2>
        <input type="date" x-model="selectedDate" @change="window.location.href='?date=' + selectedDate" class="bg-white border border-gray-200 text-gray-700 text-xs font-bold rounded-lg px-3 py-2 shadow-sm outline-none focus:border-yellow-500">
    </div>

    <!-- Header Card -->
    <div class="bg-[#D4AF37] rounded-2xl p-6 shadow-md relative overflow-hidden">
        <div class="relative z-10 text-white">
            <div class="text-sm font-extrabold tracking-wider uppercase mb-1">
                Total Collected <span x-text="selectedDate == '{{ today()->toDateString() }}' ? 'Today' : 'on ' + selectedDate"></span>
            </div>
            <div class="text-4xl font-black mt-2">&#8377;{{ number_format($totalCollected ?? 0, 2) }}</div>
        </div>
        <i class="fa-solid fa-coins absolute -bottom-4 -right-4 text-7xl text-white opacity-20"></i>
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
                <div class="text-green-600 font-bold bg-green-50 px-3 py-1.5 rounded-lg text-sm">+ &#8377;{{ number_format($cod->cod_amount > 0 ? $cod->cod_amount : $cod->invoice_value, 2) }}</div>
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
