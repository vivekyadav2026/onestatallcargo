@extends('layouts.public')
@section('title', "Transparent Shipping Rates & Pricing | OneStall Cargo")

@section('content')
<!-- 1. Hero Section -->
<section class="bg-gradient-to-r from-blue-50/40 via-white to-blue-50/40 py-12 md:py-16 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center max-w-3xl mx-auto">
        <div class="inline-block px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-brand-navy text-xs font-bold mb-4 uppercase tracking-wide">
            Transparent Pricing Structure
        </div>
        <h1 class="text-3xl md:text-5xl font-extrabold text-brand-navy leading-tight mb-4">
            Shipping Rates Built for <span class="text-brand-red">Scaling Businesses</span>
        </h1>
        <p class="text-base md:text-lg text-gray-700 mb-8 font-medium leading-relaxed">
            No monthly subscription fees, no hidden fuel surcharges. Pay only for what you ship with discounted rates across 15+ top courier partners.
        </p>
        <div class="flex justify-center gap-4">
            <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md shadow-red-500/20">
                Start Shipping at ₹42/500g
            </a>
        </div>
    </div>
</section>

<!-- 2. Live Dynamic Rate Estimator (API Integration) -->
<section class="py-12 bg-white border-y border-gray-100" x-data="liveRateCalculator()">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-50/80 rounded-3xl p-6 md:p-10 border border-gray-200 shadow-xl">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-brand-navy">Live API Rate Estimator</h2>
                <p class="text-xs text-gray-500 font-medium">Enter real pincodes to fetch live rates from our backend aggregator engine.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Pickup Pincode -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2">Pickup Pincode</label>
                    <input type="text" x-model="pickup_pincode" maxlength="6" placeholder="e.g. 110001" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-800 focus:outline-none focus:border-brand-navy" @input="fetchRate">
                </div>

                <!-- Delivery Pincode -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2">Delivery Pincode</label>
                    <input type="text" x-model="delivery_pincode" maxlength="6" placeholder="e.g. 400001" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-800 focus:outline-none focus:border-brand-navy" @input="fetchRate">
                </div>

                <!-- Weight -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2">Package Weight</label>
                    <select x-model.number="weightKg" @change="fetchRate" class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-800 focus:outline-none focus:border-brand-navy">
                        <option value="0.5">0.5 kg (500 grams)</option>
                        <option value="1.0">1.0 kg</option>
                        <option value="2.0">2.0 kg</option>
                        <option value="5.0">5.0 kg</option>
                        <option value="10.0">10.0 kg</option>
                    </select>
                </div>
            </div>

            <!-- Rate Display Box -->
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-md flex flex-col md:flex-row justify-between items-center gap-4 min-h-[100px]">
                
                <div x-show="loading" class="text-brand-navy font-bold flex items-center justify-center w-full">
                    <i class="fa-solid fa-spinner fa-spin mr-2"></i> Calculating real-time rates...
                </div>

                <div x-show="!loading && error" class="text-red-500 font-bold text-sm text-center w-full" x-text="error"></div>

                <div x-show="!loading && !error && bestRate !== null" class="flex flex-col md:flex-row justify-between w-full items-center">
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Cheapest Courier Available: <span x-text="courierName" class="font-bold text-brand-navy"></span></div>
                        <div class="text-3xl font-black text-brand-navy mt-1">
                            &#8377; <span x-text="bestRate"></span>
                            <span class="text-xs text-gray-500 font-normal">*Inc. Platform Margin</span>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('register') }}" class="px-6 py-2.5 bg-brand-navy text-white text-xs font-bold rounded-full shadow hover:bg-black transition">
                            Create Account to Book
                        </a>
                    </div>
                </div>
                
                <div x-show="!loading && !error && bestRate === null" class="text-gray-400 font-bold text-sm text-center w-full">
                    Enter valid 6-digit pincodes to see rates.
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function liveRateCalculator() {
    return {
        pickup_pincode: '',
        delivery_pincode: '',
        weightKg: 0.5,
        loading: false,
        error: null,
        bestRate: null,
        courierName: null,

        async fetchRate() {
            if (this.pickup_pincode.length === 6 && this.delivery_pincode.length === 6) {
                this.loading = true;
                this.error = null;
                this.bestRate = null;
                
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
                            weight: this.weightKg
                        })
                    });
                    
                    let result = await response.json();
                    
                    if (response.ok && result.success && result.data.length > 0) {
                        this.bestRate = result.data[0].rate;
                        this.courierName = result.data[0].courier_name;
                    } else {
                        this.error = result.message || 'No service available for this route.';
                    }
                } catch (err) {
                    this.error = 'Failed to fetch rates from server.';
                } finally {
                    this.loading = false;
                }
            }
        }
    }
}
</script>


<!-- 3. Tiered Plans Grid -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-4xl font-extrabold text-brand-navy mb-3">
                Simple Plans for Businesses of All Sizes
            </h2>
            <p class="text-base text-gray-600">
                Zero fixed monthly costs. Recharge your wallet and ship at your own pace.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Starter Plan -->
            <div class="bg-white rounded-3xl p-8 border border-gray-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="text-xs font-bold text-brand-navy uppercase tracking-wider mb-2">Starter Plan</div>
                    <div class="text-4xl font-black text-gray-900 mb-2">&#8377; 0 <span class="text-xs text-gray-500 font-normal">/ month</span></div>
                    <p class="text-xs text-gray-600 mb-6">Ideal for social sellers, boutique stores, and new startups.</p>

                    <ul class="space-y-3 text-xs text-gray-700 mb-8">
                        <li class="flex items-center"><i class="fa-solid fa-check text-green-500 mr-2"></i> B2C Express at &#8377;42/500g</li>
                        <li class="flex items-center"><i class="fa-solid fa-check text-green-500 mr-2"></i> Access to 15+ Courier Partners</li>
                        <li class="flex items-center"><i class="fa-solid fa-check text-green-500 mr-2"></i> Standard Tracking URL</li>
                        <li class="flex items-center"><i class="fa-solid fa-check text-green-500 mr-2"></i> 1-2 Days COD Remittance</li>
                        <li class="flex items-center"><i class="fa-solid fa-check text-green-500 mr-2"></i> Email & Chat Support</li>
                    </ul>
                </div>
                <a href="{{ route('register') }}" class="w-full py-3 rounded-full border-2 border-brand-navy text-brand-navy text-center font-bold text-sm hover:bg-brand-navy hover:text-white transition">
                    Get Started Free
                </a>
            </div>

            <!-- Growth Plan (Most Popular) -->
            <div class="bg-white rounded-3xl p-8 border-2 border-brand-red shadow-xl relative flex flex-col justify-between">
                <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-brand-red text-white text-[10px] font-extrabold uppercase px-4 py-1 rounded-full shadow">
                    Most Popular
                </div>
                <div>
                    <div class="text-xs font-bold text-brand-red uppercase tracking-wider mb-2">Growth Plan</div>
                    <div class="text-4xl font-black text-gray-900 mb-2">&#8377; 0 <span class="text-xs text-gray-500 font-normal">/ month</span></div>
                    <p class="text-xs text-gray-600 mb-6">Designed for growing D2C brands shipping 500+ orders monthly.</p>

                    <ul class="space-y-3 text-xs text-gray-700 mb-8">
                        <li class="flex items-center"><i class="fa-solid fa-check text-brand-red mr-2 font-bold"></i> Discounted B2C at &#8377;36/500g</li>
                        <li class="flex items-center"><i class="fa-solid fa-check text-brand-red mr-2 font-bold"></i> Branded Tracking Page Domain</li>
                        <li class="flex items-center"><i class="fa-solid fa-check text-brand-red mr-2 font-bold"></i> Automated WhatsApp NDR Bot</li>
                        <li class="flex items-center"><i class="fa-solid fa-check text-brand-red mr-2 font-bold"></i> Early Next-Day COD Payout</li>
                        <li class="flex items-center"><i class="fa-solid fa-check text-brand-red mr-2 font-bold"></i> Video Evidence Dispute Tools</li>
                    </ul>
                </div>
                <a href="{{ route('register') }}" class="w-full py-3 rounded-full bg-brand-red text-white text-center font-bold text-sm hover:bg-brand-redHover transition shadow-md">
                    Start Growth Plan
                </a>
            </div>

            <!-- Enterprise Plan -->
            <div class="bg-white rounded-3xl p-8 border border-gray-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="text-xs font-bold text-brand-navy uppercase tracking-wider mb-2">Enterprise Cargo</div>
                    <div class="text-3xl font-black text-gray-900 mb-2">Custom <span class="text-xs text-gray-500 font-normal">Slab Rates</span></div>
                    <p class="text-xs text-gray-600 mb-6">For high-volume shippers, manufacturers, and B2B wholesalers.</p>

                    <ul class="space-y-3 text-xs text-gray-700 mb-8">
                        <li class="flex items-center"><i class="fa-solid fa-check text-green-500 mr-2"></i> Custom Negotiated Slab Rates</li>
                        <li class="flex items-center"><i class="fa-solid fa-check text-green-500 mr-2"></i> Dedicated LTL & FTL Trucks</li>
                        <li class="flex items-center"><i class="fa-solid fa-check text-green-500 mr-2"></i> Dedicated Key Account Manager</li>
                        <li class="flex items-center"><i class="fa-solid fa-check text-green-500 mr-2"></i> Custom ERP & API Pipeline</li>
                        <li class="flex items-center"><i class="fa-solid fa-check text-green-500 mr-2"></i> 15-30 Days Credit Billing</li>
                    </ul>
                </div>
                <a href="{{ route('contact') }}" class="w-full py-3 rounded-full border-2 border-brand-navy text-brand-navy text-center font-bold text-sm hover:bg-brand-navy hover:text-white transition">
                    Contact Sales
                </a>
            </div>
        </div>
    </div>
</section>

<!-- 4. Fee Transparency Table -->
<section class="py-12 bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h3 class="text-2xl font-bold text-brand-navy">100% Fee Transparency</h3>
            <p class="text-xs text-gray-500">Zero hidden surcharges. All applicable fees outlined clearly.</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
            <table class="w-full text-left text-xs">
                <thead class="bg-brand-navy text-white uppercase text-[10px] font-bold">
                    <tr>
                        <th class="py-3 px-4">Component</th>
                        <th class="py-3 px-4">Charge / Rate</th>
                        <th class="py-3 px-4">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700 font-medium">
                    <tr>
                        <td class="py-3 px-4 font-bold text-gray-900">Platform Subscription</td>
                        <td class="py-3 px-4 text-green-600 font-bold">FREE (&#8377;0)</td>
                        <td class="py-3 px-4">No monthly or annual maintenance fees.</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 font-bold text-gray-900">COD Collection Fee</td>
                        <td class="py-3 px-4 font-bold text-brand-navy">1.5% or &#8377;30</td>
                        <td class="py-3 px-4">Whichever is higher per delivered COD parcel.</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 font-bold text-gray-900">Fuel Surcharge</td>
                        <td class="py-3 px-4 text-green-600 font-bold">Included</td>
                        <td class="py-3 px-4">Already factored into rate quote calculations.</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 font-bold text-gray-900">Store Integration & APIs</td>
                        <td class="py-3 px-4 text-green-600 font-bold">FREE (&#8377;0)</td>
                        <td class="py-3 px-4">Shopify, WooCommerce plugins and REST APIs included.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection