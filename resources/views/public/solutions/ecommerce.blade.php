@extends('layouts.public')
@section('title', "E-Commerce Integration Solutions | OneStall Cargo")

@section('content')
<!-- 1. Hero Section -->
<section class="bg-gradient-to-r from-blue-50/40 via-white to-blue-50/40 py-12 md:py-16 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="w-full lg:w-1/2 text-center lg:text-left">
                <div class="inline-block px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-brand-navy text-xs font-bold mb-4 uppercase tracking-wide">
                    Seamless Store Integrations
                </div>
                <h1 class="text-3xl md:text-5xl font-extrabold text-brand-navy leading-tight mb-4">
                    Integrate Your E-Commerce Store in <span class="text-brand-red">Under 5 Minutes</span>
                </h1>
                <p class="text-base md:text-lg text-gray-700 mb-8 font-medium leading-relaxed">
                    Pre-built 1-click plugins for Shopify, WooCommerce, Magento, and custom REST APIs to automate order import, tracking sync, and inventory updates.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md shadow-red-500/20">
                        Connect Your Store Free
                    </a>
                    <a href="{{ route('docs') }}" class="px-8 py-3.5 rounded-full border-2 border-brand-navy text-brand-navy font-bold text-base hover:bg-brand-navy hover:text-white transition">
                        View API Docs
                    </a>
                </div>
            </div>

            <div class="w-full lg:w-1/2 relative">
                <div class="relative rounded-2xl overflow-hidden shadow-xl border-4 border-white bg-white">
                    <img src="{{ asset('images/dashboard.jpg') }}" alt="E-Commerce Integration Dashboard" class="w-full h-80 md:h-96 object-cover">
                </div>
                <div class="absolute -bottom-4 -left-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-bold">
                        <i class="fa-solid fa-[#E7004C] fa-plug"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">1-Click Plugins</div>
                        <div class="text-sm font-bold text-brand-navy">Shopify, Woo, Magento</div>
                    </div>
                </div>
                <div class="absolute -top-4 -right-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-brand-navy font-bold">
                        <i class="fa-solid fa-arrows-rotate"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Real-Time Sync</div>
                        <div class="text-sm font-bold text-brand-red">Auto Order & AWB Import</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Metrics Bar -->
<section class="bg-brand-navy py-6 text-white border-y border-brand-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">&lt; 5 Mins</div>
                <div class="text-xs md:text-sm text-gray-300">Setup Time</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">20+</div>
                <div class="text-xs md:text-sm text-gray-300">Platform Integrations</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-brand-red">Real-Time</div>
                <div class="text-xs md:text-sm text-gray-300">Tracking Number Sync</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">99.9%</div>
                <div class="text-xs md:text-sm text-gray-300">API Uptime SLA</div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Key Capabilities Grid -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-4xl font-extrabold text-brand-navy mb-3">
                Powerful E-Commerce Integration Features
            </h2>
            <p class="text-base text-gray-600">
                Automate your entire fulfillment pipeline from order placement to shipping label generation.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-brand-navy flex items-center justify-center text-2xl mb-4">
                    <i class="fa-brands fa-shopify"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Shopify & WooCommerce Plugins</h3>
                <p class="text-sm text-gray-600">Install pre-verified official app plugins that fetch orders instantly into your OneStall Cargo dashboard.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-red-100 text-brand-red flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-arrows-spin"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Auto AWB & Tracking Sync</h3>
                <p class="text-sm text-gray-600">Once an order is assigned a courier AWB number, it is automatically written back to your store admin panel.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-code"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">RESTful Developer APIs</h3>
                <p class="text-sm text-gray-600">Robust REST APIs with JSON payloads, comprehensive postman collections, and detailed documentation for custom tech stacks.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-bell"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Webhook Event Triggers</h3>
                <p class="text-sm text-gray-600">Receive instant HTTP webhooks for shipment status changes (In Transit, Out for Delivery, Delivered, NDR, RTO).</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-store"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Multi-Store Management</h3>
                <p class="text-sm text-gray-600">Connect multiple Shopify stores, Amazon channels, or custom websites under a single master logistics account.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">High Availability & Security</h3>
                <p class="text-sm text-gray-600">Bank-grade SSL encryption, rate-limiting protection, and 99.9% API uptime to handle high peak sale events.</p>
            </div>
        </div>
    </div>
</section>

<!-- 4. Workflow Section -->
<section class="py-12 md:py-16 bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-3xl font-extrabold text-brand-navy mb-2">
                4 Steps to Integrate Your Store
            </h2>
            <p class="text-sm md:text-base text-gray-600 font-medium">Zero coding required for major platforms</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">1</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Install App</h4>
                <p class="text-xs text-gray-600">Install the OneStall Cargo plugin from your app store.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">2</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Authorize API</h4>
                <p class="text-xs text-gray-600">Enter API key to securely pair your store with OneStall.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">3</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Auto-Sync Orders</h4>
                <p class="text-xs text-gray-600">Unfulfilled orders flow automatically into your dashboard.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">4</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Write-Back AWB</h4>
                <p class="text-xs text-gray-600">Tracking URLs automatically update on your store admin.</p>
            </div>
        </div>
    </div>
</section>

<!-- 5. CTA Card -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-brand-navy via-brand-blue to-brand-navy rounded-3xl p-8 md:p-12 text-white text-center shadow-xl">
            <h2 class="text-2xl md:text-4xl font-extrabold mb-4">
                Automate Your E-Commerce Store Fulfillment Now
            </h2>
            <p class="text-sm md:text-base text-gray-200 max-w-2xl mx-auto mb-8 font-medium">
                Connect Shopify, WooCommerce, Magento, or custom store APIs in 5 minutes with zero setup charges.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md">
                    Connect Store Free
                </a>
                <a href="{{ route('docs') }}" class="px-8 py-3.5 rounded-full border border-white/40 text-white font-bold text-base hover:bg-white/10 transition">
                    View API Documentation
                </a>
            </div>
        </div>
    </div>
</section>
@endsection