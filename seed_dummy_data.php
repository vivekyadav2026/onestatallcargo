<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Rate;
use App\Models\Hub;
use App\Models\Courier;
use Illuminate\Support\Facades\Hash;

echo "Seeding Users...\n";
// Sellers
User::firstOrCreate(['email' => 'seller1@example.com'], [
    'name' => 'Tech Gadgets Store', 'password' => Hash::make('password'), 'role' => 'seller', 'wallet_balance' => 5000, 'phone' => '9876543210'
]);
User::firstOrCreate(['email' => 'seller2@example.com'], [
    'name' => 'Fashion Boutique', 'password' => Hash::make('password'), 'role' => 'seller', 'wallet_balance' => 2500, 'phone' => '9876543211'
]);

// Riders
User::firstOrCreate(['email' => 'pickup1@example.com'], [
    'name' => 'Raju (Pickup)', 'password' => Hash::make('password'), 'role' => 'pickup_rider', 'phone' => '9876543212'
]);
User::firstOrCreate(['email' => 'delivery1@example.com'], [
    'name' => 'Amit (Delivery)', 'password' => Hash::make('password'), 'role' => 'delivery_rider', 'phone' => '9876543213'
]);

echo "Seeding Rates...\n";
Rate::firstOrCreate(['zone_type' => 'Local'], [
    'base_rate' => 30, 'additional_weight_rate' => 25, 'rto_surcharge' => 15, 'cod_surcharge' => 40
]);
Rate::firstOrCreate(['zone_type' => 'Regional'], [
    'base_rate' => 45, 'additional_weight_rate' => 40, 'rto_surcharge' => 20, 'cod_surcharge' => 50
]);
Rate::firstOrCreate(['zone_type' => 'National'], [
    'base_rate' => 60, 'additional_weight_rate' => 55, 'rto_surcharge' => 30, 'cod_surcharge' => 50
]);
Rate::firstOrCreate(['zone_type' => 'Metro'], [
    'base_rate' => 50, 'additional_weight_rate' => 45, 'rto_surcharge' => 25, 'cod_surcharge' => 50
]);

echo "Seeding Hubs...\n";
Hub::firstOrCreate(['name' => 'Delhi Master Hub'], [
    'code' => 'DEL-01', 'address' => 'Okhla Phase 2, New Delhi', 'manager_name' => 'Rajesh Kumar', 'manager_phone' => '9876543214', 'is_active' => true
]);
Hub::firstOrCreate(['name' => 'Mumbai Processing Center'], [
    'code' => 'BOM-01', 'address' => 'Andheri East, Mumbai', 'manager_name' => 'Suresh Verma', 'manager_phone' => '9876543215', 'is_active' => true
]);

echo "Seeding Couriers...\n";
Courier::firstOrCreate(['name' => 'Delhivery Surface'], [
    'code' => 'DELHIVERY_SURFACE', 'api_key' => 'dhl_test_1234567890', 'is_active' => true, 'tracking_url_format' => 'https://www.delhivery.com/track/package/{awb}'
]);
Courier::firstOrCreate(['name' => 'Xpressbees Air'], [
    'code' => 'XPRESSBEES_AIR', 'api_key' => 'xb_test_0987654321', 'is_active' => true, 'tracking_url_format' => 'https://www.xpressbees.com/track?awb={awb}'
]);

echo "Done!\n";
