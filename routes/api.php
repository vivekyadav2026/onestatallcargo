<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public API endpoints (Used by the frontend website rate calculator)
Route::prefix('v1/public')->group(function () {
    Route::post('/rates', [\App\Http\Controllers\Api\V1\RateCalculatorController::class, 'calculate']);

    // Shipment Management APIs
    Route::get('/shipments', [\App\Http\Controllers\Api\V1\ShipmentApiController::class, 'index']);
    Route::post('/shipments', [\App\Http\Controllers\Api\V1\ShipmentApiController::class, 'store']);
    Route::get('/shipments/{awb}', [\App\Http\Controllers\Api\V1\ShipmentApiController::class, 'show']);
    Route::post('/shipments/{awb}/cancel', [\App\Http\Controllers\Api\V1\ShipmentApiController::class, 'cancel']);
});

// Token-based API Routes for E-commerce integrations and external customers
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    
    // User Info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rate Calculator API (Calls the Pricing Engine securely for Sellers)
    Route::post('/rates', [\App\Http\Controllers\Api\V1\RateCalculatorController::class, 'calculate']);

    // Shipment Management APIs
    Route::get('/shipments', [\App\Http\Controllers\Api\V1\ShipmentApiController::class, 'index']);
    Route::post('/shipments', [\App\Http\Controllers\Api\V1\ShipmentApiController::class, 'store']);
    Route::get('/shipments/{awb}', [\App\Http\Controllers\Api\V1\ShipmentApiController::class, 'show']);
    Route::post('/shipments/{awb}/cancel', [\App\Http\Controllers\Api\V1\ShipmentApiController::class, 'cancel']);
    
});


Route::prefix('v1/external')->group(function () {
    Route::post('/serviceability', [\App\Http\Controllers\Api\V1\ExternalCourierController::class, 'checkServiceability']);
    Route::post('/rate', [\App\Http\Controllers\Api\V1\ExternalCourierController::class, 'calculateRate']);
    Route::post('/shipment', [\App\Http\Controllers\Api\V1\ExternalCourierController::class, 'createShipment']);
    Route::get('/track/{awb}', [\App\Http\Controllers\Api\V1\ExternalCourierController::class, 'trackShipment']);
});
