<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/resources/views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.blade\.php$/', RegexIterator::GET_MATCH);

foreach($files as $fileList) {
    $path = $fileList[0];
    $content = file_get_contents($path);
    
    // Replace overflow-hidden with overflow-x-auto where a table is directly inside
    $modified = preg_replace('/<div class="([^"]*)overflow-hidden([^"]*)">\s*<table/is', '<div class="$1overflow-x-auto$2">' . "\n" . '                        <table', $content);
    
    // Replace overflow-y-auto max-h-[500px] with overflow-x-auto overflow-y-auto max-h-[500px]
    $modified = preg_replace('/<div class="([^"]*)overflow-y-auto([^"]*)">\s*<table/is', '<div class="$1overflow-x-auto overflow-y-auto$2">' . "\n" . '                        <table', $modified);
    
    if ($content !== $modified) {
        file_put_contents($path, $modified);
        echo "Fixed tables in: $path\n";
    }
}
