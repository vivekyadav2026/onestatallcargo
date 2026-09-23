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
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                    <th class="px-6 py-4">AWB Number</th>
                    <th class="px-6 py-4">Seller</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Action Needed</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-gray-700">
                @forelse($shipments as $shipment)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-bold text-[var(--gold-deep)]">{{ $shipment->awb_number }}</td>
                    <td class="px-6 py-4">{{ $shipment->user->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4"><span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-50 text-red-700">NDR</span></td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button class="text-blue-600 font-bold text-xs"><i class="fa-solid fa-rotate-right"></i> Reattempt</button>
                        <button class="text-red-600 font-bold text-xs"><i class="fa-solid fa-backward"></i> Init RTO</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-12 text-center text-gray-400">No pending NDRs.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
