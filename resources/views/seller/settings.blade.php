@extends('layouts.seller')
@section('title', 'Settings - OneStall Cargo')

@section('content')
<div class="space-y-8 max-w-[1200px]" x-data="settingsManager()">
    
    <!-- Dynamic Header -->
    <div class="flex items-center justify-between">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
            <button x-show="view !== 'grid'" @click="view = 'grid'" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-gray-900 hover:shadow-sm transition" style="display: none;">
                <i class="fa-solid fa-arrow-left"></i>
            </button>
            <div>
                <h1 class="text-[28px] font-bold text-gray-900 tracking-tight" x-text="headerTitle">Settings</h1>
                <p class="text-sm text-gray-500 mt-1" x-text="headerSubtitle">Manage your business profile, warehouses, bank accounts, and API keys.</p>
            </div>
        </div>
        
        <!-- Contextual Action Buttons -->
        <button x-show="view === 'warehouses'" @click="showAddWarehouse = true" class="px-4 py-2 bg-[#4338ca] text-white text-sm font-bold rounded-lg shadow-sm hover:bg-[#3730a3] transition" style="display: none;">
            <i class="fa-solid fa-plus mr-1"></i> Add Warehouse
        </button>
    </div>

    <!-- MAIN GRID VIEW (Essential Logistics Modules Only) -->
    <div x-show="view === 'grid'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        
        <!-- Category 1: Business Profile & Verification -->
        <div class="mb-8">
            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2"><i class="fa-solid fa-building"></i> BUSINESS PROFILE & KYC</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <button @click="openView('company', 'Company Details', 'Manage business name, GSTIN, PAN, and brand settings')" class="block text-left bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-[#4338ca] transition group">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#4338ca] flex items-center justify-center mb-3 text-lg group-hover:bg-[#4338ca] group-hover:text-white transition-colors">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-base mb-1 group-hover:text-[#4338ca]">Company Details</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Update your GSTIN, PAN, brand name, and registered address</p>
                </button>

                <button @click="openView('kyc', 'KYC Verification', 'Upload identity & tax documents for compliance')" class="block text-left bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-[#4338ca] transition group relative flex flex-col h-full">
                    @php $userKyc = Auth::user()->kyc; @endphp
                    
                    <div class="flex items-start justify-between mb-3 w-full">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <i class="fa-solid fa-id-card"></i>
                        </div>
                        
                        @if($userKyc && $userKyc->status === 'approved')
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-md text-[10px] font-bold uppercase tracking-wide border border-green-200"><i class="fa-solid fa-circle-check"></i> Verified</span>
                        @elseif($userKyc && $userKyc->status === 'pending')
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-md text-[10px] font-bold uppercase tracking-wide border border-yellow-200"><i class="fa-solid fa-hourglass-half"></i> Pending</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-md text-[10px] font-bold uppercase tracking-wide border border-gray-200"><i class="fa-solid fa-circle-exclamation"></i> Required</span>
                        @endif
                    </div>
                    
                    <h4 class="font-bold text-gray-900 text-base mb-1 group-hover:text-[#4338ca]">KYC Verification</h4>
                    <p class="text-xs text-gray-500 leading-relaxed flex-1">Submit Aadhaar, PAN, and GST documents for shipping activation</p>
                </button>
            </div>
        </div>

        <!-- Category 2: Logistics & Operations -->
        <div class="mb-8">
            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2"><i class="fa-solid fa-warehouse"></i> LOGISTICS & PAYOUTS</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <button @click="openView('warehouses', 'Pickup Warehouses', 'Manage locations where couriers will pick up your parcels')" class="block text-left bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-[#4338ca] transition group">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-3 text-lg group-hover:bg-amber-600 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-base mb-1 group-hover:text-[#4338ca]">Pickup Warehouses</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Add and manage pickup hub locations with auto pincode lookup</p>
                </button>

                <a href="{{ route('seller.label-settings') }}" class="block text-left bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-[#4338ca] transition group">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mb-3 text-lg group-hover:bg-purple-600 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-print"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-base mb-1 group-hover:text-[#4338ca]">Label Setting</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Configure label format, details to show on label and default types</p>
                </a>

                <button @click="openView('bank', 'Bank Account for COD Payouts', 'Set up your bank account for COD remittances')" class="block text-left bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-[#4338ca] transition group">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3 text-lg group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-base mb-1 group-hover:text-[#4338ca]">Bank Account Details</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Bank account for automated Cash on Delivery (COD) remittances</p>
                </button>
            </div>
        </div>

        <!-- Category 3: Developer API & Security -->
        <div class="mb-8">
            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2"><i class="fa-solid fa-code"></i> INTEGRATION & SECURITY</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <button @click="openView('api_keys', 'API Keys & Developer Tokens', 'Generate Sanctum tokens for store API integration')" class="block text-left bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-[#4338ca] transition group">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3 text-lg group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-base mb-1 group-hover:text-[#4338ca]">API Keys & Access Tokens</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Generate API keys to connect Shopify, WooCommerce, or custom ERP</p>
                </button>

                <button @click="openView('password', 'Password & Security', 'Update your login password')" class="block text-left bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-[#4338ca] transition group">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center mb-3 text-lg group-hover:bg-rose-600 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-base mb-1 group-hover:text-[#4338ca]">Change Password</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Keep your account secure by updating your password regularly</p>
                </button>
            </div>
        </div>

    </div>

    <!-- ================= SUB VIEWS (100% Functional Code) ================= -->

          <!-- VIEW 1: Company Profile -->
      <div x-show="view === 'company'" style="display: none;" class="bg-white p-6 md:p-8 rounded-3xl border border-gray-200 shadow-sm max-w-4xl mt-4">
          <div class="mb-8">
              <div class="flex items-center gap-3 mb-2">
                  <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                      <i class="fa-solid fa-building"></i>
                  </div>
                  <h3 class="font-extrabold text-gray-900 text-xl md:text-2xl">Company Profile</h3>
              </div>
              <p class="text-xs md:text-sm text-gray-500 max-w-xl pl-13">Manage your basic business information, registered address, and tax details.</p>
          </div>
          
          <form @submit.prevent="updateProfile" class="space-y-6 md:space-y-8 pl-0 md:pl-13">
              <!-- Section 1 -->
              <div class="bg-gray-50/80 p-5 rounded-2xl border border-gray-100">
                  <h4 class="text-xs font-bold text-gray-900 mb-4 flex items-center gap-2">
                      <span class="w-5 h-5 rounded-md bg-gray-200 text-gray-700 flex items-center justify-center text-[10px]">1</span> Basic & Brand Information
                  </h4>
                  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                      <div>
                          <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Contact Person Name <span class="text-red-500">*</span></label>
                          <input type="text" x-model="profile.name" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] transition" required>
                      </div>
                      <div>
                          <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Email Address <span class="text-red-500">*</span></label>
                          <input type="email" x-model="profile.email" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] transition" required>
                      </div>
                      <div>
                          <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Registered Company Name</label>
                          <input type="text" x-model="profile.company_name" placeholder="e.g. Acme Logistics Pvt Ltd" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] transition">
                      </div>
                      <div>
                          <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Brand Name (Printed on Labels)</label>
                          <input type="text" x-model="profile.brand_name" placeholder="e.g. Acme Store" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] transition">
                      </div>
                      <div>
                          <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Business Structure</label>
                          <select x-model="profile.business_type" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] transition">
                              <option value="">Select Business Structure</option>
                              <option value="Sole Proprietorship">Sole Proprietorship</option>
                              <option value="Private Limited">Private Limited (Pvt Ltd)</option>
                              <option value="Partnership / LLP">Partnership / LLP</option>
                              <option value="Individual / Freelancer">Individual / Freelancer</option>
                          </select>
                      </div>
                      <div>
                          <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Primary Phone / Mobile</label>
                          <input type="text" maxlength="10" x-model="profile.phone" placeholder="10-digit Mobile Number" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] transition">
                      </div>
                  </div>
              </div>
  
              <!-- Section 2 -->
              <div class="bg-gray-50/80 p-5 rounded-2xl border border-gray-100">
                  <h4 class="text-xs font-bold text-gray-900 mb-4 flex items-center gap-2">
                      <span class="w-5 h-5 rounded-md bg-gray-200 text-gray-700 flex items-center justify-center text-[10px]">2</span> Tax Details
                  </h4>
                  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                      <div>
                          <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">GSTIN Number (Optional)</label>
                          <input type="text" maxlength="15" x-model="profile.gstin" @input="profile.gstin = profile.gstin.toUpperCase()" placeholder="22AAAAA0000A1Z5" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono text-gray-900 uppercase focus:outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] transition">
                      </div>
                      <div>
                          <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">PAN Number</label>
                          <input type="text" maxlength="10" x-model="profile.pan_number" @input="profile.pan_number = profile.pan_number.toUpperCase()" placeholder="ABCDE1234F" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono text-gray-900 uppercase focus:outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] transition">
                      </div>
                  </div>
              </div>
  
              <!-- Section 3 -->
              <div class="bg-gray-50/80 p-5 rounded-2xl border border-gray-100">
                  <h4 class="text-xs font-bold text-gray-900 mb-4 flex items-center gap-2">
                      <span class="w-5 h-5 rounded-md bg-gray-200 text-gray-700 flex items-center justify-center text-[10px]">3</span> Registered Address
                  </h4>
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                      <div>
                          <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Pincode</label>
                          <input type="text" maxlength="6" x-model="profile.company_pincode" @input="fetchCityForCompany(profile.company_pincode)" placeholder="6-digit PIN" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] transition">
                      </div>
                      <div>
                          <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">City</label>
                          <input type="text" x-model="profile.company_city" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] transition">
                      </div>
                      <div>
                          <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">State</label>
                          <input type="text" x-model="profile.company_state" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] transition">
                      </div>
                      <div class="md:col-span-3">
                          <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Complete Address</label>
                          <textarea x-model="profile.company_address" rows="2" placeholder="Building, Street, Landmark details" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] transition"></textarea>
                      </div>
                  </div>
              </div>
  
              <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                  <p x-show="successMessage" class="text-green-600 text-xs font-bold flex items-center gap-1 order-2 sm:order-1" x-text="successMessage"></p>
                  <button type="submit" class="px-8 py-3 bg-[#0f172a] text-white text-xs font-bold hover:bg-black rounded-xl transition shadow-md w-full sm:w-auto order-1 sm:order-2" :disabled="loading">
                      <span x-show="!loading">Update Company Details</span>
                      <span x-show="loading"><i class="fa-solid fa-spinner fa-spin mr-1"></i> Saving...</span>
                  </button>
              </div>
          </form>
      </div>

      <!-- VIEW 2: KYC Verification -->
    <div x-show="view === 'kyc'" style="display: none;" class="max-w-4xl">
        @if($userKyc && $userKyc->status === 'approved')
              <div class="bg-green-50/60 border border-green-200 rounded-2xl p-6 mb-8 flex items-start sm:items-center justify-between shadow-sm max-w-4xl mt-4">
                  <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
                      <div class="w-12 h-12 bg-green-100 text-green-700 rounded-xl flex items-center justify-center text-xl shrink-0 border border-green-200">
                          <i class="fa-solid fa-shield"></i>
                      </div>
                      <div>
                          <h2 class="text-sm font-bold text-gray-900">KYC Verified & Active</h2>
                          <p class="text-[11px] text-gray-500 mt-1">Your identity documents are verified. Shipping and COD payouts are fully unlocked.</p>
                      </div>
                  </div>
                  <span class="hidden sm:inline-block px-3 py-1 bg-green-100 text-green-700 border border-green-200 rounded-lg text-[10px] font-bold uppercase tracking-wider"><i class="fa-solid fa-circle-check mr-1"></i> Verified</span>
              </div>
          @elseif($userKyc && $userKyc->status === 'pending')
              <div class="bg-yellow-50/60 border border-yellow-200 rounded-2xl p-6 mb-8 flex items-start sm:items-center justify-between shadow-sm max-w-4xl mt-4">
                  <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
                      <div class="w-12 h-12 bg-yellow-100 text-yellow-700 rounded-xl flex items-center justify-center text-xl shrink-0 border border-yellow-200">
                          <i class="fa-solid fa-clock-rotate-left"></i>
                      </div>
                      <div>
                          <h2 class="text-sm font-bold text-gray-900">Verification Under Review</h2>
                          <p class="text-[11px] text-gray-500 mt-1">Our compliance team is verifying your uploaded documents.</p>
                      </div>
                  </div>
                  <span class="hidden sm:inline-block px-3 py-1 bg-yellow-100 text-yellow-700 border border-yellow-200 rounded-lg text-[10px] font-bold uppercase tracking-wider"><i class="fa-solid fa-hourglass-half mr-1"></i> Under Review</span>
              </div>
          @endif

        @if(!$userKyc || $userKyc->status !== 'approved')
              <div class="bg-white p-6 md:p-8 rounded-3xl border border-gray-200 shadow-sm max-w-4xl mt-4">
                  <div class="mb-8">
                      <div class="mb-6 flex items-center gap-4">`n                    @if(Auth::user()->avatar)`n                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">`n                    @else`n                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-400"><i class="fa-solid fa-user text-2xl"></i></div>`n                    @endif`n                    <div>`n                        <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Profile Photo</label>`n                        <input type="file" id="avatar-upload" accept="image/*" class="text-xs">`n                    </div>`n                </div>`n                <div class="flex items-center gap-3 mb-2">
                          <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                              <i class="fa-solid fa-file-shield"></i>
                          </div>
                          <h3 class="font-extrabold text-gray-900 text-xl md:text-2xl">Complete KYC Verification</h3>
                      </div>
                      <p class="text-xs md:text-sm text-gray-500 max-w-xl pl-13">Upload your business and identity documents to unlock live shipping and COD payouts.</p>
                  </div>
      
                  <form action="{{ route('seller.kyc.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6 md:space-y-8 pl-0 md:pl-13">
                      @csrf
                      
                      <!-- Section 1 -->
                      <div class="bg-gray-50/80 p-5 rounded-2xl border border-gray-100">
                          <h4 class="text-xs font-bold text-gray-900 mb-4 flex items-center gap-2">
                              <span class="w-5 h-5 rounded-md bg-gray-200 text-gray-700 flex items-center justify-center text-[10px]">1</span> Business Details
                          </h4>
                          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                              <div>
                                  <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Business Structure</label>
                                  <select name="business_type" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] transition" required>
                                      <option value="Individual">Individual / Freelancer</option>
                                      <option value="Sole Proprietorship">Sole Proprietorship</option>
                                      <option value="Private Limited">Private Limited (Pvt Ltd)</option>
                                      <option value="Partnership / LLP">Partnership / LLP</option>
                                  </select>
                              </div>
                              <div>
                                  <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">PAN Card Number</label>
                                  <input type="text" maxlength="10" name="pan_number" value="{{ $userKyc->pan_number ?? Auth::user()->pan_number ?? '' }}" placeholder="ABCDE1234F" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-900 uppercase focus:outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] transition" required>
                              </div>
                          </div>
                      </div>

                      <!-- Section 2 -->
                      <div class="bg-gray-50/80 p-5 rounded-2xl border border-gray-100">
                          <h4 class="text-xs font-bold text-gray-900 mb-4 flex items-center gap-2">
                              <span class="w-5 h-5 rounded-md bg-gray-200 text-gray-700 flex items-center justify-center text-[10px]">2</span> Identity Proof
                          </h4>
                          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                              <div>
                                  <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Document Type</label>
                                  <select name="document_type" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] transition" required>
                                      <option value="Aadhaar">Aadhaar Card</option>
                                      <option value="Voter ID">Voter ID Card</option>
                                      <option value="Passport">Passport</option>
                                  </select>
                              </div>
                              <div>
                                  <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Document Number</label>
                                  <input type="text" name="document_number" value="{{ $userKyc->document_number ?? '' }}" placeholder="Enter ID Number" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-900 focus:outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] transition" required>
                              </div>
                          </div>
                      </div>
      
                      <!-- Section 3 -->
                      <div class="bg-gray-50/80 p-5 rounded-2xl border border-gray-100">
                          <h4 class="text-xs font-bold text-gray-900 mb-4 flex items-center gap-2">
                              <span class="w-5 h-5 rounded-md bg-gray-200 text-gray-700 flex items-center justify-center text-[10px]">3</span> Document Uploads
                          </h4>
                          
                          <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4" x-data="{
                              files: { id_front: null, id_back: null, pan_doc: null, gst_doc: null },
                              handleFile(e, type) {
                                  if(e.target.files.length > 0) {
                                      this.files[type] = e.target.files[0].name;
                                  }
                              }
                          }">
                              <!-- ID Front -->
                              <label class="relative flex flex-col items-center justify-center p-4 bg-white border border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-[#4338ca] hover:bg-blue-50/30 transition-all group">
                                  <input type="file" name="id_front" accept="image/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="handleFile($event, 'id_front')" required>
                                  <i class="fa-solid fa-cloud-arrow-up text-gray-300 text-xl mb-2 group-hover:text-[#4338ca] transition"></i>
                                  <span class="text-[11px] font-bold text-gray-700">ID Front</span>
                                  <span class="text-[9px] text-gray-400 mt-0.5 truncate w-full text-center px-1" x-text="files.id_front ? files.id_front : 'Select File'"></span>
                                  <div x-show="files.id_front" class="absolute top-2 right-2 text-green-500 text-xs"><i class="fa-solid fa-circle-check"></i></div>
                              </label>
                              
                              <!-- ID Back -->
                              <label class="relative flex flex-col items-center justify-center p-4 bg-white border border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-[#4338ca] hover:bg-blue-50/30 transition-all group">
                                  <input type="file" name="id_back" accept="image/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="handleFile($event, 'id_back')" required>
                                  <i class="fa-solid fa-cloud-arrow-up text-gray-300 text-xl mb-2 group-hover:text-[#4338ca] transition"></i>
                                  <span class="text-[11px] font-bold text-gray-700">ID Back</span>
                                  <span class="text-[9px] text-gray-400 mt-0.5 truncate w-full text-center px-1" x-text="files.id_back ? files.id_back : 'Select File'"></span>
                                  <div x-show="files.id_back" class="absolute top-2 right-2 text-green-500 text-xs"><i class="fa-solid fa-circle-check"></i></div>
                              </label>

                              <!-- PAN -->
                              <label class="relative flex flex-col items-center justify-center p-4 bg-white border border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-[#4338ca] hover:bg-blue-50/30 transition-all group">
                                  <input type="file" name="pan_doc" accept="image/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="handleFile($event, 'pan_doc')" required>
                                  <i class="fa-solid fa-cloud-arrow-up text-gray-300 text-xl mb-2 group-hover:text-[#4338ca] transition"></i>
                                  <span class="text-[11px] font-bold text-gray-700">PAN Card</span>
                                  <span class="text-[9px] text-gray-400 mt-0.5 truncate w-full text-center px-1" x-text="files.pan_doc ? files.pan_doc : 'Select File'"></span>
                                  <div x-show="files.pan_doc" class="absolute top-2 right-2 text-green-500 text-xs"><i class="fa-solid fa-circle-check"></i></div>
                              </label>

                              <!-- GST -->
                              <label class="relative flex flex-col items-center justify-center p-4 bg-white border border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-[#4338ca] hover:bg-blue-50/30 transition-all group">
                                  <input type="file" name="gst_doc" accept="image/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="handleFile($event, 'gst_doc')">
                                  <i class="fa-solid fa-cloud-arrow-up text-gray-300 text-xl mb-2 group-hover:text-[#4338ca] transition"></i>
                                  <span class="text-[11px] font-bold text-gray-700">GST (Opt.)</span>
                                  <span class="text-[9px] text-gray-400 mt-0.5 truncate w-full text-center px-1" x-text="files.gst_doc ? files.gst_doc : 'Select File'"></span>
                                  <div x-show="files.gst_doc" class="absolute top-2 right-2 text-green-500 text-xs"><i class="fa-solid fa-circle-check"></i></div>
                              </label>
                          </div>
                      </div>
      
                      <!-- Section 4 -->
                        <div class="bg-gray-50/80 p-5 rounded-2xl border border-gray-100 mb-6">
                            <h4 class="text-xs font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <span class="w-5 h-5 rounded-md bg-gray-200 text-gray-700 flex items-center justify-center text-[10px]">4</span> Bank Details <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">(Optional)</span>
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Account Holder Name</label>
                                    <input type="text" name="account_holder_name" value="{{ Auth::user()->account_holder_name ?? '' }}" placeholder="Name as per Bank" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-900 focus:outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] transition">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Bank Name</label>
                                    <input type="text" name="bank_name" value="{{ Auth::user()->bank_name ?? '' }}" placeholder="e.g. HDFC Bank" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-900 focus:outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] transition">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Account Number</label>
                                    <input type="text" name="account_number" value="{{ Auth::user()->account_number ?? '' }}" placeholder="Account Number" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-900 focus:outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] transition">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 mb-1.5 uppercase tracking-wider">IFSC Code</label>
                                    <input type="text" name="ifsc_code" value="{{ Auth::user()->ifsc_code ?? '' }}" placeholder="e.g. HDFC0001234" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-900 uppercase focus:outline-none focus:border-[#4338ca] focus:ring-1 focus:ring-[#4338ca] transition">
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                          <button type="submit" class="px-8 py-3 bg-[#0f172a] text-white text-xs font-bold hover:bg-black rounded-xl transition shadow-md w-full sm:w-auto">
                              Submit Documents
                          </button>
                      </div>
                  </form>
              </div>
          @endif
    </div>

    <!-- VIEW 3: Pickup Warehouses (Complete Management) -->
    <div x-show="view === 'warehouses'" class="w-full bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden" style="display: none;">
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 p-4 sm:p-6 md:p-8">
        
        <!-- Left Side: Form -->
        <div class="xl:col-span-6 space-y-5 xl:border-r border-gray-100 pr-0 xl:pr-8">
            <p class="text-[13px] text-gray-400 font-bold mb-6">
                <span class="text-red-500 font-bold">*</span>All Fields Required
            </p>
            
            <form @submit.prevent="saveWarehouse" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Pickup Pincode <span class="text-red-500">*</span></label>
                        <input type="text" x-model="newWh.pincode" @input="fetchCityForWarehouse(newWh.pincode)" maxlength="6" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-blue-500 transition-colors" required>
                        <p class="text-[10px] text-red-500 mt-1">*This field is Required</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Warehouse Name <span class="text-red-500">*</span></label>
                        <input type="text" x-model="newWh.name" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-blue-500 transition-colors" required>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-end mb-1.5">
                        <label class="block text-xs font-bold text-gray-700">Address 1 <span class="text-red-500">*</span></label>
                        <span class="text-[10px] font-bold text-green-600">(House No./ Ward Number)</span>
                    </div>
                    <input type="text" x-model="newWh.address_1" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-blue-500 transition-colors" required>
                </div>

                <div>
                    <div class="flex justify-between items-end mb-1.5">
                        <label class="block text-xs font-bold text-gray-700">Address 2</label>
                        <span class="text-[10px] font-bold text-green-600">(Building Name)</span>
                    </div>
                    <input type="text" x-model="newWh.address_2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-blue-500 transition-colors">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Landmark <span class="text-red-500">*</span></label>
                        <input type="text" x-model="newWh.landmark" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-blue-500 transition-colors" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">State <span class="text-red-500">*</span></label>
                        <input type="text" x-model="newWh.state" class="w-full border border-gray-200 bg-gray-100 rounded-lg px-3 py-2 text-sm outline-none font-semibold text-gray-600 cursor-not-allowed" readonly required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">City <span class="text-red-500">*</span></label>
                        <input type="text" x-model="newWh.city" class="w-full border border-gray-200 bg-gray-100 rounded-lg px-3 py-2 text-sm outline-none font-semibold text-gray-600 cursor-not-allowed" readonly required>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Contact Name <span class="text-red-500">*</span></label>
                        <input type="text" x-model="newWh.contact_person" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-blue-500 transition-colors" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Mobile Number <span class="text-red-500">*</span></label>
                        <input type="text" x-model="newWh.phone" maxlength="10" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-blue-500 transition-colors" required>
                    </div>
                </div>

                <p class="text-[11px] text-[#4c1d95] font-semibold text-left py-1.5 mt-2">
                    " ?? Add your accessible mobile number for Smooth communication at Pickup! ?? "
                </p>

                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 mt-6">
                    <button type="button" @click="resetWhForm" class="px-6 py-2.5 bg-gray-50 hover:bg-gray-100 border border-gray-200 text-gray-800 font-bold rounded shadow-sm text-sm transition w-full sm:w-32">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 bg-[#0ea5e9] hover:bg-[#0284c7] text-white font-bold rounded shadow-sm text-sm transition w-full sm:w-44 flex justify-center" :disabled="loading">
                        <span x-show="!loading" x-text="newWh.id ? 'Update Warehouse' : 'Add Warehouse'"></span>
                        <span x-show="loading"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Saving...</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Side: List -->
        <div class="xl:col-span-6">
            <h2 class="text-[20px] font-bold text-center text-gray-900 mb-6 tracking-tight mt-4 xl:mt-0">Add Warehouse Addresses for Pickup</h2>
            
            <div class="bg-[#3b0764] rounded-2xl p-4 shadow-lg min-h-[450px]">
                <div class="hidden sm:flex items-center px-4 pb-3 mb-4 border-b border-purple-800">
                    <div class="w-1/4 shrink-0 text-white font-semibold text-xs tracking-wide">Contact Person</div>
                    <div class="flex-1 min-w-0 text-white font-semibold text-xs tracking-wide text-center">Warehouse Details</div>
                    <div class="w-[110px] shrink-0 text-white font-semibold text-xs tracking-wide text-right pr-2">Action</div>
                </div>

                <div class="space-y-4 max-h-[500px] overflow-y-auto pr-1">@php $warehouses = \App\Models\Warehouse::where('user_id', Auth::id())->get(); @endphp
                    @forelse($warehouses as $wh)
                    <div class="bg-white rounded-[14px] p-4 flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-0 shadow-sm" id="wh-card-{{$wh->id}}">
                        <!-- Contact Person -->
                        <div class="w-full sm:w-1/4 shrink-0 sm:pr-2">
                            <div class="font-extrabold text-gray-900 text-[13px] truncate">{{ $wh->contact_person }}</div>
                            <div class="text-gray-600 text-[11px] font-semibold mt-0.5">{{ $wh->phone }}</div>
                        </div>

                        <!-- Warehouse Details -->
                        <div class="w-full sm:flex-1 min-w-0 text-left sm:text-center sm:px-3">
                            <div class="font-bold text-gray-900 text-[13px] mb-1 leading-tight truncate">{{ $wh->name }}</div>
                            <div class="text-gray-600 text-[10px] leading-snug break-words">{{ $wh->address }}, {{ $wh->city }}, {{ $wh->state }}, {{ $wh->pincode }}</div>
                        </div>

                        <!-- Action -->
                        <div class="w-full sm:w-[110px] shrink-0 flex items-center justify-start sm:justify-end gap-2 pr-1 pt-2 sm:pt-0 border-t sm:border-0 border-gray-100">
                            <!-- Toggle (is_default) -->
                            <button @click="setDefaultWarehouse({{ $wh->id }})" class="relative inline-flex h-5 w-9 shrink-0 items-center rounded-full transition-colors focus:outline-none" :class="'{{ $wh->is_default }}' == '1' ? 'bg-[#0ea5e9]' : 'bg-gray-300'">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform shadow-sm" :class="'{{ $wh->is_default }}' == '1' ? 'translate-x-4' : 'translate-x-0.5'"></span>
                            </button>
                            
                            <!-- Edit -->
                            <button @click="editWarehouse(JSON.parse($el.dataset.wh))" data-wh="{!! htmlspecialchars(json_encode($wh), ENT_QUOTES, 'UTF-8') !!}" class="w-7 h-7 shrink-0 rounded-full bg-green-500 text-white flex items-center justify-center hover:bg-green-600 transition shadow-sm"><i class="fa-solid fa-pencil text-[10px]"></i></button>

                            <!-- Delete -->
                            <button @click="deleteWarehouse({{ $wh->id }})" class="w-7 h-7 shrink-0 rounded-full bg-gray-500 text-white flex items-center justify-center hover:bg-red-500 transition shadow-sm"><i class="fa-solid fa-trash text-[10px]"></i></button>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <div class="text-white/60 text-xs font-semibold">No pickup addresses found.</div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- VIEW 4: Bank Account for COD Remittance -->
    <div x-show="view === 'bank'" style="display: none;" class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm max-w-2xl">
        <form @submit.prevent="updateBank">
            <h3 class="font-bold text-gray-900 text-lg mb-2">COD Remittance Bank Account</h3>
            <p class="text-xs text-gray-500 mb-6">Enter your bank account details where Cash on Delivery (COD) collected amounts will be transferred.</p>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Account Holder Name <span class="text-red-500">*</span></label>
                    <input type="text" x-model="bank.account_holder_name" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-[#4338ca]" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Bank Name <span class="text-red-500">*</span></label>
                    <input type="text" x-model="bank.bank_name" placeholder="e.g. HDFC Bank / ICICI Bank" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-[#4338ca]" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Bank Account Number <span class="text-red-500">*</span></label>
                    <input type="text" x-model="bank.account_number" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm font-mono focus:outline-none focus:border-[#4338ca]" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">IFSC Code <span class="text-red-500">*</span></label>
                    <input type="text" maxlength="11" x-model="bank.ifsc_code" @input="bank.ifsc_code = bank.ifsc_code.toUpperCase()" placeholder="HDFC0001234" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm font-mono uppercase focus:outline-none focus:border-[#4338ca]" required>
                </div>
                
                <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                    <button type="submit" class="px-6 py-3 bg-[#4338ca] text-white font-bold rounded-lg hover:bg-[#3730a3] transition flex items-center gap-2" :disabled="loading">
                        <span x-show="!loading"><i class="fa-solid fa-save"></i> Save Bank Account</span>
                        <span x-show="loading"><i class="fa-solid fa-spinner fa-spin"></i> Saving...</span>
                    </button>
                    <p x-show="bankSuccess" class="text-green-600 text-xs font-bold" x-text="bankSuccess"></p>
                </div>
            </div>
        </form>
    </div>

    <!-- VIEW 5: API Keys -->
    <div x-show="view === 'api_keys'" style="display: none;">
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm max-w-3xl mb-8">
            <h3 class="font-bold text-gray-900 text-lg mb-2">Generate API Token</h3>
            <p class="text-xs text-gray-500 mb-4">Use this token to authenticate API requests from your custom e-commerce store or ERP.</p>
            
            <form @submit.prevent="generateToken" class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
                <input type="text" x-model="newTokenName" placeholder="e.g. Shopify Store Token" class="w-full sm:flex-1 border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-[#4338ca]" required>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-gray-900 text-white font-bold rounded-lg hover:bg-black transition" :disabled="loading">
                    <span x-show="!loading">Generate Key</span>
                    <span x-show="loading"><i class="fa-solid fa-spinner fa-spin"></i></span>
                </button>
            </form>

            <div x-show="generatedToken" class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg" x-transition style="display: none;">
                <p class="text-xs font-bold text-green-800 mb-2">Token generated! Copy it now as it won't be shown again.</p>
                <div class="flex items-center bg-white border border-gray-300 rounded p-2">
                    <code class="flex-1 text-sm font-mono text-gray-800 break-all" x-text="generatedToken"></code>
                    <button type="button" @click="navigator.clipboard.writeText(generatedToken); alert('Copied!')" class="ml-4 px-3 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded hover:bg-gray-200"><i class="fa-regular fa-copy"></i> Copy</button>
                </div>
            </div>
        </div>

        <h3 class="font-bold text-gray-900 mb-4">Active API Tokens</h3>
        <div class="bg-white border border-gray-200 rounded-2xl overflow-x-auto shadow-sm">
                        <div class="overflow-x-auto w-full">
<table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 font-bold uppercase">
                    <tr><th class="px-6 py-3">Token Name</th><th class="px-6 py-3">Created At</th><th class="px-6 py-3 text-right">Action</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php $tokens = Auth::user()->tokens; @endphp
                    @foreach($tokens as $token)
                    <tr class="hover:bg-gray-50" id="token-row-{{ $token->id }}">
                        <td class="px-6 py-4 font-bold text-gray-900"><i class="fa-solid fa-key text-gray-400 mr-2"></i> {{ $token->name }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $token->created_at->format('d M Y, h:i A') }}</td>
                        <td class="px-6 py-4 text-right">
                            <button @click="deleteToken({{ $token->id }})" class="text-red-500 hover:text-red-700 font-bold text-xs"><i class="fa-solid fa-trash"></i> Revoke</button>
                        </td>
                    </tr>
                    @endforeach
                    <template x-for="t in addedTokens" :key="t.name">
                        <tr class="hover:bg-gray-50 bg-blue-50/30">
                            <td class="px-6 py-4 font-bold text-gray-900"><i class="fa-solid fa-key text-gray-400 mr-2"></i> <span x-text="t.name"></span> <span class="bg-green-100 text-green-700 text-[9px] px-1.5 py-0.5 rounded ml-2">NEW</span></td>
                            <td class="px-6 py-4 text-gray-500" x-text="t.created_at"></td>
                            <td class="px-6 py-4 text-right"><span class="text-xs text-gray-400">Created Now</span></td>
                        </tr>
                    </template>
                </tbody>
            </table>
</div>
        </div>
    </div>

    <!-- VIEW 6: Change Password -->
    <div x-show="view === 'password'" style="display: none;" class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm max-w-xl">
        <form @submit.prevent="changePassword">
            <h3 class="font-bold text-gray-900 text-lg mb-2">Change Password</h3>
            <p class="text-xs text-gray-500 mb-6">Enter your current password and choose a strong new password.</p>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Current Password <span class="text-red-500">*</span></label>
                    <input type="password" x-model="pwd.current_password" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-[#4338ca]" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">New Password <span class="text-red-500">*</span></label>
                    <input type="password" x-model="pwd.new_password" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-[#4338ca]" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Confirm New Password <span class="text-red-500">*</span></label>
                    <input type="password" x-model="pwd.new_password_confirmation" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-[#4338ca]" required>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                    <button type="submit" class="px-6 py-3 bg-[#4338ca] text-white font-bold rounded-lg hover:bg-[#3730a3] transition flex items-center gap-2" :disabled="loading">
                        <span x-show="!loading"><i class="fa-solid fa-lock mr-1"></i> Update Password</span>
                        <span x-show="loading"><i class="fa-solid fa-spinner fa-spin"></i> Updating...</span>
                    </button>
                    <p x-show="pwdSuccess" class="text-green-600 text-xs font-bold" x-text="pwdSuccess"></p>
                </div>
            </div>
    </div>
</div>

<script>
function settingsManager() {
    const urlParams = new URLSearchParams(window.location.search);
    const paramView = urlParams.get('view') || 'grid';
    const titles = {
        'kyc': 'KYC Verification',
        'company': 'Company Details',
        'warehouses': 'Pickup Warehouses',
        'bank': 'Bank Account Details',
        'api_keys': 'API Keys & Access Tokens',
        'password': 'Change Password'
    };

    return {
        view: paramView,
        headerTitle: titles[paramView] || 'Settings',
        headerSubtitle: paramView === 'grid' ? 'Manage your business profile, warehouses, bank accounts, and API keys.' : 'Configure your business settings',
        loading: false,
        successMessage: '',
        bankSuccess: '',
        pwdSuccess: '',
        
        // Profile Data
        profile: {
            name: {!! json_encode(Auth::user()->name) !!},
            email: {!! json_encode(Auth::user()->email) !!},
            phone: {!! json_encode(Auth::user()->phone ?? '') !!},
            company_name: {!! json_encode(Auth::user()->company_name ?? '') !!},
            brand_name: {!! json_encode(Auth::user()->brand_name ?? '') !!},
            gstin: {!! json_encode(Auth::user()->gstin ?? '') !!},
            pan_number: {!! json_encode(Auth::user()->pan_number ?? '') !!},
            business_type: {!! json_encode(Auth::user()->business_type ?? '') !!},
            company_address: {!! json_encode(Auth::user()->company_address ?? '') !!},
            company_city: {!! json_encode(Auth::user()->company_city ?? '') !!},
            company_state: {!! json_encode(Auth::user()->company_state ?? '') !!},
            company_pincode: {!! json_encode(Auth::user()->company_pincode ?? '') !!},
        },

        // Bank Account Data
        bank: {
            bank_name: {!! json_encode(Auth::user()->bank_name ?? '') !!},
            account_number: {!! json_encode(Auth::user()->account_number ?? '') !!},
            ifsc_code: {!! json_encode(Auth::user()->ifsc_code ?? '') !!},
            account_holder_name: {!! json_encode(Auth::user()->account_holder_name ?? '') !!},
        },

        // Password Data
        pwd: {
            current_password: '',
            new_password: '',
            new_password_confirmation: ''
        },

        // Warehouses
        showAddWarehouse: false,
        addedWarehouses: [],
        newWh: { name: '', contact_person: '', phone: '', address_1: '', address_2: '', landmark: '', city: '', state: '', pincode: '' },

        // API Keys
        newTokenName: '',
        generatedToken: null,
        addedTokens: [],

        openView(viewName, title, subtitle) {
            this.view = viewName;
            this.headerTitle = title;
            this.headerSubtitle = subtitle;
            this.successMessage = '';
            this.bankSuccess = '';
            this.pwdSuccess = '';
            this.generatedToken = null;
        },

        async updateProfile() {
            this.loading = true;
            this.successMessage = '';
            try {
                let res = await fetch('{{ route('seller.settings.profile') }}', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: (function(p){ let f = new FormData(); for(let k in p){ f.append(k, p[k] || ''); } let img = document.getElementById('avatar-upload'); if(img && img.files[0]) f.append('avatar', img.files[0]); return f; })(this.profile)
                });
                let data = await res.json();
                if(data.success) {
                    this.successMessage = "Company profile saved successfully!";
                } else { alert(data.message || "Failed to update profile"); }
            } catch(e) { alert("Error saving profile"); }
            this.loading = false;
        },

        async updateBank() {
            this.loading = true;
            this.bankSuccess = '';
            try {
                let res = await fetch('{{ route('seller.settings.bank') }}', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(this.bank)
                });
                let data = await res.json();
                if(data.success) {
                    this.bankSuccess = "Bank account updated successfully!";
                } else { alert(data.message || "Failed to update bank details"); }
            } catch(e) { alert("Error saving bank details"); }
            this.loading = false;
        },

        async changePassword() {
            this.loading = true;
            this.pwdSuccess = '';
            try {
                let res = await fetch('{{ route('seller.settings.password') }}', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(this.pwd)
                });
                let data = await res.json();
                if(data.success) {
                    this.pwdSuccess = "Password updated successfully!";
                    this.pwd = { current_password: '', new_password: '', new_password_confirmation: '' };
                } else { alert(data.message || "Failed to update password"); }
            } catch(e) { alert("Error changing password"); }
            this.loading = false;
        },

        async fetchCityForCompany(pincode) {
            if(pincode.length !== 6) return;
            try {
                let res = await fetch(`https://api.postalpincode.in/pincode/${pincode}`);
                let data = await res.json();
                if(data && data[0].Status === 'Success') {
                    let po = data[0].PostOffice[0];
                    this.profile.company_city = po.District;
                    this.profile.company_state = po.State;
                }
            } catch(e) {}
        },

        async fetchCityForWarehouse(pincode) {
            if(pincode.length !== 6) return;
            try {
                let res = await fetch(`https://api.postalpincode.in/pincode/${pincode}`);
                let data = await res.json();
                if(data && data[0].Status === 'Success') {
                    let po = data[0].PostOffice[0];
                    this.newWh.city = po.District;
                    this.newWh.state = po.State;
                }
            } catch(e) {}
        },

        editWarehouse(wh) {
            let addr1 = wh.address || '';
            let addr2 = '';
            let lmark = '-';
            
            // Try to extract landmark if present
            if (addr1.includes('(Landmark: ')) {
                let parts = addr1.split('(Landmark: ');
                addr1 = parts[0].trim();
                if (addr1.endsWith(',')) addr1 = addr1.slice(0, -1);
                lmark = parts[1].replace(')', '').trim();
            }

            this.newWh = { 
                id: wh.id,
                name: wh.name, 
                contact_person: wh.contact_person, 
                phone: wh.phone, 
                address_1: addr1, 
                address_2: addr2, 
                landmark: lmark, 
                city: wh.city, 
                state: wh.state, 
                pincode: wh.pincode 
            };
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        async saveWarehouse() {
            this.loading = true;
            
            let fullAddress = this.newWh.address_1;
            if (this.newWh.address_2) fullAddress += ", " + this.newWh.address_2;
            if (this.newWh.landmark) fullAddress += " (Landmark: " + this.newWh.landmark + ")";
            
            let payload = {
                name: this.newWh.name,
                contact_person: this.newWh.contact_person,
                phone: this.newWh.phone,
                pincode: this.newWh.pincode,
                city: this.newWh.city,
                state: this.newWh.state,
                address: fullAddress
            };

            try {
                let url = this.newWh.id ? `/seller/settings/warehouses/${this.newWh.id}` : '{{ route('seller.settings.warehouses') }}';
                let method = this.newWh.id ? 'PUT' : 'POST';

                let res = await fetch(url, {
                    method: method,
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(payload)
                });
                let data = await res.json();
                if(data.success) {
                    this.resetWhForm(); window.location.reload();
                } else { 
                    let errorMsg = data.message || "Failed to save warehouse";
                    if(data.errors) {
                        errorMsg += "\n" + Object.values(data.errors).flat().join("\n");
                    }
                    alert(errorMsg);
                }
            } catch(e) { alert("Error saving warehouse: " + e.message); }
            this.loading = false;
        },

        async deleteWarehouse(id) {
            if(!confirm("Are you sure you want to delete this pickup warehouse?")) return;
            try {
                let res = await fetch(`/seller/settings/warehouses/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                });
                let data = await res.json();
                if(data.success) {
                    let card = document.getElementById('wh-card-' + id);
                    if(card) card.remove();
                    window.location.reload();
                } else { alert("Failed to delete warehouse"); }
            } catch(e) { alert("Error deleting warehouse"); }
        },

        async setDefaultWarehouse(id) {
            try {
                let res = await fetch(`/seller/settings/warehouses/${id}/default`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                });
                let data = await res.json();
                if(data.success) {
                    window.location.reload();
                } else { alert("Failed to update default location"); }
            } catch(e) { alert("Error setting default warehouse"); }
        },

        async generateToken() {
            this.loading = true;
            this.generatedToken = null;
            try {
                let res = await fetch('{{ route('seller.settings.api-keys') }}', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ token_name: this.newTokenName })
                });
                let data = await res.json();
                if(data.success) {
                    this.generatedToken = data.token;
                    this.addedTokens.unshift({ name: data.name, created_at: data.created_at });
                    this.newTokenName = '';
                } else { alert(data.message || "Error generating token"); }
            } catch(e) { alert("Server error"); }
            this.loading = false;
        },

        async deleteToken(id) {
            if(!confirm("Are you sure you want to revoke this API token?")) return;
            try {
                await fetch(`/settings/api-keys/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                });
                document.getElementById('token-row-'+id).style.display = 'none';
            } catch(e) { alert("Error deleting token"); }
        }
    }
}
</script>
@endsection





