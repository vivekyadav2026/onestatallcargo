<?php
$file = __DIR__ . '/resources/views/seller/settings.blade.php';
$content = file_get_contents($file);

$newCompanyForm = <<<'HTML'
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
HTML;

$content = preg_replace('/<!-- VIEW 1: Company Profile -->.*?<!-- VIEW 2: KYC Verification -->/is', $newCompanyForm . "\n\n      <!-- VIEW 2: KYC Verification -->", $content);

file_put_contents($file, $content);
echo "Fixed.\n";
