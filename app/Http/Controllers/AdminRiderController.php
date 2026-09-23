<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminRiderController extends Controller
{
    public function index()
    {
        $riders = User::whereIn('role', ['rider', 'pickup_rider', 'delivery_rider'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(15);
                      
        return view('admin.riders.index', compact('riders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string',
            'password' => 'required|string|min:6',
            'role' => 'required|in:rider,pickup_rider,delivery_rider'
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role']
        ]);

        return back()->with('success', 'Rider profile created successfully.');
    }
}
