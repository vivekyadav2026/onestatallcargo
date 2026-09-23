<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // SUPER ADMIN OVERRIDE: 'super_admin' and 'admin' roles have absolute permission to bypass and view any portal
        if (in_array($user->role, ['super_admin', 'admin'])) {
            return $next($request);
        }

        // Check if user's role is in the allowed roles for this route
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // If unauthorized, redirect to their respective dashboard
        switch ($user->role) {
            case 'super_admin':
            case 'admin':
            case 'operations': 
                return redirect()->route('admin.dashboard');
            case 'seller': 
            case 'aggregator':
            case 'b2b_customer':
            case 'corporate': 
                return redirect()->route('seller.dashboard');
            case 'franchise': 
                return redirect()->route('hub.dashboard');
            case 'pickup_rider':
            case 'delivery_rider':
            case 'rider':
                return redirect()->route('rider.dashboard');
            case 'b2c_customer':
            case 'courier_partner':
                return redirect()->route('track');
            default: 
                return redirect('/');
        }
    }
}
