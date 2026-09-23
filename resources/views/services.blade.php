@extends('layouts.app')
@section('title', 'Services - OneStall Cargo')

@section('content')
<div class="bg-[#1e293b] py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-extrabold text-white tracking-tight sm:text-5xl">Our Logistics Services</h1>
        <p class="mt-4 text-xl text-gray-300 max-w-2xl mx-auto">From a 500-gram envelope to a 5-ton truckload, we have the network to deliver it.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
        <!-- B2C -->
        <div class="bg-white p-8 rounded-3xl border border-gray-200 shadow-sm flex flex-col items-start">
            <div class="w-16 h-16 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-3xl mb-6"><i class="fa-solid fa-box"></i></div>
            <h2 class="text-2xl font-extrabold text-gray-900 mb-3">E-commerce B2C Delivery</h2>
            <p class="text-gray-600 mb-6 flex-1">Tailored for online sellers. Get cheap rates, COD support, next-day delivery options, and automated NDR management. Perfect for D2C brands.</p>
            <ul class="space-y-2 text-sm font-bold text-gray-700 w-full mb-6">
                <li><i class="fa-solid fa-check text-green-500 mr-2"></i> Courier Aggregation (Delhivery, etc.)</li>
                <li><i class="fa-solid fa-check text-green-500 mr-2"></i> Early COD Remittance</li>
                <li><i class="fa-solid fa-check text-green-500 mr-2"></i> Video Evidence for RTOs</li>
            </ul>
        </div>

        <!-- B2B -->
        <div class="bg-white p-8 rounded-3xl border border-gray-200 shadow-sm flex flex-col items-start">
            <div class="w-16 h-16 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center text-3xl mb-6"><i class="fa-solid fa-truck-moving"></i></div>
            <h2 class="text-2xl font-extrabold text-gray-900 mb-3">B2B Heavy Freight (Cargo)</h2>
            <p class="text-gray-600 mb-6 flex-1">Shipping pallets or machinery? Use our Part Truck Load (PTL) or Full Truck Load (FTL) services. We handle the heavy lifting for factories and wholesalers.</p>
            <ul class="space-y-2 text-sm font-bold text-gray-700 w-full mb-6">
                <li><i class="fa-solid fa-check text-green-500 mr-2"></i> Instant Consignment Note (LR) Generation</li>
                <li><i class="fa-solid fa-check text-green-500 mr-2"></i> E-Way Bill Support</li>
                <li><i class="fa-solid fa-check text-green-500 mr-2"></i> Advanced Fleet Tracking</li>
            </ul>
        </div>

        <!-- International -->
        <div class="bg-white p-8 rounded-3xl border border-gray-200 shadow-sm flex flex-col items-start">
            <div class="w-16 h-16 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center text-3xl mb-6"><i class="fa-solid fa-plane"></i></div>
            <h2 class="text-2xl font-extrabold text-gray-900 mb-3">Cross-Border International</h2>
            <p class="text-gray-600 mb-6 flex-1">Expand your business globally. Ship to over 200+ countries with full tracking visibility and automated customs documentation.</p>
            <ul class="space-y-2 text-sm font-bold text-gray-700 w-full mb-6">
                <li><i class="fa-solid fa-check text-green-500 mr-2"></i> Automated Commercial Invoices</li>
                <li><i class="fa-solid fa-check text-green-500 mr-2"></i> Dynamic HS Code mapping</li>
                <li><i class="fa-solid fa-check text-green-500 mr-2"></i> DDP & DDU Options</li>
            </ul>
        </div>

        <!-- Hubs -->
        <div class="bg-white p-8 rounded-3xl border border-gray-200 shadow-sm flex flex-col items-start">
            <div class="w-16 h-16 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center text-3xl mb-6"><i class="fa-solid fa-building"></i></div>
            <h2 class="text-2xl font-extrabold text-gray-900 mb-3">Franchise & Hub Network</h2>
            <p class="text-gray-600 mb-6 flex-1">Join our network! Open a OneStall Cargo Franchise in your city, manage local pickups and deliveries, and earn per scan.</p>
            <ul class="space-y-2 text-sm font-bold text-gray-700 w-full mb-6">
                <li><i class="fa-solid fa-check text-green-500 mr-2"></i> Access to Hub Bagging Software</li>
                <li><i class="fa-solid fa-check text-green-500 mr-2"></i> Mobile App for your Riders</li>
                <li><i class="fa-solid fa-check text-green-500 mr-2"></i> Dedicated Account Manager</li>
            </ul>
        </div>
    </div>
</div>
@endsection
