@extends('layouts.hub')

@section('title', 'Reports & Analytics - OneStall Cargo')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Reports & Analytics</h1>
            <p class="text-sm text-gray-500 mt-1">Export data and analyze your Hub's performance metrics</p>
        </div>
        
        <form action="{{ route('hub.reports.export') }}" method="GET" class="flex gap-2">
            @if(request('date_from')) <input type="hidden" name="date_from" value="{{ request('date_from') }}"> @endif
            @if(request('date_to')) <input type="hidden" name="date_to" value="{{ request('date_to') }}"> @endif
            
            <button type="submit" class="px-5 py-2.5 bg-gray-900 text-white font-bold rounded-xl shadow-sm hover:bg-black transition text-sm">
                <i class="fa-solid fa-file-csv mr-1"></i> Export Data (CSV)
            </button>
        </form>
    </div>

    <!-- Filter Form -->
    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
        <form action="{{ route('hub.reports.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Date From</label>
                <input type="date" class="w-full sm:w-auto  name="date_from" value="{{ request('date_from') }}" class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:border-[var(--gold)]">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Date To</label>
                <input type="date" class="w-full sm:w-auto  name="date_to" value="{{ request('date_to') }}" class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:border-[var(--gold)]">
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-[var(--gold)] text-gray-900 font-bold rounded-xl shadow-sm hover:bg-yellow-500 transition text-sm">
                    Generate
                </button>
                <a href="{{ route('hub.reports.index') }}" class="w-full sm:w-auto px-5 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-200 transition text-sm">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Report Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1 -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col justify-center relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-5 text-6xl"><i class="fa-solid fa-box"></i></div>
            <h3 class="text-gray-500 font-extrabold text-[10px] uppercase tracking-widest mb-2">Total Footprint</h3>
            <div class="text-3xl font-black text-gray-900">{{ number_format($totalBookings) }}</div>
            <div class="text-xs text-gray-400 font-medium mt-1">Total shipments processed</div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col justify-center relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-5 text-6xl"><i class="fa-solid fa-truck-fast"></i></div>
            <h3 class="text-green-600 font-extrabold text-[10px] uppercase tracking-widest mb-2">Successfully Delivered</h3>
            <div class="text-3xl font-black text-gray-900">{{ number_format($delivered) }}</div>
            <div class="text-xs text-gray-400 font-medium mt-1">Total delivered orders</div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col justify-center relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-5 text-6xl"><i class="fa-solid fa-rotate-left"></i></div>
            <h3 class="text-red-600 font-extrabold text-[10px] uppercase tracking-widest mb-2">RTO / NDR</h3>
            <div class="text-3xl font-black text-gray-900">{{ number_format($rto + $ndr) }}</div>
            <div class="text-xs text-gray-400 font-medium mt-1">Returned & Non-delivered</div>
        </div>

        <!-- Card 4 -->
        <div class="bg-gray-900 rounded-2xl p-6 border border-gray-800 shadow-sm flex flex-col justify-center relative overflow-hidden text-white">
            <div class="absolute top-0 right-0 p-4 opacity-10 text-6xl"><i class="fa-solid fa-money-bill-wave"></i></div>
            <h3 class="text-gray-400 font-extrabold text-[10px] uppercase tracking-widest mb-2">Pending COD Collected</h3>
            <div class="text-3xl font-black text-[var(--gold)]">₹{{ number_format($pendingCod, 2) }}</div>
            <div class="text-xs text-gray-400 font-medium mt-1">To be remitted to Admin</div>
        </div>
    </div>

    <!-- Pickups vs Deliveries Breakdown -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm">
            <h3 class="font-extrabold text-gray-800 mb-6 border-b border-gray-100 pb-3">Hub Operations Balance</h3>
            
            <div class="space-y-6">
                <div>
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-sm font-bold text-gray-600">Origin Pickups (Booked from your area)</span>
                        <span class="text-xl font-black text-gray-900">{{ number_format($myPickups) }}</span>
                    </div>
                    @php $pickupPct = ($totalBookings > 0) ? ($myPickups / $totalBookings) * 100 : 0; @endphp
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        <div class="bg-blue-500 h-3 rounded-full" style="width: {{ $pickupPct }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-sm font-bold text-gray-600">Destination Deliveries (Arrived to your area)</span>
                        <span class="text-xl font-black text-gray-900">{{ number_format($myDeliveries) }}</span>
                    </div>
                    @php $deliveryPct = ($totalBookings > 0) ? ($myDeliveries / $totalBookings) * 100 : 0; @endphp
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        <div class="bg-green-500 h-3 rounded-full" style="width: {{ $deliveryPct }}%"></div>
                    </div>
                </div>
            </div>
            <p class="text-[10px] text-gray-400 mt-6 leading-relaxed">
                * Note: The operations balance shows the ratio of packages originating from your Hub vs packages arriving to your Hub for delivery. This helps you balance your Rider workforce.
            </p>
        </div>

        <div class="bg-gray-50 p-6 rounded-3xl border border-gray-200 border-dashed flex flex-col justify-center items-center text-center">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-gray-300 text-2xl mb-4 shadow-sm">
                <i class="fa-solid fa-chart-pie"></i>
            </div>
            <h3 class="font-extrabold text-gray-800 mb-2">Detailed Reports</h3>
            <p class="text-sm text-gray-500 mb-6 max-w-xs">Download the complete dataset to analyze performance using Excel or your favorite BI tool.</p>
            <button onclick="document.forms[0].submit()" class="px-6 py-2.5 bg-gray-900 text-white font-bold rounded-xl shadow-md hover:bg-black transition text-sm">
                Export Full Report
            </button>
        </div>
    </div>
</div>
@endsection
