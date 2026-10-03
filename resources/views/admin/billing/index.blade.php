@extends('layouts.admin')
@section('title', 'Billing & COD Ledgers - OneStall Cargo')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">COD Settlement Ledgers</h1>
            <p class="text-sm text-gray-500 mt-1">Reconcile and remit Cash-on-Delivery collections to Sellers.</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-red-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-red-50 text-red-500 flex justify-center items-center text-2xl font-black">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Pending Remittance</p>
                <h3 class="text-2xl font-black text-gray-900">&#8377; {{ number_format($totalPendingCOD, 2) }}</h3>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-3xl border border-green-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-green-50 text-green-500 flex justify-center items-center text-2xl font-black">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Settled (All Time)</p>
                <h3 class="text-2xl font-black text-gray-900">&#8377; {{ number_format($totalSettledCOD, 2) }}</h3>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-700 font-bold rounded-xl border border-green-200 text-sm">
            <i class="fa-solid fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                        <th class="px-6 py-4">Seller Details</th>
                        <th class="px-6 py-4">Current Wallet Balance</th>
                        <th class="px-6 py-4">Total Delivered COD Parcels</th>
                        <th class="px-6 py-4 text-right">Net Payable Amount</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($ledgers as $ledger)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $ledger->user->name ?? 'Unknown Seller' }}</div>
                            <div class="text-[10px] text-gray-400 font-medium mb-2"><i class="fa-solid fa-building mr-1"></i> {{ $ledger->user->company_name ?? 'N/A' }}</div>
                            
                            @if($ledger->user && $ledger->user->account_number)
                                <div class="bg-gray-50 p-2 rounded border border-gray-100 text-[10px]">
                                    <div class="font-bold text-gray-700">{{ $ledger->user->bank_name ?? 'Bank' }}</div>
                                    <div class="text-gray-500 font-mono mt-0.5">A/C: {{ $ledger->user->account_number }}</div>
                                    <div class="text-gray-500 font-mono">IFSC: {{ $ledger->user->ifsc_code }}</div>
                                    <div class="text-gray-500 mt-0.5">Name: {{ $ledger->user->account_holder_name }}</div>
                                </div>
                            @else
                                <div class="text-[10px] text-red-500 font-medium"><i class="fa-solid fa-triangle-exclamation mr-1"></i> No Bank Details Added</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($ledger->user && $ledger->user->wallet_balance < 0)
                                <span class="px-3 py-1 bg-red-50 text-red-700 font-bold rounded-full text-xs border border-red-100">
                                    &#8377; {{ number_format($ledger->user->wallet_balance, 2) }} (Due)
                                </span>
                            @else
                                <span class="px-3 py-1 bg-green-50 text-green-700 font-bold rounded-full text-xs border border-green-100">
                                    &#8377; {{ number_format($ledger->user->wallet_balance ?? 0, 2) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 font-bold rounded-full text-xs">
                                {{ $ledger->total_shipments }} Parcels
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-black text-lg text-red-500">
                            &#8377; {{ number_format($ledger->total_cod, 2) }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if(optional($ledger->user)->early_cod_plan && optional($ledger->user)->early_cod_plan !== 'standard')
                                <div class="mb-2 text-[10px] font-bold text-purple-600 bg-purple-50 inline-block px-2 py-1 rounded">
                                    <i class="fa-solid fa-bolt text-yellow-500"></i> Early COD Active ({{ optional($ledger->user)->early_cod_fee }}% Fee)
                                </div>
                                <form action="{{ route('admin.billing.remit-early', $ledger->user_id) }}" method="POST" onsubmit="return confirm('Process Early COD? This will deduct the {{ optional($ledger->user)->early_cod_fee }}% fee and instantly credit the net amount to the seller\'s wallet.');">
                                    @csrf
                                    <button type="submit" class="w-full px-5 py-2.5 bg-[#5d16c5] hover:bg-[#4b11a3] text-white font-extrabold text-xs rounded-xl shadow-sm transition">
                                        <i class="fa-solid fa-bolt mr-1"></i> Payout to Wallet
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.billing.remit', $ledger->user_id) }}" method="POST" onsubmit="return confirm('Confirm you have manually remitted &#8377; {{ number_format($ledger->total_cod, 2) }} to this seller via bank transfer? This action cannot be undone.');">
                                    @csrf
                                    <button type="submit" class="w-full px-5 py-2.5 bg-[var(--gold)] hover:bg-[var(--gold-deep)] text-gray-900 font-extrabold text-xs rounded-xl shadow-sm transition">
                                        <i class="fa-solid fa-money-bill-transfer mr-1"></i> Mark as Remitted
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                            <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center text-gray-300 text-3xl mx-auto mb-4">
                                <i class="fa-solid fa-check-double"></i>
                            </div>
                            <p class="font-bold text-gray-600 text-base">All COD remittances are up to date.</p>
                            <p class="text-xs mt-1">No pending COD settlements found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($ledgers->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $ledgers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
