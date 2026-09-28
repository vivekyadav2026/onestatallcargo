<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\RateCard;
use App\Models\RateCardZone;

// Disable event observers temporarily if needed
$rateCard = RateCard::create([
    'version_name' => 'Official Rate Card V1',
    'effective_from' => '2026-09-01',
    'is_active' => true,
    'fsc_percent' => 10.00,
    'cod_min_charge' => 30.00,
    'cod_percent' => 1.25,
    'dto_multiplier' => 1.30,
    'qc_base_charge' => 35.00,
    'qc_included_params' => 3,
    'qc_additional_param_charge' => 5.00,
    'volumetric_divisor' => 5000,
    'gst_percent' => 18.00 // Assuming standard GST
]);

$zonesData = [
    [
        'zone_name' => 'Zone 1',
        'first_0_5_kg' => 25, 'addl_0_5_kg' => 20,
        'first_2_kg' => 65, 'addl_1_kg_after_2' => 20,
        'first_5_kg' => 120, 'addl_1_kg_after_5' => 18,
        'first_10_kg' => 190, 'addl_1_kg_after_10' => 16,
        'first_20_kg' => 350, 'addl_1_kg_after_20' => 14,
    ],
    [
        'zone_name' => 'Zone 2',
        'first_0_5_kg' => 27, 'addl_0_5_kg' => 22,
        'first_2_kg' => 70, 'addl_1_kg_after_2' => 22,
        'first_5_kg' => 140, 'addl_1_kg_after_5' => 20,
        'first_10_kg' => 210, 'addl_1_kg_after_10' => 18,
        'first_20_kg' => 390, 'addl_1_kg_after_20' => 16,
    ],
    [
        'zone_name' => 'Zone 3',
        'first_0_5_kg' => 36, 'addl_0_5_kg' => 28,
        'first_2_kg' => 90, 'addl_1_kg_after_2' => 26,
        'first_5_kg' => 160, 'addl_1_kg_after_5' => 22,
        'first_10_kg' => 250, 'addl_1_kg_after_10' => 20,
        'first_20_kg' => 450, 'addl_1_kg_after_20' => 18,
    ],
    [
        'zone_name' => 'Zone 4',
        'first_0_5_kg' => 38, 'addl_0_5_kg' => 30,
        'first_2_kg' => 100, 'addl_1_kg_after_2' => 28,
        'first_5_kg' => 180, 'addl_1_kg_after_5' => 24,
        'first_10_kg' => 260, 'addl_1_kg_after_10' => 22,
        'first_20_kg' => 480, 'addl_1_kg_after_20' => 20,
    ],
    [
        'zone_name' => 'Zone 5',
        'first_0_5_kg' => 45, 'addl_0_5_kg' => 35,
        'first_2_kg' => 120, 'addl_1_kg_after_2' => 32,
        'first_5_kg' => 200, 'addl_1_kg_after_5' => 26,
        'first_10_kg' => 310, 'addl_1_kg_after_10' => 24,
        'first_20_kg' => 550, 'addl_1_kg_after_20' => 22,
    ],
    [
        'zone_name' => 'Zone 6',
        'first_0_5_kg' => null, 'addl_0_5_kg' => null,
        'first_2_kg' => null, 'addl_1_kg_after_2' => null,
        'first_5_kg' => null, 'addl_1_kg_after_5' => null,
        'first_10_kg' => null, 'addl_1_kg_after_10' => null,
        'first_20_kg' => null, 'addl_1_kg_after_20' => null,
    ]
];

foreach ($zonesData as $zone) {
    $zone['rate_card_id'] = $rateCard->id;
    RateCardZone::create($zone);
}

echo "Official rate card seeded successfully.\n";
