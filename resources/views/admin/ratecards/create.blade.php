@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Create Rate Card Version</h1>
        <a href="{{ route('admin.ratecards.index') }}" class="text-gray-600 hover:text-gray-900"><i class="fa-solid fa-arrow-left mr-1"></i> Back to List</a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow" role="alert">
            <p class="font-bold mb-2">Please fix the following errors:</p>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.ratecards.store') }}" method="POST">
        @csrf

        <!-- Meta Information -->
        <div class="bg-white rounded-lg shadow mb-6 p-6">
            <h2 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4"><i class="fa-solid fa-info-circle mr-2"></i> Rate Card Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Version Name <span class="text-red-500">*</span></label>
                    <input type="text" name="version_name" value="{{ old('version_name', 'v1.0 Official') }}" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Effective From Date <span class="text-red-500">*</span></label>
                    <input type="date" name="effective_from" value="{{ old('effective_from', date('Y-m-d')) }}" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                </div>
            </div>
        </div>

        <!-- Global Modifiers -->
        <div class="bg-white rounded-lg shadow mb-6 p-6">
            <h2 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4"><i class="fa-solid fa-percent mr-2"></i> Additional Charges & Modifiers</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">FSC (%) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="fsc_percent" value="{{ old('fsc_percent', 10) }}" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                    <p class="text-xs text-gray-500 mt-1">Fuel Surcharge applied on base freight.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">GST (%) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="gst_percent" value="{{ old('gst_percent', 18) }}" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                    <p class="text-xs text-gray-500 mt-1">As Applicable. Applied on total amount.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Volumetric Divisor <span class="text-red-500">*</span></label>
                    <input type="number" name="volumetric_divisor" value="{{ old('volumetric_divisor', 5000) }}" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                    <p class="text-xs text-gray-500 mt-1">(L × W × H) / Divisor</p>
                </div>
                
                <div class="bg-blue-50 p-3 rounded">
                    <label class="block text-sm font-medium text-blue-900 mb-1">COD Minimum Charge (₹) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="cod_min_charge" value="{{ old('cod_min_charge', 30) }}" required class="w-full border border-blue-200 rounded px-3 py-2">
                </div>
                <div class="bg-blue-50 p-3 rounded">
                    <label class="block text-sm font-medium text-blue-900 mb-1">COD Percentage (%) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="cod_percent" value="{{ old('cod_percent', 1.25) }}" required class="w-full border border-blue-200 rounded px-3 py-2">
                </div>
                <div class="bg-blue-50 p-3 rounded flex flex-col justify-center">
                    <p class="text-xs text-blue-800">System will automatically apply whichever COD charge is higher.</p>
                </div>

                <div class="bg-yellow-50 p-3 rounded">
                    <label class="block text-sm font-medium text-yellow-900 mb-1">DTO Multiplier <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="dto_multiplier" value="{{ old('dto_multiplier', 1.3) }}" required class="w-full border border-yellow-200 rounded px-3 py-2">
                    <p class="text-xs text-yellow-800 mt-1">Multiplier of forward freight (e.g., 1.3x)</p>
                </div>
                <div class="bg-yellow-50 p-3 rounded">
                    <label class="block text-sm font-medium text-yellow-900 mb-1">RVP/QC Base Charge (₹) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="qc_base_charge" value="{{ old('qc_base_charge', 35) }}" required class="w-full border border-yellow-200 rounded px-3 py-2">
                    <p class="text-xs text-yellow-800 mt-1">Flat base charge for Quality Check</p>
                </div>
                <div class="bg-yellow-50 p-3 rounded">
                    <label class="block text-sm font-medium text-yellow-900 mb-1">RVP/QC Addl. Param Charge (₹) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="qc_additional_param_charge" value="{{ old('qc_additional_param_charge', 5) }}" required class="w-full border border-yellow-200 rounded px-3 py-2">
                    <p class="text-xs text-yellow-800 mt-1">Charge per parameter beyond the standard 3</p>
                </div>
            </div>
        </div>

        <!-- Zones Matrix -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-6 border-b">
                <h2 class="text-lg font-bold text-gray-800"><i class="fa-solid fa-map-location-dot mr-2"></i> Weight Slabs & Zone Matrix</h2>
                <p class="text-sm text-gray-500 mt-1">Configure the official weight slabs. Leave a zone entirely blank to mark it as unsupported.</p>
            </div>
            
            <div class="overflow-x-auto p-4">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 text-xs uppercase tracking-wide">
                            <th class="p-3 border">Zone</th>
                            <th class="p-3 border bg-blue-50">First 0.5 KG</th>
                            <th class="p-3 border bg-blue-50">Addl 0.5 KG</th>
                            <th class="p-3 border bg-green-50">2 KG</th>
                            <th class="p-3 border bg-green-50">Addl 1 KG (>2)</th>
                            <th class="p-3 border bg-yellow-50">5 KG</th>
                            <th class="p-3 border bg-yellow-50">Addl 1 KG (>5)</th>
                            <th class="p-3 border bg-orange-50">10 KG</th>
                            <th class="p-3 border bg-orange-50">Addl 1 KG (>10)</th>
                            <th class="p-3 border bg-red-50">20 KG</th>
                            <th class="p-3 border bg-red-50">Addl 1 KG (>20)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $defaultZones = [
                                ['name' => 'Zone 1 - Local', 'data' => [25, 20, 65, 20, 120, 18, 190, 16, 350, 14]],
                                ['name' => 'Zone 2 - Regional', 'data' => [27, 22, 70, 22, 140, 20, 210, 18, 390, 16]],
                                ['name' => 'Zone 3 - Metros', 'data' => [36, 28, 90, 26, 160, 22, 250, 20, 450, 18]],
                                ['name' => 'Zone 4 - Rest of India', 'data' => [38, 30, 100, 28, 180, 24, 260, 22, 480, 20]],
                                ['name' => 'Zone 5 - NE/J&K/Kerala', 'data' => [45, 35, 120, 32, 200, 26, 310, 24, 550, 22]],
                                ['name' => 'Zone 6 - Special Destination', 'data' => ['', '', '', '', '', '', '', '', '', '']],
                            ];
                        @endphp
                        
                        @foreach($defaultZones as $index => $z)
                            <tr class="hover:bg-gray-50 text-sm">
                                <td class="p-2 border font-bold text-gray-700">
                                    {{ $z['name'] }}
                                    <input type="hidden" name="zones[{{ $index }}][zone_name]" value="{{ $z['name'] }}">
                                </td>
                                <td class="p-2 border"><input type="number" step="0.01" name="zones[{{ $index }}][first_0_5_kg]" value="{{ old('zones.'.$index.'.first_0_5_kg', $z['data'][0]) }}" class="w-full p-1 border rounded text-right"></td>
                                <td class="p-2 border"><input type="number" step="0.01" name="zones[{{ $index }}][addl_0_5_kg]" value="{{ old('zones.'.$index.'.addl_0_5_kg', $z['data'][1]) }}" class="w-full p-1 border rounded text-right"></td>
                                <td class="p-2 border"><input type="number" step="0.01" name="zones[{{ $index }}][first_2_kg]" value="{{ old('zones.'.$index.'.first_2_kg', $z['data'][2]) }}" class="w-full p-1 border rounded text-right"></td>
                                <td class="p-2 border"><input type="number" step="0.01" name="zones[{{ $index }}][addl_1_kg_after_2]" value="{{ old('zones.'.$index.'.addl_1_kg_after_2', $z['data'][3]) }}" class="w-full p-1 border rounded text-right"></td>
                                <td class="p-2 border"><input type="number" step="0.01" name="zones[{{ $index }}][first_5_kg]" value="{{ old('zones.'.$index.'.first_5_kg', $z['data'][4]) }}" class="w-full p-1 border rounded text-right"></td>
                                <td class="p-2 border"><input type="number" step="0.01" name="zones[{{ $index }}][addl_1_kg_after_5]" value="{{ old('zones.'.$index.'.addl_1_kg_after_5', $z['data'][5]) }}" class="w-full p-1 border rounded text-right"></td>
                                <td class="p-2 border"><input type="number" step="0.01" name="zones[{{ $index }}][first_10_kg]" value="{{ old('zones.'.$index.'.first_10_kg', $z['data'][6]) }}" class="w-full p-1 border rounded text-right"></td>
                                <td class="p-2 border"><input type="number" step="0.01" name="zones[{{ $index }}][addl_1_kg_after_10]" value="{{ old('zones.'.$index.'.addl_1_kg_after_10', $z['data'][7]) }}" class="w-full p-1 border rounded text-right"></td>
                                <td class="p-2 border"><input type="number" step="0.01" name="zones[{{ $index }}][first_20_kg]" value="{{ old('zones.'.$index.'.first_20_kg', $z['data'][8]) }}" class="w-full p-1 border rounded text-right"></td>
                                <td class="p-2 border"><input type="number" step="0.01" name="zones[{{ $index }}][addl_1_kg_after_20]" value="{{ old('zones.'.$index.'.addl_1_kg_after_20', $z['data'][9]) }}" class="w-full p-1 border rounded text-right"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="bg-gray-50 p-4 border-t text-sm text-gray-600 italic">
                Note: Empty rows (like Zone 6) will be saved as "Unsupported" and will explicitly block calculations for those routes. Return to Origin (RTO) charges are systemically calculated as 1x the forward freight.
            </div>
        </div>

        <div class="flex justify-end gap-4 pb-12">
            <a href="{{ route('admin.ratecards.index') }}" class="px-6 py-2 border rounded text-gray-700 hover:bg-gray-100 font-medium">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-bold shadow"><i class="fa-solid fa-save mr-2"></i> Save Rate Card</button>
        </div>
    </form>
</div>
@endsection
