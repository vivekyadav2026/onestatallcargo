<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Franchise;
use App\Models\Kyc;
use App\Models\Shipment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FranchiseShipmentSlipDownloadTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;
    private User $sellerUser;
    private User $stateManager;
    private Franchise $stateFranchise;
    private User $district1Manager;
    private Franchise $district1Franchise;
    private User $district2Manager;
    private Franchise $district2Franchise;
    private User $otherStateManager;
    private Franchise $otherStateFranchise;
    private Shipment $shipmentDistrict1;
    private Shipment $shipmentDistrict2;
    private Shipment $shipmentOtherState;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Admin
        $this->adminUser = User::factory()->create(['role' => 'admin', 'status' => 'active']);

        // 2. Seller
        $this->sellerUser = User::factory()->create(['role' => 'seller', 'status' => 'active']);
        Kyc::create(['user_id' => $this->sellerUser->id, 'status' => 'approved', 'document_type' => 'pan']);

        // 3. State Franchise (Rajasthan)
        $this->stateManager = User::factory()->create(['role' => 'franchise', 'status' => 'active']);
        Kyc::create(['user_id' => $this->stateManager->id, 'status' => 'approved', 'document_type' => 'pan']);
        $this->stateFranchise = Franchise::create([
            'user_id' => $this->stateManager->id,
            'company_name' => 'Rajasthan State Apex',
            'owner_name' => 'State Manager',
            'phone' => '9829011111',
            'email' => 'state_apex@onestall.com',
            'address' => 'Jaipur HQ',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'status' => 'approved',
            'franchise_level' => 'state',
        ]);

        // 4. District 1 Franchise (under Rajasthan)
        $this->district1Manager = User::factory()->create(['role' => 'franchise', 'status' => 'active']);
        Kyc::create(['user_id' => $this->district1Manager->id, 'status' => 'approved', 'document_type' => 'pan']);
        $this->district1Franchise = Franchise::create([
            'user_id' => $this->district1Manager->id,
            'parent_franchise_id' => $this->stateFranchise->id,
            'company_name' => 'Jaipur District Hub',
            'owner_name' => 'Jaipur Lead',
            'phone' => '9829022222',
            'email' => 'jaipur_hub@onestall.com',
            'address' => 'MI Road',
            'city' => 'Jaipur',
            'district' => 'Jaipur',
            'state' => 'Rajasthan',
            'status' => 'approved',
            'franchise_level' => 'district',
            'serviceable_pincodes' => ['302001', '302002'],
        ]);

        // 5. District 2 Franchise (under Rajasthan)
        $this->district2Manager = User::factory()->create(['role' => 'franchise', 'status' => 'active']);
        Kyc::create(['user_id' => $this->district2Manager->id, 'status' => 'approved', 'document_type' => 'pan']);
        $this->district2Franchise = Franchise::create([
            'user_id' => $this->district2Manager->id,
            'parent_franchise_id' => $this->stateFranchise->id,
            'company_name' => 'Kota District Hub',
            'owner_name' => 'Kota Lead',
            'phone' => '9829033333',
            'email' => 'kota_hub@onestall.com',
            'address' => 'Aerodrome Circle',
            'city' => 'Kota',
            'district' => 'Kota',
            'state' => 'Rajasthan',
            'status' => 'approved',
            'franchise_level' => 'district',
            'serviceable_pincodes' => ['324005'],
        ]);

        // 6. Other State Franchise (Maharashtra)
        $this->otherStateManager = User::factory()->create(['role' => 'franchise', 'status' => 'active']);
        Kyc::create(['user_id' => $this->otherStateManager->id, 'status' => 'approved', 'document_type' => 'pan']);
        $this->otherStateFranchise = Franchise::create([
            'user_id' => $this->otherStateManager->id,
            'company_name' => 'Maharashtra State Apex',
            'owner_name' => 'MH Manager',
            'phone' => '9829044444',
            'email' => 'mh_state@onestall.com',
            'address' => 'Nariman Point',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'status' => 'approved',
            'franchise_level' => 'state',
        ]);

        // 7. Shipments
        $this->shipmentDistrict1 = Shipment::create([
            'awb_number' => 'OSCJAI001',
            'user_id' => $this->sellerUser->id,
            'franchise_id' => $this->district1Franchise->id,
            'state_franchise_id' => $this->stateFranchise->id,
            'receiver_name' => 'Jaipur Customer',
            'receiver_phone' => '9876543210',
            'delivery_address' => 'Bapu Nagar',
            'delivery_city' => 'Jaipur',
            'delivery_pincode' => '302001',
            'delivery_state' => 'Rajasthan',
            'pickup_address' => 'Seller Warehouse',
            'pickup_city' => 'Delhi',
            'pickup_pincode' => '110001',
            'weight_kg' => 1.5,
            'invoice_value' => 1200.00,
            'total_amount' => 120.00,
            'shipping_charge' => 120.00,
            'status' => 'Manifested',
            'shipment_type' => 'B2C',
            'courier_partner' => 'OneStall Cargo',
        ]);

        $this->shipmentDistrict2 = Shipment::create([
            'awb_number' => 'OSCKOT002',
            'user_id' => $this->sellerUser->id,
            'franchise_id' => $this->district2Franchise->id,
            'state_franchise_id' => $this->stateFranchise->id,
            'receiver_name' => 'Kota Customer',
            'receiver_phone' => '9876543211',
            'delivery_address' => 'Talwandi',
            'delivery_city' => 'Kota',
            'delivery_pincode' => '324005',
            'delivery_state' => 'Rajasthan',
            'pickup_address' => 'Seller Warehouse',
            'pickup_city' => 'Delhi',
            'pickup_pincode' => '110001',
            'weight_kg' => 2.0,
            'invoice_value' => 2500.00,
            'total_amount' => 180.00,
            'shipping_charge' => 180.00,
            'status' => 'In Transit',
            'shipment_type' => 'B2C',
            'courier_partner' => 'OneStall Cargo',
        ]);

        $this->shipmentOtherState = Shipment::create([
            'awb_number' => 'OSCMUM003',
            'user_id' => $this->sellerUser->id,
            'state_franchise_id' => $this->otherStateFranchise->id,
            'receiver_name' => 'Mumbai Customer',
            'receiver_phone' => '9876543212',
            'delivery_address' => 'Andheri East',
            'delivery_city' => 'Mumbai',
            'delivery_pincode' => '400069',
            'delivery_state' => 'Maharashtra',
            'pickup_address' => 'Seller Warehouse',
            'pickup_city' => 'Pune',
            'pickup_pincode' => '411001',
            'weight_kg' => 1.0,
            'invoice_value' => 800.00,
            'total_amount' => 95.00,
            'shipping_charge' => 95.00,
            'status' => 'Manifested',
            'shipment_type' => 'B2C',
            'courier_partner' => 'OneStall Cargo',
        ]);
    }

    public function test_unauthenticated_request_to_slip_download_redirects_to_login(): void
    {
        $response = $this->get(route('hub.label', $this->shipmentDistrict1->awb_number));
        $response->assertRedirect('/login');

        $responseSlip = $this->get(route('hub.slip', $this->shipmentDistrict1->awb_number));
        $responseSlip->assertRedirect('/login');
    }

    public function test_district_franchise_can_download_assigned_shipment_slip(): void
    {
        // Authenticate District 1 manager
        $response = $this->actingAs($this->district1Manager)
            ->get(route('hub.label', $this->shipmentDistrict1->awb_number));

        $response->assertStatus(200);
        $response->assertSee('OSCJAI001');
        $response->assertSee('Jaipur Customer');
        $response->assertSee('ONESTALL CARGO');

        // Test alias route 'hub.slip'
        $responseSlip = $this->actingAs($this->district1Manager)
            ->get(route('hub.slip', $this->shipmentDistrict1->awb_number));
        $responseSlip->assertStatus(200);
        $responseSlip->assertSee('OSCJAI001');
    }

    public function test_district_franchise_can_download_pickup_shipment_slip(): void
    {
        // Create shipment where District 1 is the pickup franchise
        $pickupShipment = Shipment::create([
            'awb_number' => 'OSCPKP004',
            'user_id' => $this->sellerUser->id,
            'pickup_franchise_id' => $this->district1Franchise->id,
            'franchise_id' => $this->district2Franchise->id,
            'receiver_name' => 'Pickup Recipient',
            'receiver_phone' => '9876543219',
            'delivery_address' => 'Kota Addr',
            'delivery_city' => 'Kota',
            'delivery_pincode' => '324005',
            'pickup_address' => 'Jaipur Pickup Point',
            'pickup_city' => 'Jaipur',
            'pickup_pincode' => '302001',
            'weight_kg' => 1.0,
            'invoice_value' => 500.00,
            'total_amount' => 70.00,
            'shipping_charge' => 70.00,
            'status' => 'Manifested',
            'shipment_type' => 'B2C',
        ]);

        $response = $this->actingAs($this->district1Manager)
            ->get(route('hub.label', $pickupShipment->awb_number));

        $response->assertStatus(200);
        $response->assertSee('OSCPKP004');
    }

    public function test_district_franchise_cannot_download_other_district_shipment_slip(): void
    {
        // District 1 tries to download District 2's shipment slip
        $response = $this->actingAs($this->district1Manager)
            ->get(route('hub.label', $this->shipmentDistrict2->awb_number));

        $response->assertStatus(403);
    }

    public function test_state_franchise_can_download_subordinate_district_shipment_slip(): void
    {
        // Rajasthan State Manager can download District 1 shipment
        $responseD1 = $this->actingAs($this->stateManager)
            ->get(route('hub.label', $this->shipmentDistrict1->awb_number));
        $responseD1->assertStatus(200);
        $responseD1->assertSee('OSCJAI001');

        // Rajasthan State Manager can download District 2 shipment
        $responseD2 = $this->actingAs($this->stateManager)
            ->get(route('hub.label', $this->shipmentDistrict2->awb_number));
        $responseD2->assertStatus(200);
        $responseD2->assertSee('OSCKOT002');
    }

    public function test_state_franchise_cannot_download_other_state_shipment_slip(): void
    {
        // Rajasthan State Manager tries to download Mumbai (Maharashtra) shipment
        $response = $this->actingAs($this->stateManager)
            ->get(route('hub.label', $this->shipmentOtherState->awb_number));

        $response->assertStatus(403);
    }

    public function test_admin_can_download_any_shipment_slip_via_admin_and_hub_routes(): void
    {
        // Admin via admin.shipments.label
        $responseAdmin1 = $this->actingAs($this->adminUser)
            ->get(route('admin.shipments.label', $this->shipmentDistrict1->awb_number));
        $responseAdmin1->assertStatus(200);
        $responseAdmin1->assertSee('OSCJAI001');

        // Admin via hub.label
        $responseAdmin2 = $this->actingAs($this->adminUser)
            ->get(route('hub.label', $this->shipmentOtherState->awb_number));
        $responseAdmin2->assertStatus(200);
        $responseAdmin2->assertSee('OSCMUM003');

        // Admin via seller.label
        $responseAdmin3 = $this->actingAs($this->adminUser)
            ->get(route('seller.label', $this->shipmentDistrict2->awb_number));
        $responseAdmin3->assertStatus(200);
        $responseAdmin3->assertSee('OSCKOT002');
    }

    public function test_missing_shipment_returns_404_not_found(): void
    {
        $response = $this->actingAs($this->district1Manager)
            ->get(route('hub.label', 'NONEXISTENT_AWB_999'));

        $response->assertStatus(404);
    }

    public function test_forged_franchise_id_in_request_is_ignored(): void
    {
        // District 1 tries to pass District 2's franchise_id as a query parameter
        $response = $this->actingAs($this->district1Manager)
            ->get(route('hub.label', [
                'awb' => $this->shipmentDistrict2->awb_number,
                'franchise_id' => $this->district2Franchise->id
            ]));

        // Must still be 403 Forbidden because authentication is derived strictly from Auth::user()
        $response->assertStatus(403);
    }
}
