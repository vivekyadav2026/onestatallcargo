@extends('layouts.public')
@section('title', "Help Center & Seller Support | OneStall Cargo")

@section('content')
<!-- 1. Hero Section -->
<section class="bg-gradient-to-r from-blue-50/40 via-white to-blue-50/40 py-12 md:py-16 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center max-w-3xl mx-auto">
        <div class="inline-block px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-brand-navy text-xs font-bold mb-4 uppercase tracking-wide">
            24/7 Seller Assistance
        </div>
        <h1 class="text-3xl md:text-5xl font-extrabold text-brand-navy leading-tight mb-4">
            OneStall Cargo <span class="text-brand-red">Help Center</span>
        </h1>
        <p class="text-base text-gray-600 mb-8 font-medium">
            Find step-by-step onboarding guides, video tutorials, and dedicated technical support for all your shipping operations.
        </p>

        <!-- Search Bar Input -->
        <div class="relative max-w-xl mx-auto">
            <input type="text" placeholder="Search for help topics, guides, or troubleshooting..." class="w-full px-5 py-3.5 pl-12 rounded-full border border-gray-200 shadow-md text-sm focus:outline-none focus:border-brand-navy">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
        </div>
    </div>
</section>

<!-- 2. Support Categories Grid (6 Cards) -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-3xl font-extrabold text-brand-navy mb-2">
                Knowledge Base & Documentation Guides
            </h2>
            <p class="text-sm text-gray-600">Select a category below to browse articles</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Card 1 -->
            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-200 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-brand-navy flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-user-gear"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Getting Started & Account</h3>
                <p class="text-xs text-gray-600 mb-4">Account verification, GST setup, wallet recharge, and user role management.</p>
                <a href="{{ route('faq') }}" class="text-xs font-bold text-brand-red hover:underline">Read 8 Articles &rarr;</a>
            </div>

            <!-- Card 2 -->
            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-200 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-red-100 text-brand-red flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-box-archive"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Order Booking & Label Printing</h3>
                <p class="text-xs text-gray-600 mb-4">Single & bulk shipments, thermal label printing, packing slips, and pickup manifest generation.</p>
                <a href="{{ route('faq') }}" class="text-xs font-bold text-brand-red hover:underline">Read 12 Articles &rarr;</a>
            </div>

            <!-- Card 3 -->
            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-200 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Live Tracking & Buyer Alerts</h3>
                <p class="text-xs text-gray-600 mb-4">Custom branded tracking URL setup, WhatsApp/SMS milestone notifications, and NPS ratings.</p>
                <a href="{{ route('faq') }}" class="text-xs font-bold text-brand-red hover:underline">Read 10 Articles &rarr;</a>
            </div>

            <!-- Card 4 -->
            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-200 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-indian-rupee-sign"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">COD Remittance & Settlements</h3>
                <p class="text-xs text-gray-600 mb-4">1-2 day payout cycles, bank account verification, order-level reconciliation, and GST ledgers.</p>
                <a href="{{ route('faq') }}" class="text-xs font-bold text-brand-red hover:underline">Read 9 Articles &rarr;</a>
            </div>

            <!-- Card 5 -->
            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-200 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-[#E7004C] fa-shield-cat"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">NDR, RTO & Weight Disputes</h3>
                <p class="text-xs text-gray-600 mb-4">Automated IVR/WhatsApp outreach, address edits, video evidence submission, and claims.</p>
                <a href="{{ route('faq') }}" class="text-xs font-bold text-brand-red hover:underline">Read 14 Articles &rarr;</a>
            </div>

            <!-- Card 6 -->
            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-200 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-code"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Store Integrations & APIs</h3>
                <p class="text-xs text-gray-600 mb-4">Shopify, WooCommerce, Magento plugin setup guides, API keys, and Webhook configuration.</p>
                <a href="{{ route('docs') }}" class="text-xs font-bold text-brand-red hover:underline">Read 15 Articles &rarr;</a>
            </div>
        </div>
    </div>
</section>

<!-- 3. Direct Contact Support Channels Grid -->
<section class="py-12 bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-10">
            <h2 class="text-2xl font-bold text-brand-navy">Contact Support Directly</h2>
            <p class="text-xs text-gray-500 font-medium">Multiple channels available for instant operational assistance</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-brand-navy flex items-center justify-center text-xl mx-auto mb-3">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <h4 class="font-bold text-gray-900 mb-1 text-base">Live Seller Chat</h4>
                <p class="text-xs text-gray-600 mb-4">Chat with our support agents inside your dashboard panel.</p>
                <a href="{{ route('login') }}" class="inline-block text-xs font-bold text-brand-red hover:underline">Open Live Chat &rarr;</a>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                <div class="w-10 h-10 rounded-full bg-red-100 text-brand-red flex items-center justify-center text-xl mx-auto mb-3">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <h4 class="font-bold text-gray-900 mb-1 text-base">Email Ticket Desk</h4>
                <p class="text-xs text-gray-600 mb-4">Send detailed ticket inquiries with 1-hour SLA response time.</p>
                <a href="{{ route('contact') }}" class="inline-block text-xs font-bold text-brand-red hover:underline">Submit Support Ticket &rarr;</a>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xl mx-auto mb-3">
                    <i class="fa-solid fa-phone"></i>
                </div>
                <h4 class="font-bold text-gray-900 mb-1 text-base">Toll-Free Helpline</h4>
                <p class="text-xs text-gray-600 mb-4">Speak directly to an operations representative for urgent issues.</p>
                <a href="tel:1800123456" class="inline-block text-xs font-bold text-brand-navy hover:underline">{{ setting('site_phone', '1800-123-4567') }}</a>
            </div>
        </div>
    </div>
</section>
@endsection