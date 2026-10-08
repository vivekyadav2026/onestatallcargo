<?php

namespace App\Http\Controllers;

use App\Models\Hub;
use App\Models\User;
use Illuminate\Http\Request;

class AdminHubController extends Controller
{
    public function index()
    {
        $hubs = Hub::with('manager')->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $managers = User::where('role', 'franchise')->get();
        $pendingFranchises = \App\Models\Franchise::where('status', 'pending')->orderBy('created_at', 'desc')->get();
        return view('admin.hubs.index', compact('hubs', 'managers', 'pendingFranchises'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hub_code' => 'required|string|unique:hubs,hub_code|max:20',
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'pincode' => 'required|string|max:20',
            'capacity' => 'required|numeric|min:1',
            'manager_id' => 'nullable|exists:users,id'
        ]);

        Hub::create([
            'hub_code' => strtoupper($validated['hub_code']),
            'name' => $validated['name'],
            'city' => $validated['city'],
            'pincode' => $validated['pincode'],
            'capacity' => $validated['capacity'],
            'manager_id' => $validated['manager_id'],
            'is_active' => true
        ]);

        return back()->with('success', 'Hub / Franchise created successfully.');
    }

    public function toggle($id)
    {
        $hub = Hub::findOrFail($id);
        $hub->is_active = !$hub->is_active;
        $hub->save();

        return back()->with('success', 'Hub status updated.');
    }

    public function approveFranchise($id)
    {
        $franchise = \App\Models\Franchise::findOrFail($id);
        $franchise->status = 'approved';
        $franchise->save();

        $user = \App\Models\User::find($franchise->user_id);
        if ($user) {
            $user->status = 'active';
            $user->save();
        }

        // Optionally, automatically create a Hub here?
        // Let's just approve the application so the user can log in as franchise manager.
        return back()->with('success', 'Franchise application approved! You can now create a Hub and assign this manager.');
    }

    public function rejectFranchise($id)
    {
        $franchise = \App\Models\Franchise::findOrFail($id);
        $franchise->status = 'rejected';
        $franchise->save();

        $user = \App\Models\User::find($franchise->user_id);
        if ($user) {
            $user->status = 'rejected';
            $user->save();
        }

        return back()->with('success', 'Franchise application rejected.');
    }

    public function edit($id)
    {
        $hub = Hub::findOrFail($id);
        $managers = User::where('role', 'franchise')->get();
        return view('admin.hubs.edit', compact('hub', 'managers'));
    }

    public function update(Request $request, $id)
    {
        $hub = Hub::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'pincode' => 'required|string|max:20',
            'capacity' => 'required|numeric|min:1',
            'manager_id' => 'nullable|exists:users,id'
        ]);

        $hub->update($validated);

        return redirect()->route('admin.hubs.index')->with('success', 'Hub updated successfully.');
    }
    public function storeManager(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'password' => 'required|string|min:8'
        ]);

        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'role' => 'franchise'
        ]);

        return back()->with('success', 'Franchise Manager created successfully! You can now assign them to a hub.');
    }
}
