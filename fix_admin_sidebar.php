<?php
$file = __DIR__ . '/resources/views/layouts/admin.blade.php';
$content = file_get_contents($file);

$lines = explode("\n", $content);
$newLines = [];
$foundContentMgmt = false;

foreach ($lines as $line) {
    // Keep the one with fa-image
    if (strpos($line, 'fa-rectangle-ad') !== false && strpos($line, 'admin.banners.index') !== false) {
        continue;
    }
    $newLines[] = $line;
}

file_put_contents($file, implode("\n", $newLines));
echo "Removed duplicate sidebar link.\n";
