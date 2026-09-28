@extends('layouts.hub')

@section('content')
<div class="space-y-6" x-data="{ 
    selectedShipments: [],
    selectAll: false,
    toggleAll() {
        if (this.selectAll) {
            this.selectedShipments = Array.from(document.querySelectorAll('.shipment-checkbox')).map(cb => cb.value);
        } else {
            this.selectedShipments = [];
        }
    }
}">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-extrabold text-gray-900">Delivery Assignments</h2>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Shipments Ready for Delivery</h3>
            <form action="{{ route('hub.assignments.assign') }}" method="POST" class="flex gap-3 items-center" x-show="selectedShipments.length > 0">
                @csrf
                <input type="hidden" name="type" value="delivery">
                <template x-for="id in selectedShipments" :key="id">
                    <input type="hidden" name="shipment_ids[]" :value="id">
                </template>
                <select name="rider_id" required class="text-sm px-3 py-1.5 border border-gray-300 rounded-lg outline-none focus:border-[var(--gold)]">
                    <option value="">-- Assign Rider --</option>
                    @foreach($riders as $rider)
                        <option value="{{ $rider->id }}">{{ $rider->user->name }} ({{ $rider->vehicle_type ?? 'No Vehicle' }})</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-1.5 bg-[var(--gold)] text-gray-900 font-bold rounded-lg hover:bg-yellow-500 transition text-sm">
                    Assign <span x-text="selectedShipments.length"></span> Shipments
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50 text-[10px] font-extrabold uppercase text-gray-500 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-center w-10">
                            <input type="checkbox" x-model="selectAll" @change="toggleAll" class="rounded border-gray-300 text-[var(--gold)] focus:ring-[var(--gold)]">
                        </th>
                        <th class="px-4 py-3">AWB / Details</th>
                        <th class="px-4 py-3">Receiver</th>
                        <th class="px-4 py-3">Delivery Pincode</th>
                        <th class="px-4 py-3">COD / Amount</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Assigned Rider</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($shipments as $shipment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-center">
                            <input type="checkbox" value="{{ $shipment->id }}" x-model="selectedShipments" class="shipment-checkbox rounded border-gray-300 text-[var(--gold)] focus:ring-[var(--gold)]">
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-bold text-gray-900">{{ $shipment->awb_number }}</div>
                            <div class="text-[10px] text-gray-500">{{ $shipment->weight_kg }} kg | {{ $shipment->shipment_type ?? 'Standard' }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div>{{ $shipment->receiver_name }}</div>
                            <div class="text-xs text-gray-500">{{ $shipment->receiver_phone }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-bold">{{ $shipment->delivery_pincode }}</div>
                            <div class="text-xs text-gray-500">{{ $shipment->delivery_city }}</div>
                        </td>
                        <td class="px-4 py-3">
                            @if($shipment->is_cod)
                                <span class="text-red-600 font-bold">?{{ $shipment->total_amount }} (COD)</span>
                            @else
                                <span class="text-green-600 font-bold">Prepaid</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-md text-[10px] font-bold tracking-wider {{ $shipment->status === 'Out for Delivery' ? 'bg-purple-50 text-purple-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $shipment->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs">
                            @if($shipment->rider_id)
                                {{ optional(\App\Models\User::find($shipment->rider_id))->name ?? 'Deleted' }}
                            @else
                                <span class="text-gray-400">Unassigned</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No shipments pending delivery assignment.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-3 border-t border-gray-100">{{ $shipments->links() }}</div>
        </div>
    </div>
</div>
@endsection
