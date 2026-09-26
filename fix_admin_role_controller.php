<?php
$file = __DIR__ . '/app/Http/Controllers/AdminRoleController.php';
$content = file_get_contents($file);

$content = str_replace("'wallet_balance' => 'required|numeric|min:0'", "'wallet_balance' => 'required|numeric|min:0',\n            'permissions' => 'nullable|array'", $content);
$content = str_replace("\$user->wallet_balance = \$validated['wallet_balance'];", "\$user->wallet_balance = \$validated['wallet_balance'];\n        \$user->permissions = \$validated['permissions'] ?? [];", $content);

file_put_contents($file, $content);
echo "Updated AdminRoleController.\n";
