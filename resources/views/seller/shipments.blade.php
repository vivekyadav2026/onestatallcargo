@extends('layouts.seller')
@section('title', 'Orders - OneStall Cargo')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'all', showAddDropdown: false }">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Orders</h1>
        </div>
        
        <div class="flex items-center gap-3 relative">
            <a href="{{ route('seller.bulk') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 text-xs font-bold rounded-xl hover:bg-gray-50 transition shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-file-csv text-gray-400"></i> Bulk Order Update
            </a>
            
            <!-- Add Order Dropdown -->
            <div class="relative">
                <button @click="showAddDropdown = !showAddDropdown" class="px-5 py-2.5 bg-[#0f172a] hover:bg-black text-white text-xs font-bold rounded-xl shadow-md transition flex items-center gap-2">
                    Add Order <i class="fa-solid fa-chevron-down text-[10px]"></i>
                </button>
                <div x-show="showAddDropdown" @click.away="showAddDropdown = false" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-xl py-2 z-30" style="display: none;">
                    <a href="{{ route('seller.book') }}" class="block px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50"><i class="fa-solid fa-plus text-blue-600 mr-2"></i> Single Order</a>
                    <a href="{{ route('seller.bulk') }}" class="block px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50"><i class="fa-solid fa-file-excel text-green-600 mr-2"></i> Bulk CSV Upload</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card Container -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        
        <!-- Status Tabs (Matches Screenshot 3) -->
        <div class="flex border-b border-gray-100 px-4 pt-2 overflow-x-auto whitespace-nowrap bg-white">
            <button @click="activeTab = 'new'" :class="activeTab === 'new' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-5 py-3 text-xs transition">New <span class="ml-1 px-1.5 py-0.5 bg-blue-50 text-blue-700 rounded-full font-extrabold text-[10px]">{{ $shipments->where('status', 'new')->count() }}</span></button>
            <button @click="activeTab = 'pickups'" :class="activeTab === 'pickups' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-5 py-3 text-xs transition">Pickups <span class="ml-1 px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded-full font-extrabold text-[10px]">{{ $shipments->where('status', 'pickup_scheduled')->count() }}</span></button>
            <button @click="activeTab = 'transit'" :class="activeTab === 'transit' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-5 py-3 text-xs transition">In Transit <span class="ml-1 px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded-full font-extrabold text-[10px]">{{ $shipments->where('status', 'in_transit')->count() }}</span></button>
            <button @click="activeTab = 'delivered'" :class="activeTab === 'delivered' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-5 py-3 text-xs transition">Delivered <span class="ml-1 px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded-full font-extrabold text-[10px]">{{ $shipments->where('status', 'delivered')->count() }}</span></button>
            <button @click="activeTab = 'rto'" :class="activeTab === 'rto' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-5 py-3 text-xs transition">RTO <span class="ml-1 px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded-full font-extrabold text-[10px]">{{ $shipments->where('status', 'rto')->count() }}</span></button>
            <button @click="activeTab = 'all'" :class="activeTab === 'all' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-5 py-3 text-xs transition">All</button>
        </div>

        <!-- Filters Row (Matches Screenshot 3) -->
        <div class="p-4 bg-[#f8fafc] border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <select class="bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 outline-none">
                    <option>Synced On</option>
                    <option>Created Date</option>
                </select>

                <div class="bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 flex items-center gap-2">
                    <span>19/08/2026 ~ 25/09/2026</span>
                    <i class="fa-solid fa-xmark text-gray-400 cursor-pointer"></i>
                </div>

                <select class="bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 outline-none">
                    <option>Order ID</option>
                    <option>AWB Number</option>
                </select>

                <select class="bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 outline-none">
                    <option>Payment Mode</option>
                    <option>Prepaid</option>
                    <option>COD</option>
                </select>

                <select class="bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 outline-none">
                    <option>Status</option>
                    <option>New</option>
                    <option>Delivered</option>
                </select>

                <button class="px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 flex items-center gap-1.5 hover:bg-gray-50">
                    <i class="fa-solid fa-sliders text-gray-400"></i> More Filters
                </button>
            </div>

            <div class="flex items-center gap-2">
                <button onclick="window.location.reload()" class="px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-700 flex items-center gap-1.5 hover:bg-gray-50">
                    <i class="fa-solid fa-rotate-right text-gray-400"></i> Refresh
                </button>
                <button class="px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-700 flex items-center gap-1.5 hover:bg-gray-50">
                    <i class="fa-solid fa-download text-gray-400"></i> Export
                </button>
            </div>
        </div>

        <!-- Table View (Matches Screenshot 3) -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50/80 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                        <th class="px-4 py-3"><input type="checkbox" class="rounded border-gray-300"></th>
                        <th class="px-4 py-3">ORDER DETAILS</th>
                        <th class="px-4 py-3">SHIPPING ADDRESS</th>
                        <th class="px-4 py-3">PRODUCT DETAILS</th>
                        <th class="px-4 py-3">PACKAGE DETAILS</th>
                        <th class="px-4 py-3">ORDER VALUE</th>
                        <th class="px-4 py-3">PICKUP ADDRESS</th>
                        <th class="px-4 py-3">ORDER TAGS</th>
                        <th class="px-4 py-3">STATUS</th>
                        <th class="px-4 py-3 text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium text-gray-700">
                    @forelse($shipments as $shipment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3"><input type="checkbox" class="rounded border-gray-300"></td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-gray-900">{{ $shipment->order_id ?? $shipment->awb_number }}</div>
                                <div class="text-[10px] text-gray-400">{{ $shipment->created_at->format('d M Y, h:i A') }}</div>
                                <div class="text-[10px] font-mono text-[#4338ca]">{{ $shipment->awb_number }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-gray-800">{{ $shipment->receiver_name }}</div>
                                <div class="text-[10px] text-gray-500">{{ $shipment->delivery_city }}, {{ $shipment->delivery_pincode }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-xs text-gray-800 font-semibold">General Cargo Parcel</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-xs text-gray-700 font-semibold">{{ $shipment->weight ?? 0.5 }} kg</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-gray-900">&#8377; {{ number_format($shipment->invoice_value, 2) }}</div>
                                <span class="text-[9px] font-bold uppercase px-1.5 py-0.5 rounded {{ $shipment->is_cod ? 'bg-amber-100 text-amber-800' : 'bg-green-100 text-green-800' }}">{{ $shipment->is_cod ? 'COD' : 'PREPAID' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-xs text-gray-700">{{ Auth::user()->company_name ?? 'Default Hub' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-[9px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded font-bold">Standard</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-[10px] font-extrabold uppercase bg-blue-50 text-blue-700 border border-blue-100">{{ $shipment->status }}</span>
                            </td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <a href="{{ route('seller.label', $shipment->awb_number) }}" target="_blank" class="px-2.5 py-1 bg-white border border-gray-200 rounded text-gray-700 font-bold hover:bg-gray-50 shadow-sm"><i class="fa-solid fa-print"></i> Label</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-20 text-center">
                                <div class="text-gray-300 mb-2">
                                    <i class="fa-solid fa-box-archive text-4xl"></i>
                                </div>
                                <p class="text-sm font-bold text-gray-400">No orders found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($shipments->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $shipments->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
