<?php
namespace App\Services;

use App\Models\Courier;
use App\Models\Rate;

class PricingService
{
    /**
     * Get aggregated rates from all active couriers and add margin
     */
    public function getAvailableRates(string $pickup_pin, string $delivery_pin, float $weight)
    {
        $activeCouriers = Courier::where('is_active', true)->get();
        
        $availableOptions = [];

        foreach ($activeCouriers as $courier) {
            // Instantiate the correct service based on courier name
            $serviceClass = "App\\Services\\Couriers\\" . ucfirst(strtolower($courier->name)) . "Service";
            
            if (class_exists($serviceClass)) {
                $service = new $serviceClass($courier->api_credentials ?? []);
                
                // 1. Check if courier delivers here
                if ($service->checkServiceability($delivery_pin)) {
                    
                    // 2. Fetch the raw rate from provider
                    $rateResponse = $service->calculateRate($pickup_pin, $delivery_pin, $weight);
                    
                    if ($rateResponse['status'] === 'success') {
                        $base_rate = $rateResponse['base_rate'];
                        
                        // 3. APPLY AGGREGATOR MARGIN (OneStall Cargo's profit)
                        // Example: Add 15% flat margin or refer to `rates` table
                        $margin_percentage = 15;
                        $final_rate = $base_rate + ($base_rate * ($margin_percentage / 100));

                        $availableOptions[] = [
                            'courier_id' => $courier->id,
                            'courier_name' => $courier->name,
                            'base_rate' => round($base_rate, 2),
                            'final_rate' => round($final_rate, 2),
                            'margin_earned' => round($final_rate - $base_rate, 2),
                            'estimated_delivery_days' => 3 // Dummy logic
                        ];
                    }
                }
            }
        }

        // Sort by cheapest first
        usort($availableOptions, function($a, $b) {
            return $a['final_rate'] <=> $b['final_rate'];
        });

        return $availableOptions;
    }
}
