<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ChannelIntegrationService;
use Illuminate\Support\Facades\Log;

class IntegrationWebhookController extends Controller
{
    /**
     * Handle incoming webhook notifications from WooCommerce, Shopify, or Custom stores.
     */
    public function handle(Request $request, string $platform, int $user_id)
    {
        $platform = strtolower($platform);
        $payload = $request->all();

        // If raw json body is sent
        if (empty($payload)) {
            $raw = $request->getContent();
            $payload = json_decode($raw, true) ?? [];
        }

        Log::info("Webhook order received for platform [{$platform}], user [{$user_id}]", [
            'payload_summary' => is_array($payload) ? array_keys($payload) : 'non-array'
        ]);

        try {
            $service = app(ChannelIntegrationService::class);
            $result = $service->handleWebhook($platform, $user_id, $payload);

            return response()->json([
                'success' => true,
                'message' => $result['message'] ?? 'Webhook processed successfully.',
                'data' => $result,
            ], 200);
        } catch (\Throwable $e) {
            Log::error("Webhook error for [{$platform}]: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing webhook: ' . $e->getMessage(),
            ], 400);
        }
    }
}
