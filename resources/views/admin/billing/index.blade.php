@extends('layouts.admin')
@section('title', 'Billing & COD Ledgers - OneStall Cargo')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">COD Settlement Ledgers</h1>
            <p class="text-sm text-gray-500 mt-1">Reconcile and remit Cash-on-Delivery collections to Sellers.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-700 font-bold rounded-xl border border-green-200 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                    <th class="px-6 py-4">Seller Details</th>
                    <th class="px-6 py-4">Total Delivered COD Parcels</th>
                    <th class="px-6 py-4 text-right">Net Payable Amount</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($ledgers as $ledger)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900">{{ $ledger->user->name ?? 'Unknown Seller' }}</div>
                        <div class="text-[10px] text-gray-400">ID: {{ $ledger->user_id }}</div>
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-700">{{ $ledger->total_shipments }} Parcels</td>
                    <td class="px-6 py-4 text-right font-black text-lg text-gray-900">
                        ₹{{ number_format($ledger->total_cod, 2) }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('admin.billing.remit', $ledger->user_id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow transition">
                                Mark as Remitted
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                        <i class="fa-solid fa-file-invoice-dollar text-4xl mb-3 text-gray-300"></i>
                        <p class="font-bold text-gray-500">All remittances are up to date.</p>
                        <p class="text-xs mt-1">No pending COD settlements found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
