<?php
namespace App\Services\Couriers;

class EcomExpressService implements CourierInterface
{
    public function __construct(array $credentials) {}
    public function checkServiceability(string $pincode): bool { return true; }
    public function calculateRate(string $pickup_pincode, string $delivery_pincode, float $weight): array
    {
        return [
            'status' => 'success',
            'base_rate' => 39.00 + ($weight * 9),
            'provider' => 'Ecom Express'
        ];
    }
    public function createShipment(array $shipmentDetails): array { return []; }
}

