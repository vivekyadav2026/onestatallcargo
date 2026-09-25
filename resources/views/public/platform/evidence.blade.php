@extends('layouts.public')
@section('title', "Video Evidence & Fraud Protection | OneStall Cargo Platform")

@section('content')
<!-- 1. Hero Section -->
<section class="bg-gradient-to-r from-blue-50/40 via-white to-blue-50/40 py-12 md:py-16 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="w-full lg:w-1/2 text-center lg:text-left">
                <div class="inline-block px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-brand-navy text-xs font-bold mb-4 uppercase tracking-wide">
                    Anti-Fraud Tech Logistics
                </div>
                <h1 class="text-3xl md:text-5xl font-extrabold text-brand-navy leading-tight mb-4">
                    Eliminate Fake Damage & Empty Box Claims with <span class="text-brand-red">Video Evidence</span>
                </h1>
                <p class="text-base md:text-lg text-gray-700 mb-8 font-medium leading-relaxed">
                    Record HD packing station videos, capture hub handover inspection logs, and attach timestamped video proof to win 98% of courier weight and damage disputes.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md shadow-red-500/20">
                        Enable Video Evidence
                    </a>
                    <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-full border-2 border-brand-navy text-brand-navy font-bold text-base hover:bg-brand-navy hover:text-white transition">
                        Watch Feature Demo
                    </a>
                </div>
            </div>

            <div class="w-full lg:w-1/2 relative">
                <div class="relative rounded-2xl overflow-hidden shadow-xl border-4 border-white bg-white">
                    <img src="{{ asset('images/dashboard.jpg') }}" alt="Video Evidence Dashboard" class="w-full h-80 md:h-96 object-cover">
                </div>
                <div class="absolute -bottom-4 -left-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-bold">
                        <i class="fa-solid fa-video"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Audit Trail</div>
                        <div class="text-sm font-bold text-brand-navy">100% HD Video Proof</div>
                    </div>
                </div>
                <div class="absolute -top-4 -right-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-brand-navy font-bold">
                        <i class="fa-solid fa-shield-check"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Dispute Win Rate</div>
                        <div class="text-sm font-bold text-brand-red">98% Claims Approved</div>
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
                <div class="text-xs md:text-sm text-gray-300">Fraud Protection</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">98%</div>
                <div class="text-xs md:text-sm text-gray-300">Claim Approval Rate</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-brand-red">1080p HD</div>
                <div class="text-xs md:text-sm text-gray-300">Video & Photo Capture</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">Zero</div>
                <div class="text-xs md:text-sm text-gray-300">Seller Losses on Claims</div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Key Capabilities Grid -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-4xl font-extrabold text-brand-navy mb-3">
                How Video Evidence Protects Your Bottom Line
            </h2>
            <p class="text-base text-gray-600">
                Stop paying for courier damage, missing items, or fraudulent customer return claims.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-brand-navy flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-camera font-bold text-brand-navy"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Packing Station Video Recording</h3>
                <p class="text-sm text-gray-600">Record HD video clips of items being packed and sealed. Clips are auto-indexed with barcode scan of the shipping label.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-red-100 text-brand-red flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-box-open text-brand-red"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Return Unboxing Inspection</h3>
                <p class="text-sm text-gray-600">Record unboxing videos when RTO or returned packages arrive back at your warehouse to catch fake or substituted returns.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-stamp text-green-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Timestamped & Geo-Tagged</h3>
                <p class="text-sm text-gray-600">Every recorded video includes tamper-proof cryptographic timestamps and GPS location tagging for legal dispute proof.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-[#E7004C] fa-gavel"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">1-Click Dispute Submission</h3>
                <p class="text-sm text-gray-600">Attach video evidence links directly to carrier weight disputes or claim forms with one single click.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-truck-ramp-box"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Hub Handover Image Audits</h3>
                <p class="text-sm text-gray-600">Capture photo logs during carrier driver pickup handovers to prove packages left your premises in undamaged condition.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Fast Courier Compensation</h3>
                <p class="text-sm text-gray-600">Fast-track insurance and carrier compensation payouts backed by undeniable visual proof.</p>
            </div>
        </div>
    </div>
</section>

<!-- 4. Workflow Section -->
<section class="py-12 md:py-16 bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-3xl font-extrabold text-brand-navy mb-2">
                4 Steps to Zero Fraud Losses
            </h2>
            <p class="text-sm md:text-base text-gray-600 font-medium">Simple video verification workflow</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">1</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Scan & Record</h4>
                <p class="text-xs text-gray-600">Scan AWB barcode at packing station to auto-trigger video recording.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">2</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Auto Cloud Indexing</h4>
                <p class="text-xs text-gray-600">Video clip is compressed & stored securely against AWB number.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">3</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Flag Discrepancy</h4>
                <p class="text-xs text-gray-600">If buyer claims damage or courier overcharges weight, click Flag.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">4</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Instant Refund Claim</h4>
                <p class="text-xs text-gray-600">Attach video proof link to receive 100% claim approval.</p>
            </div>
        </div>
    </div>
</section>

<!-- 5. CTA Card -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-brand-navy via-brand-blue to-brand-navy rounded-3xl p-8 md:p-12 text-white text-center shadow-xl">
            <h2 class="text-2xl md:text-4xl font-extrabold mb-4">
                Protect Your Products with HD Video Evidence
            </h2>
            <p class="text-sm md:text-base text-gray-200 max-w-2xl mx-auto mb-8 font-medium">
                Eliminate fraudulent return claims and win weight discrepancy disputes with undeniable packing videos.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md">
                    Enable Video Evidence Free
                </a>
                <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-full border border-white/40 text-white font-bold text-base hover:bg-white/10 transition">
                    Contact Fraud Desk
                </a>
            </div>
        </div>
    </div>
</section>
@endsection