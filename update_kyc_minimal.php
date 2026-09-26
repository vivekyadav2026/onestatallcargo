<?php
$file = __DIR__ . '/resources/views/seller/settings.blade.php';
$content = file_get_contents($file);

$newKycForm = <<<'HTML'
          @if(!$userKyc || $userKyc->status !== 'approved')
              <div class="bg-white max-w-3xl mt-4">
                  <div class="mb-12">
                      <h3 class="font-semibold text-gray-900 text-2xl tracking-tight mb-2">KYC Verification</h3>
                      <p class="text-sm text-gray-500">Please provide your business and identity documents to comply with shipping regulations.</p>
                  </div>
      
                  <form action="{{ route('seller.kyc.submit') }}" method="POST" enctype="multipart/form-data">
                      @csrf
                      
                      <!-- 01. Business Details -->
                      <div class="mb-12">
                          <h4 class="text-sm font-semibold text-gray-900 mb-6 flex items-center tracking-wide">
                              <span class="text-gray-400 mr-3 font-normal">01</span> Business Information
                          </h4>
                          <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                              <div>
                                  <label class="block text-[13px] text-gray-600 mb-2">Business Structure</label>
                                  <select name="business_type" class="w-full bg-transparent border-0 border-b border-gray-200 py-2 px-0 text-sm text-gray-900 focus:ring-0 focus:border-black transition-colors rounded-none" required>
                                      <option value="Individual">Individual / Freelancer</option>
                                      <option value="Sole Proprietorship">Sole Proprietorship</option>
                                      <option value="Private Limited">Private Limited (Pvt Ltd)</option>
                                      <option value="Partnership / LLP">Partnership / LLP</option>
                                  </select>
                              </div>
                              <div>
                                  <label class="block text-[13px] text-gray-600 mb-2">PAN Card Number</label>
                                  <input type="text" maxlength="10" name="pan_number" value="{{ $userKyc->pan_number ?? Auth::user()->pan_number ?? '' }}" placeholder="ABCDE1234F" class="w-full bg-transparent border-0 border-b border-gray-200 py-2 px-0 text-sm text-gray-900 uppercase focus:ring-0 focus:border-black transition-colors rounded-none" required>
                              </div>
                          </div>
                      </div>

                      <!-- 02. Identity Proof -->
                      <div class="mb-12">
                          <h4 class="text-sm font-semibold text-gray-900 mb-6 flex items-center tracking-wide">
                              <span class="text-gray-400 mr-3 font-normal">02</span> Identity Verification
                          </h4>
                          <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                              <div>
                                  <label class="block text-[13px] text-gray-600 mb-2">Document Type</label>
                                  <select name="document_type" class="w-full bg-transparent border-0 border-b border-gray-200 py-2 px-0 text-sm text-gray-900 focus:ring-0 focus:border-black transition-colors rounded-none" required>
                                      <option value="Aadhaar">Aadhaar Card</option>
                                      <option value="Voter ID">Voter ID Card</option>
                                      <option value="Passport">Passport</option>
                                  </select>
                              </div>
                              <div>
                                  <label class="block text-[13px] text-gray-600 mb-2">Document Number</label>
                                  <input type="text" name="document_number" value="{{ $userKyc->document_number ?? '' }}" placeholder="Enter ID Number" class="w-full bg-transparent border-0 border-b border-gray-200 py-2 px-0 text-sm text-gray-900 focus:ring-0 focus:border-black transition-colors rounded-none" required>
                              </div>
                          </div>
                      </div>
      
                      <!-- 03. Document Uploads -->
                      <div class="mb-12">
                          <h4 class="text-sm font-semibold text-gray-900 mb-6 flex items-center tracking-wide">
                              <span class="text-gray-400 mr-3 font-normal">03</span> Upload Documents
                          </h4>
                          
                          <div class="grid grid-cols-2 md:grid-cols-4 gap-4" x-data="{
                              files: { id_front: null, id_back: null, pan_doc: null, gst_doc: null },
                              handleFile(e, type) {
                                  if(e.target.files.length > 0) {
                                      this.files[type] = e.target.files[0].name;
                                  }
                              }
                          }">
                              
                              <!-- ID Front -->
                              <label class="relative flex flex-col items-center justify-center p-6 bg-white border border-dashed border-gray-300 rounded cursor-pointer hover:border-gray-900 hover:bg-gray-50 transition-all group overflow-hidden">
                                  <input type="file" name="id_front" accept="image/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="handleFile($event, 'id_front')" required>
                                  <i class="fa-solid fa-arrow-up-from-bracket text-gray-300 mb-3 group-hover:text-gray-900 transition-colors"></i>
                                  <span class="text-[11px] font-medium text-gray-900">ID Front</span>
                                  <span class="text-[10px] text-gray-400 mt-1 truncate w-full text-center px-2" x-text="files.id_front ? files.id_front : 'Select file'"></span>
                                  <div x-show="files.id_front" class="absolute top-2 right-2 text-gray-900 text-[10px]"><i class="fa-solid fa-check"></i></div>
                              </label>
                              
                              <!-- ID Back -->
                              <label class="relative flex flex-col items-center justify-center p-6 bg-white border border-dashed border-gray-300 rounded cursor-pointer hover:border-gray-900 hover:bg-gray-50 transition-all group overflow-hidden">
                                  <input type="file" name="id_back" accept="image/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="handleFile($event, 'id_back')" required>
                                  <i class="fa-solid fa-arrow-up-from-bracket text-gray-300 mb-3 group-hover:text-gray-900 transition-colors"></i>
                                  <span class="text-[11px] font-medium text-gray-900">ID Back</span>
                                  <span class="text-[10px] text-gray-400 mt-1 truncate w-full text-center px-2" x-text="files.id_back ? files.id_back : 'Select file'"></span>
                                  <div x-show="files.id_back" class="absolute top-2 right-2 text-gray-900 text-[10px]"><i class="fa-solid fa-check"></i></div>
                              </label>

                              <!-- PAN -->
                              <label class="relative flex flex-col items-center justify-center p-6 bg-white border border-dashed border-gray-300 rounded cursor-pointer hover:border-gray-900 hover:bg-gray-50 transition-all group overflow-hidden">
                                  <input type="file" name="pan_doc" accept="image/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="handleFile($event, 'pan_doc')" required>
                                  <i class="fa-solid fa-arrow-up-from-bracket text-gray-300 mb-3 group-hover:text-gray-900 transition-colors"></i>
                                  <span class="text-[11px] font-medium text-gray-900">PAN Card</span>
                                  <span class="text-[10px] text-gray-400 mt-1 truncate w-full text-center px-2" x-text="files.pan_doc ? files.pan_doc : 'Select file'"></span>
                                  <div x-show="files.pan_doc" class="absolute top-2 right-2 text-gray-900 text-[10px]"><i class="fa-solid fa-check"></i></div>
                              </label>

                              <!-- GST -->
                              <label class="relative flex flex-col items-center justify-center p-6 bg-white border border-dashed border-gray-300 rounded cursor-pointer hover:border-gray-900 hover:bg-gray-50 transition-all group overflow-hidden">
                                  <input type="file" name="gst_doc" accept="image/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="handleFile($event, 'gst_doc')">
                                  <i class="fa-solid fa-arrow-up-from-bracket text-gray-300 mb-3 group-hover:text-gray-900 transition-colors"></i>
                                  <span class="text-[11px] font-medium text-gray-900">GST (Opt.)</span>
                                  <span class="text-[10px] text-gray-400 mt-1 truncate w-full text-center px-2" x-text="files.gst_doc ? files.gst_doc : 'Select file'"></span>
                                  <div x-show="files.gst_doc" class="absolute top-2 right-2 text-gray-900 text-[10px]"><i class="fa-solid fa-check"></i></div>
                              </label>
                          </div>
                      </div>
      
                      <div class="pt-6">
                          <button type="submit" class="px-8 py-3 bg-gray-900 text-white text-sm font-medium hover:bg-black transition rounded w-full sm:w-auto">
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
