@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6" id="app-preview">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Rate Card: {{ $rateCard->version_name }}</h1>
            <p class="text-gray-500">Effective: {{ \Carbon\Carbon::parse($rateCard->effective_from)->format('d M Y') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.ratecards.index') }}" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100 font-medium"><i class="fa-solid fa-arrow-left mr-1"></i> Back</a>
            @if(!$rateCard->is_active)
                <a href="{{ route('admin.ratecards.edit', $rateCard->id) }}" class="px-4 py-2 border border-yellow-500 text-yellow-600 rounded hover:bg-yellow-50 font-medium"><i class="fa-solid fa-pen mr-1"></i> Edit</a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Card Info & Matrix -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-blue-50 px-6 py-4 border-b flex justify-between items-center">
                    <h2 class="text-lg font-bold text-blue-900">Freight Matrix</h2>
                    @if($rateCard->is_active)
                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-green-400">Active Pricing Engine</span>
                    @else
                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-gray-300">Inactive</span>
                    @endif
                </div>
                <div class="overflow-x-auto p-4">
                    <table class="w-full text-left border-collapse min-w-[700px] text-sm">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 uppercase">
                                <th class="p-2 border">Zone</th>
                                <th class="p-2 border bg-blue-50">0.5 KG</th>
                                <th class="p-2 border bg-blue-50">+0.5 KG</th>
                                <th class="p-2 border bg-green-50">2 KG</th>
                                <th class="p-2 border bg-green-50">+1 KG (>2)</th>
                                <th class="p-2 border bg-yellow-50">5 KG</th>
                                <th class="p-2 border bg-yellow-50">+1 KG (>5)</th>
                                <th class="p-2 border bg-orange-50">10 KG</th>
                                <th class="p-2 border bg-orange-50">+1 KG (>10)</th>
                                <th class="p-2 border bg-red-50">20 KG</th>
                                <th class="p-2 border bg-red-50">+1 KG (>20)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $defaultZoneNames = [
                                    'Zone 1 - Local', 'Zone 2 - Regional', 'Zone 3 - Metros', 
                                    'Zone 4 - Rest of India', 'Zone 5 - NE/J&K/Kerala', 'Zone 6 - Special Destination'
                                ];
                            @endphp
                            @foreach($defaultZoneNames as $zoneName)
                                @php
                                    $zone = $rateCard->zones->where('zone_name', $zoneName)->first();
                                @endphp
                                <tr class="hover:bg-gray-50 border-b">
                                    <td class="p-2 border font-bold text-gray-700">{{ $zoneName }}</td>
                                    @if($zone && $zone->first_0_5_kg)
                                        <td class="p-2 border text-right">₹{{ number_format($zone->first_0_5_kg, 2) }}</td>
                                        <td class="p-2 border text-right">₹{{ number_format($zone->addl_0_5_kg, 2) }}</td>
                                        <td class="p-2 border text-right">₹{{ number_format($zone->first_2_kg, 2) }}</td>
                                        <td class="p-2 border text-right">₹{{ number_format($zone->addl_1_kg_after_2, 2) }}</td>
                                        <td class="p-2 border text-right">₹{{ number_format($zone->first_5_kg, 2) }}</td>
                                        <td class="p-2 border text-right">₹{{ number_format($zone->addl_1_kg_after_5, 2) }}</td>
                                        <td class="p-2 border text-right">₹{{ number_format($zone->first_10_kg, 2) }}</td>
                                        <td class="p-2 border text-right">₹{{ number_format($zone->addl_1_kg_after_10, 2) }}</td>
                                        <td class="p-2 border text-right">₹{{ number_format($zone->first_20_kg, 2) }}</td>
                                        <td class="p-2 border text-right">₹{{ number_format($zone->addl_1_kg_after_20, 2) }}</td>
                                    @else
                                        <td colspan="10" class="p-2 border text-center text-red-500 italic bg-red-50 font-medium">Not Configured (Unsupported)</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modifiers Summary -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Global Modifiers</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div class="p-3 bg-gray-50 rounded border">
                        <p class="text-gray-500 font-medium">FSC</p>
                        <p class="font-bold text-lg">{{ $rateCard->fsc_percent }}%</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded border">
                        <p class="text-gray-500 font-medium">COD Rule</p>
                        <p class="font-bold text-lg">Max(₹{{ $rateCard->cod_min_charge }}, {{ $rateCard->cod_percent }}%)</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded border">
                        <p class="text-gray-500 font-medium">GST</p>
                        <p class="font-bold text-lg">{{ $rateCard->gst_percent }}%</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded border">
                        <p class="text-gray-500 font-medium">Volumetric</p>
                        <p class="font-bold text-lg">/ {{ $rateCard->volumetric_divisor }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded border">
                        <p class="text-gray-500 font-medium">DTO</p>
                        <p class="font-bold text-lg">{{ $rateCard->dto_multiplier }}x Forward</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded border">
                        <p class="text-gray-500 font-medium">RTO</p>
                        <p class="font-bold text-lg">1.0x Forward</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded border">
                        <p class="text-gray-500 font-medium">QC Base</p>
                        <p class="font-bold text-lg">₹{{ $rateCard->qc_base_charge }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded border">
                        <p class="text-gray-500 font-medium">QC Addl Param</p>
                        <p class="font-bold text-lg">₹{{ $rateCard->qc_additional_param_charge }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Calculator -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6 sticky top-6 border-t-4 border-blue-500">
                <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2"><i class="fa-solid fa-calculator mr-2 text-blue-500"></i> Rate Preview Calculator</h2>
                <p class="text-xs text-gray-500 mb-4">Uses the official PricingService engine exactly as it applies in production for this version.</p>
                
                <form id="preview-form" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700">Pickup Pincode</label>
                            <input type="text" name="pickup_pincode" value="110001" class="w-full border rounded px-2 py-1 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700">Delivery Pincode</label>
                            <input type="text" name="delivery_pincode" value="400001" class="w-full border rounded px-2 py-1 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700">Weight (KG)</label>
                            <input type="number" step="0.1" name="weight_kg" value="1.5" class="w-full border rounded px-2 py-1 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700">Invoice Val (₹)</label>
                            <input type="number" name="invoice_value" value="1000" class="w-full border rounded px-2 py-1 text-sm">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Dimensions (L x W x H) cm</label>
                        <div class="grid grid-cols-3 gap-2">
                            <input type="number" name="length_cm" value="20" class="border rounded px-2 py-1 text-sm" placeholder="L">
                            <input type="number" name="width_cm" value="15" class="border rounded px-2 py-1 text-sm" placeholder="W">
                            <input type="number" name="height_cm" value="10" class="border rounded px-2 py-1 text-sm" placeholder="H">
                        </div>
                    </div>

                    <div>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="is_cod" value="1" class="rounded">
                            <span class="text-sm font-bold text-gray-700">Is COD Shipment?</span>
                        </label>
                    </div>

                    <button type="button" onclick="calculatePreview()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded text-sm transition">
                        Calculate Price
                    </button>
                </form>

                <!-- Results Section -->
                <div id="preview-result" class="mt-6 hidden">
                    <h3 class="font-bold text-gray-800 border-b pb-1 mb-3 text-sm">Calculation Result</h3>
                    
                    <div id="result-error" class="hidden bg-red-100 text-red-700 p-2 rounded text-xs mb-3 font-medium"></div>

                    <div id="result-data" class="space-y-2 text-sm">
                        <div class="flex justify-between border-b pb-1">
                            <span class="text-gray-600">Routing:</span>
                            <span class="font-bold" id="res-routing"></span>
                        </div>
                        <div class="flex justify-between border-b pb-1">
                            <span class="text-gray-600">Zone:</span>
                            <span class="font-bold text-blue-600" id="res-zone"></span>
                        </div>
                        <div class="flex justify-between border-b pb-1">
                            <span class="text-gray-600">Volumetric Wt:</span>
                            <span class="font-bold" id="res-vol-wt"></span>
                        </div>
                        <div class="flex justify-between border-b pb-1">
                            <span class="text-gray-600">Chargeable Wt:</span>
                            <span class="font-bold text-red-600" id="res-chg-wt"></span>
                        </div>
                        <div class="flex justify-between border-b pb-1">
                            <span class="text-gray-600">Forward Freight:</span>
                            <span class="font-medium" id="res-freight"></span>
                        </div>
                        <div class="flex justify-between border-b pb-1">
                            <span class="text-gray-600">FSC:</span>
                            <span class="font-medium" id="res-fsc"></span>
                        </div>
                        <div class="flex justify-between border-b pb-1">
                            <span class="text-gray-600">COD Surcharge:</span>
                            <span class="font-medium" id="res-cod"></span>
                        </div>
                        <div class="flex justify-between border-b pb-1">
                            <span class="text-gray-600">GST:</span>
                            <span class="font-medium" id="res-gst"></span>
                        </div>
                        <div class="flex justify-between pt-2 bg-green-50 p-2 rounded border border-green-200">
                            <span class="text-gray-800 font-bold">TOTAL CHARGE:</span>
                            <span class="font-black text-green-700 text-lg" id="res-total"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function calculatePreview() {
    const form = document.getElementById('preview-form');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    // Checkbox handling
    data.is_cod = form.querySelector('input[name="is_cod"]').checked ? 1 : 0;

    const resultDiv = document.getElementById('preview-result');
    const errDiv = document.getElementById('result-error');
    const dataDiv = document.getElementById('result-data');

    resultDiv.classList.remove('hidden');
    errDiv.classList.add('hidden');
    dataDiv.classList.add('hidden');

    fetch("{{ route('admin.ratecards.preview', $rateCard->id) }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(res => {
        if (!res.success) {
            errDiv.innerText = res.message;
            errDiv.classList.remove('hidden');
            return;
        }

        dataDiv.classList.remove('hidden');
        document.getElementById('res-routing').innerText = res.routing.fulfillment_type;
        
        const rate = res.data;
        document.getElementById('res-zone').innerText = rate.zone || 'N/A';
        document.getElementById('res-vol-wt').innerText = (rate.volumetric_weight || 0) + ' kg';
        document.getElementById('res-chg-wt').innerText = (rate.chargeable_weight || 0) + ' kg';
        document.getElementById('res-freight').innerText = '₹' + parseFloat(rate.freight).toFixed(2);
        document.getElementById('res-fsc').innerText = '₹' + parseFloat(rate.fsc).toFixed(2);
        document.getElementById('res-cod').innerText = '₹' + parseFloat(rate.cod).toFixed(2);
        document.getElementById('res-gst').innerText = '₹' + parseFloat(rate.gst).toFixed(2);
        document.getElementById('res-total').innerText = '₹' + parseFloat(rate.total).toFixed(2);
    })
    .catch(error => {
        errDiv.innerText = "Network Error. See console.";
        errDiv.classList.remove('hidden');
        console.error(error);
    });
}
</script>
@endsection
