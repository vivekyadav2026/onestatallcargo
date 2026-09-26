<?php
$file = __DIR__ . '/routes/web.php';
$content = file_get_contents($file);

$routes = <<<'PHP'
        Route::get('/roles', [\App\Http\Controllers\AdminRoleController::class, 'index'])->name('admin.roles.index');
        Route::get('/roles/create', [\App\Http\Controllers\AdminRoleController::class, 'create'])->name('admin.roles.create');
        Route::post('/roles', [\App\Http\Controllers\AdminRoleController::class, 'store'])->name('admin.roles.store');
PHP;

$content = str_replace("Route::get('/roles', [\App\Http\Controllers\AdminRoleController::class, 'index'])->name('admin.roles.index');", $routes, $content);

file_put_contents($file, $content);
echo "Routes updated.\n";
