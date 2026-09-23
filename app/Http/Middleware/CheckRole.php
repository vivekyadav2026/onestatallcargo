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

        // SUPER ADMIN OVERRIDE: 'admin' role has absolute permission to bypass and view any portal
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Check if user's role is in the allowed roles for this route
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // If unauthorized, redirect to their respective dashboard
        switch ($user->role) {
            case 'operations': return redirect('/admin/dashboard');
            case 'seller': 
            case 'aggregator':
            case 'b2b_customer':
            case 'corporate': 
                return redirect('/seller/dashboard');
            case 'franchise': return redirect('/hub/dashboard');
            case 'pickup_rider':
            case 'delivery_rider':
            case 'rider':
                return redirect('/rider/dashboard');
            case 'b2c_customer':
            case 'courier_partner':
                return redirect('/track');
            default: return redirect('/');
        }
    }
}
