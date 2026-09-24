@extends('layouts.public')
@section('title', "E-commerce Integration | OneStall Cargo")

@section('content')
<section class="bg-brand-navy py-10 text-center">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6">E-commerce Integration</h1>
        <p class="text-xl text-gray-300">Connect Shopify, WooCommerce, Magento and more.</p>
    </div>
</section>

<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Robust E-commerce Integration Capabilities</h2>
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

<section class="py-10 bg-gray-50 border-t border-gray-200 text-center">
    <div class="max-w-3xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Need a custom integration?</h2>
        <p class="text-gray-600 mb-8">Speak to our logistics experts to design a workflow that fits your business.</p>
        <a href="{{ route('contact') }}" class="px-8 py-3 rounded border-2 border-brand-blue text-brand-blue font-bold hover:bg-brand-blue hover:text-white transition">Contact Sales</a>
    </div>
</section>
@endsection