<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminSellerController extends Controller
{
    public function index()
    {
        // Load all users who are sellers
        $sellers = User::where('role', 'seller')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.sellers.index', compact('sellers'));
    }
}
