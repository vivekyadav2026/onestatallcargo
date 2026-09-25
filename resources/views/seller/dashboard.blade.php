@extends('layouts.seller')
@section('title', 'Seller Dashboard - OneStall Cargo')

@section('content')
<div class="space-y-6">
    
    <!-- Welcome Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-8">
        <div>
            <h1 class="text-[28px] font-bold text-gray-900 tracking-tight">Merchant Overview</h1>
            <p class="text-sm text-gray-500 mt-1">Welcome back, {{ Auth::user()->company_name ?? Auth::user()->name }}. Here is what's happening with your store today.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('seller.bulk') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition shadow-sm">
                <i class="fa-solid fa-file-csv mr-2 text-gray-400"></i> Bulk Booking
            </a>
            <a href="{{ route('seller.book') }}" class="px-4 py-2 bg-[#4338ca] hover:bg-[#3730a3] text-white text-sm font-semibold rounded-lg transition shadow-sm">
                <i class="fa-solid fa-plus mr-2"></i> Create Shipment
            </a>
        </div>
    </div>

    <!-- Premium Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110 z-0"></div>
            <div class="relative z-10 flex justify-between items-start">
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Orders</p>
                    <h3 class="text-3xl font-extrabold text-gray-900">{{ $totalOrders ?? 0 }}</h3>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 text-lg shadow-sm"><i class="fa-solid fa-box"></i></div>
            </div>
            <div class="relative z-10 mt-4 text-xs font-semibold text-green-600 flex items-center">
                <i class="fa-solid fa-arrow-trend-up mr-1"></i> +12% from last week
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-green-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110 z-0"></div>
            <div class="relative z-10 flex justify-between items-start">
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Wallet Balance</p>
                    <h3 class="text-3xl font-extrabold text-gray-900">&#8377; {{ number_format(Auth::user()->wallet_balance ?? 0, 2) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center text-green-600 text-lg shadow-sm"><i class="fa-solid fa-wallet"></i></div>
            </div>
            <div class="relative z-10 mt-4 flex items-center">
                <a href="{{ route('seller.wallet') }}" class="text-xs font-bold text-[#4338ca] hover:underline">Recharge Now &rarr;</a>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-purple-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110 z-0"></div>
            <div class="relative z-10 flex justify-between items-start">
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Delivered</p>
                    <h3 class="text-3xl font-extrabold text-gray-900">{{ $deliveredOrders ?? 0 }}</h3>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600 text-lg shadow-sm"><i class="fa-solid fa-check-double"></i></div>
            </div>
            <div class="relative z-10 mt-4 text-xs font-semibold text-gray-500 flex items-center">
                Across all active couriers
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-yellow-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110 z-0"></div>
            <div class="relative z-10 flex justify-between items-start">
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Pending COD</p>
                    <h3 class="text-3xl font-extrabold text-gray-900">&#8377; {{ number_format($codPending ?? 0, 2) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center text-yellow-600 text-lg shadow-sm"><i class="fa-solid fa-indian-rupee-sign"></i></div>
            </div>
            <div class="relative z-10 mt-4 text-xs font-semibold text-yellow-600 flex items-center">
                <i class="fa-regular fa-clock mr-1"></i> Awaiting remittance
            </div>
        </div>

    </div>

    <!-- Recent Shipments Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mt-8">
        <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center bg-white">
            <h2 class="font-bold text-gray-900 text-lg">Recent Shipments</h2>
            <a href="{{ route('seller.shipments.index') }}" class="text-sm font-semibold text-[#4338ca] hover:underline">View All &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                        <th class="px-6 py-3">AWB / Order ID</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Payment</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentShipments as $shipment)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-[#4338ca]">{{ $shipment->awb_number }}</div>
                            <div class="text-xs text-gray-500">Order: {{ $shipment->order_id ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-500 font-medium">{{ $shipment->created_at->format('d M Y, h:i A') }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-700">{{ $shipment->receiver_name }}</div>
                            <div class="text-[10px] text-gray-500">{{ $shipment->delivery_city }}, {{ $shipment->delivery_state }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($shipment->is_cod)
                                <span class="px-2 py-1 rounded bg-yellow-50 text-yellow-700 font-bold text-[10px] uppercase border border-yellow-200">COD (&#8377; {{ $shipment->invoice_value }})</span>
                            @else
                                <span class="px-2 py-1 rounded bg-green-50 text-green-700 font-bold text-[10px] uppercase border border-green-200">Prepaid</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-100">{{ $shipment->status }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('seller.label', $shipment->awb_number) }}" target="_blank" class="text-gray-500 hover:text-[#4338ca] font-semibold text-xs border border-gray-200 px-3 py-1.5 rounded bg-white hover:bg-gray-50 transition shadow-sm">
                                <i class="fa-solid fa-print mr-1"></i> Label
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="w-16 h-16 mx-auto bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-4 border border-gray-100">
                                <i class="fa-solid fa-box-open text-2xl"></i>
                            </div>
                            <h3 class="text-base font-bold text-gray-900 mb-1">No shipments yet</h3>
                            <p class="text-sm text-gray-500 font-medium">Your recent shipments will appear here once you start booking.</p>
                            <a href="{{ route('seller.book') }}" class="mt-4 inline-block px-5 py-2 bg-[#4338ca] text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-[#3730a3] transition">
                                Book First Shipment
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
