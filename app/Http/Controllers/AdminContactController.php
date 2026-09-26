<?php
namespace App\Http\Controllers;

use App\Models\ContactLead;
use Illuminate\Http\Request;

class AdminContactController extends Controller
{
    public function index()
    {
        $leads = ContactLead::latest()->paginate(20);
        return view('admin.contacts.index', compact('leads'));
    }

    public function action(Request $request, $id)
    {
        $lead = ContactLead::findOrFail($id);
        $lead->status = $request->input('status', 'Reviewed');
        $lead->save();
        return back()->with('success', 'Lead status updated.');
    }
}

