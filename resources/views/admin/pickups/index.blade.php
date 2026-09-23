@extends('layouts.admin')
@section('title', 'Pickup Management - OneStall Cargo')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Pickup Management</h1>
            <p class="text-sm text-gray-500 mt-1">Assign pickup tasks to your on-ground fleet.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 text-green-700 border border-green-200 font-bold text-sm">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden p-6">
        <form action="{{ route('admin.pickups.assign') }}" method="POST">
            @csrf
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-gray-800"><i class="fa-solid fa-boxes-packing mr-2 text-[var(--gold-deep)]"></i> Pending Pickups (Manifested)</h2>
                
                <div class="flex items-center gap-3">
                    <select name="rider_id" required class="px-3 py-2 border border-gray-300 rounded-lg text-sm font-bold focus:outline-none focus:border-[var(--gold)]">
                        <option value="">Select Rider to Assign</option>
                        @foreach($riders as $rider)
                            <option value="{{ $rider->id }}">{{ $rider->name }} ({{ ucfirst(str_replace('_', ' ', $rider->role)) }})</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-5 py-2 bg-gray-900 text-white font-bold rounded-lg text-sm hover:bg-black transition">
                        Assign Pickups
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                            <th class="px-6 py-4 w-10">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                            </th>
                            <th class="px-6 py-4">AWB Number</th>
                            <th class="px-6 py-4">Seller Details</th>
                            <th class="px-6 py-4">Pickup Location</th>
                            <th class="px-6 py-4">Type</th>
                            <th class="px-6 py-4 text-right">Assigned To</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-gray-700">
                        @forelse($pendingPickups as $shipment)
                        <tr class="hover:bg-gray-50 transition-colors {{ $shipment->assigned_rider_id ? 'bg-green-50/30' : '' }}">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="shipment_ids[]" value="{{ $shipment->id }}" class="rounded border-gray-300 text-gray-900 focus:ring-gray-900 pickup-checkbox">
                            </td>
                            <td class="px-6 py-4 font-bold text-[var(--gold-deep)]">{{ $shipment->awb_number }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $shipment->user->name ?? 'N/A' }}</div>
                                <div class="text-[10px] text-gray-400"><i class="fa-solid fa-phone mr-1"></i> {{ $shipment->user->phone ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                {{ $shipment->delivery_city }} ({{ $shipment->delivery_pincode }})
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded bg-gray-100 text-[10px] font-bold">{{ strtoupper($shipment->shipment_type) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-xs">
                                @if($shipment->assignedRider)
                                    <span class="text-green-600"><i class="fa-solid fa-motorcycle"></i> {{ $shipment->assignedRider->name }}</span>
                                @else
                                    <span class="text-orange-500">Unassigned</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-truck-ramp-box text-4xl mb-3 text-gray-200"></i>
                                    <p>No active pickups requested today.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.pickup-checkbox');
        
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
        });
    });
</script>
@endsection
