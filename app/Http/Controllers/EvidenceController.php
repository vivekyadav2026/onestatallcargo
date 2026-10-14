<?php

namespace App\Http\Controllers;

use App\Models\MediaEvidence;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EvidenceController extends Controller
{
    /**
     * Store new video/image evidence for a shipment
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipment_id' => 'required|exists:shipments,id',
            'media_file' => 'required|file|mimes:mp4,mov,avi,jpg,jpeg,png|max:20480', // 20MB max for video
            'type' => 'required|in:pickup,delivery,hub_inward,ndr_issue,damage_claim',
            'remarks' => 'nullable|string'
        ]);

        $user = auth()->user();
        $shipment = Shipment::findOrFail($request->shipment_id);

        // Authorize: Admin, Owner Seller, Assigned Rider, or Assigned Franchise Hub
        $isAdmin = in_array($user->role, ['admin', 'super_admin', 'operations']);
        $isOwner = $shipment->user_id === $user->id;
        $isAssignedRider = $shipment->rider_id === $user->id;
        $franchise = \App\Models\Franchise::where('user_id', $user->id)->first();
        $isFranchise = $franchise && $shipment->franchise_id === $franchise->id;

        if (!$isAdmin && !$isOwner && !$isAssignedRider && !$isFranchise) {
            abort(403, 'Unauthorized to upload evidence for this shipment.');
        }

        $file = $request->file('media_file');
        $extension = $file->getClientOriginalExtension();
        $mediaType = in_array(strtolower($extension), ['jpg', 'jpeg', 'png']) ? 'image' : 'video';
        
        $filename = 'evidence_' . time() . '_' . Str::random(5) . '.' . $extension;
        $path = $file->storeAs('evidences', $filename, 'public');

        $evidence = MediaEvidence::create([
            'shipment_id' => $request->shipment_id,
            'user_id' => $user->id,
            'type' => $request->type,
            'media_type' => $mediaType,
            'media_url' => '/storage/' . $path,
            'remarks' => $request->remarks
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Evidence uploaded successfully',
            'data' => $evidence
        ]);
    }

    /**
     * Get evidence for a specific shipment
     */
    public function getForShipment($shipment_id)
    {
        $user = auth()->user();
        $shipment = Shipment::findOrFail($shipment_id);

        $isAdmin = in_array($user->role, ['admin', 'super_admin', 'operations']);
        $isOwner = $shipment->user_id === $user->id;
        $isAssignedRider = $shipment->rider_id === $user->id;
        $franchise = \App\Models\Franchise::where('user_id', $user->id)->first();
        $isFranchise = $franchise && $shipment->franchise_id === $franchise->id;

        if (!$isAdmin && !$isOwner && !$isAssignedRider && !$isFranchise) {
            abort(403, 'Unauthorized to view evidence for this shipment.');
        }

        $evidences = MediaEvidence::with('user')->where('shipment_id', $shipment_id)->latest()->get();
        return response()->json([
            'success' => true,
            'data' => $evidences
        ]);
    }
}
