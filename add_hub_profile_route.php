<?php
$content = file_get_contents("routes/web.php");
$target = "Route::get('/dashboard', [\App\Http\Controllers\HubDashboardController::class, 'index'])->name('hub.dashboard');";
$replacement = "Route::get('/dashboard', [\App\Http\Controllers\HubDashboardController::class, 'index'])->name('hub.dashboard');\n        Route::get('/profile', [\App\Http\Controllers\HubDashboardController::class, 'profile'])->name('hub.profile');\n        Route::post('/profile', [\App\Http\Controllers\HubDashboardController::class, 'updateProfile'])->name('hub.profile.update');";
$content = str_replace($target, $replacement, $content);
file_put_contents("routes/web.php", $content);
