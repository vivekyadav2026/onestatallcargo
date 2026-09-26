<?php
$file = __DIR__ . '/resources/views/seller/settings.blade.php';
$content = file_get_contents($file);

$alerts = <<<'HTML'
          @if($userKyc && $userKyc->status === 'approved')
              <div class="border border-gray-200 rounded p-6 mb-8 flex items-start sm:items-center justify-between bg-white max-w-3xl mt-4">
                  <div class="flex items-center gap-4">
                      <div class="w-10 h-10 border border-gray-200 rounded flex items-center justify-center text-gray-900 shrink-0">
                          <i class="fa-solid fa-check"></i>
                      </div>
                      <div>
                          <h2 class="text-sm font-semibold text-gray-900">KYC Verified</h2>
                          <p class="text-xs text-gray-500 mt-1">Your identity documents are verified. All platform features are unlocked.</p>
                      </div>
                  </div>
              </div>
          @elseif($userKyc && $userKyc->status === 'pending')
              <div class="border border-gray-200 rounded p-6 mb-8 flex items-start sm:items-center justify-between bg-white max-w-3xl mt-4">
                  <div class="flex items-center gap-4">
                      <div class="w-10 h-10 border border-gray-200 rounded flex items-center justify-center text-gray-900 shrink-0">
                          <i class="fa-solid fa-clock"></i>
                      </div>
                      <div>
                          <h2 class="text-sm font-semibold text-gray-900">Verification Pending</h2>
                          <p class="text-xs text-gray-500 mt-1">Our compliance team is reviewing your submitted documents.</p>
                      </div>
                  </div>
              </div>
          @endif
HTML;

$content = preg_replace('/@if\(\$userKyc && \$userKyc->status === \'approved\'\).*?@endif/is', ltrim($alerts), $content);

file_put_contents($file, $content);
echo "Fixed alerts.\n";
