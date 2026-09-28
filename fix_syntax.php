<?php
$content = file_get_contents("app/Http/Controllers/RiderAppController.php");

$badCode = '            $shipment->status = \'Delivered\';
                app(\App\Services\NotificationService::class)->notifyShipmentUpdate($shipment, \'Good news! Your shipment \' . $shipment->awb_number . \' has been delivered.\');
                
                // Trigger Email & SMS
                app(\App\Services\NotificationService::class)->notifyShipmentUpdate(, "Good news! Your shipment  has been successfully delivered.");';

$goodCode = '            $shipment->status = \'Delivered\';
            app(\App\Services\NotificationService::class)->notifyShipmentUpdate($shipment, \'Good news! Your shipment \' . $shipment->awb_number . \' has been successfully delivered.\');';

$content = str_replace($badCode, $goodCode, $content);
file_put_contents("app/Http/Controllers/RiderAppController.php", $content);
