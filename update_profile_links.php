<?php
$file = __DIR__ . '/resources/views/rider/profile.blade.php';
$content = file_get_contents($file);

$content = str_replace('<a href="#" class="flex justify-between items-center p-4 border-b border-gray-100 hover:bg-gray-50">
            <span class="font-bold text-sm text-gray-700"><i class="fa-solid fa-clock-rotate-left mr-2 w-4 text-center"></i> History</span>', '<a href="{{ route(\'rider.history\') }}" class="flex justify-between items-center p-4 border-b border-gray-100 hover:bg-gray-50">
            <span class="font-bold text-sm text-gray-700"><i class="fa-solid fa-clock-rotate-left mr-2 w-4 text-center"></i> History</span>', $content);

$content = str_replace('<a href="#" class="flex justify-between items-center p-4 border-b border-gray-100 hover:bg-gray-50">
            <span class="font-bold text-sm text-gray-700"><i class="fa-solid fa-gear mr-2 w-4 text-center"></i> Settings</span>', '<a href="{{ route(\'rider.settings\') }}" class="flex justify-between items-center p-4 border-b border-gray-100 hover:bg-gray-50">
            <span class="font-bold text-sm text-gray-700"><i class="fa-solid fa-gear mr-2 w-4 text-center"></i> Settings</span>', $content);

file_put_contents($file, $content);
echo "Updated profile view links.\n";
