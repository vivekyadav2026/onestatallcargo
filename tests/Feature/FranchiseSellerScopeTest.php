<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Franchise;
use App\Models\Kyc;
use App\Models\Warehouse;
use App\Models\Shipment;
use App\Models\ServiceablePincode;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FranchiseSellerScopeTest extends TestCase
{
    use RefreshDatabase;

    private User $stateManager;
    private Franchise $stateFranchise;
    private User $districtManager;
    private Franchise $districtFranchise;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Rajasthan State Franchise
        $this->stateManager = User::factory()->create(['role' => 'franchise', 'status' => 'active']);
        Kyc::create(['user_id' => $this->stateManager->id, 'status' => 'approved', 'document_type' => 'aadhaar']);
        $this->stateFranchise = Franchise::create([
            'user_id' => $this->stateManager->id,
            'company_name' => 'Rajasthan State Apex',
            'owner_name' => 'State Lead',
            'phone' => '9829011111',
            'email' => 'state_lead@onestall.com',
            'address' => 'Jaipur HQ',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'status' => 'approved',
            'franchise_level' => 'state',
            'state_royalty_type' => 'percent',
            'state_royalty_rate' => 5.00,
        ]);

        // 2. Jaipur District Franchise under Rajasthan
        $this->districtManager = User::factory()->create(['role' => 'franchise', 'status' => 'active']);
        Kyc::create(['user_id' => $this->districtManager->id, 'status' => 'approved', 'document_type' => 'aadhaar']);
        $this->districtFranchise = Franchise::create([
            'user_id' => $this->districtManager->id,
            'parent_franchise_id' => $this->stateFranchise->id,
            'company_name' => 'Jaipur District Hub',
            'owner_name' => 'Jaipur Lead',
            'phone' => '9829022222',
            'email' => 'jaipur_dist@onestall.com',
            'address' => 'C-Scheme',
            'city' => 'Jaipur',
            'district' => 'Jaipur Urban',
            'state' => 'Rajasthan',
            'status' => 'approved',
            'franchise_level' => 'district',
            'serviceable_pincodes' => ['302001', '302002'],
            'delivery_commission_type' => 'flat',
            'delivery_commission_rate' => 25.00,
            'pickup_commission_type' => 'flat',
            'pickup_commission_rate' => 15.00,
        ]);

        ServiceablePincode::create([
            'pincode' => '302001',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'franchise_id' => $this->districtFranchise->id,
            'is_active' => true,
        ]);
    }

    public function test_state_franchiser_only_sees_sellers_from_their_own_state()
    {
        // Seller 1: Registered in Rajasthan (State Match)
        $rajasthanSeller = User::factory()->create([
            'name' => 'Rajasthan Handicrafts',
            'role' => 'seller',
            'company_name' => 'Royal Rajasthani Arts',
            'company_city' => 'Jaipur',
            'company_state' => 'Rajasthan',
            'company_pincode' => '302001',
        ]);

        // Seller 2: Registered in Gujarat (Different State)
        $gujaratSeller = User::factory()->create([
            'name' => 'Gujarat Textiles',
            'role' => 'seller',
            'company_name' => 'Ahmedabad Fabrics',
            'company_city' => 'Ahmedabad',
            'company_state' => 'Gujarat',
            'company_pincode' => '380001',
        ]);

        // Seller 3: Unset State / Empty Location
        $unassociatedSeller = User::factory()->create([
            'name' => 'Random Unset Seller',
            'role' => 'seller',
            'company_name' => 'No Location Corp',
            'company_city' => null,
            'company_state' => null,
            'company_pincode' => null,
        ]);

        // Act as Rajasthan State Franchise
        $response = $this->actingAs($this->stateManager)->get(route('hub.sellers.index'));

        $response->assertStatus(200);
        $response->assertSee('Rajasthan State');
        $response->assertSee('Rajasthan Handicrafts');
        $response->assertSee('Royal Rajasthani Arts');

        // Other state and unset sellers must NOT be visible
        $response->assertDontSee('Gujarat Textiles');
        $response->assertDontSee('Ahmedabad Fabrics');
        $response->assertDontSee('Random Unset Seller');
        $response->assertDontSee('No Location Corp');
    }

    public function test_state_franchise_sees_seller_with_warehouse_in_their_state()
    {
        // Seller has corporate HQ in Delhi, but a warehouse in Rajasthan
        $delhiSellerWithRajWarehouse = User::factory()->create([
            'name' => 'National Retailer',
            'role' => 'seller',
            'company_name' => 'All India Goods',
            'company_city' => 'New Delhi',
            'company_state' => 'Delhi',
            'company_pincode' => '110001',
        ]);

        Warehouse::create([
            'user_id' => $delhiSellerWithRajWarehouse->id,
            'name' => 'Jaipur Fulfilment Center',
            'contact_person' => 'Store Manager',
            'phone' => '9829000000',
            'address' => 'Sitapura Industrial Area',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'pincode' => '302022',
            'is_default' => false,
        ]);

        $response = $this->actingAs($this->stateManager)->get(route('hub.sellers.index'));

        $response->assertStatus(200);
        $response->assertSee('National Retailer');
        $response->assertSee('All India Goods');
    }

    public function test_state_franchise_sees_seller_with_shipment_in_their_state()
    {
        // Seller has no state filled, but booked a shipment handled by Rajasthan State Franchise
        $activeSeller = User::factory()->create([
            'name' => 'Active Shipper',
            'role' => 'seller',
            'company_name' => 'Shipper Co',
            'company_city' => null,
            'company_state' => null,
            'company_pincode' => null,
        ]);

        Shipment::create([
            'user_id' => $activeSeller->id,
            'awb_number' => 'OSC_RAJ_TEST_01',
            'state_franchise_id' => $this->stateFranchise->id,
            'delivery_state' => 'Rajasthan',
            'delivery_pincode' => '302001',
            'pickup_pincode' => '302001',
            'receiver_name' => 'Customer A',
            'receiver_phone' => '9899000000',
            'delivery_address' => 'MI Road',
            'delivery_city' => 'Jaipur',
            'weight_kg' => 1.0,
            'status' => 'In Transit',
            'shipment_type' => 'B2C',
            'invoice_value' => 500,
            'shipping_charge' => 100,
            'total_amount' => 100,
        ]);

        $response = $this->actingAs($this->stateManager)->get(route('hub.sellers.index'));

        $response->assertStatus(200);
        $response->assertSee('Active Shipper');
        $response->assertSee('Shipper Co');
    }

    public function test_district_franchise_only_sees_sellers_in_their_district_pincodes()
    {
        // Seller in Jaipur district pincode
        $jaipurSeller = User::factory()->create([
            'name' => 'Jaipur Local Seller',
            'role' => 'seller',
            'company_name' => 'Jaipur Local Mart',
            'company_city' => 'Jaipur',
            'company_state' => 'Rajasthan',
            'company_pincode' => '302001',
        ]);

        // Seller in Jodhpur district (outside Jaipur district)
        $jodhpurSeller = User::factory()->create([
            'name' => 'Jodhpur Handicrafts',
            'role' => 'seller',
            'company_name' => 'Jodhpur Arts',
            'company_city' => 'Jodhpur',
            'company_state' => 'Rajasthan',
            'company_pincode' => '342001',
        ]);

        $response = $this->actingAs($this->districtManager)->get(route('hub.sellers.index'));

        $response->assertStatus(200);
        $response->assertSee('Jaipur Local Seller');
        $response->assertDontSee('Jodhpur Handicrafts');
    }
}
