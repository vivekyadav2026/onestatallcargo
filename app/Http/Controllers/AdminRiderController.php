<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rider;
use App\Models\Franchise;
use App\Models\Hub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminRiderController extends Controller
{
    public function index()
    {
        $riders = Rider::with(['user', 'franchise', 'hub'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(15)->withQueryString();
        
        $franchises = Franchise::where('status', 'approved')->get();
        $hubs = Hub::where('is_active', true)->get();
                      
        return view('admin.riders.index', compact('riders', 'franchises', 'hubs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:rider,pickup_rider,delivery_rider',
            'franchise_id' => 'nullable|exists:franchises,id',
            'hub_id' => 'nullable|exists:hubs,id',
            'vehicle_type' => 'nullable|string',
            'vehicle_number' => 'nullable|string',
        ]);

        if ($validated['hub_id'] && !$validated['franchise_id']) {
            $hub = Hub::find($validated['hub_id']);
            $franchise = Franchise::where('user_id', $hub->manager_id)->first();
            $validated['franchise_id'] = $franchise ? $franchise->id : null;
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role']
        ]);

        Rider::create([
            'user_id' => $user->id,
            'franchise_id' => $validated['franchise_id'],
            'hub_id' => $validated['hub_id'],
            'vehicle_type' => $validated['vehicle_type'] ?? null,
            'vehicle_number' => $validated['vehicle_number'] ?? null,
            'status' => 'active',
            'is_active' => true
        ]);

        return back()->with('success', 'Rider profile created successfully.');
    }
    
    public function update(Request $request, $id)
    {
        $rider = Rider::with('user')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', Rule::unique('users')->ignore($rider->user_id)],
            'password' => 'nullable|string|min:6',
            'franchise_id' => 'nullable|exists:franchises,id',
            'hub_id' => 'nullable|exists:hubs,id',
            'vehicle_type' => 'nullable|string',
            'vehicle_number' => 'nullable|string',
            'status' => 'required|in:active,suspended',
        ]);

        $userData = [
            'name' => $validated['name'],
            'phone' => $validated['phone']
        ];
        
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }
        
        $rider->user->update($userData);

        $rider->update([
            'franchise_id' => $validated['franchise_id'],
            'hub_id' => $validated['hub_id'],
            'vehicle_type' => $validated['vehicle_type'] ?? null,
            'vehicle_number' => $validated['vehicle_number'] ?? null,
            'status' => $validated['status'],
            'is_active' => ($validated['status'] === 'active')
        ]);

        return back()->with('success', 'Rider profile updated successfully.');
    }

    public function destroy($id)
    {
        $rider = Rider::findOrFail($id);
        $rider->user->delete(); // Casually deletes the user and cascades the rider
        return back()->with('success', 'Rider removed successfully.');
    }
}
