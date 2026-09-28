<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Bag;
use App\Models\Shipment;

Bag::where('bag_number', 'TEST_CONCURRENCY_BAG')->delete();
Shipment::where('awb_number', 'TESTAWB_A')->update(['bag_id' => null, 'status' => 'Received']);

$user = User::where('email', 'test_franchise_a@test.com')->first();
$hubId = \App\Models\Hub::where('manager_id', $user->id)->first()->id;
$franchiseId = \App\Models\Franchise::where('user_id', $user->id)->first()->id;
$hubBId = \App\Models\Hub::where('name', 'Test Hub B')->first()->id;

$bag1 = Bag::create(['bag_number' => 'TEST_CONCURRENCY_BAG', 'hub_id' => $hubId, 'franchise_id' => $franchiseId, 'destination_hub_id' => $hubBId, 'status' => 'OPEN']);
$shipment = Shipment::first();
$shipment->status = 'Received';
$shipment->franchise_id = $franchiseId;
$shipment->bag_id = null;
$shipment->save();

$cookieJar = tempnam(sys_get_temp_dir(), 'cookies');
$ch = curl_init('http://127.0.0.1:8000/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
$response = curl_exec($ch);
preg_match('/<input type="hidden" name="_token" value="(.*?)">/', $response, $matches);
$csrf = $matches[1] ?? '';

$ch2 = curl_init('http://127.0.0.1:8000/login');
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_COOKIEJAR, $cookieJar);
curl_setopt($ch2, CURLOPT_COOKIEFILE, $cookieJar);
curl_setopt($ch2, CURLOPT_POST, true);
curl_setopt($ch2, CURLOPT_POSTFIELDS, http_build_query(['_token' => $csrf, 'email' => 'test_franchise_a@test.com', 'password' => 'password']));
curl_exec($ch2);

$mh = curl_multi_init();
$chs = [];
for ($i = 0; $i < 2; $i++) {
    $chs[$i] = curl_init('http://127.0.0.1:8000/hub/bagging/'.$bag1->id.'/add');
    curl_setopt($chs[$i], CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chs[$i], CURLOPT_COOKIEFILE, $cookieJar);
    curl_setopt($chs[$i], CURLOPT_POST, true);
    curl_setopt($chs[$i], CURLOPT_POSTFIELDS, http_build_query(['_token' => $csrf, 'awb_number' => $shipment->awb_number]));
    curl_multi_add_handle($mh, $chs[$i]);
}

$active = null;
do {
    $mrc = curl_multi_exec($mh, $active);
} while ($mrc == CURLM_CALL_MULTI_PERFORM || $active);

$responses = [];
for ($i = 0; $i < 2; $i++) {
    $responses[$i] = curl_multi_getcontent($chs[$i]);
    curl_multi_remove_handle($mh, $chs[$i]);
}
curl_multi_close($mh);

$shipment->refresh();
echo "Shipment Bag ID: " . $shipment->bag_id . " | Target Bag ID: " . $bag1->id . "\n";
$eventsCount = \App\Models\ShipmentEvent::where('shipment_id', $shipment->id)->where('status', 'Bagged')->where('remarks', 'like', '%TEST_CONCURRENCY_BAG%')->count();
echo "Total Bagged Events Created: " . $eventsCount . "\n";

if ($eventsCount === 1) {
    echo "CONCURRENCY TEST: PASS (Exactly 1 operation succeeded)\n";
} else {
    echo "CONCURRENCY TEST: FAIL (Race condition allowed " . $eventsCount . " operations)\n";
}

