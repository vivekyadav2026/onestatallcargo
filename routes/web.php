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
Route::get('/api-docs', function () { return view('public.developers.docs'); })->name('api-docs');
Route::get('/pricing', function () { return view('pricing'); })->name('pricing');
Route::get('/franchise', function () { return view('franchise'); })->name('franchise');


// Public Redesign Routes
Route::get('/solutions/b2c-shipping', function () { return view('public.solutions.b2c'); })->name('solutions.b2c');
Route::get('/solutions/b2b-cargo', function () { return view('public.solutions.b2b'); })->name('solutions.b2b');
Route::get('/solutions/international-shipping', function () { return view('public.solutions.international'); })->name('solutions.international');
Route::get('/solutions/quick-delivery', function () { return view('public.solutions.quick'); })->name('solutions.quick');
Route::get('/solutions/courier-aggregation', function () { return view('public.solutions.aggregation'); })->name('solutions.aggregation');
Route::get('/solutions/ecommerce-integration', function () { return view('public.solutions.ecommerce'); })->name('solutions.ecommerce');

Route::get('/platform/video-evidence', function () { return view('public.platform.evidence'); })->name('platform.evidence');
Route::get('/platform/ndr-management', function () { return view('public.platform.ndr'); })->name('platform.ndr');
Route::get('/platform/rto-management', function () { return view('public.platform.rto'); })->name('platform.rto');
Route::get('/platform/cod-settlement', function () { return view('public.platform.cod'); })->name('platform.cod');
Route::get('/platform/live-tracking', function () { return view('public.platform.tracking'); })->name('platform.tracking');
Route::get('/platform/awb-labels', function () { return view('public.platform.awb'); })->name('platform.awb');

Route::get('/developers', function () { return view('public.developers.index'); })->name('developers');
Route::get('/docs', function () { return view('public.developers.docs'); })->name('docs');

Route::get('/corporate', function () { return view('public.corporate'); })->name('corporate');
Route::get('/partners', function () { return view('public.partners'); })->name('partners');
Route::get('/about', function () { return view('public.about'); })->name('about');
Route::get('/faq', function () { return view('public.faq'); })->name('faq');
Route::get('/help', function () { return view('public.help'); })->name('help');
// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/forgot-password', function () { return view('auth.forgot-password'); })->name('password.request');
Route::post('/forgot-password', function () { return back()->with('status', 'We have emailed your password reset link! (Demo mode)'); })->name('password.email');

// Dashboard Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () { 
        return redirect('/login'); // Let AuthController redirect based on role instead, or just point to login logic
    })->name('dashboard');

    // ADMIN PORTAL (Requires Admin Role, but handled by Admin role itself)
    Route::middleware(['role:admin,operations'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/live-map', [\App\Http\Controllers\AdminController::class, 'liveMap'])->name('admin.map');
        
        Route::get('/shipments', [\App\Http\Controllers\AdminShipmentController::class, 'index'])->name('admin.shipments.index');
        Route::get('/pickups', [\App\Http\Controllers\AdminPickupController::class, 'index'])->name('admin.pickups.index');
        Route::post('/pickups/assign', [\App\Http\Controllers\AdminPickupController::class, 'assignRider'])->name('admin.pickups.assign');
        Route::get('/ndr', [\App\Http\Controllers\AdminNDRController::class, 'index'])->name('admin.ndr.index');
        Route::post('/ndr/{id}', [\App\Http\Controllers\AdminNDRController::class, 'action'])->name('admin.ndr.action');
        Route::get('/evidence', [\App\Http\Controllers\AdminEvidenceController::class, 'index'])->name('admin.evidence.index');
        
        Route::get('/hubs', [\App\Http\Controllers\AdminHubController::class, 'index'])->name('admin.hubs.index');
        Route::post('/hubs', [\App\Http\Controllers\AdminHubController::class, 'store'])->name('admin.hubs.store');
        Route::post('/hubs/{id}/toggle', [\App\Http\Controllers\AdminHubController::class, 'toggle'])->name('admin.hubs.toggle');
        Route::post('/hubs', [\App\Http\Controllers\AdminHubController::class, 'store'])->name('admin.hubs.store');
        Route::get('/couriers', [\App\Http\Controllers\AdminCourierController::class, 'index'])->name('admin.couriers.index');
        Route::post('/couriers', [\App\Http\Controllers\AdminCourierController::class, 'store'])->name('admin.couriers.store');
        Route::post('/couriers/{id}/toggle', [\App\Http\Controllers\AdminCourierController::class, 'toggle'])->name('admin.couriers.toggle');
        
        Route::get('/sellers', [\App\Http\Controllers\AdminSellerController::class, 'index'])->name('admin.sellers.index');
        Route::post('/sellers', [\App\Http\Controllers\AdminSellerController::class, 'store'])->name('admin.sellers.store');
        Route::post('/sellers/{id}', [\App\Http\Controllers\AdminSellerController::class, 'update'])->name('admin.sellers.update');
        Route::get('/rates', [\App\Http\Controllers\AdminRateController::class, 'index'])->name('admin.rates.index');
        Route::post('/rates', [\App\Http\Controllers\AdminRateController::class, 'store'])->name('admin.rates.store');
        Route::post('/rates/{id}', [\App\Http\Controllers\AdminRateController::class, 'update'])->name('admin.rates.update');
        Route::post('/rates', [\App\Http\Controllers\AdminRateController::class, 'store'])->name('admin.rates.store');
        Route::get('/billing', [\App\Http\Controllers\AdminBillingController::class, 'index'])->name('admin.billing.index');
        Route::get('/integrations', [\App\Http\Controllers\AdminIntegrationController::class, 'index'])->name('admin.integrations');
        Route::get('/riders', [\App\Http\Controllers\AdminRiderController::class, 'index'])->name('admin.riders.index');
        Route::post('/riders', [\App\Http\Controllers\AdminRiderController::class, 'store'])->name('admin.riders.store');
        Route::post('/integrations', [\App\Http\Controllers\AdminIntegrationController::class, 'save'])->name('admin.integrations.save');
        Route::post('/billing/remit/{userId}', [\App\Http\Controllers\AdminBillingController::class, 'remit'])->name('admin.billing.remit');
        
                Route::get('/roles', [\App\Http\Controllers\AdminRoleController::class, 'index'])->name('admin.roles.index');
        Route::get('/roles/{id}/edit', [\App\Http\Controllers\AdminRoleController::class, 'edit'])->name('admin.roles.edit');
        Route::put('/roles/{id}', [\App\Http\Controllers\AdminRoleController::class, 'update'])->name('admin.roles.update');
        Route::get('/reports', [\App\Http\Controllers\AdminReportController::class, 'index'])->name('admin.reports.index');
        Route::get('/reports/export', [\App\Http\Controllers\AdminReportController::class, 'exportCsv'])->name('admin.reports.export');
        
        // Admin KYC Verification
        Route::get('/kyc', [\App\Http\Controllers\KycController::class, 'adminIndex'])->name('admin.kyc.index');
        Route::post('/kyc/{id}/approve', [\App\Http\Controllers\KycController::class, 'adminApprove'])->name('admin.kyc.approve');
        Route::post('/kyc/{id}/reject', [\App\Http\Controllers\KycController::class, 'adminReject'])->name('admin.kyc.reject');
    });

    // SELLER PORTAL (Requires Seller Role)
    Route::middleware(['role:seller,aggregator,b2b_customer,corporate'])->prefix('seller')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\SellerDashboardController::class, 'index'])->name('seller.dashboard');
        
        // KYC Submission
        Route::post('/kyc/submit', [\App\Http\Controllers\KycController::class, 'submit'])->name('seller.kyc.submit');
        
        // Premium Views
        Route::view('/tools', 'seller.tools')->name('seller.tools');
        Route::view('/settings', 'seller.settings')->name('seller.settings');
        
        // Settings API
        Route::post('/settings/profile', [\App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('seller.settings.profile');
        Route::post('/settings/bank', [\App\Http\Controllers\SettingsController::class, 'updateBankDetails'])->name('seller.settings.bank');
        Route::post('/settings/password', [\App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('seller.settings.password');
        Route::post('/settings/warehouses', [\App\Http\Controllers\SettingsController::class, 'createWarehouse'])->name('seller.settings.warehouses');
        Route::put('/settings/warehouses/{id}', [\App\Http\Controllers\SettingsController::class, 'updateWarehouse'])->name('seller.settings.warehouses.update');
        Route::delete('/settings/warehouses/{id}', [\App\Http\Controllers\SettingsController::class, 'deleteWarehouse'])->name('seller.settings.warehouses.delete');
        Route::post('/settings/warehouses/{id}/default', [\App\Http\Controllers\SettingsController::class, 'setDefaultWarehouse'])->name('seller.settings.warehouses.default');
        Route::post('/settings/api-keys', [\App\Http\Controllers\SettingsController::class, 'generateApiKey'])->name('seller.settings.api-keys');
        Route::delete('/settings/api-keys/{id}', [\App\Http\Controllers\SettingsController::class, 'deleteApiKey'])->name('seller.settings.api-keys.delete');
        
        Route::view('/wallet', 'seller.wallet')->name('seller.wallet');
        
        // Cashfree Wallet Recharge
        Route::post('/wallet/recharge', [\App\Http\Controllers\CashfreeController::class, 'initiateRecharge'])->name('seller.wallet.recharge');
        Route::post('/wallet/verify', [\App\Http\Controllers\CashfreeController::class, 'verifyRecharge'])->name('seller.wallet.verify');
        
        // Bookings
        Route::get('/shipments', [\App\Http\Controllers\SellerShipmentController::class, 'index'])->name('seller.shipments.index');
        Route::post('/shipments/bulk-cancel', [\App\Http\Controllers\SellerShipmentController::class, 'bulkCancel'])->name('seller.shipments.bulk-cancel');
        Route::post('/shipments/{id}/cancel', [\App\Http\Controllers\SellerShipmentController::class, 'cancel'])->name('seller.shipments.cancel');
        Route::get('/book', [\App\Http\Controllers\SellerShipmentController::class, 'create'])->name('seller.book');
        Route::post('/book', [\App\Http\Controllers\SellerShipmentController::class, 'store'])->name('seller.book.post');
        Route::get('/bulk-book', [\App\Http\Controllers\SellerShipmentController::class, 'bulkCreate'])->name('seller.bulk');
        Route::post('/bulk-book', [\App\Http\Controllers\SellerShipmentController::class, 'bulkStore'])->name('seller.bulk.post');
        Route::get('/ndr', [\App\Http\Controllers\SellerNdrController::class, 'index'])->name('seller.ndr');
        Route::post('/ndr/{awb}', [\App\Http\Controllers\SellerNdrController::class, 'action'])->name('seller.ndr.post');
        
        // Print Label
        Route::get('/shipment/{awb}/label', [\App\Http\Controllers\SellerShipmentController::class, 'printLabel'])->name('seller.label');
        Route::get('/shipment/{awb}/lr', [\App\Http\Controllers\SellerShipmentController::class, 'printLR'])->name('seller.lr');
        Route::get('/shipment/{awb}/invoice', [\App\Http\Controllers\SellerShipmentController::class, 'printInvoice'])->name('seller.invoice');
        Route::get('/api-keys', [\App\Http\Controllers\SellerApiController::class, 'index'])->name('seller.api-keys');
        Route::get('/integrations', [\App\Http\Controllers\SellerIntegrationController::class, 'index'])->name('seller.integrations');
        Route::post('/integrations', [\App\Http\Controllers\SellerIntegrationController::class, 'save'])->name('seller.integrations.save');
        Route::post('/api-keys/generate', [\App\Http\Controllers\SellerApiController::class, 'generate'])->name('seller.api-keys.generate');
    });

    // HUB PORTAL (Requires Franchise Role)
    Route::middleware(['role:franchise'])->prefix('hub')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\HubDashboardController::class, 'index'])->name('hub.dashboard');
        Route::post('/scan', [\App\Http\Controllers\HubDashboardController::class, 'scan'])->name('hub.scan');
        Route::get('/bagging', [\App\Http\Controllers\HubDashboardController::class, 'bagging'])->name('hub.bagging');
        Route::post('/bagging/create', [\App\Http\Controllers\HubDashboardController::class, 'createBag'])->name('hub.bagging.create');
        Route::post('/bagging/scan', [\App\Http\Controllers\HubDashboardController::class, 'scanToBag'])->name('hub.bagging.scan');
    });

    // RIDER PORTAL (Requires Rider Role)
    Route::middleware(['role:rider,pickup_rider,delivery_rider'])->prefix('rider')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\RiderAppController::class, 'index'])->name('rider.dashboard');
        Route::post('/evidence', [\App\Http\Controllers\RiderAppController::class, 'uploadEvidence'])->name('rider.evidence.upload');
    });
});
