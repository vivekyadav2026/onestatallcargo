<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Shipment;
use Illuminate\Http\Request;

echo "--- RUNNING RIDER UI INTEGRATION TEST ---\n";

$riderUser = User::firstOrCreate(
    ['email' => 'rider_ui_test@test.com'],
    ['name' => 'UI Test Rider', 'password' => 'password', 'role' => 'rider', 'phone' => '1122334455']
);
$senderUser = User::firstOrCreate(
    ['email' => 'sender_ui_test@test.com'],
    ['name' => 'UI Test Sender', 'password' => 'password', 'role' => 'user']
);

$shipment = Shipment::create([
    'awb_number' => 'CODTEST' . rand(1000,9999),
    'user_id' => $senderUser->id,
    'status' => 'Out for Delivery',
    'rider_id' => $riderUser->id,
    'is_cod' => true,
    'invoice_value' => 1250.50,
    'total_amount' => 1250.50,
    'receiver_name' => 'Test Customer',
    'receiver_phone' => '9999999999',
    'pickup_pincode' => '100001',
    'delivery_pincode' => '100001',
    'weight' => 1.0,
    'dimensions' => '10x10x10',
    'delivery_address' => 'Test St',
    'delivery_city' => 'Test City'
]);

Auth::loginUsingId($riderUser->id);
$req = new Request();
$req->merge([
    'awb_number' => $shipment->awb_number,
    'action_type' => 'Delivery'
]);
$controller = app(\App\Http\Controllers\RiderAppController::class);
$res = $controller->uploadEvidence($req);

$codRes = app(\App\Http\Controllers\RiderAppController::class)->cod();
$html = $codRes->render();

echo "TEST 1: COD View Renders Successfully (No 500 Error)? ";
echo ($html ? "PASS\n" : "FAIL\n");

echo "TEST 2: Is '₹' rendered correctly without corruption? ";
if (strpos($html, '₹') !== false && strpos($html, 'â') === false) {
    echo "PASS\n";
} else {
    echo "FAIL\n";
}

echo "TEST 3: Does it show the correct collected amount (1,250.50)? ";
if (strpos($html, '1,250.50') !== false) {
    echo "PASS\n";
} else {
    echo "FAIL\n";
}

$shipment->delete();
$riderUser->delete();
$senderUser->delete();
