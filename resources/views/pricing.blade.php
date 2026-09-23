@extends('layouts.app')

@section('title', 'Shipping Rates Calculator - OneStall Cargo')

@section('content')
<div class="bg-gray-50 py-16 border-b border-gray-200">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-4">Shipping Rates Calculator</h1>
        <p class="text-gray-600 text-lg">Calculate instant freight charges including volumetric weight adjustments, COD surcharges, and Courier API comparisons.</p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 py-16">
    <div class="bg-white rounded-3xl shadow-lg border border-gray-200 p-6 md:p-10">
        
        <form class="space-y-8">
            <!-- Pincodes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2"><i class="fa-solid fa-location-dot text-green-500 mr-1"></i> Pickup Pincode</label>
                    <input type="text" placeholder="e.g. 110001" class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2"><i class="fa-solid fa-location-dot text-red-500 mr-1"></i> Delivery Pincode</label>
                    <input type="text" placeholder="e.g. 400001" class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                </div>
            </div>

            <hr>

            <!-- Package Details -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Package Details</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Weight (KG)</label>
                        <input type="number" step="0.1" placeholder="0.5" class="w-full px-4 py-3 rounded-xl border bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Length (CM)</label>
                        <input type="number" placeholder="10" class="w-full px-4 py-3 rounded-xl border bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Width (CM)</label>
                        <input type="number" placeholder="10" class="w-full px-4 py-3 rounded-xl border bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Height (CM)</label>
                        <input type="number" placeholder="10" class="w-full px-4 py-3 rounded-xl border bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-2"><i class="fa-solid fa-circle-info mr-1"></i> Volumetric weight is calculated as (L × W × H) / 5000</p>
            </div>

            <!-- Payment Type -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Payment Mode</label>
                    <select class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                        <option>Prepaid</option>
                        <option>Cash on Delivery (COD)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Shipment Value (?)</label>
                    <input type="number" placeholder="1000" class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                </div>
            </div>

            <button type="button" class="w-full py-4 rounded-xl font-extrabold bg-gray-900 text-white shadow-md hover:bg-gray-800 transition-colors text-lg">
                Calculate Rates
            </button>
        </form>

    </div>
</div>
@endsection
