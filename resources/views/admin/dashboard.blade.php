@extends('layouts.admin')

@section('title', 'Admin Console - OneStall Cargo')

@section('content')
<div class="space-y-6">

    <!-- Top Stats Row -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
        <!-- Metric 1: Total Shipments -->
        <div class="p-5 rounded-2xl bg-white border border-gray-200 shadow-sm space-y-2 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between text-gray-500">
                <span class="text-[10px] font-extrabold uppercase tracking-wider">Total Shipments</span>
                <i class="fa-solid fa-box text-blue-500"></i>
            </div>
            <div class="text-3xl font-black text-gray-900">{{ number_format($totalShipments ?? 15420) }}</div>
            <div class="text-[11px] font-medium text-gray-400">All Time Volume</div>
        </div>

        <!-- Metric 2: In Transit -->
        <div class="p-5 rounded-2xl bg-[var(--gold)] shadow-sm space-y-2 transform hover:-translate-y-1 transition-transform relative overflow-hidden">
            <div class="absolute -right-4 -top-4 text-white/20">
                <i class="fa-solid fa-truck-fast text-6xl"></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-center justify-between text-gray-900">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider">In Transit</span>
                </div>
                <div class="text-3xl font-black text-gray-900">{{ number_format($inTransit ?? 3450) }}</div>
                <div class="text-[11px] font-medium text-gray-800">Across All Couriers</div>
            </div>
        </div>

        <!-- Metric 3: Delivered -->
        <div class="p-5 rounded-2xl bg-white border border-gray-200 shadow-sm space-y-2 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between text-gray-500">
                <span class="text-[10px] font-extrabold uppercase tracking-wider">Delivered (Today)</span>
                <i class="fa-solid fa-check-circle text-green-500"></i>
            </div>
            <div class="text-3xl font-black text-gray-900">{{ number_format($deliveredToday ?? 1200) }}</div>
            <div class="text-[11px] font-medium text-gray-400">Successfully Delivered</div>
        </div>

        <!-- Metric 4: NDR -->
        <div class="p-5 rounded-2xl bg-white border border-gray-200 shadow-sm space-y-2 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between text-gray-500">
                <span class="text-[10px] font-extrabold uppercase tracking-wider">Active NDR</span>
                <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
            </div>
            <div class="text-3xl font-black text-gray-900">{{ number_format($ndrCount ?? 120) }}</div>
            <div class="text-[11px] font-medium text-gray-400">Pending Resolutions</div>
        </div>

        <!-- Metric 5: Revenue -->
        <div class="p-5 rounded-2xl bg-white border border-gray-200 shadow-sm space-y-2 hover:shadow-md transition-shadow col-span-2 md:col-span-4 lg:col-span-1">
            <div class="flex items-center justify-between text-gray-500">
                <span class="text-[10px] font-extrabold uppercase tracking-wider">Revenue</span>
                <i class="fa-solid fa-indian-rupee-sign text-[var(--gold)]"></i>
            </div>
            <div class="text-3xl font-black text-gray-900">₹{{ number_format(($totalRevenue ?? 2540000) / 100000, 2) }}L</div>
            <div class="text-[11px] font-medium text-gray-400">Total Billed Volume</div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left Column: Courier Performance Table -->
        <div class="lg:col-span-8 space-y-6">
            <div class="p-6 rounded-3xl bg-white border border-gray-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b pb-4 border-gray-100">
                    <div>
                        <h3 class="font-extrabold text-base text-gray-900">Courier Performance</h3>
                        <p class="text-xs text-gray-500 mt-1">Delivery accuracy & RTO metrics across integrated partners</p>
                    </div>
                    <a href="#" class="text-xs font-bold text-[var(--gold-deep)] hover:underline">View All</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead>
                            <tr class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400 border-b border-gray-100">
                                <th class="pb-3 font-medium">Partner Name</th>
                                <th class="pb-3 font-medium">Volume</th>
                                <th class="pb-3 font-medium">Delivery %</th>
                                <th class="pb-3 font-medium">RTO Count</th>
                                <th class="pb-3 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-gray-600 font-medium">
                            @foreach($courierPerformance ?? [] as $partner)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4">{{ $partner['name'] }}</td>
                                <td class="py-4">{{ number_format($partner['shipments']) }}</td>
                                <td class="py-4"><span class="px-2 py-1 rounded {{ $partner['accuracy'] >= 95 ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' }} text-xs font-bold">{{ $partner['accuracy'] }}%</span></td>
                                <td class="py-4">{{ $partner['rto'] }}</td>
                                <td class="py-4"><span class="w-2 h-2 inline-block rounded-full bg-green-500 mr-2"></span>Active</td>
                            </tr>
                            @endforeach
                            @if(empty($courierPerformance))
                            <tr><td colspan="5" class="py-4 text-center text-gray-400">No data available</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            <!-- Recent Bookings Widget -->
            <div class="p-6 rounded-3xl bg-white border border-gray-200 shadow-sm space-y-4 mt-6">
                <div class="flex items-center justify-between border-b pb-4 border-gray-100">
                    <div>
                        <h3 class="font-extrabold text-base text-gray-900">Recent Bookings</h3>
                        <p class="text-xs text-gray-500 mt-1">Latest shipments processed across the platform</p>
                    </div>
                    <a href="{{ route('admin.shipments.index') }}" class="text-xs font-bold text-[var(--gold-deep)] hover:underline">View All Shipments</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead>
                            <tr class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400 border-b border-gray-100">
                                <th class="pb-3 font-medium">AWB Number</th>
                                <th class="pb-3 font-medium">Seller / Client</th>
                                <th class="pb-3 font-medium">Status</th>
                                <th class="pb-3 font-medium text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-gray-600 font-medium">
                            @foreach($recentShipments ?? [] as $booking)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-3 font-bold text-[#4338ca]">{{ $booking['awb'] }}</td>
                                <td class="py-3">{{ $booking['seller'] }}</td>
                                <td class="py-3">
                                    @php
                                        $statusClass = match($booking['status']) {
                                            'Delivered' => 'bg-green-50 text-green-700',
                                            'In Transit' => 'bg-yellow-50 text-yellow-700',
                                            'NDR' => 'bg-red-50 text-red-700',
                                            'RTO Initiated', 'RTO Delivered' => 'bg-gray-100 text-gray-700',
                                            default => 'bg-blue-50 text-blue-700'
                                        };
                                    @endphp
                                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider {{ $statusClass }}">
                                        {{ $booking['status'] }}
                                    </span>
                                </td>
                                <td class="py-3 text-right">
                                    <button class="text-gray-400 hover:text-gray-900 transition"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                </td>
                            </tr>
                            @endforeach
                            @if(empty($recentShipments))
                            <tr><td colspan="4" class="py-4 text-center text-gray-400">No recent shipments</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Weak Zones / High NDR -->
        <div class="lg:col-span-4 space-y-6">
            <div class="p-6 rounded-3xl bg-white border border-gray-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b pb-4 border-gray-100">
                    <h3 class="font-extrabold text-base text-gray-900">High NDR Zones</h3>
                    <i class="fa-solid fa-map-location-dot text-red-500"></i>
                </div>

                <div class="space-y-5 pt-2">
                    @foreach($weakZones ?? [] as $zone)
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1.5 text-gray-700">
                            <span>{{ $zone['city'] }} ({{ $zone['pincode'] }})</span>
                            <span class="text-red-500">{{ $zone['ndr_rate'] }}% NDR</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="bg-red-500 h-1.5 rounded-full" style="width: {{ min($zone['ndr_rate'] * 3, 100) }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-6 pt-6 border-t border-gray-100">
                    <button class="w-full py-2.5 rounded-xl text-xs font-bold border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors">
                        View Network Analytics
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
