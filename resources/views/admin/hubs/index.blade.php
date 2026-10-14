@extends('layouts.admin')

@section('title', 'Franchise Network & Hubs - OneStall Cargo')

@section('content')
<div class="space-y-6 max-w-[1400px] mx-auto" x-data="{ 
    activeTab: 'hierarchy', 
    showAddStateModal: false, 
    showAddDistrictModal: false, 
    showAddModal: false, 
    showAddManagerModal: false,
    showApproveModal: false,
    approveApp: null,
    showIndependent: true,
    viewMode: 'table',
    openApproveModal(app) {
        this.approveApp = app;
        this.showApproveModal = true;
    }
}">
    <!-- Top Header Row -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Franchise Network & Hubs</h1>
            <p class="text-xs text-gray-500 mt-0.5">Manage State Franchises, District Hubs, territory pincodes, and physical sorting facilities</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button @click="showAddStateModal = true" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-lg shadow-sm transition flex items-center gap-1.5">
                <i class="fa-solid fa-crown text-[11px]"></i> Add State Franchise
            </button>
            <button @click="showAddDistrictModal = true" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-lg shadow-sm transition flex items-center gap-1.5">
                <i class="fa-solid fa-map-pin text-[11px]"></i> Add District Franchise
            </button>
            <button @click="showAddModal = true" class="px-3.5 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold text-xs rounded-lg shadow-sm transition flex items-center gap-1.5">
                <i class="fa-solid fa-warehouse text-gray-400 text-[11px]"></i> Add Physical Hub
            </button>
            <button @click="showAddManagerModal = true" class="px-3.5 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold text-xs rounded-lg shadow-sm transition flex items-center gap-1.5">
                <i class="fa-solid fa-user-plus text-gray-400 text-[11px]"></i> Add Manager
            </button>
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 font-semibold text-xs flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-50 text-rose-800 border border-rose-200 font-semibold text-xs flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation text-rose-600 text-sm"></i>
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 text-rose-800 border border-rose-200 font-semibold text-xs">
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Key Metrics Cards (Standard SaaS Dashboard style) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- State Franchises -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] p-5 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <p class="text-[11px] font-bold text-gray-600">State Franchises</p>
                <div class="w-7 h-7 rounded-full border border-purple-100 flex items-center justify-center text-purple-600 bg-purple-50/50">
                    <i class="fa-solid fa-crown text-[10px]"></i>
                </div>
            </div>
            <div class="my-3">
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($stateFranchises->count()) }}</h3>
            </div>
            <p class="text-[10px] font-semibold text-gray-500 pt-2 border-t border-gray-100">
                State apex linehaul hubs
            </p>
        </div>

        <!-- District Franchises -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] p-5 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <p class="text-[11px] font-bold text-gray-600">District Franchises</p>
                <div class="w-7 h-7 rounded-full border border-blue-100 flex items-center justify-center text-blue-600 bg-blue-50/50">
                    <i class="fa-solid fa-map-pin text-[10px]"></i>
                </div>
            </div>
            <div class="my-3">
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($allDistrictFranchises->count()) }}</h3>
            </div>
            <p class="text-[10px] font-semibold text-gray-500 pt-2 border-t border-gray-100">
                Last-mile delivery centers
            </p>
        </div>

        <!-- Physical Hubs -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] p-5 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <p class="text-[11px] font-bold text-gray-600">Physical Sorting Hubs</p>
                <div class="w-7 h-7 rounded-full border border-gray-100 flex items-center justify-center text-gray-400">
                    <i class="fa-solid fa-warehouse text-[10px]"></i>
                </div>
            </div>
            <div class="my-3">
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($hubs->total()) }}</h3>
            </div>
            <p class="text-[10px] font-semibold text-gray-500 pt-2 border-t border-gray-100">
                Sorting & linehaul facilities
            </p>
        </div>

        <!-- Pending Review -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] p-5 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <p class="text-[11px] font-bold text-gray-600">Pending Review</p>
                <div class="w-7 h-7 rounded-full border border-amber-100 flex items-center justify-center text-amber-500 bg-amber-50/50">
                    <i class="fa-solid fa-clock text-[10px]"></i>
                </div>
            </div>
            <div class="my-3">
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($pendingFranchises->count()) }}</h3>
            </div>
            <p class="text-[10px] font-semibold text-gray-500 pt-2 border-t border-gray-100">
                Applications awaiting action
            </p>
        </div>
    </div>

    <!-- Pending Applications (if any) -->
    @if($pendingFranchises->count() > 0)
    <div class="bg-white rounded-2xl border border-amber-200/80 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
        <div class="px-5 py-4 border-b border-amber-100 bg-amber-50/40 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-clock text-amber-600 text-xs"></i>
                <h3 class="text-sm font-bold text-gray-900">Pending Franchise Applications ({{ $pendingFranchises->count() }})</h3>
            </div>
            <span class="text-[11px] text-amber-700 font-semibold">Review required</span>
        </div>
        <div class="p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($pendingFranchises as $app)
            <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-200 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="font-bold text-gray-900 text-sm">{{ $app->company_name }}</span>
                        <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-amber-50 text-amber-700 border border-amber-200">Pending</span>
                    </div>
                    <div class="text-xs text-gray-500 mb-3"><i class="fa-solid fa-user text-gray-400 mr-1"></i> {{ $app->owner_name }}</div>
                    
                    <div class="space-y-1 mb-4 text-[11px] text-gray-600">
                        <div><span class="text-gray-400">Phone:</span> <span class="font-semibold text-gray-800">{{ $app->phone }}</span></div>
                        <div><span class="text-gray-400">Email:</span> <span class="font-semibold text-gray-800">{{ $app->email }}</span></div>
                        <div><span class="text-gray-400">Region:</span> <span class="font-semibold text-gray-800">{{ $app->city }}, {{ $app->state }}</span></div>
                        <div><span class="text-gray-400">Applied At:</span> <span class="text-gray-500">{{ $app->created_at->format('d M Y, h:i A') }}</span></div>
                    </div>
                </div>

                <div class="flex gap-2 pt-2 border-t border-gray-200">
                    <button type="button" @click="openApproveModal({{ json_encode($app) }})" class="flex-1 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition shadow-sm inline-flex items-center justify-center gap-1">
                        <i class="fa-solid fa-check text-[11px]"></i> Approve
                    </button>
                    <form action="{{ route('admin.hubs.reject_franchise', $app->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" onclick="return confirm('Reject this application?')" class="w-full py-1.5 bg-white hover:bg-rose-50 text-rose-600 text-xs font-semibold rounded-lg transition border border-rose-200 inline-flex items-center justify-center gap-1">
                            <i class="fa-solid fa-xmark text-[11px]"></i> Reject
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Tabs Navigation -->
    <div class="flex border-b border-gray-200 gap-6 text-xs font-bold">
        <button @click="activeTab = 'hierarchy'" :class="activeTab === 'hierarchy' ? 'border-b-2 border-gray-900 text-gray-900 pb-3' : 'border-transparent text-gray-500 hover:text-gray-800 pb-3'" class="transition flex items-center gap-2">
            <i class="fa-solid fa-sitemap text-gray-400"></i> Franchise Network Hierarchy
        </button>
        <button @click="activeTab = 'hubs'" :class="activeTab === 'hubs' ? 'border-b-2 border-gray-900 text-gray-900 pb-3' : 'border-transparent text-gray-500 hover:text-gray-800 pb-3'" class="transition flex items-center gap-2">
            <i class="fa-solid fa-warehouse text-gray-400"></i> Physical Sorting Hubs ({{ $hubs->total() }})
        </button>
    </div>

    <!-- TAB 1: FRANCHISE HIERARCHY -->
    <div x-show="activeTab === 'hierarchy'" class="space-y-6">
        @forelse($stateFranchises as $stateF)
            <div class="bg-white rounded-2xl border border-gray-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] overflow-hidden" x-data="{ showDistricts: true }">
                <!-- State Header Bar -->
                <div class="px-5 py-4 bg-gray-50/70 border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-purple-50 text-purple-700 border border-purple-100 flex items-center justify-center text-sm shadow-sm shrink-0">
                            <i class="fa-solid fa-crown"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-base font-bold text-gray-900 tracking-tight">{{ $stateF->company_name }}</h3>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-purple-50 text-purple-700 border border-purple-100">
                                    STATE: {{ $stateF->state }}
                                </span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $stateF->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                                    {{ $stateF->status }}
                                </span>
                            </div>
                            <div class="text-xs text-gray-500 mt-0.5">
                                Manager: <span class="font-semibold text-gray-700">{{ $stateF->owner_name }} ({{ $stateF->phone }})</span> • Base: {{ $stateF->city }}
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 w-full md:w-auto justify-between md:justify-end flex-wrap">
                        <div class="text-right hidden lg:block mr-2">
                            <div class="text-[10px] font-bold uppercase tracking-wider text-gray-400">State Royalty</div>
                            <div class="text-xs font-black text-gray-900">
                                @if($stateF->state_royalty_type === 'percent')
                                    {{ $stateF->state_royalty_rate }}% of freight
                                @elseif($stateF->state_royalty_type === 'per_kg')
                                    ₹{{ $stateF->state_royalty_rate }}/kg
                                @else
                                    ₹{{ $stateF->state_royalty_rate }} flat
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.franchises.overview', $stateF->id) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition inline-flex items-center gap-1.5">
                                <i class="fa-solid fa-chart-pie text-[11px]"></i> 360° Overview
                            </a>
                            <a href="{{ route('admin.franchises.edit', $stateF->id) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 shadow-sm transition inline-flex items-center gap-1">
                                <i class="fa-solid fa-pen text-[10px] text-gray-400"></i> Edit
                            </a>
                            <form action="{{ route('admin.franchises.toggle', $stateF->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $stateF->status === 'approved' ? 'bg-white border border-rose-200 text-rose-600 hover:bg-rose-50' : 'bg-emerald-50 border border-emerald-200 text-emerald-600 hover:bg-emerald-100' }} transition">
                                    {{ $stateF->status === 'approved' ? 'Suspend' : 'Activate' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sleek State KPI Row with Show/Hide Toggle Button -->
                <div class="px-5 py-3 bg-gray-50/40 border-b border-gray-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-4 sm:gap-6 flex-wrap divide-x divide-gray-200">
                        <div class="pr-2 sm:pr-3">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Delivered</span>
                            <span class="text-sm font-black text-gray-900">{{ number_format($stateF->metrics->delivered ?? 0) }}</span>
                        </div>
                        <div class="pl-3 sm:pl-4 pr-2 sm:pr-3">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Pending</span>
                            <span class="text-sm font-black text-blue-600">{{ number_format($stateF->metrics->pending ?? 0) }}</span>
                        </div>
                        <div class="pl-3 sm:pl-4 pr-2 sm:pr-3">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">NDR / RTO</span>
                            <span class="text-sm font-black text-amber-600">{{ number_format($stateF->metrics->ndr_rto ?? 0) }}</span>
                        </div>
                        <div class="pl-3 sm:pl-4 pr-2 sm:pr-3">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">State Royalty</span>
                            <span class="text-sm font-black text-purple-700">₹{{ number_format($stateF->metrics->commission_earned ?? 0, 2) }}</span>
                        </div>
                        <div class="pl-3 sm:pl-4 pr-2 sm:pr-3">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Pending COD</span>
                            <span class="text-sm font-black text-rose-600">₹{{ number_format($stateF->metrics->pending_cod ?? 0, 2) }}</span>
                        </div>
                        <div class="pl-3 sm:pl-4">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Wallet Balance</span>
                            <span class="text-sm font-black text-gray-900">₹{{ number_format($stateF->metrics->wallet_balance ?? 0, 2) }}</span>
                        </div>
                    </div>

                    <!-- Show / Hide Subordinate District Hubs Toggle -->
                    @if($stateF->districtFranchises->count() > 0)
                        <button @click="showDistricts = !showDistricts" 
                                class="px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-white bg-white text-xs font-semibold text-gray-700 inline-flex items-center gap-1.5 transition shadow-xs shrink-0">
                            <i class="fa-solid fa-sitemap text-purple-600 text-xs"></i>
                            <span x-text="showDistricts ? 'Hide Districts ({{ $stateF->districtFranchises->count() }})' : 'Show Districts ({{ $stateF->districtFranchises->count() }})'"></span>
                            <i class="fa-solid text-[9px] text-gray-400" :class="showDistricts ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </button>
                    @endif
                </div>

                <!-- Subordinate District Franchises (Collapsible) -->
                <div class="p-5" x-show="showDistricts" x-transition>
                    <div class="flex items-center justify-between mb-3.5">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500">
                            Subordinate District Hubs ({{ $stateF->districtFranchises->count() }})
                        </h4>
                    </div>

                    @if($stateF->districtFranchises->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($stateF->districtFranchises as $distF)
                                <div class="bg-white rounded-xl p-4 border border-gray-200 hover:border-gray-300 hover:shadow-sm transition flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <div class="font-bold text-sm text-gray-900">{{ $distF->company_name }}</div>
                                                <div class="text-xs text-blue-600 font-semibold mt-0.5">
                                                    <i class="fa-solid fa-map-pin mr-1 text-[10px]"></i> District: {{ $distF->district ?? 'N/A' }}
                                                </div>
                                            </div>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $distF->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                                                {{ $distF->status }}
                                            </span>
                                        </div>

                                        <div class="space-y-1 my-3 text-[11px] text-gray-600">
                                            <div><span class="text-gray-400">Manager:</span> <span class="font-semibold text-gray-800">{{ $distF->owner_name }} ({{ $distF->phone }})</span></div>
                                            <div>
                                                <span class="text-gray-400">Delivery Comm:</span> 
                                                <span class="font-semibold text-gray-800">
                                                    {{ $distF->delivery_commission_type === 'flat' ? '₹' . $distF->delivery_commission_rate . ' flat' : ($distF->delivery_commission_type === 'per_kg' ? '₹' . $distF->delivery_commission_rate . '/kg' : $distF->delivery_commission_rate . '%') }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="text-gray-400">Pickup Comm:</span> 
                                                <span class="font-semibold text-gray-800">
                                                    {{ $distF->pickup_commission_type === 'flat' ? '₹' . $distF->pickup_commission_rate . ' flat' : ($distF->pickup_commission_type === 'per_kg' ? '₹' . $distF->pickup_commission_rate . '/kg' : $distF->pickup_commission_rate . '%') }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="text-gray-400">Pincodes:</span> 
                                                <span class="font-mono text-gray-800 font-semibold">
                                                    @php
                                                        $pins = is_array($distF->serviceable_pincodes) ? $distF->serviceable_pincodes : json_decode($distF->serviceable_pincodes, true);
                                                    @endphp
                                                    {{ is_array($pins) ? count($pins) . ' active pins' : 'None' }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Sleek Metrics Strip -->
                                        <div class="flex items-center justify-between py-2 px-3 bg-gray-50/80 rounded-lg text-xs border border-gray-100 my-3">
                                            <div class="text-center">
                                                <span class="text-[9px] text-gray-400 block uppercase font-bold">Delivered</span>
                                                <span class="font-bold text-gray-900 text-xs">{{ $distF->metrics->delivered ?? 0 }}</span>
                                            </div>
                                            <div class="h-6 w-px bg-gray-200"></div>
                                            <div class="text-center">
                                                <span class="text-[9px] text-gray-400 block uppercase font-bold">Pending</span>
                                                <span class="font-bold text-blue-600 text-xs">{{ $distF->metrics->pending ?? 0 }}</span>
                                            </div>
                                            <div class="h-6 w-px bg-gray-200"></div>
                                            <div class="text-center">
                                                <span class="text-[9px] text-gray-400 block uppercase font-bold">COD Hand</span>
                                                <span class="font-bold text-rose-600 text-xs">₹{{ number_format($distF->metrics->pending_cod ?? 0, 0) }}</span>
                                            </div>
                                            <div class="h-6 w-px bg-gray-200"></div>
                                            <div class="text-center">
                                                <span class="text-[9px] text-gray-400 block uppercase font-bold">Wallet</span>
                                                <span class="font-bold text-gray-800 text-xs">₹{{ number_format($distF->metrics->wallet_balance ?? 0, 0) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pt-2.5 border-t border-gray-100 text-xs">
                                        <a href="{{ route('admin.franchises.overview', $distF->id) }}" class="font-bold text-blue-600 hover:text-blue-800 inline-flex items-center gap-1">
                                            <i class="fa-solid fa-chart-pie text-[11px]"></i> 360° Overview
                                        </a>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.franchises.edit', $distF->id) }}" class="px-2 py-1 rounded bg-gray-50 hover:bg-gray-100 text-gray-700 text-[11px] font-semibold border border-gray-200">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.franchises.toggle', $distF->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-2 py-1 rounded text-[11px] font-semibold {{ $distF->status === 'approved' ? 'text-rose-600 hover:bg-rose-50 border border-rose-200' : 'text-emerald-600 hover:bg-emerald-50 border border-emerald-200' }}">
                                                    {{ $distF->status === 'approved' ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 text-gray-400 bg-gray-50/50 rounded-xl border border-dashed border-gray-200">
                            <i class="fa-solid fa-map-location-dot text-xl mb-1 text-gray-300"></i>
                            <p class="text-xs">No District Franchises assigned to this State yet.</p>
                            <button @click="showAddDistrictModal = true" class="mt-2 text-xs font-semibold text-blue-600 hover:underline">
                                + Add District Franchise under {{ $stateF->state }}
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-10 text-center border border-gray-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                <i class="fa-solid fa-crown text-3xl text-gray-300 mb-2"></i>
                <h3 class="text-base font-bold text-gray-900">No State Franchises Registered</h3>
                <p class="text-xs text-gray-500 mt-1 max-w-md mx-auto">Create a State Franchise to establish state-level royalty rates and network linehaul hubs.</p>
                <button @click="showAddStateModal = true" class="mt-4 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-sm transition">
                    + Register First State Franchise
                </button>
            </div>
        @endforelse

        <!-- Independent District Franchises (Clean Collapsible Section with Show/Hide & View Toggle) -->
        @if(isset($independentDistricts) && $independentDistricts->count() > 0)
            <div class="bg-white rounded-2xl border border-gray-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] overflow-hidden mt-6">
                <!-- Section Header with Show / Hide & Table / Card Toggle -->
                <div class="px-5 py-4 bg-gray-50/70 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 border border-amber-200 flex items-center justify-center text-xs shrink-0">
                            <i class="fa-solid fa-diagram-project"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-bold text-gray-900">Direct / Independent District Franchises</h3>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200">
                                    {{ $independentDistricts->count() }} Franchises
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5">District franchises operating directly without an assigned State parent franchise.</p>
                        </div>
                    </div>

                    <!-- Controls: Table / Grid View Switcher + Show / Hide Button -->
                    <div class="flex items-center gap-2 w-full sm:w-auto justify-between sm:justify-end">
                        <div class="inline-flex rounded-lg border border-gray-200 p-0.5 bg-gray-100 text-xs font-semibold" x-show="showIndependent">
                            <button @click="viewMode = 'table'" :class="viewMode === 'table' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-800'" class="px-2.5 py-1 rounded-md transition flex items-center gap-1">
                                <i class="fa-solid fa-list text-[10px]"></i> Table
                            </button>
                            <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-800'" class="px-2.5 py-1 rounded-md transition flex items-center gap-1">
                                <i class="fa-solid fa-grip text-[10px]"></i> Cards
                            </button>
                        </div>

                        <button @click="showIndependent = !showIndependent" 
                                class="px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-white bg-white text-xs font-semibold text-gray-700 inline-flex items-center gap-1.5 transition shadow-xs">
                            <i class="fa-solid" :class="showIndependent ? 'fa-eye-slash text-gray-400' : 'fa-eye text-blue-600'"></i>
                            <span x-text="showIndependent ? 'Hide ({{ $independentDistricts->count() }})' : 'Show ({{ $independentDistricts->count() }})'"></span>
                            <i class="fa-solid text-[9px] text-gray-400" :class="showIndependent ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </button>
                    </div>
                </div>

                <!-- Content Area (Show/Hide Controlled) -->
                <div x-show="showIndependent" x-transition>
                    <!-- Mode A: Sleek SaaS Table View (Default, Compact, Highly Scannable) -->
                    <div x-show="viewMode === 'table'" class="overflow-x-auto">
                        <table class="w-full text-left text-xs whitespace-nowrap">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-5 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider">FRANCHISE & DISTRICT</th>
                                    <th class="px-5 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider">MANAGER</th>
                                    <th class="px-5 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider">COMMISSION RATES</th>
                                    <th class="px-5 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider">PINCODES</th>
                                    <th class="px-5 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider text-center">DELIVERED</th>
                                    <th class="px-5 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider text-center">PENDING</th>
                                    <th class="px-5 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider">COD IN HAND</th>
                                    <th class="px-5 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider">WALLET</th>
                                    <th class="px-5 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider">STATUS</th>
                                    <th class="px-5 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider text-right">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-gray-700 font-medium">
                                @foreach($independentDistricts as $distF)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-5 py-3.5">
                                            <div class="font-bold text-gray-900 text-xs">{{ $distF->company_name }}</div>
                                            <div class="text-[10px] text-amber-600 font-semibold">
                                                <i class="fa-solid fa-map-pin text-[9px] mr-0.5"></i> {{ $distF->district ?? 'N/A' }} ({{ $distF->state ?? 'N/A' }})
                                            </div>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <div class="font-semibold text-gray-800">{{ $distF->owner_name }}</div>
                                            <div class="text-[10px] text-gray-400">{{ $distF->phone }}</div>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <div class="text-[11px] text-gray-700">Del: <span class="font-semibold">{{ $distF->delivery_commission_type === 'flat' ? '₹' . $distF->delivery_commission_rate : $distF->delivery_commission_rate . '%' }}</span></div>
                                            <div class="text-[10px] text-gray-400">Pick: {{ $distF->pickup_commission_type === 'flat' ? '₹' . $distF->pickup_commission_rate : $distF->pickup_commission_rate . '%' }}</div>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            @php
                                                $pins = is_array($distF->serviceable_pincodes) ? $distF->serviceable_pincodes : json_decode($distF->serviceable_pincodes, true);
                                            @endphp
                                            <span class="font-mono text-[11px] font-semibold text-gray-700 bg-gray-50 border border-gray-200 px-2 py-0.5 rounded">
                                                {{ is_array($pins) ? count($pins) . ' pins' : '0 pins' }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3.5 text-center font-bold text-gray-900">
                                            {{ $distF->metrics->delivered ?? 0 }}
                                        </td>
                                        <td class="px-5 py-3.5 text-center font-bold text-blue-600">
                                            {{ $distF->metrics->pending ?? 0 }}
                                        </td>
                                        <td class="px-5 py-3.5 font-bold text-rose-600 font-mono">
                                            ₹{{ number_format($distF->metrics->pending_cod ?? 0, 0) }}
                                        </td>
                                        <td class="px-5 py-3.5 font-bold text-gray-800 font-mono">
                                            ₹{{ number_format($distF->metrics->wallet_balance ?? 0, 0) }}
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $distF->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                                                {{ $distF->status }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3.5 text-right space-x-1.5">
                                            <a href="{{ route('admin.franchises.overview', $distF->id) }}" class="px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold text-[11px] inline-flex items-center gap-1 border border-blue-100 transition">
                                                <i class="fa-solid fa-chart-pie text-[10px]"></i> 360° Overview
                                            </a>
                                            <a href="{{ route('admin.franchises.edit', $distF->id) }}" class="px-2 py-1 rounded bg-gray-50 hover:bg-gray-100 text-gray-700 text-[11px] font-semibold border border-gray-200 transition">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.franchises.toggle', $distF->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-2 py-1 rounded text-[11px] font-semibold transition {{ $distF->status === 'approved' ? 'text-rose-600 hover:bg-rose-50 border border-rose-200' : 'text-emerald-600 hover:bg-emerald-50 border border-emerald-200' }}">
                                                    {{ $distF->status === 'approved' ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mode B: Responsive Cards Grid View -->
                    <div x-show="viewMode === 'grid'" class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($independentDistricts as $distF)
                                <div class="bg-white rounded-xl p-4 border border-gray-200 hover:border-gray-300 hover:shadow-sm transition flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <div class="font-bold text-sm text-gray-900">{{ $distF->company_name }}</div>
                                                <div class="text-xs text-amber-600 font-semibold mt-0.5">
                                                    <i class="fa-solid fa-map-pin mr-1 text-[10px]"></i> District: {{ $distF->district ?? 'N/A' }} ({{ $distF->state ?? 'N/A' }})
                                                </div>
                                            </div>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $distF->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                                                {{ $distF->status }}
                                            </span>
                                        </div>

                                        <div class="space-y-1 my-3 text-[11px] text-gray-600">
                                            <div><span class="text-gray-400">Manager:</span> <span class="font-semibold text-gray-800">{{ $distF->owner_name }} ({{ $distF->phone }})</span></div>
                                            <div>
                                                <span class="text-gray-400">Delivery Comm:</span> 
                                                <span class="font-semibold text-gray-800">
                                                    {{ $distF->delivery_commission_type === 'flat' ? '₹' . $distF->delivery_commission_rate . ' flat' : ($distF->delivery_commission_type === 'per_kg' ? '₹' . $distF->delivery_commission_rate . '/kg' : $distF->delivery_commission_rate . '%') }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="text-gray-400">Pickup Comm:</span> 
                                                <span class="font-semibold text-gray-800">
                                                    {{ $distF->pickup_commission_type === 'flat' ? '₹' . $distF->pickup_commission_rate . ' flat' : ($distF->pickup_commission_type === 'per_kg' ? '₹' . $distF->pickup_commission_rate . '/kg' : $distF->pickup_commission_rate . '%') }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="text-gray-400">Pincodes:</span> 
                                                <span class="font-mono text-gray-800 font-semibold">
                                                    @php
                                                        $pins = is_array($distF->serviceable_pincodes) ? $distF->serviceable_pincodes : json_decode($distF->serviceable_pincodes, true);
                                                    @endphp
                                                    {{ is_array($pins) ? count($pins) . ' active pins' : 'None' }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Sleek Metrics Strip -->
                                        <div class="flex items-center justify-between py-2 px-3 bg-gray-50/80 rounded-lg text-xs border border-gray-100 my-3">
                                            <div class="text-center">
                                                <span class="text-[9px] text-gray-400 block uppercase font-bold">Delivered</span>
                                                <span class="font-bold text-gray-900 text-xs">{{ $distF->metrics->delivered ?? 0 }}</span>
                                            </div>
                                            <div class="h-6 w-px bg-gray-200"></div>
                                            <div class="text-center">
                                                <span class="text-[9px] text-gray-400 block uppercase font-bold">Pending</span>
                                                <span class="font-bold text-blue-600 text-xs">{{ $distF->metrics->pending ?? 0 }}</span>
                                            </div>
                                            <div class="h-6 w-px bg-gray-200"></div>
                                            <div class="text-center">
                                                <span class="text-[9px] text-gray-400 block uppercase font-bold">COD Hand</span>
                                                <span class="font-bold text-rose-600 text-xs">₹{{ number_format($distF->metrics->pending_cod ?? 0, 0) }}</span>
                                            </div>
                                            <div class="h-6 w-px bg-gray-200"></div>
                                            <div class="text-center">
                                                <span class="text-[9px] text-gray-400 block uppercase font-bold">Wallet</span>
                                                <span class="font-bold text-gray-800 text-xs">₹{{ number_format($distF->metrics->wallet_balance ?? 0, 0) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pt-2.5 border-t border-gray-100 text-xs">
                                        <a href="{{ route('admin.franchises.overview', $distF->id) }}" class="font-bold text-blue-600 hover:text-blue-800 inline-flex items-center gap-1">
                                            <i class="fa-solid fa-chart-pie text-[11px]"></i> 360° Overview
                                        </a>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.franchises.edit', $distF->id) }}" class="px-2 py-1 rounded bg-gray-50 hover:bg-gray-100 text-gray-700 text-[11px] font-semibold border border-gray-200">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.franchises.toggle', $distF->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-2 py-1 rounded text-[11px] font-semibold {{ $distF->status === 'approved' ? 'text-rose-600 hover:bg-rose-50 border border-rose-200' : 'text-emerald-600 hover:bg-emerald-50 border border-emerald-200' }}">
                                                    {{ $distF->status === 'approved' ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- TAB 2: PHYSICAL HUBS TABLE (Standard Admin SaaS style) -->
    <div x-show="activeTab === 'hubs'" style="display: none;" class="space-y-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 tracking-tight">Physical Sorting Facilities</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Direct linehaul & local warehouse facilities</p>
                </div>
                <button @click="showAddModal = true" class="px-3.5 py-1.5 bg-gray-900 hover:bg-black text-white text-xs font-semibold rounded-lg shadow-sm transition">
                    + Add New Hub
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider">HUB CODE</th>
                            <th class="px-6 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider">FACILITY NAME</th>
                            <th class="px-6 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider">CITY / PINCODE</th>
                            <th class="px-6 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider">DAILY CAPACITY</th>
                            <th class="px-6 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider">MANAGER</th>
                            <th class="px-6 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider">STATUS</th>
                            <th class="px-6 py-3 font-extrabold text-[10px] text-gray-500 uppercase tracking-wider text-right">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700 font-medium">
                        @forelse($hubs as $hub)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-3.5">
                                    <span class="font-mono font-bold text-gray-900">{{ $hub->hub_code }}</span>
                                </td>
                                <td class="px-6 py-3.5 font-bold text-gray-900">{{ $hub->name }}</td>
                                <td class="px-6 py-3.5">{{ $hub->city }} ({{ $hub->pincode }})</td>
                                <td class="px-6 py-3.5">{{ number_format($hub->capacity) }} units/day</td>
                                <td class="px-6 py-3.5">
                                    @if($hub->manager)
                                        <div class="font-semibold text-gray-900">{{ $hub->manager->name }}</div>
                                        <div class="text-[10px] text-gray-500">{{ $hub->manager->phone ?? $hub->manager->email }}</div>
                                    @else
                                        <span class="text-gray-400 italic">Unassigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $hub->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                                        {{ $hub->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-right space-x-2">
                                    <form action="{{ route('admin.hubs.toggle', $hub->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="text-[11px] font-semibold {{ $hub->is_active ? 'text-rose-600 hover:text-rose-800' : 'text-emerald-600 hover:text-emerald-800' }}">
                                            {{ $hub->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.hubs.edit', $hub->id) }}" class="text-[11px] font-semibold text-blue-600 hover:text-blue-800 inline-flex items-center gap-1">
                                        <i class="fa-solid fa-pen text-[9px]"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    <i class="fa-solid fa-warehouse text-3xl mb-2 text-gray-300"></i>
                                    <p>No Hubs configured yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($hubs->hasPages())
                <div class="px-6 py-3.5 border-t border-gray-100 bg-gray-50/50">
                    {{ $hubs->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- MODAL 1: ADD STATE FRANCHISE -->
    <div x-show="showAddStateModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-10 sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 transition-opacity" @click="showAddStateModal = false"></div>
            <div class="relative bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl border border-gray-200 overflow-hidden z-10">
                <form action="{{ route('admin.franchises.store_state') }}" method="POST">
                    @csrf
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100 mb-4">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-crown text-purple-600 text-sm"></i>
                            <h3 class="text-base font-bold text-gray-900">Register State Franchise</h3>
                        </div>
                        <button type="button" @click="showAddStateModal = false" class="text-gray-400 hover:text-gray-600 text-xs"><i class="fa-solid fa-times"></i></button>
                    </div>

                    <div class="space-y-3.5 text-xs">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Company / Entity Name</label>
                                <input type="text" name="company_name" required placeholder="e.g. Rajasthan Apex Cargo" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:border-blue-600 focus:bg-white text-xs">
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Owner / Manager Name</label>
                                <input type="text" name="owner_name" required placeholder="Full Name" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:border-blue-600 focus:bg-white text-xs">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Phone Number</label>
                                <input type="text" name="phone" required placeholder="10-digit Phone" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:border-blue-600 focus:bg-white text-xs">
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Email Address</label>
                                <input type="email" name="email" required placeholder="manager@statehub.com" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:border-blue-600 focus:bg-white text-xs">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">State</label>
                                <input type="text" name="state" required placeholder="e.g. Rajasthan" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:border-blue-600 focus:bg-white text-xs">
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Headquarter City</label>
                                <input type="text" name="city" required placeholder="e.g. Jaipur" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:border-blue-600 focus:bg-white text-xs">
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Office / Warehouse Address</label>
                            <input type="text" name="address" required placeholder="Full HQ address" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:border-blue-600 focus:bg-white text-xs">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Login Password</label>
                                <input type="password" name="password" required minlength="8" placeholder="Minimum 8 characters" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:border-blue-600 focus:bg-white text-xs">
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">State Royalty Type</label>
                                <select name="state_royalty_type" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:border-blue-600 focus:bg-white text-xs">
                                    <option value="percent">Percentage (%) of Freight</option>
                                    <option value="flat">Flat Fee per Shipment (₹)</option>
                                    <option value="per_kg">Rate per Kilogram (₹/kg)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">State Royalty Rate</label>
                            <input type="number" step="0.01" min="0" name="state_royalty_rate" required placeholder="e.g. 5.00" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:border-blue-600 focus:bg-white text-xs">
                            <p class="text-[10px] text-gray-400 mt-1">Earned on all successful deliveries within this state territory.</p>
                        </div>
                    </div>

                    <div class="mt-5 pt-3 border-t border-gray-100 flex justify-end gap-2">
                        <button type="button" @click="showAddStateModal = false" class="px-3.5 py-2 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-sm">Register State Franchise</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 2: ADD DISTRICT FRANCHISE -->
    <div x-show="showAddDistrictModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-10 sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 transition-opacity" @click="showAddDistrictModal = false"></div>
            <div class="relative bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl border border-gray-200 overflow-hidden z-10">
                <form action="{{ route('admin.franchises.store_district') }}" method="POST">
                    @csrf
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100 mb-4">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-map-pin text-blue-600 text-sm"></i>
                            <h3 class="text-base font-bold text-gray-900">Register District Franchise</h3>
                        </div>
                        <button type="button" @click="showAddDistrictModal = false" class="text-gray-400 hover:text-gray-600 text-xs"><i class="fa-solid fa-times"></i></button>
                    </div>

                    <div class="space-y-3.5 text-xs">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Parent State Franchise</label>
                                <select name="parent_franchise_id" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:border-blue-600 focus:bg-white text-xs">
                                    <option value="">-- Select State Franchise --</option>
                                    @foreach($stateFranchises as $stateF)
                                        <option value="{{ $stateF->id }}">{{ $stateF->company_name }} ({{ $stateF->state }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">District Name</label>
                                <input type="text" name="district" required placeholder="e.g. Jaipur Rural" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:border-blue-600 focus:bg-white text-xs">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">District Franchise Name</label>
                                <input type="text" name="company_name" required placeholder="e.g. Jaipur North Hub" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:border-blue-600 focus:bg-white text-xs">
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Owner / Manager Name</label>
                                <input type="text" name="owner_name" required placeholder="Full Name" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:border-blue-600 focus:bg-white text-xs">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Phone Number</label>
                                <input type="text" name="phone" required placeholder="10-digit Phone" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:border-blue-600 focus:bg-white text-xs">
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Email Address</label>
                                <input type="email" name="email" required placeholder="manager@districthub.com" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:border-blue-600 focus:bg-white text-xs">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Base City</label>
                                <input type="text" name="city" required placeholder="City name" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:border-blue-600 focus:bg-white text-xs">
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Login Password</label>
                                <input type="password" name="password" required minlength="8" placeholder="Minimum 8 characters" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:border-blue-600 focus:bg-white text-xs">
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Warehouse Address</label>
                            <input type="text" name="address" required placeholder="Full District Hub address" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:border-blue-600 focus:bg-white text-xs">
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Serviceable Pincodes (Comma-separated)</label>
                            <textarea name="serviceable_pincodes" rows="2" required placeholder="302001, 302002, 302015" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:border-blue-600 focus:bg-white text-xs font-mono"></textarea>
                            <p class="text-[10px] text-gray-400 mt-1">Territory assignment is strictly validated: duplicate active pincodes are not allowed.</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Delivery Commission Type</label>
                                <select name="delivery_commission_type" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:border-blue-600 focus:bg-white text-xs">
                                    <option value="flat">Flat Fee per Packet (₹)</option>
                                    <option value="per_kg">Rate per Kilogram (₹/kg)</option>
                                    <option value="percent">Percentage (%)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Delivery Commission Rate</label>
                                <input type="number" step="0.01" min="0" name="delivery_commission_rate" required placeholder="e.g. 25.00" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:border-blue-600 focus:bg-white text-xs">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Pickup Commission Type</label>
                                <select name="pickup_commission_type" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:border-blue-600 focus:bg-white text-xs">
                                    <option value="flat">Flat Fee per Pickup (₹)</option>
                                    <option value="per_kg">Rate per Kilogram (₹/kg)</option>
                                    <option value="percent">Percentage (%)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Pickup Commission Rate</label>
                                <input type="number" step="0.01" min="0" name="pickup_commission_rate" required placeholder="e.g. 15.00" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:border-blue-600 focus:bg-white text-xs">
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 pt-3 border-t border-gray-100 flex justify-end gap-2">
                        <button type="button" @click="showAddDistrictModal = false" class="px-3.5 py-2 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-sm">Register District Franchise</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 3: APPROVE APPLICATION -->
    <div x-show="showApproveModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-10 sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 transition-opacity" @click="showApproveModal = false"></div>
            <div class="relative bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-gray-200 overflow-hidden z-10" x-data="{ level: 'district' }">
                <template x-if="approveApp">
                    <form :action="'{{ url('admin/hubs/franchise') }}/' + approveApp.id + '/approve'" method="POST">
                        @csrf
                        <div class="flex justify-between items-center pb-3 border-b border-gray-100 mb-4">
                            <h3 class="text-sm font-bold text-gray-900">Approve Franchise Application</h3>
                            <button type="button" @click="showApproveModal = false" class="text-gray-400 hover:text-gray-600 text-xs"><i class="fa-solid fa-times"></i></button>
                        </div>

                        <div class="space-y-3.5 text-xs">
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="font-bold text-gray-900" x-text="approveApp.company_name"></div>
                                <div class="text-[11px] text-gray-500 mt-0.5" x-text="approveApp.owner_name + ' | ' + approveApp.city + ', ' + approveApp.state"></div>
                            </div>

                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Designate Franchise Level</label>
                                <select name="franchise_level" x-model="level" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none text-xs">
                                    <option value="district">District Franchise</option>
                                    <option value="state">State Franchise</option>
                                </select>
                            </div>

                            <div x-show="level === 'district'" class="space-y-3">
                                <div>
                                    <label class="block font-semibold text-gray-700 mb-1">Assign Parent State Franchise</label>
                                    <select name="parent_franchise_id" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none text-xs">
                                        <option value="">-- Select Parent State Hub --</option>
                                        @foreach($stateFranchises as $stateF)
                                            <option value="{{ $stateF->id }}">{{ $stateF->company_name }} ({{ $stateF->state }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-semibold text-gray-700 mb-1">District Name</label>
                                    <input type="text" name="district" placeholder="District name" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none text-xs">
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block font-semibold text-gray-700 mb-1">Delivery Commission Rate (₹)</label>
                                        <input type="number" step="0.01" min="0" name="delivery_commission_rate" value="25.00" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none text-xs">
                                    </div>
                                    <div>
                                        <label class="block font-semibold text-gray-700 mb-1">Pickup Commission Rate (₹)</label>
                                        <input type="number" step="0.01" min="0" name="pickup_commission_rate" value="15.00" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none text-xs">
                                    </div>
                                </div>
                            </div>

                            <div x-show="level === 'state'" class="space-y-3" style="display: none;">
                                <div>
                                    <label class="block font-semibold text-gray-700 mb-1">State Royalty Rate (%)</label>
                                    <input type="number" step="0.01" min="0" name="state_royalty_rate" value="5.00" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg outline-none text-xs">
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 pt-3 border-t border-gray-100 flex justify-end gap-2">
                            <button type="button" @click="showApproveModal = false" class="px-3.5 py-2 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-sm">Confirm Approval</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>

    <!-- MODAL 4: PHYSICAL HUB MODAL -->
    <div x-show="showAddModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-10 sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 transition-opacity" @click="showAddModal = false"></div>
            <div class="relative bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-gray-200 overflow-hidden z-10">
                <form action="{{ route('admin.hubs.store') }}" method="POST">
                    @csrf
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100 mb-4">
                        <h3 class="text-sm font-bold text-gray-900">Register Sorting Hub Facility</h3>
                        <button type="button" @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 text-xs"><i class="fa-solid fa-times"></i></button>
                    </div>

                    <div class="space-y-3.5 text-xs">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Hub Code (e.g. DEL-01)</label>
                                <input type="text" name="hub_code" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg uppercase text-xs">
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Facility Name</label>
                                <input type="text" name="name" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">City</label>
                                <input type="text" name="city" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs">
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Pincode</label>
                                <input type="text" name="pincode" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs">
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Daily Capacity (Parcels)</label>
                            <input type="number" name="capacity" value="1000" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs">
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Assign Franchise Manager (Optional)</label>
                            <select name="manager_id" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs">
                                <option value="">-- No Manager Assigned --</option>
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}">{{ $manager->name }} ({{ $manager->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-5 pt-3 border-t border-gray-100 flex justify-end gap-2">
                        <button type="button" @click="showAddModal = false" class="px-3.5 py-2 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-gray-900 hover:bg-black text-white text-xs font-semibold shadow-sm">Register Hub</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 5: ADD MANAGER MODAL -->
    <div x-show="showAddManagerModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-10 sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 transition-opacity" @click="showAddManagerModal = false"></div>
            <div class="relative bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-200 overflow-hidden z-10">
                <form action="{{ route('admin.hubs.store_manager') }}" method="POST">
                    @csrf
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100 mb-4">
                        <h3 class="text-sm font-bold text-gray-900">Create Franchise Manager</h3>
                        <button type="button" @click="showAddManagerModal = false" class="text-gray-400 hover:text-gray-600 text-xs"><i class="fa-solid fa-times"></i></button>
                    </div>

                    <div class="space-y-3.5 text-xs">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Manager Name</label>
                            <input type="text" name="name" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Email Address</label>
                            <input type="email" name="email" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Phone Number</label>
                            <input type="text" name="phone" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Password</label>
                            <input type="password" name="password" required minlength="8" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs">
                        </div>
                    </div>

                    <div class="mt-5 pt-3 border-t border-gray-100 flex justify-end gap-2">
                        <button type="button" @click="showAddManagerModal = false" class="px-3.5 py-2 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-gray-900 hover:bg-black text-white text-xs font-semibold shadow-sm">Create Manager</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
