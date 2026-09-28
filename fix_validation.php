<?php
$content = file_get_contents("app/Http/Controllers/SellerShipmentController.php");
$target = "'delivery_pincode' => 'required|string',";
$replacement = "'delivery_pincode' => 'required|string',\n            'pickup_pincode' => 'nullable|string',\n            'pickup_address' => 'nullable|string',\n            'pickup_city' => 'nullable|string',";
$content = str_replace($target, $replacement, $content);
file_put_contents("app/Http/Controllers/SellerShipmentController.php", $content);
