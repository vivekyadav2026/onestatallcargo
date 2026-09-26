<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Hub;
use App\Models\Courier;
use App\Models\User;

$manager = User::where('role', 'operations')->first();
if (!$manager) {
    $manager = User::firstOrCreate(['email' => 'ops@example.com'], [
        'name' => 'Ops Manager', 'password' => \Illuminate\Support\Facades\Hash::make('password'), 'role' => 'operations'
    ]);
}

echo "Seeding Hubs...\n";
Hub::firstOrCreate(['name' => 'Delhi Master Hub'], [
    'hub_code' => 'DEL-01', 'city' => 'New Delhi', 'pincode' => '110020', 'capacity' => 10000, 'manager_id' => $manager->id, 'is_active' => true
]);
Hub::firstOrCreate(['name' => 'Mumbai Processing Center'], [
    'hub_code' => 'BOM-01', 'city' => 'Mumbai', 'pincode' => '400001', 'capacity' => 8000, 'manager_id' => $manager->id, 'is_active' => true
]);

echo "Seeding Couriers...\n";
Courier::firstOrCreate(['name' => 'Delhivery Surface'], [
    'mode' => 'Surface', 'api_credentials' => json_encode(['api_key' => 'dhl_test_123']), 'is_active' => true
]);
Courier::firstOrCreate(['name' => 'Xpressbees Air'], [
    'mode' => 'Air', 'api_credentials' => json_encode(['api_key' => 'xb_test_123']), 'is_active' => true
]);

echo "Done!\n";
