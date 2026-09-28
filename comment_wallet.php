<?php
$content = file_get_contents("resources/views/layouts/hub.blade.php");

$target = '<div class="text-[10px] font-extrabold text-gray-500 uppercase tracking-widest mb-3 ml-2 mt-8">Business</div>
            
            <a href="{{ route(\'hub.wallet.index\') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-bold transition">
                <i class="fa-solid fa-wallet w-5"></i> Wallet & Payout
            </a>';
            
$replacement = '<!-- <div class="text-[10px] font-extrabold text-gray-500 uppercase tracking-widest mb-3 ml-2 mt-8">Business</div>
            
            <a href="{{ route(\'hub.wallet.index\') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-bold transition">
                <i class="fa-solid fa-wallet w-5"></i> Wallet & Payout
            </a> -->';

$content = str_replace($target, $replacement, $content);
file_put_contents("resources/views/layouts/hub.blade.php", $content);
