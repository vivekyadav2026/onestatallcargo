<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminIntegrationController extends Controller
{
    public function index()
    {
        return view('admin.integrations');
    }

    public function save(Request $request)
    {
        // Mock saving API keys to config or DB
        return back()->with('success', 'Integration settings updated successfully.');
    }
}
