<?php
$directory = new RecursiveDirectoryIterator("resources/views");
$iterator = new RecursiveIteratorIterator($directory);
$regex = new RegexIterator($iterator, '/^.+\.blade\.php$/i', RecursiveRegexIterator::GET_MATCH);

$badStrings = [
    urldecode('%C3%A2%E2%80%9A%C2%B9'), // ???
    urldecode('%C3%A2%2C%C2%B9'),       // ?,?
    '???',
    '?,?'
];

foreach ($regex as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    $changed = false;
    
    foreach ($badStrings as $bad) {
        if (strpos($content, $bad) !== false) {
            $content = str_replace($bad, '?', $content);
            $changed = true;
        }
    }
    
    // Also replace using raw bytes if needed
    $broken1 = "\xc3\xa2\xe2\x80\x9a\xc2\xb9";
    $broken2 = "\xc3\xa2\x2c\xc2\xb9";
    
    if (strpos($content, $broken1) !== false) {
        $content = str_replace($broken1, '?', $content);
        $changed = true;
    }
    if (strpos($content, $broken2) !== false) {
        $content = str_replace($broken2, '?', $content);
        $changed = true;
    }

    if ($changed) {
        file_put_contents($path, $content);
        echo "Fixed: $path\n";
    }
}
