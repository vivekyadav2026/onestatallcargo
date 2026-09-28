<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tables = Illuminate\Support\Facades\DB::select('SHOW TABLES');
foreach ($tables as $table) {
    $tableName = (array)$table;
    $tableName = array_values($tableName)[0];
    echo "TABLE: $tableName\n";
    $columns = Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM $tableName");
    foreach ($columns as $column) {
        echo "  - {$column->Field} ({$column->Type})\n";
    }
}
