<?php
$file = __DIR__ . '/app/Http/Controllers/AdminController.php';
$content = file_get_contents($file);

$newCouriersBlock = <<<'PHP'
        // Couriers performance mock/dynamic blend
        $couriers = DB::table('shipments')
            ->select('courier_partner', 
                DB::raw('count(*) as total'), 
                DB::raw('sum(case when status = "Delivered" then 1 else 0 end) as delivered'),
                DB::raw('sum(case when status IN ("RTO Initiated", "RTO Delivered") then 1 else 0 end) as rto')
            )
            ->whereNotNull('courier_partner')
            ->groupBy('courier_partner')
            ->get();
            
        $courierPerformance = [];
        foreach ($couriers as $c) {
            $efficiency = $c->total > 0 ? round(($c->delivered / $c->total) * 100) : 0;
            $courierPerformance[] = [
                'name' => $c->courier_partner,
                'shipments' => $c->total,
                'accuracy' => $efficiency,
                'rto' => $c->rto
            ];
        }
PHP;

$content = preg_replace('/\/\/ Couriers performance mock.*?\} /is', ltrim($newCouriersBlock) . "\n        ", $content);

file_put_contents($file, $content);
echo "Fixed AdminController.\n";
