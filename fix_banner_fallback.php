<?php
$file = __DIR__ . '/resources/views/seller/dashboard.blade.php';
$content = file_get_contents($file);

$content = preg_replace('/@elseif\(!\$walletDone\).*?@endif/is', '@endif', $content);

file_put_contents($file, $content);
echo "Removed fallback banner.\n";
