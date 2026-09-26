<?php
namespace App\Http\Controllers;

use App\Models\ContactLead;
use Illuminate\Http\Request;

class PublicContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'volume' => 'nullable|string|max:100',
            'message' => 'required|string|max:2000',
        ]);

        ContactLead::create($validated);

        return back()->with('success', 'Thank you for contacting us! Our team will get back to you shortly.');
    }
}

