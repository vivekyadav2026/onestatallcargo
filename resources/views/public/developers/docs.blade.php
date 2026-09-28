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
<section class="py-10 bg-gray-50/50 min-h-[70vh]" x-data="apiDocsApp()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Left Sidebar Navigation -->
            <div class="lg:col-span-1 space-y-6">
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Shipments API</h3>
                    <nav class="space-y-1">
                        <template x-for="endpoint in endpointsArray" :key="endpoint.key">
                            <button @click="activeEndpoint = endpoint.key" :class="activeEndpoint === endpoint.key ? 'bg-blue-50 text-brand-navy font-bold border-l-4 border-brand-red' : 'text-gray-600 hover:bg-gray-50'" class="w-full text-left px-3 py-2.5 rounded-r flex items-center justify-between transition">
                                <span x-text="endpoint.title"></span>
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded" 
                                      :class="endpoint.method === 'GET' ? 'bg-green-100 text-green-700' : (endpoint.method === 'POST' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700')" 
                                      x-text="endpoint.method"></span>
                            </button>
                        </template>
                    </nav>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">SDKs & Postman</h3>
                    <a href="{{ route('docs.postman') }}" class="block text-xs font-bold text-brand-red hover:underline mb-1"><i class="fa-solid fa-download mr-1"></i> Postman Collection</a>
                    <a href="{{ route('docs.openapi') }}" class="block text-xs font-bold text-brand-navy hover:underline"><i class="fa-brands fa-github mr-1"></i> OpenAPI v3 Spec</a>
                </div>
            </div>

            <!-- Right Dynamic Endpoint Inspector -->
            <div class="lg:col-span-3 space-y-6" x-show="activeEndpointData">
                <!-- Endpoint Header Card -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-3">
                    <div class="flex items-center gap-3">
                        <span :class="activeEndpointData.method === 'POST' ? 'bg-blue-600 text-white' : (activeEndpointData.method === 'GET' ? 'bg-green-600 text-white' : 'bg-orange-600 text-white')" class="px-3 py-1 rounded-lg text-xs font-extrabold tracking-wide" x-text="activeEndpointData.method"></span>
                        <code class="text-base font-bold font-mono text-gray-900" x-text="activeEndpointData.path"></code>
                    </div>
                    <h2 class="text-xl font-bold text-brand-navy" x-text="activeEndpointData.title"></h2>
                    <p class="text-sm text-gray-600 font-medium" x-text="activeEndpointData.desc"></p>
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
                            <pre x-text="activeEndpointData.curl"></pre>
                        </div>
                    </div>

                    <!-- JSON Response Code Block -->
                    <div class="bg-gray-900 rounded-2xl shadow-xl overflow-hidden border border-gray-800">
                        <div class="bg-gray-800 px-4 py-2.5 flex justify-between items-center text-xs text-gray-400 font-mono">
                            <span>JSON Response</span>
                            <span class="text-green-400 font-bold">200 OK</span>
                        </div>
                        <div class="p-4 font-mono text-xs text-blue-300 overflow-x-auto min-h-[280px]">
                            <pre x-text="activeEndpointData.response"></pre>
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

<script>
function apiDocsApp() {
    return {
        activeEndpoint: '{{ $apiDocs->first()->endpoint_key ?? "" }}',
        endpoints: {
            @foreach($apiDocs as $doc)
            '{{ $doc->endpoint_key }}': {
                key: '{{ $doc->endpoint_key }}',
                title: `{!! addslashes($doc->title) !!}`,
                method: '{{ $doc->method }}',
                path: '{{ $doc->path }}',
                desc: `{!! addslashes($doc->description) !!}`,
                curl: `{!! addslashes($doc->curl_example) !!}`,
                response: `{!! addslashes($doc->json_response) !!}`
            },
            @endforeach
        },
        get endpointsArray() {
            return Object.values(this.endpoints);
        },
        get activeEndpointData() {
            return this.endpoints[this.activeEndpoint] || null;
        }
    }
}
</script>
@endsection
