@extends('layouts.public')
@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="text-3xl font-extrabold text-brand-navy mb-8 text-center">Contact Us</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                <form action="{{ route('contact.post') }}" method="POST" class="space-y-6">
                    @csrf
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Full Name</label>
                        <input type="text" name="name" required class="w-full px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-blue">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" required class="w-full px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-blue">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" class="w-full px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-blue">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Company / Volume</label>
                        <input type="text" name="company_name" placeholder="Company Name" class="w-full px-4 py-2 mb-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-blue">
                        <select name="volume" class="w-full px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-blue">
                            <option value="">Select Monthly Volume</option>
                            <option value="1-100">1 - 100 Shipments</option>
                            <option value="101-500">101 - 500 Shipments</option>
                            <option value="500+">500+ Shipments</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Message</label>
                        <textarea name="message" required rows="4" class="w-full px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-blue"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-brand-navy hover:bg-black text-white font-bold py-3 rounded-xl transition">Submit Inquiry</button>
                </form>
            </div>
            
            <div class="flex flex-col justify-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Our Office</h2>
                <p class="text-gray-600 mb-6">{{ setting('site_address', '123 Logistics Park, Mumbai, India 400001') }}</p>
                <p class="text-gray-600 mb-2"><i class="fa-solid fa-envelope mr-2 text-brand-blue"></i> {{ setting('site_email', 'support@onestallcargo.com') }}</p>
                <p class="text-gray-600 mb-8"><i class="fa-solid fa-phone mr-2 text-brand-blue"></i> {{ setting('site_phone', '1800-123-4567') }}</p>
                <div class="h-64 bg-gray-200 rounded-2xl flex items-center justify-center">
                    <span class="text-gray-400 font-bold">Map Embed</span>
                </div>
            </div>
        </div>
    </div>
@endsection
