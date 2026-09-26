<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminRoleController extends Controller {
    
    public function index() {
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.roles.index', compact('users'));
    }

    public function create() {
        return view('admin.roles.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,operations,seller,aggregator,b2b_customer,b2c_customer,franchise,pickup_rider,delivery_rider,courier_partner,corporate',
            'company_name' => 'nullable|string|max:255',
            'permissions' => 'nullable|array'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'company_name' => $validated['company_name'],
            'wallet_balance' => 0,
            'permissions' => $validated['permissions'] ?? []
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'User created and role assigned successfully.');
    }

    public function edit($id) {
        $user = User::findOrFail($id);
        return view('admin.roles.edit', compact('user'));
    }

    public function update(Request $request, $id) {
        $validated = $request->validate([
            'role' => 'required|string|in:admin,operations,seller,aggregator,b2b_customer,b2c_customer,franchise,pickup_rider,delivery_rider,courier_partner,corporate',
            'company_name' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'wallet_balance' => 'required|numeric|min:0',
            'permissions' => 'nullable|array'
        ]);

        $user = User::findOrFail($id);
        
        // Prevent Super Admin from locking themselves out
        if ($user->id === auth()->id() && $validated['role'] !== 'admin') {
            return back()->with('error', 'You cannot remove your own Super Admin privileges!');
        }

        $user->role = $validated['role'];
        $user->company_name = $validated['company_name'];
        $user->wallet_balance = $validated['wallet_balance'];
        $user->permissions = $validated['permissions'] ?? [];
        $user->save();

        return redirect()->route('admin.roles.index')->with('success', 'User role & permissions updated successfully.');
    }
}
