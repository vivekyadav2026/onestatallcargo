@extends('layouts.hub')

@section('title', 'Service Areas - OneStall Cargo')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Service Areas</h1>
            <p class="text-sm text-gray-500 mt-1">View the pincodes or cities currently assigned to your Hub for operations</p>
        </div>
        <div class="flex gap-2">
            <!-- In the future, a request pincode modal could be added here -->
            <button class="px-5 py-2.5 bg-gray-100 text-gray-400 font-bold rounded-xl shadow-sm text-sm cursor-not-allowed" title="Contact Admin to update Service Areas">
                <i class="fa-solid fa-lock mr-1"></i> Request Pincode Update
            </button>
        </div>
    </div>

    <!-- Data Grid -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
            <h3 class="font-bold text-gray-800"><i class="fa-solid fa-map-location-dot mr-2"></i> Authorized Areas</h3>
            <span class="px-3 py-1 bg-[var(--gold)] text-gray-900 text-[10px] font-extrabold uppercase rounded-full">
                {{ count($serviceAreas) }} Active Areas
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="bg-white text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-100">
                        <th class="px-6 py-4">Area Code</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4 border-l border-gray-100 text-center" colspan="2">Pickups</th>
                        <th class="px-6 py-4 border-l border-gray-100 text-center" colspan="2">Deliveries</th>
                    </tr>
                    <tr class="bg-gray-50 text-[9px] font-extrabold uppercase tracking-wider text-gray-400 border-b border-gray-200">
                        <th class="px-6 py-2"></th>
                        <th class="px-6 py-2"></th>
                        <th class="px-6 py-2 border-l border-gray-100 text-center">Total All Time</th>
                        <th class="px-6 py-2 text-center text-yellow-600">Pending Now</th>
                        <th class="px-6 py-2 border-l border-gray-100 text-center">Total All Time</th>
                        <th class="px-6 py-2 text-center text-yellow-600">Pending Now</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700 font-medium">
                    @forelse($serviceAreas as $area)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-xl font-black text-gray-900">{{ $area['area_code'] }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-blue-50 text-blue-700 text-[10px] font-bold rounded-md uppercase tracking-wider">{{ $area['type'] }}</span>
                            </td>
                            
                            <!-- Pickups -->
                            <td class="px-6 py-4 border-l border-gray-100 text-center font-bold text-gray-600">
                                {{ $area['pickups_total'] }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($area['pickups_pending'] > 0)
                                    <span class="inline-flex items-center justify-center px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full">
                                        {{ $area['pickups_pending'] }}
                                    </span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>

                            <!-- Deliveries -->
                            <td class="px-6 py-4 border-l border-gray-100 text-center font-bold text-gray-600">
                                {{ $area['deliveries_total'] }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($area['deliveries_pending'] > 0)
                                    <span class="inline-flex items-center justify-center px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full">
                                        {{ $area['deliveries_pending'] }}
                                    </span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <div class="text-3xl mb-3"><i class="fa-solid fa-map-location-dot"></i></div>
                                <p>No service areas are currently assigned to your Hub.</p>
                                <p class="text-xs mt-1">Please contact the Administrator to map serviceable pincodes to your Franchise.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
