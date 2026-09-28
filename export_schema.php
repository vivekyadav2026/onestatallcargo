<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = Illuminate\Support\Facades\Schema::getTables();
$schema = [];
foreach ($tables as $table) {
    $tableName = $table['name'];
    $schema[$tableName] = Illuminate\Support\Facades\Schema::getColumnListing($tableName);
}
file_put_contents('schema_audit.json', json_encode($schema, JSON_PRETTY_PRINT));
echo "Schema exported.\n";
