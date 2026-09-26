<?php
$file = __DIR__ . '/resources/views/seller/settings.blade.php';
$content = file_get_contents($file);

$newKycForm = <<<'HTML'
          @if(!$userKyc || $userKyc->status !== 'approved')
              <div class="bg-white rounded-3xl border border-gray-100 shadow-xl overflow-hidden relative mt-4">
                  <!-- Decorative header background -->
                  <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-r from-[#eef2ff] to-[#f8fafc] z-0"></div>
                  
                  <div class="relative z-10 p-6 md:p-8">
                      <div class="mb-8">
                          <div class="w-14 h-14 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center text-xl text-[#4338ca] mb-4">
                              <i class="fa-solid fa-file-shield"></i>
                          </div>
                          <h3 class="font-extrabold text-gray-900 text-2xl mb-2">Complete Your KYC</h3>
                          <p class="text-sm text-gray-500 max-w-lg">To unlock live shipping, COD remittances, and full platform access, we need to verify your business identity in compliance with logistics regulations.</p>
                      </div>
      
                      <form action="{{ route('seller.kyc.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6 md:space-y-8">
                          @csrf
                          
                          <!-- Section 1: Business Details -->
                          <div class="bg-gray-50/50 p-5 md:p-6 rounded-2xl border border-gray-100">
                              <h4 class="text-xs font-black text-gray-800 uppercase tracking-widest mb-5 flex items-center"><span class="w-6 h-6 rounded-full bg-[#4338ca] text-white flex items-center justify-center mr-2 text-[10px]">1</span> Business & Tax Details</h4>
                              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                                  <div>
                                      <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1.5">Business Structure</label>
                                      <select name="business_type" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 focus:outline-none focus:border-[#4338ca] focus:ring-2 focus:ring-[#eef2ff] transition" required>
                                          <option value="Individual">Individual / Freelancer</option>
                                          <option value="Sole Proprietorship">Sole Proprietorship</option>
                                          <option value="Private Limited">Private Limited (Pvt Ltd)</option>
                                          <option value="Partnership / LLP">Partnership / LLP</option>
                                      </select>
                                  </div>
                                  <div>
                                      <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1.5">PAN Card Number</label>
                                      <div class="relative">
                                          <i class="fa-regular fa-id-card absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                          <input type="text" maxlength="10" name="pan_number" value="{{ $userKyc->pan_number ?? Auth::user()->pan_number ?? '' }}" placeholder="ABCDE1234F" class="w-full bg-white border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm font-bold uppercase focus:outline-none focus:border-[#4338ca] focus:ring-2 focus:ring-[#eef2ff] transition" required>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <!-- Section 2: Identity Proof -->
                          <div class="bg-gray-50/50 p-5 md:p-6 rounded-2xl border border-gray-100">
                              <h4 class="text-xs font-black text-gray-800 uppercase tracking-widest mb-5 flex items-center"><span class="w-6 h-6 rounded-full bg-[#4338ca] text-white flex items-center justify-center mr-2 text-[10px]">2</span> Address & ID Proof</h4>
                              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                                  <div>
                                      <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1.5">Document Type</label>
                                      <select name="document_type" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 focus:outline-none focus:border-[#4338ca] focus:ring-2 focus:ring-[#eef2ff] transition" required>
                                          <option value="Aadhaar">Aadhaar Card</option>
                                          <option value="Voter ID">Voter ID Card</option>
                                          <option value="Passport">Passport</option>
                                      </select>
                                  </div>
                                  <div>
                                      <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1.5">Document Number</label>
                                      <input type="text" name="document_number" value="{{ $userKyc->document_number ?? '' }}" placeholder="Enter ID Number" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold focus:outline-none focus:border-[#4338ca] focus:ring-2 focus:ring-[#eef2ff] transition" required>
                                  </div>
                              </div>
                          </div>
      
                          <!-- Section 3: Document Uploads -->
                          <div>
                              <h4 class="text-xs font-black text-gray-800 uppercase tracking-widest mb-5 flex items-center"><span class="w-6 h-6 rounded-full bg-[#4338ca] text-white flex items-center justify-center mr-2 text-[10px]">3</span> Upload Documents</h4>
                              
                              <!-- Alpine Component for file uploads -->
                              <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4" x-data="{
                                  files: { id_front: null, id_back: null, pan_doc: null, gst_doc: null },
                                  handleFile(e, type) {
                                      if(e.target.files.length > 0) {
                                          this.files[type] = e.target.files[0].name;
                                      }
                                  }
                              }">
                                  
                                  <!-- ID Front -->
                                  <label class="relative flex flex-col items-center justify-center p-4 md:p-6 bg-white border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:bg-[#eef2ff] hover:border-[#4338ca] transition-all group overflow-hidden text-center">
                                      <input type="file" name="id_front" accept="image/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="handleFile($event, 'id_front')" required>
                                      <div class="w-10 h-10 rounded-full bg-gray-50 group-hover:bg-white text-gray-400 group-hover:text-[#4338ca] flex items-center justify-center mb-2 transition">
                                          <i class="fa-solid fa-id-card"></i>
                                      </div>
                                      <span class="text-[10px] md:text-xs font-bold text-gray-800">ID Front</span>
                                      <span class="text-[9px] md:text-[10px] font-medium text-gray-400 mt-1 truncate w-full px-2" x-text="files.id_front ? files.id_front : 'Tap to upload'"></span>
                                      <div x-show="files.id_front" class="absolute top-2 right-2 text-green-500 text-xs"><i class="fa-solid fa-circle-check"></i></div>
                                  </label>
                                  
                                  <!-- ID Back -->
                                  <label class="relative flex flex-col items-center justify-center p-4 md:p-6 bg-white border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:bg-[#eef2ff] hover:border-[#4338ca] transition-all group overflow-hidden text-center">
                                      <input type="file" name="id_back" accept="image/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="handleFile($event, 'id_back')" required>
                                      <div class="w-10 h-10 rounded-full bg-gray-50 group-hover:bg-white text-gray-400 group-hover:text-[#4338ca] flex items-center justify-center mb-2 transition">
                                          <i class="fa-solid fa-id-card"></i>
                                      </div>
                                      <span class="text-[10px] md:text-xs font-bold text-gray-800">ID Back</span>
                                      <span class="text-[9px] md:text-[10px] font-medium text-gray-400 mt-1 truncate w-full px-2" x-text="files.id_back ? files.id_back : 'Tap to upload'"></span>
                                      <div x-show="files.id_back" class="absolute top-2 right-2 text-green-500 text-xs"><i class="fa-solid fa-circle-check"></i></div>
                                  </label>

                                  <!-- PAN -->
                                  <label class="relative flex flex-col items-center justify-center p-4 md:p-6 bg-white border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:bg-[#eef2ff] hover:border-[#4338ca] transition-all group overflow-hidden text-center">
                                      <input type="file" name="pan_doc" accept="image/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="handleFile($event, 'pan_doc')" required>
                                      <div class="w-10 h-10 rounded-full bg-gray-50 group-hover:bg-white text-gray-400 group-hover:text-[#4338ca] flex items-center justify-center mb-2 transition">
                                          <i class="fa-solid fa-file-invoice"></i>
                                      </div>
                                      <span class="text-[10px] md:text-xs font-bold text-gray-800">PAN Card</span>
                                      <span class="text-[9px] md:text-[10px] font-medium text-gray-400 mt-1 truncate w-full px-2" x-text="files.pan_doc ? files.pan_doc : 'Tap to upload'"></span>
                                      <div x-show="files.pan_doc" class="absolute top-2 right-2 text-green-500 text-xs"><i class="fa-solid fa-circle-check"></i></div>
                                  </label>

                                  <!-- GST -->
                                  <label class="relative flex flex-col items-center justify-center p-4 md:p-6 bg-white border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:bg-[#eef2ff] hover:border-[#4338ca] transition-all group overflow-hidden text-center">
                                      <input type="file" name="gst_doc" accept="image/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="handleFile($event, 'gst_doc')">
                                      <div class="w-10 h-10 rounded-full bg-gray-50 group-hover:bg-white text-gray-400 group-hover:text-[#4338ca] flex items-center justify-center mb-2 transition">
                                          <i class="fa-solid fa-file-signature"></i>
                                      </div>
                                      <span class="text-[10px] md:text-xs font-bold text-gray-800">GST (Opt.)</span>
                                      <span class="text-[9px] md:text-[10px] font-medium text-gray-400 mt-1 truncate w-full px-2" x-text="files.gst_doc ? files.gst_doc : 'Tap to upload'"></span>
                                      <div x-show="files.gst_doc" class="absolute top-2 right-2 text-green-500 text-xs"><i class="fa-solid fa-circle-check"></i></div>
                                  </label>
                                  
                              </div>
                          </div>
      
                          <div class="pt-2 flex justify-end">
                              <button type="submit" class="px-10 py-3.5 bg-[#4338ca] hover:bg-black text-white font-extrabold rounded-xl transition shadow-lg shadow-[#4338ca]/30 flex items-center gap-3 w-full sm:w-auto justify-center">
                                  Submit Documents <i class="fa-solid fa-arrow-right"></i>
                              </button>
                          </div>
                      </form>
                  </div>
              </div>
          @endif
HTML;

$content = preg_replace('/@if\(!\$userKyc \|\| \$userKyc->status !== \'approved\'\).*?@endif/is', ltrim($newKycForm), $content);

file_put_contents($file, $content);
echo "Fixed.\n";
