@extends('layouts.admin')

@section('title', 'Manage Role & Permissions - OneStall Cargo')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Manage User Permissions</h1>
            <p class="text-sm text-gray-500 mt-1">Assign system roles and update wallet balance for {{ $user->name }}.</p>
        </div>
        <a href="{{ route('admin.roles.index') }}" class="text-sm font-bold text-gray-500 hover:text-gray-900">&larr; Back to List</a>
    </div>

    @if(session('error'))
        <div class="p-4 rounded-xl font-bold bg-red-50 border border-red-500 text-red-700"><i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.roles.update', $user->id) }}" method="POST" class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        @csrf
        @method('PUT')
        
        <div class="p-6 md:p-8 space-y-8">
            
            <!-- User Info (Read-only) -->
            <div>
                <h3 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-2 mb-4 uppercase tracking-wider">User Identity</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Full Name</label>
                        <input type="text" value="{{ $user->name }}" class="w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-xl text-sm cursor-not-allowed" disabled>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Email Address</label>
                        <input type="text" value="{{ $user->email }}" class="w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-xl text-sm cursor-not-allowed" disabled>
                    </div>
                </div>
            </div>

            <!-- Role & Permissions -->
            <div>
                <h3 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-2 mb-4 uppercase tracking-wider">Access & Permissions</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">System Role *</label>
                        <select name="role" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-[var(--gold)] outline-none transition">
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Super Admin (Full Control)</option>
                            <option value="operations" {{ $user->role == 'operations' ? 'selected' : '' }}>Admin / Operations Team</option>
                            <option value="seller" {{ $user->role == 'seller' ? 'selected' : '' }}>Seller (Online/Offline)</option>
                            <option value="aggregator" {{ $user->role == 'aggregator' ? 'selected' : '' }}>Aggregator (Other Shipping Cos)</option>
                            <option value="b2b_customer" {{ $user->role == 'b2b_customer' ? 'selected' : '' }}>B2B Customer (Heavy/Cargo)</option>
                            <option value="b2c_customer" {{ $user->role == 'b2c_customer' ? 'selected' : '' }}>B2C Customer (Individual)</option>
                            <option value="franchise" {{ $user->role == 'franchise' ? 'selected' : '' }}>Franchise / Hub Partner</option>
                            <option value="pickup_rider" {{ $user->role == 'pickup_rider' ? 'selected' : '' }}>Pickup Rider</option>
                            <option value="delivery_rider" {{ $user->role == 'delivery_rider' ? 'selected' : '' }}>Delivery Rider</option>
                            <option value="courier_partner" {{ $user->role == 'courier_partner' ? 'selected' : '' }}>Courier Partner</option>
                            <option value="corporate" {{ $user->role == 'corporate' ? 'selected' : '' }}>Corporate Customer (Bulk)</option>
                        </select>
                        <p class="text-[10px] text-gray-500 mt-1">Controls which portal the user gets redirected to upon login.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Company / Hub Name</label>
                        <input type="text" name="company_name" value="{{ old('company_name', $user->company_name) }}" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-[var(--gold)] outline-none transition">
                        <p class="text-[10px] text-gray-500 mt-1">Required for Merchants and Franchises.</p>
                    </div>
                </div>
            </div>

            <!-- Granular Permissions Matrix -->
            <div>
                <h3 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-2 mb-4 uppercase tracking-wider">Granular Module Permissions</h3>
                <p class="text-xs text-gray-500 mb-4">Select which specific Admin Panel modules this user can access (only applicable if role is Operations/Admin).</p>
                
                @php
                    $availablePermissions = [
                        'view_dashboard' => 'View Dashboard & Stats',
                        'manage_shipments' => 'Manage Shipments & Bookings',
                        'manage_pickups' => 'Assign & Manage Pickups',
                        'manage_ndr' => 'Process NDR & RTO',
                        'manage_billing' => 'Billing & Wallet Recharge',
                        'manage_users' => 'Roles & Permissions',
                        'manage_kyc' => 'Approve/Reject KYC',
                        'manage_rates' => 'Configure Shipping Rates',
                        'manage_content' => 'Update Promo Banners & CMS',
                    ];
                    $userPerms = is_array($user->permissions) ? $user->permissions : [];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($availablePermissions as $key => $label)
                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                        <input type="checkbox" name="permissions[]" value="{{ $key }}" {{ in_array($key, $userPerms) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-xs font-bold text-gray-700">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Finance -->
            <div>
                <h3 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-2 mb-4 uppercase tracking-wider">Financial Settings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Wallet Balance (₹) *</label>
                        <input type="number" step="0.01" name="wallet_balance" value="{{ old('wallet_balance', $user->wallet_balance) }}" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-[var(--gold)] outline-none transition">
                        <p class="text-[10px] text-gray-500 mt-1">Merchants use this balance to book prepaid shipments.</p>
                    </div>
                </div>
            </div>

        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
            <button type="submit" class="px-6 py-3 rounded-xl text-sm font-bold bg-[#1e293b] text-white shadow-md hover:bg-black transition-colors">
                <i class="fa-solid fa-save mr-2"></i> Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
