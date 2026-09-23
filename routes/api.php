<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Token-based API Routes for E-commerce integrations
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    
    // Developer API
    Route::post('/rates', [\App\Http\Controllers\Api\ShipmentApiController::class, 'calculateRate']);
    Route::post('/shipments/book', [\App\Http\Controllers\Api\ShipmentApiController::class, 'book']);
    Route::get('/track/{awb}', [\App\Http\Controllers\Api\ShipmentApiController::class, 'track']);
    
});
