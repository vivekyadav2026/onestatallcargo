<?php
function fixFile($path) {
    if (!file_exists($path)) return;
    $content = file_get_contents($path);
    // Use regex to replace anything before the {{ number_format that looks like weird currency
    $content = preg_replace('/(>|\s)(???|?,?|\xE2\x82\xB9|?\xE2\x80\x9A\xC2\xB9)(?=\{\{)/i', '$1?', $content);
    // Generic fallback for any ???
    $content = str_replace(['???', '?,?'], '?', $content);
    file_put_contents($path, $content);
}

fixFile("resources/views/rider/dashboard.blade.php");
fixFile("resources/views/rider/cod.blade.php");
