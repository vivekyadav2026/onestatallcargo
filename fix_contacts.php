<?php
$file = __DIR__ . '/resources/views/admin/contacts/index.blade.php';
$content = file_get_contents($file);

$content = preg_replace('/<x-app-layout>.*?<x-slot name="header">\s*<h2[^>]*>(.*?)<\/h2>\s*<\/x-slot>/is', "@extends('layouts.admin')\n@section('title', 'Sales & Contact Leads - OneStall Cargo')\n@section('content')\n<div class=\"mb-6\">\n    <h1 class=\"text-2xl font-bold text-gray-800\">$1</h1>\n</div>", $content);
$content = str_replace('</x-app-layout>', "\n@endsection", $content);

file_put_contents($file, $content);
echo "Fixed.\n";
