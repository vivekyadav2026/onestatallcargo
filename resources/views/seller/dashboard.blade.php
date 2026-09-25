@extends('layouts.seller')
@section('title', 'Dashboard - OneStall Cargo')

@section('content')
<div class="space-y-6" x-data="{ tab: 'overview' }">
    
    @php
        $kycDone = $kyc && $kyc->status === 'approved';
        $kycPending = $kyc && $kyc->status === 'pending';
        $walletDone = (Auth::user()->wallet_balance ?? 0) > 0;
        $orderDone = ($totalOrders ?? 0) > 0;
        $setupScore = ($kycDone ? 1 : 0) + ($walletDone ? 1 : 0) + ($orderDone ? 1 : 0);
    @endphp

    <!-- Dynamic Circle 1: Top Promo Banner -->
    @if(!$walletDone)
        <div class="bg-gradient-to-r from-[#1d4ed8] via-[#2563eb] to-[#3b82f6] rounded-2xl p-6 text-white shadow-md relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 bg-blue-600/40 border border-white/20 rounded-2xl flex items-center justify-center shrink-0 shadow-inner">
                    <i class="fa-solid fa-gift text-2xl text-white"></i>
                </div>
                <div>
                    <h2 class="text-xl md:text-2xl font-black tracking-tight text-white mb-1">&#8377;500 FREE Shipping Credits</h2>
                    <p class="text-xs text-blue-100 font-medium">are sitting in your wallet. Make first recharge of &#8377;1,000 to unlock.</p>
                </div>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <div class="bg-white text-gray-900 px-3.5 py-2 rounded-xl text-xs font-bold flex items-center gap-2 border border-blue-100">
                    <span class="text-gray-400 font-normal">Use code</span>
                    <span class="font-mono text-blue-700 tracking-wider">FIRST1000</span>
                </div>
                <a href="{{ route('seller.wallet') }}" class="px-5 py-2.5 bg-black hover:bg-gray-900 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-1.5">
                    Get My Free Credits <i class="fa-solid fa-bolt text-yellow-400"></i>
                </a>
            </div>
        </div>
    @else
        <div class="bg-gradient-to-r from-[#0f172a] to-[#1e1b4b] rounded-2xl p-6 text-white shadow-md flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-500/20 border border-green-400/30 flex items-center justify-center text-green-400 text-xl">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white">Active Balance: &#8377; {{ number_format(Auth::user()->wallet_balance, 2) }}</h3>
                    <p class="text-xs text-gray-300">Your wallet is funded and ready for instant AWB generation.</p>
                </div>
            </div>
            <a href="{{ route('seller.wallet') }}" class="px-4 py-2 bg-[#4338ca] text-white font-bold text-xs rounded-xl hover:bg-[#3730a3] transition">Recharge Wallet</a>
        </div>
    @endif

    <!-- Dynamic Circle 2: Onboarding Setup Checklist -->
    @if($setupScore < 3)
    <div class="bg-gradient-to-b from-[#fffbeb] to-white rounded-2xl border border-amber-200/80 p-6 shadow-sm">
        <div class="mb-4">
            <h3 class="text-lg font-bold text-gray-900">Let's Get Shipping!</h3>
            <p class="text-xs text-gray-500">You're only a few steps away from your first shipment.</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200/80 p-4">
            <div class="flex items-center gap-2 mb-3">
                <span class="text-xs font-bold text-gray-900">Set Up Your Account</span>
                <span class="px-2 py-0.5 {{ $setupScore > 0 ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }} rounded-full text-[10px] font-extrabold">{{ $setupScore }}/3</span>
            </div>

            <div class="space-y-2">
                <!-- Step 1: KYC -->
                <div class="flex items-center justify-between p-3 {{ $kycDone ? 'bg-green-50/60 border-green-200' : ($kycPending ? 'bg-yellow-50/60 border-yellow-200' : 'bg-gray-50/80 border-gray-100') }} rounded-xl transition border">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg {{ $kycDone ? 'bg-green-100 text-green-700' : ($kycPending ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-50 text-blue-600') }} flex items-center justify-center text-sm">
                            <i class="fa-solid {{ $kycDone ? 'fa-check' : ($kycPending ? 'fa-clock' : 'fa-id-card') }}"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-900">Complete Your KYC</h4>
                            <p class="text-[10px] text-gray-500">
                                @if($kycDone)
                                    KYC documents verified & active
                                @elseif($kycPending)
                                    Verification submitted & under review by team
                                @else
                                    Upload Aadhaar, PAN, and GST details
                                @endif
                            </p>
                        </div>
                    </div>
                    @if($kycDone)
                        <span class="text-xs font-bold text-green-700"><i class="fa-solid fa-circle-check"></i> Done</span>
                    @elseif($kycPending)
                        <a href="{{ route('seller.settings') }}?view=kyc" class="text-xs font-bold text-yellow-700 hover:underline"><i class="fa-solid fa-clock"></i> Under Review</a>
                    @else
                        <a href="{{ route('seller.settings') }}?view=kyc" class="px-4 py-1.5 bg-[#0f172a] hover:bg-black text-white text-xs font-bold rounded-lg transition">Start</a>
                    @endif
                </div>

                <!-- Step 2: Wallet -->
                <div class="flex items-center justify-between p-3 {{ $walletDone ? 'bg-green-50/60 border-green-200' : 'bg-gray-50/80 border-gray-100' }} rounded-xl transition border">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg {{ $walletDone ? 'bg-green-100 text-green-700' : 'bg-blue-50 text-blue-600' }} flex items-center justify-center text-sm">
                            <i class="fa-solid {{ $walletDone ? 'fa-check' : 'fa-wallet' }}"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-900">Recharge Your Wallet</h4>
                            <p class="text-[10px] text-gray-500">{{ $walletDone ? 'Current Balance: ₹'.number_format(Auth::user()->wallet_balance, 2) : 'Keep funds ready for instant shipping' }}</p>
                        </div>
                    </div>
                    @if($walletDone)
                        <span class="text-xs font-bold text-green-700"><i class="fa-solid fa-circle-check"></i> Done</span>
                    @else
                        <a href="{{ route('seller.wallet') }}" class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-gray-900 transition">
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    @endif
                </div>

                <!-- Step 3: First Order -->
                <div class="flex items-center justify-between p-3 {{ $orderDone ? 'bg-green-50/60 border-green-200' : 'bg-gray-50/80 border-gray-100' }} rounded-xl transition border">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg {{ $orderDone ? 'bg-green-100 text-green-700' : 'bg-blue-50 text-blue-600' }} flex items-center justify-center text-sm">
                            <i class="fa-solid {{ $orderDone ? 'fa-check' : 'fa-box-archive' }}"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-900">Ship Your First Order</h4>
                            <p class="text-[10px] text-gray-500">{{ $orderDone ? 'Total Orders Booked: '.$totalOrders : 'Create single shipment or upload bulk orders' }}</p>
                        </div>
                    </div>
                    @if($orderDone)
                        <span class="text-xs font-bold text-green-700"><i class="fa-solid fa-circle-check"></i> Done</span>
                    @else
                        <a href="{{ route('seller.book') }}" class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-gray-900 transition">
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Dashboard Title & Sub-tabs -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight mb-2">Dashboard</h1>
                <div class="flex gap-6 border-b border-gray-100 -mb-6">
                    <button @click="tab = 'overview'" :class="tab === 'overview' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="pb-3 text-sm transition">Overview</button>
                    <button @click="tab = 'performance'" :class="tab === 'performance' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="pb-3 text-sm transition">Performance</button>
                </div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('seller.book') }}" class="px-4 py-2 bg-[#4338ca] hover:bg-[#3730a3] text-white text-xs font-bold rounded-xl transition shadow-sm flex items-center gap-1.5">
                    <i class="fa-solid fa-plus"></i> Create Order
                </a>
            </div>
        </div>

        <div class="p-6 bg-[#f8fafc]/50">
            
            <!-- OVERVIEW TAB -->
            <div x-show="tab === 'overview'">
                
                <!-- Dynamic Circle 3: NEEDS YOUR ATTENTION Section -->
                <div class="mb-8">
                    <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">NEEDS YOUR ATTENTION</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        
                        <!-- Card 1: Total Orders -->
                        <div class="bg-white p-4 rounded-xl border border-gray-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center text-base shrink-0">
                                    <i class="fa-solid fa-box"></i>
                                </div>
                                <div>
                                    <div class="text-xl font-extrabold text-gray-900">{{ $totalOrders ?? 0 }}</div>
                                    <div class="text-[11px] text-gray-500 font-medium leading-tight">Total Orders</div>
                                </div>
                            </div>
                            <a href="{{ route('seller.shipments.index') }}" class="w-7 h-7 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xs hover:bg-blue-100 transition shrink-0">
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </div>

                        <!-- Card 2: Pickups Scheduled -->
                        <div class="bg-white p-4 rounded-xl border border-gray-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base shrink-0">
                                    <i class="fa-solid fa-truck-ramp-box"></i>
                                </div>
                                <div>
                                    <div class="text-xl font-extrabold text-gray-900">{{ $pickupsScheduled ?? 0 }}</div>
                                    <div class="text-[11px] text-gray-500 font-medium leading-tight">Pickups Scheduled</div>
                                </div>
                            </div>
                            <a href="{{ route('seller.shipments.index') }}" class="w-7 h-7 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xs hover:bg-blue-100 transition shrink-0">
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </div>

                        <!-- Card 3: NDRs Need Action -->
                        <div class="bg-white p-4 rounded-xl border border-gray-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-base shrink-0">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                </div>
                                <div>
                                    <div class="text-xl font-extrabold text-gray-900">{{ $ndrCount ?? 0 }}</div>
                                    <div class="text-[11px] text-gray-500 font-medium leading-tight">NDRs need action</div>
                                </div>
                            </div>
                            <a href="{{ route('seller.ndr') }}" class="w-7 h-7 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xs hover:bg-blue-100 transition shrink-0">
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </div>

                        <!-- Card 4: Delivered Orders -->
                        <div class="bg-white p-4 rounded-xl border border-gray-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-base shrink-0">
                                    <i class="fa-solid fa-check-double"></i>
                                </div>
                                <div>
                                    <div class="text-xl font-extrabold text-gray-900">{{ $deliveredOrders ?? 0 }}</div>
                                    <div class="text-[11px] text-gray-500 font-medium leading-tight">Delivered Orders</div>
                                </div>
                            </div>
                            <a href="{{ route('seller.shipments.index') }}" class="w-7 h-7 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xs hover:bg-blue-100 transition shrink-0">
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </div>

                    </div>
                </div>

                <!-- SHIPPING PERFORMANCE Section -->
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">SHIPPING PERFORMANCE</h3>
                        <div class="bg-white border border-gray-200 rounded-lg px-3 py-1 text-xs font-semibold text-gray-600 flex items-center gap-2">
                            <span>19 Sep 2026 ~ 25 Sep 2026</span>
                            <i class="fa-regular fa-calendar text-gray-400"></i>
                        </div>
                    </div>

                    @if(($totalOrders ?? 0) === 0)
                        <div class="bg-white rounded-xl border border-gray-200/80 p-12 text-center flex flex-col items-center justify-center shadow-sm">
                            <div class="w-12 h-12 text-gray-300 mb-3">
                                <i class="fa-regular fa-thumbs-down text-3xl"></i>
                            </div>
                            <h4 class="text-sm font-bold text-gray-900 mb-1">No activity in this range</h4>
                            <p class="text-xs text-gray-400">Pick a different date range to see your shipping performance.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-white p-6 rounded-xl border border-gray-200/80 shadow-sm">
                            <div class="p-4 bg-blue-50/50 rounded-xl border border-blue-100">
                                <span class="text-xs text-gray-500 font-semibold block mb-1">Delivery Success Rate</span>
                                <h4 class="text-2xl font-black text-blue-700">{{ $totalOrders > 0 ? round(($deliveredOrders / $totalOrders) * 100, 1) : 0 }}%</h4>
                            </div>
                            <div class="p-4 bg-amber-50/50 rounded-xl border border-amber-100">
                                <span class="text-xs text-gray-500 font-semibold block mb-1">Pending COD Remittance</span>
                                <h4 class="text-2xl font-black text-amber-700">&#8377; {{ number_format($codPending ?? 0, 2) }}</h4>
                            </div>
                            <div class="p-4 bg-green-50/50 rounded-xl border border-green-100">
                                <span class="text-xs text-gray-500 font-semibold block mb-1">Active Wallet Balance</span>
                                <h4 class="text-2xl font-black text-green-700">&#8377; {{ number_format(Auth::user()->wallet_balance ?? 0, 2) }}</h4>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Dynamic Circle 4: Recent Orders Table -->
                <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-bold text-gray-900 text-sm">Recent Orders</h3>
                        <a href="{{ route('seller.shipments.index') }}" class="text-xs font-bold text-[#4338ca] hover:underline">View All &rarr;</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs whitespace-nowrap">
                            <thead class="bg-gray-50 text-[10px] font-extrabold uppercase text-gray-400 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-3">AWB / ORDER ID</th>
                                    <th class="px-6 py-3">CUSTOMER</th>
                                    <th class="px-6 py-3">PAYMENT</th>
                                    <th class="px-6 py-3 text-right">STATUS</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-gray-700 font-medium">
                                @forelse($recentShipments as $shipment)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-3.5">
                                        <div class="font-mono font-bold text-blue-600">{{ $shipment->awb_number }}</div>
                                        <div class="text-[10px] text-gray-400">Order: {{ $shipment->order_id ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-3.5">
                                        <div class="font-bold text-gray-800">{{ $shipment->receiver_name }}</div>
                                        <div class="text-[10px] text-gray-400">{{ $shipment->delivery_city }}</div>
                                    </td>
                                    <td class="px-6 py-3.5 font-bold">
                                        @if($shipment->is_cod)
                                            <span class="text-amber-700">COD (&#8377;{{ number_format($shipment->invoice_value, 2) }})</span>
                                        @else
                                            <span class="text-green-700">Prepaid</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5 text-right">
                                        <span class="px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-100 font-extrabold rounded-full text-[10px] uppercase">{{ $shipment->status }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-400 font-medium">No recent orders found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- PERFORMANCE TAB -->
            <div x-show="tab === 'performance'" style="display: none;">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-xl border border-gray-200/80 shadow-sm">
                        <h4 class="font-bold text-gray-900 text-sm mb-4"><i class="fa-solid fa-chart-line text-[#4338ca] mr-2"></i> Courier Delivery Ratio</h4>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-xs font-semibold mb-1"><span>Delhivery Surface</span> <span>98.2%</span></div>
                                <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden"><div class="bg-blue-600 h-full w-[98%]"></div></div>
                            </div>
                            <div>
                                <div class="flex justify-between text-xs font-semibold mb-1"><span>XpressBees Surface</span> <span>96.5%</span></div>
                                <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden"><div class="bg-indigo-600 h-full w-[96%]"></div></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl border border-gray-200/80 shadow-sm">
                        <h4 class="font-bold text-gray-900 text-sm mb-4"><i class="fa-solid fa-globe text-[#4338ca] mr-2"></i> Zone Wise Shipments</h4>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-600">Zone A (Within City)</span>
                                <span class="font-bold text-gray-900">15%</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-600">Zone B (Within State)</span>
                                <span class="font-bold text-gray-900">35%</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-600">Zone C (Metro to Metro)</span>
                                <span class="font-bold text-gray-900">40%</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-600">Zone D (Rest of India)</span>
                                <span class="font-bold text-gray-900">10%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
