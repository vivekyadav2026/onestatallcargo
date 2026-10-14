<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Franchise;
use App\Models\Kyc;
use App\Models\Shipment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FranchiseDashboardScopeTest extends TestCase
{
    use RefreshDatabase;

    private User $stateManager;
    private Franchise $stateFranchise;
    private User $district1Manager;
    private Franchise $district1Franchise;
    private User $district2Manager;
    private Franchise $district2Franchise;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. State Franchise
        $this->stateManager = User::factory()->create(['role' => 'franchise', 'status' => 'active']);
        Kyc::create(['user_id' => $this->stateManager->id, 'status' => 'approved', 'document_type' => 'aadhaar']);
        $this->stateFranchise = Franchise::create([
            'user_id' => $this->stateManager->id,
            'company_name' => 'Rajasthan State Apex',
            'owner_name' => 'State Manager',
            'phone' => '9829011111',
            'email' => 'state@onestall.com',
            'address' => 'Jaipur HQ',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'status' => 'approved',
            'franchise_level' => 'state',
            'state_royalty_type' => 'percent',
            'state_royalty_rate' => 5.00,
        ]);

        // 2. District 1 Franchise
        $this->district1Manager = User::factory()->create(['role' => 'franchise', 'status' => 'active']);
        Kyc::create(['user_id' => $this->district1Manager->id, 'status' => 'approved', 'document_type' => 'aadhaar']);
        $this->district1Franchise = Franchise::create([
            'user_id' => $this->district1Manager->id,
            'parent_franchise_id' => $this->stateFranchise->id,
            'company_name' => 'Jaipur Urban Hub',
            'owner_name' => 'Jaipur Lead',
            'phone' => '9829022222',
            'email' => 'jaipur@onestall.com',
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

        // 3. District 2 Franchise (Different district)
        $this->district2Manager = User::factory()->create(['role' => 'franchise', 'status' => 'active']);
        Kyc::create(['user_id' => $this->district2Manager->id, 'status' => 'approved', 'document_type' => 'aadhaar']);
        $this->district2Franchise = Franchise::create([
            'user_id' => $this->district2Manager->id,
            'parent_franchise_id' => $this->stateFranchise->id,
            'company_name' => 'Jodhpur West Hub',
            'owner_name' => 'Jodhpur Lead',
            'phone' => '9829033333',
            'email' => 'jodhpur@onestall.com',
            'address' => 'Sardarpura',
            'city' => 'Jodhpur',
            'district' => 'Jodhpur',
            'state' => 'Rajasthan',
            'status' => 'approved',
            'franchise_level' => 'district',
            'serviceable_pincodes' => ['342001', '342002'],
            'delivery_commission_type' => 'flat',
            'delivery_commission_rate' => 20.00,
            'pickup_commission_type' => 'flat',
            'pickup_commission_rate' => 10.00,
        ]);
    }

    public function test_state_franchise_dashboard_shows_subordinate_districts()
    {
        $response = $this->actingAs($this->stateManager)->get(route('hub.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('State Franchise HQ');
        $response->assertSee('Rajasthan State Apex');
        $response->assertSee('Jaipur Urban Hub');
        $response->assertSee('Jodhpur West Hub');
    }

    public function test_district_franchise_dashboard_shows_district_banner_and_parent()
    {
        $response = $this->actingAs($this->district1Manager)->get(route('hub.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('District Franchise');
        $response->assertSee('Jaipur Urban Hub');
        $response->assertSee('Parent State Hub:');
        $response->assertSee('Rajasthan State Apex');
        // Must not see sibling district in its personal banner
        $response->assertDontSee('Jodhpur West Hub');
    }

    public function test_shipment_data_isolation_between_districts()
    {
        $customer = User::factory()->create(['role' => 'customer']);

        // Shipment 1 assigned to District 1
        $shipment1 = Shipment::create([
            'awb_number' => 'AWB-JPR-1001',
            'user_id' => $customer->id,
            'franchise_id' => $this->district1Franchise->id,
            'state_franchise_id' => $this->stateFranchise->id,
            'receiver_name' => 'Jaipur Buyer',
            'receiver_phone' => '9829000001',
            'pickup_address' => 'Vendor 1',
            'pickup_city' => 'Jaipur',
            'pickup_pincode' => '302001',
            'delivery_address' => 'House 1',
            'delivery_city' => 'Jaipur',
            'delivery_pincode' => '302001',
            'weight_kg' => 1.0,
            'invoice_value' => 500,
            'total_amount' => 500,
            'shipping_charge' => 70,
            'status' => 'Pending',
        ]);

        // Shipment 2 assigned to District 2
        $shipment2 = Shipment::create([
            'awb_number' => 'AWB-JDH-2002',
            'user_id' => $customer->id,
            'franchise_id' => $this->district2Franchise->id,
            'state_franchise_id' => $this->stateFranchise->id,
            'receiver_name' => 'Jodhpur Buyer',
            'receiver_phone' => '9829000002',
            'pickup_address' => 'Vendor 2',
            'pickup_city' => 'Jodhpur',
            'pickup_pincode' => '342001',
            'delivery_address' => 'House 2',
            'delivery_city' => 'Jodhpur',
            'delivery_pincode' => '342001',
            'weight_kg' => 2.0,
            'invoice_value' => 1000,
            'total_amount' => 1000,
            'shipping_charge' => 90,
            'status' => 'Pending',
        ]);

        // District 1 manager views shipment list
        $responseDist1 = $this->actingAs($this->district1Manager)->get(route('hub.shipments'));
        $responseDist1->assertStatus(200);
        $responseDist1->assertSee('AWB-JPR-1001');
        $responseDist1->assertDontSee('AWB-JDH-2002'); // Isolated!

        // District 2 manager views shipment list
        $responseDist2 = $this->actingAs($this->district2Manager)->get(route('hub.shipments'));
        $responseDist2->assertStatus(200);
        $responseDist2->assertSee('AWB-JDH-2002');
        $responseDist2->assertDontSee('AWB-JPR-1001'); // Isolated!

        // State Manager views shipment list (can oversee both)
        $responseState = $this->actingAs($this->stateManager)->get(route('hub.shipments'));
        $responseState->assertStatus(200);
        $responseState->assertSee('AWB-JPR-1001');
        $responseState->assertSee('AWB-JDH-2002');
    }
}
