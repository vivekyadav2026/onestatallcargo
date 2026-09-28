<?php
function fixFile($path) {
    if (!file_exists($path)) return;
    $content = file_get_contents($path);
    
    // We know exactly what the string looks like in base64:
    // <span>???{{
    // Let's just find "?" and anything up to "{{" and replace
    $content = preg_replace('/?.*?\{\{/', '?{{', $content);
    
    file_put_contents($path, $content);
}

fixFile("resources/views/rider/dashboard.blade.php");
fixFile("resources/views/rider/cod.blade.php");
