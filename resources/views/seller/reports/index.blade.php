@extends('layouts.seller')
@section('title', 'Monthly Reports & GST Bills - OneStall Cargo')

@section('content')
<div class="space-y-6 max-w-[1400px]">
    
    <!-- Top Header & Controls -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-bold mb-2">
                <i class="fa-solid fa-file-invoice"></i> Financial & Logistics Report
            </div>
            <h1 class="text-2xl lg:text-3xl font-extrabold text-gray-900 tracking-tight">Seller Monthly Report & GST Billing</h1>
            <p class="text-sm text-gray-500 mt-1">Consolidated monthly billing statement, GST tax invoice, COD remittances and consignment analytics.</p>
        </div>

        <!-- Month Selector & Download Actions -->
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <form method="GET" action="{{ route('seller.reports.index') }}" class="flex items-center gap-2">
                <label for="monthSelect" class="text-xs font-bold uppercase tracking-wider text-gray-400">Month:</label>
                <select id="monthSelect" name="month" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm font-bold rounded-xl px-4 py-2.5 outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] cursor-pointer">
                    @foreach($availableMonths as $m)
                        <option value="{{ $m['value'] }}" {{ $m['value'] === $month ? 'selected' : '' }}>
                            {{ $m['label'] }}
                        </option>
                    @endforeach
                </select>
            </form>

            <a href="{{ route('seller.reports.gst-invoice', ['month' => $month]) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#4338ca] hover:bg-[#3730a3] text-white text-xs font-bold rounded-xl shadow-sm transition">
                <i class="fa-solid fa-file-invoice-dollar"></i> Download GST Bill
            </a>

            <a href="{{ route('seller.reports.monthly-csv', ['month' => $month]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                <i class="fa-solid fa-file-excel"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- Monthly Financial KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Total Freight Card -->
        <div class="bg-gradient-to-br from-[#0f172a] to-[#1e1b4b] text-white rounded-2xl p-5 shadow-sm relative overflow-hidden">
            <div class="text-[11px] font-extrabold text-indigo-300 uppercase tracking-widest flex items-center justify-between">
                <span>Total Freight Spend</span>
                <i class="fa-solid fa-receipt text-indigo-400 text-base"></i>
            </div>
            <div class="text-3xl font-black mt-3">₹{{ number_format($stats['total_freight'], 2) }}</div>
            <div class="text-[11px] text-gray-300 mt-2 flex items-center justify-between pt-2 border-t border-white/10">
                <span>Incl. 18% GST</span>
                <span class="font-bold text-emerald-400">{{ $stats['total_shipments'] }} Orders</span>
            </div>
        </div>

        <!-- Taxable & GST Card -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm">
            <div class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest flex items-center justify-between">
                <span>GST Tax Breakdown</span>
                <i class="fa-solid fa-landmark text-[#4338ca] text-base"></i>
            </div>
            <div class="flex items-baseline justify-between mt-3">
                <div>
                    <div class="text-xs text-gray-500 font-medium">Taxable (SAC 996511)</div>
                    <div class="text-lg font-black text-gray-900">₹{{ number_format($stats['taxable_freight'], 2) }}</div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-indigo-600 font-bold">GST (18%)</div>
                    <div class="text-lg font-black text-indigo-700">₹{{ number_format($stats['gst_amount'], 2) }}</div>
                </div>
            </div>
            <div class="text-[11px] text-gray-500 mt-3 pt-2 border-t border-gray-100">
                Eligible for Input Tax Credit (ITC)
            </div>
        </div>

        <!-- COD Collection & Remittance Card -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm">
            <div class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest flex items-center justify-between">
                <span>COD Remittance</span>
                <i class="fa-solid fa-money-bill-transfer text-emerald-600 text-base"></i>
            </div>
            <div class="text-2xl font-black text-emerald-700 mt-3">₹{{ number_format($stats['cod_remitted'], 2) }}</div>
            <div class="text-[11px] text-gray-500 mt-2 flex items-center justify-between pt-2 border-t border-gray-100">
                <span>Collected: ₹{{ number_format($stats['cod_value'], 2) }}</span>
                <span class="text-amber-700 font-bold">Pending: ₹{{ number_format($stats['cod_pending'], 2) }}</span>
            </div>
        </div>

        <!-- Operational Health Card -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm">
            <div class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest flex items-center justify-between">
                <span>Logistics Delivery Split</span>
                <i class="fa-solid fa-chart-pie text-blue-500 text-base"></i>
            </div>
            <div class="grid grid-cols-3 gap-2 text-center mt-3">
                <div class="p-2 bg-green-50 rounded-xl">
                    <div class="text-base font-black text-green-700">{{ $stats['delivered_count'] }}</div>
                    <div class="text-[9px] font-extrabold text-green-600 uppercase">Delivered</div>
                </div>
                <div class="p-2 bg-blue-50 rounded-xl">
                    <div class="text-base font-black text-blue-700">{{ $stats['in_transit_count'] }}</div>
                    <div class="text-[9px] font-extrabold text-blue-600 uppercase">Transit</div>
                </div>
                <div class="p-2 bg-red-50 rounded-xl">
                    <div class="text-base font-black text-red-700">{{ $stats['ndr_count'] + $stats['rto_count'] }}</div>
                    <div class="text-[9px] font-extrabold text-red-600 uppercase">NDR/RTO</div>
                </div>
            </div>
            <div class="text-[11px] text-gray-500 mt-2 text-right">
                Total Weight: <span class="font-bold text-gray-800">{{ number_format($stats['total_weight_kg'], 2) }} KG</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow-sm flex flex-col md:flex-row justify-between items-center gap-3">
        <form method="GET" action="{{ route('seller.reports.index') }}" class="flex flex-wrap items-center gap-2.5 w-full md:w-auto">
            <input type="hidden" name="month" value="{{ $month }}">
            
            <div class="relative flex-1 sm:w-64">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search AWB, Order ID, Receiver..." class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 text-gray-800 text-xs font-semibold rounded-xl outline-none focus:border-[#4338ca]">
            </div>

            <select name="status" class="bg-gray-50 border border-gray-200 text-gray-700 text-xs font-semibold rounded-xl px-3 py-2 outline-none focus:border-[#4338ca]">
                <option value="">All Statuses</option>
                <option value="Delivered" {{ request('status') === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="Out for Delivery" {{ request('status') === 'Out for Delivery' ? 'selected' : '' }}>Out for Delivery</option>
                <option value="In Transit" {{ request('status') === 'In Transit' ? 'selected' : '' }}>In Transit</option>
                <option value="NDR" {{ request('status') === 'NDR' ? 'selected' : '' }}>NDR (Undelivered)</option>
                <option value="RTO Initiated" {{ request('status') === 'RTO Initiated' ? 'selected' : '' }}>RTO Initiated</option>
                <option value="RTO Delivered" {{ request('status') === 'RTO Delivered' ? 'selected' : '' }}>RTO Delivered</option>
            </select>

            <select name="payment_mode" class="bg-gray-50 border border-gray-200 text-gray-700 text-xs font-semibold rounded-xl px-3 py-2 outline-none focus:border-[#4338ca]">
                <option value="">All Payment Modes</option>
                <option value="Prepaid" {{ request('payment_mode') === 'Prepaid' ? 'selected' : '' }}>Prepaid</option>
                <option value="COD" {{ request('payment_mode') === 'COD' ? 'selected' : '' }}>Cash on Delivery (COD)</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-gray-900 hover:bg-black text-white text-xs font-bold rounded-xl transition">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status', 'payment_mode']))
                <a href="{{ route('seller.reports.index', ['month' => $month]) }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold rounded-xl transition">
                    Clear
                </a>
            @endif
        </form>

        <div class="text-xs font-bold text-gray-500">
            Billing Period: <span class="text-gray-900">{{ $startOfMonth->format('d M') }} - {{ $endOfMonth->format('d M Y') }}</span>
        </div>
    </div>

    <!-- Consignments Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="font-extrabold text-gray-900 text-sm flex items-center gap-2">
                <i class="fa-solid fa-boxes-stacked text-[#4338ca]"></i>
                Shipments Billed in {{ $startOfMonth->format('F Y') }}
            </h3>
            <span class="text-xs font-bold text-gray-500 bg-white border border-gray-200 px-3 py-1 rounded-full">
                {{ $shipments->total() }} Consignments
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-400 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3.5">Booking Date</th>
                        <th class="px-5 py-3.5">AWB / Order ID</th>
                        <th class="px-5 py-3.5">Destination</th>
                        <th class="px-5 py-3.5">Weight</th>
                        <th class="px-5 py-3.5">Payment</th>
                        <th class="px-5 py-3.5">Taxable Freight</th>
                        <th class="px-5 py-3.5">GST (18%)</th>
                        <th class="px-5 py-3.5">Total Billed</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($shipments as $s)
                        @php
                            $freight = (float) $s->shipping_charge;
                            $taxable = round($freight / 1.18, 2);
                            $gst = round($freight - $taxable, 2);
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td class="px-5 py-3.5 text-gray-500 font-medium">
                                {{ $s->created_at ? $s->created_at->format('d M Y, h:i A') : 'N/A' }}
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="font-black text-indigo-700 tracking-tight">{{ $s->awb_number }}</div>
                                <div class="text-[10px] text-gray-400">Order: {{ $s->order_id ?? 'N/A' }}</div>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="font-bold text-gray-900">{{ $s->receiver_name }}</div>
                                <div class="text-[10px] text-gray-500">{{ $s->delivery_city }}, {{ $s->delivery_pincode }}</div>
                            </td>
                            <td class="px-5 py-3.5 font-bold text-gray-800">
                                {{ $s->weight_kg }} KG
                            </td>
                            <td class="px-5 py-3.5">
                                @if($s->is_cod)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-50 text-amber-700 rounded-md text-[10px] font-bold">
                                        <i class="fa-solid fa-coins"></i> COD: ₹{{ number_format($s->cod_amount > 0 ? $s->cod_amount : $s->invoice_value, 2) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-50 text-blue-700 rounded-md text-[10px] font-bold">
                                        <i class="fa-solid fa-check"></i> Prepaid
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-gray-700">
                                ₹{{ number_format($taxable, 2) }}
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-indigo-600">
                                ₹{{ number_format($gst, 2) }}
                            </td>
                            <td class="px-5 py-3.5 font-black text-gray-900">
                                ₹{{ number_format($freight, 2) }}
                            </td>
                            <td class="px-5 py-3.5">
                                @php
                                    $badgeClasses = match($s->status) {
                                        'Delivered' => 'bg-green-100 text-green-800 border-green-200',
                                        'Out for Delivery' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                        'In Transit' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'NDR' => 'bg-amber-100 text-amber-800 border-amber-200',
                                        'RTO Initiated', 'RTO Delivered' => 'bg-red-100 text-red-800 border-red-200',
                                        default => 'bg-gray-100 text-gray-800 border-gray-200',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $badgeClasses }}">
                                    {{ $s->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('seller.label', $s->awb_number) }}" target="_blank" class="text-gray-500 hover:text-[#4338ca] text-xs font-bold mr-2" title="Print Shipping Label">
                                    <i class="fa-solid fa-barcode"></i>
                                </a>
                                <a href="{{ route('seller.lr', $s->awb_number) }}" target="_blank" class="text-gray-500 hover:text-emerald-600 text-xs font-bold" title="View LR">
                                    <i class="fa-solid fa-file-invoice"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                <p class="text-sm font-bold text-gray-600">No shipments found for {{ $startOfMonth->format('F Y') }}.</p>
                                <p class="text-xs text-gray-400 mt-1">Select a different month or clear active search filters.</p>
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
