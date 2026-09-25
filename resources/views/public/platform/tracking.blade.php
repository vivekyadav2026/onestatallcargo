@extends('layouts.public')
@section('title', "Real-Time Live Tracking | OneStall Cargo Platform")

@section('content')
<!-- 1. Hero Section -->
<section class="bg-gradient-to-r from-blue-50/40 via-white to-blue-50/40 py-12 md:py-16 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="w-full lg:w-1/2 text-center lg:text-left">
                <div class="inline-block px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-brand-navy text-xs font-bold mb-4 uppercase tracking-wide">
                    Post-Purchase Experience
                </div>
                <h1 class="text-3xl md:text-5xl font-extrabold text-brand-navy leading-tight mb-4">
                    Real-Time <span class="text-brand-red">Live Tracking</span> & Buyer Engagement
                </h1>
                <p class="text-base md:text-lg text-gray-700 mb-8 font-medium leading-relaxed">
                    Transform shipment tracking into a brand-building channel. Provide live milestone updates, branded tracking pages, and multi-channel buyer alerts via SMS and WhatsApp.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md shadow-red-500/20">
                        Start Live Tracking
                    </a>
                    <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-full border-2 border-brand-navy text-brand-navy font-bold text-base hover:bg-brand-navy hover:text-white transition">
                        Request Tracking Demo
                    </a>
                </div>
            </div>

            <div class="w-full lg:w-1/2 relative">
                <div class="relative rounded-2xl overflow-hidden shadow-xl border-4 border-white bg-white">
                    <img src="{{ asset('images/mobile_app.jpg') }}" alt="Live Shipment Tracking" class="w-full h-80 md:h-96 object-cover">
                </div>
                <div class="absolute -bottom-4 -left-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-bold">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Tracking Status</div>
                        <div class="text-sm font-bold text-brand-navy">100% Real-Time GPS</div>
                    </div>
                </div>
                <div class="absolute -top-4 -right-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-brand-navy font-bold">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Buyer Updates</div>
                        <div class="text-sm font-bold text-brand-red">WhatsApp & SMS Alerts</div>
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
                <div class="text-2xl md:text-3xl font-black text-white">100%</div>
                <div class="text-xs md:text-sm text-gray-300">Milestone Accuracy</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">Custom</div>
                <div class="text-xs md:text-sm text-gray-300">Domain URL Support</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-brand-red">35%</div>
                <div class="text-xs md:text-sm text-gray-300">Higher Repeat Orders</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">Single</div>
                <div class="text-xs md:text-sm text-gray-300">Tracking API for All Carriers</div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Key Capabilities Grid -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-4xl font-extrabold text-brand-navy mb-3">
                Live Tracking & Post-Purchase Engine
            </h2>
            <p class="text-base text-gray-600">
                Keep customers informed at every step while driving additional sales on your custom tracking page.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-brand-navy flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-laptop text-brand-navy"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Branded Tracking Widget</h3>
                <p class="text-sm text-gray-600">Embed tracking search directly on your website domain with custom logo, brand colors, and product promotion banners.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-red-100 text-brand-red flex items-center justify-center text-2xl mb-4">
                    <i class="fa-brands fa-whatsapp text-brand-red"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Multi-Channel Buyer Alerts</h3>
                <p class="text-sm text-gray-600">Automated WhatsApp and SMS notifications sent on order dispatch, in-transit, out for delivery, and delivered stages.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">SLA Delay Monitoring</h3>
                <p class="text-sm text-gray-600">Automated detection of delayed shipments so your support team can proactively contact carriers before buyers complain.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Visual Map View</h3>
                <p class="text-sm text-gray-600">Display visual location maps showing transit hubs and live courier movement progress.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-code"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Unified Tracking API</h3>
                <p class="text-sm text-gray-600">Query tracking status across all 15+ carrier partners using a single standardized REST API payload.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-star"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Customer Feedback & NPS</h3>
                <p class="text-sm text-gray-600">Collect post-delivery rating scores and customer reviews directly on the tracking completion page.</p>
            </div>
        </div>
    </div>
</section>

<!-- 4. Workflow Section -->
<section class="py-12 md:py-16 bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-3xl font-extrabold text-brand-navy mb-2">
                How Live Tracking Works
            </h2>
            <p class="text-sm md:text-base text-gray-600 font-medium">Automated post-purchase customer journey</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">1</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Order Dispatched</h4>
                <p class="text-xs text-gray-600">Carrier scans package and generates AWB tracking number.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">2</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Instant Buyer SMS</h4>
                <p class="text-xs text-gray-600">Buyer receives branded tracking URL on WhatsApp & SMS.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">3</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Live Hub Updates</h4>
                <p class="text-xs text-gray-600">Milestone events update automatically in real-time.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">4</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Delivery Rating</h4>
                <p class="text-xs text-gray-600">Buyer confirms receipt and leaves delivery rating.</p>
            </div>
        </div>
    </div>
</section>

<!-- 5. CTA Card -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-brand-navy via-brand-blue to-brand-navy rounded-3xl p-8 md:p-12 text-white text-center shadow-xl">
            <h2 class="text-2xl md:text-4xl font-extrabold mb-4">
                Upgrade Your Tracking Page Today
            </h2>
            <p class="text-sm md:text-base text-gray-200 max-w-2xl mx-auto mb-8 font-medium">
                Keep customers informed, reduce "WHERE IS MY ORDER" support tickets by 60%, and boost repeat sales.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md">
                    Start Free Trial
                </a>
                <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-full border border-white/40 text-white font-bold text-base hover:bg-white/10 transition">
                    Contact Support Team
                </a>
            </div>
        </div>
    </div>
</section>
@endsection