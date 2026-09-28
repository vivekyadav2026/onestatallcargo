<?php
$content = file_get_contents("routes/web.php");
$target = 'name("hub.fleet.update");';
$replacement = 'name("hub.fleet.update");
        Route::post("/fleet/{id}/delete", [\App\Http\Controllers\HubRiderController::class, "destroy"])->name("hub.fleet.destroy");';
$content = str_replace($target, $replacement, $content);
file_put_contents("routes/web.php", $content);
