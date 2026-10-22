@extends('layouts.public')
@section('title', "OneStall Cargo API Documentation | Developer Reference")
@section('meta_description', "Comprehensive RESTful API documentation for OneStall Cargo. Integrate automated rate calculation, AWB generation, courier tracking, and webhooks.")

@section('content')
<!-- Header Banner -->
<section class="bg-[#091024] text-white border-b border-gray-800 py-12 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#F55D11_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-900/50 border border-blue-700/50 text-blue-300 text-xs font-semibold mb-3">
                    <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                    API Version 1.0 (REST JSON)
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight">
                    API Reference & Documentation
                </h1>
                <p class="text-gray-300 text-sm md:text-base mt-2 max-w-2xl font-normal leading-relaxed">
                    Build seamless shipping experiences. Automate rate calculation, book consignments, generate AWB tracking numbers, and receive real-time courier webhooks.
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('docs.postman') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gray-800 hover:bg-gray-700 border border-gray-700 text-xs font-bold text-white transition shadow-sm">
                    <i class="fa-solid fa-download text-brand-red"></i>
                    Postman Collection
                </a>
                <a href="{{ route('docs.openapi') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gray-800 hover:bg-gray-700 border border-gray-700 text-xs font-bold text-white transition shadow-sm">
                    <i class="fa-solid fa-file-code text-blue-400"></i>
                    OpenAPI v3
                </a>
                @auth
                    @if(auth()->user()->role === 'seller')
                        <a href="{{ route('seller.settings') }}#api" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-red hover:bg-brand-redHover text-xs font-bold text-white transition shadow-md shadow-red-500/20">
                            <i class="fa-solid fa-key"></i>
                            View My API Key
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-red hover:bg-brand-redHover text-xs font-bold text-white transition shadow-md shadow-red-500/20">
                        <i class="fa-solid fa-user-plus"></i>
                        Get API Credentials
                    </a>
                @endauth
            </div>
        </div>

        <!-- Base URL Pill Bar -->
        <div class="mt-8 pt-6 border-t border-gray-800/80 flex flex-wrap items-center justify-between gap-4 text-xs font-mono">
            <div class="flex items-center gap-2 text-gray-400">
                <span class="text-gray-500 uppercase font-sans font-bold text-[11px] tracking-wider">Base Endpoint:</span>
                <code class="px-3 py-1.5 bg-gray-900 border border-gray-700 text-green-400 rounded-lg select-all" id="baseUrlText">{{ url('/api') }}</code>
                <button onclick="navigator.clipboard.writeText('{{ url('/api') }}'); alert('Base URL copied to clipboard!');" class="text-gray-400 hover:text-white px-2 py-1 rounded bg-gray-800 hover:bg-gray-700 transition" title="Copy Base URL">
                    <i class="fa-regular fa-copy"></i>
                </button>
            </div>
            <div class="flex items-center gap-6 text-gray-400 font-sans">
                <span><i class="fa-solid fa-shield-halved text-green-400 mr-1.5"></i> HTTPS / TLS 1.3</span>
                <span><i class="fa-solid fa-gauge-high text-yellow-400 mr-1.5"></i> 100 req/min Rate Limit</span>
                <span><i class="fa-solid fa-code text-blue-400 mr-1.5"></i> JSON UTF-8</span>
            </div>
        </div>
    </div>
</section>

<!-- Interactive API Documentation Workspace -->
<section class="py-8 bg-gray-50 min-h-screen" x-data="apiDocsApp()" x-cloak>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Sidebar Navigation (4 cols) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Quick Search Endpoints -->
                <div class="bg-white p-3 rounded-2xl border border-gray-200/80 shadow-sm">
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="text" x-model="searchQuery" placeholder="Filter endpoints (e.g. rate, awb, track)..." class="w-full pl-8 pr-3 py-2 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition">
                    </div>
                </div>

                <!-- Getting Started Nav -->
                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm space-y-3">
                    <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Getting Started</h3>
                    <nav class="space-y-1">
                        <button @click="activeView = 'overview'" :class="activeView === 'overview' ? 'bg-red-50 text-brand-red font-bold border-l-4 border-brand-red' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-3 py-2 rounded-r text-xs flex items-center gap-2.5 transition">
                            <i class="fa-solid fa-book-open text-xs"></i>
                            <span>Overview & Architecture</span>
                        </button>
                        <button @click="activeView = 'auth'" :class="activeView === 'auth' ? 'bg-red-50 text-brand-red font-bold border-l-4 border-brand-red' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-3 py-2 rounded-r text-xs flex items-center gap-2.5 transition">
                            <i class="fa-solid fa-key text-xs"></i>
                            <span>Authentication & Tokens</span>
                        </button>
                        <button @click="activeView = 'errors'" :class="activeView === 'errors' ? 'bg-red-50 text-brand-red font-bold border-l-4 border-brand-red' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-3 py-2 rounded-r text-xs flex items-center gap-2.5 transition">
                            <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                            <span>Errors & Rate Limits</span>
                        </button>
                    </nav>
                </div>

                <!-- Endpoints List -->
                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">API Endpoints</h3>
                        <span class="text-[11px] font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full" x-text="filteredEndpoints.length + ' available'"></span>
                    </div>

                    <nav class="space-y-1.5 max-h-[580px] overflow-y-auto pr-1">
                        <template x-for="endpoint in filteredEndpoints" :key="endpoint.key">
                            <button @click="selectEndpoint(endpoint.key)" 
                                    :class="activeView === 'endpoint' && activeEndpointKey === endpoint.key ? 'bg-blue-50 text-brand-navy font-bold border-l-4 border-brand-red shadow-sm' : 'text-gray-700 hover:bg-gray-50'" 
                                    class="w-full text-left px-3 py-2.5 rounded-r flex items-center justify-between transition group text-xs">
                                <div class="truncate mr-2">
                                    <div class="font-semibold text-gray-900 group-hover:text-brand-navy truncate" x-text="endpoint.title"></div>
                                    <div class="font-mono text-[10px] text-gray-500 truncate" x-text="endpoint.path"></div>
                                </div>
                                <span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded uppercase tracking-wider flex-shrink-0" 
                                      :class="endpoint.method === 'GET' ? 'bg-green-100 text-green-700 border border-green-200' : (endpoint.method === 'POST' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-orange-100 text-orange-700 border border-orange-200')" 
                                      x-text="endpoint.method"></span>
                            </button>
                        </template>

                        <div x-show="filteredEndpoints.length === 0" class="py-6 text-center text-xs text-gray-400">
                            No endpoints match "<span x-text="searchQuery"></span>"
                        </div>
                    </nav>
                </div>

                <!-- Developer Resources Card -->
                <div class="bg-gradient-to-br from-gray-900 to-brand-navy p-5 rounded-2xl text-white shadow-md space-y-3 text-xs">
                    <div class="flex items-center gap-2 font-bold text-sm text-yellow-400">
                        <i class="fa-solid fa-headset"></i> Developer Support
                    </div>
                    <p class="text-gray-300 leading-relaxed font-normal">
                        Need help integrating the API or custom webhook setup? Our logistics engineering team is available 24/7.
                    </p>
                    <div class="pt-2 flex flex-col gap-2">
                        <a href="{{ route('contact') }}" class="inline-flex items-center gap-1.5 text-blue-300 hover:text-white font-semibold transition">
                            <i class="fa-solid fa-envelope"></i> Contact Engineering Support &rarr;
                        </a>
                        <a href="{{ route('developers') }}" class="inline-flex items-center gap-1.5 text-gray-400 hover:text-white font-semibold transition">
                            <i class="fa-solid fa-code-branch"></i> Developer Hub Home &rarr;
                        </a>
                    </div>
                </div>

            </div>

            <!-- Right Content Area (8 cols) -->
            <div class="lg:col-span-8 space-y-6">

                <!-- 1. OVERVIEW VIEW -->
                <div x-show="activeView === 'overview'" class="space-y-6">
                    <div class="bg-white p-8 rounded-3xl border border-gray-200/80 shadow-sm space-y-4">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-50 text-brand-red text-xs font-bold">
                            <i class="fa-solid fa-cube"></i> Architecture Overview
                        </div>
                        <h2 class="text-2xl font-extrabold text-brand-navy">Welcome to OneStall Cargo APIs</h2>
                        <p class="text-gray-600 leading-relaxed text-sm">
                            The OneStall Cargo API is organized around REST. Our API has predictable resource-oriented URLs, accepts JSON-encoded request bodies, returns JSON-encoded responses, and uses standard HTTP response codes, authentication, and verbs.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4">
                            <div class="p-4 rounded-2xl bg-gray-50 border border-gray-200">
                                <div class="w-8 h-8 rounded-xl bg-blue-100 text-brand-navy flex items-center justify-center font-bold text-sm mb-2">1</div>
                                <h4 class="font-bold text-gray-900 text-sm">Calculate & Quote</h4>
                                <p class="text-xs text-gray-500 mt-1">Get real-time rates and courier SLAs based on weight and pincodes.</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-gray-50 border border-gray-200">
                                <div class="w-8 h-8 rounded-xl bg-green-100 text-green-700 flex items-center justify-center font-bold text-sm mb-2">2</div>
                                <h4 class="font-bold text-gray-900 text-sm">Instant AWB Booking</h4>
                                <p class="text-xs text-gray-500 mt-1">One-click consignment creation with wallet deduction and label generation.</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-gray-50 border border-gray-200">
                                <div class="w-8 h-8 rounded-xl bg-orange-100 text-orange-700 flex items-center justify-center font-bold text-sm mb-2">3</div>
                                <h4 class="font-bold text-gray-900 text-sm">Track & Webhooks</h4>
                                <p class="text-xs text-gray-500 mt-1">Automated milestone push updates and status tracking for customers.</p>
                            </div>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button @click="selectEndpoint('calculate_rates')" class="px-5 py-2.5 rounded-xl bg-brand-red text-white text-xs font-bold hover:bg-brand-redHover transition">
                                Start with Rate Calculator &rarr;
                            </button>
                            <button @click="activeView = 'auth'" class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 text-xs font-bold hover:bg-gray-200 transition">
                                View Authentication Guide
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 2. AUTHENTICATION VIEW -->
                <div x-show="activeView === 'auth'" class="space-y-6">
                    <div class="bg-white p-8 rounded-3xl border border-gray-200/80 shadow-sm space-y-6">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold">
                            <i class="fa-solid fa-lock"></i> Security & Authentication
                        </div>
                        <h2 class="text-2xl font-extrabold text-brand-navy">Bearer Token Authentication</h2>
                        <p class="text-gray-600 leading-relaxed text-sm">
                            To interact with secure endpoints (booking shipments, rates, tracking account orders), you must authenticate requests using an HTTP Bearer Token in the <code class="px-1.5 py-0.5 bg-gray-100 text-brand-red font-mono rounded">Authorization</code> header.
                        </p>

                        <div class="bg-gray-900 rounded-2xl p-4 font-mono text-xs text-white border border-gray-800 space-y-1">
                            <span class="text-gray-400"># Pass your token in the request header</span><br>
                            <span class="text-blue-400">Authorization:</span> Bearer <span class="text-yellow-400">OSC_sec_984f8a37b120c4e789...</span><br>
                            <span class="text-blue-400">Accept:</span> application/json<br>
                            <span class="text-blue-400">Content-Type:</span> application/json
                        </div>

                        <div class="space-y-3">
                            <h4 class="font-bold text-gray-900 text-sm">How to obtain your API Token:</h4>
                            <ol class="list-decimal list-inside space-y-2 text-xs text-gray-600 leading-relaxed">
                                <li>Register or log in to your merchant account at <a href="{{ route('login') }}" class="text-brand-red font-semibold hover:underline">OneStall Cargo Seller Portal</a>.</li>
                                <li>Ensure your account KYC has been submitted and approved.</li>
                                <li>Navigate to <strong>Dashboard &rarr; Settings &rarr; API & Webhooks</strong>.</li>
                                <li>Click <strong>Generate New API Key</strong> and copy your secret key securely.</li>
                            </ol>
                        </div>

                        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-start gap-3">
                            <i class="fa-solid fa-triangle-exclamation text-amber-600 text-base mt-0.5"></i>
                            <div>
                                <span class="font-bold">Security Notice:</span> Keep your API keys confidential! Do not commit them into public GitHub repositories or client-side JavaScript apps. Always store them securely in server environment variables.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. ERRORS & RATE LIMITS VIEW -->
                <div x-show="activeView === 'errors'" class="space-y-6">
                    <div class="bg-white p-8 rounded-3xl border border-gray-200/80 shadow-sm space-y-6">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-50 text-brand-red text-xs font-bold">
                            <i class="fa-solid fa-traffic-light"></i> Response Standard
                        </div>
                        <h2 class="text-2xl font-extrabold text-brand-navy">HTTP Status Codes & Rate Limits</h2>
                        <p class="text-gray-600 leading-relaxed text-sm">
                            OneStall Cargo uses conventional HTTP response codes to indicate the success or failure of an API request. Codes in the <code class="font-mono text-green-600">2xx</code> range indicate success, codes in the <code class="font-mono text-amber-600">4xx</code> range indicate an error that failed given the information provided, and codes in the <code class="font-mono text-red-600">5xx</code> range indicate an internal server error.
                        </p>

                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-left text-gray-600 border border-gray-100 rounded-xl overflow-hidden">
                                <thead class="bg-gray-50 text-gray-700 font-bold uppercase text-[10px]">
                                    <tr>
                                        <th class="py-3 px-4">Code</th>
                                        <th class="py-3 px-4">Status</th>
                                        <th class="py-3 px-4">Meaning & Recommended Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 font-medium">
                                    <tr>
                                        <td class="py-3 px-4 font-mono font-bold text-green-600">200 OK</td>
                                        <td class="py-3 px-4 font-bold text-gray-900">Success</td>
                                        <td class="py-3 px-4">The request succeeded.</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4 font-mono font-bold text-green-600">201 Created</td>
                                        <td class="py-3 px-4 font-bold text-gray-900">Resource Created</td>
                                        <td class="py-3 px-4">Consignment booked and AWB assigned.</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4 font-mono font-bold text-red-600">400 Bad Request</td>
                                        <td class="py-3 px-4 font-bold text-gray-900">Validation Error</td>
                                        <td class="py-3 px-4">Required parameter missing (e.g. invalid pincode or negative weight).</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4 font-mono font-bold text-red-600">401 Unauthorized</td>
                                        <td class="py-3 px-4 font-bold text-gray-900">Invalid Token</td>
                                        <td class="py-3 px-4">No valid API Key provided in Authorization header.</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4 font-mono font-bold text-red-600">402 Payment Required</td>
                                        <td class="py-3 px-4 font-bold text-gray-900">Insufficient Balance</td>
                                        <td class="py-3 px-4">Merchant wallet balance is lower than consignment freight charge.</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4 font-mono font-bold text-red-600">403 Forbidden</td>
                                        <td class="py-3 px-4 font-bold text-gray-900">KYC Incomplete</td>
                                        <td class="py-3 px-4">Account KYC pending verification before live shipment dispatch is permitted.</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4 font-mono font-bold text-orange-600">429 Too Many Requests</td>
                                        <td class="py-3 px-4 font-bold text-gray-900">Rate Limited</td>
                                        <td class="py-3 px-4">Standard tier limit of 100 requests per minute exceeded. Retry after 60s.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 4. DYNAMIC ENDPOINT INSPECTOR VIEW -->
                <div x-show="activeView === 'endpoint' && activeEndpoint" class="space-y-6">
                    
                    <!-- Endpoint Header Card -->
                    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200/80 shadow-sm space-y-4">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <span class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold tracking-wide uppercase shadow-sm"
                                      :class="activeEndpoint.method === 'POST' ? 'bg-blue-600 text-white' : (activeEndpoint.method === 'GET' ? 'bg-green-600 text-white' : 'bg-orange-600 text-white')"
                                      x-text="activeEndpoint.method"></span>
                                <code class="text-sm sm:text-base font-bold font-mono text-gray-900 bg-gray-100 px-3 py-1 rounded-lg" x-text="'/api' + activeEndpoint.path"></code>
                            </div>
                            <span class="text-[11px] font-semibold text-gray-500 flex items-center gap-1.5">
                                <i class="fa-solid fa-lock text-brand-red text-xs"></i> Bearer Auth Required
                            </span>
                        </div>

                        <h2 class="text-2xl font-extrabold text-brand-navy" x-text="activeEndpoint.title"></h2>
                        <p class="text-sm text-gray-600 leading-relaxed font-normal" x-text="activeEndpoint.desc"></p>
                    </div>

                    <!-- Code Request & Response Terminal Grid -->
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                        
                        <!-- Request Terminal Card -->
                        <div class="bg-gray-950 rounded-2xl shadow-xl overflow-hidden border border-gray-800 flex flex-col">
                            <!-- Terminal Header with Tabs -->
                            <div class="bg-gray-900 px-4 py-3 flex flex-wrap items-center justify-between border-b border-gray-800 gap-2">
                                <div class="flex items-center space-x-2">
                                    <span class="w-3 h-3 rounded-full bg-red-500/80 inline-block"></span>
                                    <span class="w-3 h-3 rounded-full bg-yellow-500/80 inline-block"></span>
                                    <span class="w-3 h-3 rounded-full bg-green-500/80 inline-block"></span>
                                    <span class="text-gray-400 font-mono text-[11px] ml-1">HTTP Request</span>
                                </div>
                                
                                <div class="flex items-center gap-1 bg-gray-950 p-1 rounded-lg border border-gray-800 text-[11px] font-mono">
                                    <button @click="activeLang = 'curl'" :class="activeLang === 'curl' ? 'bg-brand-red text-white font-bold' : 'text-gray-400 hover:text-white'" class="px-2 py-0.5 rounded transition">cURL</button>
                                    <button @click="activeLang = 'js'" :class="activeLang === 'js' ? 'bg-brand-red text-white font-bold' : 'text-gray-400 hover:text-white'" class="px-2 py-0.5 rounded transition">Node.js</button>
                                    <button @click="activeLang = 'python'" :class="activeLang === 'python' ? 'bg-brand-red text-white font-bold' : 'text-gray-400 hover:text-white'" class="px-2 py-0.5 rounded transition">Python</button>
                                    <button @click="activeLang = 'php'" :class="activeLang === 'php' ? 'bg-brand-red text-white font-bold' : 'text-gray-400 hover:text-white'" class="px-2 py-0.5 rounded transition">PHP</button>
                                </div>
                            </div>

                            <!-- Code Block -->
                            <div class="p-4 font-mono text-xs overflow-x-auto flex-1 min-h-[320px] max-h-[460px] bg-gray-950 text-gray-200">
                                <pre class="text-green-400 leading-relaxed" x-text="computedCodeSnippet"></pre>
                            </div>

                            <!-- Copy Footer -->
                            <div class="bg-gray-900/90 px-4 py-2 border-t border-gray-800/80 flex justify-between items-center text-[11px]">
                                <span class="text-gray-500 font-mono">Payload: JSON</span>
                                <button @click="copySnippet(computedCodeSnippet)" class="text-gray-300 hover:text-white font-semibold flex items-center gap-1 transition">
                                    <i class="fa-regular fa-copy"></i>
                                    <span x-text="copyText">Copy Request</span>
                                </button>
                            </div>
                        </div>

                        <!-- Response Terminal Card -->
                        <div class="bg-gray-950 rounded-2xl shadow-xl overflow-hidden border border-gray-800 flex flex-col">
                            <!-- Terminal Header -->
                            <div class="bg-gray-900 px-4 py-3 flex items-center justify-between border-b border-gray-800">
                                <div class="flex items-center space-x-2">
                                    <span class="text-gray-400 font-mono text-[11px]">Server Response</span>
                                </div>
                                <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded bg-green-950 text-green-400 border border-green-800">
                                    200 OK • JSON
                                </span>
                            </div>

                            <!-- JSON Response Body -->
                            <div class="p-4 font-mono text-xs overflow-x-auto flex-1 min-h-[320px] max-h-[460px] bg-gray-950 text-blue-300">
                                <pre class="leading-relaxed" x-text="activeEndpoint.response"></pre>
                            </div>

                            <!-- Copy Footer -->
                            <div class="bg-gray-900/90 px-4 py-2 border-t border-gray-800/80 flex justify-between items-center text-[11px]">
                                <span class="text-gray-500 font-mono">Content-Type: application/json</span>
                                <button @click="copySnippet(activeEndpoint.response)" class="text-gray-300 hover:text-white font-semibold flex items-center gap-1 transition">
                                    <i class="fa-regular fa-copy"></i>
                                    <span>Copy Response</span>
                                </button>
                            </div>
                        </div>

                    </div>

                    <!-- Parameter Breakdown Documentation -->
                    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200/80 shadow-sm space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-bold text-brand-navy">Headers & Payload Attributes</h3>
                            <span class="text-xs text-gray-500 font-mono">Content-Type: application/json</span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-left text-gray-600">
                                <thead class="bg-gray-50 text-gray-700 font-bold uppercase text-[10px] border-b border-gray-100">
                                    <tr>
                                        <th class="py-2.5 px-3">Field / Header</th>
                                        <th class="py-2.5 px-3">Type</th>
                                        <th class="py-2.5 px-3">Required</th>
                                        <th class="py-2.5 px-3">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 font-medium">
                                    <tr>
                                        <td class="py-2.5 px-3 font-mono font-bold text-gray-900">Authorization</td>
                                        <td class="py-2.5 px-3 text-purple-600 font-mono">string</td>
                                        <td class="py-2.5 px-3"><span class="px-2 py-0.5 bg-red-100 text-red-700 rounded font-bold text-[10px]">Required</span></td>
                                        <td class="py-2.5 px-3">Pass as <code class="font-mono bg-gray-100 px-1 rounded">Bearer &lt;API_KEY&gt;</code> in header.</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2.5 px-3 font-mono font-bold text-gray-900">Accept</td>
                                        <td class="py-2.5 px-3 text-purple-600 font-mono">string</td>
                                        <td class="py-2.5 px-3"><span class="px-2 py-0.5 bg-red-100 text-red-700 rounded font-bold text-[10px]">Required</span></td>
                                        <td class="py-2.5 px-3"><code class="font-mono bg-gray-100 px-1 rounded">application/json</code></td>
                                    </tr>
                                    <template x-for="param in currentEndpointParams" :key="param.name">
                                        <tr>
                                            <td class="py-2.5 px-3 font-mono font-bold text-brand-navy" x-text="param.name"></td>
                                            <td class="py-2.5 px-3 font-mono text-purple-600" x-text="param.type"></td>
                                            <td class="py-2.5 px-3">
                                                <span class="px-2 py-0.5 rounded font-bold text-[10px]"
                                                      :class="param.required ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600'"
                                                      x-text="param.required ? 'Required' : 'Optional'"></span>
                                            </td>
                                            <td class="py-2.5 px-3" x-text="param.desc"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
</section>

<!-- AlpineJS Docs App -->
<script>
function apiDocsApp() {
    return {
        activeView: 'endpoint', // 'overview', 'auth', 'errors', 'endpoint'
        searchQuery: '',
        activeLang: 'curl',
        copyText: 'Copy Request',
        activeEndpointKey: '{{ $apiDocs->first()->endpoint_key ?? "calculate_rates" }}',
        
        endpoints: {
            @foreach($apiDocs as $doc)
            '{{ $doc->endpoint_key }}': {
                key: '{{ $doc->endpoint_key }}',
                title: `{!! addslashes($doc->title) !!}`,
                method: '{{ $doc->method }}',
                path: '{{ $doc->path }}',
                desc: `{!! addslashes($doc->description) !!}`,
                curl: `{!! addslashes($doc->curl_example ?? '') !!}`,
                response: `{!! addslashes($doc->json_response ?? '{}') !!}`
            },
            @endforeach
        },

        endpointParams: {
            'calculate_rates': [
                { name: 'pickup_pincode', type: 'string (6 digits)', required: true, desc: 'Origin Indian postal pincode for package pickup.' },
                { name: 'delivery_pincode', type: 'string (6 digits)', required: true, desc: 'Destination Indian postal pincode for parcel delivery.' },
                { name: 'weight_kg', type: 'numeric (kg)', required: true, desc: 'Gross physical parcel dead weight in kilograms (e.g. 0.5).' },
                { name: 'length_cm', type: 'numeric (cm)', required: false, desc: 'Parcel box length in cm for volumetric weight calculation.' },
                { name: 'width_cm', type: 'numeric (cm)', required: false, desc: 'Parcel box width in cm for volumetric weight calculation.' },
                { name: 'height_cm', type: 'numeric (cm)', required: false, desc: 'Parcel box height in cm for volumetric weight calculation.' },
                { name: 'is_cod', type: 'boolean', required: true, desc: 'Set true if Cash on Delivery (COD) payment is required from customer.' },
                { name: 'invoice_value', type: 'numeric (INR)', required: true, desc: 'Total declared invoice order value.' }
            ],
            'create_shipment': [
                { name: 'pickup_pincode', type: 'string', required: true, desc: 'Origin pickup pincode.' },
                { name: 'shipment_type', type: 'string', required: true, desc: 'Forward (standard outbound) or Reverse (return pickup).' },
                { name: 'receiver_name', type: 'string', required: true, desc: 'Consignee / customer full name.' },
                { name: 'receiver_phone', type: 'string (10 digits)', required: true, desc: 'Receiver mobile number for delivery SMS/OTP.' },
                { name: 'delivery_address', type: 'string', required: true, desc: 'Full delivery destination street address with landmark.' },
                { name: 'delivery_city', type: 'string', required: true, desc: 'Delivery destination city name.' },
                { name: 'delivery_pincode', type: 'string', required: true, desc: '6-digit destination pincode.' },
                { name: 'weight_kg', type: 'numeric', required: true, desc: 'Package gross dead weight in kilograms.' },
                { name: 'is_cod', type: 'boolean', required: true, desc: 'true for COD collection, false for prepaid.' },
                { name: 'invoice_value', type: 'numeric', required: true, desc: 'Total invoice amount in INR.' },
                { name: 'product_name', type: 'string', required: false, desc: 'Product title / description inside parcel.' },
                { name: 'product_sku', type: 'string', required: false, desc: 'Merchant stock keeping unit (SKU) identifier.' },
                { name: 'product_qty', type: 'integer', required: false, desc: 'Quantity of items included (default 1).' }
            ],
            'track_shipment': [
                { name: 'awb', type: 'string (path param)', required: true, desc: 'Official AWB tracking number (e.g. OSC98421376).' }
            ],
            'cancel_shipment': [
                { name: 'awb', type: 'string (path param)', required: true, desc: 'Official AWB tracking number to cancel before pickup.' }
            ],
            'list_shipments': [
                { name: 'page', type: 'integer (query)', required: false, desc: 'Pagination page number (default 1).' },
                { name: 'status', type: 'string (query)', required: false, desc: 'Filter by status: Pending, In Transit, Delivered, Cancelled.' }
            ],
            'serviceability': [
                { name: 'pincode', type: 'string (6 digits)', required: true, desc: 'Target Indian postal pincode to check courier reach & COD.' }
            ],
            'store_webhook': [
                { name: 'platform', type: 'string (path param)', required: true, desc: 'Store platform: shopify, woocommerce, or custom.' },
                { name: 'user_id', type: 'integer (path param)', required: true, desc: 'Your registered merchant user ID.' }
            ]
        },

        get endpointsArray() {
            return Object.values(this.endpoints);
        },

        get filteredEndpoints() {
            if (!this.searchQuery.trim()) return this.endpointsArray;
            const q = this.searchQuery.toLowerCase();
            return this.endpointsArray.filter(e => 
                e.title.toLowerCase().includes(q) || 
                e.path.toLowerCase().includes(q) || 
                e.method.toLowerCase().includes(q)
            );
        },

        get activeEndpoint() {
            return this.endpoints[this.activeEndpointKey] || this.endpointsArray[0] || null;
        },

        get currentEndpointParams() {
            return this.endpointParams[this.activeEndpointKey] || [];
        },

        selectEndpoint(key) {
            this.activeEndpointKey = key;
            this.activeView = 'endpoint';
            window.scrollTo({ top: 380, behavior: 'smooth' });
        },

        get computedCodeSnippet() {
            const ep = this.activeEndpoint;
            if (!ep) return '';
            const baseUrl = '{{ url('/api') }}';
            const fullUrl = baseUrl + ep.path;

            if (this.activeLang === 'curl') {
                return ep.curl || `curl -X ${ep.method} "${fullUrl}" \\\n  -H "Authorization: Bearer YOUR_API_TOKEN" \\\n  -H "Accept: application/json"`;
            }

            if (this.activeLang === 'js') {
                return `// Node.js (Fetch API)\nconst response = await fetch("${fullUrl}", {\n  method: "${ep.method}",\n  headers: {\n    "Authorization": "Bearer YOUR_API_TOKEN",\n    "Content-Type": "application/json",\n    "Accept": "application/json"\n  }${ep.method === 'POST' ? ',\n  body: JSON.stringify({\n    /* payload parameters */\n  })' : ''}\n});\n\nconst data = await response.json();\nconsole.log(data);`;
            }

            if (this.activeLang === 'python') {
                return `# Python 3 (Requests)\nimport requests\n\nurl = "${fullUrl}"\nheaders = {\n    "Authorization": "Bearer YOUR_API_TOKEN",\n    "Accept": "application/json",\n    "Content-Type": "application/json"\n}\n\nresponse = requests.${ep.method.toLowerCase()}(url, headers=headers${ep.method === 'POST' ? ', json={\n    # payload parameters\n}' : ''})\nprint(response.json())`;
            }

            if (this.activeLang === 'php') {
                return `<?php\n// PHP cURL\n$curl = curl_init();\n\ncurl_setopt_array($curl, [\n  CURLOPT_URL => "${fullUrl}",\n  CURLOPT_RETURNTRANSFER => true,\n  CURLOPT_CUSTOMREQUEST => "${ep.method}",\n  CURLOPT_HTTPHEADER => [\n    "Authorization: Bearer YOUR_API_TOKEN",\n    "Content-Type: application/json",\n    "Accept: application/json"\n  ]${ep.method === 'POST' ? ',\n  CURLOPT_POSTFIELDS => json_encode([\n    // payload parameters\n  ])' : ''}\n]);\n\n$response = curl_exec($curl);\ncurl_close($curl);\necho $response;`;
            }

            return ep.curl;
        },

        copySnippet(text) {
            if (!text) return;
            navigator.clipboard.writeText(text).then(() => {
                this.copyText = 'Copied!';
                setTimeout(() => { this.copyText = 'Copy Request'; }, 2000);
            });
        }
    }
}
</script>
@endsection
