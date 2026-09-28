<?php
$content = file_get_contents('app/Http/Controllers/SellerShipmentController.php');

$pattern = '/\$activeCouriers = \\\\App\\\\Models\\\\Courier::where\(\'is_active\', true\)->pluck\(\'name\'\)->toArray\(\);\s*\$carriers = \!empty\(\$activeCouriers\) \? \$activeCouriers : \[\'Onestall Cargo\'\];\s*\$shipment->courier_partner = \$carriers\[array_rand\(\$carriers\)\];/';
$replacement = 'app(\\\App\\\Services\\\ShipmentService::class)->assignRouting($shipment, $shipment->delivery_pincode);';
$content = preg_replace($pattern, $replacement, $content);

$pattern2 = '/\$shipment->courier_partner = \'Delhivery\';/';
$replacement2 = 'app(\\\App\\\Services\\\ShipmentService::class)->assignRouting($shipment, $shipment->delivery_pincode);';
$content = preg_replace($pattern2, $replacement2, $content);

file_put_contents('app/Http/Controllers/SellerShipmentController.php', $content);
echo "Done Seller";

$apiContent = file_get_contents('app/Http/Controllers/Api/ShipmentApiController.php');
$apiPattern = '/\$shipment->status = \'Manifested\';\s*\$shipment->save\(\);/';
$apiReplacement = "\$shipment->status = 'Manifested';\n        app(\\\App\\\Services\\\ShipmentService::class)->assignRouting(\$shipment, \$shipment->delivery_pincode);\n        \$shipment->save();";
$apiContent = preg_replace($apiPattern, $apiReplacement, $apiContent);
file_put_contents('app/Http/Controllers/Api/ShipmentApiController.php', $apiContent);
echo " Done API";
