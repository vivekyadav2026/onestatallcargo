<?php
$content = file_get_contents("resources/views/admin/riders/index.blade.php");

// Fix Franchise option loop
$content = str_replace(
    '{{ $franchise->user->company_name ?? $franchise->user->name }} ({{ $franchise->city }})',
    '{{ optional($franchise->user)->company_name ?? optional($franchise->user)->name ?? \'Orphan Franchise\' }} ({{ $franchise->city ?? \'No City\' }})',
    $content
);

// Fix Rider loop franchise display
$content = str_replace(
    '{{ $rider->franchise->user->company_name ?? $rider->franchise->user->name ?? \'Internal / Global\' }}',
    '{{ $rider->franchise ? (optional($rider->franchise->user)->company_name ?? optional($rider->franchise->user)->name ?? \'Orphan\') : \'Internal / Global\' }}',
    $content
);

// Fix Rider user check just in case
$content = str_replace(
    '{{ $rider->user->name }}',
    '{{ optional($rider->user)->name ?? \'Deleted User\' }}',
    $content
);

$content = str_replace(
    '{{ $rider->user->phone ?? \'N/A\' }}',
    '{{ optional($rider->user)->phone ?? \'N/A\' }}',
    $content
);

$content = str_replace(
    '{{ $rider->user->email }}',
    '{{ optional($rider->user)->email ?? \'Deleted\' }}',
    $content
);

$content = str_replace(
    '{{ str_replace(\'_\', \' \', $rider->user->role) }}',
    '{{ optional($rider->user)->role ? str_replace(\'_\', \' \', $rider->user->role) : \'\' }}',
    $content
);

file_put_contents("resources/views/admin/riders/index.blade.php", $content);
