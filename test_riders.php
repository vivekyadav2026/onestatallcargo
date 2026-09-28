<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Franchise;
use App\Models\Hub;
use App\Models\Rider;
use Illuminate\Http\Request;

echo "--- STEP 4A SECURITY TESTS ---\n";

User::where('email', 'like', 'rider_%')->delete();
Rider::where('vehicle_number', 'like', 'TEST%')->delete();

$userA = User::where('name', 'FA')->first();
$franchiseA = Franchise::where('user_id', $userA->id)->first();
$hubA = Hub::where('manager_id', $userA->id)->first();

$userB = User::where('name', 'FB')->first();
$franchiseB = Franchise::where('user_id', $userB->id)->first();
$hubB = Hub::where('manager_id', $userB->id)->first();

// Create Rider for A
$riderUserA = User::create(['name' => 'Rider A', 'email' => 'rider_a@test.com', 'password' => 'password', 'role' => 'rider', 'phone' => '10001']);
$riderA = Rider::create(['user_id' => $riderUserA->id, 'franchise_id' => $franchiseA->id, 'hub_id' => $hubA->id, 'vehicle_number' => 'TEST_A']);

// Create Rider for B
$riderUserB = User::create(['name' => 'Rider B', 'email' => 'rider_b@test.com', 'password' => 'password', 'role' => 'rider', 'phone' => '10002']);
$riderB = Rider::create(['user_id' => $riderUserB->id, 'franchise_id' => $franchiseB->id, 'hub_id' => $hubB->id, 'vehicle_number' => 'TEST_B']);

Auth::loginUsingId($userA->id);
$controller = app(\App\Http\Controllers\HubRiderController::class);

// TEST A: Franchise A can list own riders
$view = $controller->index();
$ridersList = $view->getData()['riders']->pluck('id')->toArray();
if (in_array($riderA->id, $ridersList) && !in_array($riderB->id, $ridersList)) {
    echo "TEST A (List own riders): PASS\n";
} else {
    echo "TEST A: FAIL\n";
}

// TEST B/C/D: Franchise A edit Franchise B rider -> 403
$req = new Request();
$req->merge(['name' => 'Hacked', 'phone' => '000', 'status' => 'suspended']);
try {
    $controller->update($req, $riderB->id);
    echo "TEST D (Edit other rider): FAIL\n";
} catch (\Exception $e) {
    echo "TEST D (Edit other rider): PASS (Blocked: " . $e->getMessage() . ")\n";
}

// TEST G: Franchise A assign its rider to Franchise B hub
$req->merge(['hub_id' => $hubB->id]);
$res = $controller->update($req, $riderA->id);
if (strpos($res->getSession()->get('error'), 'UNAUTHORIZED') !== false) {
    echo "TEST G (Assign other hub): PASS\n";
} else {
    echo "TEST G: FAIL\n";
}

// TEST K: Duplicate user creation
$reqStore = new Request();
$reqStore->merge(['name'=>'R', 'email'=>'rider_a@test.com', 'phone'=>'000', 'password'=>'pass', 'role'=>'rider']);
try {
    $controller->store($reqStore);
    echo "TEST K (Duplicate User): FAIL\n";
} catch (\Illuminate\Validation\ValidationException $e) {
    echo "TEST K (Duplicate User): PASS (Blocked by Validation)\n";
}

// TEST I: Rider cannot access Franchise management routes
Auth::loginUsingId($riderUserA->id);
try {
    $controller->index();
    echo "TEST I (Rider accessing Franchise Panel): FAIL\n";
} catch (\Exception $e) {
    echo "TEST I (Rider accessing Franchise Panel): PASS (Blocked: " . $e->getMessage() . ")\n";
}

// DB Integrity Test
echo "--- DATABASE INTEGRITY ---\n";
echo "Orphan Riders (no user): " . Rider::whereNotIn('user_id', User::pluck('id'))->count() . "\n";
echo "Orphan Riders (no franchise): " . Rider::whereNotNull('franchise_id')->whereNotIn('franchise_id', Franchise::pluck('id'))->count() . "\n";
echo "Orphan Riders (no hub): " . Rider::whereNotNull('hub_id')->whereNotIn('hub_id', Hub::pluck('id'))->count() . "\n";

