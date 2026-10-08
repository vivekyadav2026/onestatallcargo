@extends('layouts.admin')
@section('title', 'Admin Console - OneStall Cargo')

@section('content')
<div class="space-y-6 max-w-[1400px] mx-auto">
    
    <!-- Top Greeting Row -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
                Hello, {{ Auth::user()->name ?? 'Administrator' }} 👋
            </h2>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-[11px] font-bold text-emerald-600">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Master Node Active
                </span>
                <span class="text-xs font-semibold text-gray-400">
                    • {{ number_format($activeSellers ?? 0) }} Sellers Online
                </span>
            </div>
        </div>
        <div class="shrink-0 flex items-center gap-3">
            <button class="px-4 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold text-xs rounded-lg shadow-sm transition flex items-center gap-2">
                <i class="fa-regular fa-calendar text-gray-400"></i> {{ \Carbon\Carbon::now()->format('d M Y (l)') }}
            </button>
        </div>
    </div>

    <!-- Summary Metrics Grid (10 Cards) -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        
        <!-- Total Shipments -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] p-5 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <p class="text-[11px] font-bold text-gray-600">Platform Shipments</p>
                <div class="w-7 h-7 rounded-full border border-gray-100 flex items-center justify-center text-gray-400">
                    <i class="fa-solid fa-layer-group text-[10px]"></i>
                </div>
            </div>
            <div class="my-4">
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($totalShipments ?? 0) }}</h3>
            </div>
            <p class="text-[10px] font-semibold text-gray-500 pt-2 border-t border-gray-100">
                Yesterday: <span class="text-gray-900">{{ number_format($yesterdayShipments ?? 0) }} orders</span>
            </p>
        </div>

        <!-- Pending Pickups -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] p-5 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <p class="text-[11px] font-bold text-gray-600">Pending Pickups</p>
                <div class="w-7 h-7 rounded-full border border-amber-100 flex items-center justify-center text-amber-500 bg-amber-50/50">
                    <i class="fa-solid fa-boxes-packing text-[10px]"></i>
                </div>
            </div>
            <div class="my-4">
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($pendingPickups ?? 0) }}</h3>
            </div>
            <p class="text-[10px] font-semibold text-gray-500 pt-2 border-t border-gray-100">
                Awaiting Hub Action
            </p>
        </div>

        <!-- In Transit -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] p-5 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <p class="text-[11px] font-bold text-gray-600">In Transit</p>
                <div class="w-7 h-7 rounded-full border border-gray-100 flex items-center justify-center text-gray-400">
                    <i class="fa-solid fa-truck-fast text-[10px]"></i>
                </div>
            </div>
            <div class="my-4">
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($inTransit ?? 0) }}</h3>
            </div>
            <p class="text-[10px] font-semibold text-gray-500 pt-2 border-t border-gray-100">
                Active carrier lines
            </p>
        </div>

        <!-- Delivered Today -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] p-5 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <p class="text-[11px] font-bold text-gray-600">Delivered Today</p>
                <div class="w-7 h-7 rounded-full border border-emerald-100 flex items-center justify-center text-emerald-500 bg-emerald-50/50">
                    <i class="fa-solid fa-check-double text-[10px]"></i>
                </div>
            </div>
            <div class="my-4">
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($deliveredToday ?? 0) }}</h3>
            </div>
            <p class="text-[10px] font-semibold text-gray-500 pt-2 border-t border-gray-100">
                Yesterday: <span class="text-gray-900">{{ number_format($yesterdayDelivered ?? 0) }} delivered</span>
            </p>
        </div>

        <!-- Active NDR -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] p-5 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <p class="text-[11px] font-bold text-gray-600">Active NDR</p>
                <div class="w-7 h-7 rounded-full border border-rose-100 flex items-center justify-center text-rose-500 bg-rose-50/50">
                    <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                </div>
            </div>
            <div class="my-4">
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($ndrCount ?? 0) }}</h3>
            </div>
            <p class="text-[10px] font-semibold text-gray-500 pt-2 border-t border-gray-100">
                Yesterday: <span class="text-gray-900">{{ number_format($yesterdayNdr ?? 0) }} NDR</span>
            </p>
        </div>

        <!-- Total RTO -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] p-5 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <p class="text-[11px] font-bold text-gray-600">RTO Returns</p>
                <div class="w-7 h-7 rounded-full border border-gray-100 flex items-center justify-center text-gray-400">
                    <i class="fa-solid fa-rotate-left text-[10px]"></i>
                </div>
            </div>
            <div class="my-4">
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($rtoCount ?? 0) }}</h3>
            </div>
            <p class="text-[10px] font-semibold text-gray-500 pt-2 border-t border-gray-100">
                Yesterday: <span class="text-gray-900">{{ number_format($yesterdayRto ?? 0) }} RTO</span>
            </p>
        </div>

        <!-- Network Hubs -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] p-5 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <p class="text-[11px] font-bold text-gray-600">Franchise Hubs</p>
                <div class="w-7 h-7 rounded-full border border-gray-100 flex items-center justify-center text-gray-400">
                    <i class="fa-solid fa-shop text-[10px]"></i>
                </div>
            </div>
            <div class="my-4">
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($activeHubs ?? 0) }}</h3>
            </div>
            <p class="text-[10px] font-semibold text-gray-500 pt-2 border-t border-gray-100">
                Regional processing centers
            </p>
        </div>

        <!-- Active Riders -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] p-5 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <p class="text-[11px] font-bold text-gray-600">Field Riders</p>
                <div class="w-7 h-7 rounded-full border border-gray-100 flex items-center justify-center text-gray-400">
                    <i class="fa-solid fa-motorcycle text-[10px]"></i>
                </div>
            </div>
            <div class="my-4">
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($totalRiders ?? 0) }}</h3>
            </div>
            <p class="text-[10px] font-semibold text-gray-500 pt-2 border-t border-gray-100">
                Last-mile agents
            </p>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] p-5 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <p class="text-[11px] font-bold text-gray-600">Total Billed</p>
                <div class="w-7 h-7 rounded-full border border-blue-100 flex items-center justify-center text-blue-500 bg-blue-50/50">
                    <i class="fa-solid fa-money-bill-trend-up text-[10px]"></i>
                </div>
            </div>
            <div class="my-4">
                <h3 class="text-2xl font-black text-gray-900">₹{{ number_format(($totalRevenue ?? 0) / 100, 2) }}</h3>
            </div>
            <p class="text-[10px] font-semibold text-gray-500 pt-2 border-t border-gray-100">
                Yesterday: <span class="text-gray-900">?{{ number_format(($yesterdayRevenue ?? 0) / 100, 2) }}</span>
            </p>
        </div>

        <!-- COD Remittance -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] p-5 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <p class="text-[11px] font-bold text-gray-600">COD Remitted</p>
                <div class="w-7 h-7 rounded-full border border-gray-100 flex items-center justify-center text-gray-400">
                    <i class="fa-solid fa-building-columns text-[10px]"></i>
                </div>
            </div>
            <div class="my-4">
                <h3 class="text-2xl font-black text-gray-900">₹{{ number_format(($totalSettlements ?? 0) / 100, 2) }}</h3>
            </div>
            <p class="text-[10px] font-semibold text-gray-500 pt-2 border-t border-gray-100">
                Yesterday: <span class="text-gray-900">?{{ number_format(($yesterdaySettlements ?? 0) / 100, 2) }}</span>
            </p>
        </div>
    </div>

    <!-- Quick Actions Panel -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] p-5">
        <h3 class="text-[13px] font-bold text-gray-900 mb-4 tracking-tight">Admin Actions</h3>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.sellers.index') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-lg shadow-sm transition flex items-center gap-2">
                <i class="fa-solid fa-user-tie text-xs"></i> Manage Sellers
            </a>
            <a href="{{ route('admin.hubs.index') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-lg shadow-sm transition flex items-center gap-2">
                <i class="fa-solid fa-shop text-xs"></i> Manage Hubs
            </a>
            <a href="{{ route('admin.couriers.index') }}" class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold text-xs rounded-lg shadow-sm transition flex items-center gap-2">
                <i class="fa-solid fa-truck-fast text-gray-400"></i> Couriers
            </a>
            <a href="{{ route('admin.pincodes.index') }}" class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold text-xs rounded-lg shadow-sm transition flex items-center gap-2">
                <i class="fa-solid fa-map-pin text-gray-400"></i> Pincodes
            </a>
            <a href="{{ route('admin.ratecards.index') }}" class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold text-xs rounded-lg shadow-sm transition flex items-center gap-2">
                <i class="fa-solid fa-file-invoice text-gray-400"></i> Rate Cards
            </a>
            <a href="{{ route('admin.reports.index') }}" class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold text-xs rounded-lg shadow-sm transition flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-gray-400"></i> Reports
            </a>
            <a href="{{ route('admin.ndr.index') }}" class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold text-xs rounded-lg shadow-sm transition flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-gray-400"></i> Global NDR
            </a>
        </div>
    </div>

    <!-- Layout Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left: Courier Performance Table -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-[15px] font-bold text-gray-900 tracking-tight">Courier Performance</h3>
                        <p class="text-[11px] text-gray-400 mt-0.5">Delivery accuracy & RTO metrics</p>
                    </div>
                </div>
                <div class="overflow-x-auto px-4 pb-4 pt-2">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead class="bg-gray-50 rounded-lg">
                            <tr>
                                <th class="px-6 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider rounded-l-lg">PARTNER NAME</th>
                                <th class="px-6 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider">LOAD VOLUME</th>
                                <th class="px-6 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider">EFFICIENCY</th>
                                <th class="px-6 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider">RTO COUNT</th>
                                <th class="px-6 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider text-right rounded-r-lg">STATUS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-gray-700 font-medium">
                            @forelse($courierPerformance ?? [] as $partner)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 font-bold text-gray-900">{{ $partner['name'] }}</td>
                                <td class="px-6 py-4">{{ number_format($partner['load'] ?? 0) }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold 
                                        {{ ($partner['efficiency'] ?? 0) >= 80 ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                        {{ $partner['efficiency'] ?? 0 }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-bold text-rose-500">{{ number_format($partner['rto'] ?? 0) }}</td>
                                <td class="px-6 py-4 text-right">
                                    <span class="flex items-center justify-end gap-1.5 text-[11px] text-gray-500">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-10 text-center text-gray-500">No shipment data recorded.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Bookings Table -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-[15px] font-bold text-gray-900 tracking-tight">Recent Platform Bookings</h3>
                    <a href="{{ route('admin.shipments.index') }}" class="px-4 py-1.5 border border-gray-200 text-gray-600 hover:bg-gray-50 hover:text-gray-900 text-xs font-semibold rounded-lg transition shadow-sm">
                        View All &rarr;
                    </a>
                </div>
                <div class="overflow-x-auto px-4 pb-4 pt-2">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider rounded-l-lg">AWB NUMBER</th>
                                <th class="px-6 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider">SELLER ACCOUNT</th>
                                <th class="px-6 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider">STATUS</th>
                                <th class="px-6 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider text-right rounded-r-lg">ACTION</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-gray-700 font-medium">
                            @forelse($recentShipments ?? [] as $booking)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 font-bold text-indigo-600 hover:text-indigo-800">
                                    <a href="{{ route('admin.shipments.index', ['search' => $booking['awb']]) }}">{{ $booking['awb'] }}</a>
                                </td>
                                <td class="px-6 py-4">{{ $booking['seller'] }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClass = match($booking['status']) {
                                            'Delivered' => 'bg-emerald-50 text-emerald-600',
                                            'In Transit' => 'bg-blue-50 text-blue-600',
                                            'NDR' => 'bg-rose-50 text-rose-600',
                                            'RTO Initiated', 'RTO Delivered' => 'bg-gray-100 text-gray-600',
                                            default => 'bg-amber-50 text-amber-600'
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide {{ $statusClass }}">
                                        {{ $booking['status'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.shipments.index', ['search' => $booking['awb']]) }}" class="text-indigo-600 hover:text-indigo-800 font-bold">View</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-6 py-10 text-center text-gray-500">No recent shipments.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right: Analytics & Zones -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Weak Zones / High NDR -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">High NDR Zones</h3>
                        <p class="text-[11px] text-gray-400 mt-0.5">Areas with frequent delivery failures</p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-rose-50 flex items-center justify-center text-rose-500">
                        <i class="fa-solid fa-map-location-dot text-xs"></i>
                    </div>
                </div>
                
                <div class="space-y-5">
                    @forelse($weakZones ?? [] as $zone)
                    <div>
                        <div class="flex justify-between items-center text-[11px] font-bold mb-1.5 text-gray-700">
                            <span>{{ $zone['city'] }} ({{ $zone['pincode'] }})</span>
                            <span class="text-rose-500">{{ $zone['ndr_rate'] }}% ({{ $zone['total_ndr'] }})</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="bg-rose-500 h-1.5 rounded-full" style="width: {{ min($zone['ndr_rate'], 100) }}%"></div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-gray-500 text-xs py-4">No critical NDR zones detected.</div>
                    @endforelse
                </div>
                
                <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                    <a href="{{ route('admin.ndr.index') }}" class="text-indigo-600 font-bold text-xs hover:underline">
                        Review Actionable NDRs
                    </a>
                </div>
            </div>

            <!-- Network Status Mock Donut -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] p-6">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Network Entities</h3>
                    <p class="text-[11px] text-gray-400 mt-0.5">Sellers vs Hubs vs Field Agents</p>
                </div>
                <div class="flex items-center justify-center gap-8 mt-6 h-40">
                    
                    @php
                        $totalUsers = max(1, ($activeSellers ?? 0) + ($activeHubs ?? 0) + ($totalRiders ?? 0));
                        $p1 = (($activeSellers ?? 0) / $totalUsers) * 100;
                        $p2 = $p1 + ((($activeHubs ?? 0) / $totalUsers) * 100);
                    @endphp
                    <div class="relative w-32 h-32 rounded-full flex items-center justify-center" 
                         style="background: conic-gradient(#3b82f6 0% {{ $p1 }}%, #6366f1 {{ $p1 }}% {{ $p2 }}%, #10b981 {{ $p2 }}% 100%); padding: 14px;">
                        <div class="w-full h-full bg-white rounded-full flex items-center justify-center">
                            <div class="text-center">
                                <div class="text-xl font-black text-gray-900">{{ number_format(($activeSellers??0) + ($activeHubs??0) + ($totalRiders??0)) }}</div>
                                <div class="text-[9px] text-gray-400 font-bold uppercase">Users</div>
                            </div>
                        </div>
                    </div>

                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between gap-6 text-xs font-semibold text-gray-600">
                            <div class="flex items-center gap-2"><div class="w-2 h-2 rounded bg-blue-500"></div> Sellers</div>
                            <span>{{ number_format($activeSellers ?? 0) }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-6 text-xs font-semibold text-gray-600">
                            <div class="flex items-center gap-2"><div class="w-2 h-2 rounded bg-indigo-500"></div> Hubs</div>
                            <span>{{ number_format($activeHubs ?? 0) }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-6 text-xs font-semibold text-gray-600">
                            <div class="flex items-center gap-2"><div class="w-2 h-2 rounded bg-emerald-500"></div> Riders</div>
                            <span>{{ number_format($totalRiders ?? 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Bottom Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 pb-10">
        <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-4 shadow-sm hover:border-gray-200 transition">
            <div class="w-10 h-10 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-400">
                <i class="fa-solid fa-server text-sm"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-900">Database Engine</p>
                <p class="text-[10px] text-gray-500 font-medium">Optimal Response Time</p>
            </div>
        </div>
        
        <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-4 shadow-sm hover:border-gray-200 transition">
            <div class="w-10 h-10 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-400">
                <i class="fa-brands fa-cloudscale text-sm"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-900">API Health</p>
                <p class="text-[10px] text-gray-500 font-medium">All partner APIs responding</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-4 shadow-sm hover:border-gray-200 transition">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-500">
                <i class="fa-solid fa-money-bill-transfer text-sm"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-900">Daily Remittance</p>
                <p class="text-[10px] text-gray-500 font-medium">Auto-settlements active</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-4 shadow-sm hover:border-gray-200 transition">
            <div class="w-10 h-10 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-400">
                <i class="fa-solid fa-shield-halved text-sm"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-900">System Security</p>
                <p class="text-[10px] text-gray-500 font-medium">Cloudflare protected</p>
            </div>
        </div>
    </div>

</div>
@endsection


