<?php
$file = 'resources/views/seller/shipments.blade.php';
$content = file_get_contents($file);

$target = '<td class="px-3 py-4 align-top text-right sticky right-0 bg-white group-hover:bg-gray-50 z-10 shadow-[-3px_0_6px_rgba(0,0,0,0.04)]" x-data="{ openMenu: false }">';
$replace = '<td class="px-3 py-4 align-top text-right sticky right-0 bg-white group-hover:bg-gray-50 shadow-[-3px_0_6px_rgba(0,0,0,0.04)]" :class="openMenu ? \'z-[999]\' : \'z-10\'" x-data="{ openMenu: false }">';

$content = str_replace($target, $replace, $content);

// Also widen the dropdown menu to w-52 and right-2 so text is never truncated
$targetMenu = '<div x-show="openMenu" @click.away="openMenu = false" class="absolute right-0 top-9 w-48 bg-white border border-gray-200 rounded-xl shadow-2xl py-2 z-50 text-left" style="display: none;">';
$replaceMenu = '<div x-show="openMenu" @click.away="openMenu = false" class="absolute right-2 top-10 w-52 min-w-[210px] bg-white border border-gray-200 rounded-xl shadow-2xl py-2 z-[9999] text-left" style="display: none;">';

$content = str_replace($targetMenu, $replaceMenu, $content);

file_put_contents($file, $content);
echo "Z-Index and Dropdown width fixed!";
?>
