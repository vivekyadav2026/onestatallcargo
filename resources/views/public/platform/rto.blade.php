@extends('layouts.public')
@section('title', "RTO Reduction & Risk Intelligence | OneStall Cargo Platform")

@section('content')
<!-- 1. Hero Section -->
<section class="bg-gradient-to-r from-blue-50/40 via-white to-blue-50/40 py-12 md:py-16 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="w-full lg:w-1/2 text-center lg:text-left">
                <div class="inline-block px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-brand-navy text-xs font-bold mb-4 uppercase tracking-wide">
                    RTO Intelligence Engine
                </div>
                <h1 class="text-3xl md:text-5xl font-extrabold text-brand-navy leading-tight mb-4">
                    Protect Profit Margins with <span class="text-brand-red">Smart RTO Reduction</span>
                </h1>
                <p class="text-base md:text-lg text-gray-700 mb-8 font-medium leading-relaxed">
                    Identify high-risk COD orders before shipping, standardize incomplete delivery addresses, convert COD to prepaid, and track reverse RTO logistics in real-time.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md shadow-red-500/20">
                        Cut RTO Losses
                    </a>
                    <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-full border-2 border-brand-navy text-brand-navy font-bold text-base hover:bg-brand-navy hover:text-white transition">
                        Get RTO Audit
                    </a>
                </div>
            </div>

            <div class="w-full lg:w-1/2 relative">
                <div class="relative rounded-2xl overflow-hidden shadow-xl border-4 border-white bg-white">
                    <img src="{{ asset('images/dashboard.jpg') }}" alt="RTO Intelligence Dashboard" class="w-full h-80 md:h-96 object-cover">
                </div>
                <div class="absolute -bottom-4 -left-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-bold">
                        <i class="fa-solid fa-[#E7004C] fa-shield-halved"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Risk Score</div>
                        <div class="text-sm font-bold text-brand-navy">Pre-Dispatch COD Check</div>
                    </div>
                </div>
                <div class="absolute -top-4 -right-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-brand-navy font-bold">
                        <i class="fa-solid fa-arrow-rotate-left"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Reverse Tracking</div>
                        <div class="text-sm font-bold text-brand-red">100% RTO Visibility</div>
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
                <div class="text-2xl md:text-3xl font-black text-white">40%</div>
                <div class="text-xs md:text-sm text-gray-300">Average RTO Reduction</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">Pre-Dispatch</div>
                <div class="text-xs md:text-sm text-gray-300">Risk Intelligence Score</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-brand-red">COD to Prepaid</div>
                <div class="text-xs md:text-sm text-gray-300">Automated Conversion</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">100%</div>
                <div class="text-xs md:text-sm text-gray-300">Reverse Package Visibility</div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Key Capabilities Grid -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-4xl font-extrabold text-brand-navy mb-3">
                Smart RTO Reduction & Prevention Tools
            </h2>
            <p class="text-base text-gray-600">
                Actionable tools to prevent return losses before shipping and track reverse shipments back to your warehouse.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-brand-navy flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-[#E7004C] fa-[#E7004C] fa-magnifying-glass-chart text-brand-navy"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Pre-Dispatch Risk Scoring</h3>
                <p class="text-sm text-gray-600">Analyze buyer address completeness and past purchase reliability to flag high-risk COD orders before packing.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-red-100 text-brand-red flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-address-book text-brand-red"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Address Cleaning & Validation</h3>
                <p class="text-sm text-gray-600">Fix typos, missing house numbers, and incorrect pin codes automatically before generating shipping labels.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-credit-card text-green-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">COD to Prepaid Conversion</h3>
                <p class="text-sm text-gray-600">Automate WhatsApp incentives (e.g. 5% extra discount link) encouraging buyers to prepay for risky COD orders.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-arrow-rotate-left"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Reverse RTO Tracking</h3>
                <p class="text-sm text-gray-600">Track returning RTO packages from destination hubs back to your warehouse so inventory is never lost in transit.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-ban"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Repeat Refusal Blacklisting</h3>
                <p class="text-sm text-gray-600">Automatically flag or block buyers who repeatedly reject COD orders at doorstep across your stores.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">RTO Cost Impact Report</h3>
                <p class="text-sm text-gray-600">Quantify two-way freight costs, product damage impact, and courier-wise RTO percentages in one clear report.</p>
            </div>
        </div>
    </div>
</section>

<!-- 4. Workflow Section -->
<section class="py-12 md:py-16 bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-3xl font-extrabold text-brand-navy mb-2">
                4 Steps to Stop RTO Losses
            </h2>
            <p class="text-sm md:text-base text-gray-600 font-medium">Proactive RTO prevention workflow</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">1</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Risk Assessment</h4>
                <p class="text-xs text-gray-600">New order analyzed for address quality & COD risk score.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">2</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Address Fix & Confirm</h4>
                <p class="text-xs text-gray-600">Incomplete addresses corrected automatically or via WhatsApp link.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">3</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Safe Courier Route</h4>
                <p class="text-xs text-gray-600">Order allocated to courier with highest SLA in that pin code.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">4</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Reverse RTO Tracking</h4>
                <p class="text-xs text-gray-600">If RTO occurs, track returning package until re-stocked.</p>
            </div>
        </div>
    </div>
</section>

<!-- 5. CTA Card -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-brand-navy via-brand-blue to-brand-navy rounded-3xl p-8 md:p-12 text-white text-center shadow-xl">
            <h2 class="text-2xl md:text-4xl font-extrabold mb-4">
                Slash Your RTO Rate by 40% Today
            </h2>
            <p class="text-sm md:text-base text-gray-200 max-w-2xl mx-auto mb-8 font-medium">
                Protect your profit margins with pre-dispatch risk intelligence and automated reverse logistics tracking.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md">
                    Reduce RTO Now
                </a>
                <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-full border border-white/40 text-white font-bold text-base hover:bg-white/10 transition">
                    Request RTO Audit
                </a>
            </div>
        </div>
    </div>
</section>
@endsection