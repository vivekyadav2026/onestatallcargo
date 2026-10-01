<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SellerApiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('seller.api', compact('user'));
    }

    public function generate(Request $request)
    {
        $user = Auth::user();
        
        // Revoke old tokens
        $user->tokens()->delete();
        
        // Generate new Sanctum token
        $token = $user->createToken('ecommerce-api')->plainTextToken;
        
        $user->api_token = $token; 
        $user->save();

        return back()->with('success', 'New API Key generated successfully!')->with('new_token', $token);
    }
}
