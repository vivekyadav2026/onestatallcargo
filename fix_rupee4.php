<?php
$files = ["resources/views/rider/dashboard.blade.php", "resources/views/rider/cod.blade.php"];

foreach ($files as $path) {
    if (!file_exists($path)) continue;
    $content = file_get_contents($path);
    
    // Replace by finding the specific number_format calls and replacing the chunk before them
    $content = preg_replace('/>[^<]*?\{\{\s*number_format/s', '>?{{ number_format', $content);
    
    file_put_contents($path, $content);
}
