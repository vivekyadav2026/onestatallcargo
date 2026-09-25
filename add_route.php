<?php
$file = 'routes/web.php';
$content = file_get_contents($file);
$target = "Route::get('/shipments', [\\App\\Http\\Controllers\\SellerShipmentController::class, 'index'])->name('seller.shipments.index');";
$replace = "Route::get('/shipments', [\\App\\Http\\Controllers\\SellerShipmentController::class, 'index'])->name('seller.shipments.index');\n        Route::post('/shipments/bulk-cancel', [\\App\\Http\\Controllers\\SellerShipmentController::class, 'bulkCancel'])->name('seller.shipments.bulk-cancel');";
$content = str_replace($target, $replace, $content);
file_put_contents($file, $content);
echo "Added";
?>
