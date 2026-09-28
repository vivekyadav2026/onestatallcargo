<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Shipment;

class ShipmentStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $shipment;
    public $statusMsg;

    public function __construct(Shipment $shipment, $statusMsg = null)
    {
        $this->shipment = $shipment;
        $this->statusMsg = $statusMsg ?? "Your shipment status is now: " . $shipment->status;
    }

    public function build()
    {
        return $this->subject('Shipment Update: ' . $this->shipment->awb_number)
                    ->view('emails.shipment_status');
    }
}
