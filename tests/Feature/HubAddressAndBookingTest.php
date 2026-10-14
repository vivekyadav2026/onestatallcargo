<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Kyc;
use App\Models\Franchise;
use App\Models\Warehouse;
use App\Models\Shipment;
use App\Models\RateCard;
use App\Models\RateCardZone;
use App\Models\ServiceablePincode;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HubAddressAndBookingTest extends TestCase
{
    use RefreshDatabase;

    private User $hubUser;
    private Franchise $franchise;
    private RateCard $rateCard;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Hub/Franchise User
        $this->hubUser = User::factory()->create([
            'role' => 'franchise',
            'status' => 'active',
            'wallet_balance' => 1000.00,
        ]);

        Kyc::create([
            'user_id' => $this->hubUser->id,
            'status' => 'approved',
            'document_type' => 'aadhaar'
        ]);

        $this->franchise = Franchise::create([
            'user_id' => $this->hubUser->id,
            'company_name' => 'Jaipur Hub Express',
            'owner_name' => 'Jaipur Hub Manager',
            'phone' => '9876543210',
            'email' => 'jaipurhub@onestall.com',
            'address' => 'MI Road, Jaipur',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'status' => 'approved',
            'franchise_level' => 'district',
            'serviceable_pincodes' => json_encode(['302001', '302002', '302003']),
        ]);

        // 2. Serviceable Pincodes
        ServiceablePincode::create([
            'pincode' => '302001',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'is_active' => true,
        ]);
        ServiceablePincode::create([
            'pincode' => '302002',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'is_active' => true,
        ]);

        // 3. OneStall Active Rate Card
        $this->rateCard = RateCard::create([
            'courier_id' => null,
            'version_name' => 'OneStall Standard',
            'effective_from' => '2026-09-01',
            'is_active' => true,
            'fsc_percent' => 10.00,
            'cod_min_charge' => 20.00,
            'cod_threshold_amount' => 1400.00,
            'cod_percent' => 1.50,
            'dto_multiplier' => 1.30,
            'qc_base_charge' => 35.00,
            'qc_included_params' => 3,
            'qc_additional_param_charge' => 5.00,
            'volumetric_divisor' => 5000.00,
            'gst_percent' => 18.00,
        ]);

        RateCardZone::create([
            'rate_card_id' => $this->rateCard->id,
            'zone_name' => 'Zone 1 - Local',
            'first_0_5_kg' => 40.00,
            'addl_0_5_kg' => 20.00,
            'first_2_kg' => 80.00,
            'addl_1_kg_after_2' => 25.00,
            'first_5_kg' => 150.00,
            'addl_1_kg_after_5' => 30.00,
            'first_10_kg' => 300.00,
            'addl_1_kg_after_10' => 35.00,
            'first_20_kg' => 600.00,
            'addl_1_kg_after_20' => 40.00,
        ]);
    }

    public function test_hub_user_can_create_and_list_warehouses(): void
    {
        $payload = [
            'name' => 'Jaipur Central Hub Warehouse',
            'contact_person' => 'Ramesh Sharma',
            'phone' => '9876543210',
            'address' => 'Plot 12, Transport Nagar',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'pincode' => '302001',
        ];

        $response = $this->actingAs($this->hubUser)
            ->postJson(route('hub.warehouses.store'), $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Warehouse added successfully',
            ]);

        $this->assertDatabaseHas('warehouses', [
            'user_id' => $this->hubUser->id,
            'name' => 'Jaipur Central Hub Warehouse',
            'pincode' => '302001',
            'is_default' => 1,
        ]);

        // Test listing via GET /hub/warehouses
        $listResponse = $this->actingAs($this->hubUser)
            ->getJson(route('hub.warehouses.index'));

        $listResponse->assertStatus(200)
            ->assertJsonStructure(['success', 'warehouses'])
            ->assertJsonCount(1, 'warehouses');
    }

    public function test_hub_user_can_update_and_set_default_and_delete_warehouse(): void
    {
        $wh1 = Warehouse::create([
            'user_id' => $this->hubUser->id,
            'name' => 'Hub WH 1',
            'contact_person' => 'Mohan',
            'phone' => '9876500001',
            'address' => 'Street 1',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'pincode' => '302001',
            'is_default' => true,
        ]);

        $wh2 = Warehouse::create([
            'user_id' => $this->hubUser->id,
            'name' => 'Hub WH 2',
            'contact_person' => 'Sohan',
            'phone' => '9876500002',
            'address' => 'Street 2',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'pincode' => '302002',
            'is_default' => false,
        ]);

        // 1. Update wh1
        $updateResponse = $this->actingAs($this->hubUser)
            ->putJson(route('hub.warehouses.update', $wh1->id), [
                'name' => 'Hub WH 1 Updated',
                'contact_person' => 'Mohan Kumar',
                'phone' => '9876500001',
                'address' => 'Street 1 Updated',
                'city' => 'Jaipur',
                'state' => 'Rajasthan',
                'pincode' => '302001',
            ]);

        $updateResponse->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('warehouses', [
            'id' => $wh1->id,
            'name' => 'Hub WH 1 Updated',
        ]);

        // 2. Set wh2 as default
        $defaultResponse = $this->actingAs($this->hubUser)
            ->postJson(route('hub.warehouses.default', $wh2->id));

        $defaultResponse->assertStatus(200);
        $this->assertEquals(1, $wh2->fresh()->is_default);
        $this->assertEquals(0, $wh1->fresh()->is_default);

        // 3. Delete wh1
        $deleteResponse = $this->actingAs($this->hubUser)
            ->deleteJson(route('hub.warehouses.delete', $wh1->id));

        $deleteResponse->assertStatus(200);
        $this->assertDatabaseMissing('warehouses', ['id' => $wh1->id]);
    }

    public function test_unauthorized_user_cannot_access_hub_warehouses(): void
    {
        // Guest
        $guestResponse = $this->getJson(route('hub.warehouses.index'));
        $guestResponse->assertStatus(401);

        // Non-franchise user (seller)
        $seller = User::factory()->create(['role' => 'seller']);
        $forbiddenResponse = $this->actingAs($seller)
            ->get(route('hub.warehouses.index'));

        // CheckRole middleware throws 403 or redirect
        $this->assertTrue(in_array($forbiddenResponse->getStatusCode(), [403, 302]));
    }

    public function test_warehouse_creation_validation_errors(): void
    {
        $invalidPayload = [
            'name' => '', // required
            'phone' => '123', // digits:10 required
            'pincode' => 'invalid', // digits:6 required
        ];

        $response = $this->actingAs($this->hubUser)
            ->postJson(route('hub.warehouses.store'), $invalidPayload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'phone', 'pincode', 'contact_person', 'address']);
    }

    public function test_hub_booking_form_view_renders_successfully(): void
    {
        $response = $this->actingAs($this->hubUser)
            ->get(route('hub.shipments.create'));

        $response->assertStatus(200);
        $response->assertSee('action="' . route('hub.shipments.store') . '"', false);
        $response->assertSee('Add New Pickup Warehouse');
    }

    public function test_hub_order_booking_creates_shipment_deducts_wallet_and_redirects(): void
    {
        $wh = Warehouse::create([
            'user_id' => $this->hubUser->id,
            'name' => 'Jaipur Main Hub',
            'contact_person' => 'Ramesh Hub',
            'phone' => '9876543210',
            'address' => 'Plot 4, MI Road',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'pincode' => '302001',
            'is_default' => true,
        ]);

        $initialBalance = $this->hubUser->wallet_balance;

        $bookingData = [
            'shipment_type' => 'B2C',
            'order_id' => 'HUB-ORD-1001',
            'receiver_name' => 'Anil Sharma',
            'receiver_phone' => '9829012345',
            'receiver_email' => 'anil@example.com',
            'delivery_address' => 'House 45, Tonk Road',
            'delivery_landmark' => 'Near Metro Pillar 20',
            'delivery_city' => 'Jaipur',
            'delivery_state' => 'Rajasthan',
            'delivery_pincode' => '302002',
            'pickup_location' => $wh->id,
            'weight_kg' => 1.0,
            'length_cm' => 15,
            'width_cm' => 10,
            'height_cm' => 5,
            'payment_mode' => 'Prepaid',
            'invoice_value' => 750.00,
            'product_name' => ['Cotton Shirt'],
            'product_qty' => [1],
            'product_price' => [750.00],
            'ship_now' => '1',
        ];

        $response = $this->actingAs($this->hubUser)
            ->post(route('hub.shipments.store'), $bookingData);

        // Check redirect is to hub shipments list (Orders table)
        $response->assertRedirect(route('hub.shipments'));

        // Verify shipment record in database
        $shipment = Shipment::where('order_id', 'HUB-ORD-1001')->first();
        $this->assertNotNull($shipment);
        $this->assertEquals($this->hubUser->id, $shipment->user_id);
        $this->assertEquals('Anil Sharma', $shipment->receiver_name);
        $this->assertEquals('anil@example.com', $shipment->receiver_email);
        $this->assertEquals('Near Metro Pillar 20', $shipment->delivery_landmark);
        $this->assertEquals('Rajasthan', $shipment->delivery_state);
        $this->assertEquals('302001', $shipment->pickup_pincode);
        $this->assertEquals('302002', $shipment->delivery_pincode);
        $this->assertEquals(15, $shipment->length_cm);
        $this->assertEquals(10, $shipment->width_cm);
        $this->assertEquals(5, $shipment->height_cm);
        $this->assertEquals('Prepaid', $shipment->payment_type);
        $this->assertFalse((bool)$shipment->is_cod);
        $this->assertEquals('Manifested', $shipment->status);

        // Verify wallet was debited
        $updatedBalance = $this->hubUser->fresh()->wallet_balance;
        $this->assertLessThan($initialBalance, $updatedBalance);
        $this->assertEquals($initialBalance - $shipment->total_amount, $updatedBalance);

        // Verify wallet transaction logged
        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $this->hubUser->id,
            'type' => 'debit',
            'reference_id' => $shipment->awb_number,
        ]);

        // Verify ShipmentEvent created
        $this->assertDatabaseHas('shipment_events', [
            'shipment_id' => $shipment->id,
            'status' => 'Manifested',
        ]);
    }

    public function test_hub_cod_order_booking_stores_cod_and_payment_type(): void
    {
        $wh = Warehouse::create([
            'user_id' => $this->hubUser->id,
            'name' => 'Jaipur Hub COD WH',
            'contact_person' => 'Ramesh Hub',
            'phone' => '9876543210',
            'address' => 'Plot 4, MI Road',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'pincode' => '302001',
            'is_default' => true,
        ]);

        $bookingData = [
            'shipment_type' => 'B2C',
            'order_id' => 'HUB-COD-2002',
            'receiver_name' => 'Sunil Verma',
            'receiver_phone' => '9829054321',
            'delivery_address' => 'Flat 101, C-Scheme',
            'delivery_city' => 'Jaipur',
            'delivery_state' => 'Rajasthan',
            'delivery_pincode' => '302001',
            'pickup_location' => $wh->id,
            'weight_kg' => 0.5,
            'payment_mode' => 'COD',
            'cod_amount' => 1200.00,
            'invoice_value' => 1200.00,
            'product_name' => ['Shoes'],
            'product_qty' => [1],
            'product_price' => [1200.00],
            'ship_now' => '0',
        ];

        $response = $this->actingAs($this->hubUser)
            ->post(route('hub.shipments.store'), $bookingData);

        $response->assertRedirect(route('hub.shipments'));

        $shipment = Shipment::where('order_id', 'HUB-COD-2002')->first();
        $this->assertNotNull($shipment);
        $this->assertTrue((bool)$shipment->is_cod);
        $this->assertEquals('COD', $shipment->payment_type);
        $this->assertEquals(1200.00, $shipment->cod_amount);
    }

    public function test_hub_booking_blocked_when_kyc_not_approved(): void
    {
        // Unapproved KYC
        Kyc::where('user_id', $this->hubUser->id)->update(['status' => 'pending']);

        $bookingData = [
            'shipment_type' => 'B2C',
            'order_id' => 'HUB-KYC-FAIL',
            'receiver_name' => 'Test Receiver',
            'receiver_phone' => '9829000000',
            'delivery_address' => 'Test Address',
            'delivery_city' => 'Jaipur',
            'delivery_pincode' => '302001',
            'weight_kg' => 0.5,
        ];

        $response = $this->actingAs($this->hubUser)
            ->post(route('hub.shipments.store'), $bookingData);

        $response->assertStatus(302);
        $response->assertRedirect(route('hub.profile'));
        $this->assertDatabaseMissing('shipments', ['order_id' => 'HUB-KYC-FAIL']);
    }

    public function test_hub_booking_fails_when_wallet_balance_insufficient(): void
    {
        $this->hubUser->update(['wallet_balance' => 0.00]);

        $wh = Warehouse::create([
            'user_id' => $this->hubUser->id,
            'name' => 'Jaipur Hub WH',
            'contact_person' => 'Ramesh Hub',
            'phone' => '9876543210',
            'address' => 'Plot 4, MI Road',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'pincode' => '302001',
        ]);

        $bookingData = [
            'shipment_type' => 'B2C',
            'order_id' => 'HUB-INSUFFICIENT-BAL',
            'receiver_name' => 'Test Receiver',
            'receiver_phone' => '9829000000',
            'delivery_address' => 'Test Address',
            'delivery_city' => 'Jaipur',
            'delivery_pincode' => '302001',
            'pickup_location' => $wh->id,
            'weight_kg' => 0.5,
        ];

        $response = $this->actingAs($this->hubUser)
            ->from(route('hub.shipments.create'))
            ->post(route('hub.shipments.store'), $bookingData);

        $response->assertStatus(302);
        $response->assertSessionHas('error');
        $this->assertEquals(0.00, $this->hubUser->fresh()->wallet_balance);
        $this->assertDatabaseMissing('shipments', ['order_id' => 'HUB-INSUFFICIENT-BAL']);
    }

    public function test_seller_booking_regression_continues_working(): void
    {
        $seller = User::factory()->create([
            'role' => 'seller',
            'status' => 'active',
            'wallet_balance' => 500.00,
        ]);

        Kyc::create([
            'user_id' => $seller->id,
            'status' => 'approved',
            'document_type' => 'aadhaar'
        ]);

        $sellerWh = Warehouse::create([
            'user_id' => $seller->id,
            'name' => 'Seller Warehouse',
            'contact_person' => 'Seller Lead',
            'phone' => '9876599999',
            'address' => 'Bapu Nagar',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'pincode' => '302001',
            'is_default' => true,
        ]);

        $bookingData = [
            'shipment_type' => 'B2C',
            'order_id' => 'SELLER-REGRESSION-01',
            'receiver_name' => 'Deepak Gupta',
            'receiver_phone' => '9829088888',
            'receiver_email' => 'deepak@example.com',
            'delivery_address' => 'Plot 88, Malviya Nagar',
            'delivery_landmark' => 'Near Gaurav Tower',
            'delivery_city' => 'Jaipur',
            'delivery_state' => 'Rajasthan',
            'delivery_pincode' => '302002',
            'pickup_location' => $sellerWh->id,
            'weight_kg' => 0.5,
            'length_cm' => 10,
            'width_cm' => 10,
            'height_cm' => 10,
            'payment_mode' => 'Prepaid',
            'invoice_value' => 500.00,
            'product_name' => ['Book'],
            'product_qty' => [1],
            'product_price' => [500.00],
            'ship_now' => '0',
        ];

        $response = $this->actingAs($seller)
            ->post(route('seller.book.post'), $bookingData);

        $response->assertStatus(302);
        $response->assertRedirect(route('seller.shipments.index'));

        $this->assertDatabaseHas('shipments', [
            'order_id' => 'SELLER-REGRESSION-01',
            'user_id' => $seller->id,
            'receiver_name' => 'Deepak Gupta',
        ]);
    }

    /**
     * Test 11: Hub Ship Now action marks shipment as Pickup Scheduled without print redirection.
     */
    public function test_hub_ship_now_action_schedules_pickup_without_triggering_print(): void
    {
        $shipment = Shipment::create([
            'user_id' => $this->hubUser->id,
            'awb_number' => 'OSCSHIPNOW01',
            'order_id' => 'HUB-SHIP-NOW-01',
            'shipment_type' => 'B2C',
            'receiver_name' => 'Kavita Singh',
            'receiver_phone' => '9829123456',
            'delivery_address' => 'Station Road',
            'delivery_city' => 'Jaipur',
            'delivery_pincode' => '302001',
            'weight_kg' => 1.0,
            'invoice_value' => 500.00,
            'total_amount' => 70.00,
            'status' => 'Manifested',
        ]);

        $response = $this->actingAs($this->hubUser)
            ->from(route('hub.shipments'))
            ->post(route('hub.shipments.shipNow', $shipment->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('hub.shipments'));
        $response->assertSessionHas('success', 'Shipment successfully marked as Pickup Scheduled.');

        $shipment->refresh();
        $this->assertEquals('Pickup Scheduled', $shipment->status);

        $this->assertDatabaseHas('shipment_events', [
            'shipment_id' => $shipment->id,
            'status' => 'Pickup Scheduled',
            'remarks' => 'Shipment successfully marked for pickup.',
        ]);
    }

    /**
     * Test 12: Hub can fetch available couriers and ship now with courier selection.
     */
    public function test_hub_get_couriers_and_ship_now_with_courier_selection(): void
    {
        $shipment = Shipment::create([
            'user_id' => $this->hubUser->id,
            'awb_number' => 'OSCSHIPNOW02',
            'order_id' => 'HUB-SHIP-NOW-02',
            'shipment_type' => 'B2C',
            'receiver_name' => 'Amit Sharma',
            'receiver_phone' => '9829988776',
            'delivery_address' => 'MI Road',
            'delivery_city' => 'Jaipur',
            'delivery_pincode' => '302001',
            'weight_kg' => 1.0,
            'invoice_value' => 600.00,
            'total_amount' => 70.00,
            'status' => 'Manifested',
        ]);

        // 1. Fetch live couriers for shipment
        $courierRes = $this->actingAs($this->hubUser)
            ->getJson(route('hub.shipments.couriers', $shipment->id));

        $courierRes->assertStatus(200);
        $this->assertIsArray($courierRes->json());

        // 2. Ship Now with selected courier and rate
        $this->hubUser->update(['wallet_balance' => 200.00]);

        $response = $this->actingAs($this->hubUser)
            ->from(route('hub.shipments'))
            ->post(route('hub.shipments.shipNow', $shipment->id), [
                'courier_partner' => 'Delhivery Air',
                'shipping_charge' => 90.00,
            ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('hub.shipments'));
        $response->assertSessionHas('success', 'Shipment successfully assigned to Delhivery Air and marked for pickup.');

        $shipment->refresh();
        $this->assertEquals('Pickup Scheduled', $shipment->status);
        $this->assertEquals('Delhivery Air', $shipment->courier_partner);
        $this->assertEquals(90.00, (float)$shipment->shipping_charge);

        $this->assertDatabaseHas('shipment_events', [
            'shipment_id' => $shipment->id,
            'status' => 'Pickup Scheduled',
            'remarks' => 'Shipment assigned to Delhivery Air',
        ]);
    }
}
