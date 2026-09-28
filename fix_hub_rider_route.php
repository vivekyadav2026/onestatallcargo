<?php
$content = file_get_contents("routes/web.php");
$target = "Route::post('/fleet/{id}/update', [\App\Http\Controllers\HubRiderController::class, 'update'])->name('hub.fleet.update');";
$replacement = "Route::post('/fleet/{id}/update', [\App\Http\Controllers\HubRiderController::class, 'update'])->name('hub.fleet.update');\n        Route::post('/fleet/{id}/delete', [\App\Http\Controllers\HubRiderController::class, 'destroy'])->name('hub.fleet.destroy');";
$content = str_replace($target, $replacement, $content);
file_put_contents("routes/web.php", $content);
