@extends('layouts.admin')
@section('title', 'Platform Reports - OneStall Cargo')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Analytics & Reports</h1>
            <p class="text-sm text-gray-500 mt-1">Export daily, monthly, seller-wise, and courier-wise reports.</p>
        </div>
        <a href="{{ route('admin.reports.export') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-[#FFD700] text-gray-900 shadow-md hover:bg-[#E5C100] transition-colors inline-block"><i class="fa-solid fa-download"></i> Export Master Excel/CSV</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm cursor-pointer hover:shadow-md transition">
            <i class="fa-solid fa-calendar-day text-3xl text-blue-500 mb-4"></i>
            <h3 class="font-bold text-gray-900 text-lg">Daily Bookings</h3>
            <p class="text-3xl font-black text-gray-900 mt-2">{{ number_format($dailyBookings ?? 0) }}</p>
            <p class="text-[11px] font-bold text-gray-500 mt-1 uppercase tracking-wider">Shipments generated today</p>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm cursor-pointer hover:shadow-md transition">
            <i class="fa-solid fa-users text-3xl text-green-500 mb-4"></i>
            <h3 class="font-bold text-gray-900 text-lg">Top Seller Volume</h3>
            <p class="text-3xl font-black text-gray-900 mt-2">{{ number_format($topSeller->total ?? 0) }}</p>
            <p class="text-[11px] font-bold text-gray-500 mt-1 uppercase tracking-wider">By {{ $topSeller->name ?? 'N/A' }}</p>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm cursor-pointer hover:shadow-md transition">
            <i class="fa-solid fa-money-check-dollar text-3xl text-[var(--gold-deep)] mb-4"></i>
            <h3 class="font-bold text-gray-900 text-lg">Pending COD Remittances</h3>
            <p class="text-3xl font-black text-gray-900 mt-2">₹{{ number_format($pendingCod ?? 0, 2) }}</p>
            <p class="text-[11px] font-bold text-gray-500 mt-1 uppercase tracking-wider">Unsettled Delivered COD</p>
        </div>
    </div>
@endsection

