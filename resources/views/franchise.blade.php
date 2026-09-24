@extends('layouts.public')
@section('title', 'Franchise Partner | OneStall Cargo')

@section('content')
<section class="bg-brand-navy py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-6 items-center">
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