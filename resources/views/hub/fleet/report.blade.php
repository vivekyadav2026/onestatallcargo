@extends('layouts.hub')

@section('content')
<div class="space-y-6" x-data="{ selectedDate: '{{ $selectedDate }}' }">
    
    <div class="flex justify-between items-center bg-white p-4 rounded-2xl shadow-sm border border-gray-200">
        <div class="flex items-center gap-4">
            <a href="{{ route('hub.fleet.index') }}" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200"><i class="fa-solid fa-arrow-left"></i></a>
            <div>
                <h1 class="text-xl font-extrabold text-gray-900">{{ $rider->user->name }}'s Daily Report</h1>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">{{ $rider->vehicle_type ?? 'Rider' }} | {{ $rider->vehicle_number ?? 'No Vehicle' }}</p>
            </div>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 text-right">Select Date</label>
            <input type="date" x-model="selectedDate" @change="window.location.href='?date=' + selectedDate" class="bg-gray-50 border border-gray-200 text-gray-700 text-sm font-bold rounded-lg px-4 py-2 shadow-sm outline-none focus:border-[var(--gold)]">
        </div>
    </div>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-6 shadow-sm flex flex-col justify-center items-center text-center">
            <i class="fa-solid fa-box-check text-3xl text-indigo-400 mb-2"></i>
            <h3 class="text-3xl font-black text-indigo-900">{{ $totalDeliveries }}</h3>
            <p class="text-[10px] font-extrabold text-indigo-600 uppercase tracking-widest mt-1">Total Deliveries</p>
        </div>
        
        <div class="bg-green-50 border border-green-100 rounded-2xl p-6 shadow-sm flex flex-col justify-center items-center text-center relative overflow-hidden">
            <i class="fa-solid fa-money-bill-wave text-3xl text-green-400 mb-2"></i>
            <h3 class="text-3xl font-black text-green-900">&#8377;{{ number_format($totalCash, 2) }}</h3>
            <p class="text-[10px] font-extrabold text-green-600 uppercase tracking-widest mt-1">Hard Cash Collected</p>
            <div class="absolute top-0 right-0 bg-green-200 text-green-800 text-[9px] font-bold px-2 py-1 rounded-bl-lg uppercase">Requires Hub Deposit</div>
        </div>

        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 shadow-sm flex flex-col justify-center items-center text-center relative overflow-hidden">
            <i class="fa-solid fa-qrcode text-3xl text-blue-400 mb-2"></i>
            <h3 class="text-3xl font-black text-blue-900">&#8377;{{ number_format($totalUpi, 2) }}</h3>
            <p class="text-[10px] font-extrabold text-blue-600 uppercase tracking-widest mt-1">UPI (Cashfree) Collected</p>
            <div class="absolute top-0 right-0 bg-blue-200 text-blue-800 text-[9px] font-bold px-2 py-1 rounded-bl-lg uppercase">Auto-Settled to Bank</div>
        </div>
    </div>

    <!-- Detailed Shipment List -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-extrabold text-gray-900"><i class="fa-solid fa-list-check mr-2 text-[var(--gold)]"></i> Delivered Parcels Log</h3>
            <span class="bg-gray-200 text-gray-700 text-xs font-bold px-3 py-1 rounded-full">{{ $shipments->count() }} Records</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="bg-white text-[10px] font-extrabold uppercase tracking-wider text-gray-400 border-b border-gray-100">
                        <th class="px-6 py-4">Time</th>
                        <th class="px-6 py-4">AWB Number</th>
                        <th class="px-6 py-4">Customer Details</th>
                        <th class="px-6 py-4">COD Amount</th>
                        <th class="px-6 py-4">Payment Method</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700">
                    @forelse($shipments as $shipment)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-xs font-bold text-gray-500">
                            {{ \Carbon\Carbon::parse($shipment->updated_at)->format('h:i A') }}
                        </td>
                        <td class="px-6 py-4 font-black text-indigo-600">
                            {{ $shipment->awb_number }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $shipment->receiver_name }}</div>
                            <div class="text-[10px] text-gray-500"><i class="fa-solid fa-location-dot mr-1"></i> {{ $shipment->delivery_city }}, {{ $shipment->delivery_state }}</div>
                        </td>
                        <td class="px-6 py-4 font-black text-gray-900">
                            @if($shipment->is_cod)
                                &#8377;{{ number_format($shipment->cod_amount > 0 ? $shipment->cod_amount : $shipment->invoice_value, 2) }}
                            @else
                                <span class="text-gray-400 font-bold text-[10px] uppercase tracking-widest">Prepaid</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($shipment->is_cod)
                                @if($shipment->payment_type === 'UPI')
                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-xs font-bold"><i class="fa-solid fa-qrcode mr-1"></i> UPI (Online)</span>
                                @else
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-lg text-xs font-bold"><i class="fa-solid fa-money-bill-wave mr-1"></i> Hard Cash</span>
                                @endif
                            @else
                                <span class="bg-gray-100 text-gray-500 px-3 py-1 rounded-lg text-xs font-bold">N/A</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <i class="fa-solid fa-box-open text-4xl mb-3 text-gray-200"></i>
                            <p class="text-sm font-bold">No deliveries found for this date.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
