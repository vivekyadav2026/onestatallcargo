<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Kyc;
use App\Models\Franchise;
use App\Models\WalletTransaction;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HubWalletRechargeTest extends TestCase
{
    use RefreshDatabase;

    private User $hubUser;
    private Franchise $franchise;
    private User $adminUser;
    private User $sellerUser;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Hub / Franchise User
        $this->hubUser = User::factory()->create([
            'role' => 'franchise',
            'status' => 'active',
            'wallet_balance' => 0.00,
        ]);

        Kyc::create([
            'user_id' => $this->hubUser->id,
            'status' => 'approved',
            'document_type' => 'aadhaar',
            'document_number' => '123456789012',
            'pan_number' => 'ABCDE1234F'
        ]);

        $this->franchise = Franchise::create([
            'user_id' => $this->hubUser->id,
            'company_name' => 'Metro Express Hub',
            'owner_name' => 'Metro Partner',
            'phone' => '9876543210',
            'email' => 'metrohub@onestall.com',
            'address' => 'Plot 45, MG Road',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'status' => 'approved',
            'franchise_level' => 'district',
            'serviceable_pincodes' => json_encode(['302001']),
        ]);

        // 2. Admin User
        $this->adminUser = User::factory()->create([
            'name' => 'Super Admin Officer',
            'role' => 'admin',
            'status' => 'active',
            'wallet_balance' => 0.00,
        ]);

        // 3. Seller User
        $this->sellerUser = User::factory()->create([
            'role' => 'seller',
            'status' => 'active',
            'wallet_balance' => 0.00,
        ]);

        Kyc::create([
            'user_id' => $this->sellerUser->id,
            'status' => 'approved',
            'document_type' => 'aadhaar',
            'document_number' => '999988887777',
            'pan_number' => 'XYZPK9876Q'
        ]);
    }

    /**
     * Test 1: Franchise with approved KYC can initiate wallet recharge.
     */
    public function test_franchise_with_approved_kyc_can_initiate_wallet_recharge(): void
    {
        $response = $this->actingAs($this->hubUser)->postJson(route('hub.wallet.recharge'), [
            'amount' => 1000
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'mock_mode' => true,
        ]);

        $orderId = $response->json('order_id');
        $this->assertNotEmpty($orderId);

        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $this->hubUser->id,
            'reference_id' => $orderId,
            'type' => 'credit',
            'amount' => 1000.00,
            'status' => 'pending',
        ]);
    }

    /**
     * Test 2: Franchise without approved KYC is blocked from recharge initiation (HTTP 403).
     */
    public function test_franchise_without_approved_kyc_is_blocked_from_initiating_recharge(): void
    {
        $unverifiedHub = User::factory()->create([
            'role' => 'franchise',
            'status' => 'active',
            'wallet_balance' => 0.00,
        ]);

        Franchise::create([
            'user_id' => $unverifiedHub->id,
            'company_name' => 'Unverified Hub',
            'owner_name' => 'Unverified Partner',
            'phone' => '9111222333',
            'email' => 'unverified@onestall.com',
            'address' => 'Some address',
            'city' => 'Delhi',
            'state' => 'Delhi',
            'status' => 'approved',
            'franchise_level' => 'district',
        ]);

        // No KYC record created
        $response = $this->actingAs($unverifiedHub)->postJson(route('hub.wallet.recharge'), [
            'amount' => 1000
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'Please complete your KYC verification to recharge your wallet.',
        ]);
    }

    /**
     * Test 3: Franchise recharge requires minimum amount of 500.
     */
    public function test_franchise_recharge_requires_minimum_amount_500(): void
    {
        $response = $this->actingAs($this->hubUser)->postJson(route('hub.wallet.recharge'), [
            'amount' => 200
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['amount']);
    }

    /**
     * Test 4: Franchise can verify recharge in test mode and balance is credited.
     */
    public function test_franchise_can_verify_recharge_in_test_mode_and_balance_is_credited(): void
    {
        // 1. Initiate
        $initResponse = $this->actingAs($this->hubUser)->postJson(route('hub.wallet.recharge'), [
            'amount' => 1500
        ]);
        $orderId = $initResponse->json('order_id');

        // 2. Verify
        $verifyResponse = $this->actingAs($this->hubUser)->postJson(route('hub.wallet.verify'), [
            'order_id' => $orderId,
            'mock_mode' => true,
        ]);

        $verifyResponse->assertStatus(200);
        $verifyResponse->assertJson([
            'success' => true,
            'new_balance' => 1500.00,
        ]);

        $this->hubUser->refresh();
        $this->assertEquals(1500.00, (float)$this->hubUser->wallet_balance);

        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $this->hubUser->id,
            'reference_id' => $orderId,
            'type' => 'credit',
            'amount' => 1500.00,
            'status' => 'success',
            'balance_after' => 1500.00,
        ]);
    }

    /**
     * Test 5: Idempotency prevents double crediting on duplicate callback/verify requests.
     */
    public function test_idempotency_prevents_double_crediting_on_duplicate_callbacks(): void
    {
        // 1. Initiate
        $initResponse = $this->actingAs($this->hubUser)->postJson(route('hub.wallet.recharge'), [
            'amount' => 2000
        ]);
        $orderId = $initResponse->json('order_id');

        // 2. First verify
        $verifyResponse1 = $this->actingAs($this->hubUser)->postJson(route('hub.wallet.verify'), [
            'order_id' => $orderId,
            'mock_mode' => true,
        ]);
        $verifyResponse1->assertStatus(200);
        $verifyResponse1->assertJson(['success' => true, 'new_balance' => 2000.00]);

        $this->hubUser->refresh();
        $this->assertEquals(2000.00, (float)$this->hubUser->wallet_balance);

        // 3. Second verify (duplicate callback)
        $verifyResponse2 = $this->actingAs($this->hubUser)->postJson(route('hub.wallet.verify'), [
            'order_id' => $orderId,
            'mock_mode' => true,
        ]);
        $verifyResponse2->assertStatus(200);
        $verifyResponse2->assertJson(['success' => true, 'message' => 'Already verified']);

        // Assert balance did NOT double
        $this->hubUser->refresh();
        $this->assertEquals(2000.00, (float)$this->hubUser->wallet_balance);

        // Exactly one transaction row exists for this orderId
        $count = WalletTransaction::where('reference_id', $orderId)->count();
        $this->assertEquals(1, $count);
    }

    /**
     * Test 6: Admin can manually credit franchise wallet with auditable transaction.
     */
    public function test_admin_can_manually_credit_franchise_wallet_with_audit_trail(): void
    {
        $response = $this->actingAs($this->adminUser)->post(
            route('admin.franchises.wallet.credit', $this->franchise->id),
            [
                'amount' => 2500.00,
                'remarks' => 'Security Deposit Topup'
            ]
        );

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->hubUser->refresh();
        $this->assertEquals(2500.00, (float)$this->hubUser->wallet_balance);

        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $this->hubUser->id,
            'type' => 'credit',
            'amount' => 2500.00,
            'status' => 'success',
            'balance_after' => 2500.00,
        ]);

        $txn = WalletTransaction::where('user_id', $this->hubUser->id)
            ->where('amount', 2500.00)
            ->first();

        $this->assertNotNull($txn);
        $this->assertStringContainsString('Security Deposit Topup', $txn->description);
        $this->assertStringContainsString('Authorized by Super Admin Officer', $txn->description);
        $this->assertStringStartsWith('ADMIN_CREDIT_', $txn->reference_id);
    }

    /**
     * Test 7: Non-admin users cannot access admin manual credit endpoint.
     */
    public function test_non_admin_cannot_access_manual_wallet_credit_endpoint(): void
    {
        // Hub user tries to credit
        $response = $this->actingAs($this->hubUser)->post(
            route('admin.franchises.wallet.credit', $this->franchise->id),
            ['amount' => 1000]
        );

        $response->assertRedirect(route('hub.dashboard'));

        // Seller tries to credit
        $responseSeller = $this->actingAs($this->sellerUser)->post(
            route('admin.franchises.wallet.credit', $this->franchise->id),
            ['amount' => 1000]
        );

        $responseSeller->assertRedirect(route('seller.dashboard'));

        // Assert hub balance remained 0
        $this->hubUser->refresh();
        $this->assertEquals(0.00, (float)$this->hubUser->wallet_balance);
    }

    /**
     * Test 8: Seller wallet recharge regression remains unaffected and functional.
     */
    public function test_seller_wallet_recharge_regression_remains_unaffected(): void
    {
        // Seller initiates
        $response = $this->actingAs($this->sellerUser)->postJson(route('seller.wallet.recharge'), [
            'amount' => 500
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'mock_mode' => true]);

        $orderId = $response->json('order_id');

        // Seller verifies
        $verify = $this->actingAs($this->sellerUser)->postJson(route('seller.wallet.verify'), [
            'order_id' => $orderId,
            'mock_mode' => true,
        ]);

        $verify->assertStatus(200);
        $verify->assertJson(['success' => true, 'new_balance' => 500.00]);

        $this->sellerUser->refresh();
        $this->assertEquals(500.00, (float)$this->sellerUser->wallet_balance);
    }

    /**
     * Test 9: Hub wallet index page renders without undefined variable errors.
     */
    public function test_hub_wallet_index_renders_successfully_for_franchise_and_manager(): void
    {
        // 1. As Franchise User
        $response = $this->actingAs($this->hubUser)->get(route('hub.wallet.index'));
        $response->assertStatus(200);
        $response->assertSee('Wallet & Payouts', false);
        $response->assertSee('Recharge Wallet');

        // 2. As Hub Manager without direct franchise record
        $managerUser = User::factory()->create([
            'role' => 'franchise',
            'status' => 'active',
            'wallet_balance' => 0.00,
        ]);
        \App\Models\Hub::create([
            'hub_code' => 'TESTHUB99',
            'name' => 'Test Hub Station',
            'city' => 'Delhi',
            'pincode' => '110001',
            'capacity' => 1000,
            'manager_id' => $managerUser->id,
            'is_active' => true,
        ]);

        $managerResponse = $this->actingAs($managerUser)->get(route('hub.wallet.index'));
        $managerResponse->assertStatus(200);
        $managerResponse->assertSee('Wallet & Payouts', false);
    }
}
