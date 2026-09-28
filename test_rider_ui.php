<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Shipment;
use Illuminate\Http\Request;

echo "--- RUNNING RIDER UI INTEGRATION TEST ---\n";

// 1. Get/Create Rider
$riderUser = User::firstOrCreate(
    ['email' => 'rider_ui_test@test.com'],
    ['name' => 'UI Test Rider', 'password' => 'password', 'role' => 'rider', 'phone' => '1122334455']
);

// 2. Create Dummy COD Shipment assigned to this rider
$shipment = Shipment::create([
    'awb_number' => 'CODTEST' . rand(1000,9999),
    'status' => 'Out for Delivery',
    'rider_id' => $riderUser->id,
    'is_cod' => true,
    'invoice_value' => 1250.50,
    'total_amount' => 1250.50,
    'receiver_name' => 'Test Customer',
    'delivery_address' => 'Test St',
    'delivery_city' => 'Test City'
]);

// 3. Fake Delivery via uploadEvidence
Auth::loginUsingId($riderUser->id);
$req = new Request();
$req->merge([
    'awb_number' => $shipment->awb_number,
    'action_type' => 'Delivery'
]);
$controller = app(\App\Http\Controllers\RiderAppController::class);
$res = $controller->uploadEvidence($req);

// 4. Test COD View Rendering
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

// 5. Test Dashboard Rendering
$dashRes = app(\App\Http\Controllers\RiderAppController::class)->index();
$dashHtml = $dashRes->render();
echo "TEST 4: Dashboard Renders Successfully? ";
echo ($dashHtml ? "PASS\n" : "FAIL\n");

// Cleanup
$shipment->delete();
$riderUser->delete();
