@extends('layouts.public')
@section('title', "Calculate Shipping Rates | OneStall Cargo")

@section('content')
<!-- 1. Hero Section -->
<section class="bg-gray-50 pt-10 pb-6 md:pt-12 md:pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center gap-12">
        <div class="flex-1 text-center md:text-left">
            <h1 class="text-4xl md:text-5xl font-extrabold text-[#0a1930] leading-[1.1] mb-4 tracking-tight">
                <span class="text-[#d80032]">Calculate</span> Shipping<br>Rates in Seconds
            </h1>
            <p class="text-base md:text-lg text-gray-600 mb-6 font-medium max-w-lg mx-auto md:mx-0 leading-relaxed">
                Stop guessing your delivery cost. Instantly compare courier prices and choose what works best for your business.
            </p>
            <a href="#calculator" class="inline-block px-8 py-3.5 rounded-full bg-[#d80032] text-white font-bold text-sm hover:bg-[#b00028] transition shadow-md">
                Calculate Now
            </a>
        </div>
        <div class="flex-1 hidden md:flex justify-end">
            <!-- Clean, professional image presentation -->
            <img src="/images/pricing_hero.jpg" alt="Shipping Options" class="rounded-2xl shadow-xl w-full max-w-md object-cover h-[350px]">
        </div>
    </div>
</section>

<!-- 2. Integrated Calculator Section -->
<section id="calculator" class="pb-16 pt-6 bg-gray-50" x-data="liveRateCalculator()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Main Burgundy Card -->
        <!-- Main Light Card -->
        <div class="bg-white rounded-3xl p-6 md:p-10 shadow-xl border border-gray-100">
            
            <div class="flex flex-col lg:flex-row gap-10">
                
                <!-- Left Side: Image -->
                <div class="hidden lg:block w-[40%]">
                    <div class="h-full w-full rounded-2xl overflow-hidden relative">
                        <img src="/images/pricing_calculator.jpg" alt="Logistics" class="object-cover w-full h-full">
                        <!-- Floating Badges mimicking the reference -->
                        <div class="absolute top-1/4 right-4 bg-white p-2 rounded-lg shadow-lg">
                            <div class="bg-yellow-400 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold text-sm">&#8377;</div>
                        </div>
                        <div class="absolute bottom-1/4 left-4 bg-white p-2 rounded-lg shadow-lg">
                            <i class="fa-solid fa-calculator text-gray-700 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Right Side: The Form -->
                <div class="flex-1">
                    <form @submit.prevent="fetchRate" class="space-y-6">
                        
                        <!-- Row 1 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Pickup Area Pin Code*</label>
                                <input type="text" x-model="pickup_pincode" @input="fetchCity(pickup_pincode, 'pickupCity')" maxlength="6" required class="w-full bg-gray-50 border rounded-lg px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-1 transition" :class="pickupCity ? (pickupCity === 'Invalid Pincode' ? 'border-red-400 focus:border-red-400 focus:ring-red-400' : 'border-green-400 focus:border-green-400 focus:ring-green-400') : 'border-gray-200 focus:border-[#4338ca] focus:ring-[#4338ca]'" placeholder="e.g. 110001">
                                <div x-show="pickupCity" class="mt-1 text-[10px] font-bold flex items-center gap-1" :class="pickupCity === 'Invalid Pincode' ? 'text-red-500' : 'text-green-600'">
                                    <i class="fa-solid fa-location-dot" x-show="pickupCity !== 'Invalid Pincode'"></i>
                                    <span x-text="pickupCity"></span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Delivery Area Pin Code*</label>
                                <input type="text" x-model="delivery_pincode" @input="fetchCity(delivery_pincode, 'deliveryCity')" maxlength="6" required class="w-full bg-gray-50 border rounded-lg px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-1 transition" :class="deliveryCity ? (deliveryCity === 'Invalid Pincode' ? 'border-red-400 focus:border-red-400 focus:ring-red-400' : 'border-green-400 focus:border-green-400 focus:ring-green-400') : 'border-gray-200 focus:border-[#4338ca] focus:ring-[#4338ca]'" placeholder="e.g. 400001">
                                <div x-show="deliveryCity" class="mt-1 text-[10px] font-bold flex items-center gap-1" :class="deliveryCity === 'Invalid Pincode' ? 'text-red-500' : 'text-green-600'">
                                    <i class="fa-solid fa-location-dot" x-show="deliveryCity !== 'Invalid Pincode'"></i>
                                    <span x-text="deliveryCity"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Row 2 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Weight*</label>
                                <div class="flex shadow-sm rounded-lg">
                                    <input type="number" step="0.1" x-model.number="weightKg" required class="flex-1 bg-gray-50 border border-gray-200 rounded-l-lg px-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] transition" placeholder="0.5">
                                    <span class="inline-flex items-center px-4 rounded-r-lg border border-l-0 border-gray-200 bg-gray-100 text-gray-500 font-bold text-xs">kg</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Package Dimensions (L x W x H)</label>
                                <div class="flex items-center gap-2">
                                    <input type="number" placeholder="L" x-model="dim_l" class="flex-1 w-full min-w-0 bg-gray-50 border border-gray-200 rounded-lg px-2 py-3 text-center text-sm text-gray-900 focus:outline-none focus:border-[#4338ca] transition">
                                    <span class="text-gray-400 text-xs font-bold">×</span>
                                    <input type="number" placeholder="W" x-model="dim_w" class="flex-1 w-full min-w-0 bg-gray-50 border border-gray-200 rounded-lg px-2 py-3 text-center text-sm text-gray-900 focus:outline-none focus:border-[#4338ca] transition">
                                    <span class="text-gray-400 text-xs font-bold">×</span>
                                    <input type="number" placeholder="H" x-model="dim_h" class="flex-1 w-full min-w-0 bg-gray-50 border border-gray-200 rounded-lg px-2 py-3 text-center text-sm text-gray-900 focus:outline-none focus:border-[#4338ca] transition">
                                    <span class="text-gray-500 text-xs font-bold pl-1">CM</span>
                                </div>
                            </div>
                        </div>

                        <!-- Row 3 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-2">Payment Mode*</label>
                                <div class="flex gap-4 mt-2">
                                    <label class="flex items-center gap-2 cursor-pointer text-gray-800 font-medium text-sm">
                                        <input type="radio" x-model="payment_mode" value="prepaid" class="w-4 h-4 text-[#4338ca] border-gray-300 focus:ring-[#4338ca]">
                                        <span>Prepaid</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer text-gray-800 font-medium text-sm">
                                        <input type="radio" x-model="payment_mode" value="cod" class="w-4 h-4 text-[#4338ca] border-gray-300 focus:ring-[#4338ca]">
                                        <span>Cash on Delivery</span>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Shipment Value*</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-bold text-sm">&#8377;</span>
                                    <input type="number" x-model="shipment_value" required class="w-full bg-gray-50 border border-gray-200 rounded-lg pl-8 pr-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] transition" placeholder="1000">
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-4 pt-4 border-t border-gray-100 mt-6">
                            <button type="submit" class="px-8 py-3 rounded-xl bg-[#4338ca] text-white font-bold text-sm hover:bg-[#3730a3] shadow-md transition min-w-[160px]">
                                <span x-show="!loading">Calculate Now</span>
                                <span x-show="loading"><i class="fa-solid fa-spinner fa-spin"></i></span>
                            </button>
                            <button type="button" @click="resetForm()" class="px-8 py-3 rounded-xl border border-gray-200 bg-white text-gray-700 font-bold text-sm hover:bg-gray-50 transition">
                                Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- RESULTS SECTION -->
            <div x-show="showResults" style="display: none;" class="mt-12 pt-8 border-t border-gray-100">
                
                <!-- Filters -->
                <div class="flex gap-3 mb-8 justify-center">
                    <button @click="filterMode = 'all'" :class="filterMode === 'all' ? 'bg-[#4338ca] text-white shadow-md' : 'bg-white border border-gray-200 text-gray-600 hover:border-[#4338ca] hover:text-[#4338ca]'" class="px-6 py-2 rounded-full font-bold text-xs transition">All</button>
                    <button @click="filterMode = 'air'" :class="filterMode === 'air' ? 'bg-[#4338ca] text-white shadow-md' : 'bg-white border border-gray-200 text-gray-600 hover:border-[#4338ca] hover:text-[#4338ca]'" class="px-6 py-2 rounded-full font-bold text-xs transition">Air</button>
                    <button @click="filterMode = 'surface'" :class="filterMode === 'surface' ? 'bg-[#4338ca] text-white shadow-md' : 'bg-white border border-gray-200 text-gray-600 hover:border-[#4338ca] hover:text-[#4338ca]'" class="px-6 py-2 rounded-full font-bold text-xs transition">Surface</button>
                </div>

                <!-- Error Message -->
                <div x-show="error" class="text-red-500 bg-red-50 border border-red-100 rounded-lg text-sm font-bold py-4 text-center">
                    <span x-text="error"></span>
                </div>

                <!-- Card Layout -->
                <div x-show="!error && rates.length > 0" class="w-full max-w-2xl mx-auto">
                    <div class="text-center pb-4 px-4 w-full">
                        <h2 class="text-gray-900 text-xl md:text-2xl font-black leading-relaxed mb-8 px-4">
                            Rates for Shipping your Package from<br>
                            <span class="text-[#4338ca]">(<span x-text="pickup_pincode"></span>)</span> to <span class="text-[#4338ca]">(<span x-text="delivery_pincode"></span>)</span>
                        </h2>
                        
                        <div class="space-y-4">
                            <template x-for="rate in rates" :key="rate.courier_name">
                                <div>
                                    <!-- Surface Card -->
                                    <div x-show="filterMode === 'all' || filterMode === 'surface'" x-data="{ expanded: false }" class="bg-white rounded-xl border border-gray-200 flex flex-col overflow-hidden shadow-sm hover:border-[#4338ca] transition-colors mb-3">
                                        <div @click="expanded = !expanded" class="p-4 flex items-center justify-between cursor-pointer hover:bg-gray-50 transition">
                                            
                                            <!-- Courier Logo/Name -->
                                            <div class="flex-1 max-w-[35%] flex flex-col text-left justify-center">
                                                <div class="font-extrabold text-[12px] md:text-[14px] text-gray-900 uppercase tracking-tight" x-text="rate.courier_name"></div>
                                                <div class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1">Surface</div>
                                            </div>
                                            
                                            <!-- Weight & Rate -->
                                            <div class="flex-1 flex justify-around items-center px-2 border-l border-r border-gray-100">
                                                <div class="text-center">
                                                    <div class="text-[10px] text-gray-400 mb-0.5">Weight</div>
                                                    <div class="text-[12px] md:text-[14px] font-bold text-gray-800" x-text="Math.max(weight, (l*b*h)/5000).toFixed(2)"></div>
                                                </div>
                                                <div class="text-center">
                                                    <div class="text-[10px] text-gray-400 mb-0.5">Rate</div>
                                                    <div class="text-[12px] md:text-[14px] font-bold text-gray-800">&#8377;<span x-text="rate.rate.toFixed(2)"></span></div>
                                                </div>
                                            </div>
                                            
                                            <!-- Chevron -->
                                            <div class="pl-3 transition-transform duration-200" :class="expanded ? 'rotate-90' : ''">
                                                <i class="fa-solid fa-chevron-right text-[#4338ca] text-lg"></i>
                                            </div>
                                        </div>
                                        
                                        <!-- Expanded Details -->
                                        <div x-show="expanded" class="border-t border-gray-100 bg-[#f8faff] p-4 text-left text-xs" x-collapse>
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <div class="text-gray-400 mb-1">Estimated Delivery</div>
                                                    <div class="font-bold text-gray-800"><span x-text="rate.estimated_delivery_days"></span> Days</div>
                                                </div>
                                                <div>
                                                    <div class="text-gray-400 mb-1">Service Type</div>
                                                    <div class="font-bold text-[#4338ca]">Surface (Ground)</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Air Card -->
                                    <div x-show="filterMode === 'all' || filterMode === 'air'" x-data="{ expanded: false }" class="bg-white rounded-xl border border-gray-200 flex flex-col overflow-hidden shadow-sm hover:border-[#4338ca] transition-colors">
                                        <div @click="expanded = !expanded" class="p-4 flex items-center justify-between cursor-pointer hover:bg-gray-50 transition">
                                            
                                            <!-- Courier Logo/Name -->
                                            <div class="flex-1 max-w-[35%] flex flex-col text-left justify-center">
                                                <div class="font-extrabold text-[12px] md:text-[14px] text-gray-900 uppercase tracking-tight" x-text="rate.courier_name"></div>
                                                <div class="text-[9px] text-[#4338ca] font-bold uppercase tracking-widest mt-1">Air</div>
                                            </div>
                                            
                                            <!-- Weight & Rate -->
                                            <div class="flex-1 flex justify-around items-center px-2 border-l border-r border-gray-100">
                                                <div class="text-center">
                                                    <div class="text-[10px] text-gray-400 mb-0.5">Weight</div>
                                                    <div class="text-[12px] md:text-[14px] font-bold text-gray-800" x-text="Math.max(weight, (l*b*h)/5000).toFixed(2)"></div>
                                                </div>
                                                <div class="text-center">
                                                    <div class="text-[10px] text-gray-400 mb-0.5">Rate</div>
                                                    <div class="text-[12px] md:text-[14px] font-bold text-gray-800">&#8377;<span x-text="rate.rate.toFixed(2)"></span></div>
                                                </div>
                                            </div>
                                            
                                            <!-- Chevron -->
                                            <div class="pl-3 transition-transform duration-200" :class="expanded ? 'rotate-90' : ''">
                                                <i class="fa-solid fa-chevron-right text-[#4338ca] text-lg"></i>
                                            </div>
                                        </div>
                                        
                                        <!-- Expanded Details -->
                                        <div x-show="expanded" class="border-t border-gray-100 bg-[#f8faff] p-4 text-left text-xs" x-collapse>
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <div class="text-gray-400 mb-1">Estimated Delivery</div>
                                                    <div class="font-bold text-gray-800"><span x-text="Math.max(1, rate.estimated_delivery_days - 1)"></span> Days</div>
                                                </div>
                                                <div>
                                                    <div class="text-gray-400 mb-1">Service Type</div>
                                                    <div class="font-bold text-[#4338ca]">Air (Express)</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div x-show="!error && rates.length > 0" class="text-center mt-8">
                    <a href="{{ route('register') }}" class="inline-block px-10 py-3 rounded-full bg-[#d80032] text-white font-bold text-sm hover:bg-[#b00028] transition">
                        Ship Now
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- 3. Features Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center mb-16">
        <h2 class="text-3xl md:text-4xl font-extrabold text-[#0a1930] mb-2">Built for Sellers Who Want</h2>
        <h3 class="text-3xl md:text-4xl font-extrabold text-[#d80032]">Smarter Shipping</h3>
    </div>

    <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center gap-16">
        <div class="flex-1 w-full max-w-md mx-auto">
            <img src="/images/warehouse.jpg" alt="Smart Shipping" class="rounded-2xl shadow-lg w-full h-[400px] object-cover">
        </div>
        <div class="flex-1 space-y-8">
            <div>
                <h4 class="text-lg font-bold text-[#0a1930] mb-1">See real-time rates across couriers</h4>
                <p class="text-sm text-gray-600">Instantly compare prices from top couriers directly on a single dashboard.</p>
            </div>
            <div>
                <h4 class="text-lg font-bold text-[#0a1930] mb-1">Avoid RTOs with smarter decisions</h4>
                <p class="text-sm text-gray-600">Match the shipping mode based on your customers' expectations and delivery urgency.</p>
            </div>
            <div>
                <h4 class="text-lg font-bold text-[#0a1930] mb-1">No more manual estimates</h4>
                <p class="text-sm text-gray-600">Calculate the accurate rate depending on your parcel's volumetric weight seamlessly.</p>
            </div>
            <div>
                <h4 class="text-lg font-bold text-[#0a1930] mb-1">Choose the best courier option</h4>
                <p class="text-sm text-gray-600">Know which courier is the most economical and fastest before booking an order.</p>
            </div>
        </div>
    </div>
</section>

<script>
function liveRateCalculator() {
    return {
        filterMode: 'all',
        pickup_pincode: '',
        delivery_pincode: '',
        pickupCity: '',
        deliveryCity: '',
        weightKg: '',
        dim_l: '',
        dim_w: '',
        dim_h: '',
        payment_mode: 'prepaid',
        shipment_value: '',
        
        loading: false,
        showResults: false,
        error: null,
        rates: [],

        resetForm() {
            this.pickup_pincode = '';
            this.delivery_pincode = '';
            this.pickupCity = '';
            this.deliveryCity = '';
            this.weightKg = '';
            this.dim_l = '';
            this.dim_w = '';
            this.dim_h = '';
            this.payment_mode = 'prepaid';
            this.shipment_value = '';
            this.showResults = false;
        },

        async fetchCity(pincode, targetVar) {
            if(pincode.length !== 6) {
                this[targetVar] = '';
                return;
            }
            try {
                let res = await fetch(`https://api.postalpincode.in/pincode/${pincode}`);
                let data = await res.json();
                if(data && data[0].Status === 'Success') {
                    let po = data[0].PostOffice[0];
                    this[targetVar] = `${po.District}, ${po.State}`;
                } else {
                    this[targetVar] = 'Invalid Pincode';
                }
            } catch(e) {
                this[targetVar] = '';
            }
        },

        async fetchRate() {
            if (this.pickup_pincode.length === 6 && this.delivery_pincode.length === 6 && this.weightKg > 0) {
                this.loading = true;
                this.showResults = true;
                this.error = null;
                this.rates = [];
                
                try {
                    let response = await fetch('/api/v1/public/rates', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            pickup_pincode: this.pickup_pincode,
                            delivery_pincode: this.delivery_pincode,
                            weight: this.weightKg,
                            payment_mode: this.payment_mode,
                            shipment_value: this.shipment_value,
                            dimensions: { l: this.dim_l, w: this.dim_w, h: this.dim_h }
                        })
                    });
                    
                    let result = await response.json();
                    
                    if (response.ok && result.success && result.data.length > 0) {
                        this.rates = result.data;
                    } else {
                        this.error = result.message || 'No service available for this route.';
                    }
                } catch (err) {
                    this.error = 'Failed to fetch rates from server.';
                } finally {
                    this.loading = false;
                }
            } else {
                alert("Please fill Pincodes and Weight correctly.");
            }
        }
    }
}
</script>
@endsection
