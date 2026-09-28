<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pricingService = app(\App\Services\PricingService::class);
$serviceability = app(\App\Services\ServiceabilityService::class);

// Create a mapping for test pincodes
$testPincodes = [
    'Z1' => '110001', // Local (assuming pickup is 110001)
    'Z2' => '121001', // Regional
    'Z3' => '400001', // Metro
    'Z4' => '302001', // ROI
    'Z5' => '781001', // NE/J&K
];

echo "--- PRICING REGRESSION TESTS ---\n\n";

$tests = [
    ['name' => '1. Z1 0.5 KG', 'pin' => $testPincodes['Z1'], 'w' => 0.5, 'is_cod' => false, 'inv' => 0],
    ['name' => '2. Z2 0.5 KG', 'pin' => $testPincodes['Z2'], 'w' => 0.5, 'is_cod' => false, 'inv' => 0],
    ['name' => '3. Z3 2 KG', 'pin' => $testPincodes['Z3'], 'w' => 2, 'is_cod' => false, 'inv' => 0],
    ['name' => '4. Z4 5 KG', 'pin' => $testPincodes['Z4'], 'w' => 5, 'is_cod' => false, 'inv' => 0],
    ['name' => '5. Z5 10 KG', 'pin' => $testPincodes['Z5'], 'w' => 10, 'is_cod' => false, 'inv' => 0],
    ['name' => '6. Addl Wt (Z1 1.5 KG)', 'pin' => $testPincodes['Z1'], 'w' => 1.5, 'is_cod' => false, 'inv' => 0],
    ['name' => '7. Vol Wt > Physical (10x10x10 = 0.2kg, Physical 0.1)', 'pin' => $testPincodes['Z1'], 'w' => 0.1, 'l'=>10, 'b'=>10, 'h'=>10, 'is_cod' => false, 'inv' => 0],
    ['name' => '8. Physical > Volumetric (2kg, 10x10x10)', 'pin' => $testPincodes['Z1'], 'w' => 2, 'l'=>10, 'b'=>10, 'h'=>10, 'is_cod' => false, 'inv' => 0],
    ['name' => '9. FSC Included Check', 'pin' => $testPincodes['Z1'], 'w' => 0.5, 'is_cod' => false, 'inv' => 0],
    ['name' => '10. COD Min (₹30)', 'pin' => $testPincodes['Z1'], 'w' => 0.5, 'is_cod' => true, 'inv' => 500],
    ['name' => '11. COD Percent (1.25% of 10000 = 125)', 'pin' => $testPincodes['Z1'], 'w' => 0.5, 'is_cod' => true, 'inv' => 10000],
    ['name' => '12. DTO Check (1.3x)', 'pin' => $testPincodes['Z1'], 'w' => 0.5, 'is_cod' => false, 'inv' => 0, 'dto' => true],
    ['name' => '13. RVP/QC Check (35 + forward)', 'pin' => $testPincodes['Z1'], 'w' => 0.5, 'is_cod' => false, 'inv' => 0, 'qc' => 3], // QC params
];

foreach ($tests as $t) {
    try {
        $l = $t['l'] ?? 10;
        $b = $t['b'] ?? 10;
        $h = $t['h'] ?? 10;
        $is_cod = $t['is_cod'];
        $inv = $t['inv'];
        $dto = $t['dto'] ?? false;
        $qc = $t['qc'] ?? 0;
        
        $routing = $serviceability->determineRouting($t['pin']);
        $rateData = $pricingService->calculateRate(
            'onestall',
            '110001',
            $t['pin'],
            $t['w'],
            $l, $b, $h,
            $is_cod, $inv, false, $dto, $qc,
            $routing['provider_id']
        );
        echo "{$t['name']} => SUCCESS: ₹{$rateData['total']}\n";
    } catch (\Exception $e) {
        echo "{$t['name']} => ERROR: {$e->getMessage()}\n";
    }
}
