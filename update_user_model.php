<?php
$file = __DIR__ . '/app/Models/User.php';
$content = file_get_contents($file);

$content = str_replace("'wallet_balance',", "'wallet_balance',\n        'permissions',", $content);
$content = str_replace("'password' => 'hashed',", "'password' => 'hashed',\n            'permissions' => 'array',", $content);

file_put_contents($file, $content);
echo "Updated User model.\n";
