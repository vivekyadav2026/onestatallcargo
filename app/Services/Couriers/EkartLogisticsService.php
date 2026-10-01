<?php
namespace App\Services\Couriers;

class EkartLogisticsService implements CourierInterface
{
    protected array $credentials;

    public function __construct(array $credentials = [])
    {
        $this->credentials = $credentials;
    }

    public function checkServiceability(string $pincode): bool
    {
        return true;
    }

    public function calculateRate(string $pickup_pincode, string $delivery_pincode, float $weight, bool $is_cod = false, float $invoice_value = 0): array
    {
        $baseFreight = 44.00 + (max(0, $weight - 0.5) * 34.00);
        $fsc = round($baseFreight * 0.10, 2);
        
        $codCharge = 0;
        if ($is_cod && $invoice_value > 0) {
            $codCharge = max(30.00, round($invoice_value * 0.015, 2));
        }

        $total = $baseFreight + $fsc + $codCharge;

        return [
            'status'         => 'success',
            'provider'       => 'Ekart Logistics',
            'zone'           => 'Surface Express',
            'weight_charged' => $weight,
            'base_freight'   => $baseFreight,
            'fsc'            => $fsc,
            'cod_charge'     => $codCharge,
            'base_rate'      => $total,
            'total'          => $total,
            'breakdown'      => [
                'Base Freight' => "₹{$baseFreight}",
                'FSC (10%)'    => "₹{$fsc}",
                'COD Charge'   => $is_cod ? "₹{$codCharge}" : 'N/A',
                'Total'        => "₹{$total}"
            ]
        ];
    }

    public function createShipment(array $shipmentDetails): array
    {
        return [];
    }

    public function trackShipment(string $awb): array
    {
        return [
            'status' => 'success',
            'tracking_data' => [
                'current_status' => 'In Transit (Ekart Logistics)',
                'scans' => []
            ]
        ];
    }
}
