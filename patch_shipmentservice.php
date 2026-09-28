<?php
$file = 'app/Services/ShipmentService.php';
$content = file_get_contents($file);

// Replace createShipment to include WebhookService
$pattern = '/public function createShipment\(array \, int \\): Shipment\s*\{/s';
$replacement = <<<EOD
    public function createShipment(array \, int \): Shipment
    {
EOD;

$content = preg_replace($pattern, $replacement, $content);

// We need to trigger webhook
$pattern2 = '/\->save\(\);\s*return \;/s';
$replacement2 = <<<EOD
        \->save();
        
        // Dispatch webhook
        \ = app(WebhookService::class);
        \->dispatchEvent(\, 'shipment.created', \->formatShipmentPayload(\));

        return \;
EOD;

$content = preg_replace($pattern2, $replacement2, $content);

file_put_contents($file, $content);
echo "Patched ShipmentService\n";
