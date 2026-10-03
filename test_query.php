<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$franchise = App\Models\Franchise::first();
if (!$franchise) {
    echo "No franchise for user\n";
    exit;
}

$pincodes = is_array($franchise->serviceable_pincodes) ? $franchise->serviceable_pincodes : (json_decode($franchise->serviceable_pincodes, true) ?? []);
var_dump($pincodes);

// Run query
$q = App\Models\Shipment::query();
$q->where(function($query) use ($franchise, $pincodes) {
    $query->where('franchise_id', $franchise->id)
      ->orWhere('user_id', $franchise->user_id);
    if (!empty($pincodes)) {
        $query->orWhereIn('pickup_pincode', $pincodes)
          ->orWhereIn('delivery_pincode', $pincodes);
    }
});

echo "Query SQL:\n";
echo $q->toSql() . "\n";
echo "Bindings:\n";
var_dump($q->getBindings());

echo "Matches: " . $q->count() . "\n";
