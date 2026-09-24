@extends('layouts.public')
@section('title', 'OneStall Cargo | Ship Smarter. Deliver With Confidence.')

@section('content')
<!-- 1. Hero & Tracking -->
<section class="relative bg-brand-navy overflow-hidden">
    <!-- Hero Image Background -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/logistics_hero_banner.jpg') }}" alt="OneStall Cargo Logistics Hub" class="w-full h-full object-cover object-center opacity-40">
        <div class="absolute inset-0 bg-gradient-to-r from-brand-navy via-brand-navy/90 to-transparent"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-16 pb-20 lg:pt-24 lg:pb-24 flex flex-col lg:flex-row items-center">
        <!-- Hero Text -->
        <div class="w-full lg:w-3/5 lg:pr-12 text-center lg:text-left">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight">
                One Platform. <br>
                <span class="text-brand-yellow drop-shadow-md">Every Shipment.</span>
            </h1>
            <p class="mt-6 text-lg text-gray-200 max-w-2xl mx-auto lg:mx-0 font-medium leading-relaxed drop-shadow">
                B2C shipping, B2B cargo, international logistics, and technology-powered delivery through a single unified infrastructure.
            </p>
            
            <div class="mt-8 flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                <a href="{{ route('register') }}" class="px-8 py-3 rounded-lg bg-brand-yellow text-brand-navy font-bold text-lg hover:bg-brand-yellowHover transition shadow-lg text-center">
                    Start Shipping
                </a>
                <a href="{{ route('docs') }}" class="px-8 py-3 rounded-lg bg-white/10 backdrop-blur-sm border-2 border-white/50 text-white font-bold text-lg hover:bg-white/20 transition text-center">
                    View API Docs
                </a>
            </div>

            <!-- Quick Track -->
            <div class="mt-10 bg-white/10 p-2 rounded-xl backdrop-blur-md max-w-md mx-auto lg:mx-0 border border-white/30 shadow-2xl">
                <form action="{{ route('track.post') }}" method="POST" class="flex items-center">
                    @csrf
                    <div class="pl-4 pr-2 text-brand-yellow"><i class="fa-solid fa-cube"></i></div>
                    <input type="text" name="awb" placeholder="Enter AWB / Shipment ID" class="w-full bg-transparent border-none text-white placeholder-gray-300 focus:outline-none focus:ring-0 py-2 font-medium" required>
                    <button type="submit" class="bg-brand-blue text-white font-bold px-6 py-2 rounded-lg hover:bg-blue-800 transition shadow">Track</button>
                </form>
            </div>
        </div>

        <!-- Right Side UI Overlay (Compact) -->
        <div class="w-full lg:w-2/5 mt-12 lg:mt-0 relative hidden md:block">
            <!-- Simulated UI -->
            <div class="bg-white/95 backdrop-blur rounded-xl shadow-2xl overflow-hidden border border-gray-200/50 transform lg:rotate-2 hover:rotate-0 transition duration-500 scale-95 origin-right">
                <div class="bg-gray-100/80 px-4 py-2 border-b border-gray-200 flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                    <div class="w-2.5 h-2.5 rounded-full bg-yellow-400"></div>
                    <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
                    <div class="ml-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Live Status</div>
                </div>
                <div class="p-5">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">AWB Number</p>
                            <h3 class="text-lg font-extrabold text-brand-navy">OSC92847163</h3>
                        </div>
                        <span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold uppercase rounded">In Transit</span>
                    </div>

                    <!-- Timeline Visual -->
                    <div class="relative pl-5 border-l-2 border-gray-200 space-y-4">
                        <div class="relative">
                            <div class="absolute -left-[27px] w-3 h-3 bg-brand-navy rounded-full border-2 border-white"></div>
                            <p class="text-[10px] font-bold text-gray-400">09:42 AM</p>
                            <p class="text-xs font-bold text-gray-800">Picked Up</p>
                        </div>
                        <div class="relative">
                            <div class="absolute -left-[27px] w-3 h-3 bg-brand-navy rounded-full border-2 border-white"></div>
                            <p class="text-[10px] font-bold text-gray-400">02:15 PM</p>
                            <p class="text-xs font-bold text-gray-800">Origin Hub Scan</p>
                        </div>
                        <div class="relative">
                            <div class="absolute -left-[27px] w-3 h-3 bg-brand-yellow rounded-full border-2 border-white animate-pulse"></div>
                            <p class="text-[10px] font-bold text-brand-yellow">Now</p>
                            <p class="text-xs font-bold text-gray-800">In Transit</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Capability Strip -->
<div class="bg-gray-100 border-b border-gray-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-center md:justify-between items-center gap-6 text-sm font-bold text-gray-500 uppercase tracking-widest">
            <span>B2C Shipping</span>
            <span class="hidden md:inline text-gray-300">•</span>
            <span>B2B & Cargo</span>
            <span class="hidden md:inline text-gray-300">•</span>
            <span>International</span>
            <span class="hidden md:inline text-gray-300">•</span>
            <span>Quick Delivery</span>
            <span class="hidden md:inline text-gray-300">•</span>
            <span>Courier Aggregation</span>
            <span class="hidden md:inline text-gray-300">•</span>
            <span>API Infrastructure</span>
        </div>
    </div>
</div>

<!-- 3. One Platform -->
<section class="py-12 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center mb-8">
        <h2 class="text-3xl md:text-5xl font-extrabold text-brand-navy tracking-tight">One Platform for Every Shipment</h2>
        <p class="mt-4 text-xl text-gray-500 max-w-3xl mx-auto">Whether you are shipping a 500g parcel to a customer or a 500kg pallet to a warehouse, our infrastructure handles it.</p>
    </div>

    <div class="space-y-12">
        <!-- B2C Shipping -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center gap-6">
            <div class="w-full md:w-1/2">
                <h3 class="text-sm font-bold text-brand-yellow uppercase tracking-widest mb-2">01 — Solutions</h3>
                <h4 class="text-3xl font-bold text-gray-900 mb-4">B2C Shipping for Sellers</h4>
                <p class="text-gray-600 mb-6 text-lg">Manage your D2C brand with prepaid and Cash on Delivery (COD) capabilities. Instantly generate AWBs, print labels, and schedule pickups across multiple courier partners.</p>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center text-gray-700 font-medium"><i class="fa-solid fa-check text-green-500 mr-3"></i> Smart courier allocation</li>
                    <li class="flex items-center text-gray-700 font-medium"><i class="fa-solid fa-check text-green-500 mr-3"></i> Automated NDR & RTO workflows</li>
                    <li class="flex items-center text-gray-700 font-medium"><i class="fa-solid fa-check text-green-500 mr-3"></i> Fast COD settlements</li>
                </ul>
                <a href="{{ route('solutions.b2c') }}" class="inline-flex items-center text-brand-blue font-bold hover:text-brand-navy transition group">
                    Explore B2C Shipping <i class="fa-solid fa-arrow-right ml-2 transform group-hover:translate-x-1 transition"></i>
                </a>
            </div>
            <div class="w-full md:w-1/2">
                <!-- Seller Dashboard Mockup -->
                <div class="bg-gray-50 rounded-2xl border border-gray-200 shadow-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <span class="font-bold text-gray-800">Orders Overview</span>
                        <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded shadow-sm border border-gray-100">Today</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                            <p class="text-xs text-gray-500 font-bold uppercase mb-1">Pickup Pending</p>
                            <p class="text-2xl font-black text-brand-navy">124</p>
                        </div>
                        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                            <p class="text-xs text-gray-500 font-bold uppercase mb-1">In Transit</p>
                            <p class="text-2xl font-black text-brand-navy">892</p>
                        </div>
                        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                            <p class="text-xs text-gray-500 font-bold uppercase mb-1">Delivered</p>
                            <p class="text-2xl font-black text-green-600">4,021</p>
                        </div>
                        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                            <p class="text-xs text-gray-500 font-bold uppercase mb-1">Pending COD</p>
                            <p class="text-2xl font-black text-brand-yellow">₹45.2K</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- B2B Cargo (Reversed) -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row-reverse items-center gap-6">
            <div class="w-full md:w-1/2">
                <h3 class="text-sm font-bold text-brand-yellow uppercase tracking-widest mb-2">02 — Solutions</h3>
                <h4 class="text-3xl font-bold text-gray-900 mb-4">B2B & Heavy Cargo</h4>
                <p class="text-gray-600 mb-6 text-lg">Built for enterprise supply chains. Ship multiple boxes, calculate freight based on volume/weight, and manage PTL/FTL requirements seamlessly.</p>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center text-gray-700 font-medium"><i class="fa-solid fa-check text-green-500 mr-3"></i> Digital LR / Consignment Notes</li>
                    <li class="flex items-center text-gray-700 font-medium"><i class="fa-solid fa-check text-green-500 mr-3"></i> Hub-to-Hub transfers</li>
                    <li class="flex items-center text-gray-700 font-medium"><i class="fa-solid fa-check text-green-500 mr-3"></i> Proof of Delivery (POD) management</li>
                </ul>
                <a href="{{ route('solutions.b2b') }}" class="inline-flex items-center text-brand-blue font-bold hover:text-brand-navy transition group">
                    Explore B2B & Cargo <i class="fa-solid fa-arrow-right ml-2 transform group-hover:translate-x-1 transition"></i>
                </a>
            </div>
            <div class="w-full md:w-1/2">
                <!-- Cargo Visual -->
                <div class="bg-brand-navy rounded-2xl border border-gray-800 shadow-2xl p-6 relative overflow-hidden">
                    <div class="absolute right-0 top-0 opacity-10">
                        <i class="fa-solid fa-truck-moving text-[200px] text-white"></i>
                    </div>
                    <div class="relative z-10">
                        <div class="flex justify-between items-center mb-6 border-b border-gray-700 pb-4">
                            <span class="font-bold text-white text-lg">Consignment #LR-88219</span>
                            <span class="px-2 py-1 bg-gray-800 text-gray-300 text-xs rounded border border-gray-600">PTL Freight</span>
                        </div>
                        <div class="space-y-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Total Weight:</span>
                                <span class="text-white font-bold">1,450 kg</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Packages:</span>
                                <span class="text-white font-bold">42 Pallets</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Vehicle Type:</span>
                                <span class="text-white font-bold">32 FT Multi-Axle</span>
                            </div>
                        </div>
                        <button class="mt-6 w-full py-3 bg-gray-800 hover:bg-gray-700 text-white font-semibold rounded text-sm transition">
                            Download LR Copy
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- International Shipping -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center gap-6">
            <div class="w-full md:w-1/2">
                <h3 class="text-sm font-bold text-brand-yellow uppercase tracking-widest mb-2">03 — Solutions</h3>
                <h4 class="text-3xl font-bold text-gray-900 mb-4">International Shipping</h4>
                <p class="text-gray-600 mb-6 text-lg">Take your business global. Automate commercial invoices, manage customs documentation, and get transparent cross-border rates.</p>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center text-gray-700 font-medium"><i class="fa-solid fa-check text-green-500 mr-3"></i> Pre-calculated Duties & Taxes</li>
                    <li class="flex items-center text-gray-700 font-medium"><i class="fa-solid fa-check text-green-500 mr-3"></i> Commercial Invoice Generation</li>
                    <li class="flex items-center text-gray-700 font-medium"><i class="fa-solid fa-check text-green-500 mr-3"></i> Global Tracking Network</li>
                </ul>
                <a href="{{ route('solutions.international') }}" class="inline-flex items-center text-brand-blue font-bold hover:text-brand-navy transition group">
                    Explore International Shipping <i class="fa-solid fa-arrow-right ml-2 transform group-hover:translate-x-1 transition"></i>
                </a>
            </div>
            <div class="w-full md:w-1/2">
                <div class="bg-white rounded-2xl border border-gray-200 shadow-xl overflow-hidden aspect-video relative flex items-center justify-center">
                    <!-- Stylized Map Visual -->
                    <div class="absolute inset-0 opacity-20 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjx0ZXh0IHk9IjUwJSIgeD0iNTAlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmaWxsPSIjMWEzNjVkIiBmb250LXNpemU9IjUwMCIgZm9udC1mYW1pbHk9ImZvbnRhd2Vzb21lIj7ufDBhYzwvdGV4dD48L3N2Zz4=')] bg-center bg-no-repeat bg-contain"></div>
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="flex items-center gap-6">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-brand-navy rounded-full flex items-center justify-center text-white text-xl shadow-lg border-4 border-white mb-2"><i class="fa-solid fa-location-dot"></i></div>
                                <p class="font-bold text-gray-800">India</p>
                            </div>
                            <div class="w-24 border-t-2 border-dashed border-gray-400 relative">
                                <i class="fa-solid fa-plane absolute top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2 text-brand-yellow text-xl"></i>
                            </div>
                            <div class="text-center">
                                <div class="w-16 h-16 bg-brand-blue rounded-full flex items-center justify-center text-white text-xl shadow-lg border-4 border-white mb-2"><i class="fa-solid fa-earth-americas"></i></div>
                                <p class="font-bold text-gray-800">Global</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. Shipment Journey -->
<section class="py-12 bg-gray-50 border-t border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl md:text-5xl font-extrabold text-brand-navy tracking-tight">From Booking to Doorstep</h2>
            <p class="mt-4 text-xl text-gray-500 max-w-3xl mx-auto">Complete visibility across the entire lifecycle of your shipment.</p>
        </div>

        <div class="overflow-x-auto pb-8 scrollbar-hide">
            <div class="flex items-center min-w-[1000px] justify-between relative px-4">
                <!-- Connecting Line -->
                <div class="absolute top-6 left-12 right-12 h-1 bg-gray-300 z-0"></div>
                
                <!-- Steps -->
                @php
                    $steps = [
                        ['icon' => 'fa-laptop-code', 'title' => 'Booking & API'],
                        ['icon' => 'fa-barcode', 'title' => 'AWB & Label'],
                        ['icon' => 'fa-boxes-packing', 'title' => 'Pickup & Proof'],
                        ['icon' => 'fa-warehouse', 'title' => 'Origin Hub'],
                        ['icon' => 'fa-truck-fast', 'title' => 'Transit'],
                        ['icon' => 'fa-motorcycle', 'title' => 'Out for Delivery'],
                        ['icon' => 'fa-handshake', 'title' => 'POD / COD']
                    ];
                @endphp

                @foreach($steps as $index => $step)
                    <div class="relative z-10 flex flex-col items-center w-32">
                        <div class="w-12 h-12 rounded-full {{ $index == 2 || $index == 6 ? 'bg-brand-yellow text-brand-navy border-4 border-white shadow-md' : 'bg-white text-gray-500 border-2 border-gray-300 hover:border-brand-blue hover:text-brand-blue' }} flex items-center justify-center text-xl transition duration-300 cursor-default">
                            <i class="fa-solid {{ $step['icon'] }}"></i>
                        </div>
                        <p class="mt-4 text-sm font-bold text-gray-700 text-center leading-tight">{{ $step['title'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- 5. Courier Aggregation -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-5xl font-extrabold text-brand-navy tracking-tight mb-6">One Integration. Multiple Networks.</h2>
        <p class="text-xl text-gray-500 max-w-2xl mx-auto mb-8">Connect once to the OneStall API and instantly route your shipments across leading delivery networks based on price, speed, and serviceability.</p>
        
        <div class="flex flex-col md:flex-row items-center justify-center gap-8">
            <div class="w-full md:w-1/3 bg-gray-50 p-8 rounded-2xl border border-gray-200">
                <i class="fa-solid fa-server text-4xl text-brand-navy mb-4"></i>
                <h4 class="text-xl font-bold text-gray-900 mb-2">OneStall Platform</h4>
                <p class="text-gray-500 text-sm">Centralized Rate Engine, Booking & Tracking</p>
            </div>
            
            <div class="text-gray-300 hidden md:block">
                <i class="fa-solid fa-arrow-right-arrow-left text-3xl"></i>
            </div>
            
            <div class="w-full md:w-2/3 grid grid-cols-2 sm:grid-cols-3 gap-4">
                <!-- Mock Courier Blocks -->
                <div class="bg-white p-4 border border-gray-200 rounded-xl shadow-sm text-center font-bold text-gray-600">Carrier A</div>
                <div class="bg-white p-4 border border-gray-200 rounded-xl shadow-sm text-center font-bold text-gray-600">Carrier B</div>
                <div class="bg-white p-4 border border-gray-200 rounded-xl shadow-sm text-center font-bold text-gray-600">Carrier C</div>
                <div class="bg-white p-4 border border-gray-200 rounded-xl shadow-sm text-center font-bold text-gray-600">Local Express</div>
                <div class="bg-white p-4 border border-gray-200 rounded-xl shadow-sm text-center font-bold text-gray-600">B2B Freight</div>
                <div class="bg-white p-4 border border-brand-yellow bg-yellow-50 rounded-xl shadow-sm text-center font-bold text-brand-navy">OneStall Direct</div>
            </div>
        </div>
    </div>
</section>

<!-- 6. Developer & API Section -->
<section class="py-12 bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row items-center gap-8">
        <div class="w-full lg:w-1/2">
            <h2 class="text-3xl md:text-5xl font-extrabold tracking-tight mb-6">Shipping Infrastructure, <br><span class="text-brand-yellow">Built for Developers</span></h2>
            <p class="text-lg text-gray-400 mb-8">Modern REST APIs for booking, rating, tracking, and webhooks. Integrate logistics directly into your tech stack in hours, not weeks.</p>
            
            <div class="grid grid-cols-2 gap-4 mb-8">
                <div class="flex items-center text-gray-300"><i class="fa-solid fa-check text-green-400 mr-2"></i> Pincode Serviceability</div>
                <div class="flex items-center text-gray-300"><i class="fa-solid fa-check text-green-400 mr-2"></i> Rate Calculation</div>
                <div class="flex items-center text-gray-300"><i class="fa-solid fa-check text-green-400 mr-2"></i> AWB & Labels</div>
                <div class="flex items-center text-gray-300"><i class="fa-solid fa-check text-green-400 mr-2"></i> Real-time Webhooks</div>
            </div>
            
            <div class="flex gap-4">
                <a href="{{ route('docs') }}" class="px-6 py-3 rounded bg-white text-gray-900 font-bold hover:bg-gray-100 transition">Read Documentation</a>
            </div>
        </div>
        
        <div class="w-full lg:w-1/2">
            <div class="bg-[#1e1e1e] rounded-xl shadow-2xl border border-gray-700 overflow-hidden text-sm font-mono">
                <div class="bg-[#2d2d2d] px-4 py-2 border-b border-gray-700 flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    <span class="ml-2 text-gray-400 text-xs">POST /v1/shipments/book</span>
                </div>
                <div class="p-6 text-gray-300">
<pre><span class="text-pink-400">curl</span> -X POST https://api.onestallcargo.com/v1/shipments/book \
  -H <span class="text-green-300">"Authorization: Bearer osc_live_xxxx"</span> \
  -H <span class="text-green-300">"Content-Type: application/json"</span> \
  -d '{
    <span class="text-blue-300">"shipment_type"</span>: <span class="text-yellow-300">"B2C"</span>,
    <span class="text-blue-300">"pickup_pincode"</span>: <span class="text-yellow-300">"400001"</span>,
    <span class="text-blue-300">"delivery_pincode"</span>: <span class="text-yellow-300">"110001"</span>,
    <span class="text-blue-300">"weight_kg"</span>: <span class="text-orange-300">1.5</span>,
    <span class="text-blue-300">"is_cod"</span>: <span class="text-orange-300">true</span>,
    <span class="text-blue-300">"invoice_value"</span>: <span class="text-orange-300">1499.00</span>
  }'</pre>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 7. Video Evidence (Differentiator) -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center mb-8">
        <h2 class="text-3xl md:text-5xl font-extrabold text-brand-navy tracking-tight mb-4">Every Shipment. <span class="text-brand-yellow">Documented.</span></h2>
        <p class="text-xl text-gray-500 max-w-3xl mx-auto">Protect your business from fake claims and disputes with our immutable Video Recording & Evidence System at every critical node.</p>
    </div>

    <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
            <div class="h-40 bg-gray-200 rounded-xl mb-6 relative overflow-hidden flex items-center justify-center">
                <i class="fa-solid fa-play text-4xl text-gray-400"></i>
                <div class="absolute bottom-2 left-2 bg-black/60 text-white text-[10px] px-2 py-1 rounded">09:42 AM - REC</div>
            </div>
            <h4 class="text-lg font-bold text-gray-900 mb-2">1. Pickup Recording</h4>
            <p class="text-sm text-gray-600">Riders record parcel condition and sealing upon pickup from your facility.</p>
        </div>
        
        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
            <div class="h-40 bg-gray-200 rounded-xl mb-6 relative overflow-hidden flex items-center justify-center">
                <i class="fa-solid fa-play text-4xl text-gray-400"></i>
                <div class="absolute bottom-2 left-2 bg-black/60 text-white text-[10px] px-2 py-1 rounded">01:15 PM - REC</div>
            </div>
            <h4 class="text-lg font-bold text-gray-900 mb-2">2. Hub Scanning</h4>
            <p class="text-sm text-gray-600">Automated video capture during hub sorting, weighing, and bagging processes.</p>
        </div>

        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
            <div class="h-40 bg-gray-200 rounded-xl mb-6 relative overflow-hidden flex items-center justify-center">
                <i class="fa-solid fa-play text-4xl text-gray-400"></i>
                <div class="absolute bottom-2 left-2 bg-black/60 text-white text-[10px] px-2 py-1 rounded">05:32 PM - REC</div>
            </div>
            <h4 class="text-lg font-bold text-gray-900 mb-2">3. Delivery Proof</h4>
            <p class="text-sm text-gray-600">Visual proof of handover, OTP confirmation, and GPS location stamping.</p>
        </div>
    </div>
    <div class="text-center mt-12">
        <a href="{{ route('platform.evidence') }}" class="font-bold text-brand-blue hover:underline">Explore Video Evidence & Protection &rarr;</a>
    </div>
</section>

<!-- 8. Final CTA -->
<section class="bg-brand-blue py-10 relative overflow-hidden">
    <!-- Abstract graphic -->
    <div class="absolute right-0 top-0 h-full w-1/2 bg-brand-navy opacity-50 transform skew-x-12 translate-x-32"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h2 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight mb-6">Ready to move your shipping forward?</h2>
        <p class="text-xl text-gray-300 max-w-2xl mx-auto mb-10">Bring shipping, tracking, operations and delivery visibility into one platform.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('register') }}" class="px-8 py-4 rounded bg-brand-yellow text-brand-navy font-bold text-lg hover:bg-brand-yellowHover transition shadow-lg">
                Start Shipping Today
            </a>
            <a href="{{ route('contact') }}" class="px-8 py-4 rounded bg-white text-gray-900 font-bold text-lg hover:bg-gray-100 transition shadow-lg">
                Talk to Sales
            </a>
        </div>
    </div>
</section>

@endsection
