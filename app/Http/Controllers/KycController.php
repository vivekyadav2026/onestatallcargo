<?php

namespace App\Http\Controllers;

use App\Models\Kyc;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KycController extends Controller
{
    /**
     * Store or update seller KYC submission.
     */
    public function submit(Request $request)
    {
        $request->validate([
            'business_type' => 'required|string',
            'document_type' => 'required|string',
            'document_number' => 'required|string|max:50',
            'pan_number' => 'required|string|max:10',
            'gst_number' => 'nullable|string|max:15',
            'id_front' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:4096',
            'id_back' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:4096',
            'pan_doc' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:4096',
            'gst_doc' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:4096',
        ]);

        $user = Auth::user();
        $kyc = Kyc::firstOrNew(['user_id' => $user->id]);

        $kyc->business_type = $request->business_type;
        $kyc->document_type = $request->document_type;
        $kyc->document_number = strtoupper($request->document_number);
        $kyc->pan_number = strtoupper($request->pan_number);
        $kyc->gst_number = $request->gst_number ? strtoupper($request->gst_number) : null;
        $kyc->status = 'pending';
        $kyc->rejection_reason = null;

        // Handle Document File Uploads safely
        if ($request->hasFile('id_front')) {
            $kyc->id_front_path = $request->file('id_front')->store('kyc_docs', 'public');
        }
        if ($request->hasFile('id_back')) {
            $kyc->id_back_path = $request->file('id_back')->store('kyc_docs', 'public');
        }
        if ($request->hasFile('pan_doc')) {
            $kyc->pan_doc_path = $request->file('pan_doc')->store('kyc_docs', 'public');
        }
        if ($request->hasFile('gst_doc')) {
            $kyc->gst_doc_path = $request->file('gst_doc')->store('kyc_docs', 'public');
        }

        $kyc->save();

        // Also update User GSTIN and PAN if provided
        $user->gstin = $kyc->gst_number;
        $user->pan_number = $kyc->pan_number;
        $user->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'KYC documents submitted successfully. Verification is under review.',
                'kyc' => $kyc
            ]);
        }

        return back()->with('success', 'KYC documents submitted successfully. Verification is pending review.');
    }

    /**
     * Admin: List all KYC verification requests.
     */
    public function adminIndex(Request $request)
    {
        $query = Kyc::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default to pending first
            $query->orderByRaw("FIELD(status, 'pending', 'rejected', 'approved')");
        }

        $kycs = $query->latest()->paginate(15)->withQueryString();

        return view('admin.kyc.index', compact('kycs'));
    }

    /**
     * Admin: Approve a seller's KYC.
     */
    public function adminApprove($id)
    {
        $kyc = Kyc::findOrFail($id);
        $kyc->status = 'approved';
        $kyc->approved_at = now();
        $kyc->rejection_reason = null;
        $kyc->save();

        return back()->with('success', "KYC approved for seller: {$kyc->user->name}");
    }

    /**
     * Admin: Reject a seller's KYC with reason.
     */
    public function adminReject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $kyc = Kyc::findOrFail($id);
        $kyc->status = 'rejected';
        $kyc->rejection_reason = $request->rejection_reason;
        $kyc->approved_at = null;
        $kyc->save();

        return back()->with('success', "KYC rejected for seller: {$kyc->user->name}");
    }
}
