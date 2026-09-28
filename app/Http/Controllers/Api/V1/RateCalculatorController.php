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
            'payment_mode' => 'nullable|string',
            'shipment_value' => 'nullable|numeric',
        ]);

        $routing = app(\App\Services\ServiceabilityService::class)->determineRouting($request->delivery_pincode);
        if (!$routing['serviceable']) {
            return response()->json([
                'success' => false,
                'message' => 'No service available for this route.',
                'data' => []
            ], 404);
        }

        $l = $request->input('dimensions.l') ?: 10;
        $w = $request->input('dimensions.w') ?: 10;
        $h = $request->input('dimensions.h') ?: 10;
        $is_cod = $request->input('payment_mode') === 'cod';
        $invoice_value = $request->input('shipment_value') ?: 0;

        try {
            $rateData = $this->pricingService->calculateRate(
                $routing['fulfillment_type'],
                $request->pickup_pincode,
                $request->delivery_pincode,
                $request->weight,
                $l, $w, $h, $is_cod, $invoice_value, false, false, 0,
                $routing['provider_id']
            );

            return response()->json([
                'success' => true,
                'message' => 'Rates fetched successfully',
                'data' => [
                    [
                        'courier_name' => $routing['fulfillment_type'] === 'onestall' ? 'OneStall Cargo' : \App\Models\Courier::find($routing['provider_id'])->name,
                        'rate' => $rateData['total'],
                        'estimated_delivery_days' => 3
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Calculation error: ' . $e->getMessage(),
                'data' => []
            ], 404);
        }
    }
}
