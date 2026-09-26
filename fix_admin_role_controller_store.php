<?php
$file = __DIR__ . '/app/Http/Controllers/AdminRoleController.php';
$content = file_get_contents($file);

// 1. Update the store() validation
$content = str_replace("'company_name' => 'nullable|string|max:255'", "'company_name' => 'nullable|string|max:255',\n            'permissions' => 'nullable|array'", $content);

// 2. Update the store() user creation
$content = str_replace("'wallet_balance' => 0", "'wallet_balance' => 0,\n            'permissions' => \$validated['permissions'] ?? []", $content);

file_put_contents($file, $content);
echo "Updated AdminRoleController Store.\n";
