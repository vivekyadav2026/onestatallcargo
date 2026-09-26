@extends('layouts.admin')
@section('title', 'NDR & RTO - OneStall Cargo')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">NDR & RTO Management</h1>
            <p class="text-sm text-gray-500 mt-1">Resolve Non-Delivery Reports and process Returns to Origin.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-700 font-bold rounded-xl border border-green-200 text-sm">
            <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-x-auto">
                        <div class="overflow-x-auto w-full">
<table class="w-full text-left text-sm whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                    <th class="px-6 py-4">AWB Number</th>
                    <th class="px-6 py-4">Seller Details</th>
                    <th class="px-6 py-4">NDR Reason</th>
                    <th class="px-6 py-4">Seller Request</th>
                    <th class="px-6 py-4 text-right">Admin Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-gray-700">
                @forelse($shipments as $shipment)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-bold text-[var(--gold-deep)]">
                        {{ $shipment->awb_number }}
                        <div class="text-[10px] text-gray-500 mt-1 uppercase">{{ $shipment->status }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900">{{ $shipment->user->name ?? 'N/A' }}</div>
                        <div class="text-[10px] text-gray-400">{{ $shipment->user->phone ?? 'No Phone' }}</div>
                    </td>
                    <td class="px-6 py-4 text-red-600 font-bold text-xs">
                        {{ $shipment->ndr_reason ?? 'Customer Unavailable' }}
                    </td>
                    <td class="px-6 py-4">
                        @if($shipment->ndr_action == 'reattempt')
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded font-bold text-[10px] uppercase">Reattempt Requested</span>
                        @elseif($shipment->ndr_action == 'rto')
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded font-bold text-[10px] uppercase">RTO Requested</span>
                        @else
                            <span class="text-gray-400 text-xs italic">Awaiting Seller...</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        @if($shipment->status === 'NDR')
                            <form action="{{ route('admin.ndr.action', $shipment->id) }}" method="POST" class="inline-block">
                                @csrf
                                <input type="hidden" name="admin_action" value="reattempt">
                                <button type="submit" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs rounded border border-blue-200 transition"><i class="fa-solid fa-rotate-right mr-1"></i> Approve Reattempt</button>
                            </form>
                            <form action="{{ route('admin.ndr.action', $shipment->id) }}" method="POST" class="inline-block">
                                @csrf
                                <input type="hidden" name="admin_action" value="rto">
                                <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 font-bold text-xs rounded border border-red-200 transition"><i class="fa-solid fa-backward mr-1"></i> Approve RTO</button>
                            </form>
                        @elseif($shipment->status === 'RTO Initiated')
                            <form action="{{ route('admin.ndr.action', $shipment->id) }}" method="POST" class="inline-block">
                                @csrf
                                <input type="hidden" name="admin_action" value="rto_delivered">
                                <button type="submit" class="px-3 py-1.5 bg-green-50 hover:bg-green-100 text-green-700 font-bold text-xs rounded border border-green-200 transition"><i class="fa-solid fa-check mr-1"></i> Mark RTO Delivered to Seller</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                        <i class="fa-solid fa-triangle-exclamation text-4xl mb-3 text-gray-200"></i>
                        <p>No pending NDR or RTO shipments.</p>
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
