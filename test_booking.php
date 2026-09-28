<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\ShipmentService;
use App\Models\User;

// Find an admin or seller user
$user = User::firstOrCreate(
    ['email' => 'test_seller@onestall.com'],
    [
        'name' => 'Test Seller',
        'password' => bcrypt('password'),
        'user_type' => 'seller',
        'wallet_balance' => 5000
    ]
);

$shipmentService = app(ShipmentService::class);

$data = [
    'order_id' => 'TEST-' . time(),
    'pickup_pincode' => '110001',
    'delivery_pincode' => '110001', // Zone 1
    'receiver_name' => 'John Doe',
    'receiver_phone' => '9999999999',
    'pickup_address' => 'A-1, Test Address',
    'delivery_address' => 'B-2, Test Delivery',
    'pickup_city' => 'Delhi',
    'delivery_city' => 'Delhi',
    'weight_kg' => 2.0, // 2KG Zone 1 = 84.37
    'length_cm' => 10,
    'width_cm' => 10,
    'height_cm' => 10,
    'shipment_type' => 'Prepaid',
    'invoice_value' => 500,
    'product_name' => 'Test Product'
];

try {
    $shipment = $shipmentService->createShipment($data, $user->id);
    echo "Shipment created successfully!\n";
    echo "AWB: {$shipment->awb_number}\n";
    echo "Shipping Charge: {$shipment->shipping_charge}\n";
    echo "Routing: {$shipment->fulfillment_type}\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
