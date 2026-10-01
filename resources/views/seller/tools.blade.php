@extends('layouts.seller')
@section('title', 'Tools & Calculator - OneStall Cargo')

@section('content')
<div class="space-y-6 w-full" x-data="toolsManager()">
    
    <!-- Header -->
    <div>
        <h1 class="text-[28px] font-bold text-gray-900 tracking-tight">Tools</h1>
        <p class="text-sm text-gray-500 mt-1">Calculate shipping rates, compare courier rate charts, check pincode serviceability and track your bulk activity logs — all in one place.</p>
    </div>

    <!-- Tabs Container -->
    <div class="space-y-6">
        
        <!-- Tab Headers Bar -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm flex px-4 pt-2 overflow-x-auto whitespace-nowrap">
            <button @click="activeTab = 'calculator'" :class="activeTab === 'calculator' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-6 py-3 text-sm transition-colors">Rate Calculator</button>
            <button @click="activeTab = 'chart'" :class="activeTab === 'chart' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-6 py-3 text-sm transition-colors">Rate Chart</button>
            <button @click="activeTab = 'serviceability'" :class="activeTab === 'serviceability' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-6 py-3 text-sm transition-colors">Serviceable Pincodes</button>
            <button @click="activeTab = 'logs'" :class="activeTab === 'logs' ? 'text-[#4338ca] border-b-2 border-[#4338ca] font-bold' : 'text-gray-500 font-medium hover:text-gray-700'" class="px-6 py-3 text-sm transition-colors">Activity Logs</button>
        </div>
        
        <!-- TAB 1: Rate Calculator (Bigship Inspired UI) -->
        <div x-show="activeTab === 'calculator'" class="w-full">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 w-full items-start">
                
                <!-- Left Column: Form Card -->
                <div class="lg:col-span-6 bg-white rounded-3xl p-6 md:p-8 border border-gray-200 shadow-sm space-y-6">
                    
                    <!-- Domestic / International Tabs -->
                    <div class="flex items-center gap-6 border-b border-gray-100 pb-3">
                        <button type="button" @click="setCalcType('domestic')" :class="calc.type === 'domestic' ? 'text-blue-600 font-bold border-b-2 border-blue-600 pb-2 -mb-3.5' : 'text-gray-400 font-medium hover:text-gray-600 pb-2 -mb-3.5'" class="text-sm transition-all flex items-center gap-1.5">
                            <i class="fa-solid fa-truck"></i> Domestic
                        </button>
                        <button type="button" @click="setCalcType('international')" :class="calc.type === 'international' ? 'text-blue-600 font-bold border-b-2 border-blue-600 pb-2 -mb-3.5' : 'text-gray-400 font-medium hover:text-gray-600 pb-2 -mb-3.5'" class="text-sm transition-all flex items-center gap-1.5">
                            <i class="fa-solid fa-plane"></i> International
                        </button>
                    </div>

                    <p class="text-xs text-red-500 font-semibold flex items-center gap-1">
                        All Fields Required <span class="text-red-500">*</span>
                    </p>

                    <!-- FORM START -->
                    <form @submit.prevent="calculateRates" class="space-y-5">
                        
                        <!-- ================= DOMESTIC FORM ================= -->
                        <div x-show="calc.type === 'domestic'" class="space-y-5">
                            <!-- Pincodes Row -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Pickup Pincode <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input type="text" inputmode="numeric" x-model="calc.pickup" @input="calc.pickup = calc.pickup.replace(/\D/g, '').slice(0, 6); fetchCity(calc.pickup, 'pickupCity')" maxlength="6" placeholder="e.g. 227405" :required="calc.type === 'domestic'" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm font-semibold text-gray-800 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 pr-10">
                                        <span x-show="calc.pickup.length === 6" class="absolute right-3 top-1/2 -translate-y-1/2 text-green-500 text-base">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </span>
                                    </div>
                                    <p x-show="calc.pickupCity" class="text-[11px] text-green-600 font-medium mt-1 truncate" x-text="calc.pickupCity"></p>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Destination Pincode <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input type="text" inputmode="numeric" x-model="calc.delivery" @input="calc.delivery = calc.delivery.replace(/\D/g, '').slice(0, 6); fetchCity(calc.delivery, 'deliveryCity')" maxlength="6" placeholder="e.g. 110001" :required="calc.type === 'domestic'" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm font-semibold text-gray-800 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 pr-10">
                                        <span x-show="calc.delivery.length === 6" class="absolute right-3 top-1/2 -translate-y-1/2 text-green-500 text-base">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </span>
                                    </div>
                                    <p x-show="calc.deliveryCity" class="text-[11px] text-green-600 font-medium mt-1 truncate" x-text="calc.deliveryCity"></p>
                                </div>
                            </div>

                            <!-- Payment Mode & Risk Type -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Payment Mode <span class="text-red-500">*</span></label>
                                    <select x-model="calc.payment" @change="if(calc.payment === 'cod' && !calc.codAmount) { calc.codAmount = calc.invoiceAmount; }" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm font-semibold text-gray-800 focus:outline-none focus:border-blue-600 bg-white">
                                        <option value="prepaid">Prepaid</option>
                                        <option value="cod">COD</option>
                                        <option value="topay">ToPay</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Risk Type <span class="text-red-500">*</span></label>
                                    <select x-model="calc.riskType" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm font-semibold text-gray-800 focus:outline-none focus:border-blue-600 bg-white">
                                        <option value="owner_risk">Owner Risk</option>
                                        <option value="carrier_risk">Carrier Risk</option>
                                        <option value="third_party_insurance">Third Party Insurance</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Multi Box Checkbox -->
                            <div class="pt-1">
                                <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-xs text-gray-800 select-none">
                                    <input type="checkbox" x-model="calc.isMultiBox" class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span>Multi Box Shipment</span>
                                </label>
                            </div>
                        </div>

                        <!-- ================= INTERNATIONAL FORM (Matches Reference) ================= -->
                        <div x-show="calc.type === 'international'" class="space-y-5">
                            <!-- Countries Row -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Pickup Country <span class="text-red-500">*</span></label>
                                    <input type="text" value="India" readonly class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-700 bg-gray-100/90 cursor-not-allowed shadow-xs">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Destination Country <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input type="text" list="intl-countries-list" x-model="calc.intlDestCountry" placeholder="Enter the Country Name" :required="calc.type === 'international'" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm font-semibold text-gray-800 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 shadow-xs">
                                        <datalist id="intl-countries-list">
                                            <option value="United States">
                                            <option value="United Kingdom">
                                            <option value="United Arab Emirates">
                                            <option value="Canada">
                                            <option value="Australia">
                                            <option value="Germany">
                                            <option value="Singapore">
                                            <option value="Saudi Arabia">
                                            <option value="France">
                                            <option value="Japan">
                                            <option value="Oman">
                                            <option value="Qatar">
                                            <option value="Kuwait">
                                            <option value="Bahrain">
                                            <option value="Netherlands">
                                            <option value="New Zealand">
                                            <option value="Malaysia">
                                            <option value="Thailand">
                                            <option value="Italy">
                                            <option value="Spain">
                                            <option value="Switzerland">
                                            <option value="South Africa">
                                        </datalist>
                                    </div>
                                </div>
                            </div>

                            <!-- Type of Shipment & Shipment Category -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" :class="calc.intlCategory ? 'lg:grid-cols-3' : 'sm:grid-cols-2'">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Type of Shipment <span class="text-red-500">*</span></label>
                                    <select x-model="calc.intlServiceType" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm font-semibold text-gray-800 focus:outline-none focus:border-blue-600 bg-white">
                                        <option value="World Wide Parcel">World Wide Parcel</option>
                                        <option value="World Wide Document">World Wide Document</option>
                                        <option value="Express Cargo">Express Cargo</option>
                                        <option value="Economy Air Freight">Economy Air Freight</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Shipment Category <span class="text-red-500">*</span></label>
                                    <select x-model="calc.intlCategory" :required="calc.type === 'international'" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm font-semibold text-gray-800 focus:outline-none focus:border-blue-600 bg-white">
                                        <option value="">Select Shipment Category</option>
                                        <option value="Cargo Shipment">Cargo Shipment</option>
                                        <option value="Commercial Shipment">Commercial Shipment</option>
                                        <option value="Sample / Gift">Sample / Gift</option>
                                        <option value="Documents">Documents</option>
                                        <option value="Personal Effects">Personal Effects</option>
                                    </select>
                                </div>

                                <div x-show="calc.intlCategory" x-transition>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Shipment Sub Category <span class="text-red-500">*</span></label>
                                    <select x-model="calc.intlSubCategory" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm font-semibold text-gray-800 focus:outline-none focus:border-blue-600 bg-white">
                                        <option value="General Merchandise">Select Shipment Sub Category</option>
                                        <option value="Garments & Textiles">Garments & Textiles</option>
                                        <option value="Electronics & Accessories">Electronics & Accessories</option>
                                        <option value="Auto Parts & Machinery">Auto Parts & Machinery</option>
                                        <option value="Handicrafts & Decor">Handicrafts & Decor</option>
                                        <option value="Food Items & Spices">Food Items & Spices</option>
                                        <option value="Pharma & Healthcare">Pharma & Healthcare</option>
                                        <option value="General Merchandise">General Merchandise</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Boxes & Dimensions Section (Pixel-Perfect to Bigship Reference) -->
                        <div class="space-y-2 pt-1">
                            <label class="block text-xs font-bold text-gray-800">Boxes and Dimensions <span class="text-red-500">*</span></label>
                            
                            <div class="space-y-3">
                                <template x-for="(box, idx) in calc.boxes" :key="idx">
                                    <div class="flex items-start gap-2 sm:gap-2.5">
                                        <!-- No of Box -->
                                        <div class="w-16 sm:w-20 flex-shrink-0">
                                            <span class="block text-[11px] font-semibold text-gray-700 mb-1 whitespace-nowrap">No of Box <span class="text-red-500">*</span></span>
                                            <input type="number" min="1" max="999" x-model.number="box.no_of_boxes" @input="if(box.no_of_boxes > 999) box.no_of_boxes = 999" class="w-full h-12 border border-gray-300 rounded-lg px-2 text-sm font-bold text-gray-800 bg-white focus:outline-none focus:border-blue-500 text-center shadow-xs">
                                        </div>

                                        <!-- Length -->
                                        <div class="flex-1 min-w-[55px]">
                                            <span class="block text-[11px] font-semibold text-gray-700 mb-1 whitespace-nowrap">Length <span class="text-red-500">*</span></span>
                                            <div class="relative">
                                                <input type="number" min="1" max="999" step="0.1" x-model.number="box.length" @input="if(box.length > 999) box.length = 999" class="w-full h-12 border border-gray-300 rounded-lg pl-2 pr-7 text-sm font-bold text-gray-800 bg-white focus:outline-none focus:border-blue-500 text-center shadow-xs">
                                                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 font-bold pointer-events-none">CM</span>
                                            </div>
                                        </div>

                                        <!-- Height -->
                                        <div class="flex-1 min-w-[55px]">
                                            <span class="block text-[11px] font-semibold text-gray-700 mb-1 whitespace-nowrap">Height <span class="text-red-500">*</span></span>
                                            <div class="relative">
                                                <input type="number" min="1" max="999" step="0.1" x-model.number="box.height" @input="if(box.height > 999) box.height = 999" class="w-full h-12 border border-gray-300 rounded-lg pl-2 pr-7 text-sm font-bold text-gray-800 bg-white focus:outline-none focus:border-blue-500 text-center shadow-xs">
                                                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 font-bold pointer-events-none">CM</span>
                                            </div>
                                        </div>

                                        <!-- Width -->
                                        <div class="flex-1 min-w-[55px]">
                                            <span class="block text-[11px] font-semibold text-gray-700 mb-1 whitespace-nowrap">Width <span class="text-red-500">*</span></span>
                                            <div class="relative">
                                                <input type="number" min="1" max="999" step="0.1" x-model.number="box.width" @input="if(box.width > 999) box.width = 999" class="w-full h-12 border border-gray-300 rounded-lg pl-2 pr-7 text-sm font-bold text-gray-800 bg-white focus:outline-none focus:border-blue-500 text-center shadow-xs">
                                                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 font-bold pointer-events-none">CM</span>
                                            </div>
                                        </div>

                                        <!-- Weight -->
                                        <div class="flex-1 min-w-[65px]">
                                            <span class="block text-[11px] font-semibold text-gray-700 mb-1 whitespace-nowrap">Weight <span class="text-red-500">*</span></span>
                                            <div class="relative">
                                                <input type="number" min="0.01" max="9999" step="0.01" x-model.number="box.weight" @input="if(box.weight > 9999) box.weight = 9999" class="w-full h-12 border border-gray-300 rounded-lg pl-2 pr-7 text-sm font-bold text-gray-800 bg-white focus:outline-none focus:border-blue-500 text-center shadow-xs">
                                                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 font-bold pointer-events-none">KG</span>
                                            </div>
                                            <span class="block text-[10px] text-gray-400 font-medium mt-0.5 text-center">Per Box</span>
                                        </div>

                                        <!-- Add/Remove Action Buttons -->
                                        <div class="pt-[19px] flex items-center gap-1.5 flex-shrink-0">
                                            <template x-if="idx === calc.boxes.length - 1">
                                                <button type="button" @click="addBoxRow()" title="Add Another Box" class="w-10 h-10 rounded-lg bg-[#70b32d] hover:bg-[#5ea122] text-white flex items-center justify-center font-bold text-lg shadow-xs transition">
                                                    <i class="fa-solid fa-plus"></i>
                                                </button>
                                            </template>
                                            <template x-if="calc.boxes.length > 1">
                                                <button type="button" @click="removeBoxRow(idx)" title="Remove Box" class="w-10 h-10 rounded-lg bg-red-100 hover:bg-red-200 text-red-600 flex items-center justify-center font-bold text-sm transition">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <p class="text-[11px] text-gray-500 font-normal pt-1">
                                Note: Shipping charges are calculated based on whichever is higher: Actual Weight or Dimensional Weight.
                            </p>
                        </div>

                        <!-- Invoice Amount / COD Row (Domestic Only) -->
                        <div x-show="calc.type === 'domestic'" class="grid gap-4 pt-1" :class="calc.payment === 'cod' ? 'grid-cols-1 sm:grid-cols-2' : 'grid-cols-1'">
                            <div>
                                <div class="flex justify-between items-center mb-1.5">
                                    <label class="block text-xs font-bold text-gray-700">Invoice Amount <span class="text-red-500">*</span></label>
                                    <span class="text-[11px] text-blue-600 font-bold" x-text="(calc.invoiceAmount ? calc.invoiceAmount.toString().length : 0) + '/10'"></span>
                                </div>
                                <input type="text" inputmode="numeric" x-model="calc.invoiceAmount" @input="calc.invoiceAmount = calc.invoiceAmount.toString().replace(/\D/g, '').slice(0, 10); if(calc.payment === 'cod' && !calc.codAmountManual) { calc.codAmount = calc.invoiceAmount; }" placeholder="e.g. 5000" maxlength="10" :required="calc.type === 'domestic'" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm font-semibold text-gray-800 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 font-mono">
                            </div>

                            <div x-show="calc.payment === 'cod'" x-transition>
                                <div class="flex justify-between items-center mb-1.5">
                                    <label class="block text-xs font-bold text-gray-700">COD Collectable Amount (₹) <span class="text-red-500">*</span></label>
                                    <span class="text-[11px] text-blue-600 font-bold" x-text="(calc.codAmount ? calc.codAmount.toString().length : 0) + '/10'"></span>
                                </div>
                                <input type="text" inputmode="numeric" x-model="calc.codAmount" @input="calc.codAmountManual = true; calc.codAmount = calc.codAmount.toString().replace(/\D/g, '').slice(0, 10)" placeholder="e.g. 5000" maxlength="10" :required="calc.type === 'domestic' && calc.payment === 'cod'" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm font-semibold text-gray-800 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 font-mono">
                            </div>
                        </div>

                        <!-- Action Buttons (Matches Bigship) -->
                        <div class="flex items-center gap-3 pt-2">
                            <button type="button" @click="resetForm()" class="px-7 py-2.5 border border-gray-300 bg-gray-50 hover:bg-gray-100 text-gray-700 font-bold text-xs rounded-xl transition shadow-xs">
                                Reset
                            </button>
                            <button type="submit" :disabled="calc.loading" class="flex-1 py-2.5 bg-[#0284c7] hover:bg-[#0369a1] text-white font-bold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2 disabled:opacity-50">
                                <i class="fa-solid fa-calculator" x-show="!calc.loading"></i>
                                <i class="fa-solid fa-spinner fa-spin" x-show="calc.loading"></i>
                                <span x-text="calc.loading ? 'CALCULATING...' : 'CHECK PRICE'"></span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Column: Results Area (Matches Reference Indigo Design with Digital Scale Graphic) -->
                <div class="lg:col-span-6 bg-[#312e81] rounded-3xl p-6 md:p-8 shadow-xl flex flex-col justify-start text-white min-h-[520px]">
                    
                    <!-- Dynamic Header -->
                    <div class="text-center mb-6">
                        <h2 class="text-lg md:text-xl font-bold tracking-tight text-white leading-snug">
                            Rates for Shipping your Package
                            <!-- Domestic Dynamic Route -->
                            <template x-if="calc.type === 'domestic'">
                                <div>
                                    <template x-if="!calc.hasResults">
                                        <span class="text-indigo-200 text-sm font-normal block mt-1">(Pickup Pincode) to (Destination Pincode)</span>
                                    </template>
                                    <template x-if="calc.hasResults && calc.resultPickup && calc.resultDelivery">
                                        <div class="text-base font-bold text-indigo-100 mt-0.5">
                                            from <span class="text-indigo-200" x-text="'(' + calc.resultPickup + ')'"></span> to <span class="text-indigo-200" x-text="'(' + calc.resultDelivery + ')'"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <!-- International Dynamic Route -->
                            <template x-if="calc.type === 'international'">
                                <div>
                                    <template x-if="!calc.hasResults">
                                        <span class="text-indigo-200 text-sm font-normal block mt-1">(Pickup Country) to (Destination Country)</span>
                                    </template>
                                    <template x-if="calc.hasResults && calc.resultIntlDest">
                                        <div class="text-base font-bold text-indigo-100 mt-0.5">
                                            from <span class="text-indigo-200" x-text="'(' + (calc.resultIntlPickup || 'India') + ')'"></span> to <span class="text-indigo-200" x-text="'(' + calc.resultIntlDest + ')'"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </h2>
                        
                        <!-- Domestic Route Cities -->
                        <div x-show="calc.type === 'domestic' && calc.hasResults && (calc.resultPickupCity || calc.resultDeliveryCity)" class="text-xs text-indigo-300 mt-1 font-medium">
                            <span x-text="calc.resultPickupCity"></span>
                            <span x-show="calc.resultPickupCity && calc.resultDeliveryCity"> &rarr; </span>
                            <span x-text="calc.resultDeliveryCity"></span>
                        </div>

                        <!-- Summary Badges -->
                        <div x-show="calc.hasResults" class="mt-3 flex flex-wrap items-center justify-center gap-2 text-xs text-indigo-200">
                            <span class="bg-indigo-900/70 px-3 py-1 rounded-full border border-indigo-700/60 font-medium">
                                Total Wt: <strong class="text-white" x-text="calc.chargeableWeight + ' Kg'"></strong>
                            </span>
                            <span class="bg-indigo-900/70 px-3 py-1 rounded-full border border-indigo-700/60 uppercase font-medium">
                                Service: <strong class="text-white" x-text="calc.type === 'domestic' ? calc.payment : calc.intlServiceType"></strong>
                            </span>
                            <span x-show="calc.type === 'domestic'" class="bg-indigo-900/70 px-3 py-1 rounded-full border border-indigo-700/60 font-medium">
                                Insured: <strong class="text-white" x-text="calc.riskType === 'third_party_insurance' ? 'Yes' : 'No'"></strong>
                            </span>
                            <span x-show="calc.type === 'international' && calc.intlCategory" class="bg-indigo-900/70 px-3 py-1 rounded-full border border-indigo-700/60 font-medium">
                                Type: <strong class="text-white" x-text="calc.intlCategory"></strong>
                            </span>
                        </div>
                    </div>

                    <!-- Initial / Empty State (Illustration matching Reference Image) -->
                    <div x-show="!calc.hasResults && !calc.loading && !calc.error" class="flex-1 flex flex-col items-center justify-center text-center p-4">
                        <div class="relative w-full max-w-sm mx-auto flex items-center justify-center">
                            <!-- Digital Weighing Scale & Parcel Box Vector SVG -->
                            <svg class="w-full h-auto max-h-[290px] drop-shadow-2xl" viewBox="0 0 450 340" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <radialGradient id="scaleGlow" cx="50%" cy="50%" r="50%">
                                        <stop offset="0%" stop-color="#6366f1" stop-opacity="0.35"/>
                                        <stop offset="100%" stop-color="#312e81" stop-opacity="0"/>
                                    </radialGradient>
                                    <linearGradient id="scaleTop" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#e2e8f0"/>
                                        <stop offset="50%" stop-color="#cbd5e1"/>
                                        <stop offset="100%" stop-color="#94a3b8"/>
                                    </linearGradient>
                                    <linearGradient id="poleMetal" x1="0" y1="0" x2="1" y2="0">
                                        <stop offset="0%" stop-color="#94a3b8"/>
                                        <stop offset="50%" stop-color="#f8fafc"/>
                                        <stop offset="100%" stop-color="#64748b"/>
                                    </linearGradient>
                                    <linearGradient id="boxTop" x1="0" y1="0" x2="1" y2="1">
                                        <stop offset="0%" stop-color="#fcd34d"/>
                                        <stop offset="100%" stop-color="#f59e0b"/>
                                    </linearGradient>
                                    <linearGradient id="boxLeft" x1="0" y1="0" x2="1" y2="1">
                                        <stop offset="0%" stop-color="#d97706"/>
                                        <stop offset="100%" stop-color="#b45309"/>
                                    </linearGradient>
                                    <linearGradient id="boxRight" x1="0" y1="0" x2="1" y2="1">
                                        <stop offset="0%" stop-color="#b45309"/>
                                        <stop offset="100%" stop-color="#78350f"/>
                                    </linearGradient>
                                </defs>

                                <!-- World Map Silhouette Overlay -->
                                <g opacity="0.22" fill="#a5b4fc">
                                    <ellipse cx="120" cy="110" rx="35" ry="20"/>
                                    <ellipse cx="90" cy="90" rx="25" ry="15"/>
                                    <ellipse cx="150" cy="190" rx="20" ry="28"/>
                                    <ellipse cx="230" cy="95" rx="25" ry="18"/>
                                    <ellipse cx="240" cy="160" rx="30" ry="32"/>
                                    <ellipse cx="320" cy="100" rx="45" ry="25"/>
                                    <circle cx="310" cy="140" r="14"/>
                                    <ellipse cx="370" cy="200" rx="22" ry="15"/>
                                    <path d="M120 110 Q 210 60 310 140" stroke="#818cf8" stroke-dasharray="3 3" stroke-width="1.5" fill="none" opacity="0.7"/>
                                    <path d="M310 140 Q 240 95 230 95" stroke="#818cf8" stroke-dasharray="3 3" stroke-width="1.5" fill="none" opacity="0.7"/>
                                </g>

                                <!-- Shadow underneath scale platform -->
                                <ellipse cx="225" cy="275" rx="140" ry="30" fill="url(#scaleGlow)"/>
                                <ellipse cx="225" cy="280" rx="110" ry="18" fill="#1e1b4b" opacity="0.7"/>

                                <!-- Scale Feet -->
                                <circle cx="155" cy="275" r="7" fill="#475569"/>
                                <circle cx="295" cy="275" r="7" fill="#475569"/>
                                <circle cx="190" cy="290" r="6" fill="#334155"/>
                                <circle cx="260" cy="290" r="6" fill="#334155"/>

                                <!-- Weighing Scale Stainless Steel Platform (Isometric) -->
                                <path d="M145 240 L305 240 L345 270 L185 270 Z" fill="url(#scaleTop)" stroke="#cbd5e1" stroke-width="1.5"/>
                                <path d="M185 270 L345 270 L345 280 L185 280 Z" fill="#64748b"/>
                                <path d="M145 240 L185 270 L185 280 L145 250 Z" fill="#475569"/>

                                <!-- Scale Vertical Metal Pole -->
                                <rect x="320" y="140" width="10" height="110" rx="2" fill="url(#poleMetal)" stroke="#64748b"/>
                                <rect x="327" y="150" width="8" height="90" rx="1.5" fill="url(#poleMetal)"/>

                                <!-- Digital Indicator / Display Head -->
                                <rect x="308" y="112" width="34" height="28" rx="4" fill="#f1f5f9" stroke="#64748b" stroke-width="1.5"/>
                                <!-- LED Readout Screen -->
                                <rect x="312" y="117" width="26" height="16" rx="2" fill="#0f172a"/>
                                <text x="325" y="129" fill="#ef4444" font-size="8.5" font-family="monospace" font-weight="bold" text-anchor="middle">88.00<tspan font-size="5">kg</tspan></text>

                                <!-- Cardboard Box / Parcel on Scale Platform -->
                                <g transform="translate(170, 150)">
                                    <!-- Box Top Face -->
                                    <path d="M55 0 L110 22 L55 45 L0 22 Z" fill="url(#boxTop)" stroke="#f59e0b" stroke-width="0.75"/>
                                    <!-- Box Left Face -->
                                    <path d="M0 22 L55 45 L55 105 L0 82 Z" fill="url(#boxLeft)" stroke="#d97706" stroke-width="0.75"/>
                                    <!-- Box Right Face -->
                                    <path d="M55 45 L110 22 L110 82 L55 105 Z" fill="url(#boxRight)" stroke="#b45309" stroke-width="0.75"/>

                                    <!-- Packing Tape across box -->
                                    <path d="M27.5 11 L82.5 33.5 L82.5 93.5 L27.5 71 Z" fill="#f8fafc" opacity="0.65"/>
                                    <path d="M0 50 L55 73 L110 50 L110 57 L55 80 L0 57 Z" fill="#e2e8f0" opacity="0.6"/>
                                </g>
                            </svg>
                        </div>
                    </div>

                    <!-- Loading State -->
                    <div x-show="calc.loading" class="flex-1 flex flex-col items-center justify-center text-center p-8 space-y-3">
                        <i class="fa-solid fa-circle-notch fa-spin text-4xl text-indigo-300"></i>
                        <p class="text-sm font-bold text-indigo-100">Calculating real-time rates from active courier partners...</p>
                    </div>

                    <!-- Error State -->
                    <div x-show="calc.error && !calc.loading" class="p-4 bg-red-500/20 border border-red-400/40 rounded-2xl text-red-200 text-xs font-semibold text-center my-auto">
                        <i class="fa-solid fa-circle-exclamation mr-1"></i> <span x-text="calc.error"></span>
                    </div>

                    <!-- Rates Accordion List (Single Card Expandable at a Time) -->
                    <div x-show="calc.hasResults && !calc.loading" class="space-y-4 w-full overflow-y-auto max-h-[550px] pr-1">
                        <template x-for="rate in calc.rates" :key="rate.courier_id">
                            <div class="bg-white rounded-2xl overflow-hidden shadow-md transition text-gray-800">
                                
                                <!-- Card Header Summary (Click to Toggle) -->
                                <div @click="calc.expandedCourier = (calc.expandedCourier === rate.courier_id ? null : rate.courier_id)" class="p-4 flex items-center justify-between cursor-pointer hover:bg-gray-50 transition border-b border-gray-100">
                                    
                                    <!-- Courier Brand / Logo -->
                                    <div class="flex items-center gap-3 min-w-[140px]">
                                        <div class="w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center text-indigo-700 font-extrabold text-xs shadow-inner">
                                            <i :class="calc.type === 'international' ? 'fa-solid fa-plane-departure' : 'fa-solid fa-truck'"></i>
                                        </div>
                                        <div>
                                            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                                                <span x-text="rate.recommended ? 'Recommended Partner' : 'Courier'"></span>
                                            </div>
                                            <div class="text-xs font-extrabold text-gray-900 tracking-tight" x-text="rate.courier_name"></div>
                                        </div>
                                    </div>

                                    <!-- TAT & Zone Info -->
                                    <div class="hidden sm:flex items-center gap-4 text-center px-2">
                                        <div>
                                            <div class="text-[10px] text-gray-400 font-bold uppercase">TAT</div>
                                            <div class="text-xs font-bold text-gray-800" x-text="rate.tat + (rate.tat.toString().includes('Day') ? '' : ' Days')"></div>
                                        </div>
                                        <div>
                                            <div class="text-[10px] text-gray-400 font-bold uppercase">Zone</div>
                                            <div class="text-xs font-bold text-gray-800 truncate max-w-[120px]" x-text="rate.tat_zone"></div>
                                        </div>
                                    </div>

                                    <!-- Weight & Total Rate -->
                                    <div class="flex items-center gap-4 text-right">
                                        <div>
                                            <div class="text-[10px] text-gray-400 font-bold uppercase">Total Freight</div>
                                            <div class="text-sm font-extrabold text-blue-700 font-mono">
                                                &#8377;<span x-text="rate.total_rate.toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                                            </div>
                                        </div>

                                        <div class="w-7 h-7 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 transition-transform duration-200" :class="calc.expandedCourier === rate.courier_id ? 'rotate-90' : ''">
                                            <i class="fa-solid fa-chevron-right text-xs"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Expanded Itemized Charges Breakdown -->
                                <div x-show="calc.expandedCourier === rate.courier_id" x-collapse class="p-5 bg-gray-50/50 space-y-2 text-xs border-t border-gray-100 font-medium">
                                    <div class="flex justify-between items-center py-1 border-b border-gray-100">
                                        <span class="text-gray-600 font-semibold">Base Charge</span>
                                        <span class="font-extrabold text-gray-900 font-mono">&#8377;<span x-text="rate.base_charge.toFixed(2)"></span></span>
                                    </div>
                                    
                                    <template x-if="rate.customs_clearance && rate.customs_clearance > 0">
                                        <div class="flex justify-between items-center py-1 border-b border-gray-100">
                                            <span class="text-gray-600 font-semibold">Customs Clearance & Documentation</span>
                                            <span class="font-extrabold text-gray-900 font-mono">&#8377;<span x-text="rate.customs_clearance.toFixed(2)"></span></span>
                                        </div>
                                    </template>

                                    <template x-for="charge in rate.dynamic_charges" :key="charge.name">
                                        <div class="flex justify-between items-center py-1 border-b border-gray-100">
                                            <span class="text-gray-600 font-semibold" x-text="charge.name"></span>
                                            <span class="font-extrabold text-gray-900 font-mono">&#8377;<span x-text="charge.amount.toFixed(2)"></span></span>
                                        </div>
                                    </template>

                                    <template x-if="rate.warai_charge && rate.warai_charge > 0">
                                        <div class="flex justify-between items-center py-1 border-b border-gray-100">
                                            <span class="text-gray-600 font-semibold">Warai / FSC Fuel Surcharge</span>
                                            <span class="font-extrabold text-gray-900 font-mono">&#8377;<span x-text="rate.warai_charge.toFixed(2)"></span></span>
                                        </div>
                                    </template>

                                    <template x-if="rate.to_pay && rate.to_pay > 0">
                                        <div class="flex justify-between items-center py-1 border-b border-gray-100">
                                            <span class="text-gray-600 font-semibold">To Pay</span>
                                            <span class="font-extrabold text-gray-900 font-mono">&#8377;<span x-text="rate.to_pay.toFixed(2)"></span></span>
                                        </div>
                                    </template>

                                    <template x-if="rate.third_party_insurance && rate.third_party_insurance > 0">
                                        <div class="flex justify-between items-center py-1 border-b border-gray-100">
                                            <span class="text-gray-600 font-semibold">Third Party Insurance</span>
                                            <span class="font-extrabold text-gray-900 font-mono">&#8377;<span x-text="rate.third_party_insurance.toFixed(2)"></span></span>
                                        </div>
                                    </template>

                                    <template x-if="rate.cod_charges && rate.cod_charges > 0">
                                        <div class="flex justify-between items-center py-1 border-b border-gray-100">
                                            <span class="text-gray-600 font-semibold">COD Charges</span>
                                            <span class="font-extrabold text-gray-900 font-mono">&#8377;<span x-text="rate.cod_charges.toFixed(2)"></span></span>
                                        </div>
                                    </template>

                                    <template x-if="rate.state_tax && rate.state_tax > 0">
                                        <div class="flex justify-between items-center py-1 border-b border-gray-100">
                                            <span class="text-gray-600 font-semibold">State Tax / IGST (18%)</span>
                                            <span class="font-extrabold text-gray-900 font-mono">&#8377;<span x-text="rate.state_tax.toFixed(2)"></span></span>
                                        </div>
                                    </template>

                                    <!-- Bottom Total & Action Button -->
                                    <div class="flex items-center justify-between pt-3">
                                        <div>
                                            <span class="text-[11px] text-gray-500 font-bold uppercase">Estimated Total</span>
                                            <div class="text-base font-extrabold text-gray-900 font-mono">
                                                &#8377;<span x-text="rate.total_rate.toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                                            </div>
                                        </div>
                                      
                                    </div>
                                </div>

                            </div>
                        </template>
                    </div>

                </div>

            </div>
        </div>

        <!-- TAB 2: Rate Chart -->
        <div x-show="activeTab === 'chart'" class="p-6 md:p-8 bg-white rounded-3xl border border-gray-200 shadow-sm min-h-[500px]" style="display: none;">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Standard Rate Chart</h2>
                <div class="bg-white border border-gray-200 rounded-2xl overflow-x-auto shadow-sm">
                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 font-bold uppercase">
                                <tr>
                                    <th class="px-6 py-4">Courier</th>
                                    <th class="px-6 py-4">Weight Slab</th>
                                    <th class="px-6 py-4">Forward Rate (Local)</th>
                                    <th class="px-6 py-4">Forward Rate (National)</th>
                                    <th class="px-6 py-4">COD %</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($rateChart ?? [] as $rate)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-bold text-gray-900">{{ $rate['courier'] }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $rate['weight_slab'] }}</td>
                                    <td class="px-6 py-4 text-gray-700 font-medium">&#8377; {{ number_format($rate['forward_local'], 2) }}</td>
                                    <td class="px-6 py-4 text-gray-700 font-medium">&#8377; {{ number_format($rate['forward_national'], 2) }}</td>
                                    <td class="px-6 py-4 text-gray-700 font-medium">{{ $rate['cod_percent'] }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">No active rate charts found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 3: Serviceable Pincodes -->
        <div x-show="activeTab === 'serviceability'" class="p-6 md:p-8 bg-white rounded-3xl border border-gray-200 shadow-sm min-h-[500px]" style="display: none;">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 max-w-6xl mx-auto">
                
                <!-- Left Box: Download Serviceable Pincodes List -->
                <div class="lg:col-span-4 bg-gray-50/70 p-6 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-gray-900 text-base mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-map-location-dot text-[#4338ca]"></i> Download Serviceable Pincodes List
                        </h3>

                        <form action="{{ route('seller.tools.pincodes.export') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Pickup Pincode <span class="text-gray-400 font-normal">(For Zone Mapping)</span></label>
                                <input type="text" name="pickup_pincode" maxlength="6" placeholder="e.g. 110001" required class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#4338ca] font-medium bg-white">
                            </div>

                            <button type="submit" class="w-full py-2.5 bg-[#818cf8] hover:bg-[#6366f1] text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center justify-center gap-2">
                                <i class="fa-solid fa-download"></i> Download
                            </button>
                        </form>
                    </div>

                    <p class="text-[11px] text-gray-400 mt-6 leading-relaxed">
                        Exports run in the background. You can queue more pincodes while others are processing — track them under Download History.
                    </p>
                </div>

                <!-- Right Box: Download History -->
                <div class="lg:col-span-8 bg-gray-50/70 p-6 rounded-2xl border border-gray-200/80 shadow-sm">
                    <h3 class="font-bold text-gray-900 text-base mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-gray-400"></i> Download History
                    </h3>

                    <!-- Search Filter Row -->
                    <div class="flex flex-wrap items-center gap-2 mb-6">
                        <input type="text" placeholder="Pincode" class="border border-gray-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-gray-700 outline-none w-32 bg-white">
                        
                        <select class="border border-gray-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-gray-700 outline-none bg-white">
                            <option>All Status</option>
                            <option>Completed</option>
                            <option>Processing</option>
                        </select>

                        <div class="border border-gray-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-gray-700 flex items-center gap-2 bg-white">
                            <span>26/08/2026 ~ 25/09/2026</span>
                            <i class="fa-solid fa-xmark text-gray-400 cursor-pointer"></i>
                        </div>
                    </div>

                    <!-- History Items Container -->
                    <div class="space-y-3">
                        <div class="text-center py-10">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 text-gray-400 mb-3">
                                <i class="fa-solid fa-folder-open text-xl"></i>
                            </div>
                            <p class="text-sm font-semibold text-gray-600">No export history found.</p>
                            <p class="text-xs text-gray-400 mt-1">Start a new download from the left panel.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 4: Activity Logs -->
        <div x-show="activeTab === 'logs'" class="p-6 md:p-8 bg-white rounded-3xl border border-gray-200 shadow-sm min-h-[500px]" style="display: none;">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-xl font-bold text-gray-900 mb-4">System Activity Logs</h2>
                <div class="bg-white border border-gray-200 rounded-2xl overflow-x-auto shadow-sm">
                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 font-bold uppercase">
                                <tr>
                                    <th class="px-6 py-4">Date / Time</th>
                                    <th class="px-6 py-4">Action</th>
                                    <th class="px-6 py-4">Details</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($activityLogs ?? [] as $log)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-gray-500 font-medium">{{ \Carbon\Carbon::parse($log['date'])->diffForHumans() }}</td>
                                    <td class="px-6 py-4 font-bold text-gray-900">
                                        <span class="px-2 py-1 rounded text-[10px] uppercase mr-2 border {{ $log['action_class'] ?? 'bg-blue-50 text-blue-700 border-blue-100' }}">
                                            {{ $log['action'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">{{ $log['details'] }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-500">No activity logged yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function toolsManager() {
    return {
        activeTab: 'calculator',
        
        calc: {
            type: 'domestic',
            pickup: '',
            delivery: '',
            pickupCity: '',
            deliveryCity: '',
            resultPickup: '',
            resultDelivery: '',
            resultPickupCity: '',
            resultDeliveryCity: '',
            payment: 'prepaid',
            riskType: 'owner_risk',
            isMultiBox: false,
            invoiceAmount: '',
            codAmount: '',
            codAmountManual: false,

            // International Specific Fields
            intlPickupCountry: 'India',
            intlDestCountry: '',
            intlServiceType: 'World Wide Parcel',
            intlCategory: '',
            intlSubCategory: 'General Merchandise',
            resultIntlPickup: '',
            resultIntlDest: '',

            boxes: [
                { no_of_boxes: 1, length: 10, height: 10, width: 10, weight: 0.5 }
            ],
            chargeableWeight: 0.5,
            loading: false,
            hasResults: false,
            error: null,
            rates: [],
            expandedCourier: null,
        },

        pincodeCheck: '',
        pincodeLoading: false,
        pincodeResult: false,

        init() {
            // Clean Start
        },

        setCalcType(type) {
            this.calc.type = type;
            this.calc.hasResults = false;
            this.calc.rates = [];
            this.calc.error = null;
            this.calc.expandedCourier = null;
        },

        addBoxRow() {
            this.calc.boxes.push({ no_of_boxes: 1, length: 10, height: 10, width: 10, weight: 0.5 });
        },

        removeBoxRow(idx) {
            if (this.calc.boxes.length > 1) {
                this.calc.boxes.splice(idx, 1);
            }
        },

        resetForm() {
            this.calc.pickup = '';
            this.calc.delivery = '';
            this.calc.pickupCity = '';
            this.calc.deliveryCity = '';
            this.calc.resultPickup = '';
            this.calc.resultDelivery = '';
            this.calc.resultPickupCity = '';
            this.calc.resultDeliveryCity = '';
            this.calc.payment = 'prepaid';
            this.calc.riskType = 'owner_risk';
            this.calc.isMultiBox = false;
            this.calc.invoiceAmount = '';
            this.calc.codAmount = '';
            this.calc.codAmountManual = false;

            this.calc.intlDestCountry = '';
            this.calc.intlServiceType = 'World Wide Parcel';
            this.calc.intlCategory = '';
            this.calc.intlSubCategory = 'General Merchandise';
            this.calc.resultIntlPickup = '';
            this.calc.resultIntlDest = '';

            this.calc.boxes = [
                { no_of_boxes: 1, length: 10, height: 10, width: 10, weight: 0.5 }
            ];
            this.calc.hasResults = false;
            this.calc.rates = [];
            this.calc.expandedCourier = null;
            this.calc.error = null;
        },

        async fetchCity(pincode, targetVar) {
            if(!pincode || pincode.length !== 6) {
                this.calc[targetVar] = '';
                return;
            }
            try {
                let res = await fetch(`https://api.postalpincode.in/pincode/${pincode}`);
                let data = await res.json();
                if(data && data[0].Status === 'Success') {
                    let po = data[0].PostOffice[0];
                    this.calc[targetVar] = `${po.District}, ${po.State}`;
                } else {
                    this.calc[targetVar] = '';
                }
            } catch(e) {
                this.calc[targetVar] = '';
            }
        },
        
        async calculateRates() {
            if (this.calc.type === 'domestic') {
                if(!this.calc.pickup || this.calc.pickup.length !== 6 || !this.calc.delivery || this.calc.delivery.length !== 6) {
                    this.calc.error = "Please enter valid 6-digit pickup & destination pincodes.";
                    return;
                }
            } else {
                if(!this.calc.intlDestCountry) {
                    this.calc.error = "Please enter a valid Destination Country.";
                    return;
                }
                if(!this.calc.intlCategory) {
                    this.calc.error = "Please select a Shipment Category.";
                    return;
                }
            }

            this.calc.loading = true;
            this.calc.error = null;
            this.calc.hasResults = false;
            
            try {
                let payload = {};
                if (this.calc.type === 'domestic') {
                    payload = {
                        shipment_type: 'domestic',
                        pickup_pincode: this.calc.pickup,
                        delivery_pincode: this.calc.delivery,
                        payment_mode: this.calc.payment,
                        risk_type: this.calc.riskType,
                        invoice_amount: this.calc.invoiceAmount || 0,
                        cod_amount: this.calc.payment === 'cod' ? (this.calc.codAmount || this.calc.invoiceAmount || 0) : 0,
                        boxes: this.calc.boxes
                    };
                } else {
                    payload = {
                        shipment_type: 'international',
                        pickup_country: this.calc.intlPickupCountry || 'India',
                        destination_country: this.calc.intlDestCountry,
                        shipment_service_type: this.calc.intlServiceType,
                        shipment_category: this.calc.intlCategory,
                        shipment_sub_category: this.calc.intlSubCategory,
                        invoice_amount: this.calc.invoiceAmount || 0,
                        boxes: this.calc.boxes
                    };
                }

                let response = await fetch('/api/v1/public/rates', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'Accept': 'application/json', 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                    },
                    body: JSON.stringify(payload)
                });
                
                let result = await response.json();
                if(response.ok && result.success && result.data.length > 0) {
                    this.calc.rates = result.data;
                    this.calc.chargeableWeight = result.chargeable_weight || 0.5;
                    
                    if (this.calc.type === 'domestic') {
                        this.calc.resultPickup = this.calc.pickup;
                        this.calc.resultDelivery = this.calc.delivery;
                        this.calc.resultPickupCity = this.calc.pickupCity;
                        this.calc.resultDeliveryCity = this.calc.deliveryCity;
                    } else {
                        this.calc.resultIntlPickup = result.pickup_country || 'India';
                        this.calc.resultIntlDest = result.destination_country || this.calc.intlDestCountry;
                    }

                    this.calc.hasResults = true;
                } else {
                    this.calc.error = result.message || 'No active courier services available for this route.';
                }
            } catch(e) {
                this.calc.error = 'Failed to connect to rate calculation server. Please try again.';
            }
            this.calc.loading = false;
        },

        async checkPincode() {
            if(this.pincodeCheck.length !== 6) return;
            this.pincodeLoading = true;
            this.pincodeResult = false;
            
            setTimeout(() => {
                this.pincodeResult = true;
                this.pincodeLoading = false;
            }, 600);
        }
    }
}
</script>
@endsection
