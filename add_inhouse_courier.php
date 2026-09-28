<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Courier;

$courier = Courier::firstOrCreate(
    ['name' => 'Onestall Cargo Surface'],
    [
        'mode' => 'Surface',
        'api_credentials' => ['in_house' => true],
        'is_active' => true
    ]
);

$courierAir = Courier::firstOrCreate(
    ['name' => 'Onestall Cargo Air'],
    [
        'mode' => 'Air',
        'api_credentials' => ['in_house' => true],
        'is_active' => true
    ]
);

echo "In-house couriers added successfully!\n";
