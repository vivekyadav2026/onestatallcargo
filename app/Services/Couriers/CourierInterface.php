<?php
namespace App\Services\Couriers;

interface CourierInterface
{
    /**
     * Check if a pincode is serviceable
     */
    public function checkServiceability(string $pincode): bool;

    /**
     * Calculate shipping rate with add-ons
     */
    public function calculateRate(string $pickup_pincode, string $delivery_pincode, float $weight, bool $is_cod = false, float $invoice_value = 0): array;

    /**
     * Create a shipment / generate AWB
     */
    public function createShipment(array $shipmentDetails): array;

    /**
     * Track a shipment status
     */
    public function trackShipment(string $awb): array;
}
