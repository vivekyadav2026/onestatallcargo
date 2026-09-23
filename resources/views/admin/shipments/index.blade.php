@extends('layouts.admin')

@section('title', 'Shipments - OneStall Cargo')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Shipments Management</h1>
            <p class="text-sm text-gray-500 mt-1">View and manage all B2B and B2C shipments</p>
        </div>
        <div class="flex gap-2">
            <button class="btn btn-secondary text-sm"><i class="fa-solid fa-download"></i> Export CSV</button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-bold text-gray-700 mb-1">Search AWB / Seller</label>
            <input type="text" placeholder="OSC123456789" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[var(--gold)]">
        </div>
        <div class="w-48">
            <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
            <select class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[var(--gold)]">
                <option value="">All Statuses</option>
                <option value="Manifested">Manifested</option>
                <option value="In Transit">In Transit</option>
                <option value="Out for Delivery">Out for Delivery</option>
                <option value="Delivered">Delivered</option>
                <option value="NDR">NDR</option>
            </select>
        </div>
        <button class="px-5 py-2 bg-gray-900 text-white font-bold rounded-lg text-sm hover:bg-gray-800 transition">
            Filter
        </button>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                        <th class="px-6 py-4">AWB Number</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Seller</th>
                        <th class="px-6 py-4">Destination</th>
                        <th class="px-6 py-4">Weight</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700 font-medium">
                    @forelse($shipments as $shipment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-bold text-[var(--gold-deep)]">{{ $shipment->awb_number }}</span>
                            </td>
                            <td class="px-6 py-4">{{ $shipment->created_at->format('d M Y, h:i A') }}</td>
                            <td class="px-6 py-4">{{ $shipment->user ? $shipment->user->name : 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $shipment->delivery_city }} ({{ $shipment->delivery_pincode }})</td>
                            <td class="px-6 py-4">{{ $shipment->weight_kg }} kg</td>
                            <td class="px-6 py-4 font-bold text-gray-900">₹{{ number_format($shipment->total_amount, 2) }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'Manifested' => 'bg-gray-100 text-gray-800',
                                        'In Transit' => 'bg-blue-50 text-blue-700',
                                        'Out for Delivery' => 'bg-yellow-50 text-yellow-700',
                                        'Delivered' => 'bg-green-50 text-green-700',
                                        'NDR' => 'bg-red-50 text-red-700',
                                    ];
                                    $color = $statusColors[$shipment->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $color }}">
                                    {{ $shipment->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-[var(--gold-deep)] hover:text-yellow-600 font-bold text-xs"><i class="fa-solid fa-eye"></i> View</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-box-open text-4xl mb-3 text-gray-200"></i>
                                    <p>No shipments found in the system yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($shipments->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $shipments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
