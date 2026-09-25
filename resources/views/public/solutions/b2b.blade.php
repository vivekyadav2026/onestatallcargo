@extends('layouts.public')
@section('title', "B2B & Cargo Shipping Solutions | OneStall Cargo")

@section('content')
<!-- 1. Hero Section -->
<section class="bg-gradient-to-r from-blue-50/40 via-white to-blue-50/40 py-12 md:py-16 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="w-full lg:w-1/2 text-center lg:text-left">
                <div class="inline-block px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-brand-navy text-xs font-bold mb-4 uppercase tracking-wide">
                    Bulk Freight & Logistics
                </div>
                <h1 class="text-3xl md:text-5xl font-extrabold text-brand-navy leading-tight mb-4">
                    Heavy Freight & <span class="text-brand-red">B2B Cargo Shipping</span> Made Simple
                </h1>
                <p class="text-base md:text-lg text-gray-700 mb-8 font-medium leading-relaxed">
                    Move bulk inventory across warehouses, offline distributors, and retail hubs with our LTL (Less-Than-Truckload) and FTL (Full-Truckload) network at discounted freight rates.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md shadow-red-500/20">
                        Get B2B Rates
                    </a>
                    <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-full border-2 border-brand-navy text-brand-navy font-bold text-base hover:bg-brand-navy hover:text-white transition">
                        Speak to Cargo Expert
                    </a>
                </div>
            </div>

            <div class="w-full lg:w-1/2 relative">
                <div class="relative rounded-2xl overflow-hidden shadow-xl border-4 border-white bg-white">
                    <img src="{{ asset('images/warehouse.jpg') }}" alt="B2B Cargo Logistics" class="w-full h-80 md:h-96 object-cover">
                </div>
                <div class="absolute -bottom-4 -left-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-bold">
                        <i class="fa-solid fa-truck-moving"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Freight Savings</div>
                        <div class="text-sm font-bold text-brand-navy">Up to 30% Discount</div>
                    </div>
                </div>
                <div class="absolute -top-4 -right-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-brand-navy font-bold">
                        <i class="fa-solid fa-boxes-packing"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Consignment Type</div>
                        <div class="text-sm font-bold text-brand-red">LTL & FTL Available</div>
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
                <div class="text-2xl md:text-3xl font-black text-white">100+</div>
                <div class="text-xs md:text-sm text-gray-300">Commercial Hubs</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">LTL & FTL</div>
                <div class="text-xs md:text-sm text-gray-300">Flexible Transport</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-brand-red">100%</div>
                <div class="text-xs md:text-sm text-gray-300">Transit Protection Cover</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">Dedicated</div>
                <div class="text-xs md:text-sm text-gray-300">Account Manager</div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Key Capabilities Grid -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-4xl font-extrabold text-brand-navy mb-3">
                Enterprise B2B & Cargo Solutions
            </h2>
            <p class="text-base text-gray-600">
                End-to-end commercial logistics designed for manufacturers, wholesalers, and multi-location retail brands.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-brand-navy flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-truck-ramp-box"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Doorstep Heavy Pickup</h3>
                <p class="text-sm text-gray-600">Schedule dock-to-dock pickups with heavy vehicle support for pallets, crates, and multi-box cargo shipments.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-red-100 text-brand-red flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Appointment-Based Delivery</h3>
                <p class="text-sm text-gray-600">Ensure fixed slot delivery at major retail chains, malls, and distribution centers with strict SLA compliance.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-[#E7004C] fa-file-invoice"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">GST & E-Way Bill Compliance</h3>
                <p class="text-sm text-gray-600">Automated E-Way bill generation and seamless GST compliance checks for uninterrupted interstate cargo movement.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Multi-Box Tracking</h3>
                <p class="text-sm text-gray-600">Track complex shipments containing dozens or hundreds of packages under a single master consignment note.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-shield-heart"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Freight Protection & Insurance</h3>
                <p class="text-sm text-gray-600">Full transit risk coverage protecting high-value inventory against damage, loss, or pilferage.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Dedicated Key Account Manager</h3>
                <p class="text-sm text-gray-600">Get a single point of contact for custom freight rate negotiation, route planning, and dedicated support.</p>
            </div>
        </div>
    </div>
</section>

<!-- 4. Workflow Section -->
<section class="py-12 md:py-16 bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-3xl font-extrabold text-brand-navy mb-2">
                4 Steps to Move Bulk Cargo Nationwide
            </h2>
            <p class="text-sm md:text-base text-gray-600 font-medium">Simplified B2B freight logistics</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">1</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Book Consignment</h4>
                <p class="text-xs text-gray-600">Enter weight, dimensions, and pick-up details in your B2B dashboard.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">2</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Scheduled Dock Pickup</h4>
                <p class="text-xs text-gray-600">Truck arrives at your warehouse with dock-loading support.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">3</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Line-Haul Express Transit</h4>
                <p class="text-xs text-gray-600">Fast inter-state transport via dedicated commercial cargo lanes.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-navy text-white font-bold flex items-center justify-center mx-auto mb-4 text-sm">4</div>
                <h4 class="font-bold text-gray-900 mb-2 text-base">Verified Unloading</h4>
                <p class="text-xs text-gray-600">POD (Proof of Delivery) digital sign-off and doorstep unloading.</p>
            </div>
        </div>
    </div>
</section>

<!-- 5. CTA Card -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-brand-navy via-brand-blue to-brand-navy rounded-3xl p-8 md:p-12 text-white text-center shadow-xl">
            <h2 class="text-2xl md:text-4xl font-extrabold mb-4">
                Ready to Optimize Your B2B Supply Chain?
            </h2>
            <p class="text-sm md:text-base text-gray-200 max-w-2xl mx-auto mb-8 font-medium">
                Get custom freight quotes, dedicated account management, and reliable LTL/FTL cargo transport across India.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md">
                    Create B2B Account
                </a>
                <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-full border border-white/40 text-white font-bold text-base hover:bg-white/10 transition">
                    Contact Cargo Desk
                </a>
            </div>
        </div>
    </div>
</section>
@endsection