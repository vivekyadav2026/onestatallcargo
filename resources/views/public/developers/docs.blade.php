@extends('layouts.public')
@section('title', "Interactive API Documentation | OneStall Cargo")

@section('content')
<!-- Header Banner -->
<section class="bg-brand-navy py-10 text-white border-b border-brand-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center md:text-left flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold mb-2">API Reference & Documentation</h1>
            <p class="text-sm md:text-base text-gray-300 font-medium">RESTful API endpoints for rate calculation, shipment booking, tracking, and webhooks.</p>
        </div>
        <div class="flex gap-3">
            <span class="px-3 py-1 bg-green-500/20 text-green-400 border border-green-500/30 text-xs font-mono font-bold rounded-full">Base URL: https://api.onestallcargo.com/v1</span>
        </div>
    </div>
</section>

<!-- Interactive API Documentation Workspace -->
<section class="py-10 bg-gray-50/50 min-h-[70vh]" x-data="{
    activeEndpoint: 'create_order',
    endpoints: {
        'create_order': {
            title: 'Create Order & Generate AWB',
            method: 'POST',
            path: '/v1/shipments/create',
            desc: 'Create a new shipment booking and generate an AWB tracking number across partner courier networks.',
            curl: `curl -X POST https://api.onestallcargo.com/v1/shipments/create \\
  -H 'Authorization: Bearer YOUR_API_KEY' \\
  -H 'Content-Type: application/json' \\
  -d '{
    \"pickup_pincode\": \"110001\",
    \"delivery_pincode\": \"400001\",
    \"weight_kg\": 1.5,
    \"payment_type\": \"COD\",
    \"cod_amount\": 1499,
    \"consignee\": {
      \"name\": \"Rahul Sharma\",
      \"phone\": \"9876543210\",
      \"address\": \"Flat 402, Sunshine Heights, Andheri West\",
      \"city\": \"Mumbai\",
      \"state\": \"Maharashtra\"
    }
  }'`,
            response: `{
  \"status\": \"success\",
  \"code\": 200,
  \"message\": \"Shipment created successfully\",
  \"data\": {
    \"shipment_id\": \"OSC-9845321\",
    \"awb_number\": \"DELHI19845321\",
    \"courier_name\": \"Delhivery Surface\",
    \"freight_charges\": 85.00,
    \"cod_charges\": 30.00,
    \"est_delivery_date\": \"2026-09-28\",
    \"label_url\": \"https://api.onestallcargo.com/v1/labels/DELHI19845321.pdf\"
  }
}`
        },
        'rate_calculator': {
            title: 'Calculate Shipping Rates',
            method: 'POST',
            path: '/v1/rates',
            desc: 'Fetch real-time freight rate quotes across all active courier partners for a given pickup and delivery pin code.',
            curl: `curl -X POST https://api.onestallcargo.com/v1/rates \\
  -H 'Authorization: Bearer YOUR_API_KEY' \\
  -H 'Content-Type: application/json' \\
  -d '{
    \"pickup_pincode\": \"110001\",
    \"delivery_pincode\": \"560001\",
    \"weight_kg\": 2.0,
    \"payment_type\": \"Prepaid\"
  }'`,
            response: `{
  \"status\": \"success\",
  \"code\": 200,
  \"data\": {
    \"available_couriers\": [
      { \"courier\": \"XpressBees Surface\", \"rate\": 78.00, \"etd_days\": 3, \"rating\": 4.8 },
      { \"courier\": \"Delhivery Express\", \"rate\": 92.50, \"etd_days\": 2, \"rating\": 4.9 },
      { \"courier\": \"Shadowfax Air\", \"rate\": 110.00, \"etd_days\": 1, \"rating\": 4.7 }
    ]
  }
}`
        },
        'track_shipment': {
            title: 'Track Shipment Status',
            method: 'GET',
            path: '/v1/shipment/track/{awb_number}',
            desc: 'Fetch real-time milestone events and tracking updates for any AWB number.',
            curl: `curl -X GET https://api.onestallcargo.com/v1/shipment/track/DELHI19845321 \\
  -H 'Authorization: Bearer YOUR_API_KEY'`,
            response: `{
  \"status\": \"success\",
  \"code\": 200,
  \"data\": {
    \"awb_number\": \"DELHI19845321\",
    \"current_status\": \"Out for Delivery\",
    \"scans\": [
      { \"location\": \"Mumbai Hub\", \"status\": \"Out for Delivery\", \"timestamp\": \"2026-09-25 09:30:00\" },
      { \"location\": \"Mumbai Airport Hub\", \"status\": \"In Transit\", \"timestamp\": \"2026-09-24 22:15:00\" },
      { \"location\": \"Delhi Facility\", \"status\": \"Dispatched\", \"timestamp\": \"2026-09-24 14:00:00\" }
    ]
  }
}`
        },
        'ndr_action': {
            title: 'Submit NDR Re-Attempt Action',
            method: 'POST',
            path: '/v1/ndr/reattempt',
            desc: 'Submit updated buyer address or scheduled re-attempt date for an undelivered shipment.',
            curl: `curl -X POST https://api.onestallcargo.com/v1/ndr/reattempt \\
  -H 'Authorization: Bearer YOUR_API_KEY' \\
  -H 'Content-Type: application/json' \\
  -d '{
    \"awb_number\": \"DELHI19845321\",
    \"action\": \"REATTEMPT\",
    \"preferred_date\": \"2026-09-26\",
    \"updated_address\": \"Plot 12, Sector 5, Near Metro Pillar 140\"
  }'`,
            response: `{
  \"status\": \"success\",
  \"code\": 200,
  \"message\": \"Re-attempt instruction submitted to courier partner successfully\"
}`
        },
        'webhooks': {
            title: 'Subscribe to Webhooks',
            method: 'POST',
            path: '/v1/webhooks/subscribe',
            desc: 'Subscribe your endpoint URL to receive HTTP webhooks for shipment events.',
            curl: `curl -X POST https://api.onestallcargo.com/v1/webhooks/subscribe \\
  -H 'Authorization: Bearer YOUR_API_KEY' \\
  -H 'Content-Type: application/json' \\
  -d '{
    \"target_url\": \"https://yourstore.com/api/onestall-webhook\",
    \"events\": [\"shipment.dispatched\", \"shipment.delivered\", \"shipment.ndr\", \"shipment.rto\"]
  }'`,
            response: `{
  \"status\": \"success\",
  \"code\": 200,
  \"data\": {
    \"subscription_id\": \"WH-88412\",
    \"secret_token\": \"whsec_x89a12b34c56\"
  }
}`
        }
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Left Sidebar Navigation -->
            <div class="lg:col-span-1 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm space-y-6">
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Authentication</h3>
                    <div class="text-xs text-gray-600 space-y-2 bg-gray-50 p-3 rounded-lg border border-gray-100">
                        <p class="font-bold text-gray-800">Bearer Token</p>
                        <p>Pass API Key in Authorization header:</p>
                        <code class="text-[10px] bg-white p-1 rounded border border-gray-200 block text-brand-navy">Authorization: Bearer &lt;KEY&gt;</code>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">API Endpoints</h3>
                    <nav class="space-y-1 text-sm font-medium">
                        <button @click="activeEndpoint = 'create_order'" :class="activeEndpoint === 'create_order' ? 'bg-blue-50 text-brand-navy font-bold border-l-4 border-brand-red' : 'text-gray-600 hover:bg-gray-50'" class="w-full text-left px-3 py-2.5 rounded-r flex items-center justify-between transition">
                            <span>Create Order</span>
                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-blue-100 text-blue-700">POST</span>
                        </button>

                        <button @click="activeEndpoint = 'rate_calculator'" :class="activeEndpoint === 'rate_calculator' ? 'bg-blue-50 text-brand-navy font-bold border-l-4 border-brand-red' : 'text-gray-600 hover:bg-gray-50'" class="w-full text-left px-3 py-2.5 rounded-r flex items-center justify-between transition">
                            <span>Calculate Rates</span>
                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-blue-100 text-blue-700">POST</span>
                        </button>

                        <button @click="activeEndpoint = 'track_shipment'" :class="activeEndpoint === 'track_shipment' ? 'bg-blue-50 text-brand-navy font-bold border-l-4 border-brand-red' : 'text-gray-600 hover:bg-gray-50'" class="w-full text-left px-3 py-2.5 rounded-r flex items-center justify-between transition">
                            <span>Track Shipment</span>
                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-green-100 text-green-700">GET</span>
                        </button>

                        <button @click="activeEndpoint = 'ndr_action'" :class="activeEndpoint === 'ndr_action' ? 'bg-blue-50 text-brand-navy font-bold border-l-4 border-brand-red' : 'text-gray-600 hover:bg-gray-50'" class="w-full text-left px-3 py-2.5 rounded-r flex items-center justify-between transition">
                            <span>NDR Action</span>
                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-blue-100 text-blue-700">POST</span>
                        </button>

                        <button @click="activeEndpoint = 'webhooks'" :class="activeEndpoint === 'webhooks' ? 'bg-blue-50 text-brand-navy font-bold border-l-4 border-brand-red' : 'text-gray-600 hover:bg-gray-50'" class="w-full text-left px-3 py-2.5 rounded-r flex items-center justify-between transition">
                            <span>Webhooks</span>
                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-blue-100 text-blue-700">POST</span>
                        </button>
                    </nav>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">SDKs & Postman</h3>
                    <a href="#" class="block text-xs font-bold text-brand-red hover:underline mb-1"><i class="fa-solid fa-download mr-1"></i> Postman Collection</a>
                    <a href="#" class="block text-xs font-bold text-brand-navy hover:underline"><i class="fa-brands fa-github mr-1"></i> OpenAPI v3 Spec</a>
                </div>
            </div>

            <!-- Right Dynamic Endpoint Inspector -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Endpoint Header Card -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-3">
                    <div class="flex items-center gap-3">
                        <span :class="endpoints[activeEndpoint].method === 'POST' ? 'bg-blue-600 text-white' : 'bg-green-600 text-white'" class="px-3 py-1 rounded-lg text-xs font-extrabold tracking-wide" x-text="endpoints[activeEndpoint].method"></span>
                        <code class="text-base font-bold font-mono text-gray-900" x-text="endpoints[activeEndpoint].path"></code>
                    </div>
                    <h2 class="text-xl font-bold text-brand-navy" x-text="endpoints[activeEndpoint].title"></h2>
                    <p class="text-sm text-gray-600 font-medium" x-text="endpoints[activeEndpoint].desc"></p>
                </div>

                <!-- Code Request & Response Terminal Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- cURL Request Code Block -->
                    <div class="bg-gray-900 rounded-2xl shadow-xl overflow-hidden border border-gray-800">
                        <div class="bg-gray-800 px-4 py-2.5 flex justify-between items-center text-xs text-gray-400 font-mono">
                            <span>HTTP Request (cURL)</span>
                            <span class="text-blue-400 font-bold">JSON Payload</span>
                        </div>
                        <div class="p-4 font-mono text-xs text-green-400 overflow-x-auto min-h-[280px]">
                            <pre x-text="endpoints[activeEndpoint].curl"></pre>
                        </div>
                    </div>

                    <!-- JSON Response Code Block -->
                    <div class="bg-gray-900 rounded-2xl shadow-xl overflow-hidden border border-gray-800">
                        <div class="bg-gray-800 px-4 py-2.5 flex justify-between items-center text-xs text-gray-400 font-mono">
                            <span>JSON Response</span>
                            <span class="text-green-400 font-bold">200 OK</span>
                        </div>
                        <div class="p-4 font-mono text-xs text-blue-300 overflow-x-auto min-h-[280px]">
                            <pre x-text="endpoints[activeEndpoint].response"></pre>
                        </div>
                    </div>
                </div>

                <!-- Response Status Codes Table -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                    <h3 class="text-base font-bold text-brand-navy">HTTP Status Codes</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left text-gray-600">
                            <thead class="bg-gray-50 text-gray-700 font-bold uppercase text-[10px]">
                                <tr>
                                    <th class="py-2 px-3">Code</th>
                                    <th class="py-2 px-3">Status</th>
                                    <th class="py-2 px-3">Description</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 font-medium">
                                <tr>
                                    <td class="py-2 px-3 font-mono font-bold text-green-600">200</td>
                                    <td class="py-2 px-3 font-bold">OK</td>
                                    <td class="py-2 px-3">Request processed successfully.</td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-3 font-mono font-bold text-red-600">400</td>
                                    <td class="py-2 px-3 font-bold">Bad Request</td>
                                    <td class="py-2 px-3">Missing required fields or invalid payload parameters.</td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-3 font-mono font-bold text-red-600">401</td>
                                    <td class="py-2 px-3 font-bold">Unauthorized</td>
                                    <td class="py-2 px-3">Invalid or expired Bearer API Token.</td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-3 font-mono font-bold text-orange-600">429</td>
                                    <td class="py-2 px-3 font-bold">Rate Limit Exceeded</td>
                                    <td class="py-2 px-3">Exceeded 100 requests per minute quota.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection