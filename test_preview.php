<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/admin/ratecards/1/preview', 'POST', [
    'pickup_pincode' => '110001',
    'delivery_pincode' => '110001',
    'weight_kg' => '0.5',
    'length_cm' => 10,
    'width_cm' => 10,
    'height_cm' => 10,
    'is_cod' => 0,
    'invoice_value' => 500
]);

$controller = app(\App\Http\Controllers\AdminRateCardController::class);
$response = $controller->preview($request, 1);
echo $response->getContent();
