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
        <div class="bg-[#8a0b38] rounded-3xl p-6 md:p-10 shadow-2xl">
            
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
                                <label class="block text-xs font-medium text-white mb-1">Pickup Area Pin Code*</label>
                                <input type="text" x-model="pickup_pincode" maxlength="6" required class="w-full bg-transparent border border-white/30 rounded-md px-4 py-3 text-sm text-white focus:outline-none focus:border-white transition" placeholder="e.g. 110001">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-white mb-1">Delivery Area Pin Code*</label>
                                <input type="text" x-model="delivery_pincode" maxlength="6" required class="w-full bg-transparent border border-white/30 rounded-md px-4 py-3 text-sm text-white focus:outline-none focus:border-white transition" placeholder="e.g. 400001">
                            </div>
                        </div>

                        <!-- Row 2 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-medium text-white mb-1">Weight*</label>
                                <div class="flex relative">
                                    <input type="number" step="0.1" x-model.number="weightKg" required class="w-full bg-transparent border border-white/30 rounded-md px-4 py-3 text-sm text-white focus:outline-none focus:border-white transition" placeholder="0.5">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-white/70 text-xs">kg</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-white mb-1">Package Dimensions</label>
                                <div class="flex items-center gap-2">
                                    <input type="number" placeholder="L" x-model="dim_l" class="w-full bg-transparent border border-white/30 rounded-md px-2 py-3 text-center text-sm text-white focus:outline-none focus:border-white transition">
                                    <span class="text-white/70 text-xs">X</span>
                                    <input type="number" placeholder="W" x-model="dim_w" class="w-full bg-transparent border border-white/30 rounded-md px-2 py-3 text-center text-sm text-white focus:outline-none focus:border-white transition">
                                    <span class="text-white/70 text-xs">X</span>
                                    <input type="number" placeholder="H" x-model="dim_h" class="w-full bg-transparent border border-white/30 rounded-md px-2 py-3 text-center text-sm text-white focus:outline-none focus:border-white transition">
                                    <span class="text-white/70 text-xs">CM</span>
                                </div>
                            </div>
                        </div>

                        <!-- Row 3 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-medium text-white mb-2">Payment Mode*</label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer text-white text-sm">
                                        <input type="radio" x-model="payment_mode" value="prepaid" class="w-4 h-4 text-[#d80032] bg-transparent border-white/30 focus:ring-[#d80032]">
                                        <span>Prepaid</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer text-white text-sm">
                                        <input type="radio" x-model="payment_mode" value="cod" class="w-4 h-4 text-[#d80032] bg-white border-white focus:ring-[#d80032]">
                                        <span>Cash on Delivery</span>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-white mb-1">Shipment Value*</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-white/70 text-sm">&#8377;</span>
                                    <input type="number" x-model="shipment_value" required class="w-full bg-transparent border border-white/30 rounded-md pl-8 pr-4 py-3 text-sm text-white focus:outline-none focus:border-white transition" placeholder="1000">
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-4 pt-4 border-t border-white/10 mt-6">
                            <button type="submit" class="px-8 py-3 rounded-full bg-[#d80032] text-white font-bold text-sm hover:bg-[#b00028] transition min-w-[160px]">
                                <span x-show="!loading">Calculate Now</span>
                                <span x-show="loading"><i class="fa-solid fa-spinner fa-spin"></i></span>
                            </button>
                            <button type="button" @click="resetForm()" class="px-8 py-3 rounded-full border border-white/50 text-white font-bold text-sm hover:bg-white/10 transition">
                                Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- RESULTS SECTION -->
            <div x-show="showResults" style="display: none;" class="mt-12 bg-[#3b172a] rounded-2xl p-6 shadow-inner">
                
                <!-- Filters -->
                <div class="flex gap-3 mb-6">
                    <button class="px-6 py-1.5 rounded-full bg-white text-black text-xs font-bold">All</button>
                    <button class="px-6 py-1.5 rounded-full border border-white/30 text-white text-xs font-medium hover:border-white transition">Air</button>
                    <button class="px-6 py-1.5 rounded-full border border-white/30 text-white text-xs font-medium hover:border-white transition">Surface</button>
                </div>

                <!-- Error Message -->
                <div x-show="error" class="text-red-300 text-sm font-medium py-4 text-center">
                    <span x-text="error"></span>
                </div>

                <!-- Table -->
                <div x-show="!error && rates.length > 0" class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-white border-collapse">
                        <thead class="border-b border-white/20 text-xs text-white font-medium">
                            <tr>
                                <th class="pb-3 px-2 font-medium">Courier Name</th>
                                <th class="pb-3 px-2 font-medium">Type</th>
                                <th class="pb-3 px-2 font-medium">Courier Charges</th>
                                <th class="pb-3 px-2 font-medium">AWB / Delivery</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10 text-sm">
                            <template x-for="rate in rates" :key="rate.courier_name">
                                <tr class="hover:bg-white/5 transition">
                                    <td class="py-4 px-2 font-medium" x-text="rate.courier_name + ' Surface'"></td>
                                    <td class="py-4 px-2 text-white/80">Surface</td>
                                    <td class="py-4 px-2 font-medium">&#8377; <span x-text="rate.rate.toFixed(2)"></span></td>
                                    <td class="py-4 px-2 text-white/80"><span x-text="rate.estimated_delivery_days"></span> Days</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
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
        pickup_pincode: '',
        delivery_pincode: '',
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
            this.weightKg = '';
            this.dim_l = '';
            this.dim_w = '';
            this.dim_h = '';
            this.payment_mode = 'prepaid';
            this.shipment_value = '';
            this.showResults = false;
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