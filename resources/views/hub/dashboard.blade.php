@extends('layouts.hub')

@section('content')

@php
    $kycDone = $kyc && $kyc->status === 'approved';
    $kycPending = $kyc && $kyc->status === 'pending';
@endphp

<div x-data="{ showKycModal: {{ $errors->any() ? 'true' : 'false' }} }" class="space-y-6">

    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-700 font-bold rounded-xl border border-green-200 text-sm">
            <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-red-50 text-red-700 font-bold rounded-xl border border-red-200 text-sm mb-6">
            <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ session('error') }}
        </div>
    @endif

    @if($franchise && $franchise->status !== 'approved')
    <!-- Franchise Pending Banner -->
    <div class="bg-gradient-to-r from-red-600 to-red-500 rounded-2xl p-6 text-white shadow-md flex items-center justify-between gap-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-lock text-2xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold">Application Pending Approval</h2>
                <p class="text-sm text-red-100 mt-1">Your franchise application is currently being reviewed by our Admin team. You cannot operate the hub until it is approved.</p>
            </div>
        </div>
    </div>
    @endif

    @if(!$kycDone)
    <!-- Franchise KYC Banner -->
    <div class="bg-gradient-to-r {{ $kycPending ? 'from-yellow-600 to-yellow-500' : 'from-[#1e293b] to-black' }} rounded-2xl p-6 text-white shadow-md flex flex-col md:flex-row items-center justify-between gap-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                <i class="fa-solid {{ $kycPending ? 'fa-clock' : 'fa-id-card' }} text-2xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-extrabold">{{ $kycPending ? 'KYC Under Review' : 'Complete Your Franchise KYC' }}</h3>
                <p class="text-sm opacity-90 mt-1">
                    {{ $kycPending ? 'Your verification documents have been submitted to the Admin and are pending review.' : 'To activate full hub functionalities and settlements, please verify your Franchise details.' }}
                </p>
            </div>
        </div>
        @if(!$kycPending)
            <button @click="showKycModal = true" class="whitespace-nowrap px-6 py-3 bg-[#FFD700] hover:bg-yellow-500 text-gray-900 font-bold rounded-xl shadow-sm transition">
                Submit Documents
            </button>
        @endif
    </div>
    @endif

    @if($franchise && $franchise->isState())
    <!-- State Franchise Control Tower Banner -->
    <div class="bg-gradient-to-r from-purple-900 via-indigo-900 to-gray-900 rounded-3xl p-6 text-white shadow-lg mb-6 border border-purple-800">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-purple-500/20 border border-purple-400/30 rounded-2xl flex items-center justify-center text-2xl text-purple-300">
                    <i class="fa-solid fa-crown"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-500/30 border border-purple-400/40 text-purple-200">State Franchise HQ</span>
                        <span class="text-xs text-gray-300">{{ $franchise->state }}</span>
                    </div>
                    <h2 class="text-xl font-black mt-0.5">{{ $franchise->company_name }}</h2>
                    <p class="text-xs text-purple-200 mt-1">State-level logistics oversight, linehaul bag movements, and subordinate district hubs</p>
                </div>
            </div>
            <div class="flex items-center gap-4 bg-white/10 rounded-2xl p-3 border border-white/10">
                <div class="text-center px-2">
                    <div class="text-[10px] uppercase font-bold text-purple-200">Districts</div>
                    <div class="text-xl font-black text-white">{{ $subordinateDistricts->count() }}</div>
                </div>
                <div class="w-px h-8 bg-white/20"></div>
                <div class="text-center px-2">
                    <div class="text-[10px] uppercase font-bold text-purple-200">Linehaul Bags</div>
                    <div class="text-xl font-black text-white">{{ $stateBagsInTransit }}</div>
                </div>
                <div class="w-px h-8 bg-white/20"></div>
                <div class="text-center px-2">
                    <div class="text-[10px] uppercase font-bold text-purple-200">State Royalty</div>
                    <div class="text-xl font-black text-[#FFD700]">₹{{ number_format($stateRoyaltyTotal, 2) }}</div>
                </div>
            </div>
        </div>
    </div>
    @elseif($franchise && $franchise->isDistrict())
    <!-- District Franchise Hub Banner -->
    <div class="bg-gradient-to-r from-blue-900 to-indigo-900 rounded-3xl p-5 text-white shadow-md mb-6 border border-blue-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-blue-500/20 border border-blue-400/30 rounded-2xl flex items-center justify-center text-xl text-blue-300">
                <i class="fa-solid fa-map-pin"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-500/30 text-blue-200">District Franchise</span>
                    <span class="text-xs text-blue-200">District: {{ $franchise->district ?? $franchise->city }} ({{ $franchise->state }})</span>
                </div>
                <h3 class="text-lg font-black mt-0.5">{{ $franchise->company_name }}</h3>
                @if($franchise->parentFranchise)
                    <div class="text-[11px] text-blue-200">Parent State Hub: <span class="font-bold text-white">{{ $franchise->parentFranchise->company_name }}</span></div>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-3 bg-white/10 rounded-2xl px-4 py-2 border border-white/10 text-xs">
            <div>
                <span class="text-blue-200 text-[10px] uppercase block">Delivery Comm:</span>
                <span class="font-black text-white">{{ $franchise->delivery_commission_type === 'flat' ? '₹' . $franchise->delivery_commission_rate . ' flat' : ($franchise->delivery_commission_type === 'per_kg' ? '₹' . $franchise->delivery_commission_rate . '/kg' : $franchise->delivery_commission_rate . '%') }}</span>
            </div>
            <div class="w-px h-6 bg-white/20"></div>
            <div>
                <span class="text-blue-200 text-[10px] uppercase block">Pickup Comm:</span>
                <span class="font-black text-white">{{ $franchise->pickup_commission_type === 'flat' ? '₹' . $franchise->pickup_commission_rate . ' flat' : ($franchise->pickup_commission_type === 'per_kg' ? '₹' . $franchise->pickup_commission_rate . '/kg' : $franchise->pickup_commission_rate . '%') }}</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Dedicated Wallet & Quick Actions Banner -->
    <div class="bg-gradient-to-r from-[#0f172a] via-[#1e1b4b] to-[#0f172a] rounded-3xl p-6 text-white shadow-xl mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-5 border border-indigo-950 relative overflow-hidden">
        <!-- Abstract BG glow -->
        <div class="absolute right-0 top-0 w-48 h-48 bg-amber-400 opacity-5 rounded-full blur-2xl pointer-events-none"></div>

        <div class="flex items-center gap-4 relative z-10">
            <div class="w-14 h-14 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center text-3xl text-[var(--gold)] shrink-0 shadow-inner">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-extrabold uppercase tracking-widest text-gray-400">Prepaid Wallet Balance</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-400/20 text-amber-300 border border-amber-400/30">Active</span>
                </div>
                <div class="text-3xl lg:text-4xl font-black text-[var(--gold)] tracking-tight mt-0.5">
                    ₹{{ number_format(Auth::user()->wallet_balance ?? 0, 2) }}
                </div>
                <p class="text-xs text-gray-400 mt-0.5">Wallet balance is required to deduct shipping charges when booking orders.</p>
            </div>
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto relative z-10">
            <a href="{{ route('hub.wallet.index') }}" class="flex-1 md:flex-none px-5 py-3 bg-[#FFD700] hover:bg-yellow-400 text-gray-950 text-xs font-black rounded-xl shadow-md transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-plus text-xs"></i> Recharge Wallet
            </a>
            <a href="{{ route('hub.shipments.create') }}" class="flex-1 md:flex-none px-5 py-3 bg-white/10 hover:bg-white/20 text-white text-xs font-extrabold rounded-xl transition flex items-center justify-center gap-2 border border-white/15">
                <i class="fa-solid fa-pen-to-square text-xs"></i> New Booking
            </a>
        </div>
    </div>

    <!-- Top Metrics Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-box"></i></div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Total Bookings</p>
                <h4 class="text-2xl font-black text-gray-900 leading-none mt-1">{{ $totalBookings }}</h4>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-truck-pickup"></i></div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Pending Pickup</p>
                <h4 class="text-2xl font-black text-gray-900 leading-none mt-1">{{ $pendingPickup }}</h4>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-motorcycle"></i></div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Out for Delivery</p>
                <h4 class="text-2xl font-black text-gray-900 leading-none mt-1">{{ $outForDelivery }}</h4>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-check-double"></i></div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Delivered</p>
                <h4 class="text-2xl font-black text-gray-900 leading-none mt-1">{{ $delivered }}</h4>
            </div>
        </div>
        
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-rotate-left"></i></div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">NDR</p>
                <h4 class="text-2xl font-black text-gray-900 leading-none mt-1">{{ $ndr }}</h4>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gray-50 text-gray-600 flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-arrow-rotate-left"></i></div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">RTO</p>
                <h4 class="text-2xl font-black text-gray-900 leading-none mt-1">{{ $rto }}</h4>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-yellow-50 text-yellow-600 flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-coins"></i></div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">COD Pending</p>
                <h4 class="text-2xl font-black text-gray-900 leading-none mt-1">₹{{ number_format($codPending, 2) }}</h4>
            </div>
        </div>
        <div class="bg-gradient-to-br from-gray-900 to-black p-5 rounded-2xl shadow-lg border border-gray-800 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gray-800/50 text-[#FFD700] flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-wallet"></i></div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Today's Earnings</p>
                <h4 class="text-2xl font-black text-white leading-none mt-1">₹{{ number_format($todayEarnings, 2) }}</h4>
            </div>
        </div>
    </div>

    <!-- Revenue Graph -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-8 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-extrabold text-gray-800"><i class="fa-solid fa-chart-line mr-2"></i> 7 Days Revenue</h2>
            <select class="text-xs font-bold border border-gray-200 rounded-lg px-2 py-1 outline-none text-gray-600">
                <option>Last 7 Days</option>
                <option>Last 30 Days</option>
            </select>
        </div>
        <div class="h-64">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    @if($franchise && $franchise->isState() && $subordinateDistricts->count() > 0)
    <!-- Subordinate District Hubs Monitoring Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 mb-8 overflow-hidden">
        <div class="p-5 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-sitemap text-purple-600"></i>
                <h3 class="font-extrabold text-gray-900 text-sm">Subordinate District Franchises & Hubs ({{ $subordinateDistricts->count() }})</h3>
            </div>
            <span class="text-xs text-gray-500 font-bold">State Territory: {{ $franchise->state }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 text-[10px] font-black uppercase tracking-wider text-gray-400 border-b border-gray-100">
                        <th class="px-5 py-3">District Hub</th>
                        <th class="px-5 py-3">District Name</th>
                        <th class="px-5 py-3">Manager</th>
                        <th class="px-5 py-3">Total Parcels</th>
                        <th class="px-5 py-3">Delivered</th>
                        <th class="px-5 py-3">Pending</th>
                        <th class="px-5 py-3">Delivery Comm</th>
                        <th class="px-5 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium text-gray-700">
                    @foreach($subordinateDistricts as $dist)
                        <tr class="hover:bg-purple-50/30 transition">
                            <td class="px-5 py-3 font-bold text-gray-900">{{ $dist->company_name }}</td>
                            <td class="px-5 py-3">
                                <div class="font-bold text-blue-600">{{ $dist->district ?? $dist->city }}</div>
                                @php
                                    $distPins = is_array($dist->serviceable_pincodes) ? $dist->serviceable_pincodes : json_decode($dist->serviceable_pincodes, true);
                                @endphp
                                @if(!empty($distPins) && is_array($distPins))
                                    <div class="text-[10px] text-gray-500 font-mono mt-0.5 flex flex-wrap gap-1 items-center">
                                        <span class="text-gray-400 font-sans text-[9px] font-bold uppercase">PIN:</span>
                                        {{ implode(', ', array_slice($distPins, 0, 4)) }}{{ count($distPins) > 4 ? ' +' . (count($distPins) - 4) . ' more' : '' }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-gray-600">{{ $dist->owner_name }} ({{ $dist->phone }})</td>
                            <td class="px-5 py-3 font-bold">{{ $dist->shipments_count }}</td>
                            <td class="px-5 py-3 font-bold text-green-600">{{ $dist->delivered_count }}</td>
                            <td class="px-5 py-3 font-bold text-amber-600">{{ $dist->pending_count }}</td>
                            <td class="px-5 py-3 font-bold text-gray-800">
                                {{ $dist->delivery_commission_type === 'flat' ? '₹' . $dist->delivery_commission_rate : $dist->delivery_commission_rate . '%' }}
                            </td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $dist->status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $dist->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    
    <!-- Left Column: Scanner Tool -->
    <div class="md:col-span-1 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden" x-data="{ scanAction: 'Receive' }">
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <h2 class="font-extrabold text-gray-800"><i class="fa-solid fa-barcode mr-2"></i> Scan AWB</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('hub.scan') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">AWB Number</label>
                        <input type="text" name="awb_number" required autofocus class="w-full text-xl font-bold uppercase tracking-widest px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl focus:bg-white focus:border-[#FFD700] focus:ring-2 focus:ring-[#FFD700] outline-none transition" placeholder="OSC...">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Action</label>
                        <select name="action" x-model="scanAction" class="w-full font-bold px-4 py-3 bg-white border border-gray-300 rounded-xl outline-none">
                            <option value="Receive">In-Scan (Receive at Hub)</option>
                            <option value="Dispatch">Out-Scan (Hub Dispatch)</option>
                            <option value="Out for Delivery">Assign to Rider (OFD)</option>
                        </select>
                    </div>

                    <!-- Rider Assignment (Only visible on OFD) -->
                    <div x-show="scanAction == 'Out for Delivery'" style="display: none;">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Select Rider</label>
                        <select name="rider_id" class="w-full font-bold px-4 py-3 bg-blue-50 border border-blue-200 text-blue-900 rounded-xl outline-none">
                            <option value="">-- Choose Rider --</option>
                            @foreach($riders as $rider)
                                <option value="{{ $rider->id }}">{{ $rider->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full py-4 bg-[#FFD700] hover:bg-[#D4AF37] text-gray-900 font-extrabold rounded-xl shadow transition">
                        Submit Scan
                    </button>
                </form>
            </div>
        </div>
        
        <div class="bg-[#1e293b] rounded-2xl p-6 text-white text-center">
            <i class="fa-solid fa-camera text-4xl mb-3 text-gray-400"></i>
            <h3 class="font-bold mb-1">Bagging & Evidence</h3>
            <p class="text-xs text-gray-400">Hub cameras are active. Ensure parcels are scanned under the recording zone for damage disputes.</p>
        </div>
    </div>

    <!-- Right Column: Recent Activity Log -->
    <div class="md:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
            <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <h2 class="font-extrabold text-gray-800"><i class="fa-solid fa-list-check mr-2"></i> Recent Scans</h2>
            </div>
            <div class="p-0 overflow-x-auto w-full">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                            <th class="px-6 py-4">AWB</th>
                            <th class="px-6 py-4">Time</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Rider</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentScans as $scan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-bold text-[#D4AF37]">{{ $scan->awb_number }}</td>
                            <td class="px-6 py-3 text-gray-500 text-xs">{{ $scan->updated_at->format('H:i:s d M') }}</td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-1 rounded bg-gray-100 font-bold text-[10px] uppercase tracking-wider text-gray-600">{{ $scan->status }}</span>
                            </td>
                            <td class="px-6 py-3 text-xs text-gray-500">
                                {{ $scan->rider_id ? optional(\App\Models\User::find($scan->rider_id))->name ?? 'Deleted Rider' : '-' }}
                            </td>
                            <td class="px-6 py-3 text-right">
                                <a href="{{ route('hub.label', $scan->awb_number) }}" target="_blank" class="px-2.5 py-1 text-[10px] font-bold bg-[#1e1b4b] text-white hover:bg-black rounded-lg transition inline-flex items-center gap-1 shadow-sm">
                                    <i class="fa-solid fa-print"></i> Slip
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- KYC Modal -->
<div x-show="showKycModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
        <div x-show="showKycModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-40 backdrop-blur-sm transition-opacity" @click="showKycModal = false" aria-hidden="true"></div>
        <div x-show="showKycModal" 
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <form action="{{ route('hub.kyc.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="bg-white px-6 pt-6 pb-6">
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="text-lg font-extrabold text-gray-900" id="modal-title">Franchise KYC Submission</h3>
                        <button type="button" @click="showKycModal = false" class="text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center transition"><i class="fa-solid fa-times"></i></button>
                    </div>
                    @if($errors->any()) <div class='mb-4 p-3 bg-red-50 text-red-700 text-xs font-bold rounded-lg border border-red-200'><ul class='list-disc pl-4'>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div> @endif <div class='space-y-4'>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Business Type</label>
                                <select name="business_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none">
                                    <option value="Proprietorship">Proprietorship</option>
                                    <option value="Partnership">Partnership</option>
                                    <option value="Private Limited">Private Limited</option>
                                    <option value="LLP">LLP</option>
                                    <option value="ONC">ONC</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Document Type</label>
                                <select name="document_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none">
                                    <option value="Aadhaar">Aadhaar</option>
                                    <option value="Voter ID">Voter ID</option>
                                    <option value="Passport">Passport</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Document Number</label>
                                <input type="text" name="document_number" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">PAN Number</label>
                                <input type="text" name="pan_number" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none uppercase">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">ID Front Image</label>
                                <input type="file" name="id_front" accept="image/*,.pdf" class="w-full px-2 py-1 text-xs border border-gray-300 rounded-lg outline-none" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">ID Back Image</label>
                                <input type="file" name="id_back" accept="image/*,.pdf" class="w-full px-2 py-1 text-xs border border-gray-300 rounded-lg outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">PAN Card Image</label>
                                <input type="file" name="pan_doc" accept="image/*,.pdf" class="w-full px-2 py-1 text-xs border border-gray-300 rounded-lg outline-none">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                    <button type="button" @click="showKycModal = false" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg text-sm">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-black text-white font-bold rounded-lg text-sm">Submit KYC</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<!-- Add AlpineJS -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_reverse($chartLabels)) !!},
                datasets: [{
                    label: 'Revenue (₹)',
                    data: {!! json_encode(array_reverse($chartData)) !!},
                    borderColor: '#4338ca',
                    backgroundColor: 'rgba(67, 56, 202, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#4338ca',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [4, 4] } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
@endsection
