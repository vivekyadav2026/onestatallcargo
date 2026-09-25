@extends('layouts.public')
@section('title', "Quick Delivery & Hyperlocal Solutions | OneStall Cargo")

@section('content')
<!-- 1. Hero Section -->
<section class="bg-gradient-to-r from-blue-50/40 via-white to-blue-50/40 py-12 md:py-16 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="w-full lg:w-1/2 text-center lg:text-left">
                <div class="inline-block px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-brand-navy text-xs font-bold mb-4 uppercase tracking-wide">
                    Express Hyperlocal Delivery
                </div>
                <h1 class="text-3xl md:text-5xl font-extrabold text-brand-navy leading-tight mb-4">
                    Deliver Orders in <span class="text-brand-red">Hours, Not Days</span>
                </h1>
                <p class="text-base md:text-lg text-gray-700 mb-8 font-medium leading-relaxed">
                    Delight local customers with 2-hour hyperlocal bike deliveries and same-day intra-city express shipping powered by real-time GPS tracking.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md shadow-red-500/20">
                        Start Quick Delivery
                    </a>
                    <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-full border-2 border-brand-navy text-brand-navy font-bold text-base hover:bg-brand-navy hover:text-white transition">
                        Talk to Sales
                    </a>
                </div>
            </div>

            <div class="w-full lg:w-1/2 relative">
                <div class="relative rounded-2xl overflow-hidden shadow-xl border-4 border-white bg-white">
                    <img src="{{ asset('images/mobile_app.jpg') }}" alt="Quick Delivery App" class="w-full h-80 md:h-96 object-cover">
                </div>
                <div class="absolute -bottom-4 -left-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-bold">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Delivery Speed</div>
                        <div class="text-sm font-bold text-brand-navy">Same-Day & 2-Hour</div>
                    </div>
                </div>
                <div class="absolute -top-4 -right-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-brand-navy font-bold">
                        <i class="fa-solid fa-location-crosshairs"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Rider Tracking</div>
                        <div class="text-sm font-bold text-brand-red">Live GPS Map</div>
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
                <div class="text-2xl md:text-3xl font-black text-white">2 Hours</div>
                <div class="text-xs md:text-sm text-gray-300">Avg Hyperlocal Time</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">50+ Cities</div>
                <div class="text-xs md:text-sm text-gray-300">Intra-City Coverage</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-brand-red">Instant</div>
                <div class="text-xs md:text-sm text-gray-300">Driver Dispatch</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">99.8%</div>
                <div class="text-xs md:text-sm text-gray-300">On-Time Arrival</div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Key Capabilities Grid -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-4xl font-extrabold text-brand-navy mb-3">
                Built for On-Demand & Intra-City Commerce
            </h2>
            <p class="text-base text-gray-600">
                Fulfill local orders rapidly to gain a competitive edge over traditional 2-3 day shipping options.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-brand-navy flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-motorcycle"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">On-Demand Bike Riders</h3>
                <p class="text-sm text-gray-600">Instant courier rider dispatch for parcels up to 10kg with doorstep pickup within 15 minutes of order placement.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-red-100 text-brand-red flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-truck-pickup"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Intra-City Van Express</h3>
                <p class="text-sm text-gray-600">Dedicated cargo vans and 3-wheelers for larger intra-city bulk stock transfers between stores and hubs.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Live Rider GPS Link</h3>
                <p class="text-sm text-gray-600">Send customers a live GPS tracking URL via SMS so they can watch the delivery rider approach in real-time.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-key"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">OTP Secure Delivery</h3>
                <p class="text-sm text-gray-600">Mandatory buyer OTP verification on delivery ensuring 100% successful handovers and zero missing package disputes.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-shop"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Dark Store Fulfillment</h3>
                <p class="text-sm text-gray-600">Store fast-moving SKUs in micro-fulfillment centers across metro cities for ultra-fast same-day dispatch.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-route"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Smart Route Optimization</h3>
                <p class="text-sm text-gray-600">Intelligent batching and route mapping to ensure riders deliver multiple nearby orders efficiently.</p>
            </div>
        </div>
    </div>
</section>

<!-- 4. Workflow Section -->
<section class="py-12 md:py-16 bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-3xl font-extrabold text-brand-navy mb-2">
                How Quick Delivery Works
            </h2>
            <p class="text-sm md:text-base text-gray-600 font-medium">From checkout to doorstep in under 2 hours</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">1</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Order Placed</h4>
                <p class="text-xs text-gray-600">Customer selects Express Same-Day at checkout.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">2</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Rider Assigned</h4>
                <p class="text-xs text-gray-600">Nearest rider accepted & arrives at store in 15 mins.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">3</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Live Map Tracking</h4>
                <p class="text-xs text-gray-600">Buyer receives live SMS tracking link.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">4</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">OTP Verified Delivery</h4>
                <p class="text-xs text-gray-600">Rider verifies OTP and completes delivery.</p>
            </div>
        </div>
    </div>
</section>

<!-- 5. CTA Card -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-brand-navy via-brand-blue to-brand-navy rounded-3xl p-8 md:p-12 text-white text-center shadow-xl">
            <h2 class="text-2xl md:text-4xl font-extrabold mb-4">
                Offer Same-Day Delivery to Your Local Buyers Today
            </h2>
            <p class="text-sm md:text-base text-gray-200 max-w-2xl mx-auto mb-8 font-medium">
                Increase cart conversion rates by offering lightning-fast hyperlocal delivery in your city.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md">
                    Start Quick Delivery
                </a>
                <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-full border border-white/40 text-white font-bold text-base hover:bg-white/10 transition">
                    Contact Hyperlocal Team
                </a>
            </div>
        </div>
    </div>
</section>
@endsection