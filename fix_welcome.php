<?php
$file = __DIR__ . '/resources/views/welcome.blade.php';
$content = file_get_contents($file);

$replacements = [
    'eCommerce Shipping' => '{{ setting(\'hero_title1\', \'eCommerce Shipping\') }}',
    'Built for the Bold' => '{{ setting(\'hero_title2\', \'Built for the Bold\') }}',
    'One platform to ship, track, and manage all your B2C and B2B orders globally.' => '{{ setting(\'hero_subtitle\', \'One platform to ship, track, and manage all your B2C and B2B orders globally.\') }}'
];

foreach ($replacements as $search => $replace) {
    if (strpos($content, $replace) === false) {
        $content = str_replace($search, $replace, $content);
    }
}

file_put_contents($file, $content);
echo "Done.\n";
