@extends('layouts.seller')
@section('title', 'Settings - OneStall Cargo')

@section('content')
<div class="space-y-8 max-w-[1200px]" x-data="settingsManager()">
    
    <!-- Dynamic Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <button @click="openView('company', 'Company Details', 'Manage business name, GSTIN, PAN, and brand settings')" class="block text-left bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-[#4338ca] transition group">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#4338ca] flex items-center justify-center mb-3 text-lg group-hover:bg-[#4338ca] group-hover:text-white transition-colors">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-base mb-1 group-hover:text-[#4338ca]">Company Details</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Update your GSTIN, PAN, brand name, and registered address</p>
                </button>

                <button @click="openView('kyc', 'KYC Verification', 'Upload identity & tax documents for compliance')" class="block text-left bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-[#4338ca] transition group relative">
                    @php $userKyc = Auth::user()->kyc; @endphp
                    @if($userKyc && $userKyc->status === 'approved')
              <div class="bg-green-50/60 border border-green-200 rounded-2xl p-6 mb-8 flex items-start sm:items-center justify-between shadow-sm max-w-4xl mt-4">
                  <div class="flex items-center gap-4">
                      <div class="w-12 h-12 bg-green-100 text-green-700 rounded-xl flex items-center justify-center text-xl shrink-0 border border-green-200">
                          <i class="fa-solid fa-shield-check"></i>
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
                  <div class="flex items-center gap-4">
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
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mb-3 text-lg group-hover:bg-purple-600 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-id-card"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-base mb-1 group-hover:text-[#4338ca]">KYC Verification</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Submit Aadhaar, PAN, and GST documents for shipping activation</p>
                </button>
            </div>
        </div>

        <!-- Category 2: Logistics & Operations -->
        <div class="mb-8">
            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2"><i class="fa-solid fa-warehouse"></i> LOGISTICS & PAYOUTS</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <button @click="openView('warehouses', 'Pickup Warehouses', 'Manage locations where couriers will pick up your parcels')" class="block text-left bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-[#4338ca] transition group">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-3 text-lg group-hover:bg-amber-600 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-base mb-1 group-hover:text-[#4338ca]">Pickup Warehouses</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Add and manage pickup hub locations with auto pincode lookup</p>
                </button>

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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                  <div class="flex items-center gap-4">
                      <div class="w-12 h-12 bg-green-100 text-green-700 rounded-xl flex items-center justify-center text-xl shrink-0 border border-green-200">
                          <i class="fa-solid fa-shield-check"></i>
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
                  <div class="flex items-center gap-4">
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
                      <div class="flex items-center gap-3 mb-2">
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
                          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
    <div x-show="view === 'warehouses'" style="display: none;">
        
        <!-- Add Warehouse Drawer/Modal -->
        <div x-show="showAddWarehouse" class="bg-white p-6 rounded-2xl border border-gray-200 shadow-lg mb-8" x-transition style="display: none;">
            <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
                <h3 class="font-bold text-gray-900 text-lg flex items-center gap-2"><i class="fa-solid fa-plus text-[#4338ca]"></i> Add New Pickup Warehouse</h3>
                <button type="button" @click="showAddWarehouse = false" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form @submit.prevent="saveWarehouse" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label class="text-xs font-bold text-gray-700">Warehouse Name / Label</label><input type="text" placeholder="e.g. Primary Delhi Hub" x-model="newWh.name" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 mt-1 text-sm outline-none focus:border-[#4338ca]" required></div>
                <div><label class="text-xs font-bold text-gray-700">Contact Person Name</label><input type="text" placeholder="Full Name" x-model="newWh.contact_person" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 mt-1 text-sm outline-none focus:border-[#4338ca]" required></div>
                <div><label class="text-xs font-bold text-gray-700">Mobile Phone</label><input type="text" maxlength="10" placeholder="10-digit Phone" x-model="newWh.phone" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 mt-1 text-sm outline-none focus:border-[#4338ca]" required></div>
                <div><label class="text-xs font-bold text-gray-700">Pincode (Auto-Detect City/State)</label><input type="text" maxlength="6" placeholder="6-digit PIN" x-model="newWh.pincode" @input="fetchCityForWarehouse(newWh.pincode)" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 mt-1 text-sm outline-none focus:border-[#4338ca]" required></div>
                <div class="md:col-span-2"><label class="text-xs font-bold text-gray-700">Complete Address</label><textarea x-model="newWh.address" placeholder="Building, Street, Landmark details" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 mt-1 text-sm outline-none focus:border-[#4338ca]" rows="2" required></textarea></div>
                <div><label class="text-xs font-bold text-gray-700">City</label><input type="text" x-model="newWh.city" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 mt-1 text-sm outline-none focus:border-[#4338ca]" required></div>
                <div><label class="text-xs font-bold text-gray-700">State</label><input type="text" x-model="newWh.state" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 mt-1 text-sm outline-none focus:border-[#4338ca]" required></div>
                
                <div class="md:col-span-2 flex justify-end gap-3 mt-2 pt-3 border-t border-gray-100">
                    <button type="button" @click="showAddWarehouse = false" class="px-5 py-2.5 border border-gray-300 text-gray-700 font-bold rounded-lg text-xs">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 bg-[#4338ca] text-white font-bold rounded-lg hover:bg-[#3730a3] transition text-xs shadow-md" :disabled="loading">
                        <span x-show="!loading">Save Pickup Location</span>
                        <span x-show="loading"><i class="fa-solid fa-spinner fa-spin"></i> Saving...</span>
                    </button>
                </div>
            </form>
        </div>

        @php $warehouses = \App\Models\Warehouse::where('user_id', Auth::id())->get(); @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($warehouses as $wh)
                <div class="bg-white p-6 rounded-2xl border {{ $wh->is_default ? 'border-[#4338ca] ring-1 ring-[#4338ca]' : 'border-gray-200' }} shadow-sm relative flex flex-col justify-between" id="wh-card-{{ $wh->id }}">
                    <div>
                        <div class="flex justify-between items-start mb-3">
                            <h4 class="font-bold text-gray-900 text-base flex items-center gap-2">
                                <i class="fa-solid fa-warehouse text-[#4338ca]"></i> {{ $wh->name }}
                            </h4>
                            @if($wh->is_default)
                                <span class="bg-green-100 text-green-800 text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-full"><i class="fa-solid fa-star"></i> Default Location</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-600 font-semibold mb-1"><i class="fa-regular fa-user w-4 text-gray-400"></i> {{ $wh->contact_person }} ({{ $wh->phone }})</p>
                        <p class="text-xs text-gray-500 leading-relaxed"><i class="fa-solid fa-location-dot w-4 text-gray-400"></i> {{ $wh->address }}, {{ $wh->city }}, {{ $wh->state }} - <span class="font-bold text-gray-800 font-mono">{{ $wh->pincode }}</span></p>
                    </div>

                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                        @if(!$wh->is_default)
                            <button @click="setDefaultWarehouse({{ $wh->id }})" class="text-[#4338ca] hover:underline font-bold text-xs"><i class="fa-regular fa-star"></i> Set as Default</button>
                        @else
                            <span class="text-xs text-gray-400 font-bold">Primary Location</span>
                        @endif

                        <button @click="deleteWarehouse({{ $wh->id }})" class="text-red-500 hover:text-red-700 font-bold text-xs"><i class="fa-solid fa-trash mr-1"></i> Delete</button>
                    </div>
                </div>
            @empty
                <div class="md:col-span-2 bg-white p-12 text-center rounded-2xl border border-gray-200">
                    <div class="w-16 h-16 mx-auto bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-4 border border-gray-100">
                        <i class="fa-solid fa-warehouse text-2xl"></i>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1">No Pickup Warehouses Found</h3>
                    <p class="text-sm text-gray-500 font-medium mb-4">Add a pickup location so couriers know where to collect your packages.</p>
                    <button @click="showAddWarehouse = true" class="px-5 py-2.5 bg-[#4338ca] text-white font-bold text-xs rounded-xl shadow-md"><i class="fa-solid fa-plus mr-1"></i> Add Your First Warehouse</button>
                </div>
            @endforelse

            <template x-for="wh in addedWarehouses" :key="wh.id">
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm relative flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-3">
                            <h4 class="font-bold text-gray-900 text-base flex items-center gap-2"><i class="fa-solid fa-warehouse text-[#4338ca]"></i> <span x-text="wh.name"></span></h4>
                            <span x-show="wh.is_default" class="bg-green-100 text-green-800 text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-full"><i class="fa-solid fa-star"></i> Default</span>
                        </div>
                        <p class="text-xs text-gray-600 font-semibold mb-1"><i class="fa-regular fa-user w-4 text-gray-400"></i> <span x-text="wh.contact_person"></span> (<span x-text="wh.phone"></span>)</p>
                        <p class="text-xs text-gray-500 leading-relaxed"><i class="fa-solid fa-location-dot w-4 text-gray-400"></i> <span x-text="wh.address"></span>, <span x-text="wh.city"></span>, <span x-text="wh.state"></span> - <span class="font-bold text-gray-800 font-mono" x-text="wh.pincode"></span></p>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- VIEW 4: Bank Account for COD Remittance -->
    <div x-show="view === 'bank'" style="display: none;" class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm max-w-2xl">
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
        <div class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm max-w-3xl mb-8">
            <h3 class="font-bold text-gray-900 text-lg mb-2">Generate API Token</h3>
            <p class="text-xs text-gray-500 mb-4">Use this token to authenticate API requests from your custom e-commerce store or ERP.</p>
            
            <form @submit.prevent="generateToken" class="flex items-center gap-4">
                <input type="text" x-model="newTokenName" placeholder="e.g. Shopify Store Token" class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-[#4338ca]" required>
                <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white font-bold rounded-lg hover:bg-black transition" :disabled="loading">
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
    <div x-show="view === 'password'" style="display: none;" class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm max-w-xl">
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
        newWh: { name: '', contact_person: '', phone: '', address: '', city: '', state: '', pincode: '' },

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
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(this.profile)
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
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
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
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
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

        async saveWarehouse() {
            this.loading = true;
            try {
                let res = await fetch('{{ route('seller.settings.warehouses') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(this.newWh)
                });
                let data = await res.json();
                if(data.success) {
                    this.addedWarehouses.push(data.warehouse);
                    this.showAddWarehouse = false;
                    this.newWh = { name: '', contact_person: '', phone: '', address: '', city: '', state: '', pincode: '' };
                } else { alert(data.message || "Failed to save warehouse"); }
            } catch(e) { alert("Error saving warehouse"); }
            this.loading = false;
        },

        async deleteWarehouse(id) {
            if(!confirm("Are you sure you want to delete this pickup warehouse?")) return;
            try {
                let res = await fetch(`/seller/settings/warehouses/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
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
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
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
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
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
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                document.getElementById('token-row-'+id).style.display = 'none';
            } catch(e) { alert("Error deleting token"); }
        }
    }
}
</script>
@endsection
