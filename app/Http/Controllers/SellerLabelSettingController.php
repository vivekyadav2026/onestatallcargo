<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerLabelSettingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $settings = $user->label_settings ?? [
            'label_type' => 'thermal',
            'enable_product_name' => true,
            'enable_consignee_contact' => true,
            'enable_consignee_address' => true,
            'enable_support_contact' => true,
            'enable_support_email' => true,
            'enable_rto_address' => true,
        ];

        return view('seller.label_settings', compact('settings'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'label_type' => 'required|string|in:thermal,single,multiple',
        ]);

        $settings = [
            'label_type' => $validated['label_type'],
            'enable_product_name' => $request->has('enable_product_name'),
            'enable_consignee_contact' => $request->has('enable_consignee_contact'),
            'enable_consignee_address' => $request->has('enable_consignee_address'),
            'enable_support_contact' => $request->has('enable_support_contact'),
            'enable_support_email' => $request->has('enable_support_email'),
            'enable_rto_address' => $request->has('enable_rto_address'),
        ];

        $user->label_settings = $settings;
        $user->save();

        return back()->with('success', 'Label settings saved successfully.');
    }
}
