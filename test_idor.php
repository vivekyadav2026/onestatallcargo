<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Shipment;
use Illuminate\Http\Request;

// Create test rider
$riderUser = User::firstOrCreate(
    ['email' => 'hacker_rider@test.com'],
    ['name' => 'Hacker Rider', 'password' => 'password', 'role' => 'rider', 'phone' => '9999999']
);

// Get any shipment not belonging to this rider
$shipment = Shipment::where('rider_id', '!=', $riderUser->id)->orWhereNull('rider_id')->first();
echo "Targeting AWB: " . $shipment->awb_number . " (Assigned Rider: " . ($shipment->rider_id ?? 'None') . ")\n";

Auth::loginUsingId($riderUser->id);

$req = new Request();
$req->merge([
    'awb_number' => $shipment->awb_number,
    'action_type' => 'Delivery'
]);

$controller = app(\App\Http\Controllers\RiderAppController::class);
$res = $controller->uploadEvidence($req);

$shipment->refresh();
echo "Shipment Status after attack: " . $shipment->status . "\n";
if ($shipment->status === 'Delivered') {
    echo "IDOR VULNERABILITY FOUND: Rider can deliver other people's shipments!\n";
} else {
    echo "SECURE\n";
}
