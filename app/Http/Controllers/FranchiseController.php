<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Franchise;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class FranchiseController extends Controller
{
    public function create()
    {
        return view('franchise.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'serviceable_pincodes' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            // Create user account for franchise
            $user = User::create([
                'name' => $validated['owner_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'franchise' // assuming role column exists
            ]);

            // Create franchise record
            $pincodesArray = array_map('trim', explode(',', $validated['serviceable_pincodes']));

            Franchise::create([
                'user_id' => $user->id,
                'company_name' => $validated['company_name'],
                'owner_name' => $validated['owner_name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'status' => 'pending', // Requires admin approval
                'serviceable_pincodes' => $pincodesArray
            ]);

            DB::commit();

            return back()->with('success', 'Franchise application submitted successfully! Our team will contact you soon.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
