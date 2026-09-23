@extends('layouts.seller')
@section('title', 'Book Shipment - OneStall Cargo')
@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{ shipmentType: 'B2C' }">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Book a Parcel</h1>
        <p class="text-sm text-gray-500 mt-1">Select mode and enter package details.</p>
    </div>

    <!-- Booking Type Toggles -->
    <div class="flex gap-4 mb-4 bg-gray-200 p-1 rounded-xl">
        <button @click="shipmentType = 'B2C'" :class="shipmentType == 'B2C' ? 'bg-white shadow text-[#1e293b]' : 'text-gray-500'" class="flex-1 py-2 text-sm font-bold rounded-lg transition" type="button">Standard (B2C)</button>
        <button @click="shipmentType = 'B2B'" :class="shipmentType == 'B2B' ? 'bg-white shadow text-[#1e293b]' : 'text-gray-500'" class="flex-1 py-2 text-sm font-bold rounded-lg transition" type="button">Cargo / PTL (B2B)</button>
        <button @click="shipmentType = 'International'" :class="shipmentType == 'International' ? 'bg-white shadow text-[#1e293b]' : 'text-gray-500'" class="flex-1 py-2 text-sm font-bold rounded-lg transition" type="button">International</button>
    </div>

    <form action="{{ route('seller.book.post') }}" method="POST" class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        @csrf
        <!-- Hidden input to track selected type -->
        <input type="hidden" name="shipment_type" x-model="shipmentType">
        
        <div class="p-6 md:p-8 space-y-8">
            <!-- Receiver -->
            <div>
                <h3 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-2 mb-4 uppercase tracking-wider">Receiver Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Name / Company *</label>
                        <input type="text" name="receiver_name" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-[var(--gold)] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Mobile Number *</label>
                        <input type="text" name="receiver_phone" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-[var(--gold)] outline-none">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Complete Address *</label>
                        <input type="text" name="delivery_address" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-[var(--gold)] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">City *</label>
                        <input type="text" name="delivery_city" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-[var(--gold)] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pincode / Zipcode *</label>
                        <input type="text" name="delivery_pincode" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-[var(--gold)] outline-none">
                    </div>
                    
                    <!-- International Fields -->
                    <div x-show="shipmentType == 'International'" class="md:col-span-2 grid grid-cols-2 gap-4 mt-2 p-4 bg-blue-50 rounded-xl border border-blue-100" style="display: none;">
                        <div>
                            <label class="block text-xs font-bold text-blue-900 mb-1">Destination Country *</label>
                            <select name="destination_country" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:border-blue-500 outline-none">
                                <option value="India">India</option>
                                <option value="USA">United States</option>
                                <option value="UK">United Kingdom</option>
                                <option value="UAE">United Arab Emirates</option>
                                <option value="Australia">Australia</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-blue-900 mb-1">Customs Value (USD) *</label>
                            <input type="number" step="0.01" name="customs_value" value="0.00" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:border-blue-500 outline-none">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-blue-900 mb-1">HS Code (Harmonized System)</label>
                            <input type="text" name="hs_code" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:border-blue-500 outline-none" placeholder="e.g. 6109.10.00">
                        </div>
                    </div>
                </div>
            </div>

            <!-- B2B / Cargo Fields -->
            <div x-show="shipmentType == 'B2B'" class="p-4 bg-orange-50 rounded-xl border border-orange-200" style="display: none;">
                <h3 class="text-sm font-bold text-orange-900 border-b border-orange-200 pb-2 mb-4 uppercase tracking-wider">Cargo & Freight Logistics</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-orange-900 mb-1">Vehicle Requirement</label>
                        <select name="vehicle_type" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:border-orange-500 outline-none">
                            <option value="PTL">Part Truck Load (PTL)</option>
                            <option value="FTL_Small">Full Truck Load (FTL) - Small LCV</option>
                            <option value="FTL_Large">Full Truck Load (FTL) - Large Truck</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-orange-900 mb-1">E-Way Bill Number (Optional)</label>
                        <input type="text" name="eway_bill" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:border-orange-500 outline-none">
                    </div>
                </div>
                <p class="text-xs text-orange-800 mt-3 font-bold"><i class="fa-solid fa-info-circle"></i> Booking this will generate an official Consignment Note (LR) instead of a standard AWB.</p>
            </div>

            <!-- Dimensions -->
            <div>
                <h3 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-2 mb-4 uppercase tracking-wider">Package Details</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Weight (kg) *</label>
                        <input type="number" step="0.1" name="weight_kg" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white outline-none">
                    </div>
                    <div><label class="block text-xs font-bold text-gray-700 mb-1">Length (cm)</label><input type="number" name="length_cm" value="10" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white outline-none"></div>
                    <div><label class="block text-xs font-bold text-gray-700 mb-1">Width (cm)</label><input type="number" name="width_cm" value="10" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white outline-none"></div>
                    <div><label class="block text-xs font-bold text-gray-700 mb-1">Height (cm)</label><input type="number" name="height_cm" value="10" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white outline-none"></div>
                </div>
            </div>

            <!-- Payment -->
            <div x-show="shipmentType != 'International'">
                <h3 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-2 mb-4 uppercase tracking-wider">Payment</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Payment Mode</label>
                        <select name="is_cod" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none">
                            <option value="0">Prepaid</option>
                            <option value="1">Cash on Delivery (COD)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Invoice Value (₹) *</label>
                        <input type="number" name="invoice_value" value="0" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none">
                    </div>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
            <div class="text-xs text-gray-500"><i class="fa-solid fa-truck-fast"></i> Carrier will be auto-assigned (Delhivery/BlueDart/Self) based on best rates.</div>
            <button type="submit" class="px-6 py-3 rounded-xl text-sm font-bold bg-[#1e293b] text-white shadow-md hover:bg-black transition-colors">
                Book Shipment & Print Label
            </button>
        </div>
    </form>
</div>
@endsection
