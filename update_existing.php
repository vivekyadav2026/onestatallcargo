<?php

$pages = [
    'pricing.blade.php' => <<<EOD
@extends('layouts.public')
@section('title', 'Pricing | OneStall Cargo')

@section('content')
<section class="bg-brand-navy py-20 text-center text-white">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Transparent Logistics Pricing</h1>
        <p class="text-xl text-gray-300">Pay only for what you ship. Enterprise-grade infrastructure at competitive rates.</p>
    </div>
</section>

<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8 text-center flex flex-col">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">B2C Shipping</h3>
                <p class="text-gray-500 mb-6">For online sellers and D2C brands</p>
                <div class="text-4xl font-extrabold text-brand-blue mb-6">Volume Based</div>
                <ul class="space-y-4 mb-8 text-left text-gray-600 flex-grow">
                    <li><i class="fa-solid fa-check text-green-500 mr-2"></i> Zero setup fees</li>
                    <li><i class="fa-solid fa-check text-green-500 mr-2"></i> Courier aggregation</li>
                    <li><i class="fa-solid fa-check text-green-500 mr-2"></i> Fast COD remittance</li>
                </ul>
                <a href="{{ route('contact') }}" class="w-full py-3 rounded border-2 border-brand-blue text-brand-blue font-bold hover:bg-brand-blue hover:text-white transition">Contact Us</a>
            </div>

            <div class="bg-brand-navy rounded-2xl border border-brand-navy shadow-xl p-8 text-center flex flex-col relative transform md:-translate-y-4">
                <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-brand-yellow text-brand-navy px-4 py-1 rounded-full text-xs font-bold uppercase tracking-wide">Most Popular</div>
                <h3 class="text-2xl font-bold text-white mb-2">B2B & Cargo</h3>
                <p class="text-gray-400 mb-6">For heavy freight and PTL/FTL</p>
                <div class="text-4xl font-extrabold text-brand-yellow mb-6">Custom Quote</div>
                <ul class="space-y-4 mb-8 text-left text-gray-300 flex-grow">
                    <li><i class="fa-solid fa-check text-brand-yellow mr-2"></i> Dedicated account manager</li>
                    <li><i class="fa-solid fa-check text-brand-yellow mr-2"></i> Custom E-Way bill logic</li>
                    <li><i class="fa-solid fa-check text-brand-yellow mr-2"></i> Digital Consignment Notes</li>
                </ul>
                <a href="{{ route('contact') }}" class="w-full py-3 rounded bg-brand-yellow text-brand-navy font-bold hover:bg-brand-yellowHover transition">Talk to Sales</a>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8 text-center flex flex-col">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">International</h3>
                <p class="text-gray-500 mb-6">For cross-border commerce</p>
                <div class="text-4xl font-extrabold text-brand-blue mb-6">Get a Quote</div>
                <ul class="space-y-4 mb-8 text-left text-gray-600 flex-grow">
                    <li><i class="fa-solid fa-check text-green-500 mr-2"></i> 200+ Countries</li>
                    <li><i class="fa-solid fa-check text-green-500 mr-2"></i> Customs documentation</li>
                    <li><i class="fa-solid fa-check text-green-500 mr-2"></i> Express air freight</li>
                </ul>
                <a href="{{ route('contact') }}" class="w-full py-3 rounded border-2 border-brand-blue text-brand-blue font-bold hover:bg-brand-blue hover:text-white transition">Get Quote</a>
            </div>
            
        </div>
    </div>
</section>
@endsection
EOD,
    
    'franchise.blade.php' => <<<EOD
@extends('layouts.public')
@section('title', 'Franchise Partner | OneStall Cargo')

@section('content')
<section class="bg-brand-navy py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6">Build a profitable logistics business.</h1>
            <p class="text-xl text-gray-300 mb-8">Become a OneStall Hub Franchise. Manage local pickups, hub scanning, sorting, and last-mile delivery using our enterprise tech stack.</p>
            <a href="#apply" class="px-8 py-4 rounded bg-brand-yellow text-brand-navy font-bold text-lg hover:bg-brand-yellowHover transition">Apply for Franchise</a>
        </div>
        <div class="bg-white/10 rounded-2xl p-8 border border-white/20">
            <h3 class="text-2xl font-bold text-white mb-6">Hub Capabilities</h3>
            <ul class="space-y-4">
                <li class="flex items-center text-gray-300"><i class="fa-solid fa-barcode text-brand-yellow mr-3"></i> Barcode Scanning & Inwarding</li>
                <li class="flex items-center text-gray-300"><i class="fa-solid fa-boxes-packing text-brand-yellow mr-3"></i> Bag Creation & Dispatch</li>
                <li class="flex items-center text-gray-300"><i class="fa-solid fa-motorcycle text-brand-yellow mr-3"></i> Rider Assignment</li>
                <li class="flex items-center text-gray-300"><i class="fa-solid fa-indian-rupee-sign text-brand-yellow mr-3"></i> Franchise Ledger & Commission</li>
            </ul>
        </div>
    </div>
</section>
@endsection
EOD,
    
    'track.blade.php' => <<<EOD
@extends('layouts.public')
@section('title', 'Track Shipment | OneStall Cargo')

@section('content')
<section class="py-20 bg-gray-50 min-h-[60vh]">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-brand-navy px-8 py-10 text-center">
                <h1 class="text-3xl font-extrabold text-white mb-2">Track Your Shipment</h1>
                <p class="text-gray-300">Enter your AWB or Shipment ID below</p>
            </div>
            
            <div class="p-8">
                <form action="{{ route('track.post') }}" method="POST" class="mb-8">
                    @csrf
                    <div class="flex flex-col sm:flex-row gap-4">
                        <input type="text" name="awb" placeholder="e.g. OSC12345678" class="flex-grow px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-blue focus:border-transparent text-lg font-bold" required>
                        <button type="submit" class="px-8 py-3 rounded-lg bg-brand-blue text-white font-bold text-lg hover:bg-brand-navy transition">Track</button>
                    </div>
                </form>

                @if(isset(\$shipment))
                    <div class="mt-8 border-t border-gray-200 pt-8">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <p class="text-sm text-gray-500 font-bold uppercase">AWB Number</p>
                                <h2 class="text-2xl font-black text-brand-navy">{{ \$shipment->awb_number }}</h2>
                            </div>
                            <span class="px-3 py-1 bg-green-100 text-green-700 font-bold uppercase rounded text-sm">{{ \$shipment->status }}</span>
                        </div>
                        
                        <div class="relative pl-6 border-l-2 border-brand-yellow space-y-8 mt-8">
                            <div class="relative">
                                <div class="absolute -left-[31px] w-4 h-4 bg-brand-yellow rounded-full border-4 border-white"></div>
                                <p class="text-xs font-bold text-gray-400">{{ \$shipment->created_at->format('d M Y, h:i A') }}</p>
                                <p class="text-base font-bold text-gray-900">Shipment Booked</p>
                                <p class="text-sm text-gray-600">Information received.</p>
                            </div>
                        </div>
                    </div>
                @elseif(request()->isMethod('post'))
                    <div class="mt-8 border-t border-gray-200 pt-8 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 text-red-500 mb-4 text-2xl"><i class="fa-solid fa-triangle-exclamation"></i></div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Shipment Not Found</h3>
                        <p class="text-gray-600">We couldn't find a shipment with that AWB. Please check the number and try again.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
EOD,

    'contact.blade.php' => <<<EOD
@extends('layouts.public')
@section('title', 'Contact Us | OneStall Cargo')

@section('content')
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-16">
        <div>
            <h1 class="text-4xl font-extrabold text-brand-navy mb-6">Get in touch</h1>
            <p class="text-lg text-gray-600 mb-8">Whether you have a question about B2B freight, API integrations, or becoming a franchise partner, our team is ready to answer all your questions.</p>
            
            <div class="space-y-6">
                <div class="flex items-start">
                    <div class="w-12 h-12 rounded bg-white border border-gray-200 flex items-center justify-center text-brand-blue text-xl shadow-sm mr-4"><i class="fa-solid fa-envelope"></i></div>
                    <div>
                        <h4 class="font-bold text-gray-900">Email Us</h4>
                        <p class="text-gray-600">support@onestallcargo.com</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="w-12 h-12 rounded bg-white border border-gray-200 flex items-center justify-center text-brand-blue text-xl shadow-sm mr-4"><i class="fa-solid fa-phone"></i></div>
                    <div>
                        <h4 class="font-bold text-gray-900">Call Us</h4>
                        <p class="text-gray-600">1800-123-4567</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="w-12 h-12 rounded bg-white border border-gray-200 flex items-center justify-center text-brand-blue text-xl shadow-sm mr-4"><i class="fa-solid fa-location-dot"></i></div>
                    <div>
                        <h4 class="font-bold text-gray-900">Headquarters</h4>
                        <p class="text-gray-600">123 Logistics Park, Mumbai, India 400001</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
            <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">First Name</label>
                        <input type="text" class="w-full px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-blue">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Last Name</label>
                        <input type="text" class="w-full px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-blue">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                    <input type="email" class="w-full px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-blue">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Inquiry Type</label>
                    <select class="w-full px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-blue">
                        <option>Sales Inquiry</option>
                        <option>Support</option>
                        <option>Franchise Opportunity</option>
                        <option>API & Integration</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Message</label>
                    <textarea rows="4" class="w-full px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-blue"></textarea>
                </div>
                <button type="button" class="w-full py-3 rounded bg-brand-navy text-white font-bold hover:bg-brand-blue transition">Send Message</button>
            </form>
        </div>
    </div>
</section>
@endsection
EOD
];

foreach ($pages as $file => $content) {
    $path = __DIR__ . "/resources/views/" . $file;
    file_put_contents($path, $content);
    echo "Updated $file\n";
}
