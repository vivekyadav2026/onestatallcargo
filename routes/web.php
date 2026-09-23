<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

Route::get('/', function () { return view('welcome'); });
Route::get('/contact', function () { return view('contact'); })->name('contact');
Route::get('/services', function () { return view('services'); })->name('services');
Route::get('/track', [\App\Http\Controllers\TrackController::class, 'index'])->name('track');
Route::post('/track', [\App\Http\Controllers\TrackController::class, 'track'])->name('track.post');
Route::get('/api-docs', function () { return view('api-docs'); })->name('api-docs');
Route::get('/pricing', function () { return view('pricing'); })->name('pricing');
Route::get('/franchise', function () { return view('franchise'); })->name('franchise');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/forgot-password', function () { return view('auth.forgot-password'); })->name('password.request');

// Dashboard Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () { 
        return redirect('/login'); // Let AuthController redirect based on role instead, or just point to login logic
    })->name('dashboard');

    // ADMIN PORTAL (Requires Admin Role, but handled by Admin role itself)
    Route::middleware(['role:admin,operations'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        Route::get('/shipments', [\App\Http\Controllers\AdminShipmentController::class, 'index'])->name('admin.shipments.index');
        Route::get('/pickups', [\App\Http\Controllers\AdminPickupController::class, 'index'])->name('admin.pickups.index');
        Route::get('/ndr', [\App\Http\Controllers\AdminNDRController::class, 'index'])->name('admin.ndr.index');
        Route::get('/evidence', [\App\Http\Controllers\AdminEvidenceController::class, 'index'])->name('admin.evidence.index');
        
        Route::get('/hubs', [\App\Http\Controllers\AdminHubController::class, 'index'])->name('admin.hubs.index');
        Route::post('/hubs', [\App\Http\Controllers\AdminHubController::class, 'store'])->name('admin.hubs.store');
        Route::get('/couriers', [\App\Http\Controllers\AdminCourierController::class, 'index'])->name('admin.couriers.index');
        Route::post('/couriers', [\App\Http\Controllers\AdminCourierController::class, 'store'])->name('admin.couriers.store');
        Route::post('/couriers/{id}/toggle', [\App\Http\Controllers\AdminCourierController::class, 'toggle'])->name('admin.couriers.toggle');
        
        Route::get('/sellers', [\App\Http\Controllers\AdminSellerController::class, 'index'])->name('admin.sellers.index');
        Route::get('/rates', [\App\Http\Controllers\AdminRateController::class, 'index'])->name('admin.rates.index');
        Route::post('/rates', [\App\Http\Controllers\AdminRateController::class, 'store'])->name('admin.rates.store');
        Route::get('/billing', [\App\Http\Controllers\AdminBillingController::class, 'index'])->name('admin.billing.index');
        
                Route::get('/roles', [\App\Http\Controllers\AdminRoleController::class, 'index'])->name('admin.roles.index');
        Route::get('/roles/{id}/edit', [\App\Http\Controllers\AdminRoleController::class, 'edit'])->name('admin.roles.edit');
        Route::put('/roles/{id}', [\App\Http\Controllers\AdminRoleController::class, 'update'])->name('admin.roles.update');
        Route::get('/reports', [\App\Http\Controllers\AdminReportController::class, 'index'])->name('admin.reports.index');
    });

    // SELLER PORTAL (Requires Seller Role)
    Route::middleware(['role:seller,aggregator,b2b_customer,corporate'])->prefix('seller')->group(function () {
                Route::get('/dashboard', [\App\Http\Controllers\SellerDashboardController::class, 'index'])->name('seller.dashboard');
        Route::post('/wallet/recharge', [\App\Http\Controllers\SellerDashboardController::class, 'recharge'])->name('seller.wallet.recharge');
        
        // Bookings
        Route::get('/book', [\App\Http\Controllers\SellerShipmentController::class, 'create'])->name('seller.book');
        Route::post('/book', [\App\Http\Controllers\SellerShipmentController::class, 'store'])->name('seller.book.post');
        Route::get('/bulk-book', [\App\Http\Controllers\SellerShipmentController::class, 'bulkCreate'])->name('seller.bulk');
        Route::post('/bulk-book', [\App\Http\Controllers\SellerShipmentController::class, 'bulkStore'])->name('seller.bulk.post');
        Route::get('/ndr', [\App\Http\Controllers\SellerNdrController::class, 'index'])->name('seller.ndr');
        Route::post('/ndr/{awb}', [\App\Http\Controllers\SellerNdrController::class, 'action'])->name('seller.ndr.post');
        
        // Print Label
        Route::get('/shipment/{awb}/label', [\App\Http\Controllers\SellerShipmentController::class, 'printLabel'])->name('seller.label');
        Route::get('/api-keys', [\App\Http\Controllers\SellerApiController::class, 'index'])->name('seller.api-keys');
        Route::post('/api-keys/generate', [\App\Http\Controllers\SellerApiController::class, 'generate'])->name('seller.api-keys.generate');
    });

    // HUB PORTAL (Requires Franchise Role)
    Route::middleware(['role:franchise'])->prefix('hub')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\HubDashboardController::class, 'index'])->name('hub.dashboard');
        Route::post('/scan', [\App\Http\Controllers\HubDashboardController::class, 'scan'])->name('hub.scan');
    });

    // RIDER PORTAL (Requires Rider Role)
    Route::middleware(['role:rider,pickup_rider,delivery_rider'])->prefix('rider')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\RiderAppController::class, 'index'])->name('rider.dashboard');
        Route::post('/evidence', [\App\Http\Controllers\RiderAppController::class, 'uploadEvidence'])->name('rider.evidence.upload');
    });
});











