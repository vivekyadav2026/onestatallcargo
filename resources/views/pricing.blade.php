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

<!-- 2. Interactive Rate Estimator (Alpine.js) -->
<section class="py-12 bg-white border-y border-gray-100" x-data="{
    serviceType: 'b2c',
    weightKg: 0.5,
    zone: 'metro',
    calculateRate() {
        let base = 42;
        if (this.serviceType === 'b2c') base = 42;
        if (this.serviceType === 'b2b') base = 140; // 10kg base
        if (this.serviceType === 'intl') base = 450;
        if (this.serviceType === 'quick') base = 55;

        let multiplier = 1.0;
        if (this.zone === 'intracity') multiplier = 0.9;
        if (this.zone === 'metro') multiplier = 1.0;
        if (this.zone === 'regional') multiplier = 1.25;
        if (this.zone === 'roi') multiplier = 1.4;

        let total = (base * (this.serviceType === 'b2b' ? (this.weightKg / 10) : (this.weightKg / 0.5)) * multiplier).toFixed(2);
        return total < base ? base.toFixed(2) : total;
    }
}">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-50/80 rounded-3xl p-6 md:p-10 border border-gray-200 shadow-xl">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-brand-navy">Instant Rate Estimator</h2>
                <p class="text-xs text-gray-500 font-medium">Select your shipping options below for an instant estimated rate quote</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Service Type -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2">Service Type</label>
                    <select x-model="serviceType" class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-800 focus:outline-none focus:border-brand-navy">
                        <option value="b2c">B2C Express Courier</option>
                        <option value="b2b">B2B Heavy Cargo (LTL)</option>
                        <option value="intl">International Air</option>
                        <option value="quick">Hyperlocal Quick Delivery</option>
                    </select>
                </div>

                <!-- Weight -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2">Package Weight</label>
                    <select x-model.number="weightKg" class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-800 focus:outline-none focus:border-brand-navy">
                        <option value="0.5">0.5 kg (500 grams)</option>
                        <option value="1.0">1.0 kg</option>
                        <option value="2.0">2.0 kg</option>
                        <option value="5.0">5.0 kg</option>
                        <option value="10.0">10.0 kg</option>
                        <option value="20.0">20.0 kg</option>
                    </select>
                </div>

                <!-- Destination Zone -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2">Destination Zone</label>
                    <select x-model="zone" class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-800 focus:outline-none focus:border-brand-navy">
                        <option value="intracity">Intra-City (Same City)</option>
                        <option value="metro">Metro to Metro</option>
                        <option value="regional">Regional / State</option>
                        <option value="roi">Rest of India (NE / J&K)</option>
                    </select>
                </div>
            </div>

            <!-- Rate Display Box -->
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-md flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <div class="text-xs text-gray-500 font-medium">Estimated Shipping Starting From</div>
                    <div class="text-3xl font-black text-brand-navy">
                        &#8377; <span x-text="calculateRate()"></span>
                        <span class="text-xs text-gray-500 font-normal">*T&C Apply</span>
                    </div>
                </div>
                <div>
                    <a href="{{ route('register') }}" class="px-6 py-3 rounded-full bg-brand-red text-white font-bold text-sm hover:bg-brand-redHover transition shadow-md">
                        Book Shipment Now &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

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