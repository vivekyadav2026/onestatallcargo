<?php
$content = file_get_contents("resources/views/admin/riders/index.blade.php");

$content = str_replace(
    '{{ $rider->user->phone }}',
    '{{ optional($rider->user)->phone }}',
    $content
);

file_put_contents("resources/views/admin/riders/index.blade.php", $content);
