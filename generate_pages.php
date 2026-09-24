<?php

$pages = [
    'public/solutions/b2c.blade.php' => ['title' => 'B2C Shipping', 'desc' => 'Ship directly to customers with multi-courier aggregation and COD support.'],
    'public/solutions/b2b.blade.php' => ['title' => 'B2B & Cargo', 'desc' => 'Heavy freight, FTL, PTL, and multi-box consignments made easy.'],
    'public/solutions/international.blade.php' => ['title' => 'International Shipping', 'desc' => 'Cross-border logistics with customs documentation and duties calculation.'],
    'public/solutions/quick.blade.php' => ['title' => 'Quick Delivery', 'desc' => 'Hyperlocal and same-day express delivery services.'],
    'public/solutions/aggregation.blade.php' => ['title' => 'Courier Aggregation', 'desc' => 'One integration for all major delivery networks.'],
    'public/solutions/ecommerce.blade.php' => ['title' => 'E-commerce Integration', 'desc' => 'Connect Shopify, WooCommerce, Magento and more.'],
    
    'public/platform/evidence.blade.php' => ['title' => 'Video Evidence', 'desc' => 'Immutable video proof for every pickup, hub scan, and delivery.'],
    'public/platform/ndr.blade.php' => ['title' => 'NDR Management', 'desc' => 'Non-delivery reports actioned in real-time to reduce RTOs.'],
    'public/platform/rto.blade.php' => ['title' => 'RTO Management', 'desc' => 'Protect your inventory and streamline return-to-origin flows.'],
    'public/platform/cod.blade.php' => ['title' => 'COD & Settlement', 'desc' => 'Fast remittances and transparent ledgers for cash on delivery.'],
    'public/platform/tracking.blade.php' => ['title' => 'Live GPS Tracking', 'desc' => 'Know where your shipments are at all times.'],
    'public/platform/awb.blade.php' => ['title' => 'AWB & Labels', 'desc' => 'Instantly generate standard shipping labels and manifests.'],
    
    'public/developers/index.blade.php' => ['title' => 'Developer Platform', 'desc' => 'Shipping infrastructure built for developers.'],
    'public/developers/docs.blade.php' => ['title' => 'API Documentation', 'desc' => 'Integrate OneStall Cargo directly into your tech stack.'],
    
    'public/corporate.blade.php' => ['title' => 'Corporate & Enterprise', 'desc' => 'Custom logistics solutions for large-scale operations.'],
    'public/partners.blade.php' => ['title' => 'Our Partners', 'desc' => 'The network that powers OneStall Cargo.'],
    'public/about.blade.php' => ['title' => 'About Us', 'desc' => 'We are building the future of logistics infrastructure.'],
    'public/faq.blade.php' => ['title' => 'Frequently Asked Questions', 'desc' => 'Find answers to common shipping queries.'],
    'public/help.blade.php' => ['title' => 'Help Center', 'desc' => 'Support and guides for using OneStall Cargo.'],
];

foreach ($pages as $file => $data) {
    $path = __DIR__ . "/resources/views/" . $file;
    
    $content = <<<EOD
@extends('layouts.public')
@section('title', "{$data['title']} | OneStall Cargo")

@section('content')
<section class="bg-brand-navy py-20 text-center">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6">{$data['title']}</h1>
        <p class="text-xl text-gray-300">{$data['desc']}</p>
    </div>
</section>

<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Robust {$data['title']} Capabilities</h2>
                <p class="text-lg text-gray-600 mb-6">OneStall Cargo provides enterprise-grade infrastructure to support your logistics needs. Our platform is designed to handle complexity so you don't have to.</p>
                
                <ul class="space-y-4 mb-8">
                    <li class="flex items-start">
                        <i class="fa-solid fa-check text-green-500 mt-1 mr-3"></i>
                        <span class="text-gray-700">Seamless integration with existing workflows</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fa-solid fa-check text-green-500 mt-1 mr-3"></i>
                        <span class="text-gray-700">Real-time visibility and reporting</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fa-solid fa-check text-green-500 mt-1 mr-3"></i>
                        <span class="text-gray-700">Dedicated operational support</span>
                    </li>
                </ul>
                
                <a href="{{ route('register') }}" class="inline-flex px-6 py-3 rounded bg-brand-blue text-white font-bold hover:bg-brand-navy transition">Get Started</a>
            </div>
            
            <div class="bg-gray-100 rounded-2xl aspect-video flex items-center justify-center border border-gray-200 shadow-inner">
                <i class="fa-solid fa-laptop-code text-6xl text-gray-300"></i>
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-gray-50 border-t border-gray-200 text-center">
    <div class="max-w-3xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Need a custom integration?</h2>
        <p class="text-gray-600 mb-8">Speak to our logistics experts to design a workflow that fits your business.</p>
        <a href="{{ route('contact') }}" class="px-8 py-3 rounded border-2 border-brand-blue text-brand-blue font-bold hover:bg-brand-blue hover:text-white transition">Contact Sales</a>
    </div>
</section>
@endsection
EOD;

    // Check if directory exists
    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    
    file_put_contents($path, $content);
    echo "Generated $file\n";
}
