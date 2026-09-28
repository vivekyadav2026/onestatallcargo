<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); $kernel->bootstrap();
Auth::loginUsingId(36);
$c = new \App\Http\Controllers\HubBaggingController();
$r = new \Illuminate\Http\Request(); $r->merge(["awb_number" => "OSC20536"]);
try { $c->addShipment($r, $argv[1]); } catch(\Exception $e) {}
