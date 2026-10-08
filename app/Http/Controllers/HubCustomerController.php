<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Franchise;
use App\Models\Hub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HubCustomerController extends Controller
{
    private function getAuthScope()
    {
        $user = Auth::user();
        $franchise = Franchise::where('user_id', $user->id)->first();
        $isHubManager = Hub::where('manager_id', $user->id)->exists();
        
        if (!$franchise && !$isHubManager) {
            abort(403, 'Unauthorized');
        }

        return [
            'user' => $user,
            'franchise_id' => $franchise ? $franchise->id : null,
            'franchise' => $franchise
        ];
    }

    public function index(Request $request)
    {
        $scope = $this->getAuthScope();
        
        // For Franchise/Hub, show users with role 'customer' 
        // In a real system, you might link them via shipments or a franchise_id column.
        // For simplicity, we show all 'customer' users they have created (created_by) 
        // or just all customers if no direct linkage exists.
        // We will just show all role 'customer' for now, but usually they'd see their own.
        
        $query = User::where('role', 'customer');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

                $customers = $query->withCount([
            'shipments as total_orders',
            'shipments as delivered_orders' => function($q) {
                $q->where('status', 'Delivered');
            }
        ])->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        return view('hub.customers.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20|unique:users',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
        ]);

        return back()->with('success', 'New walk-in customer account created successfully.');
    }
}
