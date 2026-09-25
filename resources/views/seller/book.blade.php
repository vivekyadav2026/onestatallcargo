@extends('layouts.seller')
@section('title', ($mode ?? 'create') === 'edit' ? 'Edit Order - OneStall Cargo' : (($mode ?? 'create') === 'clone' ? 'Clone Order - OneStall Cargo' : 'Add Order - OneStall Cargo'))
@section('content')
<div class="max-w-[1000px] mx-auto pb-24" x-data="bookingForm()">
    
    <!-- Breadcrumb & Header -->
    <div class="mb-4 text-[11px] font-semibold text-gray-500">
        <a href="{{ route('seller.shipments.index') }}" class="hover:text-gray-800">Orders</a> <i class="fa-solid fa-chevron-right text-[8px] mx-1"></i> Add Order
    </div>
    
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('seller.shipments.index') }}" class="w-8 h-8 rounded-full hover:bg-gray-200 flex items-center justify-center text-gray-600 transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">{{ ($mode ?? "create") === "edit" ? "Edit Order #".($shipment->awb_number ?? "") : (($mode ?? "create") === "clone" ? "Clone Order (Copy of #".($shipment->awb_number ?? "").")" : "Add Order") }}</h1>
    </div>

    <form action="{{ route('seller.book.post') }}" method="POST" id="add-order-form">
                @csrf
        <input type="hidden" name="mode" value="{{ $mode ?? 'create' }}">
        <input type="hidden" name="shipment_id" value="{{ ($mode ?? '') === 'edit' ? ($shipment->id ?? '') : '' }}">
        <!-- Sync calculated invoice value with form submit -->
        <input type="hidden" name="invoice_value" :value="totalOrderValue()">
        <input type="hidden" name="ship_now" x-model="shipNow">
        
        <!-- CARD 1: Shipment Type & Warehouse -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
            <div class="mb-6">
                <h2 class="text-[14px] font-bold text-gray-900 mb-3">Shipment Type</h2>
                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-gray-700">
                        <input type="radio" name="shipment_type" value="B2C" x-model="shipmentType" class="accent-[#4338ca] w-4 h-4"> Package
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-gray-700">
                        <input type="radio" name="shipment_type" value="Document" x-model="shipmentType" class="accent-[#4338ca] w-4 h-4"> Document
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-gray-700">
                        <input type="radio" name="shipment_type" value="B2B" x-model="shipmentType" class="accent-[#4338ca] w-4 h-4"> Reverse / Cargo
                    </label>
                </div>
            </div>
            
            <div>
                <h2 class="text-[14px] font-bold text-gray-900 mb-3">Pickup Warehouse</h2>
                @php $warehouses = \App\Models\Warehouse::where('user_id', Auth::id())->get(); @endphp
                @if($warehouses->count() > 0)
                    <select name="pickup_location" class="w-full max-w-md px-3 py-2.5 border border-gray-200 rounded-lg text-xs font-semibold text-gray-700 outline-none focus:border-[#4338ca]">
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}" {{ $wh->is_default ? 'selected' : '' }}>{{ $wh->name }} ({{ $wh->pincode }})</option>
                        @endforeach
                    </select>
                @else
                    <div class="flex items-center justify-between p-4 bg-[#f8fafc] border border-gray-100 rounded-xl">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-[#eef2ff] text-[#4338ca] flex items-center justify-center">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-gray-900">No pickup warehouses found</div>
                                <div class="text-[10px] text-gray-500">Please add a pickup warehouse to place the order</div>
                            </div>
                        </div>
                        <a href="{{ route('seller.settings') }}?view=warehouses" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50 shadow-sm flex items-center gap-2">
                            <i class="fa-solid fa-plus"></i> Add Pickup Warehouse
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- CARD 2: Recipient Details -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
            <h2 class="text-[14px] font-bold text-gray-900">Recipient Details</h2>
            <p class="text-[11px] text-gray-500 mb-5 mt-1">Add your customer's shipping address and contact information</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                <div>
                    <label class="block text-[11px] font-semibold text-gray-700 mb-1.5">Mobile Number *</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 border border-r-0 border-gray-200 bg-gray-50 text-gray-500 text-xs rounded-l-lg">+91</span>
                        <input type="text" name="receiver_phone" value="{{ old('receiver_phone', $shipment->receiver_phone ?? '') }}" required placeholder="Enter mobile number" class="flex-1 px-3 py-2 border border-gray-200 rounded-r-lg text-xs outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] z-10">
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-gray-700 mb-1.5">Full Name *</label>
                    <input type="text" name="receiver_name" value="{{ old('receiver_name', $shipment->receiver_name ?? '') }}" required placeholder="Enter full name" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs outline-none focus:border-[#4338ca]">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-gray-700 mb-1.5 flex items-center gap-1">Address * <i class="fa-regular fa-circle-question text-gray-400"></i></label>
                    <input type="text" name="delivery_address" value="{{ old('delivery_address', $shipment->delivery_address ?? '') }}" required placeholder="Enter buyer's full address" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs outline-none focus:border-[#4338ca]">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-gray-700 mb-1.5">Landmark <span class="text-gray-400 font-normal">(Optional)</span></label>
                    <input type="text" placeholder="Enter any nearby landmark" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs outline-none focus:border-[#4338ca]">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-gray-700 mb-1.5">Pincode *</label>
                    <input type="text" name="delivery_pincode" value="{{ old('delivery_pincode', $shipment->delivery_pincode ?? '') }}" required placeholder="Enter pincode" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs outline-none focus:border-[#4338ca]">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-gray-700 mb-1.5">City *</label>
                    <input type="text" name="delivery_city" value="{{ old('delivery_city', $shipment->delivery_city ?? '') }}" required placeholder="City" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs outline-none focus:border-[#4338ca]">
                </div>
                
                <div>
                    <label class="block text-[11px] font-semibold text-gray-700 mb-1.5">State</label>
                    <input type="text" placeholder="State" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs outline-none focus:border-[#4338ca]">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-gray-700 mb-1.5">Buyer's Email ID <span class="text-gray-400 font-normal">(Optional)</span></label>
                    <input type="email" placeholder="Enter email address" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs outline-none focus:border-[#4338ca]">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-gray-700 mb-1.5">GST <span class="text-gray-400 font-normal">(Optional)</span></label>
                    <input type="text" placeholder="Enter GST number" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs outline-none focus:border-[#4338ca]">
                </div>
            </div>
            <div class="flex items-center gap-2 mt-4">
                <input type="checkbox" checked class="w-4 h-4 rounded text-[#4338ca] accent-[#4338ca]">
                <span class="text-xs font-semibold text-gray-700">My billing details are same as recipient details</span>
            </div>
        </div>

        <!-- CARD 3: Product Details (Dynamic Alpine Component) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
            <div class="p-6">
                <h2 class="text-[14px] font-bold text-gray-900 mb-5">Product Details</h2>
                
                <!-- Loop over products -->
                <template x-for="(product, index) in products" :key="index">
                    <div class="flex flex-wrap md:flex-nowrap gap-3 items-start mb-4 pb-4 border-b border-gray-100 last:border-b-0 last:pb-0">
                        <div class="w-full md:w-[35%]">
                            <label class="block text-[10px] font-semibold text-gray-700 mb-1">Product Name *</label>
                            <div class="relative">
                                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-400 text-[10px]"></i>
                                <input type="text" x-model="product.name" placeholder="Search product" :class="product.name.trim() === '' ? 'border-red-300 focus:border-red-500' : 'border-gray-200 focus:border-[#4338ca]'" class="w-full pl-8 pr-3 py-2 border rounded-lg text-xs outline-none">
                            </div>
                            <span x-show="product.name.trim() === ''" class="text-[9px] text-red-500 mt-1 block">Product name is required.</span>
                        </div>
                        <div class="w-full md:w-[15%]">
                            <label class="block text-[10px] font-semibold text-gray-700 mb-1">Unit Price</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-400 text-xs">&#8377;</span>
                                <input type="number" step="0.01" min="0" x-model.number="product.price" class="w-full pl-6 pr-3 py-2 border border-gray-200 rounded-lg text-xs outline-none focus:border-[#4338ca]">
                            </div>
                        </div>
                        <div class="w-full md:w-[15%]">
                            <label class="block text-[10px] font-semibold text-gray-700 mb-1">Quantity</label>
                            <div class="flex items-center border border-gray-200 rounded-lg px-2 py-1.5 h-[34px]">
                                <button type="button" @click="if(product.qty > 1) product.qty--" class="text-gray-400 hover:text-gray-700 px-1 font-bold">-</button>
                                <input type="number" min="1" x-model.number="product.qty" class="w-full text-center text-xs outline-none">
                                <button type="button" @click="product.qty++" class="text-gray-400 hover:text-gray-700 px-1 font-bold">+</button>
                            </div>
                        </div>
                        <div class="w-full md:w-[15%]">
                            <label class="block text-[10px] font-semibold text-gray-700 mb-1">SKU <span class="text-gray-400 font-normal">(Optional)</span></label>
                            <input type="text" x-model="product.sku" placeholder="SKU" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs outline-none focus:border-[#4338ca]">
                        </div>
                        <div class="w-full md:w-[15%]">
                            <label class="block text-[10px] font-semibold text-gray-700 mb-1">HSN <span class="text-gray-400 font-normal">(Optional)</span></label>
                            <input type="text" x-model="product.hsn" placeholder="HSN Code" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs outline-none focus:border-[#4338ca]">
                        </div>
                        <div class="w-6 flex justify-center pt-6">
                            <button type="button" @click="removeProduct(index)" x-show="products.length > 1" class="text-red-500 hover:text-red-700 text-xs">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                </template>

                <button type="button" @click="addProduct()" class="text-[#4338ca] hover:text-indigo-800 text-xs font-bold flex items-center gap-1 mt-4">
                    <i class="fa-solid fa-plus"></i> Add Another Product
                </button>
            </div>
            
            <div class="border-t border-gray-100 bg-gray-50/50 p-4 px-6">
                <!-- Accordion Toggle for Charges & Discount -->
                <div @click="showOtherCharges = !showOtherCharges" class="flex items-center justify-between text-xs text-gray-600 mb-3 cursor-pointer select-none">
                    <span class="font-semibold">Add Other Charges & Discount <span class="text-gray-400 font-normal">(Optional)</span></span>
                    <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200" :class="showOtherCharges ? 'rotate-180' : ''"></i>
                </div>
                
                <!-- Expandable Inputs -->
                <div x-show="showOtherCharges" x-collapse class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 pt-2">
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-700 mb-1">Shipping Charge</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-400 text-xs">&#8377;</span>
                            <input type="number" step="0.01" min="0" x-model.number="shippingCharge" class="w-full pl-6 pr-3 py-2 border border-gray-200 rounded-lg text-xs outline-none focus:border-[#4338ca]">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-700 mb-1">Gift Wrap / Extra Fee</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-400 text-xs">&#8377;</span>
                            <input type="number" step="0.01" min="0" x-model.number="extraFee" class="w-full pl-6 pr-3 py-2 border border-gray-200 rounded-lg text-xs outline-none focus:border-[#4338ca]">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-700 mb-1">Discount Amount</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-400 text-xs">&#8377;</span>
                            <input type="number" step="0.01" min="0" x-model.number="discountAmount" class="w-full pl-6 pr-3 py-2 border border-gray-200 rounded-lg text-xs outline-none focus:border-[#4338ca]">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs text-gray-700 mb-2 mt-2 pt-3 border-t border-gray-200 border-dashed">
                    <span>Sub-total for Product</span>
                    <span class="font-bold">&#8377; <span x-text="subtotalProducts().toFixed(2)"></span></span>
                </div>
                <div class="flex items-center justify-between text-xs text-gray-900 font-bold mb-1">
                    <span>Total Order Value</span>
                    <span>&#8377; <span x-text="totalOrderValue().toFixed(2)"></span></span>
                </div>
                <div class="text-[9px] text-gray-400 mt-2">Note: All Prices/Charges are inclusive of GST.</div>
            </div>
        </div>

        <!-- CARD 4: Payment Method & Package Details -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 p-6">
            <h2 class="text-[14px] font-bold text-gray-900 mb-1">Payment Method</h2>
            <p class="text-[11px] text-gray-500 mb-4">Select the payment mode, chosen by the buyer for this order.</p>

            <div class="flex gap-4 mb-8">
                <input type="hidden" name="is_cod" :value="isCod">
                <div @click="isCod = '0'" :class="isCod === '0' ? 'border-[#4338ca] bg-[#eef2ff]' : 'border-gray-200'" class="flex-1 max-w-[200px] border rounded-lg p-3 flex items-center gap-3 cursor-pointer transition select-none">
                    <input type="radio" name="pay_mode_radio" value="0" :checked="isCod === '0'" class="accent-[#4338ca]">
                    <span class="text-xs font-bold text-gray-800">Prepaid</span>
                </div>
                <div @click="isCod = '1'" :class="isCod === '1' ? 'border-[#4338ca] bg-[#eef2ff]' : 'border-gray-200'" class="flex-1 max-w-[200px] border rounded-lg p-3 flex items-center gap-3 cursor-pointer transition select-none">
                    <input type="radio" name="pay_mode_radio" value="1" :checked="isCod === '1'" class="accent-[#4338ca]">
                    <span class="text-xs font-bold text-gray-800">Cash on Delivery</span>
                </div>
            </div>

            <div class="flex items-center gap-2 mb-4">
                <h2 class="text-[14px] font-bold text-gray-900">Package Details</h2>
                <span class="bg-[#ecfdf5] text-[#059669] border border-[#a7f3d0] rounded px-2 py-0.5 text-[9px] font-bold flex items-center gap-1"><i class="fa-solid fa-lightbulb"></i> Tip: Recheck values to avoid weight discrepancies</span>
            </div>
            <p class="text-[11px] text-gray-500 mb-4">Provide the details of the final package that includes all the ordered items packed together.</p>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-5">
                <div>
                    <label class="block text-[10px] font-semibold text-gray-700 mb-1">Dead Weight <span class="block text-gray-400 font-normal text-[9px]">Physical weight of a package</span></label>
                    <div class="relative">
                        <input type="number" step="0.01" name="weight_kg" x-model.number="deadWeight" required class="w-full px-3 py-2 pr-8 border border-gray-200 rounded-lg text-xs outline-none focus:border-[#4338ca]">
                        <span class="absolute right-3 top-2 text-gray-400 text-xs">kg</span>
                    </div>
                </div>
                <div class="col-span-2">
                    <label class="block text-[10px] font-semibold text-gray-700 mb-1">Package Dimensions <span class="block text-gray-400 font-normal text-[9px]">L x B x H of the complete package</span></label>
                    <div class="flex items-center gap-2">
                        <div class="relative flex-1">
                            <input type="number" name="length_cm" x-model.number="lengthCm" placeholder="Length" class="w-full px-3 py-2 pr-7 border border-gray-200 rounded-lg text-xs outline-none focus:border-[#4338ca]">
                            <span class="absolute right-2 top-2 text-gray-400 text-[10px]">cm</span>
                        </div>
                        <div class="relative flex-1">
                            <input type="number" name="width_cm" x-model.number="widthCm" placeholder="Breadth" class="w-full px-3 py-2 pr-7 border border-gray-200 rounded-lg text-xs outline-none focus:border-[#4338ca]">
                            <span class="absolute right-2 top-2 text-gray-400 text-[10px]">cm</span>
                        </div>
                        <div class="relative flex-1">
                            <input type="number" name="height_cm" x-model.number="heightCm" placeholder="Height" class="w-full px-3 py-2 pr-7 border border-gray-200 rounded-lg text-xs outline-none focus:border-[#4338ca]">
                            <span class="absolute right-2 top-2 text-gray-400 text-[10px]">cm</span>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-gray-700 mb-1">Volumetric Weight <i class="fa-regular fa-circle-question text-gray-400"></i></label>
                    <div class="relative">
                        <input type="text" readonly :value="volumetricWeight().toFixed(2)" class="w-full px-3 py-2 pr-8 border border-gray-200 bg-gray-50 rounded-lg text-xs outline-none text-gray-500">
                        <span class="absolute right-3 top-2 text-gray-400 text-xs">kg</span>
                    </div>
                </div>
            </div>

            <div class="bg-[#f0fdf4] border border-[#bbf7d0] rounded-lg p-3 mb-5 flex items-start gap-3">
                <i class="fa-solid fa-circle-info text-[#16a34a] mt-0.5"></i>
                <div>
                    <div class="text-xs font-bold text-[#166534]">Chargeable Weight: <span x-text="chargeableWeight().toFixed(2)"></span> kg</div>
                    <div class="text-[10px] text-[#15803d]">Chargeable weight is the higher of the dead weight or volumetric weight, used by the courier for freight charges.</div>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg px-4 py-3 flex items-center justify-between mb-5">
                <div class="flex items-center gap-2 text-xs font-semibold text-gray-700">
                    <i class="fa-solid fa-triangle-exclamation text-gray-400"></i> Dangerous Goods <i class="fa-regular fa-circle-question text-gray-400"></i>
                </div>
                <div @click="isDangerous = !isDangerous" class="w-8 h-4 rounded-full relative cursor-pointer transition-colors" :class="isDangerous ? 'bg-[#4338ca]' : 'bg-gray-200'">
                    <div class="w-3 h-3 bg-white rounded-full absolute top-0.5 shadow-sm transition-all" :class="isDangerous ? 'right-0.5' : 'left-0.5'"></div>
                </div>
            </div>

            <!-- Guidelines Section Toggle -->
            <div class="border-t border-gray-100 pt-4">
                <div @click="showGuidelines = !showGuidelines" class="flex items-center justify-between text-[11px] cursor-pointer select-none">
                    <span class="text-gray-500 font-medium">Pack like a Pro - Guidelines for Packaging and Measuring</span>
                    <span class="text-[#4338ca] font-semibold flex items-center gap-1">See Guidelines <i class="fa-solid fa-chevron-down text-[9px] transition-transform duration-200" :class="showGuidelines ? 'rotate-180' : ''"></i></span>
                </div>
                <div x-show="showGuidelines" class="mt-3 bg-[#f8fafc] p-4 rounded-lg text-xs text-gray-600 space-y-2 border border-gray-100">
                    <p class="font-bold text-gray-800">Tips for Safe Packaging:</p>
                    <ul class="list-disc pl-4 text-[11px] space-y-1">
                        <li>Use corrugated boxes with double walls for heavy items.</li>
                        <li>Ensure at least 2 inches of cushioning padding around fragile goods.</li>
                        <li>Measure outer box dimensions accurately after sealing.</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- CARD 5: Other Details (Expandable Accordion) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
            <div @click="showOtherDetails = !showOtherDetails" class="p-6 flex items-center justify-between text-[14px] font-bold text-gray-900 cursor-pointer select-none">
                Other Details
                <i class="fa-solid fa-chevron-down text-gray-400 text-xs transition-transform duration-200" :class="showOtherDetails ? 'rotate-180' : ''"></i>
            </div>
            
            <div x-show="showOtherDetails" class="p-6 pt-0 border-t border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 mb-1">Custom Order / Channel ID <span class="text-gray-400 font-normal">(Optional)</span></label>
                        <input type="text" name="order_id" placeholder="e.g. ORD-98213" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs outline-none focus:border-[#4338ca]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 mb-1">E-Way Bill Number <span class="text-gray-400 font-normal">(Required if invoice > ₹50,000)</span></label>
                        <input type="text" name="eway_bill" placeholder="Enter 12-digit E-Way Bill" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs outline-none focus:border-[#4338ca]">
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

<!-- Sticky Bottom Action Bar -->
<div class="fixed bottom-0 left-0 md:left-16 right-0 bg-white border-t border-gray-200 p-4 z-40 flex items-center justify-end gap-3 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
    @if(($mode ?? 'create') === 'edit')
        <button type="button" @click="shipNow = 1; document.getElementById('add-order-form').submit()" class="px-5 py-2.5 border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50 transition">Update Order & Ship Now</button>
        <button type="button" @click="shipNow = 0; document.getElementById('add-order-form').submit()" class="px-5 py-2.5 bg-[#1e1b4b] text-white text-xs font-bold rounded-lg hover:bg-black transition shadow-md">Update Order</button>
    @elseif(($mode ?? 'create') === 'clone')
        <button type="button" @click="shipNow = 1; document.getElementById('add-order-form').submit()" class="px-5 py-2.5 border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50 transition">Clone Order & Ship Now</button>
        <button type="button" @click="shipNow = 0; document.getElementById('add-order-form').submit()" class="px-5 py-2.5 bg-[#1e1b4b] text-white text-xs font-bold rounded-lg hover:bg-black transition shadow-md">Clone Order</button>
    @else
        <button type="button" @click="shipNow = 1; document.getElementById('add-order-form').submit()" class="px-5 py-2.5 border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50 transition">Add Order & Ship Now</button>
        <button type="button" @click="shipNow = 0; document.getElementById('add-order-form').submit()" class="px-5 py-2.5 bg-[#1e1b4b] text-white text-xs font-bold rounded-lg hover:bg-black transition shadow-md">Add Order</button>
    @endif
</div>

<script>
function bookingForm() {
    return {
        shipmentType: '{{ old('shipment_type', $shipment->shipment_type ?? 'B2C') }}',
        isCod: '{{ old('is_cod', ($shipment->is_cod ?? false) ? '1' : '0') }}',
        isDangerous: false,
        showOtherCharges: false,
        showGuidelines: false,
        showOtherDetails: false,
        shipNow: 0,
        shippingCharge: 0,
        extraFee: 0,
        discountAmount: 0,
        deadWeight: {{ old('weight_kg', $shipment->weight_kg ?? 0.5) }},
        lengthCm: 10,
        widthCm: 10,
        heightCm: 10,
        products: [
            { 
                name: '{{ old('product_name', isset($shipment) ? 'Package Item for ' . $shipment->receiver_name : '') }}', 
                price: {{ old('invoice_value', $shipment->invoice_value ?? 0) }}, 
                qty: 1, 
                sku: '{{ $shipment->awb_number ?? "" }}', 
                hsn: '' 
            }
        ],
        addProduct() {
            this.products.push({ name: '', price: 0, qty: 1, sku: '', hsn: '' });
        },
        removeProduct(index) {
            if (this.products.length > 1) {
                this.products.splice(index, 1);
            }
        },
        subtotalProducts() {
            return this.products.reduce((sum, p) => {
                const val = (parseFloat(p.price) || 0) * (parseInt(p.qty) || 1);
                return sum + val;
            }, 0);
        },
        totalOrderValue() {
            const sub = this.subtotalProducts();
            const ship = parseFloat(this.shippingCharge) || 0;
            const extra = parseFloat(this.extraFee) || 0;
            const disc = parseFloat(this.discountAmount) || 0;
            return Math.max(0, sub + ship + extra - disc);
        },
        volumetricWeight() {
            const l = parseFloat(this.lengthCm) || 0;
            const w = parseFloat(this.widthCm) || 0;
            const h = parseFloat(this.heightCm) || 0;
            return (l * w * h) / 5000;
        },
        chargeableWeight() {
            const dw = parseFloat(this.deadWeight) || 0;
            const vw = this.volumetricWeight();
            return Math.max(dw, vw);
        }
    }
}
</script>
@endsection
