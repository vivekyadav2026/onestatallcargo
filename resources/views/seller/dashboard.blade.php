@extends('layouts.seller')
@section('title', 'Seller Dashboard - OneStall Cargo')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Merchant Overview</h1>
            <p class="text-sm text-gray-500 mt-1">Welcome back, {{ $user->company_name ?? $user->name }}.</p>
        </div>
        
        <!-- Wallet Recharge Form -->
        <form action="{{ route('seller.wallet.recharge') }}" method="POST" class="flex items-center">
            @csrf
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-bold">₹</span>
                <input type="number" name="amount" min="100" value="1000" class="w-32 pl-7 pr-4 py-2 border-y border-l border-gray-200 rounded-l-xl text-sm outline-none bg-white font-bold text-gray-700 focus:border-green-500">
            </div>
            <button type="submit" class="px-5 py-2 rounded-r-xl text-sm font-bold bg-green-500 text-white shadow-md hover:bg-green-600 border border-green-500 transition-colors">
                <i class="fa-solid fa-plus mr-1"></i> Recharge
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 border border-green-200 text-green-700 font-bold rounded-xl"><i class="fa-solid fa-check mr-2"></i> {{ session('success') }}</div>
    @endif

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Total Orders</p>
                <h3 class="text-3xl font-black text-gray-900">{{ $totalOrders }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 text-xl"><i class="fa-solid fa-box"></i></div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Wallet Balance</p>
                <h3 class="text-3xl font-black text-gray-900">₹{{ number_format($user->wallet_balance, 2) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-500 text-xl"><i class="fa-solid fa-wallet"></i></div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Delivered</p>
                <h3 class="text-3xl font-black text-gray-900">{{ $deliveredOrders }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center text-purple-500 text-xl"><i class="fa-solid fa-check-double"></i></div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Pending COD</p>
                <h3 class="text-3xl font-black text-gray-900">₹{{ number_format($codPending, 2) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-yellow-50 flex items-center justify-center text-yellow-600 text-xl"><i class="fa-solid fa-indian-rupee-sign"></i></div>
        </div>

    </div>

    <!-- Recent Shipments -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden mt-8">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h2 class="font-extrabold text-gray-900">Recent Shipments</h2>
            <a href="{{ route('seller.book') }}" class="text-xs font-bold text-blue-600 hover:underline">Book New &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="bg-white text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                        <th class="px-6 py-4">AWB Number</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Receiver</th>
                        <th class="px-6 py-4">Payment</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentShipments as $shipment)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-900">{{ $shipment->awb_number }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $shipment->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $shipment->receiver_name }}<br><span class="text-[10px]">{{ $shipment->delivery_city }}</span></td>
                        <td class="px-6 py-4">
                            @if($shipment->is_cod)
                                <span class="text-yellow-600 font-bold">COD (₹{{ $shipment->invoice_value }})</span>
                            @else
                                <span class="text-green-600 font-bold">Prepaid</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700">{{ $shipment->status }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('seller.label', $shipment->awb_number) }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-bold text-xs"><i class="fa-solid fa-print"></i> Label</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <i class="fa-solid fa-box-open text-4xl mb-3"></i>
                            <p class="font-bold">No shipments found. Start booking!</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
