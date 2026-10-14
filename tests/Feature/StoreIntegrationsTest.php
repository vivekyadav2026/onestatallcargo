<?php

namespace Tests\Feature;

use App\Models\Channel;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class StoreIntegrationsTest extends TestCase
{
    use RefreshDatabase;

    protected User $sellerUser;
    protected User $hubUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sellerUser = User::factory()->create([
            'role' => 'seller',
            'status' => 'active',
            'wallet_balance' => 1000.00,
        ]);
        \App\Models\Kyc::create([
            'user_id' => $this->sellerUser->id,
            'status' => 'approved',
            'document_type' => 'pan',
        ]);

        $this->hubUser = User::factory()->create([
            'role' => 'franchise',
            'status' => 'active',
            'wallet_balance' => 1000.00,
        ]);

        Warehouse::create([
            'user_id' => $this->sellerUser->id,
            'name' => 'Main Seller Warehouse',
            'contact_person' => 'Seller Contact',
            'phone' => '9876543210',
            'address' => 'Warehouse 1, Transport Nagar',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'pincode' => '302003',
            'is_default' => true,
        ]);
    }

    public function test_seller_can_view_integrations_page(): void
    {
        $response = $this->actingAs($this->sellerUser)
            ->get(route('seller.integrations'));

        $response->assertStatus(200);
        $response->assertSee('E-commerce Integrations');
        $response->assertSee('WooCommerce');
        $response->assertSee('Shopify');
        $response->assertSee('Custom Webhook');
    }

    public function test_hub_can_view_integrations_page(): void
    {
        $response = $this->actingAs($this->hubUser)
            ->get(route('hub.integrations'));

        $response->assertStatus(200);
        $response->assertSee('E-commerce Integrations');
    }

    public function test_seller_can_connect_woocommerce_store(): void
    {
        Http::fake([
            'https://mytestshop.com/wp-json/wc/v3/*' => Http::response(['status' => 'ok'], 200),
        ]);

        $response = $this->actingAs($this->sellerUser)
            ->post(route('seller.integrations.save'), [
                'platform' => 'woocommerce',
                'name' => 'My WordPress Fashion',
                'store_url' => 'https://mytestshop.com',
                'consumer_key' => 'ck_test1234567890',
                'consumer_secret' => 'cs_test1234567890',
            ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('channels', [
            'user_id' => $this->sellerUser->id,
            'platform' => 'woocommerce',
            'name' => 'My WordPress Fashion',
            'store_url' => 'https://mytestshop.com',
            'consumer_key' => 'ck_test1234567890',
        ]);
    }

    public function test_seller_can_connect_shopify_store(): void
    {
        Http::fake([
            'https://my-brand.myshopify.com/admin/api/*' => Http::response([
                'shop' => ['name' => 'My Shopify Brand']
            ], 200),
        ]);

        $response = $this->actingAs($this->sellerUser)
            ->post(route('seller.integrations.save'), [
                'platform' => 'shopify',
                'name' => 'My Shopify Brand',
                'store_url' => 'my-brand.myshopify.com',
                'access_token' => 'shpat_testtoken123456',
            ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('channels', [
            'user_id' => $this->sellerUser->id,
            'platform' => 'shopify',
            'store_url' => 'my-brand.myshopify.com',
        ]);
    }

    public function test_seller_can_disconnect_store(): void
    {
        $channel = Channel::create([
            'user_id' => $this->sellerUser->id,
            'platform' => 'woocommerce',
            'name' => 'To Be Disconnected',
            'store_url' => 'https://disconnect-test.com',
            'consumer_key' => 'ck_test',
            'consumer_secret' => 'cs_test',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->sellerUser)
            ->post(route('seller.integrations.disconnect', $channel->id));

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('channels', [
            'id' => $channel->id,
        ]);
    }

    public function test_woocommerce_order_sync_creates_shipment(): void
    {
        $channel = Channel::create([
            'user_id' => $this->sellerUser->id,
            'platform' => 'woocommerce',
            'name' => 'Active Woo Shop',
            'store_url' => 'https://activewoo.com',
            'consumer_key' => 'ck_active',
            'consumer_secret' => 'cs_active',
            'is_active' => true,
        ]);

        Http::fake([
            'https://activewoo.com/wp-json/wc/v3/orders*' => Http::response([
                [
                    'id' => 1055,
                    'total' => '899.00',
                    'payment_method' => 'cod',
                    'shipping' => [
                        'first_name' => 'Aarav',
                        'last_name' => 'Sharma',
                        'address_1' => 'Plot 45, Tonk Road',
                        'city' => 'Jaipur',
                        'state' => 'Rajasthan',
                        'postcode' => '302015',
                        'phone' => '9829011111',
                    ],
                    'billing' => [
                        'email' => 'aarav@example.com',
                        'phone' => '9829011111',
                    ],
                    'line_items' => [
                        [
                            'name' => 'Designer Cotton Shirt',
                            'quantity' => 2,
                        ]
                    ]
                ]
            ], 200),
        ]);

        $response = $this->actingAs($this->sellerUser)
            ->post(route('seller.integrations.sync', $channel->id));

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('shipments', [
            'user_id' => $this->sellerUser->id,
            'order_id' => 'WC-1055',
            'receiver_name' => 'Aarav Sharma',
            'receiver_phone' => '9829011111',
            'delivery_city' => 'Jaipur',
            'delivery_pincode' => '302015',
            'is_cod' => 1,
            'invoice_value' => 899.00,
            'status' => 'New',
        ]);

        $channel->refresh();
        $this->assertEquals(1, $channel->synced_orders_count);
        $this->assertNotNull($channel->last_sync_at);
    }

    public function test_incoming_webhook_creates_shipment_automatically(): void
    {
        $payload = [
            'id' => 2088,
            'total' => '1499.00',
            'payment_method' => 'prepaid',
            'shipping' => [
                'first_name' => 'Pooja',
                'last_name' => 'Verma',
                'address_1' => 'B-12, Malviya Nagar',
                'city' => 'Delhi',
                'state' => 'Delhi',
                'postcode' => '110017',
                'phone' => '9811022222',
            ],
            'billing' => [
                'email' => 'pooja@example.com',
                'phone' => '9811022222',
            ],
            'line_items' => [
                [
                    'name' => 'Leather Handbag',
                    'quantity' => 1,
                ]
            ]
        ];

        $response = $this->postJson(
            route('api.integrations.webhook', [
                'platform' => 'woocommerce',
                'user_id' => $this->sellerUser->id,
            ]),
            $payload
        );

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('shipments', [
            'user_id' => $this->sellerUser->id,
            'order_id' => 'WC-2088',
            'receiver_name' => 'Pooja Verma',
            'receiver_phone' => '9811022222',
            'delivery_city' => 'Delhi',
            'delivery_pincode' => '110017',
            'is_cod' => 0,
            'invoice_value' => 1499.00,
        ]);
    }

    public function test_incoming_custom_webhook_creates_shipment(): void
    {
        $payload = [
            'order_id' => 'CUSTOM-ORD-77',
            'receiver_name' => 'Vikram Singh',
            'receiver_phone' => '9988776655',
            'receiver_email' => 'vikram@custom.com',
            'delivery_address' => 'Sector 14, Civil Lines',
            'delivery_city' => 'Jaipur',
            'delivery_pincode' => '302006',
            'delivery_state' => 'Rajasthan',
            'weight_kg' => 1.2,
            'is_cod' => true,
            'invoice_value' => 2100.00,
            'product_name' => 'Wireless Headphones',
            'product_qty' => 1,
        ];

        $response = $this->postJson(
            route('api.integrations.webhook', [
                'platform' => 'custom',
                'user_id' => $this->sellerUser->id,
            ]),
            $payload
        );

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('shipments', [
            'user_id' => $this->sellerUser->id,
            'order_id' => 'CUSTOM-CUSTOM-ORD-77',
            'receiver_name' => 'Vikram Singh',
            'receiver_phone' => '9988776655',
            'is_cod' => 1,
            'invoice_value' => 2100.00,
        ]);
    }
}
