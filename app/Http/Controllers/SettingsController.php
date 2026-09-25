<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Warehouse;

class SettingsController extends Controller
{
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.Auth::id(),
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->company_name = $request->company_name;
        $user->email = $request->email;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Profile updated successfully']);
    }

    public function createWarehouse(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|digits:10',
            'address' => 'required|string|max:1000',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'pincode' => 'required|digits:6',
        ]);

        $warehouse = new Warehouse($request->all());
        $warehouse->user_id = Auth::id();
        
        // If it's the first warehouse, make it default
        if(Warehouse::where('user_id', Auth::id())->count() === 0) {
            $warehouse->is_default = true;
        }

        $warehouse->save();

        return response()->json([
            'success' => true, 
            'message' => 'Warehouse added successfully',
            'warehouse' => $warehouse
        ]);
    }

    public function generateApiKey(Request $request)
    {
        $request->validate([
            'token_name' => 'required|string|max:255'
        ]);

        $user = Auth::user();
        $token = $user->createToken($request->token_name)->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'API Key generated successfully',
            'token' => $token,
            'name' => $request->token_name,
            'created_at' => now()->format('d M Y, h:i A')
        ]);
    }

    public function deleteApiKey($id)
    {
        Auth::user()->tokens()->where('id', $id)->delete();
        return response()->json(['success' => true]);
    }
}
