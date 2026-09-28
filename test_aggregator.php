<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Shipment;
use Illuminate\Http\Request;

echo "--- TESTING AGGREGATOR ROUTING ---\n";

// Ensure Delhivery is active
$delhivery = \App\Models\Courier::firstOrCreate(
    ['name' => 'Delhivery'],
    ['mode' => 'sandbox', 'is_active' => true, 'markup_type' => 'percentage', 'markup_value' => 10]
);
$delhivery->is_active = true;
$delhivery->save();

// Create Seller
$seller = User::firstOrCreate(
    ['email' => 'aggregator_test@test.com'],
    ['name' => 'Aggregator Tester', 'password' => 'password', 'role' => 'user']
);

// Approve KYC for Seller so they can ship
\App\Models\Kyc::updateOrCreate(
    ['user_id' => $seller->id],
    ['status' => 'approved', 'document_type' => 'aadhar', 'document_number' => '1234']
);

// Ensure Pincode 999999 has NO franchise
\App\Models\ServiceablePincode::where('pincode', '999999')->delete();

// Fake the Controller call
Auth::loginUsingId($seller->id);
$req = new Request();
$req->merge([
    'shipment_type' => 'Forward',
    'receiver_name' => 'Remote Customer',
    'receiver_phone' => '8888888888',
    'delivery_address' => 'Middle of nowhere',
    'delivery_city' => 'Remote City',
    'delivery_pincode' => '999999', // NO FRANCHISE
    'weight_kg' => 1.5,
    'is_cod' => false,
    'invoice_value' => 500,
    'ship_now' => 1
]);

echo "Booking shipment to unserviceable pincode 999999...\n";

try {
    $controller = app(\App\Http\Controllers\SellerShipmentController::class);
    $response = $controller->store($req);
    
    // Find the shipment
    $shipment = Shipment::where('user_id', $seller->id)->orderBy('id', 'desc')->first();
    
    echo "Shipment AWB: " . $shipment->awb_number . "\n";
    echo "Fulfillment Type: " . $shipment->fulfillment_type . "\n";
    echo "Provider ID: " . $shipment->provider_id . " (Expected: " . $delhivery->id . ")\n";
    echo "Status: " . $shipment->status . "\n";
    
    if ($shipment->provider_id == $delhivery->id) {
        echo "TEST PASS: Shipment successfully routed to Aggregator (Delhivery)!\n";
    } else {
        echo "TEST FAIL: Did not route to aggregator.\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}

// Cleanup
if (isset($shipment)) $shipment->delete();
