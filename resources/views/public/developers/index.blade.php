@extends('layouts.public')
@section('title', "Developer Platform & Shipping APIs | OneStall Cargo")

@section('content')
<!-- 1. Hero Section -->
<section class="bg-gradient-to-r from-blue-50/40 via-white to-blue-50/40 py-12 md:py-16 relative overflow-hidden" x-data="{ activeTab: 'curl' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <!-- Left Text Content -->
            <div class="w-full lg:w-1/2 text-center lg:text-left">
                <div class="inline-block px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-brand-navy text-xs font-bold mb-4 uppercase tracking-wide">
                    Developer Platform & Infrastructure
                </div>
                <h1 class="text-3xl md:text-5xl font-extrabold text-brand-navy leading-tight mb-4">
                    Logistics Infrastructure <span class="text-brand-red">Built for Developers</span>
                </h1>
                <p class="text-base md:text-lg text-gray-700 mb-8 font-medium leading-relaxed">
                    Integrate nationwide shipping, real-time rate comparison, AWB generation, and webhook tracking into your application using clean, RESTful APIs.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('docs') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md shadow-red-500/20">
                        Explore API Docs
                    </a>
                    <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full border-2 border-brand-navy text-brand-navy font-bold text-base hover:bg-brand-navy hover:text-white transition">
                        Get Sandbox API Keys
                    </a>
                </div>
                <div class="mt-6 flex items-center justify-center lg:justify-start gap-6 text-xs text-gray-500 font-semibold">
                    <span><i class="fa-solid fa-code text-green-500 mr-1"></i> RESTful JSON APIs</span>
                    <span><i class="fa-solid fa-bolt text-green-500 mr-1"></i> &lt;100ms Latency</span>
                </div>
            </div>

            <!-- Right Terminal Window Mockup -->
            <div class="w-full lg:w-1/2">
                <div class="bg-gray-900 rounded-2xl shadow-2xl overflow-hidden border border-gray-800 font-mono text-xs">
                    <!-- Terminal Header -->
                    <div class="bg-gray-800 px-4 py-3 flex items-center justify-between border-b border-gray-700">
                        <div class="flex items-center space-x-2">
                            <span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span>
                            <span class="w-3 h-3 rounded-full bg-yellow-500 inline-block"></span>
                            <span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span>
                            <span class="text-gray-400 font-bold ml-2">POST /api/v1/shipments/create</span>
                        </div>
                        <!-- Language Tabs -->
                        <div class="flex space-x-2 text-[10px]">
                            <button @click="activeTab = 'curl'" :class="activeTab === 'curl' ? 'bg-brand-red text-white' : 'text-gray-400 hover:text-white'" class="px-2 py-0.5 rounded font-bold transition">cURL</button>
                            <button @click="activeTab = 'node'" :class="activeTab === 'node' ? 'bg-brand-red text-white' : 'text-gray-400 hover:text-white'" class="px-2 py-0.5 rounded font-bold transition">Node.js</button>
                            <button @click="activeTab = 'php'" :class="activeTab === 'php' ? 'bg-brand-red text-white' : 'text-gray-400 hover:text-white'" class="px-2 py-0.5 rounded font-bold transition">PHP</button>
                            <button @click="activeTab = 'python'" :class="activeTab === 'python' ? 'bg-brand-red text-white' : 'text-gray-400 hover:text-white'" class="px-2 py-0.5 rounded font-bold transition">Python</button>
                        </div>
                    </div>

                    <!-- Code Snippets -->
                    <div class="p-5 overflow-x-auto text-gray-300 leading-relaxed min-h-[260px]">
                        <!-- cURL -->
                        <template x-if="activeTab === 'curl'">
<pre class="text-green-400">curl -X POST https://api.onestallcargo.com/v1/shipments/create \
  -H <span class="text-yellow-300">"Authorization: Bearer YOUR_API_KEY"</span> \
  -H <span class="text-yellow-300">"Content-Type: application/json"</span> \
  -d <span class="text-blue-300">'{
    "pickup_pincode": "110001",
    "delivery_pincode": "400001",
    "weight_kg": 1.5,
    "payment_type": "COD",
    "cod_amount": 1499,
    "consignee": {
      "name": "Rahul Sharma",
      "phone": "9876543210"
    }
  }'</span></pre>
                        </template>

                        <!-- Node.js -->
                        <template x-if="activeTab === 'node'">
<pre class="text-blue-300"><span class="text-purple-400">const</span> axios = <span class="text-purple-400">require</span>(<span class="text-yellow-300">'axios'</span>);

<span class="text-purple-400">const</span> response = <span class="text-purple-400">await</span> axios.post(<span class="text-yellow-300">'https://api.onestallcargo.com/v1/shipments/create'</span>, {
  pickup_pincode: <span class="text-yellow-300">'110001'</span>,
  delivery_pincode: <span class="text-yellow-300">'400001'</span>,
  weight_kg: <span class="text-green-400">1.5</span>,
  payment_type: <span class="text-yellow-300">'COD'</span>,
  cod_amount: <span class="text-green-400">1499</span>
}, {
  headers: { <span class="text-yellow-300">'Authorization'</span>: <span class="text-yellow-300">`Bearer ${process.env.ONESTALL_API_KEY}`</span> }
});</pre>
                        </template>

                        <!-- PHP -->
                        <template x-if="activeTab === 'php'">
<pre class="text-purple-300"><span class="text-purple-400">$response</span> = Http::withToken(<span class="text-yellow-300">env('ONESTALL_API_KEY')</span>)
  ->post(<span class="text-yellow-300">'https://api.onestallcargo.com/v1/shipments/create'</span>, [
      <span class="text-yellow-300">'pickup_pincode'</span> => <span class="text-yellow-300">'110001'</span>,
      <span class="text-yellow-300">'delivery_pincode'</span> => <span class="text-yellow-300">'400001'</span>,
      <span class="text-yellow-300">'weight_kg'</span> => <span class="text-green-400">1.5</span>,
      <span class="text-yellow-300">'payment_type'</span> => <span class="text-yellow-300">'COD'</span>,
      <span class="text-yellow-300">'cod_amount'</span> => <span class="text-green-400">1499</span>
  ]);</pre>
                        </template>

                        <!-- Python -->
                        <template x-if="activeTab === 'python'">
<pre class="text-yellow-200"><span class="text-purple-400">import</span> requests

headers = {<span class="text-yellow-300">"Authorization"</span>: <span class="text-yellow-300">"Bearer YOUR_API_KEY"</span>}
payload = {
    <span class="text-yellow-300">"pickup_pincode"</span>: <span class="text-yellow-300">"110001"</span>,
    <span class="text-yellow-300">"delivery_pincode"</span>: <span class="text-yellow-300">"400001"</span>,
    <span class="text-yellow-300">"weight_kg"</span>: <span class="text-green-400">1.5</span>,
    <span class="text-yellow-300">"payment_type"</span>: <span class="text-yellow-300">"COD"</span>
}
res = requests.post(<span class="text-yellow-300">"https://api.onestallcargo.com/v1/shipments/create"</span>, json=payload, headers=headers)</pre>
                        </template>
                    </div>

                    <!-- Terminal Footer Response -->
                    <div class="bg-gray-950 px-5 py-3 border-t border-gray-800 text-green-400 font-semibold flex justify-between items-center">
                        <span><span class="text-gray-500">Status:</span> 200 OK</span>
                        <span><span class="text-gray-500">Response Time:</span> 84ms</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Metrics Bar -->
<section class="bg-brand-navy py-6 text-white border-y border-brand-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">99.99%</div>
                <div class="text-xs md:text-sm text-gray-300">API Uptime SLA</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">&lt; 100ms</div>
                <div class="text-xs md:text-sm text-gray-300">Average Latency</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-brand-red">10M+</div>
                <div class="text-xs md:text-sm text-gray-300">Daily API Calls</div>
            </div>
            <div>
                <div class="text-2xl md:text-3xl font-black text-white">Postman</div>
                <div class="text-xs md:text-sm text-gray-300">Collection Available</div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Key Capabilities Grid -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl md:text-4xl font-extrabold text-brand-navy mb-3">
                Developer-First Logistics Features
            </h2>
            <p class="text-base text-gray-600">
                Architected for reliability, scalability, and ease of integration into any software stack.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-brand-navy flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-code text-brand-navy"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">RESTful Shipping APIs</h3>
                <p class="text-sm text-gray-600">Calculate rates, generate AWB tracking numbers, and request pickups using clean JSON endpoints.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-red-100 text-brand-red flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-bell text-brand-red"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Real-Time Webhook Engine</h3>
                <p class="text-sm text-gray-600">Subscribe to instant HTTP webhooks for shipment tracking status, NDR alerts, and COD settlement events.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-vial text-green-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Sandbox & Test Keys</h3>
                <p class="text-sm text-gray-600">Test your entire order lifecycle in a isolated sandbox environment with simulated carrier responses.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-layer-group text-purple-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">SDKs & Client Libraries</h3>
                <p class="text-sm text-gray-600">Pre-built official SDKs for Node.js, Python, PHP (Laravel), and Go available on NPM and Packagist.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-key text-orange-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Granular API Token Scopes</h3>
                <p class="text-sm text-gray-600">Create restricted API keys (Read-Only, Write-Only, Webhook-Only) with IP whitelisting controls.</p>
            </div>

            <div class="bg-gray-50/70 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-book-bookmark text-teal-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">OpenAPI & Postman Specs</h3>
                <p class="text-sm text-gray-600">Download OpenAPI v3 schemas and Postman collection files to test endpoints in 30 seconds.</p>
            </div>
        </div>
    </div>
</section>

<!-- 4. CTA Card -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-brand-navy via-brand-blue to-brand-navy rounded-3xl p-8 md:p-12 text-white text-center shadow-xl">
            <h2 class="text-2xl md:text-4xl font-extrabold mb-4">
                Build Powerful Logistics Into Your App
            </h2>
            <p class="text-sm md:text-base text-gray-200 max-w-2xl mx-auto mb-8 font-medium">
                Get your free API key, test in our sandbox environment, and go live with nationwide shipping in hours.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('docs') }}" class="px-8 py-3.5 rounded-full bg-brand-red text-white font-bold text-base hover:bg-brand-redHover transition shadow-md">
                    Explore API Reference
                </a>
                <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-full border border-white/40 text-white font-bold text-base hover:bg-white/10 transition">
                    Get Free API Key
                </a>
            </div>
        </div>
    </div>
</section>
@endsection