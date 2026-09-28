<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Bag;
use App\Models\Manifest;
use App\Models\Shipment;
use App\Models\ShipmentEvent;

Bag::where('bag_number', 'like', 'CONC%')->delete();
ShipmentEvent::where('remarks', 'like', '%CONCBAG%')->delete();
Manifest::where('manifest_number', 'like', 'MF%')->delete();

$user = User::where('name', 'FA')->first();
$franchiseId = \App\Models\Franchise::where('user_id', $user->id)->first()->id;
$hubId = \App\Models\Hub::where('manager_id', $user->id)->first()->id;

$bag1 = Bag::create(['bag_number' => 'CONCBAG1', 'hub_id' => $hubId, 'franchise_id' => $franchiseId, 'destination_hub_id' => $hubId, 'status' => 'OPEN']);
$bag2 = Bag::create(['bag_number' => 'CONCBAG2', 'hub_id' => $hubId, 'franchise_id' => $franchiseId, 'destination_hub_id' => $hubId, 'status' => 'OPEN']);

$shipment = Shipment::first();
$shipment->status = 'Received';
$shipment->franchise_id = $franchiseId;
$shipment->bag_id = null;
$shipment->save();

$workerCode = '<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); $kernel->bootstrap();
Auth::loginUsingId('.$user->id.');
$c = new \App\Http\Controllers\HubBaggingController();
$r = new \Illuminate\Http\Request(); $r->merge(["awb_number" => "'.$shipment->awb_number.'"]);
try { $c->addShipment($r, $argv[1]); } catch(\Exception $e) {}
';
file_put_contents('conc_w1.php', $workerCode);

echo "Running Concurrency Test: Same AWB into Bag A and Bag B...\n";
$descriptorspec = [0 => ["pipe", "r"], 1 => ["pipe", "w"], 2 => ["pipe", "w"]];
$p1 = proc_open('php conc_w1.php ' . $bag1->id, $descriptorspec, $pipes1);
$p2 = proc_open('php conc_w1.php ' . $bag2->id, $descriptorspec, $pipes2);
proc_close($p1);
proc_close($p2);

$shipment->refresh();
$baggedCount = \App\Models\ShipmentEvent::where('shipment_id', $shipment->id)->where('status', 'Bagged')->where('remarks', 'like', '%CONCBAG%')->count();
echo "Shipment Bag ID assigned: " . $shipment->bag_id . "\n";
echo "Total Bagged Events Created: " . $baggedCount . "\n";

echo "Running Concurrency Test 2: Same bag create manifest twice...\n";
$bag1->status = 'SEALED'; $bag1->save();

$workerCode2 = '<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); $kernel->bootstrap();
Auth::loginUsingId('.$user->id.');
$c = new \App\Http\Controllers\HubBaggingController();
try { clone clone $c->createManifest($argv[1]); } catch(\Exception $e) {}
';
file_put_contents('conc_w2.php', str_replace("clone clone", "", $workerCode2));

$p3 = proc_open('php conc_w2.php ' . $bag1->id, $descriptorspec, $pipes3);
$p4 = proc_open('php conc_w2.php ' . $bag1->id, $descriptorspec, $pipes4);
proc_close($p3);
proc_close($p4);

$manifestsCount = Manifest::where('bag_id', $bag1->id)->count();
echo "Total Manifests Created for Bag: " . $manifestsCount . "\n";
if ($baggedCount === 1 && $manifestsCount === 1) {
    echo "CONCURRENCY TESTS: PASS (Only 1 succeeded per lock)\n";
} else {
    echo "CONCURRENCY TESTS: FAIL\n";
}

