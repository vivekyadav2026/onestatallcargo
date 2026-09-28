<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\ShipmentStatusMail;
use App\Models\Shipment;

class NotificationService
{
    public function notifyShipmentUpdate(Shipment $shipment, $statusMsg = null)
    {
        $message = $statusMsg ?? "Update from OneStall Cargo: Your shipment {$shipment->awb_number} status is now {$shipment->status}.";

        // 1. Send SMS via Twilio
        if (!empty($shipment->receiver_phone)) {
            $twilio = app(TwilioService::class);
            $twilio->sendSms($shipment->receiver_phone, $message);
        }

        // 2. Send Email (If receiver email is stored somewhere, else to Seller)
        // Since shipments table might not have receiver_email, we can notify the Seller.
        if ($shipment->user && !empty($shipment->user->email)) {
            try {
                Mail::to($shipment->user->email)->send(new ShipmentStatusMail($shipment, $message));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Email failed: " . $e->getMessage());
            }
        }
    }
}
