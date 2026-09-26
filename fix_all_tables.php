<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/resources/views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.blade\.php$/', RegexIterator::GET_MATCH);

$count = 0;
foreach($files as $fileList) {
    $path = $fileList[0];
    $content = file_get_contents($path);
    
    // Find all tables that are NOT immediately preceded by a div with overflow-x-auto
    // We can do this safely by simply injecting a wrapper around ANY table, but if a table is ALREADY wrapped, we shouldn't wrap it again.
    // Instead of regex hacking, let's just use regex to find `<table` and the nearest preceding `<div class="..."`.
    // Actually, it's safer to just regex wrap `<table ...> ... </table>` IF it's not already inside an overflow-x-auto div.

    $modified = preg_replace_callback(
        '/(<div[^>]*class="([^"]*)"[^>]*>\s*)(<table.*?<\/table>)/is',
        function ($matches) {
            $divOpening = $matches[1];
            $classes = $matches[2];
            $tableContent = $matches[3];

            if (strpos($classes, 'overflow-x-auto') === false) {
                // Return the original div, but wrap the table inside it with an overflow-x-auto div
                return $divOpening . '<div class="overflow-x-auto w-full">' . "\n" . $tableContent . "\n" . '</div>';
            }
            // Already has overflow-x-auto
            return $matches[0];
        },
        $content
    );

    // Some tables might not be immediately inside a div (maybe preceded by a heading). 
    // It's safer to find the table and just wrap it directly.
    $modified2 = preg_replace_callback(
        '/<table\b[^>]*>(.*?)<\/table>/is',
        function ($matches) use ($content) {
            $tableFull = $matches[0];
            
            // Look a little bit before the table in the original content to see if there's an overflow-x-auto div
            $pos = strpos($content, $tableFull);
            if ($pos !== false) {
                $substrBefore = substr($content, max(0, $pos - 100), 100);
                if (preg_match('/<div[^>]*class="[^"]*overflow-x-auto[^"]*"[^>]*>\s*$/is', $substrBefore)) {
                    return $tableFull; // already wrapped
                }
            }
            
            return '<div class="overflow-x-auto w-full">' . "\n" . $tableFull . "\n" . '</div>';
        },
        $content
    );

    if ($content !== $modified2) {
        file_put_contents($path, $modified2);
        echo "Wrapped table in: $path\n";
        $count++;
    }
}
echo "Total fixed: $count\n";
