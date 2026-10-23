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
            'id_front' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:4096',
            'id_back' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:4096',
            'pan_doc' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:4096',
            'gst_doc' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:4096',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'ifsc_code' => 'nullable|string|max:11',
            'account_holder_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:1000',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:10',
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

        // Handle Document File Uploads safely with compression
        if ($request->hasFile('id_front')) {
            $kyc->id_front_path = \App\Services\ImageService::uploadAndCompress($request->file('id_front'), 'kyc_docs', 'public');
        }
        if ($request->hasFile('id_back')) {
            $kyc->id_back_path = \App\Services\ImageService::uploadAndCompress($request->file('id_back'), 'kyc_docs', 'public');
        }
        if ($request->hasFile('pan_doc')) {
            $kyc->pan_doc_path = \App\Services\ImageService::uploadAndCompress($request->file('pan_doc'), 'kyc_docs', 'public');
        }
        if ($request->hasFile('gst_doc')) {
            $kyc->gst_doc_path = \App\Services\ImageService::uploadAndCompress($request->file('gst_doc'), 'kyc_docs', 'public');
        }

        $kyc->save();

        // Also update User GSTIN and PAN if provided
        $user->gstin = $kyc->gst_number;
        $user->pan_number = $kyc->pan_number;

        // Save Bank Details if provided
        if ($request->filled('bank_name') && $request->filled('account_number')) {
            $user->bank_name = $request->bank_name;
            $user->account_number = $request->account_number;
            $user->ifsc_code = strtoupper($request->ifsc_code);
            $user->account_holder_name = $request->account_holder_name;
        }

        // Save Registered Business/Pickup Address if provided
        if ($request->filled('address')) {
            $user->company_address = $request->address;
        }
        if ($request->filled('city')) {
            $user->company_city = $request->city;
        }
        if ($request->filled('state')) {
            $user->company_state = $request->state;
        }
        if ($request->filled('pincode')) {
            $user->company_pincode = substr(preg_replace('/[^0-9]/', '', (string)$request->pincode), 0, 6);
        }

        $user->save();

        // If user has no warehouse and address is available, automatically create default warehouse
        if ($user->warehouses()->count() === 0 && !empty($user->company_address) && !empty($user->company_pincode)) {
            \App\Models\Warehouse::create([
                'user_id' => $user->id,
                'name' => $user->company_name ? ($user->company_name . ' Hub') : ($user->name . ' Warehouse'),
                'contact_person' => $user->name,
                'phone' => substr(preg_replace('/[^0-9]/', '', (string)$user->phone), -10) ?: '9999999999',
                'email' => $user->email,
                'address' => $user->company_address,
                'city' => $user->company_city ?: 'City',
                'state' => $user->company_state ?: 'State',
                'pincode' => $user->company_pincode,
                'is_default' => true,
            ]);
        }

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
     * Admin: List all KYC verification requests with search and counts.
     */
    public function adminIndex(Request $request)
    {
        $query = Kyc::with(['user', 'user.warehouses']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('document_number', 'like', "%{$search}%")
                  ->orWhere('pan_number', 'like', "%{$search}%")
                  ->orWhere('gst_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%")
                         ->orWhere('company_name', 'like', "%{$search}%")
                         ->orWhere('company_city', 'like', "%{$search}%")
                         ->orWhere('company_state', 'like', "%{$search}%")
                         ->orWhere('company_pincode', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        } elseif (!$request->has('status')) {
            // Default to pending first
            $query->where('status', 'pending');
        }

        $kycs = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'all' => Kyc::count(),
            'pending' => Kyc::where('status', 'pending')->count(),
            'approved' => Kyc::where('status', 'approved')->count(),
            'rejected' => Kyc::where('status', 'rejected')->count(),
        ];

        return view('admin.kyc.index', compact('kycs', 'counts'));
    }

    /**
     * Admin: Approve a seller's KYC and ensure default warehouse exists.
     */
    public function adminApprove($id)
    {
        $kyc = Kyc::with('user.warehouses')->findOrFail($id);
        $kyc->status = 'approved';
        $kyc->approved_at = now();
        $kyc->rejection_reason = null;
        $kyc->save();

        $user = $kyc->user;
        // Auto-create default warehouse if user does not have any warehouse yet
        if ($user && $user->warehouses->isEmpty() && (!empty($user->company_address) || !empty($user->company_pincode))) {
            \App\Models\Warehouse::create([
                'user_id' => $user->id,
                'name' => $user->company_name ? ($user->company_name . ' Hub') : ($user->name . ' Warehouse'),
                'contact_person' => $user->name,
                'phone' => substr(preg_replace('/[^0-9]/', '', (string)$user->phone), -10) ?: '9999999999',
                'email' => $user->email,
                'address' => $user->company_address ?: 'Registered Business Address',
                'city' => $user->company_city ?: 'City',
                'state' => $user->company_state ?: 'State',
                'pincode' => substr(preg_replace('/[^0-9]/', '', (string)$user->company_pincode), 0, 6) ?: '110001',
                'is_default' => true,
            ]);
        }

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

    /**
     * Admin: Create/assign a warehouse directly for a seller from KYC review.
     */
    public function adminCreateWarehouse(Request $request, $id)
    {
        $kyc = Kyc::findOrFail($id);
        $user = $kyc->user;

        $request->validate([
            'warehouse_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:1000',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'pincode' => 'required|string|max:10',
        ]);

        $cleanedPhone = substr(preg_replace('/[^0-9]/', '', (string)$request->phone), -10) ?: '9999999999';
        $cleanedPin = substr(preg_replace('/[^0-9]/', '', (string)$request->pincode), 0, 6);

        $isFirst = $user->warehouses()->count() === 0;

        $warehouse = \App\Models\Warehouse::create([
            'user_id' => $user->id,
            'name' => $request->warehouse_name,
            'contact_person' => $request->contact_person,
            'phone' => $cleanedPhone,
            'email' => $user->email,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $cleanedPin,
            'is_default' => $isFirst || $request->boolean('is_default'),
        ]);

        // Also update user company address if currently empty
        if (empty($user->company_address)) {
            $user->company_address = $request->address;
            $user->company_city = $request->city;
            $user->company_state = $request->state;
            $user->company_pincode = $cleanedPin;
            $user->save();
        }

        return back()->with('success', "Warehouse '{$warehouse->name}' added successfully for seller: {$user->name}");
    }
}



