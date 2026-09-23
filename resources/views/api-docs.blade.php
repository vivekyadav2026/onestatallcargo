@extends('layouts.app')

@section('title', 'Developer API - OneStall Cargo')

@section('content')
<div class="bg-gray-50 py-12 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">OneStall REST API</h1>
        <p class="text-gray-600">Integrate End-to-End Logistics into your ERP, Shopify, or Custom App.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex flex-col md:flex-row gap-12">
    
    <!-- Sidebar -->
    <div class="w-full md:w-64 flex-shrink-0 space-y-2 border-r pr-4">
        <div class="text-xs font-bold uppercase text-gray-400 tracking-widest mb-4">Getting Started</div>
        <a href="#" class="block py-2 text-sm font-bold text-[var(--gold-deep)]">Authentication</a>
        <a href="#" class="block py-2 text-sm text-gray-600 hover:text-gray-900">Environments</a>
        <a href="#" class="block py-2 text-sm text-gray-600 hover:text-gray-900">Webhooks</a>

        <div class="text-xs font-bold uppercase text-gray-400 tracking-widest mt-8 mb-4">Endpoints</div>
        <a href="#" class="block py-2 text-sm text-gray-600 hover:text-gray-900">1. Serviceability Check</a>
        <a href="#" class="block py-2 text-sm text-gray-600 hover:text-gray-900">2. Rate Calculator</a>
        <a href="#" class="block py-2 text-sm text-gray-600 hover:text-gray-900">3. Create Shipment</a>
        <a href="#" class="block py-2 text-sm text-gray-600 hover:text-gray-900">4. Fetch AWB / Label</a>
        <a href="#" class="block py-2 text-sm text-gray-600 hover:text-gray-900">5. Tracking Events</a>
        <a href="#" class="block py-2 text-sm text-gray-600 hover:text-gray-900">6. Video Evidence API</a>
    </div>

    <!-- Main Content -->
    <div class="flex-grow space-y-12">
        
        <section>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Authentication</h2>
            <p class="text-sm text-gray-600 mb-4">OneStall uses Bearer Token authentication. Pass your secret token in the Authorization header of every request.</p>
            <div class="bg-gray-900 rounded-xl p-4 font-mono text-sm text-gray-300">
                <span class="text-blue-400">Authorization:</span> Bearer <span class="text-green-400">osc_live_xxxxxxxxxxxxxxxxx</span>
            </div>
        </section>

        <hr>

        <section>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Create Shipment</h2>
            <p class="text-sm text-gray-600 mb-4">Book a new parcel and instantly generate an AWB number and routing label.</p>
            
            <div class="flex items-center gap-3 mb-4">
                <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-bold uppercase">POST</span>
                <span class="font-mono text-sm font-bold text-gray-700">https://api.onestallcargo.com/v1/shipments</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Request -->
                <div>
                    <div class="text-xs font-bold text-gray-500 uppercase mb-2">Request Body (JSON)</div>
                    <div class="bg-gray-900 rounded-xl p-4 font-mono text-xs text-green-400 h-64 overflow-y-auto">
{
  "pickup_pincode": "110001",
  "delivery_pincode": "400001",
  "weight": 1.5,
  "length": 10,
  "width": 10,
  "height": 10,
  "payment_mode": "COD",
  "cod_amount": 1499.00,
  "shipment_type": "B2C"
}
                    </div>
                </div>
                
                <!-- Response -->
                <div>
                    <div class="text-xs font-bold text-gray-500 uppercase mb-2">Success Response (200 OK)</div>
                    <div class="bg-gray-900 rounded-xl p-4 font-mono text-xs text-blue-400 h-64 overflow-y-auto">
{
  "success": true,
  "data": {
    "shipment_id": 98452,
    "awb_number": "OSC10004561",
    "status": "BOOKED",
    "label_url": "https://api.onestallcargo.com/labels/OSC10004561.pdf",
    "routing_code": "BOM-HUB-1"
  }
}
                    </div>
                </div>
            </div>
        </section>

    </div>
</div>
@endsection
