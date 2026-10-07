<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckKyc
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Only enforce for sellers and related roles
        if ($user && in_array($user->role, ['seller', 'aggregator', 'b2b_customer', 'corporate'])) {
            
            // Allow them to visit settings and submit KYC
            if ($request->routeIs('seller.settings', 'seller.kyc.submit', 'seller.settings.*')) {
                return $next($request);
            }

            // If no KYC or rejected, block and redirect to KYC page
            if (!$user->kyc || $user->kyc->status === 'rejected') {
                return redirect()->route('seller.settings', ['view' => 'kyc'])
                    ->with('warning', 'Please complete your KYC verification to access this module.');
            }
        }

        return $next($request);
    }
}
