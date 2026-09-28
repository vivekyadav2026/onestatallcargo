<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Shipment;
use App\Models\Franchise;
use App\Models\Courier;
use App\Models\User;
use App\Services\ShipmentService;

// Setup test data
echo "Setting up test data...\n";

// Clear previous test data
Franchise::truncate();
Courier::truncate();
User::where('email', 'like', '%test%')->delete();

$seller = User::create([
    'name' => 'Test Seller',
    'email' => 'seller@test.com',
    'password' => bcrypt('password'),
    'role' => 'seller'
]);

$franchiseA = Franchise::create([
    'company_name' => 'Franchise A',
    'owner_name' => 'Owner A',
    'email' => 'a@test.com',
    'phone' => '1111111111',
    'address' => 'Address A',
    'city' => 'City A',
    'pincode' => '110001',
    'state' => 'State A',
    'status' => 'approved',
    'serviceable_pincodes' => '110001,110002'
]);

$franchiseB = Franchise::create([
    'company_name' => 'Franchise B',
    'owner_name' => 'Owner B',
    'email' => 'b@test.com',
    'phone' => '2222222222',
    'address' => 'Address B',
    'city' => 'City B',
    'pincode' => '201301',
    'state' => 'State B',
    'status' => 'approved',
    'serviceable_pincodes' => '201301,201302'
]);

$courier = Courier::create([
    'name' => 'Delhivery',
    'mode' => 'sandbox',
    'is_active' => true
]);

$shipmentService = app(ShipmentService::class);

function createTestShipment($service, $pincode, $user, $override = false, $overrideType = null, $overrideProvider = null) {
    $data = [
        'pickup_pincode' => '110000',
        'delivery_pincode' => $pincode,
        'weight_kg' => 1,
        'receiver_name' => 'Test',
        'receiver_phone' => '9999999999',
        'delivery_address' => 'Test Address',
        'delivery_city' => 'Test City',
        'shipment_type' => 'B2C',
        'invoice_value' => 100,
        'total_amount' => 50,
    ];
    
    $shipment = new Shipment();
    $shipment->fill($data);
    $shipment->user_id = $user->id;
    $shipment->awb_number = 'OSC' . rand(10000, 99999);
    
    if ($override) {
        $shipment->is_overridden = true;
        $shipment->fulfillment_type = $overrideType;
        $shipment->provider_id = $overrideProvider;
    }
    
    $service->assignRouting($shipment, $pincode);
    $shipment->save();
    return $shipment;
}

echo "\n--- TEST 1: OneStall Available ---\n";
$shipment1 = createTestShipment($shipmentService, '110001', $seller);
echo "Pincode: 110001 | Fulfillment: {$shipment1->fulfillment_type} | Franchise ID: {$shipment1->franchise_id}\n";
if ($shipment1->fulfillment_type === 'onestall' && $shipment1->franchise_id === $franchiseA->id) {
    echo "✅ PASS\n";
} else {
    echo "❌ FAIL\n";
}

echo "\n--- TEST 2: OneStall Not Available ---\n";
$shipment2 = createTestShipment($shipmentService, '400001', $seller);
echo "Pincode: 400001 | Fulfillment: {$shipment2->fulfillment_type} | Provider ID: {$shipment2->provider_id}\n";
if ($shipment2->fulfillment_type === 'external' && $shipment2->provider_id === $courier->id) {
    echo "✅ PASS\n";
} else {
    echo "❌ FAIL\n";
}

echo "\n--- TEST 7: Disable Franchise Pincode ---\n";
$franchiseA->serviceable_pincodes = '110002'; // Removed 110001
$franchiseA->save();
$shipment3 = createTestShipment($shipmentService, '110001', $seller);
echo "Pincode: 110001 | Fulfillment: {$shipment3->fulfillment_type} | Provider ID: {$shipment3->provider_id}\n";
if ($shipment3->fulfillment_type === 'external') {
    echo "✅ PASS\n";
} else {
    echo "❌ FAIL\n";
}

echo "\n--- TEST 8: Move Pincode ---\n";
$franchiseB->serviceable_pincodes = '201301,201302,110001'; // Moved 110001 to B
$franchiseB->save();
$shipment4 = createTestShipment($shipmentService, '110001', $seller);
echo "Pincode: 110001 | Fulfillment: {$shipment4->fulfillment_type} | Franchise ID: {$shipment4->franchise_id}\n";
if ($shipment4->fulfillment_type === 'onestall' && $shipment4->franchise_id === $franchiseB->id) {
    echo "✅ PASS\n";
} else {
    echo "❌ FAIL\n";
}

echo "\n--- TEST 16: Admin Override ---\n";
$shipment5 = createTestShipment($shipmentService, '110001', $seller, true, 'external', $courier->id);
echo "Pincode: 110001 | Override Fulfillment: {$shipment5->fulfillment_type} | Provider ID: {$shipment5->provider_id}\n";
if ($shipment5->fulfillment_type === 'external' && $shipment5->provider_id === $courier->id) {
    echo "✅ PASS\n";
} else {
    echo "❌ FAIL\n";
}
