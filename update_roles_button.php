<?php
$file = __DIR__ . '/resources/views/admin/roles/index.blade.php';
$content = file_get_contents($file);

$content = str_replace('<button class="px-5 py-2.5', '<a href="{{ route(\'admin.roles.create\') }}" class="px-5 py-2.5', $content);
$content = str_replace('Add User</button>', 'Add User</a>', $content);

file_put_contents($file, $content);
echo "Updated index view button.\n";
