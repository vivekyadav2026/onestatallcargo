@extends('layouts.seller')
@section('title', 'All Shipments - OneStall Cargo')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">All Shipments</h1>
            <p class="text-sm text-gray-500 mt-1">Manage and track all your booked parcels.</p>
        </div>
        <a href="{{ route('seller.book') }}" class="px-5 py-2.5 bg-[#1e293b] text-white font-bold rounded-xl shadow-md hover:bg-black transition text-sm">
            Book New Parcel
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                        <th class="px-6 py-4">AWB Number</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Receiver & Dest.</th>
                        <th class="px-6 py-4">Payment</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($shipments as $shipment)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-900">{{ $shipment->awb_number }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $shipment->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-700">{{ $shipment->receiver_name }}</div>
                            <div class="text-[10px] text-gray-400">{{ $shipment->delivery_city }}, {{ $shipment->delivery_pincode }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($shipment->is_cod)
                                <span class="text-yellow-600 font-bold text-xs">COD (₹{{ $shipment->invoice_value }})</span>
                            @else
                                <span class="text-green-600 font-bold text-xs">Prepaid</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700">{{ $shipment->status }}</span>
                        </td>
                        <td class="px-6 py-4 text-right flex justify-end gap-3">
                            <a href="{{ route('track') }}?awb_number={{ $shipment->awb_number }}" target="_blank" class="text-gray-500 hover:text-gray-900 font-bold text-xs"><i class="fa-solid fa-location-crosshairs"></i> Track</a>
                            
                            @if($shipment->shipment_type == 'cargo' || $shipment->shipment_type == 'b2b')
                                <a href="{{ route('seller.lr', $shipment->awb_number) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-bold text-xs"><i class="fa-solid fa-file-contract"></i> LR Note</a>
                            @elseif($shipment->shipment_type == 'international')
                                <a href="{{ route('seller.invoice', $shipment->awb_number) }}" target="_blank" class="text-purple-600 hover:text-purple-800 font-bold text-xs"><i class="fa-solid fa-file-invoice"></i> Invoice</a>
                            @else
                                <a href="{{ route('seller.label', $shipment->awb_number) }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-bold text-xs"><i class="fa-solid fa-print"></i> Label</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <p class="font-bold">No shipments found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $shipments->links() }}
        </div>
    </div>
</div>
@endsection
