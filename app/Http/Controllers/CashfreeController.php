<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\WalletTransaction;
use Illuminate\Support\Str;

class CashfreeController extends Controller
{
    private function getHeaders()
    {
        // Fetch from database settings
        $appId = \App\Models\Setting::where('key', 'cashfree_app_id')->value('value');
        $secret = \App\Models\Setting::where('key', 'cashfree_secret_key')->value('value');

        // Fallback to TEST_APP_ID if empty
        if (!$appId) {
            $appId = env('CASHFREE_APP_ID', 'TEST_APP_ID');
        }
        if (!$secret) {
            $secret = env('CASHFREE_SECRET_KEY', 'TEST_SECRET');
        }

        return [
            'x-client-id' => $appId,
            'x-client-secret' => $secret,
            'x-api-version' => '2023-08-01',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];
    }

    private function getBaseUrl()
    {
        $env = \App\Models\Setting::where('key', 'cashfree_environment')->value('value') ?: env('CASHFREE_ENV', 'sandbox');
        
        return $env === 'production' 
            ? 'https://api.cashfree.com/pg' 
            : 'https://sandbox.cashfree.com/pg';
    }

    public function initiateRecharge(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        $user = auth()->user();
        $orderId = 'WALLET_' . $user->id . '_' . time() . strtoupper(Str::random(4));

        // Create a pending wallet transaction
        $transaction = new WalletTransaction();
        $transaction->user_id = $user->id;
        $transaction->type = 'credit';
        $transaction->amount = $request->amount;
        $transaction->reference_id = $orderId;
        $transaction->description = 'Wallet Recharge via Cashfree';
        $transaction->status = 'pending';
        $transaction->save();

        $appId = \App\Models\Setting::where('key', 'cashfree_app_id')->value('value');
        $env = \App\Models\Setting::where('key', 'cashfree_environment')->value('value') ?: env('CASHFREE_ENV', 'sandbox');

        // Check if we have valid real credentials
        if (empty($appId) || $appId == 'TEST_APP_ID') {
            // For testing purposes when the user hasn't put real keys yet
            // Just simulate a successful checkout session
            return response()->json([
                'success' => true,
                'payment_session_id' => 'mock_session_id',
                'order_id' => $orderId,
                'environment' => $env,
                'mock_mode' => true
            ]);
        }

        // Real Cashfree API Call
        $payload = [
            'order_id' => $orderId,
            'order_amount' => round($request->amount, 2),
            'order_currency' => 'INR',
            'customer_details' => [
                'customer_id' => 'CUST_' . $user->id,
                'customer_phone' => $user->phone ?? '9999999999',
                'customer_email' => $user->email,
                'customer_name' => $user->name
            ],
            'order_meta' => [
                // If user is redirected on mobile, they come back to wallet page to verify
                'return_url' => route('seller.wallet') . '?order_id={order_id}',
                'notify_url' => url('/api/webhooks/cashfree') // for background verification
            ]
        ];

        $response = Http::withHeaders($this->getHeaders())
            ->post($this->getBaseUrl() . '/orders', $payload);

        if ($response->successful()) {
            $data = $response->json();
            return response()->json([
                'success' => true,
                'payment_session_id' => $data['payment_session_id'],
                'order_id' => $orderId,
                'environment' => env('CASHFREE_ENV', 'sandbox')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to initialize payment gateway. ' . $response->body()
        ], 500);
    }

    public function verifyRecharge(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string'
        ]);

        $orderId = $request->order_id;
        $transaction = WalletTransaction::where('reference_id', $orderId)->firstOrFail();

        // If already processed
        if ($transaction->status === 'success') {
            return response()->json(['success' => true, 'message' => 'Already verified']);
        }

        // Check if mock mode
        if ($request->mock_mode) {
            $transaction->status = 'success';
            $transaction->save();
            
            $user = $transaction->user;
            $user->wallet_balance += $transaction->amount;
            $user->save();

            $transaction->balance_after = $user->wallet_balance;
            $transaction->save();

            return response()->json(['success' => true, 'new_balance' => $user->wallet_balance]);
        }

        // Real Cashfree Verify Call
        $response = Http::withHeaders($this->getHeaders())
            ->get($this->getBaseUrl() . '/orders/' . $orderId);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['order_status']) && $data['order_status'] === 'PAID') {
                $transaction->status = 'success';
                $transaction->save();

                $user = $transaction->user;
                $user->wallet_balance += $transaction->amount;
                $user->save();

                $transaction->balance_after = $user->wallet_balance;
                $transaction->save();

                return response()->json(['success' => true, 'new_balance' => $user->wallet_balance]);
            }
        }

        $transaction->status = 'failed';
        $transaction->save();

        return response()->json(['success' => false, 'message' => 'Payment not verified']);
    }
}
