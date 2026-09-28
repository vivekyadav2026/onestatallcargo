<?php
use App\Models\User;
use App\Models\Franchise;
use App\Models\Hub;
use App\Models\Shipment;
use App\Models\Bag;
use App\Models\Manifest;

User::where('email', 'like', 'test_franchise%')->delete();
Franchise::where('company_name', 'like', 'Test Franchise%')->delete();
Hub::where('name', 'like', 'Test Hub%')->delete();
Bag::where('bag_number', 'like', 'TESTBAG%')->delete();
Manifest::where('manifest_number', 'like', 'MF%')->delete();

$userA = User::create(['name' => 'FA', 'email' => 'test_franchise_a@test.com', 'password' => bcrypt('password'), 'role' => 'franchise']);
$franchiseA = Franchise::create(['user_id' => $userA->id, 'company_name' => 'Test Franchise A', 'owner_name' => 'A', 'phone' => '1', 'email' => 'a@a', 'address' => 'A', 'city' => 'A', 'state' => 'A', 'status' => 'approved', 'serviceable_pincodes' => ['111111']]);
$hubA = Hub::create(['hub_code' => 'THA', 'name' => 'Test Hub A', 'city' => 'City A', 'pincode' => '111111', 'manager_id' => $userA->id, 'is_active' => true]);

$userB = User::create(['name' => 'FB', 'email' => 'test_franchise_b@test.com', 'password' => bcrypt('password'), 'role' => 'franchise']);
$franchiseB = Franchise::create(['user_id' => $userB->id, 'company_name' => 'Test Franchise B', 'owner_name' => 'B', 'phone' => '2', 'email' => 'b@b', 'address' => 'B', 'city' => 'B', 'state' => 'B', 'status' => 'approved', 'serviceable_pincodes' => ['222222']]);
$hubB = Hub::create(['hub_code' => 'THB', 'name' => 'Test Hub B', 'city' => 'City B', 'pincode' => '222222', 'manager_id' => $userB->id, 'is_active' => true]);

$shipmentA = Shipment::first();
$shipmentA->franchise_id = $franchiseA->id;
$shipmentA->bag_id = null;
$shipmentA->status = 'Received';
$shipmentA->save();

$shipmentB = Shipment::skip(1)->first();
$shipmentB->franchise_id = $franchiseB->id;
$shipmentB->bag_id = null;
$shipmentB->status = 'Received';
$shipmentB->save();

$bagA = Bag::create(['bag_number' => 'TESTBAG_A', 'hub_id' => $hubA->id, 'franchise_id' => $franchiseA->id, 'destination_hub_id' => $hubB->id, 'status' => 'OPEN']);

Auth::login($userA);
$controller = new \App\Http\Controllers\HubBaggingController();
$request = new \Illuminate\Http\Request();

echo "================================\n";

// A. Franchise A opens Franchise B bag -> 403
$bagB = Bag::create(['bag_number' => 'TESTBAG_B', 'hub_id' => $hubB->id, 'franchise_id' => $franchiseB->id, 'destination_hub_id' => $hubA->id, 'status' => 'OPEN']);
try { $controller->showBag($bagB->id); echo "TEST A: FAIL (Opened B's bag)\n"; } 
catch (\Exception $e) { echo "TEST A: PASS (Blocked: " . $e->getMessage() . ")\n"; }

// B. Franchise A opens Franchise B manifest -> 403
$manifestB = Manifest::create(['manifest_number' => 'MF_TEST_B', 'bag_id' => $bagB->id, 'source_hub_id' => $hubB->id, 'franchise_id' => $franchiseB->id]);
try { $controller->showManifest($manifestB->id); echo "TEST B: FAIL\n"; } 
catch (\Exception $e) { echo "TEST B: PASS (Blocked)\n"; }

// C. Franchise A adds Franchise B shipment -> blocked
$request->merge(['awb_number' => $shipmentB->awb_number]);
$r = $controller->addShipment($request, $bagA->id);
if(strpos($r->getSession()->get('error'), 'UNAUTHORIZED') !== false) echo "TEST C: PASS (Blocked)\n"; else echo "TEST C: FAIL\n";

// E. Franchise A seals Franchise B bag -> blocked
try { $controller->sealBag($bagB->id); echo "TEST E: FAIL\n"; }
catch (\Exception $e) { echo "TEST E: PASS (Blocked)\n"; }

// H. Invalid destination hub -> blocked
$request->merge(['destination_hub_id' => 99999]);
try { $controller->storeBag($request); echo "TEST H: FAIL (Validation passed unexpectedly)\n"; }
catch(\Illuminate\Validation\ValidationException $e) { echo "TEST H: PASS (Blocked by validation)\n"; }

// J. Delivered shipment -> blocked
$shipmentA->status = 'Delivered'; $shipmentA->save();
$request->merge(['awb_number' => $shipmentA->awb_number]);
$r = $controller->addShipment($request, $bagA->id);
if(strpos($r->getSession()->get('error'), 'Only [Received] shipments can be bagged') !== false) echo "TEST J: PASS (Blocked)\n"; else echo "TEST J: FAIL\n";
$shipmentA->status = 'Received'; $shipmentA->save(); // Reset

// M. Shipment already in another bag -> blocked
$shipmentA->bag_id = $bagA->id; $shipmentA->save();
$r = $controller->addShipment($request, $bagA->id);
if(strpos($r->getSession()->get('error'), 'already assigned') !== false) echo "TEST M: PASS (Blocked)\n"; else echo "TEST M: FAIL\n";

// Seal Bag A
$controller->sealBag($bagA->id);

// Duplicate Manifest Creation -> Blocked
$controller->createManifest($bagA->id);
$r = $controller->createManifest($bagA->id);
if(strpos($r->getSession()->get('error'), 'already exists') !== false) echo "TEST N (Duplicate Manifest): PASS\n"; else echo "TEST N: FAIL\n";

echo "================================\n";
