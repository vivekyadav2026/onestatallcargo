<?php
$file = 'routes/web.php';
$content = file_get_contents($file);

$riderRoute = <<<EOD
    Route::post('rider/ndr/{awb}', [\App\Http\Controllers\RiderAppController::class, 'autoNdr'])->name('rider.ndr.post');
EOD;

$customerNdrRoute = <<<EOD
Route::get('ndr/resolve/{awb}', [\App\Http\Controllers\PublicContactController::class, 'resolveNdr'])->name('ndr.resolve');
Route::post('ndr/resolve/{awb}', [\App\Http\Controllers\PublicContactController::class, 'submitResolveNdr'])->name('ndr.resolve.submit');
EOD;

// Insert rider route inside rider auth group
if (strpos($content, "Route::post('rider/evidence'") !== false) {
    $content = str_replace("Route::post('rider/evidence', [\App\Http\Controllers\RiderAppController::class, 'uploadEvidence'])->name('rider.evidence.upload');", "Route::post('rider/evidence', [\App\Http\Controllers\RiderAppController::class, 'uploadEvidence'])->name('rider.evidence.upload');\n" . $riderRoute, $content);
}

// Insert customer NDR route at the bottom
$content .= "\n" . $customerNdrRoute . "\n";

file_put_contents($file, $content);
echo "Patched routes/web.php\n";
