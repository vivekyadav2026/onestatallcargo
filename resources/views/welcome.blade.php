@extends('layouts.app')
@section('title', 'OneStall Cargo - Global Logistics & Courier Aggregator')

@section('content')
<!-- Hero Section -->
<div class="relative bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32 pt-20">
            <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                <div class="sm:text-center lg:text-left">
                    <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-widest border border-blue-100 mb-4 inline-block">India's #1 Shipping Platform</span>
                    <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                        <span class="block xl:inline">Deliver anywhere with</span>
                        <span class="block text-[#FFD700] drop-shadow-sm">OneStall Cargo</span>
                    </h1>
                    <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                        B2C, B2B, and International shipping powered by our smart Courier Aggregator API. Automate your logistics, print labels, and track in real-time.
                    </p>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                        <div class="rounded-xl shadow">
                            <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-extrabold rounded-xl text-gray-900 bg-[#FFD700] hover:bg-[#E5C100] md:py-4 md:text-lg md:px-10 transition">
                                Start Shipping Now
                            </a>
                        </div>
                        <div class="mt-3 sm:mt-0 sm:ml-3">
                            <a href="{{ route('track') }}" class="w-full flex items-center justify-center px-8 py-3 border-2 border-gray-200 text-base font-bold rounded-xl text-gray-700 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10 transition">
                                Track a Parcel
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <!-- Decorative Image Area (Abstract representation since we don't have a real asset) -->
    <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-[#1e293b] flex items-center justify-center p-12">
        <div class="grid grid-cols-2 gap-4 w-full max-w-md opacity-80">
            <div class="bg-gray-800 rounded-2xl h-32 flex items-center justify-center border border-gray-700 shadow-xl transform -translate-y-4">
                <i class="fa-solid fa-truck-fast text-4xl text-[#FFD700]"></i>
            </div>
            <div class="bg-gray-800 rounded-2xl h-48 flex items-center justify-center border border-gray-700 shadow-xl">
                <i class="fa-solid fa-plane-departure text-4xl text-[#FFD700]"></i>
            </div>
            <div class="bg-gray-800 rounded-2xl h-48 flex items-center justify-center border border-gray-700 shadow-xl transform -translate-y-8">
                <i class="fa-solid fa-boxes-stacked text-4xl text-[#FFD700]"></i>
            </div>
            <div class="bg-gray-800 rounded-2xl h-32 flex items-center justify-center border border-gray-700 shadow-xl transform translate-y-4">
                <i class="fa-solid fa-qrcode text-4xl text-[#FFD700]"></i>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-base text-[#D4AF37] font-extrabold tracking-wide uppercase">All-in-One Logistics</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">Everything you need to scale</p>
            <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">From single D2C orders to heavy Freight cargo, we handle the complex logistics so you don't have to.</p>
        </div>

        <div class="mt-16">
            <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-3 md:gap-x-8 md:gap-y-10">
                
                <div class="relative bg-white p-8 rounded-3xl border border-gray-200 shadow-sm hover:shadow-lg transition">
                    <dt>
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-xl bg-blue-100 text-blue-600 border border-blue-200">
                            <i class="fa-solid fa-truck text-xl"></i>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-bold text-gray-900">Courier Aggregator</p>
                    </dt>
                    <dd class="mt-4 ml-16 text-base text-gray-500">
                        Automatically assign the best courier partner (Delhivery, BlueDart, Ecom Express) based on real-time routing algorithms and costs.
                    </dd>
                </div>

                <div class="relative bg-white p-8 rounded-3xl border border-gray-200 shadow-sm hover:shadow-lg transition">
                    <dt>
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-xl bg-orange-100 text-orange-600 border border-orange-200">
                            <i class="fa-solid fa-weight-hanging text-xl"></i>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-bold text-gray-900">B2B Heavy Cargo</p>
                    </dt>
                    <dd class="mt-4 ml-16 text-base text-gray-500">
                        Require FTL or PTL? Book heavy cargo easily. Generate instant E-Way bills and Consignment Notes (LR) right from the dashboard.
                    </dd>
                </div>

                <div class="relative bg-white p-8 rounded-3xl border border-gray-200 shadow-sm hover:shadow-lg transition">
                    <dt>
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-xl bg-purple-100 text-purple-600 border border-purple-200">
                            <i class="fa-solid fa-globe text-xl"></i>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-bold text-gray-900">International Shipping</p>
                    </dt>
                    <dd class="mt-4 ml-16 text-base text-gray-500">
                        Cross-border made simple. Generate customs declarations, define HS Codes, and ship to over 200+ countries with transparent pricing.
                    </dd>
                </div>

                <div class="relative bg-white p-8 rounded-3xl border border-gray-200 shadow-sm hover:shadow-lg transition">
                    <dt>
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-xl bg-green-100 text-green-600 border border-green-200">
                            <i class="fa-solid fa-file-csv text-xl"></i>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-bold text-gray-900">Bulk CSV Upload</p>
                    </dt>
                    <dd class="mt-4 ml-16 text-base text-gray-500">
                        Have 1,000 orders to ship today? Upload a single Excel/CSV file to book them all at once and instantly download thermal AWB labels.
                    </dd>
                </div>

                <div class="relative bg-white p-8 rounded-3xl border border-gray-200 shadow-sm hover:shadow-lg transition">
                    <dt>
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-xl bg-red-100 text-red-600 border border-red-200">
                            <i class="fa-solid fa-video text-xl"></i>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-bold text-gray-900">Video Evidence & NDR</p>
                    </dt>
                    <dd class="mt-4 ml-16 text-base text-gray-500">
                        Our Rider App records video proof for every pickup and delivery. Dispute fake RTOs easily with immutable video evidence.
                    </dd>
                </div>

                <div class="relative bg-white p-8 rounded-3xl border border-gray-200 shadow-sm hover:shadow-lg transition">
                    <dt>
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-xl bg-indigo-100 text-indigo-600 border border-indigo-200">
                            <i class="fa-solid fa-code text-xl"></i>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-bold text-gray-900">Developer API</p>
                    </dt>
                    <dd class="mt-4 ml-16 text-base text-gray-500">
                        Integrate our shipping engine directly into Shopify, WooCommerce, or your custom ERP with our comprehensive REST API.
                    </dd>
                </div>

            </dl>
        </div>
    </div>
</div>

<!-- CTA -->
<div class="bg-[#1e293b]">
    <div class="max-w-2xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
            <span class="block">Ready to streamline your shipping?</span>
        </h2>
        <p class="mt-4 text-lg leading-6 text-gray-300">Join thousands of sellers shipping millions of parcels daily with OneStall Cargo.</p>
        <a href="{{ route('register') }}" class="mt-8 w-full inline-flex items-center justify-center px-8 py-4 border border-transparent text-base font-bold rounded-xl text-gray-900 bg-[#FFD700] hover:bg-[#E5C100] sm:w-auto transition shadow-lg">
            Create Free Account
        </a>
    </div>
</div>
@endsection
