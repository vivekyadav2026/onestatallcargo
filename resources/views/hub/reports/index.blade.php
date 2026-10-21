@extends('layouts.hub')

@section('title', 'Reports & Analytics - OneStall Cargo')

@section('content')
<div class="space-y-6">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-50 text-amber-800 rounded-full text-xs font-bold mb-2">
                <i class="fa-solid fa-chart-line"></i> Territory Logistics Analytics
            </div>
            <h1 class="text-2xl lg:text-3xl font-extrabold text-gray-900 tracking-tight">Hub Reports & Analytics</h1>
            <p class="text-sm text-gray-500 mt-1">Audit consignment flow, track deliveries, monitor COD collection, and export raw data.</p>
        </div>
        
        <form action="{{ route('hub.reports.export') }}" method="GET" class="flex gap-2">
            @if(request('date_from')) <input type="hidden" name="date_from" value="{{ request('date_from') }}"> @endif
            @if(request('date_to')) <input type="hidden" name="date_to" value="{{ request('date_to') }}"> @endif
            @if(request('quick_date')) <input type="hidden" name="quick_date" value="{{ request('quick_date') }}"> @endif
            @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
            @if(request('flow')) <input type="hidden" name="flow" value="{{ request('flow') }}"> @endif
            @if(request('payment_mode')) <input type="hidden" name="payment_mode" value="{{ request('payment_mode') }}"> @endif
            @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
            
            <button type="submit" class="px-5 py-2.5 bg-gray-900 text-white font-bold rounded-xl shadow-sm hover:bg-black transition text-xs flex items-center gap-2">
                <i class="fa-solid fa-file-csv text-sm"></i> Export Data (CSV)
            </button>
        </form>
    </div>

    <!-- Filter Form with Quick Date Presets -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm space-y-4">
        
        <!-- Quick Date Preset Pills -->
        <div class="flex flex-wrap items-center gap-2 pb-3 border-b border-gray-100 text-xs">
            <span class="font-bold text-gray-400 uppercase text-[10px] tracking-wider mr-1">Quick Dates:</span>
            
            @php
                $activePreset = request('quick_date');
            @endphp

            <a href="{{ route('hub.reports.index', array_merge(request()->except(['date_from', 'date_to', 'quick_date', 'page']), ['quick_date' => 'today'])) }}" class="px-3 py-1.5 rounded-lg font-bold transition {{ $activePreset === 'today' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Today
            </a>
            <a href="{{ route('hub.reports.index', array_merge(request()->except(['date_from', 'date_to', 'quick_date', 'page']), ['quick_date' => 'yesterday'])) }}" class="px-3 py-1.5 rounded-lg font-bold transition {{ $activePreset === 'yesterday' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Yesterday
            </a>
            <a href="{{ route('hub.reports.index', array_merge(request()->except(['date_from', 'date_to', 'quick_date', 'page']), ['quick_date' => '7days'])) }}" class="px-3 py-1.5 rounded-lg font-bold transition {{ $activePreset === '7days' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Last 7 Days
            </a>
            <a href="{{ route('hub.reports.index', array_merge(request()->except(['date_from', 'date_to', 'quick_date', 'page']), ['quick_date' => '30days'])) }}" class="px-3 py-1.5 rounded-lg font-bold transition {{ $activePreset === '30days' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Last 30 Days
            </a>
            <a href="{{ route('hub.reports.index', array_merge(request()->except(['date_from', 'date_to', 'quick_date', 'page']), ['quick_date' => 'this_month'])) }}" class="px-3 py-1.5 rounded-lg font-bold transition {{ $activePreset === 'this_month' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                This Month
            </a>
            @if(request()->hasAny(['quick_date', 'date_from', 'date_to', 'status', 'flow', 'payment_mode', 'search']))
                <a href="{{ route('hub.reports.index') }}" class="ml-auto text-rose-600 hover:underline font-bold text-xs">
                    <i class="fa-solid fa-rotate-left mr-1"></i> Reset All Filters
                </a>
            @endif
        </div>

        <form action="{{ route('hub.reports.index') }}" method="GET" class="flex flex-wrap items-end gap-3 text-xs">
            <div>
                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-1">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-3 py-2 border border-gray-200 bg-gray-50 text-gray-800 rounded-xl outline-none focus:border-[var(--gold)]">
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-1">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-3 py-2 border border-gray-200 bg-gray-50 text-gray-800 rounded-xl outline-none focus:border-[var(--gold)]">
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-1">Status</label>
                <select name="status" class="px-3 py-2 border border-gray-200 bg-gray-50 text-gray-800 rounded-xl outline-none focus:border-[var(--gold)]">
                    <option value="">All Statuses</option>
                    <option value="Delivered" {{ request('status') === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="Out for Delivery" {{ request('status') === 'Out for Delivery' ? 'selected' : '' }}>Out for Delivery</option>
                    <option value="In Transit" {{ request('status') === 'In Transit' ? 'selected' : '' }}>In Transit</option>
                    <option value="NDR" {{ request('status') === 'NDR' ? 'selected' : '' }}>NDR (Failed)</option>
                    <option value="RTO" {{ request('status') === 'RTO' ? 'selected' : '' }}>RTO (Returned)</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-1">Operation Flow</label>
                <select name="flow" class="px-3 py-2 border border-gray-200 bg-gray-50 text-gray-800 rounded-xl outline-none focus:border-[var(--gold)]">
                    <option value="">All Operations</option>
                    <option value="pickup" {{ request('flow') === 'pickup' ? 'selected' : '' }}>Origin Pickups</option>
                    <option value="delivery" {{ request('flow') === 'delivery' ? 'selected' : '' }}>Destination Deliveries</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-1">Payment Mode</label>
                <select name="payment_mode" class="px-3 py-2 border border-gray-200 bg-gray-50 text-gray-800 rounded-xl outline-none focus:border-[var(--gold)]">
                    <option value="">All Modes</option>
                    <option value="COD" {{ request('payment_mode') === 'COD' ? 'selected' : '' }}>Cash on Delivery (COD)</option>
                    <option value="Prepaid" {{ request('payment_mode') === 'Prepaid' ? 'selected' : '' }}>Prepaid</option>
                </select>
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-1">Search Keyword</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="AWB, Order ID, Receiver, City..." class="w-full px-3 py-2 border border-gray-200 bg-gray-50 text-gray-800 rounded-xl outline-none focus:border-[var(--gold)]">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2 bg-[var(--gold)] text-gray-900 font-bold rounded-xl hover:bg-yellow-500 transition shadow-xs">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Report Metrics Grid (5 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Card 1 -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm flex flex-col justify-between relative overflow-hidden">
            <div class="text-gray-400 font-extrabold text-[10px] uppercase tracking-widest flex justify-between items-center">
                <span>Total Footprint</span>
                <i class="fa-solid fa-boxes-stacked text-gray-400 text-sm"></i>
            </div>
            <div class="text-3xl font-black text-gray-900 mt-2">{{ number_format($totalBookings) }}</div>
            <div class="text-[11px] text-gray-400 font-medium mt-1">Total volume handled</div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm flex flex-col justify-between relative overflow-hidden">
            <div class="text-green-600 font-extrabold text-[10px] uppercase tracking-widest flex justify-between items-center">
                <span>Delivered</span>
                <i class="fa-solid fa-circle-check text-green-500 text-sm"></i>
            </div>
            <div class="text-3xl font-black text-green-700 mt-2">{{ number_format($delivered) }}</div>
            <div class="text-[11px] text-gray-400 font-medium mt-1">Successfully fulfilled</div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm flex flex-col justify-between relative overflow-hidden">
            <div class="text-blue-600 font-extrabold text-[10px] uppercase tracking-widest flex justify-between items-center">
                <span>In Transit / OFD</span>
                <i class="fa-solid fa-truck-fast text-blue-500 text-sm"></i>
            </div>
            <div class="text-3xl font-black text-blue-700 mt-2">{{ number_format($inTransit) }}</div>
            <div class="text-[11px] text-gray-400 font-medium mt-1">Active on the road</div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm flex flex-col justify-between relative overflow-hidden">
            <div class="text-rose-600 font-extrabold text-[10px] uppercase tracking-widest flex justify-between items-center">
                <span>NDR / RTO</span>
                <i class="fa-solid fa-triangle-exclamation text-rose-500 text-sm"></i>
            </div>
            <div class="text-3xl font-black text-rose-700 mt-2">{{ number_format($rto + $ndr) }}</div>
            <div class="text-[11px] text-gray-400 font-medium mt-1">Failed attempts & returns</div>
        </div>

        <!-- Card 5 -->
        <div class="bg-gray-900 rounded-2xl p-5 border border-gray-800 shadow-sm flex flex-col justify-between relative overflow-hidden text-white">
            <div class="text-gray-300 font-extrabold text-[10px] uppercase tracking-widest flex justify-between items-center">
                <span>Pending COD</span>
                <i class="fa-solid fa-sack-dollar text-[var(--gold)] text-sm"></i>
            </div>
            <div class="text-2xl font-black text-[var(--gold)] mt-2">₹{{ number_format($pendingCod, 2) }}</div>
            <div class="text-[11px] text-gray-400 font-medium mt-1">Awaiting hub remittance</div>
        </div>
    </div>

    <!-- Pickups vs Deliveries Operational Balance -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <h3 class="font-extrabold text-gray-900 text-sm mb-4 flex items-center justify-between border-b border-gray-100 pb-3">
            <span><i class="fa-solid fa-scale-balanced text-[var(--gold)] mr-2"></i> Hub Operations Balance (Origin Pickups vs Destination Deliveries)</span>
            <span class="text-xs font-bold text-gray-500">Pickups: {{ number_format($myPickups) }} | Deliveries: {{ number_format($myDeliveries) }}</span>
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="flex justify-between items-end mb-1 text-xs">
                    <span class="font-bold text-gray-700"><i class="fa-solid fa-arrow-up-from-bracket text-blue-500 mr-1"></i> Origin Pickups (Booked in your territory)</span>
                    <span class="font-black text-gray-900">{{ number_format($myPickups) }}</span>
                </div>
                @php $pickupPct = ($totalBookings > 0) ? ($myPickups / $totalBookings) * 100 : 0; @endphp
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-blue-500 h-2.5 rounded-full transition-all" style="width: {{ $pickupPct }}%"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between items-end mb-1 text-xs">
                    <span class="font-bold text-gray-700"><i class="fa-solid fa-arrow-down-to-bracket text-emerald-500 mr-1"></i> Destination Deliveries (Arrived for last-mile)</span>
                    <span class="font-black text-gray-900">{{ number_format($myDeliveries) }}</span>
                </div>
                @php $deliveryPct = ($totalBookings > 0) ? ($myDeliveries / $totalBookings) * 100 : 0; @endphp
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-emerald-500 h-2.5 rounded-full transition-all" style="width: {{ $deliveryPct }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Shipments Log Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="font-extrabold text-gray-900 text-sm flex items-center gap-2">
                <i class="fa-solid fa-list-check text-indigo-600"></i>
                Consignments Log
            </h3>
            <span class="text-xs font-bold text-gray-600 bg-white border border-gray-200 px-3 py-1 rounded-full shadow-2xs">
                Showing {{ $shipments->firstItem() ?? 0 }} - {{ $shipments->lastItem() ?? 0 }} of {{ $shipments->total() }} Records
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-400 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3.5">Booking Date</th>
                        <th class="px-5 py-3.5">AWB / Order ID</th>
                        <th class="px-5 py-3.5">Sender Store</th>
                        <th class="px-5 py-3.5">Receiver Details</th>
                        <th class="px-5 py-3.5">Weight</th>
                        <th class="px-5 py-3.5">Payment</th>
                        <th class="px-5 py-3.5">COD Amount</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($shipments as $s)
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-5 py-3.5 text-gray-500 font-medium">
                            {{ $s->created_at ? $s->created_at->format('d M Y, h:i A') : 'N/A' }}
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="font-black text-indigo-600">{{ $s->awb_number }}</span>
                            <div class="text-[10px] text-gray-400">Order: {{ $s->order_id ?? 'N/A' }}</div>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-gray-900">{{ optional($s->user)->company_name ?: optional($s->user)->name ?: 'Client Store' }}</div>
                            <div class="text-[10px] text-gray-400">{{ optional($s->user)->phone }}</div>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-gray-900">{{ $s->receiver_name }}</div>
                            <div class="text-[10px] text-gray-500">{{ $s->delivery_city }} ({{ $s->delivery_pincode }})</div>
                        </td>
                        <td class="px-5 py-3.5 font-bold text-gray-800">
                            {{ $s->weight_kg }} KG
                        </td>
                        <td class="px-5 py-3.5">
                            @if($s->is_cod || $s->payment_type === 'COD')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                    COD
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-200">
                                    Prepaid
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 font-black text-gray-900">
                            @if($s->is_cod || $s->payment_type === 'COD')
                                &#8377;{{ number_format($s->cod_amount > 0 ? $s->cod_amount : $s->invoice_value, 2) }}
                            @else
                                <span class="text-gray-400">&#8377;0.00</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            @php
                                $badgeClass = match($s->status) {
                                    'Delivered' => 'bg-green-100 text-green-800 border-green-200',
                                    'Out for Delivery' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                    'In Transit' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'NDR' => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'RTO', 'RTO Initiated', 'RTO Delivered' => 'bg-rose-100 text-rose-800 border-rose-200',
                                    default => 'bg-gray-100 text-gray-800 border-gray-200',
                                };
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $badgeClass }}">
                                {{ $s->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right flex justify-end gap-2 items-center">
                            <a href="{{ route('hub.slip', $s->awb_number) }}" target="_blank" class="text-indigo-600 font-bold hover:underline text-[11px] bg-indigo-50 px-2.5 py-1 rounded-lg" title="Print Slip">
                                <i class="fa-solid fa-receipt mr-1"></i>Slip
                            </a>
                            <a href="{{ route('hub.label', $s->awb_number) }}" target="_blank" class="text-gray-700 font-bold hover:underline text-[11px] bg-gray-100 px-2.5 py-1 rounded-lg" title="Print Label">
                                <i class="fa-solid fa-barcode mr-1"></i>Label
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-400">
                            <i class="fa-solid fa-folder-open text-4xl mb-3 text-gray-300"></i>
                            <p class="text-sm font-bold text-gray-600">No shipments found matching the selected report criteria.</p>
                            <p class="text-xs text-gray-400 mt-1">Try expanding the date range or clearing filters.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($shipments->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50">
                {{ $shipments->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
