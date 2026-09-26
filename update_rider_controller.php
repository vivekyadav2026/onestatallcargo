<?php
$file = __DIR__ . '/app/Http/Controllers/RiderAppController.php';
$content = file_get_contents($file);

$newMethods = <<<'PHP'
    public function scan()
    {
        return view('rider.scan');
    }

    public function cod()
    {
        $riderId = Auth::id();
        $codShipments = Shipment::where('rider_id', $riderId)
                            ->where('status', 'Delivered')
                            ->where('is_cod', true)
                            ->orderBy('updated_at', 'desc')
                            ->get();
                            
        $totalCollected = $codShipments->sum('invoice_value');
        
        return view('rider.cod', compact('codShipments', 'totalCollected'));
    }

    public function profile()
    {
        $user = Auth::user();
        
        $totalDeliveries = Shipment::where('rider_id', $user->id)
                            ->where('status', 'Delivered')
                            ->count();
                            
        return view('rider.profile', compact('user', 'totalDeliveries'));
    }
PHP;

$content = preg_replace('/}\s*$/', $newMethods . "\n}\n", $content);
file_put_contents($file, $content);
echo "Updated RiderAppController.\n";
