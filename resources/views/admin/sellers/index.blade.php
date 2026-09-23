@extends('layouts.admin')

@section('title', 'Sellers Directory - OneStall Cargo')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Sellers Directory</h1>
            <p class="text-sm text-gray-500 mt-1">Manage all registered B2B and B2C sellers</p>
        </div>
        <div class="flex gap-2">
            <button class="px-5 py-2.5 rounded-xl text-xs font-bold bg-[var(--gold)] text-gray-900 shadow-md hover:bg-[var(--gold-deep)] transition-colors"><i class="fa-solid fa-plus"></i> Add New Seller</button>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                        <th class="px-6 py-4">Seller Details</th>
                        <th class="px-6 py-4">Company Name</th>
                        <th class="px-6 py-4">Phone</th>
                        <th class="px-6 py-4">Wallet Balance</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700 font-medium">
                    @forelse($sellers as $seller)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $seller->name }}</div>
                                <div class="text-xs text-gray-500">{{ $seller->email }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $seller->company_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $seller->phone ?? 'N/A' }}</td>
                            <td class="px-6 py-4 font-bold text-[var(--gold-deep)]">₹{{ number_format($seller->wallet_balance, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-50 text-green-700">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button class="text-gray-500 hover:text-gray-900 font-bold text-xs"><i class="fa-solid fa-pen"></i> Edit</button>
                                <button class="text-[var(--gold-deep)] hover:text-yellow-600 font-bold text-xs"><i class="fa-solid fa-file-invoice"></i> Ledger</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <p>No sellers registered yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($sellers->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $sellers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
