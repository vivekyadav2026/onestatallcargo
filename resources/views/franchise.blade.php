@extends('layouts.app')
@section('title', 'Become a Franchise - OneStall Cargo')

@section('content')
<div class="relative bg-[#1e293b] py-24 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h1 class="text-4xl font-extrabold text-white tracking-tight sm:text-6xl mb-6">Open a Delivery Hub</h1>
        <p class="mt-4 text-xl text-gray-300 max-w-3xl mx-auto mb-10">Join India's fastest-growing logistics network. Become a OneStall Cargo Franchise and turn your space into a high-yield delivery hub.</p>
        <a href="{{ route('contact') }}" class="px-8 py-4 bg-[#FFD700] text-gray-900 font-extrabold rounded-xl shadow-lg hover:bg-[#E5C100] transition text-lg">Apply for Franchise</a>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="text-center mb-16">
        <h2 class="text-3xl font-extrabold text-gray-900">Why Partner With Us?</h2>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
        <div class="text-center">
            <div class="w-20 h-20 mx-auto bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-3xl mb-6">
                <i class="fa-solid fa-indian-rupee-sign"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3">Earn Per Scan</h3>
            <p class="text-gray-500">You earn a fixed commission for every parcel you scan in (Receive) and every parcel your riders deliver (Out for Delivery).</p>
        </div>
        <div class="text-center">
            <div class="w-20 h-20 mx-auto bg-green-100 rounded-full flex items-center justify-center text-green-600 text-3xl mb-6">
                <i class="fa-solid fa-laptop-code"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3">Enterprise Software</h3>
            <p class="text-gray-500">Get free access to our Hub Dashboard. Manage master bags, manifest generation, and rider assignments with zero technical hassle.</p>
        </div>
        <div class="text-center">
            <div class="w-20 h-20 mx-auto bg-purple-100 rounded-full flex items-center justify-center text-purple-600 text-3xl mb-6">
                <i class="fa-solid fa-motorcycle"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3">Rider Mobile App</h3>
            <p class="text-gray-500">Your local delivery fleet gets our proprietary mobile app for live route tracking, video evidence capture, and instant COD settlements.</p>
        </div>
    </div>
</div>

<div class="bg-gray-50 py-20 border-t border-gray-200">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold text-gray-900 mb-6">Basic Requirements</h2>
        <ul class="text-left bg-white p-8 rounded-3xl border border-gray-200 shadow-sm space-y-4">
            <li class="flex items-start"><i class="fa-solid fa-check-circle text-green-500 mt-1 mr-3 text-lg"></i> <span class="text-gray-700">Minimum 200 sq.ft commercial space on the ground floor.</span></li>
            <li class="flex items-start"><i class="fa-solid fa-check-circle text-green-500 mt-1 mr-3 text-lg"></i> <span class="text-gray-700">Computer, stable internet, and a barcode scanner.</span></li>
            <li class="flex items-start"><i class="fa-solid fa-check-circle text-green-500 mt-1 mr-3 text-lg"></i> <span class="text-gray-700">2-3 delivery riders with smartphones and 2-wheelers.</span></li>
            <li class="flex items-start"><i class="fa-solid fa-check-circle text-green-500 mt-1 mr-3 text-lg"></i> <span class="text-gray-700">Refundable security deposit (varies by Tier 1/2/3 city).</span></li>
        </ul>
    </div>
</div>
@endsection
