<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$models = glob(app_path('Models/*.php'));
foreach ($models as $model) {
    echo basename($model, '.php') . PHP_EOL;
}
