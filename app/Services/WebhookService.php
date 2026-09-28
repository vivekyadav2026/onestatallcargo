<?php

namespace App\Services;

use App\Models\Webhook;
use App\Models\Shipment;
use App\Jobs\DispatchWebhookJob;

class WebhookService
{
    /**
     * Dispatch an event to all subscribed webhooks for a given user.
     *
     * @param int $userId
     * @param string $event (e.g. 'shipment.created')
     * @param array $payload
     */
    public function dispatchEvent(int $userId, string $event, array $payload)
    {
        $webhooks = Webhook::where('user_id', $userId)
                        ->where('is_active', true)
                        ->get();

        foreach ($webhooks as $webhook) {
            $events = is_array($webhook->events) ? $webhook->events : json_decode($webhook->events, true);
            
            // If events is null or empty, subscribe to all, OR check if in array
            if (empty($events) || in_array($event, $events)) {
                DispatchWebhookJob::dispatch($webhook, $event, $payload);
            }
        }
    }

    /**
     * Format a shipment into standard payload
     */
    public function formatShipmentPayload(Shipment $shipment): array
    {
        return [
            'shipment_id' => $shipment->id,
            'order_id' => $shipment->order_id,
            'awb_number' => $shipment->awb_number,
            'status' => $shipment->status,
            'courier_partner' => $shipment->courier_partner,
            'tracking_url' => url('/track?awb=' . $shipment->awb_number),
            'updated_at' => $shipment->updated_at->toIso8601String(),
        ];
    }
}
