@extends('layouts.hub')

@section('title', 'Registered Sellers - OneStall Cargo')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Registered Sellers</h1>
            <p class="text-sm text-gray-500 mt-1">View active E-commerce Sellers and B2B Brands operating in your network area</p>
        </div>
        <div class="flex items-center gap-3">
            <form action="{{ route('hub.sellers.index') }}" method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search company, phone, pincode..." class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[var(--gold)] w-64">
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white font-bold rounded-xl text-sm hover:bg-black transition"><i class="fa-solid fa-search"></i></button>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                        <th class="px-6 py-4">Company & Brand</th>
                        <th class="px-6 py-4">Contact Person</th>
                        <th class="px-6 py-4">Location</th>
                        <th class="px-6 py-4">Volume (Shipments)</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700 font-medium">
                    @forelse($sellers as $seller)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $seller->company_name ?? 'N/A' }}</div>
                                @if($seller->brand_name)
                                    <div class="text-[11px] text-blue-600 font-semibold"><i class="fa-solid fa-tag text-[9px] mr-1"></i> {{ $seller->brand_name }}</div>
                                @endif
                                <div class="text-[10px] text-gray-500 mt-1">Acct: {{ $seller->business_type ?? 'B2B Seller' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $seller->name }}</div>
                                <div class="text-[11px] text-gray-500"><i class="fa-solid fa-envelope text-[9px] mr-1"></i> {{ $seller->email }}</div>
                                <div class="text-[11px] text-gray-500"><i class="fa-solid fa-phone text-[9px] mr-1"></i> {{ $seller->phone ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $seller->company_city ?? 'City Not Set' }}</div>
                                <div class="text-[11px] font-mono text-gray-500">PIN: {{ $seller->company_pincode ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs font-bold text-gray-900">{{ $seller->total_shipments ?? 0 }} Total Bookings</div>
                                @if($seller->pending_pickups > 0)
                                <div class="text-[11px] text-yellow-600 font-bold mt-1"><i class="fa-solid fa-truck-pickup mr-1"></i> {{ $seller->pending_pickups }} Pending Pickups</div>
                                @else
                                <div class="text-[11px] text-gray-400 mt-1"><i class="fa-solid fa-check mr-1"></i> All Picked Up</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('hub.shipments.index') }}?user_id={{ $seller->id }}" class="px-3 py-1.5 bg-gray-100 text-gray-700 hover:bg-gray-200 font-bold text-xs rounded-lg transition-colors">
                                    View Shipments
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <div class="text-3xl mb-3"><i class="fa-solid fa-store-slash"></i></div>
                                <p>No registered sellers found in your service area.</p>
                                <p class="text-xs mt-1">Sellers appear here automatically when they book shipments from your assigned pincodes.</p>
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
