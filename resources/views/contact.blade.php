@extends('layouts.app')
@section('title', 'Contact Support - OneStall Cargo')

@section('content')
<div class="bg-gray-50 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Contact Our Team</h1>
            <p class="mt-4 text-lg text-gray-500">We're here to help with your logistics needs.</p>
        </div>

        <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden flex flex-col md:flex-row">
            
            <div class="w-full md:w-2/5 bg-[#1e293b] p-10 text-white">
                <h3 class="text-2xl font-bold mb-6">Contact Info</h3>
                <p class="text-gray-400 mb-8 text-sm">Fill out the form and our sales or support team will get back to you within 24 hours.</p>
                
                <div class="space-y-6">
                    <div class="flex items-center">
                        <i class="fa-solid fa-phone text-[#FFD700] text-xl w-8"></i>
                        <span>1800-123-4567</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fa-solid fa-envelope text-[#FFD700] text-xl w-8"></i>
                        <span>support@onestallcargo.com</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fa-solid fa-location-dot text-[#FFD700] text-xl w-8 mt-1"></i>
                        <span>123 Logistics Park,<br>Andheri East, Mumbai<br>India 400001</span>
                    </div>
                </div>
            </div>

            <div class="w-full md:w-3/5 p-10">
                <form action="#" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                        <input type="text" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-transparent" placeholder="John Doe">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                        <input type="email" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-transparent" placeholder="john@example.com">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Message</label>
                        <textarea rows="4" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-transparent" placeholder="How can we help you?"></textarea>
                    </div>
                    <button type="button" class="w-full bg-[#FFD700] text-gray-900 font-extrabold py-3 rounded-xl hover:bg-[#E5C100] transition shadow-md">
                        Send Message
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
