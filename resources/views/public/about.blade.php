@extends('layouts.public')
@section('title', "About Us | OneStall Cargo")

@section('content')
<!-- 1. Hero Section -->
<section class="bg-gradient-to-r from-blue-50/40 via-white to-blue-50/40 py-12 md:py-16 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center max-w-3xl mx-auto">
        <div class="inline-block px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-brand-navy text-xs font-bold mb-4 uppercase tracking-wide">
            Our Story & Mission
        </div>
        <h1 class="text-3xl md:text-5xl font-extrabold text-brand-navy leading-tight mb-4">
            Pioneering Next-Gen <span class="text-brand-red">Logistics Infrastructure</span> in India
        </h1>
        <p class="text-base md:text-lg text-gray-700 mb-8 font-medium leading-relaxed">
            OneStall Cargo empowers 10,000+ e-commerce brands, sellers, and corporate enterprises with automated courier allocation, transparent pricing, 1-2 day COD remittance, and seamless nationwide logistics.
        </p>
        <div class="flex justify-center gap-4">
            <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md shadow-red-500/20">
                Join OneStall Platform
            </a>
            <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-full border-2 border-brand-navy text-brand-navy font-bold text-base hover:bg-brand-navy hover:text-white transition">
                Contact Our Team
            </a>
        </div>
    </div>
</section>

<!-- 2. Metrics Bar -->
<section class="bg-brand-navy py-6 text-white border-y border-brand-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">10,000+</div>
                <div class="text-xs md:text-sm text-gray-300">Active Sellers & Shippers</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">29,000+</div>
                <div class="text-xs md:text-sm text-gray-300">Pin Codes Covered</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-brand-red">15+</div>
                <div class="text-xs md:text-sm text-gray-300">Integrated Courier Partners</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">50M+</div>
                <div class="text-xs md:text-sm text-gray-300">Parcels Delivered</div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Mission & Vision Cards -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Mission Card -->
            <div class="bg-gray-50/80 p-8 rounded-3xl border border-gray-200 shadow-sm relative">
                <div class="w-12 h-12 rounded-2xl bg-blue-100 text-brand-navy flex items-center justify-center text-2xl mb-6">
                    <i class="fa-solid fa-bullseye"></i>
                </div>
                <h2 class="text-2xl font-bold text-brand-navy mb-4">Our Mission</h2>
                <p class="text-gray-600 leading-relaxed text-sm md:text-base">
                    To democratize enterprise-grade supply chain infrastructure for every seller in India. We aim to remove logistics complexities by providing intelligent courier routing, automated NDR management, 1-2 day COD payouts, and transparent rates without any hidden subscriptions.
                </p>
            </div>

            <!-- Vision Card -->
            <div class="bg-gray-50/80 p-8 rounded-3xl border border-gray-200 shadow-sm relative">
                <div class="w-12 h-12 rounded-2xl bg-red-100 text-brand-red flex items-center justify-center text-2xl mb-6">
                    <i class="fa-solid fa-eye"></i>
                </div>
                <h2 class="text-2xl font-bold text-brand-navy mb-4">Our Vision</h2>
                <p class="text-gray-600 leading-relaxed text-sm md:text-base">
                    To build India's most trusted, technology-first shipping ecosystem connecting metro hubs, tier-2/3 cities, and international markets. We envision a future where shipping an order is as instant and reliable as sending a digital message.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- 4. Core Values Grid -->
<section class="py-12 md:py-16 bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-4xl font-extrabold text-brand-navy mb-3">
                Driven by Core Values
            </h2>
            <p class="text-base text-gray-600">
                The principles that guide our product engineering, carrier partnerships, and customer support.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm text-center">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-brand-navy flex items-center justify-center text-xl mx-auto mb-4">
                    <i class="fa-solid fa-code"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Tech First</h3>
                <p class="text-xs text-gray-600 leading-relaxed">Automated workflows, RESTful APIs, and real-time data transparency at every level.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm text-center">
                <div class="w-10 h-10 rounded-xl bg-red-100 text-brand-red flex items-center justify-center text-xl mx-auto mb-4">
                    <i class="fa-solid fa-[#E7004C] fa-heart"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Seller Centric</h3>
                <p class="text-xs text-gray-600 leading-relaxed">Fast COD remittance, zero subscription fees, and proactive operational assistance.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm text-center">
                <div class="w-10 h-10 rounded-xl bg-green-100 text-green-600 flex items-center justify-center text-xl mx-auto mb-4">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Trust & Transparency</h3>
                <p class="text-xs text-gray-600 leading-relaxed">Video evidence packing logs, transparent billing, and 1-click dispute resolution.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm text-center">
                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl mx-auto mb-4">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Nationwide Scale</h3>
                <p class="text-xs text-gray-600 leading-relaxed">Connecting tier-1 metros to remote regional pin codes with 15+ carrier networks.</p>
            </div>
        </div>
    </div>
</section>

<!-- 5. CTA Section -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-brand-navy via-brand-blue to-brand-navy rounded-3xl p-8 md:p-12 text-white text-center shadow-xl">
            <h2 class="text-2xl md:text-4xl font-extrabold mb-4">
                Ready to Experience Next-Gen Logistics?
            </h2>
            <p class="text-sm md:text-base text-gray-200 max-w-2xl mx-auto mb-8 font-medium">
                Join thousands of businesses who trust OneStall Cargo for faster deliveries, lower freight costs, and daily COD remittance.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md">
                    Start Shipping Free
                </a>
                <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-full border border-white/40 text-white font-bold text-base hover:bg-white/10 transition">
                    Contact Sales
                </a>
            </div>
        </div>
    </div>
</section>
@endsection