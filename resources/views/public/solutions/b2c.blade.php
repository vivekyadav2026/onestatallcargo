@extends('layouts.public')
@section('title', "B2C Shipping Solutions | OneStall Cargo")

@section('content')
<!-- 1. Hero Section -->
<section class="bg-gradient-to-r from-blue-50/40 via-white to-blue-50/40 py-12 md:py-16 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <!-- Left Text Content -->
            <div class="w-full lg:w-1/2 text-center lg:text-left">
                <div class="inline-block px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-brand-navy text-xs font-bold mb-4 uppercase tracking-wide">
                    Direct-To-Consumer Logistics
                </div>
                <h1 class="text-3xl md:text-5xl font-extrabold text-brand-navy leading-tight mb-4">
                    Next-Gen <span class="text-brand-red">B2C Shipping</span> For E-Commerce Brands
                </h1>
                <p class="text-base md:text-lg text-gray-700 mb-8 font-medium leading-relaxed">
                    Ship across 29,000+ pin codes in India with 15+ top courier partners. Lower shipping costs, reduce RTO by up to 40%, and get 1-2 day COD remittance.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md shadow-red-500/20">
                        Start Shipping Free
                    </a>
                    <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-full border-2 border-brand-navy text-brand-navy font-bold text-base hover:bg-brand-navy hover:text-white transition">
                        Talk to an Expert
                    </a>
                </div>
                <div class="mt-6 flex items-center justify-center lg:justify-start gap-6 text-xs text-gray-500 font-semibold">
                    <span><i class="fa-solid fa-check text-green-500 mr-1"></i> No Monthly Subscription</span>
                    <span><i class="fa-solid fa-check text-green-500 mr-1"></i> Pay-As-You-Go</span>
                </div>
            </div>

            <!-- Right Image Mockup -->
            <div class="w-full lg:w-1/2 relative">
                <div class="relative rounded-2xl overflow-hidden shadow-xl border-4 border-white bg-white">
                    <img src="{{ asset('images/dashboard.jpg') }}" alt="B2C Shipping Dashboard" class="w-full h-80 md:h-96 object-cover">
                </div>
                <!-- Floating Badge 1 -->
                <div class="absolute -bottom-4 -left-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-bold">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">COD Remittance</div>
                        <div class="text-sm font-bold text-brand-navy">1-2 Days Cycle</div>
                    </div>
                </div>
                <!-- Floating Badge 2 -->
                <div class="absolute -top-4 -right-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-brand-navy font-bold">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">RTO Reduction</div>
                        <div class="text-sm font-bold text-brand-red">Up to 40% Lower</div>
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
                <div class="text-2xl md:text-3xl font-black text-white">29,000+</div>
                <div class="text-xs md:text-sm text-gray-300">Deliverable Pin Codes</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">15+</div>
                <div class="text-xs md:text-sm text-gray-300">Courier Partners</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-brand-red">1-2 Days</div>
                <div class="text-xs md:text-sm text-gray-300">Fastest COD Remittance</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">99.2%</div>
                <div class="text-xs md:text-sm text-gray-300">SLA Delivery Adherence</div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Key Capabilities Grid -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-4xl font-extrabold text-brand-navy mb-3">
                Everything You Need for B2C Shipping Success
            </h2>
            <p class="text-base text-gray-600">
                Powerful automated tools to streamline orders, cut shipping costs, and deliver superior customer experiences.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-brand-navy flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Multi-Courier Allocation</h3>
                <p class="text-sm text-gray-600">Automatically route each shipment to the best courier based on real-time SLA data, pin code serviceability, and cost efficiency.</p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-red-100 text-brand-red flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-indian-rupee-sign"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Fastest COD Remittance</h3>
                <p class="text-sm text-gray-600">Keep cash flow uninterrupted with 1-2 business day COD payouts. No long wait times or frozen working capital.</p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-rotate-left"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Automated NDR Management</h3>
                <p class="text-sm md:text-base text-gray-600">Re-engage buyers via automated IVR, SMS, and WhatsApp channels on failed delivery attempts to cut RTO losses by 40%.</p>
            </div>

            <!-- Feature 4 -->
            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-[#E7004C] fa-globe"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Branded Tracking Page</h3>
                <p class="text-sm text-gray-600">Replace generic courier links with your own customized tracking page complete with your logo, promotional banners, and order status.</p>
            </div>

            <!-- Feature 5 -->
            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-print"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Bulk Label & Manifest Printing</h3>
                <p class="text-sm text-gray-600">Generate shipping labels, packing slips, and pickup manifests in bulk with a single click to speed up fulfillment.</p>
            </div>

            <!-- Feature 6 -->
            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-scale-balanced"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Weight Discrepancy Manager</h3>
                <p class="text-sm text-gray-600">Eliminate overcharging with automated weight discrepancy detection, audit trail photo evidence, and 1-click dispute resolution.</p>
            </div>
        </div>
    </div>
</section>

<!-- 4. How It Works Workflow -->
<section class="py-12 md:py-16 bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-3xl font-extrabold text-brand-navy mb-2">
                4 Simple Steps to Automate B2C Deliveries
            </h2>
            <p class="text-sm md:text-base text-gray-600">Start shipping in less than 5 minutes</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-xl border border-gray-100 relative text-center">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">1</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Connect Store</h4>
                <p class="text-xs text-gray-600">Sync Shopify, WooCommerce, or Magento with 1-click plugins.</p>
            </div>

            <div class="bg-white p-6 rounded-xl border border-gray-100 relative text-center">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">2</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Auto-Allocate</h4>
                <p class="text-xs text-gray-600">Orders are assigned to the cheapest & fastest courier automatically.</p>
            </div>

            <div class="bg-white p-6 rounded-xl border border-gray-100 relative text-center">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">3</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Print & Pickup</h4>
                <p class="text-xs text-gray-600">Print shipping labels in bulk and schedule doorstep carrier pickups.</p>
            </div>

            <div class="bg-white p-6 rounded-xl border border-gray-100 relative text-center">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">4</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Track & Collect</h4>
                <p class="text-xs text-gray-600">Real-time buyer tracking and 1-2 day COD payouts directly to your account.</p>
            </div>
        </div>
    </div>
</section>

<!-- 5. Integrated Carrier Logos -->
<section class="py-10 bg-white border-y border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-xs font-bold text-brand-navy uppercase tracking-widest mb-6">Delivering via India's Leading Carriers</p>
        <div class="flex flex-wrap justify-center items-center gap-8 md:gap-12 opacity-75">
            <span class="text-lg md:text-xl font-black text-gray-600">Delhivery</span>
            <span class="text-lg md:text-xl font-black text-gray-600">BlueDart</span>
            <span class="text-lg md:text-xl font-black text-gray-600">XpressBees</span>
            <span class="text-lg md:text-xl font-black text-gray-600">EcomExpress</span>
            <span class="text-lg md:text-xl font-black text-gray-600">Shadowfax</span>
            <span class="text-lg md:text-xl font-black text-gray-600">DTDC</span>
        </div>
    </div>
</section>

<!-- 6. Bottom CTA Card -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-brand-navy via-brand-blue to-brand-navy rounded-3xl p-8 md:p-12 text-white text-center shadow-xl relative overflow-hidden">
            <h2 class="text-2xl md:text-4xl font-extrabold mb-4">
                Ready to Scale Your B2C E-Commerce Logistics?
            </h2>
            <p class="text-sm md:text-base text-gray-200 max-w-2xl mx-auto mb-8 font-medium">
                Join thousands of online sellers who trust OneStall Cargo for faster deliveries, lower shipping rates, and early COD remittance.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md">
                    Create Free Account
                </a>
                <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-full border border-white/40 text-white font-bold text-base hover:bg-white/10 transition">
                    Schedule a Demo
                </a>
            </div>
        </div>
    </div>
</section>
@endsection