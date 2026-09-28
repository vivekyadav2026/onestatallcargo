<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Shipment;
use Illuminate\Support\Facades\Log;

class ProcessExternalShipment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $shipment;
    public $tries = 3;
    public $backoff = [30, 60, 120];

    public function __construct(Shipment $shipment)
    {
        $this->shipment = $shipment;
    }

    public function handle()
    {
        try {
            // Mock external API call payload for Delhivery/BlueDart
            $payload = [
                'order' => $this->shipment->order_id,
                'weight' => $this->shipment->weight_kg,
                'consignee' => $this->shipment->receiver_name,
                'destination' => $this->shipment->delivery_pincode,
            ];

            // Simulate External API Delay/Processing
            sleep(2);

            // Simulate Success
            $this->shipment->external_shipment_id = 'EXT-' . strtoupper(uniqid());
            $this->shipment->courier_awb = 'AWB-EXT-' . rand(100000, 999999);
            $this->shipment->status = 'Booked';
            $this->shipment->save();

            Log::info("External Shipment API success for Order ID: {$this->shipment->order_id}");

        } catch (\Exception $e) {
            Log::error("External API Failed for Order ID: {$this->shipment->order_id}. Error: " . $e->getMessage());
            $this->fail($e);
        }
    }
}
