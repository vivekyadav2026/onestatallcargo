<?php
$file = __DIR__ . '/resources/views/admin/banners/index.blade.php';
$content = file_get_contents($file);

$alpineWrapper = <<<'HTML'
<div class="space-y-6 max-w-4xl mx-auto" x-data="{
    title: '{{ addslashes($banner->title ?? '₹500 FREE Shipping Credits') }}',
    subtitle: '{{ addslashes($banner->subtitle ?? 'are sitting in your wallet.') }}',
    coupon: '{{ addslashes($banner->coupon_code ?? '') }}',
    btnText: '{{ addslashes($banner->button_text ?? 'Get My Free Credits') }}',
    bgClass: '{{ $banner->bg_gradient ?? 'from-[#1d4ed8] via-[#2563eb] to-[#3b82f6]' }}'
}">
HTML;

$content = str_replace('<div class="space-y-6 max-w-4xl mx-auto">', $alpineWrapper, $content);

$previewBlock = <<<'HTML'
        <div class="bg-gradient-to-r rounded-2xl p-6 text-white shadow-md relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6" :class="bgClass">
            <div class="flex items-center gap-6">
                <div class="w-14 h-14 bg-white/20 border border-white/30 rounded-2xl flex items-center justify-center shrink-0 shadow-inner">
                    <i class="fa-solid fa-gift text-2xl text-white"></i>
                </div>
                <div>
                    <h2 class="text-lg md:text-xl font-black tracking-tight text-white mb-1" x-text="title"></h2>
                    <p class="text-xs text-blue-100 font-medium" x-text="subtitle"></p>
                </div>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <div x-show="coupon" class="bg-white text-gray-900 px-3 py-1.5 rounded-xl text-xs font-bold flex items-center gap-2 border border-blue-100">
                    <span class="text-gray-400 font-normal">Use code</span>
                    <span class="font-mono text-blue-700 tracking-wider" x-text="coupon"></span>
                </div>
                <a href="#" class="px-4 py-2 bg-black hover:bg-gray-900 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-1.5">
                    <span x-text="btnText"></span> <i class="fa-solid fa-bolt text-yellow-400"></i>
                </a>
            </div>
        </div>
HTML;

$content = preg_replace('/<div class="bg-gradient-to-r.*?<\/div>\s*<\/div>\s*<\/div>/is', ltrim($previewBlock) . "\n    </div>", $content);

$inputs = [
    'name="title"' => 'name="title" x-model="title"',
    'name="subtitle"' => 'name="subtitle" x-model="subtitle"',
    'name="coupon_code"' => 'name="coupon_code" x-model="coupon"',
    'name="button_text"' => 'name="button_text" x-model="btnText"',
    'name="bg_gradient"' => 'name="bg_gradient" x-model="bgClass"',
];

foreach ($inputs as $find => $replace) {
    $content = str_replace($find, $replace, $content);
}

file_put_contents($file, $content);
echo "Added Alpine logic.\n";
