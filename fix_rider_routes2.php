<?php
$file = __DIR__ . '/routes/web.php';
$content = file_get_contents($file);

$newRoutes = <<<'PHP'
    // RIDER PORTAL (Requires Rider Role)
    Route::middleware(['role:rider,pickup_rider,delivery_rider'])->prefix('rider')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\RiderAppController::class, 'index'])->name('rider.dashboard');
        Route::get('/scan', [\App\Http\Controllers\RiderAppController::class, 'scan'])->name('rider.scan');
        Route::get('/cod', [\App\Http\Controllers\RiderAppController::class, 'cod'])->name('rider.cod');
        Route::get('/profile', [\App\Http\Controllers\RiderAppController::class, 'profile'])->name('rider.profile');
        Route::get('/history', [\App\Http\Controllers\RiderAppController::class, 'history'])->name('rider.history');
        Route::get('/settings', [\App\Http\Controllers\RiderAppController::class, 'settings'])->name('rider.settings');
        Route::post('/profile/update', [\App\Http\Controllers\RiderAppController::class, 'updateProfile'])->name('rider.profile.update');
        Route::post('/evidence', [\App\Http\Controllers\RiderAppController::class, 'uploadEvidence'])->name('rider.evidence.upload');
PHP;

$content = preg_replace('/\/\/ RIDER PORTAL.*?Route::post\(\'\/evidence\'.*?;/is', $newRoutes, $content);
file_put_contents($file, $content);
echo "Updated routes.\n";
