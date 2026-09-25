@extends('layouts.public')
@section('title', "Courier Aggregation Solutions | OneStall Cargo")

@section('content')
<!-- 1. Hero Section -->
<section class="bg-gradient-to-r from-blue-50/40 via-white to-blue-50/40 py-12 md:py-16 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="w-full lg:w-1/2 text-center lg:text-left">
                <div class="inline-block px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-brand-navy text-xs font-bold mb-4 uppercase tracking-wide">
                    Multi-Courier Aggregation
                </div>
                <h1 class="text-3xl md:text-5xl font-extrabold text-brand-navy leading-tight mb-4">
                    Single Dashboard Access to <span class="text-brand-red">15+ Top Courier Networks</span>
                </h1>
                <p class="text-base md:text-lg text-gray-700 mb-8 font-medium leading-relaxed">
                    Stop managing multiple courier contracts. Get instant access to Delhivery, BlueDart, XpressBees, Ecom Express, and more under one single unified platform.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md shadow-red-500/20">
                        Create Free Account
                    </a>
                    <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-full border-2 border-brand-navy text-brand-navy font-bold text-base hover:bg-brand-navy hover:text-white transition">
                        Compare Rates
                    </a>
                </div>
            </div>

            <div class="w-full lg:w-1/2 relative">
                <div class="relative rounded-2xl overflow-hidden shadow-xl border-4 border-white bg-white">
                    <img src="{{ asset('images/dashboard.jpg') }}" alt="Courier Aggregation Dashboard" class="w-full h-80 md:h-96 object-cover">
                </div>
                <div class="absolute -bottom-4 -left-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-bold">
                        <i class="fa-solid fa-network-wired"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Unified Integration</div>
                        <div class="text-sm font-bold text-brand-navy">15+ Integrated Carriers</div>
                    </div>
                </div>
                <div class="absolute -top-4 -right-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-brand-navy font-bold">
                        <i class="fa-solid fa-sliders"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Smart Recommendation</div>
                        <div class="text-sm font-bold text-brand-red">Auto Rate & SLA Matching</div>
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
                <div class="text-2xl md:text-3xl font-black text-white">15+</div>
                <div class="text-xs md:text-sm text-gray-300">Carrier Partners</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">1 Single</div>
                <div class="text-xs md:text-sm text-gray-300">Recharge Wallet</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-brand-red">Up to 40%</div>
                <div class="text-xs md:text-sm text-gray-300">Cheaper Freight Rates</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">Centralized</div>
                <div class="text-xs md:text-sm text-gray-300">NDR & Disputes Engine</div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Key Capabilities Grid -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-4xl font-extrabold text-brand-navy mb-3">
                Why Top E-Commerce Brands Choose Courier Aggregation
            </h2>
            <p class="text-base text-gray-600">
                Diversify courier dependency and optimize every single order for cost and speed automatically.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-brand-navy flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-[#E7004C] fa-file-contract"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Single Master Contract</h3>
                <p class="text-sm text-gray-600">No need to sign separate contracts or negotiate minimum monthly volumes with individual courier companies.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-red-100 text-brand-red flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Real-Time Performance Scoring</h3>
                <p class="text-sm text-gray-600">Compare delivery success rates and NDR resolution speed across different couriers for specific pin codes.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Unified Wallet & Billing</h3>
                <p class="text-sm text-gray-600">Maintain one single prepaid shipping wallet for all your orders regardless of which courier carries the shipment.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-repeat"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Fail-Safe Auto Routing</h3>
                <p class="text-sm text-gray-600">If a courier suffers a strike or hub delay in a region, orders are instantly re-routed to alternative active carriers.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-shield"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Centralized Dispute Resolution</h3>
                <p class="text-sm text-gray-600">One dedicated support team handles lost shipment claims, weight disputes, and damage claims on your behalf.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-sliders"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Custom Rule Engine</h3>
                <p class="text-sm text-gray-600">Set custom allocation rules (e.g. ship high-value COD orders via BlueDart, lightweight prepaids via Delhivery).</p>
            </div>
        </div>
    </div>
</section>

<!-- 4. Workflow Section -->
<section class="py-12 md:py-16 bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-3xl font-extrabold text-brand-navy mb-2">
                How Courier Aggregation Works
            </h2>
            <p class="text-sm md:text-base text-gray-600 font-medium">One dashboard powering all your shipping carriers</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">1</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Connect Store</h4>
                <p class="text-xs text-gray-600">Integrate Shopify, WooCommerce, or custom store with 1-click.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">2</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Auto Rate Match</h4>
                <p class="text-xs text-gray-600">System compares rates & SLAs across 15+ carriers instantly.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">3</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Single Label Format</h4>
                <p class="text-xs text-gray-600">Print standardized shipping labels regardless of carrier chosen.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">4</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Unified Payouts</h4>
                <p class="text-xs text-gray-600">Receive combined COD remittance in a single 1-2 day payout cycle.</p>
            </div>
        </div>
    </div>
</section>

<!-- 5. CTA Card -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-brand-navy via-brand-blue to-brand-navy rounded-3xl p-8 md:p-12 text-white text-center shadow-xl">
            <h2 class="text-2xl md:text-4xl font-extrabold mb-4">
                Unlock 15+ Courier Networks With One Account
            </h2>
            <p class="text-sm md:text-base text-gray-200 max-w-2xl mx-auto mb-8 font-medium">
                No setup fee, no monthly subscriptions. Recharge your shipping wallet and start shipping in 5 minutes.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md">
                    Create Free Account
                </a>
                <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-full border border-white/40 text-white font-bold text-base hover:bg-white/10 transition">
                    Compare Rates
                </a>
            </div>
        </div>
    </div>
</section>
@endsection