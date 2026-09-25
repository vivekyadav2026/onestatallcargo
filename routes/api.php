<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public API endpoints (Used by the frontend website rate calculator)
Route::prefix('v1/public')->group(function () {
    Route::post('/rates', [\App\Http\Controllers\Api\V1\RateCalculatorController::class, 'calculate']);
});

// Token-based API Routes for E-commerce integrations and external customers
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    
    // User Info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rate Calculator API (Calls the Pricing Engine securely for Sellers)
    Route::post('/rates', [\App\Http\Controllers\Api\V1\RateCalculatorController::class, 'calculate']);
    
});
