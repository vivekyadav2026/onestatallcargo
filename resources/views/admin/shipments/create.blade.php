@extends('layouts.admin')

@section('title', 'Create Shipment - Admin OneStall Cargo')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Create Shipment</h1>
            <p class="text-sm text-gray-500 mt-1">Book a shipment on behalf of a seller or client account.</p>
        </div>
        <a href="{{ route('admin.shipments.index') }}" class="px-4 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-100 font-bold text-sm transition">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back to Shipments
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl shadow-sm" role="alert">
            <p class="font-bold mb-1">Please fix the following errors:</p>
            <ul class="list-disc pl-5 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.shipments.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Seller & Order Info -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
            <h2 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-user-tag text-[var(--gold-deep)]"></i> Seller & Order Reference
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Select Seller / Merchant <span class="text-red-500">*</span></label>
                    <select name="user_id" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[var(--gold)]">
                        <option value="">-- Choose Account --</option>
                        @foreach($sellers as $seller)
                            <option value="{{ $seller->id }}" {{ old('user_id') == $seller->id ? 'selected' : '' }}>
                                {{ $seller->name }} ({{ $seller->company_name ?: $seller->email }}) [₹{{ number_format($seller->wallet_balance ?? 0, 2) }}]
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Order ID / Reference</label>
                    <input type="text" name="order_id" value="{{ old('order_id') }}" placeholder="e.g. ORD-10294" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[var(--gold)]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Product Name / Description</label>
                    <input type="text" name="product_name" value="{{ old('product_name') }}" placeholder="e.g. Cotton Apparels" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[var(--gold)]">
                </div>
            </div>
        </div>

        <!-- Addresses Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Pickup Info -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h2 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-location-dot text-blue-600"></i> Pickup Details
                </h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pickup Pincode <span class="text-red-500">*</span></label>
                        <input type="text" name="pickup_pincode" value="{{ old('pickup_pincode') }}" required maxlength="6" placeholder="6-digit pincode" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[var(--gold)]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pickup City <span class="text-red-500">*</span></label>
                        <input type="text" name="pickup_city" value="{{ old('pickup_city') }}" required placeholder="e.g. Mumbai" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[var(--gold)]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Full Pickup Address <span class="text-red-500">*</span></label>
                        <textarea name="pickup_address" rows="3" required placeholder="Warehouse / Shop full address" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[var(--gold)]">{{ old('pickup_address') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Delivery Info -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h2 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-truck text-green-600"></i> Receiver / Delivery Details
                </h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Receiver Name <span class="text-red-500">*</span></label>
                            <input type="text" name="receiver_name" value="{{ old('receiver_name') }}" required placeholder="Full name" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[var(--gold)]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Receiver Phone <span class="text-red-500">*</span></label>
                            <input type="text" name="receiver_phone" value="{{ old('receiver_phone') }}" required placeholder="10-digit mobile" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[var(--gold)]">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Delivery Pincode <span class="text-red-500">*</span></label>
                            <input type="text" name="delivery_pincode" value="{{ old('delivery_pincode') }}" required maxlength="6" placeholder="6-digit pincode" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[var(--gold)]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Delivery City <span class="text-red-500">*</span></label>
                            <input type="text" name="delivery_city" value="{{ old('delivery_city') }}" required placeholder="e.g. Delhi" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[var(--gold)]">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Delivery Address <span class="text-red-500">*</span></label>
                        <textarea name="delivery_address" rows="3" required placeholder="House / Flat, Street, Landmark" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[var(--gold)]">{{ old('delivery_address') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Package Dimensions & Pricing -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
            <h2 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-box-open text-amber-500"></i> Package Dimensions & Payment
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Weight (kg) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="weight_kg" value="{{ old('weight_kg', '0.5') }}" required min="0.01" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[var(--gold)]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Length (cm)</label>
                    <input type="number" step="0.1" name="length_cm" value="{{ old('length_cm', '10') }}" min="1" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[var(--gold)]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Width (cm)</label>
                    <input type="number" step="0.1" name="width_cm" value="{{ old('width_cm', '10') }}" min="1" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[var(--gold)]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Height (cm)</label>
                    <input type="number" step="0.1" name="height_cm" value="{{ old('height_cm', '10') }}" min="1" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[var(--gold)]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-100">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Payment Mode <span class="text-red-500">*</span></label>
                    <select name="is_cod" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[var(--gold)]">
                        <option value="0" {{ old('is_cod') == '0' ? 'selected' : '' }}>Prepaid</option>
                        <option value="1" {{ old('is_cod') == '1' ? 'selected' : '' }}>Cash on Delivery (COD)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Invoice Value (₹) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="invoice_value" value="{{ old('invoice_value', '500') }}" required min="1" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[var(--gold)]">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.shipments.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-100 font-bold text-sm transition">Cancel</a>
            <button type="submit" class="px-8 py-2.5 bg-gray-900 text-white rounded-xl hover:bg-black font-bold text-sm shadow-md transition">
                <i class="fa-solid fa-plus mr-1"></i> Book Shipment
            </button>
        </div>
    </form>
</div>
@endsection
