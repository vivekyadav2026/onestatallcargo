@extends('layouts.app')
@section('title', 'API Documentation - OneStall Cargo')

@section('content')
<div class="bg-gray-900 pt-16 pb-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-extrabold text-white tracking-tight sm:text-5xl">Developer API Docs</h1>
        <p class="mt-4 text-xl text-gray-400 max-w-2xl mx-auto">Automate your shipping operations. Integrate our RESTful API directly into your ERP, Shopify, or WooCommerce store.</p>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 pb-16">
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden flex flex-col md:flex-row border border-gray-200">
        
        <!-- Sidebar -->
        <div class="w-full md:w-64 bg-gray-50 border-r border-gray-200 p-6">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Getting Started</h3>
            <ul class="space-y-3 text-sm font-bold text-gray-700">
                <li><a href="#" class="text-[#D4AF37]">Authentication</a></li>
                <li><a href="#" class="hover:text-[#D4AF37]">Errors & Rate Limits</a></li>
            </ul>
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-8 mb-4">Endpoints</h3>
            <ul class="space-y-3 text-sm font-bold text-gray-700">
                <li><a href="#" class="hover:text-[#D4AF37]">Create Shipment</a></li>
                <li><a href="#" class="hover:text-[#D4AF37]">Track AWB</a></li>
                <li><a href="#" class="hover:text-[#D4AF37]">Calculate Rate</a></li>
                <li><a href="#" class="hover:text-[#D4AF37]">Cancel Shipment</a></li>
            </ul>
        </div>

        <!-- Content -->
        <div class="flex-1 p-8 lg:p-12">
            <h2 class="text-2xl font-extrabold text-gray-900 mb-4">Authentication</h2>
            <p class="text-gray-600 mb-6">OneStall Cargo uses Bearer token authentication via Laravel Sanctum. You can generate your API key from the Developer section of your Seller Dashboard.</p>
            
            <div class="bg-[#1e293b] rounded-xl p-4 overflow-x-auto text-sm font-mono text-gray-300 shadow-inner mb-8">
                <span class="text-pink-400">Authorization:</span> Bearer <span class="text-green-400">1|your_super_secret_token_here...</span>
            </div>

            <h2 class="text-2xl font-extrabold text-gray-900 mb-4 border-t pt-8">Create a Shipment</h2>
            <p class="text-gray-600 mb-4">Endpoint: <span class="bg-gray-100 text-gray-800 font-mono px-2 py-1 rounded text-sm">POST /api/shipments/create</span></p>
            
            <div class="bg-[#1e293b] rounded-xl p-4 overflow-x-auto text-sm font-mono text-gray-300 shadow-inner">
<pre class="m-0"><span class="text-blue-400">curl</span> -X POST https://api.onestallcargo.com/api/shipments/create \
  -H <span class="text-yellow-300">"Authorization: Bearer YOUR_TOKEN"</span> \
  -H <span class="text-yellow-300">"Content-Type: application/json"</span> \
  -d <span class="text-yellow-300">'{
    "receiver_name": "John Doe",
    "delivery_phone": "9876543210",
    "delivery_city": "Mumbai",
    "delivery_pincode": "400001",
    "weight_kg": 1.5,
    "is_cod": true,
    "invoice_value": 1500
  }'</span></pre>
            </div>
            
            <div class="mt-8 p-4 bg-green-50 border border-green-200 rounded-xl">
                <h4 class="font-bold text-green-800 mb-2">Success Response (200 OK)</h4>
                <pre class="text-xs font-mono text-green-700 m-0">{
  "status": "success",
  "awb_number": "OSC987654321",
  "tracking_url": "https://onestallcargo.com/track?awb=OSC987654321"
}</pre>
            </div>
        </div>
    </div>
</div>
@endsection
