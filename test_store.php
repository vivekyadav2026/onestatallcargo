<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = \Illuminate\Http\Request::create("/seller/book", "POST", [
    "shipment_type" => "B2C",
    "receiver_name" => "John Doe",
    "receiver_phone" => "9876543210",
    "delivery_address" => "123 Main St",
    "delivery_city" => "Mumbai",
    "delivery_pincode" => "400001",
    "weight_kg" => 0.5,
    "is_cod" => "1",
    "invoice_value" => 1000
]);
Auth::loginUsingId(2); // Try user 2
$controller = app(\App\Http\Controllers\SellerShipmentController::class);
try {
    $response = $controller->store($request);
    echo "Response status: " . $response->getStatusCode() . PHP_EOL;
    echo "Redirect URL: " . $response->getTargetUrl() . PHP_EOL;
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}

