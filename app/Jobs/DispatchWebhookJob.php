<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Webhook;

class DispatchWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $webhook;
    public $event;
    public $payload;

    public $tries = 3;
    public $backoff = [10, 60, 300]; // Retry after 10s, 60s, 300s

    /**
     * Create a new job instance.
     */
    public function __construct(Webhook $webhook, string $event, array $payload)
    {
        $this->webhook = $webhook;
        $this->event = $event;
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $signature = hash_hmac('sha256', json_encode($this->payload), $this->webhook->secret ?? '');

        try {
            $response = Http::timeout(10)->withHeaders([
                'X-OSC-Signature' => $signature,
                'X-OSC-Event' => $this->event,
                'Content-Type' => 'application/json'
            ])->post($this->webhook->endpoint_url, $this->payload);

            DB::table('webhook_logs')->insert([
                'webhook_id' => $this->webhook->id,
                'event' => $this->event,
                'payload' => json_encode($this->payload),
                'response_status' => $response->status(),
                'response_body' => substr($response->body(), 0, 1000),
                'is_successful' => $response->successful(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (!$response->successful()) {
                $this->release($this->backoff[$this->attempts() - 1] ?? 300);
            }
        } catch (\Exception $e) {
            DB::table('webhook_logs')->insert([
                'webhook_id' => $this->webhook->id,
                'event' => $this->event,
                'payload' => json_encode($this->payload),
                'response_status' => null,
                'response_body' => $e->getMessage(),
                'is_successful' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->release($this->backoff[$this->attempts() - 1] ?? 300);
        }
    }
}
