<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Franchise;
use App\Models\Shipment;
use App\Models\Kyc;

class EndToEndAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_franchise_interceptor_and_awb_generation()
    {
        $seller = User::factory()->create(['role' => 'seller', 'wallet_balance' => 1000]);
        Kyc::factory()->create(['user_id' => $seller->id, 'status' => 'approved']);
        
        $franchiseUser = User::factory()->create(['role' => 'franchise']);
        $franchise = Franchise::create([
            'user_id' => $franchiseUser->id,
            'company_name' => 'Local Logistics',
            'owner_name' => 'John',
            'phone' => '1234567890',
            'email' => 'john@test.com',
            'address' => 'Test',
            'city' => 'Mumbai',
            'state' => 'MH',
            'status' => 'approved',
            'serviceable_pincodes' => ['400001']
        ]);

        $shipmentService = app(\App\Services\ShipmentService::class);
        
        $shipmentData = [
            'shipment_type' => 'B2C',
            'receiver_name' => 'Test Receiver',
            'receiver_phone' => '9999999999',
            'delivery_address' => 'Test Addr',
            'delivery_city' => 'Mumbai',
            'delivery_pincode' => '400001',
            'pickup_pincode' => '110001',
            'weight_kg' => 2,
            'is_cod' => false,
            'invoice_value' => 500
        ];

        $shipment = $shipmentService->createShipment($shipmentData, $seller->id);

        $this->assertNotNull($shipment->awb_number);
        $this->assertStringStartsWith('OSC', $shipment->awb_number);
        $this->assertEquals($franchise->id, $shipment->franchise_id, 'Franchise interceptor failed.');
        $this->assertEquals('Onestall Franchise', $shipment->courier_partner);
    }
}
