<?php
$content = file_get_contents("routes/web.php");
$replacement = "        // Hub Fleet Management\n        Route::get(\"/fleet\", [\App\Http\Controllers\HubRiderController::class, \"index\"])->name(\"hub.fleet.index\");\n        Route::post(\"/fleet\", [\App\Http\Controllers\HubRiderController::class, \"store\"])->name(\"hub.fleet.store\");\n        Route::post(\"/fleet/{id}/update\", [\App\Http\Controllers\HubRiderController::class, \"update\"])->name(\"hub.fleet.update\");\n\n        // Hub Bagging & Manifests";
$content = str_replace("// Hub Bagging & Manifests", $replacement, $content);
file_put_contents("routes/web.php", $content);

