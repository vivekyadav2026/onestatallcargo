<?php
$file = __DIR__ . '/resources/views/seller/settings.blade.php';
$content = file_get_contents($file);

$alerts = <<<'HTML'
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
HTML;

$content = preg_replace('/@if\(\$userKyc && \$userKyc->status === \'approved\'\).*?@endif/is', ltrim($alerts), $content);

file_put_contents($file, $content);
echo "Fixed alerts.\n";
