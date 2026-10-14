<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Channel;
use App\Services\ChannelIntegrationService;
use Illuminate\Support\Facades\Auth;

class SellerIntegrationController extends Controller
{
    protected ChannelIntegrationService $service;

    public function __construct(ChannelIntegrationService $service)
    {
        $this->service = $service;
    }

    /**
     * Display connected channels and integration options.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $channels = Channel::where('user_id', $user->id)
            ->latest()
            ->get();

        return view('seller.integrations', compact('channels', 'user'));
    }

    /**
     * Connect or save a store integration.
     */
    public function save(Request $request)
    {
        $user = Auth::user();
        $platform = strtolower($request->input('platform', 'woocommerce'));

        try {
            if ($platform === 'woocommerce') {
                $validated = $request->validate([
                    'store_url' => 'required|string',
                    'consumer_key' => 'required|string',
                    'consumer_secret' => 'required|string',
                    'name' => 'nullable|string|max:100',
                ]);
                $channel = $this->service->connectWooCommerce($user, $validated);
                return back()->with('success', "WooCommerce store '{$channel->name}' connected successfully! You can now sync orders or use the Webhook URL.");
            } elseif ($platform === 'shopify') {
                $validated = $request->validate([
                    'store_url' => 'required|string',
                    'access_token' => 'required|string',
                    'name' => 'nullable|string|max:100',
                ]);
                $channel = $this->service->connectShopify($user, $validated);
                return back()->with('success', "Shopify store '{$channel->name}' connected successfully! You can now sync unfulfilled orders.");
            } elseif ($platform === 'custom') {
                $validated = $request->validate([
                    'name' => 'required|string|max:100',
                    'store_url' => 'nullable|string',
                ]);
                $channel = $this->service->connectCustom($user, $validated);
                return back()->with('success', "Custom Integration '{$channel->name}' created! Use your dedicated webhook endpoint to push orders.");
            }

            return back()->with('error', 'Unsupported platform selected.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to connect store: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Manually trigger order sync for a channel.
     */
    public function sync(Request $request, $id)
    {
        $channel = Channel::where('user_id', Auth::id())->findOrFail($id);

        $result = $this->service->syncOrders($channel);

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Disconnect / remove a channel.
     */
    public function disconnect(Request $request, $id)
    {
        $channel = Channel::where('user_id', Auth::id())->findOrFail($id);
        $name = $channel->name;
        $channel->delete();

        return back()->with('success', "Store '{$name}' has been disconnected.");
    }
}
