<?php
$files = [
    __DIR__ . '/resources/views/contact.blade.php',
    __DIR__ . '/resources/views/legal.blade.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    $content = preg_replace('/<x-public-layout>/i', "@extends('layouts.public')\n@section('content')", $content);
    $content = str_replace('</x-public-layout>', "@endsection", $content);
    
    file_put_contents($file, $content);
    echo "Fixed: $file\n";
}
