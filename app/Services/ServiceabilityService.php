<?php

namespace App\Services;

use App\Models\Franchise;
use App\Models\Courier;
use Illuminate\Support\Facades\Log;

class ServiceabilityService
{
    /**
     * Determine the fulfillment and routing for a given delivery pincode.
     * Returns a structured array.
     */
    public function determineRouting(string $deliveryPincode): array
    {
        // PRIORITY 1: OneStall Cargo own service/franchise
        $franchiseId = $this->getFranchiseForPincode($deliveryPincode);

        if ($franchiseId) {
            return [
                'serviceable' => true,
                'fulfillment_type' => 'onestall',
                'franchise_id' => $franchiseId,
                'provider_id' => null,
                'reason' => 'OneStall Cargo service available',
            ];
        }

        // PRIORITY 2: External Courier (Fallback)
        $provider = Courier::where('is_active', true)->where('name', '!=', 'Onestall Cargo')->first();
        
        if ($provider) {
             return [
                'serviceable' => true,
                'fulfillment_type' => 'external',
                'franchise_id' => null,
                'provider_id' => $provider->id,
                'reason' => 'OneStall Cargo service unavailable',
            ];
        }
        
        // Final fallback if no external couriers exist, try finding any active.
        $anyProvider = Courier::where('is_active', true)->first();
        if ($anyProvider) {
            return [
                'serviceable' => true,
                'fulfillment_type' => 'external',
                'franchise_id' => null,
                'provider_id' => $anyProvider->id,
                'reason' => 'Fallback provider',
            ];
        }

        return [
            'serviceable' => false,
            'fulfillment_type' => null,
            'franchise_id' => null,
            'provider_id' => null,
            'reason' => 'No active service available for this pincode',
        ];
    }

    public function getFranchiseForPincode(string $pincode): ?int
    {
        // DB-driven check first
        $serviceable = \App\Models\ServiceablePincode::where('pincode', $pincode)
            ->where('is_active', true)
            ->whereNotNull('franchise_id')
            ->first();

        if ($serviceable) {
            $franchise = Franchise::find($serviceable->franchise_id);
            if ($franchise && $franchise->status === 'approved') {
                return $franchise->id;
            }
        }

        // Fallback to legacy string check
        $franchises = Franchise::where('status', 'approved')->get();

        foreach ($franchises as $franchise) {
            $pincodes = $franchise->serviceable_pincodes ?? [];
            if (is_array($pincodes) && in_array($pincode, $pincodes)) {
                return $franchise->id;
            }
            if (is_string($pincodes) && str_contains($pincodes, $pincode)) {
                $pincodesArray = array_map('trim', explode(',', $pincodes));
                if (in_array($pincode, $pincodesArray)) {
                    return $franchise->id;
                }
            }
        }

        return null;
    }
}
