<?php
$file = __DIR__ . '/resources/views/seller/settings.blade.php';
$content = file_get_contents($file);

$newKycForm = <<<'HTML'
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
HTML;

$content = preg_replace('/@if\(!\$userKyc \|\| \$userKyc->status !== \'approved\'\).*?@endif/is', ltrim($newKycForm), $content);

file_put_contents($file, $content);
echo "Fixed.\n";
