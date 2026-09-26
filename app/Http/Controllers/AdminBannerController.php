<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class AdminBannerController extends Controller
{
    public function index()
    {
        $banner = Banner::first() ?? new Banner([
            'title' => '₹500 FREE Shipping Credits',
            'subtitle' => 'are sitting in your wallet. Make first recharge of ₹1,000 to unlock.',
            'coupon_code' => 'FIRST1000',
            'button_text' => 'Get My Free Credits',
            'button_link' => '/seller/wallet',
            'bg_gradient' => 'from-[#1d4ed8] via-[#2563eb] to-[#3b82f6]',
            'is_active' => true,
        ]);

        return view('admin.banners.index', compact('banner'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string',
            'coupon_code' => 'nullable|string|max:100',
            'button_text' => 'required|string|max:100',
            'button_link' => 'required|string|max:255',
            'bg_gradient' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $banner = Banner::first();
        if ($banner) {
            $banner->update($validated);
        } else {
            Banner::create($validated);
        }

        return back()->with('success', 'Promo Banner updated successfully! Changes are live on the seller dashboard.');
    }
}
