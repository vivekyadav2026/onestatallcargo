@extends('layouts.public')
@section('title', 'Pricing | OneStall Cargo')

@section('content')
<section class="bg-brand-navy py-10 text-center text-white">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Transparent Logistics Pricing</h1>
        <p class="text-xl text-gray-300">Pay only for what you ship. Enterprise-grade infrastructure at competitive rates.</p>
    </div>
</section>

<section class="py-12 bg-white">
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