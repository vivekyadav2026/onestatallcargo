<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
if ($user) {
    $kyc = \App\Models\Kyc::where("user_id", $user->id)->first();
    echo "User ID: " . $user->id . " | KYC Status: " . ($kyc ? $kyc->status : "no_kyc") . PHP_EOL;
}

