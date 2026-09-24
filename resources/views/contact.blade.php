@extends('layouts.public')
@section('title', 'Contact Us | OneStall Cargo')

@section('content')
<section class="py-10 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
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