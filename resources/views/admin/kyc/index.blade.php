@extends('layouts.admin')

@section('title', 'KYC Verification & Approvals - OneStall Cargo Admin')

@section('content')
<div class="space-y-6" x-data="{
    showRejectModal: false,
    rejectUrl: '',
    rejectionReason: '',
    docModal: false,
    docUrl: '',
    docTitle: '',
    detailsModal: false,
    kycDetails: null,
    warehouseModal: false,
    warehouseForm: {
        action: '',
        seller_name: '',
        warehouse_name: '',
        contact_person: '',
        phone: '',
        address: '',
        city: '',
        state: '',
        pincode: '',
        is_default: true
    },
    openAddWarehouse(kyc) {
        this.warehouseForm = {
            action: '/admin/kyc/' + kyc.id + '/warehouse',
            seller_name: kyc.user_name,
            warehouse_name: kyc.company_name ? (kyc.company_name + ' Hub') : (kyc.user_name + ' Warehouse'),
            contact_person: kyc.user_name,
            phone: kyc.phone || '',
            address: kyc.address || '',
            city: kyc.city || '',
            state: kyc.state || '',
            pincode: kyc.pincode || '',
            is_default: true
        };
        this.warehouseModal = true;
    },
    openDocPreview(url, title) {
        this.docUrl = url;
        this.docTitle = title;
        this.docModal = true;
    },
    setRejectReason(text) {
        this.rejectionReason = text;
    }
}">

    <!-- Page Header & Title -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-3xl border border-gray-200/80 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold mb-2">
                <i class="fa-solid fa-shield-halved"></i> Merchant Compliance & Verification
            </div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">KYC Verification Dashboard</h1>
            <p class="text-xs md:text-sm text-gray-500 mt-1">Review government ID documents, verify registered business addresses, and assign default pickup warehouses.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.kyc.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition flex items-center gap-2">
                <i class="fa-solid fa-rotate-right"></i> Refresh
            </a>
            @if(request('status') || request('search'))
                <a href="{{ route('admin.kyc.index') }}" class="px-4 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                    <i class="fa-solid fa-filter-circle-xmark"></i> Clear Filters
                </a>
            @endif
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 text-emerald-800 border border-emerald-200 font-bold text-xs flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700"><i class="fa-solid fa-xmark"></i></button>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-2xl bg-red-50 text-red-800 border border-red-200 font-bold text-xs flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-red-600 text-base"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-xmark"></i></button>
        </div>
    @endif

    <!-- KPI Summary Metric Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Pending Card -->
        <a href="{{ route('admin.kyc.index', ['status' => 'pending']) }}" class="p-5 rounded-3xl bg-white border {{ request('status', 'pending') === 'pending' ? 'border-amber-400 ring-2 ring-amber-400/20 shadow-md' : 'border-gray-200/80 shadow-sm' }} hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Pending Review</span>
                <div class="w-9 h-9 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-sm font-bold group-hover:scale-110 transition">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-gray-900 tracking-tight">{{ $counts['pending'] ?? 0 }}</div>
            <div class="text-[11px] font-semibold text-amber-600 mt-1 flex items-center gap-1">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                Action Required
            </div>
        </a>

        <!-- Approved Card -->
        <a href="{{ route('admin.kyc.index', ['status' => 'approved']) }}" class="p-5 rounded-3xl bg-white border {{ request('status') === 'approved' ? 'border-emerald-400 ring-2 ring-emerald-400/20 shadow-md' : 'border-gray-200/80 shadow-sm' }} hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Approved Sellers</span>
                <div class="w-9 h-9 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm font-bold group-hover:scale-110 transition">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-gray-900 tracking-tight">{{ $counts['approved'] ?? 0 }}</div>
            <div class="text-[11px] font-semibold text-emerald-600 mt-1">
                Verified & Active Shipping
            </div>
        </a>

        <!-- Rejected Card -->
        <a href="{{ route('admin.kyc.index', ['status' => 'rejected']) }}" class="p-5 rounded-3xl bg-white border {{ request('status') === 'rejected' ? 'border-red-400 ring-2 ring-red-400/20 shadow-md' : 'border-gray-200/80 shadow-sm' }} hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Rejected Requests</span>
                <div class="w-9 h-9 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center text-sm font-bold group-hover:scale-110 transition">
                    <i class="fa-solid fa-ban"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-gray-900 tracking-tight">{{ $counts['rejected'] ?? 0 }}</div>
            <div class="text-[11px] font-semibold text-red-500 mt-1">
                Mismatched / Unclear Docs
            </div>
        </a>

        <!-- Total Card -->
        <a href="{{ route('admin.kyc.index', ['status' => 'all']) }}" class="p-5 rounded-3xl bg-white border {{ request('status') === 'all' ? 'border-blue-400 ring-2 ring-blue-400/20 shadow-md' : 'border-gray-200/80 shadow-sm' }} hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">All Submissions</span>
                <div class="w-9 h-9 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold group-hover:scale-110 transition">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-gray-900 tracking-tight">{{ $counts['all'] ?? 0 }}</div>
            <div class="text-[11px] font-semibold text-blue-600 mt-1">
                Total KYC Registered
            </div>
        </a>
    </div>

    <!-- Filters & Live Search Toolbar -->
    <div class="bg-white p-4 rounded-3xl border border-gray-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.kyc.index') }}" class="w-full md:w-96 relative">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, phone, PAN, PIN..." class="w-full pl-9 pr-8 py-2 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
            @if(request('search'))
                <a href="{{ route('admin.kyc.index', ['status' => request('status')]) }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-xs" title="Clear search">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </form>

        <!-- Segmented Status Tabs -->
        <div class="flex flex-wrap items-center bg-gray-100 p-1 rounded-2xl gap-1 w-full md:w-auto">
            <a href="{{ route('admin.kyc.index', array_merge(request()->except('page', 'status'), ['status' => 'pending'])) }}" 
               class="px-3 py-1.5 text-xs font-bold rounded-xl transition flex items-center gap-1.5 {{ request('status', 'pending') === 'pending' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                Pending ({{ $counts['pending'] ?? 0 }})
            </a>
            <a href="{{ route('admin.kyc.index', array_merge(request()->except('page', 'status'), ['status' => 'approved'])) }}" 
               class="px-3 py-1.5 text-xs font-bold rounded-xl transition flex items-center gap-1.5 {{ request('status') === 'approved' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                Approved ({{ $counts['approved'] ?? 0 }})
            </a>
            <a href="{{ route('admin.kyc.index', array_merge(request()->except('page', 'status'), ['status' => 'rejected'])) }}" 
               class="px-3 py-1.5 text-xs font-bold rounded-xl transition flex items-center gap-1.5 {{ request('status') === 'rejected' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                Rejected ({{ $counts['rejected'] ?? 0 }})
            </a>
            <a href="{{ route('admin.kyc.index', array_merge(request()->except('page', 'status'), ['status' => 'all'])) }}" 
               class="px-3 py-1.5 text-xs font-bold rounded-xl transition flex items-center gap-1.5 {{ request('status') === 'all' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                All ({{ $counts['all'] ?? 0 }})
            </a>
        </div>
    </div>

    <!-- Main KYC Table -->
    <div class="bg-white rounded-3xl border border-gray-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50/80 text-[11px] font-black uppercase tracking-wider text-gray-500 border-b border-gray-200">
                        <th class="px-5 py-4">Seller & Business</th>
                        <th class="px-5 py-4">Identity & Tax Proof</th>
                        <th class="px-5 py-4">Registered Address</th>
                        <th class="px-5 py-4">Pickup Warehouse</th>
                        <th class="px-5 py-4">Documents</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($kycs as $kyc)
                        <tr class="hover:bg-blue-50/20 transition-colors group">
                            
                            <!-- Col 1: Seller Details with Initials Avatar -->
                            <td class="px-5 py-4 align-top">
                                <div class="flex items-start gap-3">
                                    @php
                                        $initials = collect(explode(' ', $kyc->user->name))->map(fn($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('');
                                        $initials = $initials ?: 'S';
                                    @endphp
                                    <div class="w-9 h-9 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-600 text-white font-extrabold text-xs flex items-center justify-center shrink-0 shadow-sm">
                                        {{ $initials }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-extrabold text-gray-900 text-sm leading-tight flex items-center gap-1.5">
                                            <span>{{ $kyc->user->name }}</span>
                                            <span class="text-[10px] font-mono text-gray-400 bg-gray-100 px-1.5 py-0.2 rounded font-normal">#{{ $kyc->user->id }}</span>
                                        </div>
                                        <div class="text-[11px] text-gray-500 truncate mt-0.5">{{ $kyc->user->email }}</div>
                                        @if($kyc->user->phone)
                                            <div class="text-[11px] font-semibold text-gray-700 mt-0.5 flex items-center gap-1">
                                                <i class="fa-solid fa-phone text-[9px] text-gray-400"></i>
                                                <span>{{ $kyc->user->phone }}</span>
                                            </div>
                                        @endif
                                        @if($kyc->user->company_name)
                                            <div class="inline-flex items-center gap-1 text-[10px] font-bold text-indigo-700 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md mt-1 truncate max-w-[200px]" title="{{ $kyc->user->company_name }}">
                                                <i class="fa-solid fa-building text-[9px]"></i>
                                                <span class="truncate">{{ $kyc->user->company_name }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Col 2: Identity & Tax Proof -->
                            <td class="px-5 py-4 align-top space-y-1.5">
                                <div>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wide bg-blue-100 text-blue-800 border border-blue-200">
                                        {{ $kyc->business_type }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-800">
                                    <span class="font-semibold text-gray-500 text-[11px]">{{ $kyc->document_type }}:</span>
                                    <span class="font-mono font-bold text-gray-900 ml-1">{{ $kyc->document_number }}</span>
                                </div>
                                <div class="text-xs">
                                    <span class="font-semibold text-gray-500 text-[11px]">PAN:</span>
                                    <span class="font-mono font-bold text-gray-900 bg-gray-100 px-1.5 py-0.5 rounded ml-1">{{ $kyc->pan_number }}</span>
                                </div>
                                @if($kyc->gst_number)
                                    <div class="text-xs">
                                        <span class="font-semibold text-gray-500 text-[11px]">GST:</span>
                                        <span class="font-mono font-bold text-purple-700 bg-purple-50 px-1.5 py-0.5 rounded ml-1">{{ $kyc->gst_number }}</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Col 3: Registered Address (High Contrast) -->
                            <td class="px-5 py-4 align-top">
                                @if($kyc->user->company_address || $kyc->user->company_pincode)
                                    <div class="max-w-xs space-y-1">
                                        <div class="text-xs text-gray-800 font-semibold leading-relaxed whitespace-normal flex items-start gap-1.5">
                                            <i class="fa-solid fa-location-dot text-red-500 text-[11px] mt-0.5 shrink-0"></i>
                                            <span>{{ $kyc->user->company_address ?: 'Street not specified' }}</span>
                                        </div>
                                        <div class="text-[11px] font-bold text-gray-900 pl-4">
                                            <span>{{ $kyc->user->company_city ? $kyc->user->company_city . ', ' : '' }}{{ $kyc->user->company_state }}</span>
                                            @if($kyc->user->company_pincode)
                                                <span class="inline-block px-1.5 py-0.2 bg-red-100 text-red-700 border border-red-200 font-mono font-black rounded ml-1">
                                                    PIN: {{ $kyc->user->company_pincode }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="text-[11px] text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-lg inline-flex items-center gap-1 font-semibold">
                                        <i class="fa-solid fa-circle-exclamation text-amber-500"></i> No address provided
                                    </div>
                                @endif
                            </td>

                            <!-- Col 4: Pickup Warehouse -->
                            <td class="px-5 py-4 align-top">
                                @php
                                    $defaultWh = $kyc->user->warehouses->where('is_default', true)->first() ?? $kyc->user->warehouses->first();
                                    $whCount = $kyc->user->warehouses->count();
                                @endphp

                                @if($defaultWh)
                                    <div class="space-y-1">
                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 font-bold text-xs shadow-sm">
                                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                                            <span class="truncate max-w-[140px]" title="{{ $defaultWh->name }}">{{ $defaultWh->name }}</span>
                                        </div>
                                        <div class="text-[11px] text-gray-600 font-medium pl-1">
                                            {{ $defaultWh->city }} • <span class="font-mono font-bold">{{ $defaultWh->pincode }}</span>
                                        </div>
                                        @if($whCount > 1)
                                            <div class="text-[10px] text-gray-400 font-semibold pl-1">+{{ $whCount - 1 }} additional facility</div>
                                        @endif
                                    </div>
                                @else
                                    <div class="space-y-2">
                                        <div>
                                            <span class="px-2.5 py-1 bg-amber-50 border border-amber-200 text-amber-800 rounded-lg text-[10px] font-extrabold inline-flex items-center gap-1">
                                                <i class="fa-solid fa-triangle-exclamation text-amber-500"></i> No Facility
                                            </span>
                                        </div>
                                        <button type="button" @click="openAddWarehouse({
                                            id: {{ $kyc->id }},
                                            user_name: '{{ addslashes($kyc->user->name) }}',
                                            company_name: '{{ addslashes($kyc->user->company_name ?? '') }}',
                                            phone: '{{ addslashes($kyc->user->phone ?? '') }}',
                                            address: '{{ addslashes($kyc->user->company_address ?? '') }}',
                                            city: '{{ addslashes($kyc->user->company_city ?? '') }}',
                                            state: '{{ addslashes($kyc->user->company_state ?? '') }}',
                                            pincode: '{{ addslashes($kyc->user->company_pincode ?? '') }}'
                                        })" class="px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-[11px] font-bold shadow-sm transition flex items-center gap-1">
                                            <i class="fa-solid fa-plus text-[9px]"></i> Add Warehouse
                                        </button>
                                    </div>
                                @endif
                            </td>

                            <!-- Col 5: Documents Chips -->
                            <td class="px-5 py-4 align-top">
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex flex-wrap gap-1">
                                        @if($kyc->id_front_path)
                                            <button @click="openDocPreview('{{ asset('storage/' . $kyc->id_front_path) }}', '{{ $kyc->user->name }} - ID Front')" class="px-2 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-md text-[10px] font-bold hover:bg-blue-100 transition shadow-2xs flex items-center gap-1">
                                                <i class="fa-solid fa-id-card"></i> ID Front
                                            </button>
                                        @endif
                                        @if($kyc->id_back_path)
                                            <button @click="openDocPreview('{{ asset('storage/' . $kyc->id_back_path) }}', '{{ $kyc->user->name }} - ID Back')" class="px-2 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-md text-[10px] font-bold hover:bg-blue-100 transition shadow-2xs flex items-center gap-1">
                                                <i class="fa-solid fa-id-card"></i> ID Back
                                            </button>
                                        @endif
                                    </div>
                                    <div class="flex flex-wrap gap-1">
                                        @if($kyc->pan_doc_path)
                                            <button @click="openDocPreview('{{ asset('storage/' . $kyc->pan_doc_path) }}', '{{ $kyc->user->name }} - PAN Card')" class="px-2 py-1 bg-purple-50 text-purple-700 border border-purple-200 rounded-md text-[10px] font-bold hover:bg-purple-100 transition shadow-2xs flex items-center gap-1">
                                                <i class="fa-solid fa-file-invoice"></i> PAN Doc
                                            </button>
                                        @endif
                                        @if($kyc->gst_doc_path)
                                            <button @click="openDocPreview('{{ asset('storage/' . $kyc->gst_doc_path) }}', '{{ $kyc->user->name }} - GST Certificate')" class="px-2 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-md text-[10px] font-bold hover:bg-emerald-100 transition shadow-2xs flex items-center gap-1">
                                                <i class="fa-solid fa-file-shield"></i> GST Doc
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Col 6: Status -->
                            <td class="px-5 py-4 align-top">
                                @if($kyc->status === 'approved')
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-full text-xs font-bold inline-flex items-center gap-1.5 shadow-2xs">
                                        <i class="fa-solid fa-circle-check text-emerald-600"></i> Approved
                                    </span>
                                @elseif($kyc->status === 'pending')
                                    <span class="px-3 py-1 bg-amber-100 text-amber-800 border border-amber-200 rounded-full text-xs font-bold inline-flex items-center gap-1.5 shadow-2xs">
                                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-800 border border-red-200 rounded-full text-xs font-bold inline-flex items-center gap-1.5 shadow-2xs">
                                        <i class="fa-solid fa-circle-xmark text-red-600"></i> Rejected
                                    </span>
                                    @if($kyc->rejection_reason)
                                        <div class="text-[10px] text-red-600 font-medium mt-1 max-w-[160px] truncate" title="{{ $kyc->rejection_reason }}">
                                            {{ $kyc->rejection_reason }}
                                        </div>
                                    @endif
                                @endif
                            </td>

                            <!-- Col 7: Actions Group -->
                            <td class="px-5 py-4 align-top text-right">
                                @php
                                    $kycData = [
                                        'kyc_id' => $kyc->id,
                                        'name' => $kyc->user->name,
                                        'email' => $kyc->user->email,
                                        'phone' => $kyc->user->phone,
                                        'company' => $kyc->user->company_name,
                                        'address' => $kyc->user->company_address,
                                        'city' => $kyc->user->company_city,
                                        'state' => $kyc->user->company_state,
                                        'pincode' => $kyc->user->company_pincode,
                                        'business_type' => $kyc->business_type,
                                        'document_type' => $kyc->document_type,
                                        'document_number' => $kyc->document_number,
                                        'pan' => $kyc->pan_number,
                                        'gst' => $kyc->gst_number,
                                        'bank_name' => $kyc->user->bank_name,
                                        'account_number' => $kyc->user->account_number,
                                        'ifsc' => $kyc->user->ifsc_code,
                                        'account_holder' => $kyc->user->account_holder_name,
                                        'id_front' => $kyc->id_front_path ? asset('storage/' . $kyc->id_front_path) : null,
                                        'id_back' => $kyc->id_back_path ? asset('storage/' . $kyc->id_back_path) : null,
                                        'pan_doc' => $kyc->pan_doc_path ? asset('storage/' . $kyc->pan_doc_path) : null,
                                        'gst_doc' => $kyc->gst_doc_path ? asset('storage/' . $kyc->gst_doc_path) : null,
                                        'status' => $kyc->status,
                                        'warehouses' => $kyc->user->warehouses->map(fn($w) => [
                                            'name' => $w->name,
                                            'city' => $w->city,
                                            'state' => $w->state,
                                            'pincode' => $w->pincode,
                                            'address' => $w->address,
                                            'is_default' => $w->is_default
                                        ])
                                    ];
                                @endphp

                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- View Details Button -->
                                    <button @click="kycDetails = JSON.parse(decodeURIComponent('{{ rawurlencode(json_encode($kycData)) }}')); detailsModal = true" type="button" class="px-2.5 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-xl text-xs font-bold transition flex items-center gap-1 shadow-2xs" title="View complete details">
                                        <i class="fa-solid fa-eye text-indigo-600"></i> Details
                                    </button>

                                    <!-- Quick Add Warehouse Button -->
                                    <button type="button" @click="openAddWarehouse({
                                        id: {{ $kyc->id }},
                                        user_name: '{{ addslashes($kyc->user->name) }}',
                                        company_name: '{{ addslashes($kyc->user->company_name ?? '') }}',
                                        phone: '{{ addslashes($kyc->user->phone ?? '') }}',
                                        address: '{{ addslashes($kyc->user->company_address ?? '') }}',
                                        city: '{{ addslashes($kyc->user->company_city ?? '') }}',
                                        state: '{{ addslashes($kyc->user->company_state ?? '') }}',
                                        pincode: '{{ addslashes($kyc->user->company_pincode ?? '') }}'
                                    })" class="px-2.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-xl text-xs font-bold transition flex items-center gap-1 shadow-2xs" title="Assign warehouse">
                                        <i class="fa-solid fa-warehouse"></i> + Facility
                                    </button>
                                    
                                    <!-- Approve Button -->
                                    @if($kyc->status !== 'approved')
                                        <form action="{{ route('admin.kyc.approve', $kyc->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Approve KYC for {{ addslashes($kyc->user->name) }}? A default pickup warehouse will be created automatically.')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1 shadow-sm">
                                                <i class="fa-solid fa-check"></i> Approve
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Reject Button -->
                                    @if($kyc->status !== 'rejected')
                                        <button @click="rejectUrl = '{{ route('admin.kyc.reject', $kyc->id) }}'; rejectionReason = ''; showRejectModal = true" class="px-2.5 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 rounded-xl text-xs font-bold transition flex items-center gap-1 shadow-2xs" title="Reject with reason">
                                            <i class="fa-solid fa-xmark"></i> Reject
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center text-gray-400 bg-gray-50/50">
                                <div class="w-16 h-16 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3 text-2xl">
                                    <i class="fa-solid fa-shield-cat"></i>
                                </div>
                                <h3 class="text-sm font-extrabold text-gray-800">No KYC Verification Requests Found</h3>
                                <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">There are no verification requests matching your selected filters or search terms.</p>
                                <div class="mt-4">
                                    <a href="{{ route('admin.kyc.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition">
                                        View All Submissions
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kycs->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/80">
                {{ $kycs->links() }}
            </div>
        @endif
    </div>

    <!-- ================= MODALS ================= -->

    <!-- 1. Add Warehouse Modal -->
    <div x-show="warehouseModal" style="display: none;" class="fixed inset-0 z-[80] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 text-center">
            <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-xs transition-opacity" @click="warehouseModal = false"></div>
            
            <div class="inline-block bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all max-w-lg w-full p-6 relative z-10 border border-gray-100 my-8">
                <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-700 flex items-center justify-center text-lg shadow-sm">
                            <i class="fa-solid fa-warehouse"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-gray-900">Add Pickup Facility</h3>
                            <p class="text-xs text-gray-500">For Merchant: <span class="font-extrabold text-indigo-700" x-text="warehouseForm.seller_name"></span></p>
                        </div>
                    </div>
                    <button @click="warehouseModal = false" class="w-8 h-8 rounded-full bg-gray-100 text-gray-400 hover:text-gray-700 hover:bg-gray-200 flex items-center justify-center transition"><i class="fa-solid fa-xmark"></i></button>
                </div>
                
                <form :action="warehouseForm.action" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1">Facility / Warehouse Name <span class="text-red-500">*</span></label>
                        <input type="text" name="warehouse_name" x-model="warehouseForm.warehouse_name" required class="w-full border border-gray-300 rounded-xl px-3.5 py-2 text-xs font-bold text-gray-900 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1">Contact Person <span class="text-red-500">*</span></label>
                            <input type="text" name="contact_person" x-model="warehouseForm.contact_person" required class="w-full border border-gray-300 rounded-xl px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1">Phone Number <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" x-model="warehouseForm.phone" required class="w-full border border-gray-300 rounded-xl px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1">Complete Pickup Address <span class="text-red-500">*</span></label>
                        <textarea name="address" x-model="warehouseForm.address" rows="2" required placeholder="Premises, Street, Area, Landmark" class="w-full border border-gray-300 rounded-xl p-3 text-xs text-gray-900 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition"></textarea>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1">City <span class="text-red-500">*</span></label>
                            <input type="text" name="city" x-model="warehouseForm.city" required class="w-full border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-900 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1">State <span class="text-red-500">*</span></label>
                            <input type="text" name="state" x-model="warehouseForm.state" required class="w-full border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-900 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1">Pincode <span class="text-red-500">*</span></label>
                            <input type="text" maxlength="6" name="pincode" x-model="warehouseForm.pincode" required class="w-full border border-gray-300 rounded-xl px-3 py-2 text-xs font-mono font-bold text-gray-900 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition">
                        </div>
                    </div>
                    <div class="flex items-center gap-2 pt-1">
                        <input type="checkbox" name="is_default" value="1" id="wh_default_cb" checked class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                        <label for="wh_default_cb" class="text-xs text-gray-700 font-bold">Set as Primary Default Warehouse for consignments</label>
                    </div>
                    
                    <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
                        <button type="button" @click="warehouseModal = false" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl text-xs font-bold hover:bg-gray-50 transition">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 shadow-md transition">
                            <i class="fa-solid fa-save mr-1"></i> Save & Assign Warehouse
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 2. Rejection Modal with Preset Reasons -->
    <div x-show="showRejectModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 text-center">
            <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-xs transition-opacity" @click="showRejectModal = false"></div>
            
            <div class="inline-block bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all max-w-md w-full p-6 relative z-10 border border-gray-100 my-8">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-ban"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-gray-900">Reject KYC Verification</h3>
                        <p class="text-xs text-gray-500">Provide a clear reason so the seller can rectify their documents.</p>
                    </div>
                </div>

                <!-- Reason Presets -->
                <div class="mb-3 space-y-1.5">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Quick Reason Presets:</label>
                    <div class="flex flex-wrap gap-1.5">
                        <button type="button" @click="setRejectReason('Uploaded Aadhaar card image is blurred or illegible.')" class="px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md text-[10px] font-semibold transition">Blurred Aadhaar</button>
                        <button type="button" @click="setRejectReason('PAN Card details do not match the registered seller name.')" class="px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md text-[10px] font-semibold transition">PAN Mismatch</button>
                        <button type="button" @click="setRejectReason('Complete address and postal pincode are missing.')" class="px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md text-[10px] font-semibold transition">Incomplete Address</button>
                        <button type="button" @click="setRejectReason('Bank details (Account No / IFSC) could not be verified.')" class="px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md text-[10px] font-semibold transition">Bank Unverified</button>
                    </div>
                </div>
                
                <form :action="rejectUrl" method="POST">
                    @csrf
                    <textarea name="rejection_reason" x-model="rejectionReason" rows="3" required placeholder="Specify custom rejection reason..." class="w-full border border-gray-300 rounded-2xl p-3 text-xs text-gray-900 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition mb-4"></textarea>
                    
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="showRejectModal = false" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl text-xs font-bold hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 bg-red-600 text-white rounded-xl text-xs font-bold hover:bg-red-700 shadow-md transition">Confirm Rejection</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 3. Document Lightbox Preview Modal -->
    <div x-show="docModal" style="display: none;" class="fixed inset-0 z-[90] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 text-center">
            <div class="fixed inset-0 bg-gray-950/85 backdrop-blur-sm" @click="docModal = false"></div>
            
            <div class="inline-block bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all max-w-3xl w-full p-4 relative z-10 border border-gray-800 my-8">
                <div class="flex justify-between items-center mb-3 px-3">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-file-image text-indigo-600"></i>
                        <h4 class="font-extrabold text-gray-900 text-sm" x-text="docTitle || 'Document Preview'"></h4>
                    </div>
                    <div class="flex items-center gap-2">
                        <a :href="docUrl" target="_blank" download class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-bold transition flex items-center gap-1">
                            <i class="fa-solid fa-arrow-down"></i> Open Original
                        </a>
                        <button @click="docModal = false" class="w-7 h-7 rounded-full bg-gray-100 text-gray-500 hover:text-gray-800 flex items-center justify-center transition"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                </div>
                <div class="bg-gray-950 rounded-2xl overflow-hidden flex items-center justify-center min-h-[300px] max-h-[75vh] p-3 border border-gray-800">
                    <img :src="docUrl" class="max-h-[70vh] object-contain rounded-xl select-none" alt="Document Preview">
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Full Comprehensive Details Modal -->
    <div x-show="detailsModal" style="display: none;" class="fixed inset-0 z-[70] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 text-center">
            <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-xs" @click="detailsModal = false"></div>
            
            <div class="inline-block bg-white rounded-3xl text-left shadow-2xl transform transition-all max-w-4xl w-full relative z-[75] my-8 flex flex-col max-h-[90vh] overflow-hidden border border-gray-100">
                <!-- Modal Header -->
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 shrink-0 bg-gray-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-lg shadow-sm">
                            <i class="fa-solid fa-id-card-clip"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-gray-900 text-lg">Merchant Verification 360°</h3>
                            <p class="text-xs text-gray-500" x-text="'Seller Account #' + (kycDetails ? kycDetails.kyc_id : '')"></p>
                        </div>
                    </div>
                    <button @click="detailsModal = false" class="w-8 h-8 rounded-full bg-gray-200/80 text-gray-500 hover:text-gray-800 flex items-center justify-center transition"><i class="fa-solid fa-xmark"></i></button>
                </div>
                
                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto flex-1 space-y-6" x-show="kycDetails">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Card 1: Seller Profile -->
                        <div class="bg-gray-50/80 rounded-2xl p-5 border border-gray-200/80 space-y-3">
                            <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest pb-2 border-b border-gray-200 flex items-center gap-1.5">
                                <i class="fa-solid fa-user text-indigo-600"></i> Profile & Business Details
                            </h4>
                            <div class="grid grid-cols-2 gap-3 text-xs">
                                <div><span class="text-gray-500 block text-[10px] uppercase font-bold">Seller Name</span><div class="font-extrabold text-gray-900 text-sm mt-0.5" x-text="kycDetails.name"></div></div>
                                <div><span class="text-gray-500 block text-[10px] uppercase font-bold">Contact Phone</span><div class="font-bold text-gray-900 mt-0.5" x-text="kycDetails.phone || 'N/A'"></div></div>
                                <div><span class="text-gray-500 block text-[10px] uppercase font-bold">Email Address</span><div class="font-medium text-gray-700 truncate mt-0.5" x-text="kycDetails.email"></div></div>
                                <div><span class="text-gray-500 block text-[10px] uppercase font-bold">Company / Brand</span><div class="font-bold text-indigo-700 mt-0.5" x-text="kycDetails.company || 'N/A'"></div></div>
                                <div><span class="text-gray-500 block text-[10px] uppercase font-bold">Business Structure</span><div class="font-bold text-gray-800 mt-0.5" x-text="kycDetails.business_type"></div></div>
                                <div>
                                    <span class="text-gray-500 block text-[10px] uppercase font-bold">Current Status</span>
                                    <div class="mt-1">
                                        <span x-show="kycDetails.status === 'approved'" class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 rounded-md text-[10px] font-bold"><i class="fa-solid fa-check mr-1"></i> Approved</span>
                                        <span x-show="kycDetails.status === 'pending'" class="px-2.5 py-0.5 bg-amber-100 text-amber-800 rounded-md text-[10px] font-bold"><i class="fa-solid fa-clock mr-1"></i> Pending</span>
                                        <span x-show="kycDetails.status === 'rejected'" class="px-2.5 py-0.5 bg-red-100 text-red-800 rounded-md text-[10px] font-bold"><i class="fa-solid fa-xmark mr-1"></i> Rejected</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Identity & Tax -->
                        <div class="bg-gray-50/80 rounded-2xl p-5 border border-gray-200/80 space-y-3">
                            <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest pb-2 border-b border-gray-200 flex items-center gap-1.5">
                                <i class="fa-solid fa-fingerprint text-purple-600"></i> Tax & Identity Numbers
                            </h4>
                            <div class="grid grid-cols-2 gap-3 text-xs">
                                <div>
                                    <span class="text-gray-500 block text-[10px] uppercase font-bold" x-text="kycDetails.document_type + ' Number'"></span>
                                    <div class="font-mono font-black text-gray-900 text-sm mt-0.5" x-text="kycDetails.document_number"></div>
                                </div>
                                <div>
                                    <span class="text-gray-500 block text-[10px] uppercase font-bold">PAN Card Number</span>
                                    <div class="font-mono font-black text-gray-900 text-sm mt-0.5 uppercase" x-text="kycDetails.pan"></div>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-gray-500 block text-[10px] uppercase font-bold">GSTIN Number</span>
                                    <div class="font-mono font-black text-purple-700 text-sm mt-0.5 uppercase" x-text="kycDetails.gst || 'Not Provided (Unregistered)'"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Registered Address & Pickup Warehouses -->
                        <div class="bg-gray-50/80 rounded-2xl p-5 border border-gray-200/80 md:col-span-2 space-y-3">
                            <div class="flex justify-between items-center pb-2 border-b border-gray-200">
                                <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest flex items-center gap-1.5">
                                    <i class="fa-solid fa-location-dot text-red-500"></i> Registered Address & Pickup Facilities
                                </h4>
                                <button type="button" @click="detailsModal = false; openAddWarehouse({
                                    id: kycDetails.kyc_id,
                                    user_name: kycDetails.name,
                                    company_name: kycDetails.company,
                                    phone: kycDetails.phone,
                                    address: kycDetails.address,
                                    city: kycDetails.city,
                                    state: kycDetails.state,
                                    pincode: kycDetails.pincode
                                })" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1">
                                    <i class="fa-solid fa-plus text-[10px]"></i> Add Facility
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-white p-4 rounded-xl border border-gray-200 space-y-1">
                                    <div class="text-[10px] font-black text-gray-400 uppercase">Registered Address</div>
                                    <div class="font-bold text-gray-900 text-xs leading-relaxed" x-text="kycDetails.address || 'No address provided'"></div>
                                    <div class="text-xs text-gray-800 font-extrabold mt-1">
                                        <span x-text="kycDetails.city ? kycDetails.city + ', ' : ''"></span>
                                        <span x-text="kycDetails.state ? kycDetails.state + ' ' : ''"></span>
                                        <span class="font-mono text-red-600 font-black" x-text="kycDetails.pincode ? ('• PIN: ' + kycDetails.pincode) : ''"></span>
                                    </div>
                                </div>

                                <div class="bg-white p-4 rounded-xl border border-gray-200 space-y-1.5">
                                    <div class="text-[10px] font-black text-gray-400 uppercase">Configured Facilities</div>
                                    <template x-if="kycDetails.warehouses && kycDetails.warehouses.length > 0">
                                        <div class="space-y-2 max-h-32 overflow-y-auto pr-1">
                                            <template x-for="wh in kycDetails.warehouses" :key="wh.name">
                                                <div class="p-2 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-between">
                                                    <div>
                                                        <div class="font-bold text-gray-900 text-xs flex items-center gap-1.5">
                                                            <i class="fa-solid fa-warehouse text-indigo-500 text-[10px]"></i>
                                                            <span x-text="wh.name"></span>
                                                        </div>
                                                        <div class="text-[11px] text-gray-500" x-text="wh.city + ' - PIN: ' + wh.pincode"></div>
                                                    </div>
                                                    <span x-show="wh.is_default" class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[9px] font-extrabold rounded-full">Default</span>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="!kycDetails.warehouses || kycDetails.warehouses.length === 0">
                                        <div class="text-xs text-amber-700 bg-amber-50 p-3 rounded-lg border border-amber-200 font-semibold flex items-center gap-2">
                                            <i class="fa-solid fa-circle-exclamation text-amber-500"></i> No pickup facility added yet.
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Card 4: Bank Details -->
                        <div class="bg-gray-50/80 rounded-2xl p-5 border border-gray-200/80 md:col-span-2 space-y-3">
                            <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest pb-2 border-b border-gray-200 flex items-center gap-1.5">
                                <i class="fa-solid fa-building-columns text-emerald-600"></i> Bank Account for Remittance (COD)
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                                <div><span class="text-gray-500 block text-[10px] uppercase font-bold">Bank Name</span><div class="font-bold text-gray-900 mt-0.5" x-text="kycDetails.bank_name || 'N/A'"></div></div>
                                <div><span class="text-gray-500 block text-[10px] uppercase font-bold">Beneficiary Name</span><div class="font-bold text-gray-900 mt-0.5" x-text="kycDetails.account_holder || 'N/A'"></div></div>
                                <div><span class="text-gray-500 block text-[10px] uppercase font-bold">Account Number</span><div class="font-mono font-black text-gray-900 mt-0.5" x-text="kycDetails.account_number || 'N/A'"></div></div>
                                <div><span class="text-gray-500 block text-[10px] uppercase font-bold">IFSC Code</span><div class="font-mono font-black text-gray-900 uppercase mt-0.5" x-text="kycDetails.ifsc || 'N/A'"></div></div>
                            </div>
                        </div>

                        <!-- Card 5: Documents Gallery -->
                        <div class="md:col-span-2 space-y-3">
                            <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest pb-2 border-b border-gray-200 flex items-center gap-1.5">
                                <i class="fa-solid fa-images text-blue-600"></i> Uploaded Documents Gallery
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <template x-if="kycDetails.id_front">
                                    <div class="border border-gray-200 rounded-2xl p-3 bg-white flex flex-col items-center hover:shadow-md transition">
                                        <div class="text-[10px] font-extrabold text-gray-500 mb-2 uppercase">ID Card Front</div>
                                        <img :src="kycDetails.id_front" class="h-28 object-contain cursor-pointer hover:scale-105 transition rounded-lg" @click="openDocPreview(kycDetails.id_front, 'ID Front')" alt="ID Front">
                                    </div>
                                </template>
                                <template x-if="kycDetails.id_back">
                                    <div class="border border-gray-200 rounded-2xl p-3 bg-white flex flex-col items-center hover:shadow-md transition">
                                        <div class="text-[10px] font-extrabold text-gray-500 mb-2 uppercase">ID Card Back</div>
                                        <img :src="kycDetails.id_back" class="h-28 object-contain cursor-pointer hover:scale-105 transition rounded-lg" @click="openDocPreview(kycDetails.id_back, 'ID Back')" alt="ID Back">
                                    </div>
                                </template>
                                <template x-if="kycDetails.pan_doc">
                                    <div class="border border-gray-200 rounded-2xl p-3 bg-white flex flex-col items-center hover:shadow-md transition">
                                        <div class="text-[10px] font-extrabold text-gray-500 mb-2 uppercase">PAN Card Document</div>
                                        <img :src="kycDetails.pan_doc" class="h-28 object-contain cursor-pointer hover:scale-105 transition rounded-lg" @click="openDocPreview(kycDetails.pan_doc, 'PAN Card')" alt="PAN Card">
                                    </div>
                                </template>
                                <template x-if="kycDetails.gst_doc">
                                    <div class="border border-gray-200 rounded-2xl p-3 bg-white flex flex-col items-center hover:shadow-md transition">
                                        <div class="text-[10px] font-extrabold text-gray-500 mb-2 uppercase">GST Certificate</div>
                                        <img :src="kycDetails.gst_doc" class="h-28 object-contain cursor-pointer hover:scale-105 transition rounded-lg" @click="openDocPreview(kycDetails.gst_doc, 'GST Certificate')" alt="GST">
                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50/80 rounded-b-3xl shrink-0">
                    <button @click="detailsModal = false" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-xl text-xs font-bold hover:bg-gray-100 transition shadow-sm">
                        Close Inspector
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
