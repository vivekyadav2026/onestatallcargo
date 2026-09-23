@extends('layouts.app')

@section('title', 'OneStall Cargo - Complete End-to-End Logistics Software')

@section('content')
<!-- Hero Section -->
<div class="relative bg-[#0f172a] overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-r from-gray-900 via-[#1e293b] to-gray-900 opacity-90"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiMzMzQiIGZpbGwtb3BhY2l0eT0iMC4xNSIvPjwvc3ZnPg==')] opacity-20"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32 flex flex-col items-center text-center">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/20 text-[var(--gold)] text-sm font-bold uppercase tracking-widest mb-8">
            <span class="w-2 h-2 rounded-full bg-[var(--gold)] animate-pulse"></span>
            India's Premium Logistics Network
        </div>
        
        <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight mb-6">
            Ship Anywhere. <span class="text-[var(--gold)]">Track Everything.</span>
        </h1>
        <p class="text-lg md:text-xl text-gray-300 max-w-3xl mb-12">
            OneStall Cargo brings B2C, B2B Cargo, and Hyperlocal deliveries into one platform. Integrated with top couriers and powered by our unique Video Evidence system.
        </p>

        <!-- Tracking Box -->
        <div class="w-full max-w-2xl bg-white p-2 rounded-2xl shadow-2xl flex flex-col sm:flex-row gap-2 relative z-20">
            <div class="relative flex-grow flex items-center">
                <i class="fa-solid fa-box text-gray-400 absolute left-4"></i>
                <input type="text" placeholder="Enter AWB Number or Shipment ID (e.g. OSC10004561)" class="w-full pl-12 pr-4 py-4 rounded-xl text-gray-900 font-bold focus:outline-none focus:ring-2 focus:ring-[var(--gold)] placeholder-gray-400">
            </div>
            <button class="bg-[var(--gold)] hover:bg-[var(--gold-deep)] text-gray-900 font-extrabold px-8 py-4 rounded-xl transition-colors whitespace-nowrap shadow-md">
                Track Parcel <i class="fa-solid fa-arrow-right ml-2"></i>
            </button>
        </div>
    </div>
</div>

<!-- Key Features -->
<div class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-extrabold text-gray-900">Why Choose OneStall Cargo?</h2>
            <p class="mt-4 text-gray-600 max-w-2xl mx-auto">We provide an end-to-end ecosystem for sellers, franchises, and enterprise clients with unparalleled transparency.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl mb-6">
                    <i class="fa-solid fa-video"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">100% Video Evidence</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Our flagship feature. We record video proof at pickup, sorting hubs, and delivery. Say goodbye to fake return claims and damaged parcel disputes.
                </p>
            </div>
            
            <!-- Feature 2 -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="w-14 h-14 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center text-2xl mb-6">
                    <i class="fa-solid fa-network-wired"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Multi-Courier Aggregation</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Connected with Delhivery, Blue Dart, DTDC, and more. Our dynamic rate engine automatically selects the best carrier for your pincode and weight.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl mb-6">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Automated COD Settlement</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Reliable Cash-on-Delivery collections with fast wallet settlements, digital ledgers for franchises, and real-time reconciliation.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Services Breakdown -->
<div class="py-20 bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="space-y-6">
                <div class="text-[var(--gold-deep)] font-bold uppercase tracking-widest text-sm">Comprehensive Services</div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight">
                    One Platform for All Your Delivery Needs
                </h2>
                <p class="text-gray-600 text-lg">Whether you are an online seller, a corporate entity shipping heavy cargo, or an individual, we have a service tailored for you.</p>
                
                <ul class="space-y-4 mt-8">
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-circle-check mt-1 text-emerald-500"></i>
                        <div>
                            <strong class="block text-gray-900">B2C & E-Commerce</strong>
                            <span class="text-sm text-gray-500">Shopify/WooCommerce API integration with smart NDR management.</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-circle-check mt-1 text-emerald-500"></i>
                        <div>
                            <strong class="block text-gray-900">B2B Cargo & Heavy Freight</strong>
                            <span class="text-sm text-gray-500">PTL/FTL support, Lorry Receipts (LR), and multiple-piece shipment handling.</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-circle-check mt-1 text-emerald-500"></i>
                        <div>
                            <strong class="block text-gray-900">Quick Delivery (Hyperlocal)</strong>
                            <span class="text-sm text-gray-500">Same Day and Next Day deliveries via our dedicated Rider App with live GPS tracking.</span>
                        </div>
                    </li>
                </ul>
            </div>
            
            <div class="relative">
                <div class="absolute inset-0 bg-gradient-to-tr from-[var(--gold)] to-yellow-300 rounded-3xl transform rotate-3 scale-105 opacity-20"></div>
                <div class="bg-gray-900 rounded-3xl p-8 relative shadow-2xl text-white">
                    <h3 class="text-xl font-bold mb-6">Developer Ready API</h3>
                    <div class="bg-[#1e293b] rounded-xl p-4 font-mono text-sm text-green-400 overflow-x-auto">
                        <span class="text-pink-400">POST</span> /api/v1/shipments<br><br>
                        {<br>
                        &nbsp;&nbsp;"pickup_pincode": "110001",<br>
                        &nbsp;&nbsp;"delivery_pincode": "400001",<br>
                        &nbsp;&nbsp;"weight": 1.5,<br>
                        &nbsp;&nbsp;"payment_mode": "COD",<br>
                        &nbsp;&nbsp;"cod_amount": 1499.00<br>
                        }
                    </div>
                    <div class="mt-6 flex justify-end">
                        <a href="#" class="text-[var(--gold)] font-bold text-sm hover:underline">Read API Documentation &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-gradient-to-r from-gray-900 to-[#1e293b] py-16">
    <div class="max-w-4xl mx-auto px-4 text-center space-y-8">
        <h2 class="text-3xl font-extrabold text-white">Ready to streamline your logistics?</h2>
        <p class="text-gray-300">Join thousands of sellers and franchises operating on the OneStall Cargo network.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4 pt-4">
            <a href="{{ route('register') }}" class="px-8 py-4 rounded-xl font-extrabold bg-[var(--gold)] text-gray-900 shadow-lg hover:bg-[var(--gold-deep)] transition-colors">
                Create Seller Account
            </a>
            <a href="#" class="px-8 py-4 rounded-xl font-extrabold bg-white/10 text-white border border-white/20 hover:bg-white/20 transition-colors">
                Apply for Franchise
            </a>
        </div>
    </div>
</div>
@endsection
