<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Franchise;
use App\Models\ServiceablePincode;

$franchises = Franchise::all();
foreach ($franchises as $f) {
    if ($f->serviceable_pincodes) {
        $pins = explode(',', $f->serviceable_pincodes);
        foreach ($pins as $pin) {
            $pin = trim($pin);
            if (!empty($pin)) {
                ServiceablePincode::updateOrCreate(
                    ['pincode' => $pin],
                    ['franchise_id' => $f->id, 'is_active' => true]
                );
            }
        }
    }
}
echo "Migrated pincodes.\n";
