<?php
$file = __DIR__ . '/resources/views/layouts/seller.blade.php';
$content = file_get_contents($file);

// Fix logo alignment condition
$content = preg_replace('/:class="\(\s*sidebarHover\s*\|\|\s*isPinned\s*\)\s*\?\s*\'justify-start\'\s*:\s*\'justify-center\'"/', ':class="(sidebarHover || isPinned || mobileSidebarOpen) ? \'justify-start\' : \'justify-center\'"', $content);

// Fix logo size condition
$content = preg_replace('/:class="\(\s*sidebarHover\s*\|\|\s*isPinned\s*\)\s*\?\s*\'h-10\s*max-w-\[140px\]\'\s*:\s*\'h-8\s*w-8\'"/', ':class="(sidebarHover || isPinned || mobileSidebarOpen) ? \'h-10 max-w-[140px]\' : \'h-8 w-8\'"', $content);

// Fix all nav text spans
$content = preg_replace('/x-show="sidebarHover\s*\|\|\s*isPinned"/', 'x-show="sidebarHover || isPinned || mobileSidebarOpen"', $content);

file_put_contents($file, $content);
echo "Fixed sidebar logic.\n";
