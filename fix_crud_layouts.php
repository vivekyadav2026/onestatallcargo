<?php

function fixLayout($file, $title) {
    $content = file_get_contents($file);
    
    // Replace <x-app-layout> and slot with @extends
    $content = preg_replace('/<x-app-layout>.*?<x-slot name="header">\s*<div[^>]*>\s*<h2[^>]*>(.*?)<\/h2>\s*(<a[^>]*>.*?<\/a>)?\s*<\/div>\s*<\/x-slot>/is', "@extends('layouts.admin')\n@section('title', '$title - OneStall Cargo')\n@section('content')\n<div class=\"flex justify-between items-center mb-6\">\n    <h1 class=\"text-2xl font-bold text-gray-800\">$1</h1>\n    $2\n</div>", $content);
    
    $content = preg_replace('/<x-app-layout>.*?<x-slot name="header">\s*<h2[^>]*>(.*?)<\/h2>\s*<\/x-slot>/is', "@extends('layouts.admin')\n@section('title', '$title - OneStall Cargo')\n@section('content')\n<div class=\"mb-6\">\n    <h1 class=\"text-2xl font-bold text-gray-800\">$1</h1>\n</div>", $content);
    
    $content = str_replace('</x-app-layout>', "\n@endsection", $content);
    
    file_put_contents($file, $content);
    echo "Fixed: $file\n";
}

fixLayout(__DIR__ . '/resources/views/admin/testimonials/index.blade.php', 'Testimonials');
fixLayout(__DIR__ . '/resources/views/admin/testimonials/create.blade.php', 'Create Testimonial');
fixLayout(__DIR__ . '/resources/views/admin/testimonials/edit.blade.php', 'Edit Testimonial');
fixLayout(__DIR__ . '/resources/views/admin/settings/index.blade.php', 'Global Settings');
fixLayout(__DIR__ . '/resources/views/admin/settings/create.blade.php', 'Create Setting');
fixLayout(__DIR__ . '/resources/views/admin/settings/edit.blade.php', 'Edit Setting');

echo "All layouts fixed.\n";
