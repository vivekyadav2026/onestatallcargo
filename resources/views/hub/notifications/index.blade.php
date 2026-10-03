@extends('layouts.hub')

@section('title', 'Notifications - OneStall Cargo')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">System Notifications</h1>
            <p class="text-sm text-gray-500 mt-1">Updates, alerts, and announcements from Admin</p>
        </div>
        <button class="w-full sm:w-auto px-4 py-2 bg-gray-100 text-gray-600 font-bold rounded-xl text-sm hover:bg-gray-200 transition">
            <i class="fa-solid fa-check-double mr-1"></i> Mark all as read
        </button>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="divide-y divide-gray-100">
            <!-- Sample Notification 1 -->
            <div class="p-4 hover:bg-gray-50 transition flex gap-4 items-start bg-blue-50/30">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 mt-1">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start">
                        <h4 class="font-bold text-gray-900 text-sm">Welcome to the New Hub Portal!</h4>
                        <span class="text-[10px] text-gray-400 font-bold">Just now</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">The new Franchise & Hub operations dashboard is now live. Please check out the new features including Bagging, Wallets, and Reports.</p>
                </div>
            </div>

            <!-- Sample Notification 2 -->
            <div class="p-4 hover:bg-gray-50 transition flex gap-4 items-start">
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center shrink-0 mt-1">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start">
                        <h4 class="font-bold text-gray-900 text-sm">Action Required: Pending Pickups</h4>
                        <span class="text-[10px] text-gray-400 font-bold">2 hours ago</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">You have 5 shipments scheduled for pickup today that are not yet assigned to a Rider. Please assign them from the Assignments tab.</p>
                </div>
            </div>

            <!-- Sample Notification 3 -->
            <div class="p-4 hover:bg-gray-50 transition flex gap-4 items-start">
                <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center shrink-0 mt-1">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start">
                        <h4 class="font-bold text-gray-900 text-sm">COD Remittance Reminder</h4>
                        <span class="text-[10px] text-gray-400 font-bold">Yesterday</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">Please ensure all collected COD amounts are remitted to Admin on time to maintain service quality metrics.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
