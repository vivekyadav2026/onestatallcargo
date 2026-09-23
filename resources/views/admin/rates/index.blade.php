@extends('layouts.admin')

@section('title', 'Rate Engine - OneStall Cargo')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Rate Engine Configuration</h1>
            <p class="text-sm text-gray-500 mt-1">Configure volumetric and dead weight pricing per zone (500g logic)</p>
        </div>
        <div class="flex gap-2">
            <button class="px-5 py-2.5 rounded-xl text-xs font-bold bg-[var(--gold)] text-gray-900 shadow-md hover:bg-[var(--gold-deep)] transition-colors"><i class="fa-solid fa-plus"></i> Add New Rule</button>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                        <th class="px-6 py-4">Zone Type</th>
                        <th class="px-6 py-4">Base Rate (first 500g)</th>
                        <th class="px-6 py-4">Additional Rate (per 500g)</th>
                        <th class="px-6 py-4">RTO Surcharge</th>
                        <th class="px-6 py-4">COD Surcharge</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700 font-medium">
                    @forelse($rates as $rate)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-900 uppercase">{{ $rate->zone_type }}</span>
                            </td>
                            <td class="px-6 py-4 text-[var(--gold-deep)] font-bold">₹{{ number_format($rate->base_rate, 2) }}</td>
                            <td class="px-6 py-4 text-gray-900 font-bold">₹{{ number_format($rate->additional_weight_rate, 2) }}</td>
                            <td class="px-6 py-4 text-red-500">₹{{ number_format($rate->rto_surcharge, 2) }}</td>
                            <td class="px-6 py-4 text-gray-900">₹{{ number_format($rate->cod_surcharge, 2) }}</td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-gray-500 hover:text-gray-900 font-bold text-xs"><i class="fa-solid fa-pen"></i> Edit</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <p>No rate configurations found. System default is being used.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

