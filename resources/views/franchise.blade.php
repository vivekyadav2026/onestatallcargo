@extends('layouts.app')

@section('title', 'Become a Franchise Partner - OneStall Cargo')

@section('content')
<div class="bg-gradient-to-r from-gray-900 to-[#1e293b] py-20 text-center">
    <div class="max-w-4xl mx-auto px-4">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/20 text-yellow-400 text-sm font-bold uppercase tracking-widest mb-6">
            <i class="fa-solid fa-handshake"></i> Partner Network
        </div>
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6">Join the Fastest Growing Logistics Network</h1>
        <p class="text-lg text-gray-300">Open a OneStall Cargo Franchise or Hub in your city and become part of our Pan-India delivery ecosystem.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-16 grid grid-cols-1 md:grid-cols-2 gap-16">
    <!-- Benefits -->
    <div class="space-y-10">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Why Partner with Us?</h2>
            <p class="text-gray-600">As a Hub Manager, you get full access to our Franchise Dashboard, automated ledgers, and real-time scanning tools.</p>
        </div>
        
        <div class="space-y-6">
            <div class="flex gap-4">
                <div class="w-12 h-12 shrink-0 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-chart-line"></i></div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">High ROI & Commissions</h3>
                    <p class="text-sm text-gray-600 mt-1">Earn competitive margins on every inbound/outbound scan, pickup, and delivery executed at your hub.</p>
                </div>
            </div>
            
            <div class="flex gap-4">
                <div class="w-12 h-12 shrink-0 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-mobile-screen"></i></div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Dedicated Software Suite</h3>
                    <p class="text-sm text-gray-600 mt-1">Get the Hub Operations Panel and the Android Rider App to manage your fleet efficiently.</p>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="w-12 h-12 shrink-0 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-shield-halved"></i></div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Video Evidence Tech</h3>
                    <p class="text-sm text-gray-600 mt-1">Our flagship video recording feature ensures you are protected from false damage claims and NDR disputes.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 relative">
        <div class="absolute -top-6 -right-6 w-24 h-24 bg-yellow-100 rounded-full mix-blend-multiply filter blur-xl opacity-70"></div>
        <h3 class="text-2xl font-extrabold text-gray-900 mb-6">Apply for Franchise</h3>
        
        <form class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">First Name</label>
                    <input type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Last Name</label>
                    <input type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Mobile Number</label>
                <input type="tel" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">City</label>
                    <input type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Pincode</label>
                    <input type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[var(--gold)]">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Current Business/Premises Details</label>
                <textarea rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[var(--gold)]"></textarea>
            </div>

            <button type="button" class="w-full py-4 rounded-xl font-extrabold bg-[var(--gold)] text-gray-900 shadow-md hover:bg-[var(--gold-deep)] transition-colors mt-4">
                Submit Application
            </button>
        </form>
    </div>
</div>
@endsection
