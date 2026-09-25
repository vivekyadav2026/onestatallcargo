@extends('layouts.public')
@section('title', "COD Remittance & Settlement | OneStall Cargo Platform")

@section('content')
<!-- 1. Hero Section -->
<section class="bg-gradient-to-r from-blue-50/40 via-white to-blue-50/40 py-12 md:py-16 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="w-full lg:w-1/2 text-center lg:text-left">
                <div class="inline-block px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-brand-navy text-xs font-bold mb-4 uppercase tracking-wide">
                    Financial Settlement Engine
                </div>
                <h1 class="text-3xl md:text-5xl font-extrabold text-brand-navy leading-tight mb-4">
                    Fastest <span class="text-brand-red">1-2 Days COD Payouts</span> for Unstoppable Cash Flow
                </h1>
                <p class="text-base md:text-lg text-gray-700 mb-8 font-medium leading-relaxed">
                    Never let your working capital get frozen. Receive Cash on Delivery remittances directly into your bank account within 24-48 hours with 100% order-level automated reconciliation.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md shadow-red-500/20">
                        Get Early COD Remittance
                    </a>
                    <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-full border-2 border-brand-navy text-brand-navy font-bold text-base hover:bg-brand-navy hover:text-white transition">
                        Talk to Finance Team
                    </a>
                </div>
            </div>

            <div class="w-full lg:w-1/2 relative">
                <div class="relative rounded-2xl overflow-hidden shadow-xl border-4 border-white bg-white">
                    <img src="{{ asset('images/dashboard.jpg') }}" alt="COD Remittance Dashboard" class="w-full h-80 md:h-96 object-cover">
                </div>
                <div class="absolute -bottom-4 -left-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-bold">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Payout Cycle</div>
                        <div class="text-sm font-bold text-brand-navy">1-2 Business Days</div>
                    </div>
                </div>
                <div class="absolute -top-4 -right-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-brand-navy font-bold">
                        <i class="fa-solid fa-file-shield"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Reconciliation</div>
                        <div class="text-sm font-bold text-brand-red">100% Order-Level Audit</div>
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
                <div class="text-2xl md:text-3xl font-black text-white">1-2 Days</div>
                <div class="text-xs md:text-sm text-gray-300">COD Remittance Speed</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">100%</div>
                <div class="text-xs md:text-sm text-gray-300">Automated Reconciliation</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-brand-red">Daily / Weekly</div>
                <div class="text-xs md:text-sm text-gray-300">Custom Payout Options</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">Zero</div>
                <div class="text-xs md:text-sm text-gray-300">Hidden Bank Charges</div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Key Capabilities Grid -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-4xl font-extrabold text-brand-navy mb-3">
                Financial Settlement & Cash Flow Tools
            </h2>
            <p class="text-base text-gray-600">
                Transparent order-level accounting, fast payouts, and zero hidden deductions.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-brand-navy flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-indian-rupee-sign text-brand-navy"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">1-2 Day Early COD Remittance</h3>
                <p class="text-sm text-gray-600">Avoid the traditional 7-14 day courier payout delays. Get cash deposited directly into your bank account in 24-48 hours.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-red-100 text-brand-red flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-file-invoice-dollar text-brand-red"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Automated Order Reconciliation</h3>
                <p class="text-sm text-gray-600">Every single rupee collected by courier partners is automatically matched against AWB numbers with zero manual spreadsheets.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-receipt text-green-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Transparent GST Ledger</h3>
                <p class="text-sm text-gray-600">Download itemized GST-compliant settlement statements, freight invoice summaries, and tax credit reports for hassle-free accounting.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-building-columns text-purple-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Direct Bank Account Credit</h3>
                <p class="text-sm text-gray-600">Automated NEFT / RTGS transfers directly into your registered company bank account with instant UTR reference numbers.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-wallet text-orange-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Auto Freight Settlement</h3>
                <p class="text-sm text-gray-600">Optionally deduct shipping charges directly from incoming COD earnings to keep your prepaid shipping wallet continuously funded.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-triangle-exclamation text-teal-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Short Remittance Protection</h3>
                <p class="text-sm text-gray-600">Automated system flags if a courier partner remits less than the actual invoice amount collected from the buyer.</p>
            </div>
        </div>
    </div>
</section>

<!-- 4. Workflow Section -->
<section class="py-12 md:py-16 bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-3xl font-extrabold text-brand-navy mb-2">
                How COD Settlement Works
            </h2>
            <p class="text-sm md:text-base text-gray-600 font-medium">Fast 1-2 day financial payout pipeline</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">1</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Order Delivered</h4>
                <p class="text-xs text-gray-600">Courier collects Cash on Delivery payment at doorstep.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">2</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Digital Manifest Scan</h4>
                <p class="text-xs text-gray-600">Courier confirms cash deposit into hub bank account.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">3</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Auto-Reconciliation</h4>
                <p class="text-xs text-gray-600">OneStall matches order amount & prepares payout file.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">4</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Bank Account Credit</h4>
                <p class="text-xs text-gray-600">Funds deposited into your bank account in 24-48 hours.</p>
            </div>
        </div>
    </div>
</section>

<!-- 5. CTA Card -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-brand-navy via-brand-blue to-brand-navy rounded-3xl p-8 md:p-12 text-white text-center shadow-xl">
            <h2 class="text-2xl md:text-4xl font-extrabold mb-4">
                Unlock 1-2 Day COD Payouts Today
            </h2>
            <p class="text-sm md:text-base text-gray-200 max-w-2xl mx-auto mb-8 font-medium">
                Accelerate cash flow, eliminate frozen working capital, and get daily automated COD remittance.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md">
                    Get Early COD Remittance
                </a>
                <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-full border border-white/40 text-white font-bold text-base hover:bg-white/10 transition">
                    Contact Finance Team
                </a>
            </div>
        </div>
    </div>
</section>
@endsection