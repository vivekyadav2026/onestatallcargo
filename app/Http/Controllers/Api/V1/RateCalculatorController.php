<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\PricingService;
use Illuminate\Http\Request;

class RateCalculatorController extends Controller
{
    protected $pricingService;

    public function __construct(PricingService $pricingService)
    {
        $this->pricingService = $pricingService;
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'pickup_pincode' => 'required|digits:6',
            'delivery_pincode' => 'required|digits:6',
            'weight' => 'required|numeric|min:0.1',
        ]);

        $rates = $this->pricingService->getAvailableRates(
            $request->pickup_pincode,
            $request->delivery_pincode,
            $request->weight
        );

        if (empty($rates)) {
            return response()->json([
                'success' => false,
                'message' => 'No service available for this route.',
                'data' => []
            ], 404);
        }

        // Only return the final rate to the user (hide the base rate and margin)
        $customerFacingRates = array_map(function($rate) {
            return [
                'courier_name' => $rate['courier_name'],
                'rate' => $rate['final_rate'],
                'estimated_delivery_days' => $rate['estimated_delivery_days']
            ];
        }, $rates);

        return response()->json([
            'success' => true,
            'message' => 'Rates fetched successfully',
            'data' => $customerFacingRates
        ]);
    }
}
