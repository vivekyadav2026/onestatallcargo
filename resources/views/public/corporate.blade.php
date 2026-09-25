@extends('layouts.public')
@section('title', "Corporate & Enterprise Logistics | OneStall Cargo")

@section('content')
<!-- 1. Hero Section -->
<section class="bg-gradient-to-r from-blue-50/40 via-white to-blue-50/40 py-12 md:py-16 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="w-full lg:w-1/2 text-center lg:text-left">
                <div class="inline-block px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-brand-navy text-xs font-bold mb-4 uppercase tracking-wide">
                    Enterprise Supply Chain Solutions
                </div>
                <h1 class="text-3xl md:text-5xl font-extrabold text-brand-navy leading-tight mb-4">
                    Enterprise-Grade <span class="text-brand-red">Corporate Logistics</span>
                </h1>
                <p class="text-base md:text-lg text-gray-700 mb-8 font-medium leading-relaxed">
                    Customized SLA contracts, dedicated freight truck allocation, SAP/ERP integrations, and 15-30 day credit billing terms tailored for large corporations and manufacturers.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md shadow-red-500/20">
                        Schedule Corporate Demo
                    </a>
                    <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full border-2 border-brand-navy text-brand-navy font-bold text-base hover:bg-brand-navy hover:text-white transition">
                        Open Corporate Account
                    </a>
                </div>
            </div>

            <div class="w-full lg:w-1/2 relative">
                <div class="relative rounded-2xl overflow-hidden shadow-xl border-4 border-white bg-white">
                    <img src="{{ asset('images/warehouse.jpg') }}" alt="Corporate Logistics" class="w-full h-80 md:h-96 object-cover">
                </div>
                <div class="absolute -bottom-4 -left-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-bold">
                        <i class="fa-solid fa-building flex items-center justify-center"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Enterprise Clients</div>
                        <div class="text-sm font-bold text-brand-navy">500+ Corporate Brands</div>
                    </div>
                </div>
                <div class="absolute -top-4 -right-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-brand-navy font-bold">
                        <i class="fa-solid fa-file-contract"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Credit Terms</div>
                        <div class="text-sm font-bold text-brand-red">15 - 30 Days Payouts</div>
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
                <div class="text-2xl md:text-3xl font-black text-white">500+</div>
                <div class="text-xs md:text-sm text-gray-300">Corporate Clients</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">99.8%</div>
                <div class="text-xs md:text-sm text-gray-300">Guaranteed SLA Adherence</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-brand-red">Custom ERP</div>
                <div class="text-xs md:text-sm text-gray-300">SAP & Oracle Pipeline</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">Dedicated</div>
                <div class="text-xs md:text-sm text-gray-300">Operations Desk</div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Enterprise Features Grid -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-4xl font-extrabold text-brand-navy mb-3">
                Tailored Corporate Supply Chain Infrastructure
            </h2>
            <p class="text-base text-gray-600">
                End-to-end logistics contracts designed specifically for large-scale enterprise workflows.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-brand-navy flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-user-gear"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Dedicated Key Account Manager</h3>
                <p class="text-sm text-gray-600">Single point of contact responsible for daily shipment coordination, customs compliance, and carrier SLA management.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-red-100 text-brand-red flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-network-wired"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">SAP & Oracle ERP Sync</h3>
                <p class="text-sm text-gray-600">Custom webhooks and direct API pipelines connecting your existing enterprise ERP system directly with our dispatch engine.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">15-30 Days Credit Billing</h3>
                <p class="text-sm text-gray-600">Flexible credit payment terms with weekly itemized GST invoices to match corporate accounting cycles.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-snowflake"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Cold Chain & Sensitive Cargo</h3>
                <p class="text-sm text-gray-600">Temperature-controlled transport vehicles and specialized packaging for pharmaceutical, chemical, and perishable goods.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Custom Transit Risk Coverage</h3>
                <p class="text-sm text-gray-600">Tailored transit insurance policies offering full invoice value protection against loss, theft, or damage.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Custom Analytics & Reporting</h3>
                <p class="text-sm text-gray-600">Monthly executive dashboards summarizing freight expenditure, carrier SLA performance, and carbon emission reports.</p>
            </div>
        </div>
    </div>
</section>

<!-- 4. CTA Card -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-brand-navy via-brand-blue to-brand-navy rounded-3xl p-8 md:p-12 text-white text-center shadow-xl">
            <h2 class="text-2xl md:text-4xl font-extrabold mb-4">
                Let's Build a Custom Enterprise Contract
            </h2>
            <p class="text-sm md:text-base text-gray-200 max-w-2xl mx-auto mb-8 font-medium">
                Speak to our corporate logistics experts to discuss customized freight slabs, ERP integrations, and credit terms.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md">
                    Schedule Executive Meeting
                </a>
            </div>
        </div>
    </div>
</section>
@endsection