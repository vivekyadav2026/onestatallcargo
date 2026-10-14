<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Franchise;
use App\Models\ServiceablePincode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminFranchiseHierarchyTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->regularUser = User::factory()->create(['role' => 'seller']);
    }

    public function test_unauthorized_user_cannot_access_franchise_admin()
    {
        $response = $this->actingAs($this->regularUser)->get(route('admin.hubs.index'));
        $response->assertRedirect(route('seller.dashboard'));
    }

    public function test_admin_can_view_franchise_index()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.hubs.index'));
        $response->assertStatus(200);
        $response->assertSee('Franchise Network & Hubs');
    }

    public function test_admin_can_create_state_franchise()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.franchises.store_state'), [
            'company_name' => 'Maharashtra Apex Logistics',
            'owner_name' => 'State Manager MH',
            'phone' => '9820012345',
            'email' => 'mh_state@onestall.com',
            'password' => 'secret1234',
            'state' => 'Maharashtra',
            'city' => 'Mumbai',
            'address' => 'Bandra Kurla Complex',
            'state_royalty_type' => 'percent',
            'state_royalty_rate' => 5.50,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('franchises', [
            'company_name' => 'Maharashtra Apex Logistics',
            'franchise_level' => 'state',
            'state' => 'Maharashtra',
            'state_royalty_rate' => 5.50,
        ]);
    }

    public function test_admin_can_create_district_franchise_under_state_franchise()
    {
        $state = Franchise::create([
            'company_name' => 'Gujarat State Cargo',
            'owner_name' => 'GJ Lead',
            'phone' => '9825012345',
            'email' => 'gj_state@onestall.com',
            'address' => 'SG Highway',
            'city' => 'Ahmedabad',
            'state' => 'Gujarat',
            'status' => 'approved',
            'franchise_level' => 'state',
            'state_royalty_type' => 'percent',
            'state_royalty_rate' => 4.00,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.franchises.store_district'), [
            'parent_franchise_id' => $state->id,
            'district' => 'Ahmedabad Urban',
            'company_name' => 'Ahmedabad City Delivery Hub',
            'owner_name' => 'District Lead',
            'phone' => '9825099999',
            'email' => 'ahmedabad_hub@onestall.com',
            'password' => 'secret1234',
            'city' => 'Ahmedabad',
            'address' => 'Navrangpura',
            'serviceable_pincodes' => '380001, 380006, 380009',
            'delivery_commission_type' => 'flat',
            'delivery_commission_rate' => 30.00,
            'pickup_commission_type' => 'flat',
            'pickup_commission_rate' => 15.00,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('franchises', [
            'company_name' => 'Ahmedabad City Delivery Hub',
            'franchise_level' => 'district',
            'parent_franchise_id' => $state->id,
            'district' => 'Ahmedabad Urban',
            'state' => 'Gujarat',
            'delivery_commission_rate' => 30.00,
        ]);

        // Pincodes must be mapped to the new franchise
        $this->assertDatabaseHas('serviceable_pincodes', [
            'pincode' => '380001',
            'is_active' => true,
        ]);
    }

    public function test_duplicate_active_territory_assignment_is_rejected()
    {
        $state = Franchise::create([
            'company_name' => 'Delhi State Cargo',
            'owner_name' => 'DL Lead',
            'phone' => '9811012345',
            'email' => 'dl_state@onestall.com',
            'address' => 'Connaught Place',
            'city' => 'New Delhi',
            'state' => 'Delhi',
            'status' => 'approved',
            'franchise_level' => 'state',
        ]);

        // Existing district franchise already serving 110001
        $dist1 = Franchise::create([
            'company_name' => 'CP District Hub',
            'owner_name' => 'Lead One',
            'phone' => '9811055555',
            'email' => 'dist1@onestall.com',
            'address' => 'CP Block A',
            'city' => 'New Delhi',
            'district' => 'Central Delhi',
            'state' => 'Delhi',
            'status' => 'approved',
            'franchise_level' => 'district',
            'parent_franchise_id' => $state->id,
            'serviceable_pincodes' => ['110001'],
        ]);

        ServiceablePincode::create([
            'pincode' => '110001',
            'city' => 'New Delhi',
            'state' => 'Delhi',
            'franchise_id' => $dist1->id,
            'is_active' => true,
        ]);

        // Second district hub tries to claim 110001
        $response = $this->actingAs($this->admin)->post(route('admin.franchises.store_district'), [
            'parent_franchise_id' => $state->id,
            'district' => 'New Delhi West',
            'company_name' => 'Conflict Hub',
            'owner_name' => 'Lead Two',
            'phone' => '9811077777',
            'email' => 'conflict@onestall.com',
            'password' => 'secret1234',
            'city' => 'New Delhi',
            'address' => 'Pusa Road',
            'serviceable_pincodes' => '110001, 110005', // 110001 overlaps!
            'delivery_commission_type' => 'flat',
            'delivery_commission_rate' => 20.00,
            'pickup_commission_type' => 'flat',
            'pickup_commission_rate' => 10.00,
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('franchises', [
            'company_name' => 'Conflict Hub',
        ]);
    }

    public function test_admin_can_toggle_franchise_status()
    {
        $franchise = Franchise::create([
            'company_name' => 'Toggle Test Hub',
            'owner_name' => 'Toggle Owner',
            'phone' => '9800011111',
            'email' => 'toggle@onestall.com',
            'address' => 'Main Rd',
            'city' => 'Pune',
            'district' => 'Pune',
            'state' => 'Maharashtra',
            'status' => 'approved',
            'franchise_level' => 'district',
            'serviceable_pincodes' => ['411001'],
        ]);

        ServiceablePincode::create([
            'pincode' => '411001',
            'franchise_id' => $franchise->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.franchises.toggle', $franchise->id));
        $response->assertRedirect();

        $this->assertEquals('inactive', $franchise->fresh()->status);
        $this->assertFalse((bool) ServiceablePincode::where('pincode', '411001')->value('is_active'));
    }

    public function test_admin_can_view_edit_franchise_page()
    {
        $franchise = Franchise::create([
            'company_name' => 'View Edit Test Hub',
            'owner_name' => 'Test Manager',
            'phone' => '9800022222',
            'email' => 'editview@onestall.com',
            'address' => 'Some Address',
            'city' => 'Jaipur',
            'district' => 'Jaipur',
            'state' => 'Rajasthan',
            'status' => 'approved',
            'franchise_level' => 'state',
            'state_royalty_type' => 'percent',
            'state_royalty_rate' => 5.0,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.franchises.edit', $franchise->id));
        $response->assertStatus(200);
        $response->assertSee('Edit Franchise');
        $response->assertSee('Reset Password (Optional)');
    }

    public function test_admin_can_edit_franchise_and_reset_password()
    {
        $user = User::create([
            'name' => 'Old Manager',
            'email' => 'manager_edit@onestall.com',
            'phone' => '9800033333',
            'password' => Hash::make('old_password_123'),
            'role' => 'franchise',
            'status' => 'active',
        ]);

        $state = Franchise::create([
            'user_id' => $user->id,
            'company_name' => 'Old State Cargo',
            'owner_name' => 'Old Manager',
            'phone' => '9800033333',
            'email' => 'manager_edit@onestall.com',
            'address' => 'Old Address',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'status' => 'approved',
            'franchise_level' => 'state',
            'state_royalty_type' => 'percent',
            'state_royalty_rate' => 5.0,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.franchises.update', $state->id), [
            'company_name' => 'Updated State Cargo',
            'owner_name' => 'New Manager Name',
            'phone' => '9800044444',
            'email' => 'new_manager@onestall.com',
            'city' => 'Jaipur Central',
            'state' => 'Rajasthan',
            'address' => 'New Hub Address 123',
            'status' => 'approved',
            'state_royalty_type' => 'percent',
            'state_royalty_rate' => 7.5,
            'password' => 'new_secret_password_789',
        ]);

        $response->assertRedirect(route('admin.hubs.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('franchises', [
            'id' => $state->id,
            'company_name' => 'Updated State Cargo',
            'owner_name' => 'New Manager Name',
            'email' => 'new_manager@onestall.com',
        ]);

        $freshUser = $user->fresh();
        $this->assertEquals('New Manager Name', $freshUser->name);
        $this->assertEquals('new_manager@onestall.com', $freshUser->email);
        $this->assertTrue(Hash::check('new_secret_password_789', $freshUser->password));
    }

    public function test_admin_can_edit_franchise_without_changing_password()
    {
        $user = User::create([
            'name' => 'Manager Keep PW',
            'email' => 'keeppw@onestall.com',
            'phone' => '9800066666',
            'password' => Hash::make('keep_this_original_password'),
            'role' => 'franchise',
            'status' => 'active',
        ]);

        $state = Franchise::create([
            'user_id' => $user->id,
            'company_name' => 'Keep PW State Cargo',
            'owner_name' => 'Manager Keep PW',
            'phone' => '9800066666',
            'email' => 'keeppw@onestall.com',
            'address' => 'Original Address',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'status' => 'approved',
            'franchise_level' => 'state',
            'state_royalty_type' => 'percent',
            'state_royalty_rate' => 5.0,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.franchises.update', $state->id), [
            'company_name' => 'Updated Company Name Only',
            'owner_name' => 'Manager Keep PW',
            'phone' => '9800066666',
            'email' => 'keeppw@onestall.com',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'address' => 'Original Address',
            'status' => 'approved',
            'state_royalty_type' => 'percent',
            'state_royalty_rate' => 5.0,
            'password' => '', // blank password should not alter existing password
        ]);

        $response->assertRedirect(route('admin.hubs.index'));
        $this->assertTrue(Hash::check('keep_this_original_password', $user->fresh()->password));
    }
}
