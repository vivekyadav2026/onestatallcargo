<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Bag;
use App\Models\Manifest;
use App\Models\Shipment;

echo "--- HISTORICAL DB AUDIT ---\n";
echo "Bags with franchise_id IS NULL: " . Bag::whereNull('franchise_id')->count() . "\n";
echo "Manifests with franchise_id IS NULL: " . Manifest::whereNull('franchise_id')->count() . "\n";
echo "Bags with hub_id IS NULL: " . Bag::whereNull('hub_id')->count() . "\n";
echo "Manifests with source_hub_id IS NULL: " . Manifest::whereNull('source_hub_id')->count() . "\n";
echo "Bags with invalid destination_hub_id: " . Bag::whereNotNull('destination_hub_id')->whereNotIn('destination_hub_id', \App\Models\Hub::pluck('id'))->count() . "\n";
echo "Manifests with invalid destination_hub_id: " . Manifest::whereNotNull('destination_hub_id')->whereNotIn('destination_hub_id', \App\Models\Hub::pluck('id'))->count() . "\n";
echo "Duplicate bag_id in manifests: " . Manifest::select('bag_id')->groupBy('bag_id')->havingRaw('COUNT(id) > 1')->count() . "\n";
echo "Orphaned shipment bag_id: " . Shipment::whereNotNull('bag_id')->whereNotIn('bag_id', Bag::pluck('id'))->count() . "\n";

echo "--- SCHEMA CHECK ---\n";
$schema = Illuminate\Support\Facades\DB::select("SHOW CREATE TABLE manifests")[0]->{'Create Table'};
if (strpos($schema, 'UNIQUE KEY `manifests_bag_id_unique` (`bag_id`)') !== false) {
    echo "UNIQUE constraint manifests_bag_id_unique exists: PASS\n";
} else {
    echo "UNIQUE constraint manifests_bag_id_unique exists: FAIL\n";
}
