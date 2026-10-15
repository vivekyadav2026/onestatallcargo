@extends('layouts.admin')

@section('title', 'Edit Franchise - ' . $franchise->company_name)

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] p-5 flex items-center justify-between">
        <div class="flex items-center gap-3.5">
            <a href="{{ route('admin.hubs.index') }}" class="w-8 h-8 rounded-lg bg-gray-50 hover:bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-500 hover:text-gray-900 transition text-xs shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="text-xl font-bold text-gray-900 tracking-tight">{{ $franchise->company_name }}</h1>
                    <span class="px-2.5 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider {{ $franchise->isState() ? 'bg-purple-50 text-purple-700 border border-purple-100' : 'bg-blue-50 text-blue-700 border border-blue-100' }}">
                        {{ strtoupper($franchise->franchise_level) }} FRANCHISE
                    </span>
                    <span class="px-2.5 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider {{ $franchise->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                        {{ $franchise->status }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-0.5">Configure territory, parent hierarchy, commission settings, and operating status</p>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.franchises.overview', $franchise->id) }}" class="px-3.5 py-2 rounded-lg text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition inline-flex items-center gap-1.5">
                <i class="fa-solid fa-chart-pie text-[11px]"></i> 360° Overview
            </a>
        </div>
    </div>

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

    <div class="bg-white rounded-2xl p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-gray-200">
        <form action="{{ route('admin.franchises.update', $franchise->id) }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <!-- Basic Info -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-4 pb-2 border-b border-gray-100">
                        General & Contact Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Company / Trading Name</label>
                            <input type="text" name="company_name" value="{{ old('company_name', $franchise->company_name) }}" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs focus:border-blue-600 focus:bg-white outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Owner / Manager Name</label>
                            <input type="text" name="owner_name" value="{{ old('owner_name', $franchise->owner_name) }}" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs focus:border-blue-600 focus:bg-white outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $franchise->phone) }}" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs focus:border-blue-600 focus:bg-white outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $franchise->email) }}" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs focus:border-blue-600 focus:bg-white outline-none">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Reset Password (Optional)</label>
                            <input type="password" name="password" placeholder="Leave blank to keep existing password unchanged" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs focus:border-blue-600 focus:bg-white outline-none">
                            <p class="text-[10px] text-gray-400 mt-1">Enter a new password (min. 6 characters) only if you want to reset the login credentials for this franchise account.</p>
                        </div>
                    </div>
                </div>

                <!-- Territory & Hierarchy -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-4 pb-2 border-b border-gray-100">
                        Territory & Network Hierarchy
                    </h3>
                    
                    @if($franchise->isDistrict())
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Parent State Franchise</label>
                                <select name="parent_franchise_id" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs focus:border-blue-600 focus:bg-white outline-none">
                                    <option value="">-- Select Parent State Franchise --</option>
                                    @foreach($stateFranchises as $stateF)
                                        <option value="{{ $stateF->id }}" {{ old('parent_franchise_id', $franchise->parent_franchise_id) == $stateF->id ? 'selected' : '' }}>
                                            {{ $stateF->company_name }} ({{ $stateF->state }})
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-[10px] text-gray-400 mt-1">State is inherited from the parent State Franchise.</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">District Name</label>
                                <input type="text" name="district" value="{{ old('district', $franchise->district) }}" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs focus:border-blue-600 focus:bg-white outline-none">
                            </div>
                        </div>
                    @else
                        <div class="p-3.5 bg-purple-50/50 rounded-xl border border-purple-100 mb-4">
                            <div class="flex items-center gap-2 font-bold text-purple-900 text-xs">
                                <i class="fa-solid fa-crown text-purple-600"></i> Top-Level State Entity
                            </div>
                            <p class="text-xs text-purple-700 mt-0.5">
                                This State Franchise oversees linehaul bag movement and collects state royalty across its subordinate district network.
                            </p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Base City / Town</label>
                            <input type="text" name="city" value="{{ old('city', $franchise->city) }}" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs focus:border-blue-600 focus:bg-white outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">State</label>
                            <input type="text" name="state" value="{{ old('state', $franchise->state) }}" required {{ $franchise->isDistrict() ? 'readonly class=bg-gray-100' : '' }} class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs focus:border-blue-600 focus:bg-white outline-none">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Office / Hub Address</label>
                            <textarea name="address" rows="2" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs focus:border-blue-600 focus:bg-white outline-none">{{ old('address', $franchise->address) }}</textarea>
                        </div>
                    </div>

                    @if($franchise->isDistrict())
                        <div class="mt-4">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Serviceable Pincodes (Comma separated)</label>
                            @php
                                $pinsString = is_array($franchise->serviceable_pincodes) ? implode(', ', $franchise->serviceable_pincodes) : $franchise->serviceable_pincodes;
                            @endphp
                            <textarea name="serviceable_pincodes" rows="3" required placeholder="302001, 302002, 302015" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs focus:border-blue-600 focus:bg-white outline-none font-mono">{{ old('serviceable_pincodes', $pinsString) }}</textarea>
                            <p class="text-[10px] text-gray-400 mt-1">Territory assignment is strictly validated: duplicate active pincodes are not allowed.</p>
                        </div>
                    @endif
                </div>

                <!-- Commission & Royalty Configuration -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-4 pb-2 border-b border-gray-100">
                        Commission & Royalty Settings
                    </h3>

                    @if($franchise->isState())
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">State Royalty Type</label>
                                <select name="state_royalty_type" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs focus:border-blue-600 focus:bg-white outline-none">
                                    <option value="percent" {{ old('state_royalty_type', $franchise->state_royalty_type) === 'percent' ? 'selected' : '' }}>Percentage (%) of Shipping Charge</option>
                                    <option value="flat" {{ old('state_royalty_type', $franchise->state_royalty_type) === 'flat' ? 'selected' : '' }}>Flat Fee per Shipment (₹)</option>
                                    <option value="per_kg" {{ old('state_royalty_type', $franchise->state_royalty_type) === 'per_kg' ? 'selected' : '' }}>Rate per Kilogram (₹/kg)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">State Royalty Rate</label>
                                <input type="number" step="0.01" min="0" name="state_royalty_rate" value="{{ old('state_royalty_rate', $franchise->state_royalty_rate) }}" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs focus:border-blue-600 focus:bg-white outline-none">
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Delivery Commission Type</label>
                                <select name="delivery_commission_type" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs focus:border-blue-600 focus:bg-white outline-none">
                                    <option value="flat" {{ old('delivery_commission_type', $franchise->delivery_commission_type) === 'flat' ? 'selected' : '' }}>Flat Fee per Delivered Packet (₹)</option>
                                    <option value="per_kg" {{ old('delivery_commission_type', $franchise->delivery_commission_type) === 'per_kg' ? 'selected' : '' }}>Rate per Kilogram (₹/kg)</option>
                                    <option value="percent" {{ old('delivery_commission_type', $franchise->delivery_commission_type) === 'percent' ? 'selected' : '' }}>Percentage (%) of Shipping Charge</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Delivery Commission Rate</label>
                                <input type="number" step="0.01" min="0" name="delivery_commission_rate" value="{{ old('delivery_commission_rate', $franchise->delivery_commission_rate) }}" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs focus:border-blue-600 focus:bg-white outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Pickup Commission Type</label>
                                <select name="pickup_commission_type" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs focus:border-blue-600 focus:bg-white outline-none">
                                    <option value="flat" {{ old('pickup_commission_type', $franchise->pickup_commission_type) === 'flat' ? 'selected' : '' }}>Flat Fee per Picked-up Packet (₹)</option>
                                    <option value="per_kg" {{ old('pickup_commission_type', $franchise->pickup_commission_type) === 'per_kg' ? 'selected' : '' }}>Rate per Kilogram (₹/kg)</option>
                                    <option value="percent" {{ old('pickup_commission_type', $franchise->pickup_commission_type) === 'percent' ? 'selected' : '' }}>Percentage (%) of Shipping Charge</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Pickup Commission Rate</label>
                                <input type="number" step="0.01" min="0" name="pickup_commission_rate" value="{{ old('pickup_commission_rate', $franchise->pickup_commission_rate) }}" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs focus:border-blue-600 focus:bg-white outline-none">
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Status -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-4 pb-2 border-b border-gray-100">
                        Operational Status
                    </h3>
                    <div class="w-full md:w-1/2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Franchise Status</label>
                        <select name="status" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs focus:border-blue-600 focus:bg-white outline-none">
                            <option value="approved" {{ old('status', $franchise->status) === 'approved' ? 'selected' : '' }}>Approved & Active</option>
                            <option value="inactive" {{ old('status', $franchise->status) === 'inactive' ? 'selected' : '' }}>Inactive / Suspended</option>
                            <option value="pending" {{ old('status', $franchise->status) === 'pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="rejected" {{ old('status', $franchise->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                </div>

                @if($franchise->isState() && $franchise->districtFranchises->count() > 0)
                    <!-- Subordinate District Franchises -->
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-4 pb-2 border-b border-gray-100">
                            Subordinate District Franchises ({{ $franchise->districtFranchises->count() }})
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($franchise->districtFranchises as $child)
                                <div class="p-3 bg-gray-50/70 rounded-xl border border-gray-200 flex items-center justify-between">
                                    <div>
                                        <div class="font-bold text-xs text-gray-900">{{ $child->company_name }}</div>
                                        <div class="text-[10px] text-gray-500">District: {{ $child->district ?? 'N/A' }} | Owner: {{ $child->owner_name }}</div>
                                        @php
                                            $cPins = is_array($child->serviceable_pincodes) ? $child->serviceable_pincodes : json_decode($child->serviceable_pincodes, true);
                                        @endphp
                                        @if(!empty($cPins) && is_array($cPins))
                                            <div class="text-[10px] text-gray-500 font-mono mt-0.5">
                                                <span class="text-gray-400 font-sans text-[9px] font-bold uppercase">PIN:</span> {{ implode(', ', array_slice($cPins, 0, 5)) }}{{ count($cPins) > 5 ? ' +' . (count($cPins) - 5) . ' more' : '' }}
                                            </div>
                                        @endif
                                    </div>
                                    <a href="{{ route('admin.franchises.edit', $child->id) }}" class="text-[11px] font-semibold text-blue-600 hover:text-blue-800">
                                        Edit <i class="fa-solid fa-arrow-right text-[9px]"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-8 pt-4 border-t border-gray-100 flex items-center justify-end gap-2.5">
                <a href="{{ route('admin.hubs.index') }}" class="px-4 py-2 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 rounded-lg bg-gray-900 hover:bg-black text-white text-xs font-semibold transition shadow-sm">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
