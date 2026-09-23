<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SellerIntegrationController extends Controller
{
    public function index()
    {
        return view('seller.integrations');
    }

    public function save(Request $request)
    {
        return back()->with('success', 'E-commerce store connected successfully.');
    }
}
