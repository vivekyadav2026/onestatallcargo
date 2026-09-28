<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Shipment;
use Illuminate\Http\Request;

$seller = User::where('email', 'aggregator_test@test.com')->first();
Auth::loginUsingId($seller->id);

$req = new Request();
$req->merge([
    'shipment_type' => 'Forward',
    'receiver_name' => 'Remote Customer',
    'receiver_phone' => '8888888888',
    'delivery_address' => 'Middle of nowhere',
    'delivery_city' => 'Remote City',
    'delivery_pincode' => '999999',
    'weight_kg' => 1.5,
    'is_cod' => false,
    'invoice_value' => 500,
    'ship_now' => 1
]);

$controller = app(\App\Http\Controllers\SellerShipmentController::class);
$response = $controller->store($req);

if (method_exists($response, 'getSession')) {
    echo "Session Error: " . $response->getSession()->get('error') . "\n";
    echo "Session Success: " . $response->getSession()->get('success') . "\n";
} else {
    echo "Not a redirect.\n";
}
