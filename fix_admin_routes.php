<?php
$content = file_get_contents("routes/web.php");
$target = "Route::post('/riders/{id}/delete',";
$replacement = "Route::post('/riders/{id}/update', [\App\Http\Controllers\AdminRiderController::class, 'update'])->name('admin.riders.update');\n        Route::post('/riders/{id}/delete',";
$content = str_replace($target, $replacement, $content);
file_put_contents("routes/web.php", $content);
