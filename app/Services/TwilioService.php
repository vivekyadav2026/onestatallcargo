<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected $sid;
    protected $token;
    protected $fromNumber;

    public function __construct()
    {
        $this->sid = env('TWILIO_SID');
        $this->token = env('TWILIO_AUTH_TOKEN');
        $this->fromNumber = env('TWILIO_FROM_NUMBER');
    }

    public function sendSms($to, $message)
    {
        if (empty($this->sid) || empty($this->token)) {
            Log::info("Twilio SMS Ignored (No Config): To {$to}, Msg: {$message}");
            return false;
        }

        // Ensure E.164 format (+91...)
        if (!str_starts_with($to, '+')) {
            $to = '+91' . ltrim($to, '0');
        }

        $url = "https://api.twilio.com/2010-04-01/Accounts/{$this->sid}/Messages.json";

        $response = Http::asForm()->withBasicAuth($this->sid, $this->token)->post($url, [
            'To' => $to,
            'From' => $this->fromNumber,
            'Body' => $message
        ]);

        if ($response->successful()) {
            return true;
        } else {
            Log::error("Twilio SMS Failed: " . $response->body());
            return false;
        }
    }
}
