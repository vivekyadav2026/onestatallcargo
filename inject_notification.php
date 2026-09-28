<?php
$content = file_get_contents("app/Http/Controllers/RiderAppController.php");

$target = "$shipment->status = 'Delivered';";
$replacement = "$shipment->status = 'Delivered';\n                \n                // Trigger Email & SMS\n                app(\App\Services\NotificationService::class)->notifyShipmentUpdate($shipment, \"Good news! Your shipment {$shipment->awb_number} has been successfully delivered.\");";

$content = str_replace($target, $replacement, $content);
file_put_contents("app/Http/Controllers/RiderAppController.php", $content);
