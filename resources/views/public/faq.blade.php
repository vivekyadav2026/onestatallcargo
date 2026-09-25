@extends('layouts.public')
@section('title', "Frequently Asked Questions (FAQ) | OneStall Cargo")

@section('content')
<div class="bg-gray-50/50 py-10 md:py-12" x-data="faqApp()">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-block px-3 py-0.5 rounded-full border border-blue-200 bg-blue-50 text-brand-navy text-[10px] font-bold mb-3 uppercase tracking-wider">
            Help & FAQs
        </div>
        <h1 class="text-2xl md:text-4xl font-extrabold text-brand-navy leading-tight mb-3">
            Frequently Asked <span class="text-brand-red">Questions</span>
        </h1>
        <p class="text-sm text-gray-600 mb-6 font-medium max-w-xl mx-auto">
            Everything you need to know about rates, courier partners, COD remittance, NDR, and store integrations.
        </p>

        <!-- Search Bar -->
        <div class="relative max-w-lg mx-auto mb-6">
            <input type="text" x-model="searchQuery" placeholder="Search for questions (e.g. COD, rates, NDR)..." class="w-full px-4 py-2.5 pl-10 rounded-full border border-gray-200 shadow-sm text-xs focus:outline-none focus:border-brand-navy bg-white">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
        </div>

        <!-- Category Filter Pills -->
        <div class="flex flex-wrap justify-center gap-1.5 mb-8 text-[11px] font-bold">
            <button @click="activeCategory = 'all'" :class="activeCategory === 'all' ? 'bg-brand-navy text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'" class="px-3 py-1.5 rounded-full transition">All FAQs</button>
            <button @click="activeCategory = 'general'" :class="activeCategory === 'general' ? 'bg-brand-navy text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'" class="px-3 py-1.5 rounded-full transition">General</button>
            <button @click="activeCategory = 'shipping'" :class="activeCategory === 'shipping' ? 'bg-brand-navy text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'" class="px-3 py-1.5 rounded-full transition">Shipping & Rates</button>
            <button @click="activeCategory = 'cod'" :class="activeCategory === 'cod' ? 'bg-brand-navy text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'" class="px-3 py-1.5 rounded-full transition">COD & Remittance</button>
            <button @click="activeCategory = 'ndr'" :class="activeCategory === 'ndr' ? 'bg-brand-navy text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'" class="px-3 py-1.5 rounded-full transition">NDR & Disputes</button>
            <button @click="activeCategory = 'integrations'" :class="activeCategory === 'integrations' ? 'bg-brand-navy text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'" class="px-3 py-1.5 rounded-full transition">APIs & Integrations</button>
        </div>
    </div>

    <!-- Accordion FAQ List -->
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-3">
            <template x-for="(faq, index) in filteredFaqs()" :key="index">
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden transition">
                    <button @click="openFaq = (openFaq === index ? null : index)" class="w-full text-left p-4 flex justify-between items-center font-bold text-gray-900 text-sm focus:outline-none">
                        <span x-text="faq.q"></span>
                        <i class="fa-solid text-xs ml-3" :class="openFaq === index ? 'fa-chevron-up text-brand-red' : 'fa-chevron-down text-gray-400'"></i>
                    </button>
                    <div x-show="openFaq === index" class="px-4 pb-4 text-xs text-gray-600 border-t border-gray-100 pt-2.5 leading-relaxed">
                        <p x-text="faq.a"></p>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<!-- Bottom Minimalist CTA -->
<section class="py-8 bg-white border-t border-gray-100 text-center">
    <div class="max-w-xl mx-auto px-4">
        <h3 class="text-lg font-bold text-brand-navy mb-1">Still Have Questions?</h3>
        <p class="text-xs text-gray-500 mb-4">Our support team is available 24/7 to assist you.</p>
        <div class="flex justify-center gap-3">
            <a href="{{ route('help') }}" class="px-5 py-2 rounded-full bg-brand-navy text-white font-bold text-xs hover:bg-brand-blue transition">
                Help Center
            </a>
            <a href="{{ route('contact') }}" class="px-5 py-2 rounded-full border border-gray-300 text-gray-700 font-bold text-xs hover:bg-gray-50 transition">
                Contact Support
            </a>
        </div>
    </div>
</section>

<script>
function faqApp() {
    return {
        searchQuery: '',
        activeCategory: 'all',
        openFaq: 0,
        faqs: [
            {
                category: 'general',
                q: 'How do I get started with OneStall Cargo?',
                a: 'Getting started takes less than 2 minutes! Create a free account on our registration page, recharge your shipping wallet with any amount, and connect your store (Shopify/WooCommerce) or create your first manual order immediately.'
            },
            {
                category: 'general',
                q: 'Are there any monthly subscription fees or hidden setup charges?',
                a: 'No! OneStall Cargo operates on a 100% pay-as-you-go model. There are zero subscription fees, zero store integration fees, and zero hidden fuel surcharges. You only pay for the shipments you actually book.'
            },
            {
                category: 'shipping',
                q: 'Which courier partners are available on OneStall Cargo?',
                a: 'We provide single-dashboard access to 15+ top courier partners in India, including Delhivery, BlueDart, XpressBees, Ecom Express, Shadowfax, DTDC, and Aramex for international shipments.'
            },
            {
                category: 'shipping',
                q: 'How does automated courier allocation work?',
                a: 'Our recommendation engine automatically compares real-time delivery performance SLAs, cost efficiency, and pin code serviceability to recommend or automatically assign the best courier partner for every order.'
            },
            {
                category: 'cod',
                q: 'How fast is Cash on Delivery (COD) remittance credited?',
                a: 'We offer early COD remittance in just 1-2 business days directly into your bank account, ensuring your business never suffers from frozen working capital.'
            },
            {
                category: 'cod',
                q: 'What are the COD collection charges?',
                a: 'COD collection charges are flat 1.5% of the invoice value or ₹30 (whichever is higher) per delivered Cash on Delivery package.'
            },
            {
                category: 'ndr',
                q: 'How does automated NDR management reduce RTO losses?',
                a: 'When a delivery fails, our automated NDR engine triggers outbound IVR phone calls, SMS, and interactive WhatsApp messages to the buyer to collect corrected addresses or delivery slot requests, converting up to 40% of undelivered packages into successful sales.'
            },
            {
                category: 'ndr',
                q: 'How do I submit weight discrepancy video evidence?',
                a: 'If a courier overcharges shipment weight, click Flag Weight Dispute in your dashboard and attach your packing video or photo proof. Our team verifies the evidence and settles 98% of claims in your favor.'
            },
            {
                category: 'integrations',
                q: 'How do I integrate Shopify or WooCommerce with OneStall?',
                a: 'Install the OneStall Cargo plugin from your store admin, paste your API token, and click Connect. Orders will sync automatically and AWB tracking numbers will be written back to your store admin panel.'
            },
            {
                category: 'integrations',
                q: 'Do you offer REST APIs for custom websites or mobile apps?',
                a: 'Yes! We provide robust RESTful JSON APIs with complete Postman collections and webhooks for rate calculation, AWB generation, live tracking, and NDR actions.'
            }
        ],
        filteredFaqs() {
            return this.faqs.filter(faq => {
                const matchesCategory = (this.activeCategory === 'all' || faq.category === this.activeCategory);
                const matchesSearch = faq.q.toLowerCase().includes(this.searchQuery.toLowerCase()) || faq.a.toLowerCase().includes(this.searchQuery.toLowerCase());
                return matchesCategory && matchesSearch;
            });
        }
    };
}
</script>
@endsection