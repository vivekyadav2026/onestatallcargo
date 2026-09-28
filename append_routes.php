<?php
$content = file_get_contents("routes/web.php");

$routes = "
        // Hub Rider Assignment
        Route::get('/assignments/pickups', [\App\Http\Controllers\HubAssignmentController::class, 'pickups'])->name('hub.assignments.pickups');
        Route::get('/assignments/deliveries', [\App\Http\Controllers\HubAssignmentController::class, 'deliveries'])->name('hub.assignments.deliveries');
        Route::post('/assignments/assign', [\App\Http\Controllers\HubAssignmentController::class, 'assign'])->name('hub.assignments.assign');

        // Hub NDR & RTO
        Route::get('/ndr', [\App\Http\Controllers\HubNdrController::class, 'index'])->name('hub.ndr.index');
        Route::post('/ndr/{id}/action', [\App\Http\Controllers\HubNdrController::class, 'action'])->name('hub.ndr.action');

        // Hub Wallet / Commissions
        Route::get('/wallet', [\App\Http\Controllers\HubWalletController::class, 'index'])->name('hub.wallet.index');
";

if (strpos($content, 'hub.assignments.pickups') === false) {
    $content = str_replace('// Hub Bagging & Manifests', $routes . "\n        // Hub Bagging & Manifests", $content);
    file_put_contents("routes/web.php", $content);
}
