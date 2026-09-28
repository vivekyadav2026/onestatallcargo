<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\PricingService;
use App\Models\RateCard;

$service = new PricingService();

// Mock the determineZone by overriding it for the test script
class TestPricingService extends PricingService {
    public $mockZone = 'Zone 1';
    protected function determineZone(string $p, string $d) {
        return $this->mockZone;
    }
}

$tester = new TestPricingService();

$tests = [
    // weight, zone, cod, invoice, rto, dto, qc
    [0.5, 'Zone 1', false, 0, false, false, 0],
    [1.0, 'Zone 1', false, 0, false, false, 0],
    [1.5, 'Zone 2', false, 0, false, false, 0], // Zone 2: 1.5kg = 2kg slab = 70
    [2.0, 'Zone 3', false, 0, false, false, 0], // Zone 3: 2kg slab = 90
    [3.0, 'Zone 4', true, 1000, false, false, 0], // Zone 4: 3kg = first 2kg (100) + 1kg (28) = 128. COD 1000*1.25%=12.5 (min 30).
    [5.0, 'Zone 4', false, 0, false, false, 0], // Zone 4: 5kg slab = 180
    [6.0, 'Zone 5', false, 0, false, false, 0], // Zone 5: 6kg = 5kg (200) + 1kg (26) = 226
    [10.0, 'Zone 5', false, 0, false, false, 0], // Zone 5: 10kg slab = 310
    [11.0, 'Zone 1', false, 0, false, false, 0], // Zone 1: 11kg = 10kg (190) + 1kg (16) = 206
    [20.0, 'Zone 2', false, 0, true, false, 0],  // Zone 2: 20kg slab = 390. RTO = +390. Total base = 780.
    [21.0, 'Zone 3', false, 0, false, false, 0], // Zone 3: 21kg = 20kg (450) + 1kg (18) = 468
];

echo "| Weight | Zone | Base Freight | FSC | COD | GST | Total |\n";
echo "|---|---|---|---|---|---|---|\n";

foreach ($tests as $t) {
    $tester->mockZone = $t[1];
    
    // pickup, delivery, phys, l, b, h, is_cod, inv, rto, dto, qc
    $res = $tester->calculateOneStallRate('110001', '110001', $t[0], 10, 10, 10, $t[2], $t[3], $t[4], $t[5], $t[6]);
    
    echo "| {$t[0]} KG | {$res['zone']} | ₹{$res['base_freight']} | ₹{$res['fsc_amount']} | ₹{$res['cod_charge']} | ₹{$res['gst']} | ₹{$res['total']} |\n";
}

// Test Zone 6 missing rate
try {
    $tester->mockZone = 'Zone 6';
    $res = $tester->calculateOneStallRate('110001', '110001', 1.0, 10, 10, 10, false, 0);
    echo "\nZone 6 calculation succeeded (unexpected)\n";
} catch (\Exception $e) {
    echo "\nZone 6 error correctly caught: " . $e->getMessage() . "\n";
}

// Volumetric test
$tester->mockZone = 'Zone 1';
$res = $tester->calculateOneStallRate('110001', '110001', 2.0, 50, 40, 30, false, 0); // 50*40*30/5000 = 12KG
echo "\nVolumetric Test: Physical 2KG, Volumetric 12KG (50x40x30).\n";
echo "Chargeable Weight: {$res['chargeable_weight']} KG. Base Freight: {$res['base_freight']}\n";

