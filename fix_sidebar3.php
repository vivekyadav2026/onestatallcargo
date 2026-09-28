<?php
$content = file_get_contents("resources/views/layouts/hub.blade.php");

$replacement = "
            <a href=\"{{ route('hub.dashboard') }}\" class=\"flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-bold transition\">
                <i class=\"fa-solid fa-qrcode w-5\"></i> Scanner Desk
            </a>
            <a href=\"{{ route('hub.bagging.index') }}\" class=\"flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-bold transition\">
                <i class=\"fa-solid fa-boxes-packing w-5\"></i> Dispatch & Bagging
            </a>
            <a href=\"{{ route('hub.fleet.index') }}\" class=\"flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-bold transition\">
                <i class=\"fa-solid fa-motorcycle w-5\"></i> Fleet Management
            </a>
            <a href=\"{{ route('hub.assignments.pickups') }}\" class=\"flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-bold transition\">
                <i class=\"fa-solid fa-truck-pickup w-5\"></i> Pickup Assignments
            </a>
            <a href=\"{{ route('hub.assignments.deliveries') }}\" class=\"flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-bold transition\">
                <i class=\"fa-solid fa-box-open w-5\"></i> Delivery Assignments
            </a>
            <a href=\"{{ route('hub.ndr.index') }}\" class=\"flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-bold transition\">
                <i class=\"fa-solid fa-rotate-left w-5\"></i> NDR & RTO
            </a>
            
            <div class=\"text-[10px] font-extrabold text-gray-500 uppercase tracking-widest mb-3 ml-2 mt-8\">Business</div>
            
            <a href=\"{{ route('hub.wallet.index') }}\" class=\"flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-bold transition\">
                <i class=\"fa-solid fa-wallet w-5\"></i> Wallet & Payout
            </a>
";

// replace everything from <a href="{{ route('hub.dashboard') }}" down to Pickups & NDR</a>
$content = preg_replace('/<a href="\{\{\s*route\(\'hub\.dashboard\'\)\s*\}\}".*?<i class="fa-solid fa-file-invoice w-5"><\/i> Pickups & NDR\s*<\/a>/s', $replacement, $content);
file_put_contents("resources/views/layouts/hub.blade.php", $content);
