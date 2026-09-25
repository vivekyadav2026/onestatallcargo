<?php
$file = 'resources/views/seller/shipments.blade.php';

$code = <<<'EOD'
@extends('layouts.seller')
@section('title', 'Orders - OneStall Cargo')

@section('content')
<div class="space-y-6" x-data="shipmentTable()">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Orders</h1>
        </div>
        
        <div class="flex items-center gap-3 relative" x-data="{ showAddDropdown: false }">
            <a href="{{ route('seller.bulk') }}" class="px-4 py-2.5 bg-white border border-gray-200 text-gray-700 text-xs font-bold rounded-xl hover:bg-gray-50 transition shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-file-csv text-gray-400"></i> Bulk Order Update
            </a>
            
            <!-- Add Order Dropdown -->
            <div class="relative">
                <button @click="showAddDropdown = !showAddDropdown" class="px-5 py-2.5 bg-[#1e1b4b] hover:bg-black text-white text-xs font-bold rounded-xl shadow-md transition flex items-center gap-2">
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
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-visible">
        
        <!-- Status Tabs (URL Query Preserving) -->
        @php
            $currentStatus = request('status', 'new');
            $counts = [
                'new' => \App\Models\Shipment::where('user_id', Auth::id())->whereIn('status', ['new', 'Manifested', 'Booked', 'booked', 'New'])->count(),
                'pickups' => \App\Models\Shipment::where('user_id', Auth::id())->whereIn('status', ['pickup_scheduled', 'Pickup Scheduled', 'pickups'])->count(),
                'transit' => \App\Models\Shipment::where('user_id', Auth::id())->whereIn('status', ['in_transit', 'In Transit', 'transit'])->count(),
                'delivered' => \App\Models\Shipment::where('user_id', Auth::id())->whereIn('status', ['delivered', 'Delivered'])->count(),
                'rto' => \App\Models\Shipment::where('user_id', Auth::id())->whereIn('status', ['rto', 'RTO'])->count(),
                'all' => \App\Models\Shipment::where('user_id', Auth::id())->count(),
            ];

            $kycRecord = \App\Models\Kyc::where('user_id', Auth::id())->first();
            $isKycApproved = $kycRecord && $kycRecord->status === 'approved';

            function getTabUrl($statusVal) {
                $params = request()->query();
                $params['status'] = $statusVal;
                unset($params['page']);
                return '?' . http_build_query($params);
            }
        @endphp

        <div class="flex border-b border-gray-100 px-4 pt-2 overflow-x-auto whitespace-nowrap bg-white">
            <a href="{{ getTabUrl('new') }}" class="{{ $currentStatus === 'new' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700' }} px-5 py-3 text-xs transition flex items-center gap-1.5">
                New <span class="px-1.5 py-0.5 bg-blue-50 text-blue-700 rounded-full font-extrabold text-[10px]">{{ $counts['new'] }}</span>
            </a>
            <a href="{{ getTabUrl('pickups') }}" class="{{ $currentStatus === 'pickups' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700' }} px-5 py-3 text-xs transition flex items-center gap-1.5">
                Pickups <span class="px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded-full font-extrabold text-[10px]">{{ $counts['pickups'] }}</span>
            </a>
            <a href="{{ getTabUrl('transit') }}" class="{{ $currentStatus === 'transit' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700' }} px-5 py-3 text-xs transition flex items-center gap-1.5">
                In Transit <span class="px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded-full font-extrabold text-[10px]">{{ $counts['transit'] }}</span>
            </a>
            <a href="{{ getTabUrl('delivered') }}" class="{{ $currentStatus === 'delivered' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700' }} px-5 py-3 text-xs transition flex items-center gap-1.5">
                Delivered <span class="px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded-full font-extrabold text-[10px]">{{ $counts['delivered'] }}</span>
            </a>
            <a href="{{ getTabUrl('rto') }}" class="{{ $currentStatus === 'rto' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700' }} px-5 py-3 text-xs transition flex items-center gap-1.5">
                RTO <span class="px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded-full font-extrabold text-[10px]">{{ $counts['rto'] }}</span>
            </a>
            <a href="{{ getTabUrl('all') }}" class="{{ $currentStatus === 'all' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700' }} px-5 py-3 text-xs transition flex items-center gap-1.5">
                All
            </a>
        </div>

        <!-- Single Line Compact Filters Row -->
        <form action="{{ route('seller.shipments.index') }}" method="GET" class="p-3 bg-[#f8fafc] border-b border-gray-100 flex flex-nowrap items-center justify-between gap-2 overflow-x-auto">
            <input type="hidden" name="status" value="{{ request('status', 'new') }}">
            
            <div class="flex items-center gap-2 shrink-0">
                <select name="date_filter" onchange="this.form.submit()" class="bg-white border border-gray-200 rounded-xl px-2.5 py-1.5 text-xs font-semibold text-gray-700 outline-none">
                    <option value="created">Created Date</option>
                    <option value="synced">Synced Date</option>
                </select>

                <div class="relative flex items-center">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Order ID / AWB / Mobile" class="bg-white border border-gray-200 rounded-xl pl-2.5 pr-7 py-1.5 text-xs font-semibold text-gray-700 outline-none w-48 focus:border-[#4338ca]">
                    <button type="submit" class="absolute right-2 text-gray-400 hover:text-[#4338ca]"><i class="fa-solid fa-magnifying-glass text-[10px]"></i></button>
                </div>

                <select name="payment_mode" onchange="this.form.submit()" class="bg-white border border-gray-200 rounded-xl px-2.5 py-1.5 text-xs font-semibold text-gray-700 outline-none">
                    <option value="">Payment Mode (All)</option>
                    <option value="prepaid" {{ request('payment_mode') === 'prepaid' ? 'selected' : '' }}>Prepaid</option>
                    <option value="cod" {{ request('payment_mode') === 'cod' ? 'selected' : '' }}>COD</option>
                </select>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('seller.shipments.index') }}" class="px-3 py-1.5 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-700 flex items-center gap-1.5 hover:bg-gray-50 shadow-sm">
                    <i class="fa-solid fa-rotate-right text-gray-400"></i> Reset Filters
                </a>
                <button type="button" onclick="window.print()" class="px-3 py-1.5 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-700 flex items-center gap-1.5 hover:bg-gray-50 shadow-sm">
                    <i class="fa-solid fa-download text-gray-400"></i> Export
                </button>
            </div>
        </form>

        <!-- Clean Table View with STICKY Action Column & Working Checkboxes -->
        <div class="overflow-x-auto min-h-[300px] pb-28 relative">
            
            <!-- Floating Bulk Selection Bar -->
            <div x-show="selectedIds.length > 0" x-transition class="sticky top-0 z-30 bg-[#1e1b4b] text-white px-4 py-2.5 flex items-center justify-between text-xs font-bold shadow-lg">
                <div class="flex items-center gap-3">
                    <span class="bg-[#4338ca] px-2.5 py-0.5 rounded-full text-[11px]" x-text="selectedIds.length + ' selected'"></span>
                    <span>Selected Orders Actions</span>
                </div>
                <div class="flex items-center gap-3">
                    <form action="{{ route('seller.shipments.bulk-cancel') }}" method="POST" onsubmit="return confirm('Cancel selected orders?');" class="inline">
                        @csrf
                        <input type="hidden" name="ids" :value="selectedIds.join(',')">
                        <button type="submit" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-bold transition">Bulk Cancel</button>
                    </form>
                    <button type="button" @click="selectedIds = []" class="text-gray-300 hover:text-white text-xs underline">Deselect All</button>
                </div>
            </div>

            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead>
                    <tr class="bg-[#f8fafc] text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                        <th class="px-3 py-3 w-8"><input type="checkbox" @change="toggleAll($event)" :checked="isAllSelected()" class="rounded border-gray-300 accent-[#4338ca] cursor-pointer"></th>
                        <th class="px-3 py-3">ORDER DETAILS</th>
                        <th class="px-3 py-3">SHIPPING ADDRESS</th>
                        <th class="px-3 py-3">PRODUCT DETAILS</th>
                        <th class="px-3 py-3">PACKAGE DETAILS</th>
                        <th class="px-3 py-3">ORDER VALUE</th>
                        <th class="px-3 py-3">PICKUP ADDRESS</th>
                        <!-- STICKY ACTION COLUMN HEADER -->
                        <th class="px-3 py-3 text-right sticky right-0 bg-[#f8fafc] z-20 shadow-[-3px_0_6px_rgba(0,0,0,0.04)]">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium text-gray-700">
                    @forelse($shipments as $shipment)
                        <tr class="group hover:bg-gray-50/80 transition-colors">
                            <td class="px-3 py-4 align-top">
                                <input type="checkbox" value="{{ $shipment->id }}" x-model="selectedIds" class="rounded border-gray-300 accent-[#4338ca] cursor-pointer">
                            </td>
                            
                            <!-- ORDER DETAILS -->
                            <td class="px-3 py-4 align-top">
                                <a href="#" class="font-bold text-[#4338ca] text-xs hover:underline block">{{ $shipment->awb_number }}</a>
                                <div class="text-[10px] text-gray-500 mt-1">Synced On · {{ $shipment->created_at->format('d Sep Y | h:i A') }}</div>
                                <div class="text-[10px] text-gray-500">Created On · {{ $shipment->created_at->format('d Sep Y | h:i A') }}</div>
                                <div class="text-[10px] text-gray-400 font-semibold flex items-center gap-1 mt-1.5">
                                    <i class="fa-solid fa-desktop text-[9px]"></i> Manual
                                </div>
                            </td>

                            <!-- SHIPPING ADDRESS -->
                            <td class="px-3 py-4 align-top max-w-[200px]">
                                <div class="font-bold text-gray-900 text-xs truncate">{{ $shipment->receiver_name }}</div>
                                <div class="text-[11px] text-gray-500 truncate mt-0.5">{{ $shipment->delivery_address }}</div>
                                <div class="text-[11px] text-gray-500 truncate">{{ $shipment->delivery_city }}, {{ $shipment->delivery_pincode }}</div>
                                <div class="text-[11px] text-gray-400 mt-1"><i class="fa-solid fa-phone text-[9px] mr-1"></i>{{ $shipment->receiver_phone }}</div>
                            </td>

                            <!-- PRODUCT DETAILS -->
                            <td class="px-3 py-4 align-top max-w-[180px]">
                                <div class="font-semibold text-gray-800 text-xs truncate">Package Item</div>
                                <div class="text-[10px] text-gray-400 mt-0.5">QTY: 1 · SKU: N/A</div>
                            </td>

                            <!-- PACKAGE DETAILS -->
                            <td class="px-3 py-4 align-top">
                                <div class="text-xs font-bold text-gray-800">{{ number_format($shipment->weight_kg, 2) }} kg</div>
                                <div class="text-[10px] text-gray-400 mt-0.5">0.00 cm x 0.00 cm x 0.00 cm</div>
                            </td>

                            <!-- ORDER VALUE -->
                            <td class="px-3 py-4 align-top">
                                <div class="font-bold text-gray-900 text-xs">&#8377;{{ number_format($shipment->invoice_value > 0 ? $shipment->invoice_value : $shipment->total_amount, 2) }}</div>
                                <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                                    <span class="text-[9px] font-bold px-2 py-0.5 rounded {{ $shipment->is_cod ? 'bg-amber-100 text-amber-800' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                        {{ $shipment->is_cod ? 'COD' : 'Prepaid' }}
                                    </span>
                                    @if(($shipment->invoice_value ?? 0) > 50000)
                                    <span class="text-[9px] font-bold px-2 py-0.5 rounded bg-rose-50 text-rose-600 border border-rose-200">Add E-Way Bill</span>
                                    @endif
                                </div>
                            </td>

                            <!-- PICKUP ADDRESS -->
                            <td class="px-3 py-4 align-top">
                                <div class="text-xs font-semibold text-gray-700 truncate max-w-[100px]">{{ Auth::user()->company_name ?? 'Main Hub' }}</div>
                            </td>

                            <!-- STICKY ACTION COLUMN CELL -->
                            <td class="px-3 py-4 align-top text-right sticky right-0 bg-white group-hover:bg-gray-50 z-10 shadow-[-3px_0_6px_rgba(0,0,0,0.04)]" x-data="{ openMenu: false }">
                                <div class="flex items-center justify-end gap-2">
                                    @if($isKycApproved)
                                        <a href="{{ route('seller.label', $shipment->awb_number) }}" target="_blank" class="px-3 py-1.5 bg-[#1e1b4b] hover:bg-black text-white text-xs font-bold rounded-lg shadow-sm transition">
                                            Ship Now
                                        </a>
                                    @else
                                        <a href="{{ route('seller.settings') }}?view=kyc" onclick="alert('KYC Verification Required!\n\nPlease complete and get your KYC approved before shipping orders.');" class="px-3 py-1.5 bg-gray-400 hover:bg-gray-600 text-white text-xs font-bold rounded-lg shadow-sm transition">
                                            Ship Now
                                        </a>
                                    @endif

                                    <button @click="openMenu = !openMenu" class="w-7 h-7 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 flex items-center justify-center text-gray-600 text-xs shadow-sm">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>

                                    <!-- Dropdown Menu Opens Downwards smoothly -->
                                    <div x-show="openMenu" @click.away="openMenu = false" class="absolute right-0 top-9 w-48 bg-white border border-gray-200 rounded-xl shadow-2xl py-2 z-50 text-left" style="display: none;">
                                        @if($isKycApproved)
                                            <a href="{{ route('seller.book') }}?edit={{ $shipment->id }}" class="block px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">Edit Order</a>
                                        @else
                                            <a href="{{ route('seller.settings') }}?view=kyc" onclick="alert('KYC Verification Required!\n\nPlease complete and get your KYC approved.');" class="block px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">Edit Order</a>
                                        @endif

                                        @if(in_array(strtolower($shipment->status), ['new', 'manifested', 'booked']))
                                        <form action="{{ route('seller.shipments.cancel', $shipment->id) }}" method="POST" onsubmit="return confirm('Cancel this order?');">
                                            @csrf
                                            <button type="submit" class="w-full text-left px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50">Cancel Order</button>
                                        </form>
                                        @endif
                                        <a href="{{ route('seller.book') }}?clone={{ $shipment->id }}" class="block px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">Clone Order</a>
                                        <a href="{{ route('seller.invoice', $shipment->awb_number) }}" target="_blank" class="block px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">Download Invoice</a>
                                        <a href="#" @click.prevent="openEway('{{ $shipment->awb_number }}'); openMenu = false" class="block px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">Update E-way Bill</a>
                                        <a href="#" @click.prevent="openComm('{{ $shipment->awb_number }}'); openMenu = false" class="block px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">View Communication</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-20 text-center">
                                <div class="text-gray-300 mb-2">
                                    <i class="fa-solid fa-box-archive text-4xl"></i>
                                </div>
                                <p class="text-sm font-bold text-gray-400">No orders found matching the filter</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        @if($shipments->hasPages())
        <div class="p-4 border-t border-gray-100 bg-[#f8fafc]">
            {{ $shipments->links() }}
        </div>
        @endif
    </div>

    <!-- Update E-Way Bill Modal -->
    <div x-show="showEwayModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showEwayModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" @click="showEwayModal = false"></div>
            
            <div x-show="showEwayModal" x-transition class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
                    <h3 class="text-lg font-extrabold text-gray-900">Update E-Way Bill</h3>
                    <button @click="showEwayModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                <div class="mb-4">
                    <p class="text-xs text-gray-500 mb-3">Updating E-Way Bill for AWB: <span class="font-bold text-[#4338ca]" x-text="currentAwb"></span></p>
                    <label class="block text-xs font-bold text-gray-700 mb-1">E-Way Bill Number <span class="text-red-500">*</span></label>
                    <input type="text" x-model="ewayNumber" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca]" placeholder="e.g., 123456789012">
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button @click="showEwayModal = false" class="px-4 py-2 text-xs font-bold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition">Cancel</button>
                    <button @click="submitEway()" class="px-4 py-2 text-xs font-bold text-white bg-[#4338ca] rounded-lg hover:bg-indigo-700 transition">Save E-Way Bill</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Communication Modal -->
    <div x-show="showCommModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showCommModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" @click="showCommModal = false"></div>
            
            <div x-show="showCommModal" x-transition class="relative inline-block w-full max-w-lg p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
                    <h3 class="text-lg font-extrabold text-gray-900">Communication Logs</h3>
                    <button @click="showCommModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                <div class="mb-4">
                    <p class="text-xs text-gray-500 mb-6">Tracking updates sent for AWB: <span class="font-bold text-[#4338ca]" x-text="currentAwb"></span></p>
                    
                    <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:ml-[2.5rem] md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-200 before:to-transparent">
                        <div class="relative flex items-center justify-between md:justify-normal">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-green-100 text-green-500 shadow-sm shrink-0 relative z-10 md:ml-5">
                                <i class="fa-brands fa-whatsapp text-lg"></i>
                            </div>
                            <div class="w-[calc(100%-4rem)] md:w-[calc(100%-5rem)] ml-4 p-4 rounded-xl border border-gray-100 bg-white shadow-sm">
                                <div class="flex items-center justify-between space-x-2 mb-1">
                                    <div class="font-bold text-gray-900 text-xs">WhatsApp Sent</div>
                                    <time class="font-medium text-[10px] text-gray-500">Today, 10:30 AM</time>
                                </div>
                                <div class="text-[11px] text-gray-600 mt-2">Your order has been shipped and is on the way. Tracking Link: ...</div>
                            </div>
                        </div>
                        
                        <div class="relative flex items-center justify-between md:justify-normal">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-blue-100 text-blue-500 shadow-sm shrink-0 relative z-10 md:ml-5">
                                <i class="fa-solid fa-envelope text-sm"></i>
                            </div>
                            <div class="w-[calc(100%-4rem)] md:w-[calc(100%-5rem)] ml-4 p-4 rounded-xl border border-gray-100 bg-white shadow-sm">
                                <div class="flex items-center justify-between space-x-2 mb-1">
                                    <div class="font-bold text-gray-900 text-xs">Email Sent</div>
                                    <time class="font-medium text-[10px] text-gray-500">Yesterday, 5:45 PM</time>
                                </div>
                                <div class="text-[11px] text-gray-600 mt-2">Order confirmed. We are packing your items...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('shipmentTable', () => ({
            selectedIds: [],
            allRowIds: [{{ $shipments->pluck('id')->implode(',') }}],
            showEwayModal: false,
            showCommModal: false,
            currentAwb: '',
            ewayNumber: '',
            
            toggleAll(e) {
                if (e.target.checked) {
                    this.selectedIds = [...this.allRowIds];
                } else {
                    this.selectedIds = [];
                }
            },
            
            isAllSelected() {
                return this.allRowIds.length > 0 && this.selectedIds.length === this.allRowIds.length;
            },

            openEway(awb) {
                this.currentAwb = awb;
                this.ewayNumber = '';
                this.showEwayModal = true;
            },
            
            submitEway() {
                if(!this.ewayNumber) {
                    alert('Please enter an E-way bill number');
                    return;
                }
                alert('E-Way Bill ' + this.ewayNumber + ' successfully updated for ' + this.currentAwb);
                this.showEwayModal = false;
            },
            
            openComm(awb) {
                this.currentAwb = awb;
                this.showCommModal = true;
            }
        }));
    });
</script>
@endsection
EOD;

file_put_contents($file, $code);
echo "Shipments view updated successfully";
?>
