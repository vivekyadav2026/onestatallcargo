<?php
use App\Models\User;
use App\Models\Franchise;
use App\Models\Hub;
use App\Models\Shipment;
use App\Models\Bag;
use App\Models\Manifest;

Manifest::where('manifest_number', 'like', 'MF%')->delete();
Shipment::where('awb_number', 'TESTAWB_A')->update(['bag_id' => null]);
Bag::where('bag_number', 'like', 'TESTBAG%')->delete();

$hubA = Hub::where('name', 'Test Hub A')->first();
$userA = User::where('name', 'FA')->first();
$franchiseA = Franchise::where('company_name', 'Test Franchise A')->first();

$bagA = Bag::create(['bag_number' => 'TESTBAG_A2', 'hub_id' => $hubA->id, 'franchise_id' => $franchiseA->id, 'destination_hub_id' => $hubA->id, 'status' => 'OPEN']);

$shipmentA = Shipment::where('awb_number', 'TESTAWB_A')->first() ?? Shipment::first();
$shipmentA->status = 'Delivered';
$shipmentA->franchise_id = $franchiseA->id;
$shipmentA->bag_id = null;
$shipmentA->save();

Auth::login($userA);
$controller = new \App\Http\Controllers\HubBaggingController();
$request = new \Illuminate\Http\Request();
$request->merge(['awb_number' => $shipmentA->awb_number]);

$r = $controller->addShipment($request, $bagA->id);
if (strpos($r->getSession()->get('error'), 'Only [Received] shipments') !== false) {
    echo "TEST J: PASS (Blocked Delivered)\n";
} else {
    echo "TEST J: FAIL. Got error: " . $r->getSession()->get('error') . "\n";
}
