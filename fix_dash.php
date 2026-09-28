<?php
$content = file_get_contents("resources/views/hub/dashboard.blade.php");
$content = str_replace(
    '{{ $scan->rider_id ? \App\Models\User::find($scan->rider_id)->name : \'-\' }}',
    '{{ $scan->rider_id ? optional(\App\Models\User::find($scan->rider_id))->name ?? \'Deleted Rider\' : \'-\' }}',
    $content
);
file_put_contents("resources/views/hub/dashboard.blade.php", $content);
