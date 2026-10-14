<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Franchise;
use App\Models\Shipment;
use App\Models\FranchiseCommission;
use InvalidArgumentException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FranchiseHierarchyModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_state_and_district_franchise_with_hierarchy()
    {
        $state = Franchise::create([
            'company_name' => 'Rajasthan State Logistics',
            'owner_name' => 'State Admin',
            'phone' => '9876543210',
            'email' => 'state_hq@onestall.com',
            'address' => 'MI Road',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'status' => 'approved',
            'franchise_level' => 'state',
            'state_royalty_type' => 'percent',
            'state_royalty_rate' => 5.00,
        ]);

        $this->assertTrue($state->isState());
        $this->assertFalse($state->isDistrict());
        $this->assertNull($state->parent_franchise_id);

        $district = Franchise::create([
            'company_name' => 'Jaipur District Logistics',
            'owner_name' => 'District Lead',
            'phone' => '9876543211',
            'email' => 'jaipur_dist@onestall.com',
            'address' => 'Sitapura Ind Area',
            'city' => 'Jaipur',
            'district' => 'Jaipur',
            'state' => 'Rajasthan',
            'status' => 'approved',
            'franchise_level' => 'district',
            'parent_franchise_id' => $state->id,
            'delivery_commission_type' => 'flat',
            'delivery_commission_rate' => 20.00,
            'pickup_commission_type' => 'flat',
            'pickup_commission_rate' => 10.00,
        ]);

        $this->assertTrue($district->isDistrict());
        $this->assertFalse($district->isState());
        $this->assertEquals($state->id, $district->parentFranchise->id);
        $this->assertTrue($state->districtFranchises->contains('id', $district->id));

        // Test commission relationship with real shipment
        $user = \App\Models\User::factory()->create();
        $shipment = Shipment::create([
            'awb_number' => 'AWB-TEST-001',
            'user_id' => $user->id,
            'franchise_id' => $district->id,
            'receiver_name' => 'John Doe',
            'receiver_phone' => '9876543200',
            'pickup_address' => 'Pickup Addr',
            'pickup_city' => 'Jaipur',
            'pickup_pincode' => '302022',
            'delivery_address' => 'Delivery Addr',
            'delivery_city' => 'Jaipur',
            'delivery_pincode' => '302022',
            'weight_kg' => 1.5,
            'invoice_value' => 500.00,
            'total_amount' => 500.00,
            'shipping_charge' => 100.00,
            'status' => 'Delivered',
        ]);

        $commission = FranchiseCommission::create([
            'franchise_id' => $district->id,
            'shipment_id' => $shipment->id,
            'commission_type' => 'district_delivery',
            'rate_type' => 'flat',
            'rate_applied' => 20.00,
            'amount' => 20.00,
            'status' => 'pending',
        ]);
        $this->assertEquals(1, $district->commissions->count());
        $this->assertEquals(20.00, $district->commissions->first()->amount);

        // Test cycle / invalid parent assignment prevention
        $this->expectException(InvalidArgumentException::class);
        Franchise::create([
            'company_name' => 'Sub Child District',
            'owner_name' => 'Sub Lead',
            'phone' => '9876543212',
            'email' => 'sub_dist@onestall.com',
            'address' => 'Sitapura',
            'city' => 'Jaipur',
            'district' => 'Jaipur South',
            'state' => 'Rajasthan',
            'status' => 'approved',
            'franchise_level' => 'district',
            'parent_franchise_id' => $district->id, // Cannot be child of a district franchise!
        ]);
    }

    public function test_self_referencing_parent_throws_exception()
    {
        $franchise = Franchise::create([
            'company_name' => 'Self Ref Test',
            'owner_name' => 'Self Owner',
            'phone' => '9876543299',
            'email' => 'self@onestall.com',
            'address' => 'Station Road',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'status' => 'approved',
            'franchise_level' => 'district',
        ]);

        $this->expectException(InvalidArgumentException::class);
        $franchise->parent_franchise_id = $franchise->id;
        $franchise->save();
    }

    public function test_state_franchise_always_nullifies_parent()
    {
        $otherFranchise = Franchise::create([
            'company_name' => 'Other State',
            'owner_name' => 'Other',
            'phone' => '9876543298',
            'email' => 'other@onestall.com',
            'address' => 'Road',
            'city' => 'Kota',
            'state' => 'Rajasthan',
            'status' => 'approved',
            'franchise_level' => 'state',
        ]);

        $stateFranchise = Franchise::create([
            'company_name' => 'Primary State',
            'owner_name' => 'Primary',
            'phone' => '9876543297',
            'email' => 'primary@onestall.com',
            'address' => 'Road',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'status' => 'approved',
            'franchise_level' => 'state',
            'parent_franchise_id' => $otherFranchise->id, // should be forced null by boot
        ]);

        $this->assertNull($stateFranchise->fresh()->parent_franchise_id);
    }
}
