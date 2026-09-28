<?php
$file = 'app/Http/Controllers/RiderAppController.php';
$content = file_get_contents($file);

$pattern = '/\->save\(\);\s*return back\(\)->with\(\'success\', \'Shipment marked as NDR successfully\.\'\);/s';

$replacement = <<<EOD
        \->save();
        
        \App\Models\ShipmentEvent::create([
            'shipment_id' => \->id,
            'status' => 'NDR',
            'remarks' => 'Rider marked as NDR: ' . \['ndr_reason'],
            'location' => \->delivery_city
        ]);

        \ = app(\App\Services\WebhookService::class);
        \->dispatchEvent(\->user_id, 'ndr.created', \->formatShipmentPayload(\));

        // In a real system, we'd trigger an SMS/Email to the customer here with the link:
        // url('/ndr/resolve/' . \)

        return back()->with('success', 'Shipment marked as NDR successfully.');
EOD;

$content = preg_replace($pattern, $replacement, $content);
file_put_contents($file, $content);
echo "Patched RiderAppController\n";
