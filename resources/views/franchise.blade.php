@extends('layouts.public')
@section('title', "Logistics Franchise & Partner Hub Program | OneStall Cargo")

@section('content')
<!-- 1. Hero Section -->
<section class="bg-gradient-to-r from-blue-50/40 via-white to-blue-50/40 py-12 md:py-16 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="w-full lg:w-1/2 text-center lg:text-left">
                <div class="inline-block px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-brand-navy text-xs font-bold mb-4 uppercase tracking-wide">
                    Business Partnership Opportunity
                </div>
                <h1 class="text-3xl md:text-5xl font-extrabold text-brand-navy leading-tight mb-4">
                    Own a High-ROI <span class="text-brand-red">Logistics Hub Franchise</span>
                </h1>
                <p class="text-base md:text-lg text-gray-700 mb-8 font-medium leading-relaxed">
                    Partner with OneStall Cargo to open a local logistics booking counter or sorting hub in your city. Earn high commission margins on every B2B, B2C, and cargo shipment.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="#apply" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md shadow-red-500/20">
                        Apply for Franchise
                    </a>
                    <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-full border-2 border-brand-navy text-brand-navy font-bold text-base hover:bg-brand-navy hover:text-white transition">
                        Download Brochure
                    </a>
                </div>
            </div>

            <div class="w-full lg:w-1/2 relative">
                <div class="relative rounded-2xl overflow-hidden shadow-xl border-4 border-white bg-white">
                    <img src="{{ asset('images/warehouse.jpg') }}" alt="Franchise Logistics Hub" class="w-full h-80 md:h-96 object-cover">
                </div>
                <div class="absolute -bottom-4 -left-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-bold">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Profit Margin</div>
                        <div class="text-sm font-bold text-brand-navy">35% - 45% ROI</div>
                    </div>
                </div>
                <div class="absolute -top-4 -right-4 bg-white p-3.5 rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 z-20">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-brand-navy font-bold">
                        <i class="fa-solid fa-store"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Territory Exclusivity</div>
                        <div class="text-sm font-bold text-brand-red">Guaranteed Pin Code Rights</div>
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
                <div class="text-xs md:text-sm text-gray-300">Active Franchise Hubs</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">35-45%</div>
                <div class="text-xs md:text-sm text-gray-300">Average Profit Margin</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-brand-red">30 Days</div>
                <div class="text-xs md:text-sm text-gray-300">Fast Launch Time</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">100%</div>
                <div class="text-xs md:text-sm text-gray-300">Tech & Ops Training</div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Franchise Models Grid -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-4xl font-extrabold text-brand-navy mb-3">
                Select Your Logistics Franchise Model
            </h2>
            <p class="text-base text-gray-600">
                Choose an investment tier suited to your budget and local commercial footprint.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
            <!-- Model 1 -->
            <div class="bg-gray-50/70 p-8 rounded-3xl border border-gray-200 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 rounded-2xl bg-blue-100 text-brand-navy flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-shop"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Booking Counter Micro-Hub</h3>
                <p class="text-sm text-gray-600 mb-6">Perfect for existing shop owners, stationery stores, or retail counters looking to add shipping services.</p>
                <div class="space-y-3 text-xs text-gray-700 mb-8">
                    <div class="flex justify-between border-b pb-2"><span>Investment Required:</span> <strong class="text-brand-navy">&#8377; 50,000 - &#8377; 1,000,000</strong></div>
                    <div class="flex justify-between border-b pb-2"><span>Space Needed:</span> <strong class="text-brand-navy">100 - 200 Sq. Ft.</strong></div>
                    <div class="flex justify-between border-b pb-2"><span>Role:</span> <strong>Parcel Intake & Booking Counter</strong></div>
                    <div class="flex justify-between border-b pb-2"><span>Software Provided:</span> <strong class="text-green-600">Hub Dashboard + Barcode Scanner</strong></div>
                </div>
                <a href="#apply" class="block w-full py-3 rounded-full bg-brand-navy text-white text-center font-bold text-sm hover:bg-brand-blue transition">
                    Apply for Micro-Hub
                </a>
            </div>

            <!-- Model 2 -->
            <div class="bg-white p-8 rounded-3xl border-2 border-brand-red shadow-xl">
                <div class="w-12 h-12 rounded-2xl bg-red-100 text-brand-red flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-warehouse"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Regional Sorting & Distribution Hub</h3>
                <p class="text-sm text-gray-600 mb-6">For entrepreneurs seeking a full-scale logistics hub with last-mile rider fleet management and linehaul dispatch.</p>
                <div class="space-y-3 text-xs text-gray-700 mb-8">
                    <div class="flex justify-between border-b pb-2"><span>Investment Required:</span> <strong class="text-brand-red">&#8377; 3,000,000 - &#8377; 5,000,000</strong></div>
                    <div class="flex justify-between border-b pb-2"><span>Space Needed:</span> <strong class="text-brand-navy">500 - 1200 Sq. Ft.</strong></div>
                    <div class="flex justify-between border-b pb-2"><span>Role:</span> <strong>Sorting, Bagging & Rider Fleet Hub</strong></div>
                    <div class="flex justify-between border-b pb-2"><span>Territory:</span> <strong class="text-green-600">Exclusive Pin Code Rights</strong></div>
                </div>
                <a href="#apply" class="block w-full py-3 rounded-full bg-brand-red text-white text-center font-bold text-sm hover:bg-brand-redHover transition shadow-md">
                    Apply for Regional Hub
                </a>
            </div>
        </div>
    </div>
</section>

<!-- 4. Lead Application Form -->
<section id="apply" class="py-12 md:py-16 bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-8 md:p-10 rounded-3xl shadow-xl border border-gray-200">
            <div class="text-center mb-8">
                <h3 class="text-2xl font-bold text-brand-navy">Franchise Application Form</h3>
                <p class="text-xs text-gray-500 font-medium">Fill in your details below and our franchise team will contact you within 24 hours</p>
            </div>

            <form class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Full Name</label>
                        <input type="text" placeholder="Ramesh Kumar" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-brand-navy">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Phone Number</label>
                        <input type="tel" placeholder="9876543210" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-brand-navy">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">City / Town</label>
                        <input type="text" placeholder="Jaipur" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-brand-navy">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pin Code Needed</label>
                        <input type="text" placeholder="302001" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-brand-navy">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Preferred Franchise Model</label>
                    <select class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-brand-navy">
                        <option>Booking Counter Micro-Hub (&#8377;50k - &#8377;1L)</option>
                        <option>Regional Sorting Hub (&#8377;3L - &#8377;5L)</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-brand-red text-white font-extrabold text-sm hover:bg-brand-redHover transition shadow-md mt-2">
                    Submit Franchise Application &rarr;
                </button>
            </form>
        </div>
    </div>
</section>
@endsection