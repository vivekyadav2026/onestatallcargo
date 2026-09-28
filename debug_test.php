<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Shipment;
use App\Models\Bag;

$bagA = Bag::where('bag_number', 'TESTBAG_A')->first();
$shipmentA = Shipment::where('awb_number', 'TESTAWB_A')->first() ?? Shipment::first();
$shipmentA->status = 'Delivered';
$shipmentA->bag_id = null;
$shipmentA->save();

Auth::login(\App\Models\User::where('name', 'FA')->first());
$controller = new \App\Http\Controllers\HubBaggingController();
$request = new \Illuminate\Http\Request();
$request->merge(['awb_number' => $shipmentA->awb_number]);

$r = $controller->addShipment($request, $bagA->id);
echo "Got error: " . $r->getSession()->get('error');
