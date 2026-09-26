<?php
namespace App\Services\Couriers;

class XpressbeesService implements CourierInterface
{
    public function __construct(array $credentials) {}
    public function checkServiceability(string $pincode): bool { return true; }
    public function calculateRate(string $pickup_pincode, string $delivery_pincode, float $weight): array
    {
        return [
            'status' => 'success',
            'base_rate' => 35.00 + ($weight * 8),
            'provider' => 'Xpressbees'
        ];
    }
    public function createShipment(array $shipmentDetails): array { return []; }
}

