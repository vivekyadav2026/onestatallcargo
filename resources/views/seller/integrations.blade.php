@extends(Auth::user()->role === 'franchise' ? 'layouts.hub' : 'layouts.seller')
@section('title', 'E-commerce Integrations - OneStall Cargo')
@section('content')
@php
    $isFranchise = Auth::user()->role === 'franchise';
    $saveRoute = $isFranchise ? route('hub.integrations.save') : route('seller.integrations.save');
    $urlPrefix = $isFranchise ? 'hub' : 'seller';
@endphp
<div class="space-y-8 max-w-7xl mx-auto" x-data="{ activeTab: 'woocommerce', copiedWebhookId: null }">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 text-[10px] font-extrabold tracking-wider uppercase rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                    <i class="fa-solid fa-plug mr-1"></i> Multi-Channel Connect
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight mt-1">E-commerce Integrations</h1>
            <p class="text-sm text-gray-500 mt-1">Connect your online store (WooCommerce, Shopify, Custom App) to automatically pull orders, sync live tracking, and ship in 1 click.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ $isFranchise ? route('hub.shipments') : route('seller.shipments.index') }}" class="px-4 py-2.5 text-xs font-bold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition shadow-xs flex items-center gap-2">
                <i class="fa-solid fa-boxes-stacked text-gray-400"></i> View Orders
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 text-emerald-800 font-bold rounded-2xl border border-emerald-200 text-sm flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-50 text-rose-800 font-bold rounded-2xl border border-rose-200 text-sm flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Connected Stores Section -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 space-y-6">
        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
            <div>
                <h2 class="text-lg font-black text-gray-900 tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-link text-[#4338ca]"></i> Connected Stores
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Manage your active store integrations and sync incoming orders.</p>
            </div>
            <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-bold rounded-full">
                {{ $channels->count() }} {{ Str::plural('Channel', $channels->count()) }} Connected
            </span>
        </div>

        @if($channels->isEmpty())
            <div class="py-12 text-center text-gray-400">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-2xl mb-3 shadow-inner">
                    <i class="fa-solid fa-store"></i>
                </div>
                <h3 class="font-bold text-gray-800 text-base">No store connected yet</h3>
                <p class="text-xs text-gray-500 max-w-sm mx-auto mt-1">Connect your WooCommerce or Shopify store below to start importing and fulfilling orders automatically.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($channels as $channel)
                    <div class="border border-gray-200 rounded-2xl p-5 bg-gradient-to-br from-white to-gray-50/50 shadow-xs hover:shadow-md transition flex flex-col justify-between relative overflow-hidden">
                        
                        <!-- Top Bar with Platform Branding -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2.5">
                                    @if($channel->platform === 'woocommerce')
                                        <div class="w-10 h-10 rounded-xl bg-[#96588a]/10 text-[#96588a] flex items-center justify-center text-xl font-bold">
                                            <i class="fa-brands fa-wordpress"></i>
                                        </div>
                                    @elseif($channel->platform === 'shopify')
                                        <div class="w-10 h-10 rounded-xl bg-[#95BF47]/10 text-[#95BF47] flex items-center justify-center text-xl font-bold">
                                            <i class="fa-brands fa-shopify"></i>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold">
                                            <i class="fa-solid fa-code"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="font-extrabold text-sm text-gray-900 leading-tight">{{ $channel->name }}</h3>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">{{ strtoupper($channel->platform) }}</span>
                                    </div>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $channel->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $channel->is_active ? 'Active' : 'Paused' }}
                                </span>
                            </div>

                            <div class="text-xs text-gray-600 break-all mb-3 font-mono bg-white px-3 py-1.5 rounded-lg border border-gray-100">
                                <i class="fa-solid fa-globe text-gray-400 mr-1"></i> {{ $channel->store_url }}
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-xs py-2 border-y border-gray-100 text-gray-600 mb-3">
                                <div>
                                    <div class="text-[10px] text-gray-400 uppercase font-bold">Total Orders</div>
                                    <div class="font-black text-gray-900 text-sm">{{ number_format($channel->synced_orders_count) }}</div>
                                </div>
                                <div>
                                    <div class="text-[10px] text-gray-400 uppercase font-bold">Last Synced</div>
                                    <div class="font-bold text-gray-800 text-xs mt-0.5">{{ $channel->last_sync_at ? $channel->last_sync_at->diffForHumans() : 'Never' }}</div>
                                </div>
                            </div>

                            <!-- Webhook URL Dropdown / Copy -->
                            <div class="mb-4">
                                <label class="block text-[10px] font-extrabold uppercase text-gray-400 mb-1">Live Webhook URL</label>
                                <div class="flex items-center gap-1.5">
                                    <input type="text" readonly value="{{ $channel->webhook_url }}" id="wh-{{ $channel->id }}" class="w-full text-[11px] font-mono bg-gray-50 border border-gray-200 rounded-lg px-2.5 py-1 text-gray-700 select-all outline-none">
                                    <button type="button" @click="navigator.clipboard.writeText(document.getElementById('wh-{{ $channel->id }}').value); copiedWebhookId = {{ $channel->id }}; setTimeout(() => copiedWebhookId = null, 2000)" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-gray-100 hover:bg-gray-200 text-gray-700 transition flex items-center gap-1">
                                        <i class="fa-regular fa-copy" x-show="copiedWebhookId !== {{ $channel->id }}"></i>
                                        <i class="fa-solid fa-check text-emerald-600" x-show="copiedWebhookId === {{ $channel->id }}" style="display: none;"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2 pt-2 border-t border-gray-100">
                            @if(in_array($channel->platform, ['woocommerce', 'shopify']))
                            <form action="{{ url($urlPrefix . '/integrations/' . $channel->id . '/sync') }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full py-2 bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white border border-indigo-200 text-xs font-bold rounded-xl transition flex items-center justify-center gap-1.5 shadow-xs">
                                    <i class="fa-solid fa-arrows-rotate"></i> Sync Orders
                                </button>
                            </form>
                            @endif

                            <form action="{{ url($urlPrefix . '/integrations/' . $channel->id . '/disconnect') }}" method="POST" onsubmit="return confirm('Are you sure you want to disconnect this store?');">
                                @csrf
                                <button type="submit" title="Disconnect Store" class="p-2 text-rose-600 hover:bg-rose-50 border border-rose-200 rounded-xl transition text-xs font-bold flex items-center justify-center">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Connect New Integration Section -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 sm:p-8 space-y-6">
        <div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-circle-plus text-[#4338ca]"></i> Connect a New Store
            </h2>
            <p class="text-xs text-gray-500 mt-1">Select your e-commerce platform below and enter your API keys to get started.</p>
        </div>

        <!-- Platform Tabs -->
        <div class="flex flex-wrap gap-2 border-b border-gray-100 pb-3">
            <button type="button" @click="activeTab = 'woocommerce'" :class="activeTab === 'woocommerce' ? 'bg-[#96588a] text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="px-5 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2">
                <i class="fa-brands fa-wordpress text-base"></i> WooCommerce (WordPress)
            </button>
            <button type="button" @click="activeTab = 'shopify'" :class="activeTab === 'shopify' ? 'bg-[#95BF47] text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="px-5 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2">
                <i class="fa-brands fa-shopify text-base"></i> Shopify
            </button>
            <button type="button" @click="activeTab = 'custom'" :class="activeTab === 'custom' ? 'bg-[#1e293b] text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="px-5 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2">
                <i class="fa-solid fa-code text-base"></i> Custom Webhook / API
            </button>
        </div>

        <!-- Tab 1: WooCommerce -->
        <div x-show="activeTab === 'woocommerce'" x-transition class="space-y-6">
            <div class="p-4 rounded-2xl bg-[#96588a]/5 border border-[#96588a]/20 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-[#96588a] text-white flex items-center justify-center text-2xl shadow-md">
                        <i class="fa-brands fa-wordpress"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-gray-900 text-base">WooCommerce REST API Integration</h3>
                        <p class="text-xs text-gray-600 mt-0.5">Sync orders on-demand or use real-time Webhooks whenever customers buy on WordPress.</p>
                    </div>
                </div>
            </div>

            <form action="{{ $saveRoute }}" method="POST" class="space-y-4 max-w-2xl">
                @csrf
                <input type="hidden" name="platform" value="woocommerce">

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Store Name / Label <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="e.g. My WordPress Shop" class="w-full text-sm font-semibold px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:border-[#96588a] focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Store Base URL <span class="text-red-500">*</span></label>
                    <input type="url" name="store_url" required placeholder="https://yourwebsite.com" class="w-full text-sm font-semibold px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:border-[#96588a] focus:bg-white transition">
                    <span class="text-[11px] text-gray-400 mt-0.5 block">Include <code>https://</code> with your domain name.</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Consumer Key <span class="text-red-500">*</span></label>
                        <input type="text" name="consumer_key" required placeholder="ck_xxxxxxxxxxxxxxxxxxxxxxxx" class="w-full text-sm font-mono px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:border-[#96588a] focus:bg-white transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Consumer Secret <span class="text-red-500">*</span></label>
                        <input type="password" name="consumer_secret" required placeholder="cs_xxxxxxxxxxxxxxxxxxxxxxxx" class="w-full text-sm font-mono px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:border-[#96588a] focus:bg-white transition">
                    </div>
                </div>

                <!-- Step-by-Step Instructions -->
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200/80 text-xs text-gray-600 space-y-2">
                    <div class="font-extrabold text-gray-800 uppercase tracking-wider text-[11px] flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-info text-[#96588a]"></i> How to generate WooCommerce API Keys:
                    </div>
                    <ol class="list-decimal list-inside space-y-1 text-gray-600 ml-1">
                        <li>Log in to your WordPress Admin dashboard.</li>
                        <li>Go to <strong>WooCommerce &gt; Settings &gt; Advanced &gt; REST API</strong>.</li>
                        <li>Click <strong>Add Key</strong>. Enter Description (e.g. <em>OneStall Cargo</em>) and set Permissions to <strong>Read/Write</strong>.</li>
                        <li>Click <strong>Generate API Key</strong>, then copy Consumer Key &amp; Consumer Secret above.</li>
                    </ol>
                </div>

                <button type="submit" class="px-6 py-3 bg-[#96588a] hover:bg-[#7b4670] text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-check"></i> Connect WooCommerce Store
                </button>
            </form>
        </div>

        <!-- Tab 2: Shopify -->
        <div x-show="activeTab === 'shopify'" x-transition class="space-y-6" style="display: none;">
            <div class="p-4 rounded-2xl bg-[#95BF47]/10 border border-[#95BF47]/20 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-[#95BF47] text-white flex items-center justify-center text-2xl shadow-md">
                        <i class="fa-brands fa-shopify"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-gray-900 text-base">Shopify Store Integration</h3>
                        <p class="text-xs text-gray-600 mt-0.5">Import unfulfilled orders directly using Shopify Custom App Admin API token.</p>
                    </div>
                </div>
            </div>

            <form action="{{ $saveRoute }}" method="POST" class="space-y-4 max-w-2xl">
                @csrf
                <input type="hidden" name="platform" value="shopify">

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Store Name / Label <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="e.g. My Apparel Brand" class="w-full text-sm font-semibold px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:border-[#95BF47] focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Shopify Store Domain (.myshopify.com) <span class="text-red-500">*</span></label>
                    <input type="text" name="store_url" required placeholder="your-store-name.myshopify.com" class="w-full text-sm font-semibold px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:border-[#95BF47] focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Admin API Access Token <span class="text-red-500">*</span></label>
                    <input type="password" name="access_token" required placeholder="shpat_xxxxxxxxxxxxxxxxxxxxxxxx" class="w-full text-sm font-mono px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:border-[#95BF47] focus:bg-white transition">
                </div>

                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200/80 text-xs text-gray-600 space-y-2">
                    <div class="font-extrabold text-gray-800 uppercase tracking-wider text-[11px] flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-info text-[#95BF47]"></i> How to generate Shopify Admin Token:
                    </div>
                    <ol class="list-decimal list-inside space-y-1 text-gray-600 ml-1">
                        <li>In your Shopify Admin, go to <strong>Settings &gt; Apps and sales channels &gt; Develop apps</strong>.</li>
                        <li>Click <strong>Create an app</strong>.</li>
                        <li>Under <strong>Configuration &gt; Admin API integration</strong>, enable <code>read_orders</code>, <code>write_orders</code>, and <code>write_fulfillments</code>.</li>
                        <li>Click <strong>Install app</strong>, then click <strong>Reveal token once</strong> and paste here.</li>
                    </ol>
                </div>

                <button type="submit" class="px-6 py-3 bg-[#95BF47] hover:bg-[#83aa3d] text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-check"></i> Connect Shopify Store
                </button>
            </form>
        </div>

        <!-- Tab 3: Custom Webhook / API -->
        <div x-show="activeTab === 'custom'" x-transition class="space-y-6" style="display: none;">
            <div class="p-4 rounded-2xl bg-slate-100 border border-slate-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-[#1e293b] text-white flex items-center justify-center text-2xl shadow-md">
                        <i class="fa-solid fa-code"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-gray-900 text-base">Custom Store &amp; Webhook Endpoint</h3>
                        <p class="text-xs text-gray-600 mt-0.5">Push orders directly from Magento, OpenCart, PHP/Node apps, or Zapier webhooks.</p>
                    </div>
                </div>
            </div>

            <form action="{{ $saveRoute }}" method="POST" class="space-y-4 max-w-2xl">
                @csrf
                <input type="hidden" name="platform" value="custom">

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Custom System Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="e.g. My Magento Store / ERP" class="w-full text-sm font-semibold px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:border-[#1e293b] focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Website URL (Optional)</label>
                    <input type="url" name="store_url" placeholder="https://mycustomwebsite.com" class="w-full text-sm font-semibold px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:border-[#1e293b] focus:bg-white transition">
                </div>

                <button type="submit" class="px-6 py-3 bg-[#1e293b] hover:bg-black text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Add Custom Webhook Channel
                </button>
            </form>

            <div class="mt-6 border-t border-gray-100 pt-6 space-y-4">
                <h4 class="font-extrabold text-sm text-gray-800">Direct Webhook Payload Reference</h4>
                <p class="text-xs text-gray-500">Send an HTTP POST request to your personal webhook endpoint:</p>
                <div class="bg-gray-900 text-gray-100 rounded-2xl p-4 font-mono text-xs overflow-x-auto">
                    <span class="text-emerald-400 font-bold">POST</span> {{ url('/api/v1/integrations/custom/webhook/' . Auth::id()) }}
                    <div class="text-gray-400 mt-2">// JSON Body Example:</div>
<pre class="mt-1 text-gray-200">{
  "order_id": "ORD-5001",
  "receiver_name": "Rajesh Sharma",
  "receiver_phone": "9876543210",
  "receiver_email": "customer@gmail.com",
  "delivery_address": "House 12, MG Road",
  "delivery_city": "Jaipur",
  "delivery_pincode": "302001",
  "delivery_state": "Rajasthan",
  "weight_kg": 0.5,
  "is_cod": true,
  "invoice_value": 850.00,
  "product_name": "Premium Cotton Kurta",
  "product_qty": 1
}</pre>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
