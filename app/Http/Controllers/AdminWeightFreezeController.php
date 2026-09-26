<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeightFreeze;

class AdminWeightFreezeController extends Controller
{
    public function index(Request $request)
    {
        $freezes = WeightFreeze::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.weight.freeze', compact('freezes'));
    }

    public function action(Request $request, $id)
    {
        $validated = $request->validate([
            'action' => 'required|in:accepted,rejected'
        ]);

        $freeze = WeightFreeze::findOrFail($id);
        $freeze->status = $validated['action'];
        $freeze->save();

        return back()->with('success', 'Weight freeze ' . $validated['action'] . ' successfully.');
    }
}

