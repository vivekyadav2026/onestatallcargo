<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use Illuminate\Http\Request;

class AdminRateController extends Controller
{
    public function index()
    {
        $rates = Rate::orderBy('zone_type')->get();
        return view('admin.rates.index', compact('rates'));
    }
}
