<?php

namespace App\Http\Controllers;

use App\Models\Hub;
use Illuminate\Http\Request;

class AdminHubController extends Controller
{
    public function index()
    {
        $hubs = Hub::with('manager')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.hubs.index', compact('hubs'));
    }
}
