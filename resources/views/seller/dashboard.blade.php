@extends('layouts.seller')
@section('title', 'Dashboard - OneStall Cargo')

@section('content')
<div class="space-y-6">
    
    <!-- Welcome Banner (Clean Dark Card like Bigship) -->
    <div class="bg-[#181824] rounded-2xl p-7 md:p-8 text-white relative overflow-hidden shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-1">
            <p class="text-[11px] font-bold text-gray-400 tracking-wider uppercase">GOOD MORNING</p>
            <h2 class="text-2xl md:text-3xl font-normal text-white tracking-tight">
                Welcome Back, <span class="font-bold text-[#6366f1]">{{ Auth::user()->name ?? 'Seller' }}</span>
            </h2>
            <p class="text-xs md:text-sm text-gray-300">Here's what's moving across your shipments today.</p>
        </div>
        <div class="shrink-0 flex items-center gap-3">
            <a href="{{ route('seller.book') }}" class="px-6 py-2.5 bg-[#4f46e5] hover:bg-[#4338ca] text-white font-semibold text-xs md:text-sm rounded-full shadow-md transition flex items-center gap-2">
                <i class="fa-solid fa-plus text-xs"></i> Book Shipment
            </a>
        </div>
    </div>

    <!-- Quick Actions Row (Clean White Card with 6 Minimal Icons) -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-5 md:p-6">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 text-center">
            
            <a href="{{ route('seller.tools') }}" class="flex flex-col items-center gap-2.5 group py-2 hover:bg-gray-50/80 rounded-xl transition">
                <div class="w-13 h-13 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center group-hover:scale-105 group-hover:shadow-xs transition p-2.5">
                    <img src="https://cdn-icons-png.flaticon.com/512/869/869061.png" alt="Calculator" class="w-7 h-7 object-contain">
                </div>
                <span class="text-xs font-bold text-gray-700 group-hover:text-[#4338ca] transition">Rate Calculator</span>
            </a>

            <a href="{{ route('seller.settings') }}?view=warehouses" class="flex flex-col items-center gap-2.5 group py-2 hover:bg-gray-50/80 rounded-xl transition">
                <div class="w-13 h-13 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center group-hover:scale-105 group-hover:shadow-xs transition p-2.5">
                    <img src="https://cdn-icons-png.flaticon.com/512/2830/2830303.png" alt="Warehouse" class="w-7 h-7 object-contain">
                </div>
                <span class="text-xs font-bold text-gray-700 group-hover:text-[#4338ca] transition">Add Warehouse</span>
            </a>

            <a href="{{ route('seller.wallet') }}" class="flex flex-col items-center gap-2.5 group py-2 hover:bg-gray-50/80 rounded-xl transition">
                <div class="w-13 h-13 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center group-hover:scale-105 group-hover:shadow-xs transition p-2.5">
                    <img src="https://cdn-icons-png.flaticon.com/512/2143/2143144.png" alt="Wallet" class="w-7 h-7 object-contain">
                </div>
                <span class="text-xs font-bold text-gray-700 group-hover:text-[#4338ca] transition">Recharge Wallet</span>
            </a>

            <a href="{{ route('seller.wallet') }}" class="flex flex-col items-center gap-2.5 group py-2 hover:bg-gray-50/80 rounded-xl transition">
                <div class="w-13 h-13 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center group-hover:scale-105 group-hover:shadow-xs transition p-2.5">
                    <img src="https://cdn-icons-png.flaticon.com/512/2489/2489756.png" alt="Early COD" class="w-7 h-7 object-contain">
                </div>
                <span class="text-xs font-bold text-gray-700 group-hover:text-[#4338ca] transition">Early COD</span>
            </a>

            <a href="{{ route('seller.book') }}" class="flex flex-col items-center gap-2.5 group py-2 hover:bg-gray-50/80 rounded-xl transition">
                <div class="w-13 h-13 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center group-hover:scale-105 group-hover:shadow-xs transition p-2.5">
                    <img src="https://cdn-icons-png.flaticon.com/512/4236/4236968.png" alt="Book Order" class="w-7 h-7 object-contain">
                </div>
                <span class="text-xs font-bold text-gray-700 group-hover:text-[#4338ca] transition">Book Order</span>
            </a>

            <a href="{{ route('seller.settings') }}?view=company" class="flex flex-col items-center gap-2.5 group py-2 hover:bg-gray-50/80 rounded-xl transition">
                <div class="w-13 h-13 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center group-hover:scale-105 group-hover:shadow-xs transition p-2.5">
                    <img src="https://cdn-icons-png.flaticon.com/512/2760/2760155.png" alt="Transporter" class="w-7 h-7 object-contain">
                </div>
                <span class="text-xs font-bold text-gray-700 group-hover:text-[#4338ca] transition">Transporter ID</span>
            </a>

        </div>
    </div>

    <!-- Important Notice Banner -->
    @if(!isset($kyc) || $kyc->status !== 'approved')
    <div class="bg-amber-50/80 border border-amber-200/80 rounded-xl p-4 flex items-center gap-3">
        <i class="fa-solid fa-circle-info text-amber-600 text-sm"></i>
        <p class="text-xs text-amber-900 leading-relaxed">
            <span class="font-bold">Important Note:</span> To avoid shipment delays, ensure all required documents are signed, stamped, enclosed, and uploaded. 
            <a href="{{ route('seller.settings') }}?view=kyc" class="font-bold text-amber-800 underline ml-1">See Document &rarr;</a>
        </p>
    </div>
    @endif

    <!-- Shipment Operations Overview Section (100% Dynamic) -->
    <div class="space-y-4 pt-2">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-[#181824] text-white flex items-center justify-center text-xs">
                    <i class="fa-solid fa-cube"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 tracking-tight">Shipment Operations Overview</h3>
            </div>
            
            <!-- Quick Channel & Mode Filters Form -->
            <form method="GET" action="{{ route('seller.dashboard') }}" class="flex items-center gap-2">
                <input type="hidden" name="courier" value="{{ request('courier', 'all') }}">
                
                <select name="shipment_type" onchange="this.form.submit()" class="text-xs border border-gray-200 rounded-lg py-1.5 px-2.5 bg-white text-gray-700 outline-none focus:border-indigo-500 font-medium">
                    <option value="all" {{ request('shipment_type') == 'all' ? 'selected' : '' }}>All Modes</option>
                    <option value="B2C" {{ request('shipment_type') == 'B2C' ? 'selected' : '' }}>B2C Domestic</option>
                    <option value="B2B" {{ request('shipment_type') == 'B2B' ? 'selected' : '' }}>B2B Cargo</option>
                    <option value="International" {{ request('shipment_type') == 'International' ? 'selected' : '' }}>International</option>
                </select>

                <select name="days" onchange="this.form.submit()" class="text-xs border border-gray-200 rounded-lg py-1.5 px-2.5 bg-white text-gray-700 outline-none focus:border-indigo-500 font-medium">
                    <option value="60" {{ request('days', '60') == '60' ? 'selected' : '' }}>Last 60 Days</option>
                    <option value="30" {{ request('days') == '30' ? 'selected' : '' }}>Last 30 Days</option>
                    <option value="7" {{ request('days') == '7' ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="all" {{ request('days') == 'all' ? 'selected' : '' }}>All Time</option>
                </select>
            </form>
        </div>

        <!-- Metrics Grid (1 Dark Card + 5 Dynamic White Cards) -->
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
            
            <!-- 1. Total Shipments (Clean Dark Card) -->
            <a href="{{ route('seller.shipments.index') }}?status=all" class="col-span-2 md:col-span-1 bg-[#181824] hover:bg-[#202030] text-white p-5 rounded-2xl flex flex-col justify-between shadow-xs transition group">
                <p class="text-xs font-medium text-gray-400 group-hover:text-white transition">Total Shipments</p>
                <div class="my-3">
                    <h3 class="text-4xl font-normal text-white">{{ $totalOrders }}</h3>
                </div>
                <p class="text-[11px] text-gray-400 flex items-center justify-between">
                    <span>{{ request('days') == '30' ? 'Last 30 days' : (request('days') == '7' ? 'Last 7 days' : 'Last 60 days') }}</span>
                    <i class="fa-solid fa-arrow-right text-[10px] opacity-0 group-hover:opacity-100 transition"></i>
                </p>
            </a>
            
            <!-- 2. Today's Shipment -->
            <a href="{{ route('seller.shipments.index') }}?status=all&start_date={{ date('Y-m-d') }}&end_date={{ date('Y-m-d') }}" class="bg-white hover:border-indigo-300 p-5 rounded-2xl border border-gray-200/80 shadow-xs flex flex-col justify-between transition group">
                <p class="text-xs font-medium text-gray-500 group-hover:text-[#4338ca] transition">Today's Shipment</p>
                <div class="my-3">
                    <h3 class="text-3xl font-bold text-gray-900">{{ $todayShipments }}</h3>
                </div>
                <p class="text-[11px] text-gray-400">{{ \Carbon\Carbon::today()->format('M j, Y') }}</p>
            </a>

            <!-- 3. Yesterday's Shipment -->
            <a href="{{ route('seller.shipments.index') }}?status=all&start_date={{ date('Y-m-d', strtotime('-1 day')) }}&end_date={{ date('Y-m-d', strtotime('-1 day')) }}" class="bg-white hover:border-indigo-300 p-5 rounded-2xl border border-gray-200/80 shadow-xs flex flex-col justify-between transition group">
                <p class="text-xs font-medium text-gray-500 group-hover:text-[#4338ca] transition">Yesterday's Shipment</p>
                <div class="my-3">
                    <h3 class="text-3xl font-bold text-gray-900">{{ $yesterdayShipments }}</h3>
                </div>
                <p class="text-[11px] text-gray-400">{{ \Carbon\Carbon::yesterday()->format('M j, Y') }}</p>
            </a>

            <!-- 4. Unshipped -->
            <a href="{{ route('seller.shipments.index') }}?status=new" class="bg-white hover:border-indigo-300 p-5 rounded-2xl border border-gray-200/80 shadow-xs flex flex-col justify-between transition group">
                <p class="text-xs font-medium text-gray-500 group-hover:text-[#4338ca] transition">Unshipped</p>
                <div class="my-3">
                    <h3 class="text-3xl font-bold text-gray-900">{{ $unshippedCount }}</h3>
                </div>
                <p class="text-[11px] text-amber-600 font-medium flex items-center gap-1">
                    Ready to manifest <i class="fa-solid fa-circle-info text-[10px] text-gray-300"></i>
                </p>
            </a>

            <!-- 5. Total Load -->
            <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-xs flex flex-col justify-between">
                <p class="text-xs font-medium text-gray-500">Total Load</p>
                <div class="my-3">
                    <h3 class="text-3xl font-bold text-gray-900">{{ $totalLoadKg }}</h3>
                </div>
                <p class="text-[11px] text-gray-400 flex items-center gap-1">
                    In Kg <i class="fa-solid fa-circle-info text-[10px] text-gray-300"></i>
                </p>
            </div>

            <!-- 6. Avg Shipping Cost -->
            <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-xs flex flex-col justify-between">
                <p class="text-xs font-medium text-gray-500">Avg. Shipping Cost</p>
                <div class="my-3">
                    <h3 class="text-3xl font-bold text-gray-900"><span class="text-base text-gray-400 font-normal">&#8377;</span>{{ number_format($avgShippingCost, 2) }}</h3>
                </div>
                <p class="text-[11px] text-gray-400 flex items-center gap-1">
                    Per shipment avg <i class="fa-solid fa-circle-info text-[10px] text-gray-300"></i>
                </p>
            </div>

        </div>
        
        <!-- Shipment Journey / Funnel (Clean White Card) -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-6 space-y-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Shipment Journey</h3>
                    <p class="text-xs text-gray-500">Where shipments are in the delivery pipeline</p>
                </div>
                
                <!-- Journey Filters Form -->
                <form method="GET" action="{{ route('seller.dashboard') }}" class="flex flex-wrap items-center gap-2">
                    <input type="hidden" name="days" value="{{ request('days', '60') }}">
                    <input type="hidden" name="shipment_type" value="{{ request('shipment_type', 'all') }}">
                    
                    <select name="courier" onchange="this.form.submit()" class="text-xs border border-gray-200 rounded-lg py-1.5 px-3 bg-white text-gray-700 outline-none focus:border-indigo-500 font-medium">
                        <option value="all">All Couriers</option>
                        @if(isset($couriers))
                            @foreach($couriers as $c)
                                <option value="{{ $c->name }}" {{ request('courier') == $c->name ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        @endif
                    </select>

                    <a href="{{ route('seller.shipments.index') }}" class="bg-[#3b82f6] text-white text-xs px-3.5 py-1.5 rounded-lg font-semibold shadow-xs">
                        Manifested Date
                    </a>
                    
                    <a href="{{ route('seller.dashboard') }}" class="bg-[#181824] text-white text-xs px-3.5 py-1.5 rounded-lg font-semibold flex items-center gap-1.5 hover:bg-black transition">
                        <i class="fa-solid fa-rotate-right text-[11px]"></i> Reload Data
                    </a>
                </form>
            </div>

            <!-- Multi-colored Linear Progress Bar (Dynamic Ratios) -->
            @php
                $pickupPct = $totalOrders > 0 ? max(2, round(($pickupsScheduled / $totalOrders) * 100)) : 25;
                $transitPct = $totalOrders > 0 ? max(2, round(($inTransit / $totalOrders) * 100)) : 25;
                $ofdPct = $totalOrders > 0 ? max(2, round(($outForDelivery / $totalOrders) * 100)) : 15;
                $delPct = $totalOrders > 0 ? max(2, round(($deliveredOrders / $totalOrders) * 100)) : 35;
            @endphp
            <div class="w-full h-1.5 rounded-full overflow-hidden flex bg-gray-100">
                <div class="bg-blue-500" style="width: {{ $pickupPct }}%"></div>
                <div class="bg-amber-400" style="width: {{ $transitPct }}%"></div>
                <div class="bg-sky-400" style="width: {{ $ofdPct }}%"></div>
                <div class="bg-emerald-500" style="width: {{ $delPct }}%"></div>
            </div>
            
            <!-- 4 Stage Status Cards (Clickable to Filter) -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                
                <!-- Pickup Scheduled -->
                <a href="{{ route('seller.shipments.index') }}?status=pickups" class="p-4 rounded-xl border border-gray-200/80 flex items-center gap-3.5 bg-white hover:border-blue-300 transition group">
                    <div class="w-9 h-9 rounded-full bg-[#181824] text-white flex items-center justify-center text-xs shrink-0 group-hover:scale-105 transition">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500">Pickup Scheduled</p>
                        <p class="text-base font-bold text-gray-900 mt-0.5">
                            {{ $pickupsScheduled }} <span class="text-[11px] text-gray-400 font-normal">({{ $totalOrders > 0 ? round(($pickupsScheduled / $totalOrders) * 100, 2) : 0 }}%)</span>
                        </p>
                    </div>
                </a>

                <!-- In-Transit -->
                <a href="{{ route('seller.shipments.index') }}?status=transit" class="p-4 rounded-xl border border-gray-200/80 flex items-center gap-3.5 bg-white hover:border-amber-300 transition group">
                    <div class="w-9 h-9 rounded-full bg-amber-400 text-white flex items-center justify-center text-xs shrink-0 group-hover:scale-105 transition">
                        <i class="fa-solid fa-truck"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500">In-Transit</p>
                        <p class="text-base font-bold text-gray-900 mt-0.5">
                            {{ $inTransit }} <span class="text-[11px] text-gray-400 font-normal">({{ $totalOrders > 0 ? round(($inTransit / $totalOrders) * 100, 2) : 0 }}%)</span>
                        </p>
                    </div>
                </a>

                <!-- Out for Delivery -->
                <a href="{{ route('seller.shipments.index') }}?status=out_for_delivery" class="p-4 rounded-xl border border-gray-200/80 flex items-center gap-3.5 bg-white hover:border-sky-300 transition group">
                    <div class="w-9 h-9 rounded-full bg-sky-400 text-white flex items-center justify-center text-xs shrink-0 group-hover:scale-105 transition">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500">Out for Delivery</p>
                        <p class="text-base font-bold text-gray-900 mt-0.5">
                            {{ $outForDelivery }} <span class="text-[11px] text-gray-400 font-normal">({{ $totalOrders > 0 ? round(($outForDelivery / $totalOrders) * 100, 2) : 0 }}%)</span>
                        </p>
                    </div>
                </a>

                <!-- Delivered -->
                <a href="{{ route('seller.shipments.index') }}?status=delivered" class="p-4 rounded-xl border border-emerald-200 bg-emerald-50/40 flex items-center gap-3.5 hover:border-emerald-300 transition group">
                    <div class="w-9 h-9 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs shrink-0 group-hover:scale-105 transition">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-emerald-800">Delivered</p>
                        <p class="text-base font-bold text-gray-900 mt-0.5">
                            {{ $deliveredOrders }} <span class="text-[11px] text-emerald-600 font-normal">({{ $totalOrders > 0 ? round(($deliveredOrders / $totalOrders) * 100, 2) : 0 }}%)</span>
                        </p>
                    </div>
                </a>

            </div>
            
            <!-- Exceptions Section (Bigship style) -->
            <div class="pt-2">
                <div class="flex justify-between items-center mb-3">
                    <p class="text-[11px] font-bold text-rose-500 uppercase tracking-widest">EXCEPTIONS</p>
                    <p class="text-xs font-bold text-gray-700">
                        {{ $totalExceptions }} <span class="text-[11px] text-gray-400 font-normal">({{ $totalOrders > 0 ? round(($totalExceptions / $totalOrders) * 100, 2) : 0 }}%)</span>
                    </p>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    
                    <a href="{{ route('seller.ndr') }}" class="p-3.5 rounded-xl border border-gray-200/70 flex items-center gap-3 bg-white hover:border-rose-200 transition">
                        <div class="w-7 h-7 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center text-[10px] shrink-0 font-bold">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-medium text-gray-500">Undelivered (NDR)</p>
                            <p class="text-sm font-bold text-gray-900">{{ $ndrCount }}</p>
                        </div>
                    </a>

                    <a href="{{ route('seller.shipments.index') }}?status=rto" class="p-3.5 rounded-xl border border-gray-200/70 flex items-center gap-3 bg-white hover:border-rose-200 transition">
                        <div class="w-7 h-7 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center text-[10px] shrink-0 font-bold">
                            <i class="fa-solid fa-rotate-left"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-medium text-gray-500">RTO In-Transit</p>
                            <p class="text-sm font-bold text-gray-900">{{ $rtoInTransit }}</p>
                        </div>
                    </a>

                    <a href="{{ route('seller.shipments.index') }}?status=rto" class="p-3.5 rounded-xl border border-gray-200/70 flex items-center gap-3 bg-white hover:border-rose-200 transition">
                        <div class="w-7 h-7 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center text-[10px] shrink-0 font-bold">
                            <i class="fa-solid fa-rotate-left"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-medium text-gray-500">RTO Delivered</p>
                            <p class="text-sm font-bold text-gray-900">{{ $rtoDelivered }}</p>
                        </div>
                    </a>

                    <a href="{{ route('seller.shipments.index') }}?status=lost" class="p-3.5 rounded-xl border border-gray-200/70 flex items-center gap-3 bg-white hover:border-rose-200 transition">
                        <div class="w-7 h-7 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center text-[10px] shrink-0 font-bold">
                            <i class="fa-solid fa-xmark"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-medium text-gray-500">Lost</p>
                            <p class="text-sm font-bold text-gray-900">{{ $lostCount }}</p>
                        </div>
                    </a>

                </div>
            </div>
        </div>
        
        <!-- Latest Orders Table (Exact Bigship styling) -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
            <div class="px-6 py-4.5 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-900 text-sm">Latest Orders</h3>
                <a href="{{ route('seller.shipments.index') }}" class="px-4 py-1.5 border border-blue-200 text-blue-600 hover:bg-blue-50 text-xs font-semibold rounded-full transition">
                    View All Orders
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead class="bg-[#5c72a8] text-white font-semibold">
                        <tr>
                            <th class="px-6 py-3 w-16 text-center text-blue-100">#</th>
                            <th class="px-6 py-3 text-white">LR / AWB NO.</th>
                            <th class="px-6 py-3 text-white">CONSIGNEE</th>
                            <th class="px-6 py-3 text-right text-white">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700 font-medium">
                        @forelse($recentShipments as $index => $shipment)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-6 py-4 text-center text-gray-400 font-mono">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('seller.shipments.index') }}?search={{ $shipment->awb_number }}" class="font-bold text-gray-900 hover:text-blue-600">
                                    {{ $shipment->awb_number }}
                                </a>
                                <div class="text-[11px] text-gray-400 mt-0.5">{{ $shipment->created_at->format('M j, Y - g:i A') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $shipment->receiver_name }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-semibold text-gray-800 text-xs">
                                    {{ ucfirst($shipment->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400 font-medium">
                                No recent orders found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
