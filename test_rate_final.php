<?php
$request = new \Illuminate\Http\Request();
$request->replace([
    'pickup_pincode' => '110001',
    'delivery_pincode' => '400001',
    'weight' => '1.5',
    'payment_mode' => 'prepaid',
    'risk_type' => 'owner_risk',
    'invoice_amount' => '1000'
]);

$controller = app(\App\Http\Controllers\Api\V1\RateCalculatorController::class);
$response = $controller->calculate($request);

echo json_encode($response->getData(), JSON_PRETTY_PRINT);
