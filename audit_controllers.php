<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

function listControllers($dir) {
    $controllers = [];
    foreach (glob($dir . '/*.php') as $file) {
        $controllers[] = basename($file, '.php');
    }
    foreach (glob($dir . '/*', GLOB_ONLYDIR) as $subDir) {
        $subControllers = listControllers($subDir);
        foreach ($subControllers as $c) {
            $controllers[] = basename($subDir) . '\\' . $c;
        }
    }
    return $controllers;
}

$controllers = listControllers(app_path('Http/Controllers'));
foreach ($controllers as $controller) {
    echo $controller . PHP_EOL;
}
