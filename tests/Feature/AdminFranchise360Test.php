<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Franchise;
use App\Models\Shipment;
use App\Models\FranchiseCommission;
use App\Models\WalletTransaction;
use App\Models\ServiceablePincode;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminFranchise360Test extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $franchiseUser;
    private User $regularUser;
    private Franchise $stateFranchise;
    private Franchise $districtFranchise;
    private Franchise $independentDistrict;
    private Shipment $deliveredCodShipment;
    private Shipment $remittedCodShipment;
    private Shipment $inTransitShipment;
    private Shipment $ndrShipment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->regularUser = User::factory()->create(['role' => 'seller']);

        // Create State Franchise User & Franchise
        $stateUser = User::factory()->create([
            'role' => 'franchise',
            'wallet_balance' => 350.00,
        ]);
        $this->stateFranchise = Franchise::create([
            'user_id' => $stateUser->id,
            'company_name' => 'Maharashtra State Logistics Apex',
            'owner_name' => 'State Boss',
            'phone' => '9800000001',
            'email' => 'state_apex@onestall.com',
            'address' => 'BKC Center',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'status' => 'approved',
            'franchise_level' => 'state',
            'state_royalty_type' => 'percent',
            'state_royalty_rate' => 5.0,
        ]);

        // Create District Franchise User & Franchise under State
        $this->franchiseUser = User::factory()->create([
            'role' => 'franchise',
            'wallet_balance' => 450.00,
        ]);
        $this->districtFranchise = Franchise::create([
            'user_id' => $this->franchiseUser->id,
            'company_name' => 'Pune District Express',
            'owner_name' => 'Pune Owner',
            'phone' => '9800000002',
            'email' => 'pune@onestall.com',
            'address' => 'Shivajinagar',
            'city' => 'Pune',
            'district' => 'Pune',
            'state' => 'Maharashtra',
            'status' => 'approved',
            'franchise_level' => 'district',
            'parent_franchise_id' => $this->stateFranchise->id,
            'serviceable_pincodes' => ['411001', '411002'],
            'delivery_commission_type' => 'flat',
            'delivery_commission_rate' => 40.0,
            'pickup_commission_type' => 'flat',
            'pickup_commission_rate' => 20.0,
        ]);

        // Create Independent District Franchise
        $indepUser = User::factory()->create(['role' => 'franchise']);
        $this->independentDistrict = Franchise::create([
            'user_id' => $indepUser->id,
            'company_name' => 'Nagpur Direct Hub',
            'owner_name' => 'Nagpur Owner',
            'phone' => '9800000003',
            'email' => 'nagpur@onestall.com',
            'address' => 'Sitabuldi',
            'city' => 'Nagpur',
            'district' => 'Nagpur',
            'state' => 'Maharashtra',
            'status' => 'approved',
            'franchise_level' => 'district',
            'parent_franchise_id' => null,
            'serviceable_pincodes' => ['440001'],
        ]);

        // Map Pincodes
        ServiceablePincode::create([
            'franchise_id' => $this->districtFranchise->id,
            'pincode' => '411001',
            'city' => 'Pune',
            'state' => 'Maharashtra',
            'is_active' => true,
        ]);

        // Seed Shipments
        $sender = User::factory()->create(['role' => 'seller']);

        // 1. Delivered COD (Unremitted - Pending In Hand: 1500)
        $this->deliveredCodShipment = Shipment::create([
            'awb_number' => 'AWB-360-COD-01',
            'user_id' => $sender->id,
            'franchise_id' => $this->districtFranchise->id,
            'state_franchise_id' => $this->stateFranchise->id,
            'status' => 'Delivered',
            'payment_type' => 'COD',
            'is_cod' => 1,
            'cod_amount' => 1500.00,
            'invoice_value' => 1500.00,
            'total_amount' => 100.00,
            'shipping_charge' => 100.00,
            'weight_kg' => 1.5,
            'cod_remitted' => 0,
            'receiver_name' => 'Rahul Sharma',
            'receiver_phone' => '9999900001',
            'delivery_address' => 'FC Road Lane 4',
            'delivery_city' => 'Pune',
            'delivery_state' => 'Maharashtra',
            'delivery_pincode' => '411001',
            'pickup_address' => 'Merchant Hub A',
            'pickup_city' => 'Mumbai',
            'pickup_pincode' => '400001',
        ]);

        // 2. Delivered COD (Remitted to Admin: 2500)
        $this->remittedCodShipment = Shipment::create([
            'awb_number' => 'AWB-360-COD-02',
            'user_id' => $sender->id,
            'franchise_id' => $this->districtFranchise->id,
            'state_franchise_id' => $this->stateFranchise->id,
            'status' => 'Delivered',
            'payment_type' => 'COD',
            'is_cod' => 1,
            'cod_amount' => 2500.00,
            'invoice_value' => 2500.00,
            'total_amount' => 120.00,
            'shipping_charge' => 120.00,
            'weight_kg' => 2.0,
            'cod_remitted' => 1,
            'receiver_name' => 'Anita Desai',
            'receiver_phone' => '9999900002',
            'delivery_address' => 'JM Road Apt 12',
            'delivery_city' => 'Pune',
            'delivery_state' => 'Maharashtra',
            'delivery_pincode' => '411001',
            'pickup_address' => 'Merchant Hub A',
            'pickup_city' => 'Mumbai',
            'pickup_pincode' => '400001',
        ]);

        // 3. In Transit Shipment
        $this->inTransitShipment = Shipment::create([
            'awb_number' => 'AWB-360-TRANS-01',
            'user_id' => $sender->id,
            'franchise_id' => $this->districtFranchise->id,
            'state_franchise_id' => $this->stateFranchise->id,
            'status' => 'In Transit',
            'payment_type' => 'Prepaid',
            'is_cod' => 0,
            'invoice_value' => 500.00,
            'total_amount' => 80.00,
            'shipping_charge' => 80.00,
            'weight_kg' => 1.0,
            'receiver_name' => 'Suresh Patil',
            'receiver_phone' => '9999900003',
            'delivery_address' => 'Kothrud Sector 2',
            'delivery_city' => 'Pune',
            'delivery_state' => 'Maharashtra',
            'delivery_pincode' => '411001',
            'pickup_address' => 'Merchant Hub B',
            'pickup_city' => 'Mumbai',
            'pickup_pincode' => '400001',
        ]);

        // 4. NDR Shipment
        $this->ndrShipment = Shipment::create([
            'awb_number' => 'AWB-360-NDR-01',
            'user_id' => $sender->id,
            'franchise_id' => $this->districtFranchise->id,
            'state_franchise_id' => $this->stateFranchise->id,
            'status' => 'NDR',
            'payment_type' => 'Prepaid',
            'is_cod' => 0,
            'invoice_value' => 600.00,
            'total_amount' => 90.00,
            'shipping_charge' => 90.00,
            'weight_kg' => 1.2,
            'receiver_name' => 'Vikas Roy',
            'receiver_phone' => '9999900004',
            'delivery_address' => 'Hadapsar Phase 1',
            'delivery_city' => 'Pune',
            'delivery_state' => 'Maharashtra',
            'delivery_pincode' => '411001',
            'pickup_address' => 'Merchant Hub B',
            'pickup_city' => 'Mumbai',
            'pickup_pincode' => '400001',
        ]);

        // Commissions
        FranchiseCommission::create([
            'franchise_id' => $this->districtFranchise->id,
            'shipment_id' => $this->deliveredCodShipment->id,
            'commission_type' => 'delivery',
            'amount' => 40.00,
            'status' => 'credited',
        ]);

        FranchiseCommission::create([
            'franchise_id' => $this->stateFranchise->id,
            'shipment_id' => $this->deliveredCodShipment->id,
            'commission_type' => 'royalty',
            'amount' => 5.00,
            'status' => 'credited',
        ]);

        // Wallet Transaction
        WalletTransaction::create([
            'user_id' => $this->franchiseUser->id,
            'type' => 'credit',
            'amount' => 40.00,
            'balance_after' => 450.00,
            'description' => 'Delivery commission for AWB-360-COD-01',
        ]);
    }

    public function test_unauthenticated_user_cannot_access_360_overview()
    {
        $response = $this->get(route('admin.franchises.overview', $this->districtFranchise->id));
        $response->assertRedirect(route('login'));
    }

    public function test_unauthorized_user_cannot_access_360_overview()
    {
        $response = $this->actingAs($this->regularUser)->get(route('admin.franchises.overview', $this->districtFranchise->id));
        $response->assertRedirect(route('seller.dashboard'));
    }

    public function test_admin_can_view_state_franchise_360_overview()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.franchises.overview', $this->stateFranchise->id));
        $response->assertStatus(200);

        // Check Franchise Info
        $response->assertSee('Maharashtra State Logistics Apex');
        $response->assertSee('STATE FRANCHISE');
        $response->assertSee('Pune District Express'); // subordinate district

        // Check KPI metrics rendered
        $response->assertSee('2'); // Delivered count
        $response->assertSee('1'); // Pending count
        $response->assertSee('₹1,500.00'); // Pending COD In Hand
        $response->assertSee('₹5.00'); // State royalty
    }

    public function test_admin_can_view_district_franchise_360_overview()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.franchises.overview', $this->districtFranchise->id));
        $response->assertStatus(200);

        // Check Franchise Info
        $response->assertSee('Pune District Express');
        $response->assertSee('DISTRICT FRANCHISE');
        $response->assertSee('Maharashtra State Logistics Apex'); // parent state link
        $response->assertSee('411001'); // serviceable pincode

        // Check KPI metrics
        $response->assertSee('₹1,500.00'); // COD in hand
        $response->assertSee('₹40.00'); // delivery commission
        $response->assertSee('₹450.00'); // wallet balance
    }

    public function test_admin_can_filter_orders_tab()
    {
        // View orders tab with status filter = Delivered
        $response = $this->actingAs($this->admin)->get(route('admin.franchises.overview', [
            'id' => $this->districtFranchise->id,
            'tab' => 'orders',
            'status' => 'Delivered',
        ]));

        $response->assertStatus(200);
        $response->assertSee('AWB-360-COD-01');
        $response->assertSee('AWB-360-COD-02');
        $response->assertDontSee('AWB-360-TRANS-01');
    }

    public function test_admin_can_view_cod_reconciliation_tab()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.franchises.overview', [
            'id' => $this->districtFranchise->id,
            'tab' => 'cod',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Total COD Collected (Delivered)');
        $response->assertSee('₹4,000.00'); // Total 1500 + 2500
        $response->assertSee('₹2,500.00'); // Remitted
        $response->assertSee('₹1,500.00'); // Pending In-Hand
        $response->assertSee('AWB-360-COD-01');
        $response->assertSee('In Hand (Pending)');
        $response->assertSee('Remitted');
    }

    public function test_admin_can_view_commission_ledger_tab()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.franchises.overview', [
            'id' => $this->districtFranchise->id,
            'tab' => 'commissions',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Commission Ledger Records');
        $response->assertSee('delivery');
        $response->assertSee('₹40.00');
    }

    public function test_admin_can_view_wallet_transactions_tab()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.franchises.overview', [
            'id' => $this->districtFranchise->id,
            'tab' => 'wallet',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Franchise Commission Wallet');
        $response->assertSee('Delivery commission for AWB-360-COD-01');
        $response->assertSee('+₹40.00');
        $response->assertSee('₹450.00');
    }

    public function test_admin_hubs_index_displays_metrics_and_360_links()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.hubs.index'));
        $response->assertStatus(200);

        // Check 360 Overview links exist for state, subordinate district, and independent district
        $response->assertSee(route('admin.franchises.overview', $this->stateFranchise->id));
        $response->assertSee(route('admin.franchises.overview', $this->districtFranchise->id));
        $response->assertSee(route('admin.franchises.overview', $this->independentDistrict->id));

        // Check Independent section is rendered
        $response->assertSee('Direct / Independent District Franchises');
        $response->assertSee('Nagpur Direct Hub');
    }

    public function test_overview_returns_404_for_nonexistent_franchise()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.franchises.overview', 99999));
        $response->assertStatus(404);
    }
}
