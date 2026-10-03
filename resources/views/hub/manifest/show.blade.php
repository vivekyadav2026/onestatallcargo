@extends('layouts.hub')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('hub.bagging.index') }}" class="text-gray-400 hover:text-gray-900"><i class="fa-solid fa-arrow-left"></i></a>
            <h2 class="text-2xl font-extrabold text-gray-900">Manifest: {{ $manifest->manifest_number }}</h2>
            <span class="px-3 py-1 rounded-full text-xs font-bold tracking-wider {{ $manifest->status === 'CREATED' ? 'bg-yellow-50 text-yellow-700' : 'bg-green-50 text-green-700' }}">
                {{ $manifest->status }}
            </span>
        </div>
        
        <div class="flex gap-2">
            <a href="{{ route('hub.manifests.print', $manifest->id) }}" target="_blank" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-900 font-bold rounded-xl shadow-sm hover:bg-gray-50 transition text-sm">
                <i class="fa-solid fa-print mr-1"></i> Print
            </a>
            @if($manifest->status === 'CREATED')
                <form action="{{ route('hub.manifests.dispatch', $manifest->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 bg-gray-900 text-white font-bold rounded-xl shadow-sm hover:bg-black transition text-sm">
                        <i class="fa-solid fa-truck-fast mr-1"></i> Dispatch Manifest
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex justify-between items-start">
            <div>
                <h4 class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Destination</h4>
                <div class="text-xl font-bold text-gray-900">{{ optional($manifest->destinationHub)->name ?? 'Unknown' }} ({{ optional($manifest->destinationHub)->city ?? '' }})</div>
                <div class="text-sm text-gray-500 mt-1">Bag: {{ $manifest->bag->bag_number }}</div>
            </div>
            <div class="text-right">
                <h4 class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Shipment Count</h4>
                <div class="text-xl font-bold text-gray-900">{{ $manifest->shipment_count }}</div>
                <div class="text-sm text-gray-500 mt-1">Weight: {{ $manifest->bag->weight_kg }} kg</div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50 text-[10px] font-extrabold uppercase text-gray-500 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3">AWB</th>
                        <th class="px-6 py-3">Order ID</th>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Destination Pincode</th>
                        <th class="px-6 py-3">Weight</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($manifest->bag->shipments as $shipment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-bold text-gray-900">{{ $shipment->awb_number }}</td>
                        <td class="px-6 py-3">{{ $shipment->order_id }}</td>
                        <td class="px-6 py-3">{{ $shipment->receiver_name }}</td>
                        <td class="px-6 py-3">{{ $shipment->delivery_city }} ({{ $shipment->delivery_pincode }})</td>
                        <td class="px-6 py-3">{{ $shipment->weight_kg }} kg</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">No shipments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

