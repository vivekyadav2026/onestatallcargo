<?php
$content = file_get_contents("resources/views/admin/riders/index.blade.php");

$content = preg_replace(
    '/\{\{\s*\$franchise->user->company_name\s*\?\?\s*\$franchise->user->name\s*\}\}/',
    '{{ optional($franchise->user)->company_name ?? optional($franchise->user)->name ?? \'Orphan Franchise\' }}',
    $content
);

file_put_contents("resources/views/admin/riders/index.blade.php", $content);
