<?php
$content = file_get_contents("routes/web.php");

$target = "Route::get('/hubs', [\App\Http\Controllers\AdminHubController::class, 'index'])->name('admin.hubs.index');";
$replacement = "Route::get('/hubs', [\App\Http\Controllers\AdminHubController::class, 'index'])->name('admin.hubs.index');\n        Route::get('/pincodes', [\App\Http\Controllers\AdminPincodeController::class, 'index'])->name('admin.pincodes.index');\n        Route::post('/pincodes', [\App\Http\Controllers\AdminPincodeController::class, 'store'])->name('admin.pincodes.store');\n        Route::post('/pincodes/import', [\App\Http\Controllers\AdminPincodeController::class, 'import'])->name('admin.pincodes.import');\n        Route::post('/pincodes/{id}/delete', [\App\Http\Controllers\AdminPincodeController::class, 'destroy'])->name('admin.pincodes.destroy');";

$content = str_replace($target, $replacement, $content);
file_put_contents("routes/web.php", $content);
