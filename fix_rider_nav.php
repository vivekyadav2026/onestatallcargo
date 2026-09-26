<?php
$file = __DIR__ . '/resources/views/layouts/rider.blade.php';
$content = file_get_contents($file);

$nav = <<<HTML
    <!-- Bottom Nav -->
    <nav class="fixed bottom-0 w-full bg-white border-t border-gray-200 flex justify-around p-3 pb-safe z-50">
        <a href="{{ route('rider.dashboard') }}" class="flex flex-col items-center {{ request()->routeIs('rider.dashboard') ? 'text-[#D4AF37]' : 'text-gray-400' }}"><i class="fa-solid fa-list-check text-xl mb-1"></i><span class="text-[10px] font-bold">Tasks</span></a>
        <a href="{{ route('rider.scan') }}" class="flex flex-col items-center {{ request()->routeIs('rider.scan') ? 'text-[#D4AF37]' : 'text-gray-400' }}"><i class="fa-solid fa-camera text-xl mb-1"></i><span class="text-[10px] font-bold">Scan</span></a>
        <a href="{{ route('rider.cod') }}" class="flex flex-col items-center {{ request()->routeIs('rider.cod') ? 'text-[#D4AF37]' : 'text-gray-400' }}"><i class="fa-solid fa-wallet text-xl mb-1"></i><span class="text-[10px] font-bold">COD</span></a>
        <a href="{{ route('rider.profile') }}" class="flex flex-col items-center {{ request()->routeIs('rider.profile') ? 'text-[#D4AF37]' : 'text-gray-400' }}"><i class="fa-solid fa-user text-xl mb-1"></i><span class="text-[10px] font-bold">Profile</span></a>
    </nav>
HTML;

$content = preg_replace('/<!-- Bottom Nav -->.*?<\/nav>/is', $nav, $content);

file_put_contents($file, $content);
echo "Updated Rider Bottom Nav.\n";
