<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Franchise;
use App\Models\Shipment;
use App\Models\FranchiseCommission;
use App\Services\FranchiseCommissionService;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FranchiseCommissionTest extends TestCase
{
    use RefreshDatabase;

    private User $stateUser;
    private Franchise $stateFranchise;
    private User $districtUser;
    private Franchise $districtFranchise;
    private User $seller;
    private FranchiseCommissionService $commissionService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->commissionService = app(FranchiseCommissionService::class);

        // State Franchise with 5% royalty
        $this->stateUser = User::factory()->create(['role' => 'franchise', 'wallet_balance' => 0.00]);
        $this->stateFranchise = Franchise::create([
            'user_id' => $this->stateUser->id,
            'company_name' => 'Rajasthan State Hub',
            'owner_name' => 'State Owner',
            'phone' => '9829000010',
            'email' => 'state_owner@onestall.com',
            'address' => 'HQ Jaipur',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'status' => 'approved',
            'franchise_level' => 'state',
            'state_royalty_type' => 'percent',
            'state_royalty_rate' => 5.00, // 5% royalty
        ]);

        // District Franchise under State Franchise with ₹30 flat delivery and ₹15 pickup
        $this->districtUser = User::factory()->create(['role' => 'franchise', 'wallet_balance' => 0.00]);
        $this->districtFranchise = Franchise::create([
            'user_id' => $this->districtUser->id,
            'parent_franchise_id' => $this->stateFranchise->id,
            'company_name' => 'Jaipur Central District Hub',
            'owner_name' => 'District Owner',
            'phone' => '9829000020',
            'email' => 'district_owner@onestall.com',
            'address' => 'MI Road',
            'city' => 'Jaipur',
            'district' => 'Jaipur Central',
            'state' => 'Rajasthan',
            'status' => 'approved',
            'franchise_level' => 'district',
            'serviceable_pincodes' => ['302001'],
            'delivery_commission_type' => 'flat',
            'delivery_commission_rate' => 30.00, // ₹30 flat
            'pickup_commission_type' => 'flat',
            'pickup_commission_rate' => 15.00, // ₹15 flat
        ]);

        $this->seller = User::factory()->create(['role' => 'seller']);
    }

    public function test_delivery_commissions_and_state_royalty_calculated_correctly()
    {
        $shipment = Shipment::create([
            'awb_number' => 'AWB-COMM-001',
            'user_id' => $this->seller->id,
            'franchise_id' => $this->districtFranchise->id,
            'state_franchise_id' => $this->stateFranchise->id,
            'receiver_name' => 'Alice Customer',
            'receiver_phone' => '9829000030',
            'pickup_address' => 'Vendor Store',
            'pickup_city' => 'Jaipur',
            'pickup_pincode' => '302001',
            'delivery_address' => 'Customer House',
            'delivery_city' => 'Jaipur',
            'delivery_pincode' => '302001',
            'weight_kg' => 2.0,
            'shipping_charge' => 200.00, // Freight ₹200
            'invoice_value' => 1000.00,
            'total_amount' => 1000.00,
            'status' => 'Delivered',
        ]);

        $result = $this->commissionService->processDeliveryCommissions($shipment);

        $this->assertTrue($result['success']);

        // District Hub earns flat ₹30.00
        $this->assertEquals(30.00, (float) $this->districtUser->fresh()->wallet_balance);
        $this->assertDatabaseHas('franchise_commissions', [
            'franchise_id' => $this->districtFranchise->id,
            'shipment_id' => $shipment->id,
            'commission_type' => 'district_delivery',
            'amount' => 30.00,
            'status' => 'credited',
        ]);

        // State Hub earns 5% of ₹200 shipping charge = ₹10.00
        $this->assertEquals(10.00, (float) $this->stateUser->fresh()->wallet_balance);
        $this->assertDatabaseHas('franchise_commissions', [
            'franchise_id' => $this->stateFranchise->id,
            'shipment_id' => $shipment->id,
            'commission_type' => 'state_royalty',
            'amount' => 10.00,
            'status' => 'credited',
        ]);
    }

    public function test_strict_idempotency_duplicate_delivery_events_do_not_double_credit()
    {
        $shipment = Shipment::create([
            'awb_number' => 'AWB-IDEMP-002',
            'user_id' => $this->seller->id,
            'franchise_id' => $this->districtFranchise->id,
            'state_franchise_id' => $this->stateFranchise->id,
            'receiver_name' => 'Bob Customer',
            'receiver_phone' => '9829000031',
            'pickup_address' => 'Vendor Store',
            'pickup_city' => 'Jaipur',
            'pickup_pincode' => '302001',
            'delivery_address' => 'Customer House',
            'delivery_city' => 'Jaipur',
            'delivery_pincode' => '302001',
            'weight_kg' => 1.0,
            'shipping_charge' => 100.00,
            'invoice_value' => 500.00,
            'total_amount' => 500.00,
            'status' => 'Delivered',
        ]);

        // 1st Execution
        $this->commissionService->processDeliveryCommissions($shipment);
        $districtBalanceAfterFirst = (float) $this->districtUser->fresh()->wallet_balance;
        $stateBalanceAfterFirst = (float) $this->stateUser->fresh()->wallet_balance;

        $this->assertEquals(30.00, $districtBalanceAfterFirst);
        $this->assertEquals(5.00, $stateBalanceAfterFirst);

        // 2nd Execution (Duplicate webhook / rider re-submit)
        $this->commissionService->processDeliveryCommissions($shipment);

        // 3rd Execution
        $this->commissionService->processDeliveryCommissions($shipment);

        // Balances MUST NOT change!
        $this->assertEquals($districtBalanceAfterFirst, (float) $this->districtUser->fresh()->wallet_balance);
        $this->assertEquals($stateBalanceAfterFirst, (float) $this->stateUser->fresh()->wallet_balance);

        // Commission rows MUST be exactly 1 each
        $this->assertEquals(1, FranchiseCommission::where('franchise_id', $this->districtFranchise->id)->where('shipment_id', $shipment->id)->count());
        $this->assertEquals(1, FranchiseCommission::where('franchise_id', $this->stateFranchise->id)->where('shipment_id', $shipment->id)->count());
    }

    public function test_pickup_commission_calculated_and_idempotent()
    {
        $shipment = Shipment::create([
            'awb_number' => 'AWB-PICKUP-003',
            'user_id' => $this->seller->id,
            'pickup_franchise_id' => $this->districtFranchise->id,
            'franchise_id' => $this->districtFranchise->id,
            'receiver_name' => 'Charlie Customer',
            'receiver_phone' => '9829000032',
            'pickup_address' => 'Store 1',
            'pickup_city' => 'Jaipur',
            'pickup_pincode' => '302001',
            'delivery_address' => 'Delhi House',
            'delivery_city' => 'Delhi',
            'delivery_pincode' => '110001',
            'weight_kg' => 1.0,
            'shipping_charge' => 100.00,
            'invoice_value' => 500.00,
            'total_amount' => 500.00,
            'status' => 'In Transit',
        ]);

        $this->commissionService->processPickupCommissions($shipment);

        // Earned pickup commission of ₹15.00
        $this->assertEquals(15.00, (float) $this->districtUser->fresh()->wallet_balance);

        // Duplicate call
        $this->commissionService->processPickupCommissions($shipment);
        $this->assertEquals(15.00, (float) $this->districtUser->fresh()->wallet_balance); // Remains 15!
    }

    public function test_cod_collection_is_kept_separate_from_commissions()
    {
        $shipment = Shipment::create([
            'awb_number' => 'AWB-COD-004',
            'user_id' => $this->seller->id,
            'franchise_id' => $this->districtFranchise->id,
            'state_franchise_id' => $this->stateFranchise->id,
            'receiver_name' => 'COD Buyer',
            'receiver_phone' => '9829000033',
            'pickup_address' => 'Store 1',
            'pickup_city' => 'Jaipur',
            'pickup_pincode' => '302001',
            'delivery_address' => 'Jaipur House',
            'delivery_city' => 'Jaipur',
            'delivery_pincode' => '302001',
            'weight_kg' => 1.0,
            'shipping_charge' => 100.00,
            'is_cod' => true,
            'payment_type' => 'COD',
            'cod_amount' => 2500.00, // ₹2,500 COD to collect from customer
            'invoice_value' => 2500.00,
            'total_amount' => 2500.00,
            'cod_remitted' => false,
            'status' => 'Delivered',
        ]);

        $this->commissionService->processDeliveryCommissions($shipment);

        // Franchise wallet balance must ONLY reflect the earned commission (₹30.00), NOT the collected COD (₹2,500.00)!
        $this->assertEquals(30.00, (float) $this->districtUser->fresh()->wallet_balance);

        // COD remains recorded separately on shipment pending remittance
        $this->assertEquals(2500.00, (float) $shipment->fresh()->cod_amount);
        $this->assertFalse((bool) $shipment->fresh()->cod_remitted);
    }
}
