@extends('layouts.rider')
@section('content')
<div class="space-y-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-black text-gray-900">Task History</h1>
        <a href="{{ route('rider.profile') }}" class="text-xs font-bold text-gray-500 hover:text-gray-900">&larr; Back</a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="divide-y divide-gray-100">
            @forelse($history as $shipment)
            <div class="p-4 hover:bg-gray-50 transition">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <div class="text-sm font-black text-gray-900">{{ $shipment->awb_number }}</div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">{{ $shipment->updated_at->format('d M, h:i A') }}</div>
                    </div>
                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider 
                        {{ $shipment->status == 'Delivered' ? 'bg-green-100 text-green-700' : 
                          ($shipment->status == 'In Transit' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700') }}">
                        {{ $shipment->status }}
                    </span>
                </div>
                <div class="text-xs text-gray-600 font-bold"><i class="fa-solid fa-location-dot w-4 text-center mr-1"></i> {{ $shipment->delivery_city }}, {{ $shipment->delivery_state }}</div>
            </div>
            @empty
            <div class="p-8 text-center text-gray-400">
                <i class="fa-solid fa-clock-rotate-left text-4xl mb-2 text-gray-300"></i>
                <p class="font-bold text-sm">No task history found.</p>
            </div>
            @endforelse
        </div>
        
        @if($history->hasPages())
        <div class="p-4 bg-gray-50 border-t border-gray-100">{{ $history->links() }}</div>
        @endif
    </div>
</div>
@endsection
