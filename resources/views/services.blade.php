@extends('layouts.app')

@section('title', 'Our Services - OneStall Cargo')

@section('content')
<div class="bg-gradient-to-r from-[#0f172a] to-[#1e293b] py-20">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6">Logistics for Every Need</h1>
        <p class="text-lg text-gray-300 max-w-2xl mx-auto">From Hyperlocal 2-hour delivery to heavy B2B truck loads, OneStall Cargo provides the exact fleet and network required for your business.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-20 space-y-20">

    <!-- B2C -->
    <div class="flex flex-col md:flex-row items-center gap-12">
        <div class="flex-1 space-y-6">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 text-2xl">
                <i class="fa-solid fa-shopping-bag"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900">B2C & E-Commerce Module</h2>
            <p class="text-gray-600 text-lg">A complete shipping solution for online sellers. Seamlessly integrate with Shopify, WooCommerce, and custom platforms.</p>
            <ul class="space-y-3">
                <li class="flex items-center gap-3 text-gray-700 font-medium"><i class="fa-solid fa-check text-green-500"></i> Cash on Delivery & Prepaid shipments</li>
                <li class="flex items-center gap-3 text-gray-700 font-medium"><i class="fa-solid fa-check text-green-500"></i> Multi-courier support with smart routing</li>
                <li class="flex items-center gap-3 text-gray-700 font-medium"><i class="fa-solid fa-check text-green-500"></i> NDR & RTO management dashboard</li>
                <li class="flex items-center gap-3 text-gray-700 font-medium"><i class="fa-solid fa-check text-green-500"></i> Automated COD wallet settlements</li>
            </ul>
        </div>
        <div class="flex-1 bg-gray-100 rounded-3xl h-64 md:h-96 w-full flex items-center justify-center border-4 border-white shadow-xl relative overflow-hidden">
            <div class="absolute inset-0 bg-blue-600 opacity-10"></div>
            <i class="fa-solid fa-box-open text-9xl text-blue-200"></i>
        </div>
    </div>

    <hr>

    <!-- B2B -->
    <div class="flex flex-col md:flex-row-reverse items-center gap-12">
        <div class="flex-1 space-y-6">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-orange-50 text-orange-600 text-2xl">
                <i class="fa-solid fa-truck-moving"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900">B2B & Heavy Cargo</h2>
            <p class="text-gray-600 text-lg">For heavy and bulky shipments requiring specialized transit networks.</p>
            <ul class="space-y-3">
                <li class="flex items-center gap-3 text-gray-700 font-medium"><i class="fa-solid fa-check text-green-500"></i> PTL (Part Truck Load) and FTL (Full Truck Load)</li>
                <li class="flex items-center gap-3 text-gray-700 font-medium"><i class="fa-solid fa-check text-green-500"></i> Multiple boxes / multiple pieces handling</li>
                <li class="flex items-center gap-3 text-gray-700 font-medium"><i class="fa-solid fa-check text-green-500"></i> Lorry Receipts (LR) & Consignment Notes</li>
                <li class="flex items-center gap-3 text-gray-700 font-medium"><i class="fa-solid fa-check text-green-500"></i> Freight calculation & loading/unloading details</li>
            </ul>
        </div>
        <div class="flex-1 bg-gray-100 rounded-3xl h-64 md:h-96 w-full flex items-center justify-center border-4 border-white shadow-xl relative overflow-hidden">
            <div class="absolute inset-0 bg-orange-600 opacity-10"></div>
            <i class="fa-solid fa-pallet text-9xl text-orange-200"></i>
        </div>
    </div>

    <hr>

    <!-- Quick Delivery -->
    <div class="flex flex-col md:flex-row items-center gap-12">
        <div class="flex-1 space-y-6">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-purple-50 text-purple-600 text-2xl">
                <i class="fa-solid fa-motorcycle"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900">Quick / Hyperlocal Delivery</h2>
            <p class="text-gray-600 text-lg">For fast, local delivery using our dedicated rider app and live GPS network.</p>
            <ul class="space-y-3">
                <li class="flex items-center gap-3 text-gray-700 font-medium"><i class="fa-solid fa-check text-green-500"></i> Same Day & Next Day deliveries</li>
                <li class="flex items-center gap-3 text-gray-700 font-medium"><i class="fa-solid fa-check text-green-500"></i> Automatic assignment of a nearby rider</li>
                <li class="flex items-center gap-3 text-gray-700 font-medium"><i class="fa-solid fa-check text-green-500"></i> Live GPS location & ETA sharing</li>
                <li class="flex items-center gap-3 text-gray-700 font-medium"><i class="fa-solid fa-check text-green-500"></i> Video evidence at doorstep</li>
            </ul>
        </div>
        <div class="flex-1 bg-gray-100 rounded-3xl h-64 md:h-96 w-full flex items-center justify-center border-4 border-white shadow-xl relative overflow-hidden">
            <div class="absolute inset-0 bg-purple-600 opacity-10"></div>
            <i class="fa-solid fa-map-location-dot text-9xl text-purple-200"></i>
        </div>
    </div>

</div>
@endsection
