<?php
$directory = __DIR__ . '/resources/views';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

$replacements = [
    '1800-123-4567' => '{{ setting(\'site_phone\', \'1800-123-4567\') }}',
    'support@onestallcargo.com' => '{{ setting(\'site_email\', \'support@onestallcargo.com\') }}',
    '123 Logistics Park, Mumbai, India 400001' => '{{ setting(\'site_address\', \'123 Logistics Park, Mumbai, India 400001\') }}'
];

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $original = $content;
        
        foreach ($replacements as $search => $replace) {
            // don't double replace
            if (strpos($content, $replace) === false) {
                $content = str_replace($search, $replace, $content);
            }
        }
        
        if ($content !== $original) {
            file_put_contents($file->getPathname(), $content);
            echo "Updated: " . $file->getPathname() . "\n";
        }
    }
}
echo "Done.\n";
