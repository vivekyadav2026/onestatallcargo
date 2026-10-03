@extends('layouts.hub')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-extrabold text-gray-900">Wallet & Payouts</h2>
    </div>

    <!-- Wallet Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gray-900 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10 text-6xl">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <h3 class="text-gray-400 font-bold text-sm mb-1 uppercase tracking-wider">Current Balance</h3>
            <div class="text-4xl font-black text-[var(--gold)]">₹{{ number_format($user->wallet_balance ?? 0, 2) }}</div>
            <div class="mt-4 text-xs text-gray-400">Available for withdrawal</div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col justify-center">
            <h3 class="text-gray-500 font-bold text-sm mb-1 uppercase tracking-wider">Total Earnings</h3>
            <div class="text-2xl font-black text-gray-900">₹{{ number_format($totalEarnings ?? 0, 2) }} <span class="text-xs text-gray-400 font-medium">(All Time)</span></div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col justify-center">
            <h3 class="text-gray-500 font-bold text-sm mb-1 uppercase tracking-wider">Pending COD Remittance</h3>
            <div class="text-2xl font-black text-red-600">₹{{ number_format($pendingCod ?? 0, 2) }}</div>
            <div class="text-xs text-gray-400 font-medium mt-1">To be collected by Admin</div>
            @if(isset($pendingCod) && $pendingCod > 0)
            <form action="{{ route('hub.wallet.remit') }}" method="POST" class="mt-4">
                @csrf
                <button type="submit" class="w-full py-2 bg-gray-900 text-white text-xs font-bold rounded-lg hover:bg-black transition">Remit COD to Admin</button>
            </form>
            @endif
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Transaction History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50 text-[10px] font-extrabold uppercase text-gray-500 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Ref / Description</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3 text-right">Amount</th>
                        <th class="px-4 py-3 text-right">Balance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @if(isset($transactions) && count($transactions) > 0)
                        @foreach($transactions as $txn)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-xs text-gray-500">{{ $txn->created_at->format('d M Y, h:i A') }}</td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-gray-900">{{ $txn->reference_id ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $txn->description }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-md text-[10px] font-bold tracking-wider {{ $txn->type === 'credit' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                    {{ strtoupper($txn->type) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-bold {{ $txn->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $txn->type === 'credit' ? '+' : '-' }}₹{{ number_format($txn->amount, 2) }}
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-gray-900">₹{{ number_format($txn->balance_after, 2) }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No transactions found.</td></tr>
                    @endif
                </tbody>
            </table>
            @if(isset($transactions) && method_exists($transactions, 'links'))
            <div class="p-3 border-t border-gray-100">{{ $transactions->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
