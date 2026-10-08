
@extends('layouts.rider')
@section('title', 'Scan / Search Shipment')
@section('content')
<div class="space-y-6">
    <div class="bg-black text-white p-6 rounded-3xl text-center relative overflow-hidden h-40 flex flex-col justify-center shadow-lg border border-gray-800">
        <i class="fa-solid fa-qrcode text-4xl text-gray-700 animate-pulse mb-3"></i>
        <h2 class="text-lg font-bold">Camera Scanner Active</h2>
        <p class="text-xs text-gray-400 mt-1">Point at AWB Barcode</p>
        <div class="absolute inset-0 border-4 border-yellow-500 m-6 rounded-xl opacity-50"></div>
        <div class="absolute w-full h-0.5 bg-green-500 opacity-70 top-1/2 left-0" style="animation: scan 2s infinite alternate;"></div>
    </div>
    
    <div class="bg-white p-4 rounded-3xl shadow-sm border border-gray-200">
        <h3 class="font-bold text-gray-900 mb-2">Manual Entry Fallback</h3>
        <p class="text-[10px] text-gray-500 mb-3">If barcode is torn or unreadable, type the AWB number manually below.</p>
        <form action="{{ route('rider.scan') }}" method="GET" class="flex gap-2">
            <input type="text" name="awb" value="{{ $awb ?? '' }}" placeholder="Enter AWB Number" class="flex-1 bg-gray-50 border border-gray-200 px-4 py-3 rounded-xl outline-none text-sm font-bold uppercase focus:border-blue-500" required>
            <button type="submit" class="bg-[#1e293b] text-white px-5 rounded-xl font-bold hover:bg-black transition"><i class="fa-solid fa-search"></i></button>
        </form>
    </div>

    @if($awb)
        @if($shipment)
            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-5">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-black text-lg text-gray-900">{{ $shipment->awb_number }}</h3>
                        <p class="text-xs font-bold text-gray-500">{{ $shipment->receiver_name }}</p>
                    </div>
                    <span class="bg-blue-50 text-blue-700 text-[10px] px-2 py-1 rounded font-bold uppercase">{{ $shipment->status }}</span>
                </div>
                <div class="text-xs text-gray-600 mb-4"><i class="fa-solid fa-map-pin w-4"></i> {{ $shipment->delivery_city }} ({{ $shipment->delivery_pincode }})</div>
                
                @if($shipment->is_cod)
                    <div class="bg-yellow-50 text-yellow-800 p-3 rounded-xl text-xs font-bold mb-4">
                        <i class="fa-solid fa-indian-rupee-sign mr-1"></i> COD Collect: ?{{ number_format($shipment->total_amount, 2) }}
                    </div>
                @endif
                
                <div class="grid grid-cols-2 gap-3">
                    <form action="{{ route('rider.evidence.upload') }}" method="POST" class="col-span-2">
                        @csrf
                        <input type="hidden" name="awb_number" value="{{ $shipment->awb_number }}">
                        <input type="hidden" name="action_type" value="{{ in_array($shipment->status, ['Pending Pickup', 'Pickup Scheduled']) ? 'Pickup' : 'Delivery' }}">
                        <button type="submit" class="w-full py-3 bg-[#FFD700] hover:bg-[#e6c200] text-gray-900 font-extrabold rounded-xl transition">
                            <i class="fa-solid fa-check-circle mr-1"></i> 
                            {{ in_array($shipment->status, ['Pending Pickup', 'Pickup Scheduled']) ? 'Confirm Pickup' : 'Confirm Delivery' }}
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="p-6 bg-red-50 rounded-3xl border border-red-100 text-center text-red-700">
                <i class="fa-solid fa-triangle-exclamation text-3xl mb-2"></i>
                <p class="font-bold text-sm">No shipment found with AWB: {{ $awb }}</p>
                <p class="text-xs mt-1 opacity-80">Please check the number and try again.</p>
            </div>
        @endif
    @endif
</div>
<style>@keyframes scan { from { top: 10%; } to { top: 90%; } }</style>
@endsection

