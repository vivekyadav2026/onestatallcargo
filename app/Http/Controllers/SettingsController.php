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
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'gstin' => 'nullable|string|max:15',
            'pan_number' => 'nullable|string|max:10',
            'business_type' => 'nullable|string|max:255',
            'company_address' => 'nullable|string|max:1000',
            'company_city' => 'nullable|string|max:255',
            'company_state' => 'nullable|string|max:255',
            'company_pincode' => 'nullable|string|max:6',
            'website' => 'nullable|string|max:255',
            'support_phone' => 'nullable|string|max:20',
            'email' => 'required|email|max:255|unique:users,email,'.Auth::id(),
        ]);

        $user = Auth::user();
        $user->fill($request->only([
            'name', 'phone', 'company_name', 'brand_name', 'gstin', 'pan_number',
            'business_type', 'company_address', 'company_city', 'company_state',
            'company_pincode', 'website', 'support_phone', 'email'
        ]));
        $user->save();

        return response()->json(['success' => true, 'message' => 'Company details updated successfully']);
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

    public function updateWarehouse(Request $request, $id)
    {
        $warehouse = Warehouse::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|digits:10',
            'address' => 'required|string|max:1000',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'pincode' => 'required|digits:6',
        ]);

        $warehouse->update($request->only([
            'name', 'contact_person', 'phone', 'address', 'city', 'state', 'pincode'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Warehouse updated successfully',
            'warehouse' => $warehouse
        ]);
    }

    public function deleteWarehouse($id)
    {
        $warehouse = Warehouse::where('user_id', Auth::id())->findOrFail($id);
        $wasDefault = $warehouse->is_default;
        $warehouse->delete();

        // If default warehouse was deleted, pick another one as default
        if ($wasDefault) {
            $next = Warehouse::where('user_id', Auth::id())->first();
            if ($next) {
                $next->is_default = true;
                $next->save();
            }
        }

        return response()->json(['success' => true, 'message' => 'Warehouse deleted successfully']);
    }

    public function setDefaultWarehouse($id)
    {
        Warehouse::where('user_id', Auth::id())->update(['is_default' => false]);
        $warehouse = Warehouse::where('user_id', Auth::id())->findOrFail($id);
        $warehouse->is_default = true;
        $warehouse->save();

        return response()->json(['success' => true, 'message' => 'Default warehouse updated successfully']);
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

    public function updateBankDetails(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'ifsc_code' => 'required|string|max:11',
            'account_holder_name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->bank_name = $request->bank_name;
        $user->account_number = $request->account_number;
        $user->ifsc_code = strtoupper($request->ifsc_code);
        $user->account_holder_name = $request->account_holder_name;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Bank details updated successfully for COD remittances']);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();
        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Current password does not match']);
        }

        $user->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        $user->save();

        return response()->json(['success' => true, 'message' => 'Password changed successfully']);
    }
}
