<?php

namespace Tests\Feature;

use App\Models\Franchise;
use App\Models\Kyc;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HubReportsTest extends TestCase
{
    use RefreshDatabase;

    private User $franchiseManager;
    private Franchise $franchise;
    private User $seller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seller = User::factory()->create([
            'role'   => 'seller',
            'status' => 'active',
            'name'   => 'Star Seller Store',
        ]);
        Kyc::create(['user_id' => $this->seller->id, 'status' => 'approved', 'document_type' => 'gst']);

        $this->franchiseManager = User::factory()->create([
            'role'   => 'franchise',
            'status' => 'active',
            'name'   => 'Jaipur Hub In-charge',
        ]);
        Kyc::create(['user_id' => $this->franchiseManager->id, 'status' => 'approved', 'document_type' => 'aadhaar']);

        $this->franchise = Franchise::create([
            'user_id'             => $this->franchiseManager->id,
            'company_name'        => 'Jaipur Apex Logistics',
            'owner_name'          => 'Jaipur Hub In-charge',
            'phone'               => '9829000111',
            'email'               => 'jaipurhub@onestall.com',
            'address'             => 'MI Road Hub',
            'city'                => 'Jaipur',
            'state'               => 'Rajasthan',
            'status'              => 'approved',
            'franchise_level'     => 'district',
            'serviceable_pincodes'=> json_encode(['302001', '302002', '302003']),
        ]);
    }

    private function createShipment(array $attributes = []): Shipment
    {
        return Shipment::create(array_merge([
            'user_id'          => $this->seller->id,
            'franchise_id'     => $this->franchise->id,
            'awb_number'       => 'AWB-' . uniqid(),
            'order_id'         => 'ORD-' . rand(1000, 9999),
            'receiver_name'    => 'Customer Test',
            'receiver_phone'   => '9876543210',
            'delivery_address' => 'Station Road',
            'delivery_city'    => 'Jaipur',
            'delivery_state'   => 'Rajasthan',
            'delivery_pincode' => '302001',
            'weight_kg'        => 1.5,
            'invoice_value'    => 1200.00,
            'total_amount'     => 150.00,
            'shipping_charge'  => 150.00,
            'status'           => 'Delivered',
            'is_cod'           => 1,
            'cod_amount'       => 1200.00,
            'cod_remitted'     => 0,
            'created_at'       => now(),
            'updated_at'       => now(),
        ], $attributes));
    }

    public function test_unauthenticated_user_is_redirected_from_hub_reports(): void
    {
        $response = $this->get(route('hub.reports.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_franchise_manager_can_view_hub_reports_with_kpis_and_shipment_list(): void
    {
        // 1. Delivered COD
        $this->createShipment([
            'awb_number'    => 'AWB-HUB-DEL-01',
            'receiver_name' => 'Amitabh Bachchan',
            'status'        => 'Delivered',
            'is_cod'        => 1,
            'cod_amount'    => 2500.00,
            'cod_remitted'  => 0,
        ]);

        // 2. In Transit Prepaid
        $this->createShipment([
            'awb_number'    => 'AWB-HUB-TRANSIT-02',
            'receiver_name' => 'Shah Rukh Khan',
            'status'        => 'In Transit',
            'is_cod'        => 0,
            'cod_amount'    => 0,
        ]);

        // 3. RTO Initiated
        $this->createShipment([
            'awb_number'    => 'AWB-HUB-RTO-03',
            'receiver_name' => 'Salman Khan',
            'status'        => 'RTO Initiated',
            'is_cod'        => 1,
            'cod_amount'    => 1000.00,
        ]);

        $response = $this->actingAs($this->franchiseManager)->get(route('hub.reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Hub Reports & Analytics', false);
        $response->assertSee('AWB-HUB-DEL-01');
        $response->assertSee('AWB-HUB-TRANSIT-02');
        $response->assertSee('AWB-HUB-RTO-03');
        $response->assertSee('Amitabh Bachchan');

        // Check KPI values
        $response->assertSee('3'); // Total 3 shipments
        $response->assertSee('2,500.00'); // Pending COD
    }

    public function test_franchise_manager_can_filter_hub_reports_by_status_and_search(): void
    {
        $this->createShipment([
            'awb_number'    => 'AWB-SEARCH-TARGET',
            'receiver_name' => 'Target Customer',
            'status'        => 'Delivered',
        ]);

        $this->createShipment([
            'awb_number'    => 'AWB-SEARCH-OTHER',
            'receiver_name' => 'Other Customer',
            'status'        => 'NDR',
        ]);

        // Filter by status = Delivered
        $response = $this->actingAs($this->franchiseManager)->get(route('hub.reports.index', [
            'status' => 'Delivered',
        ]));
        $response->assertStatus(200);
        $response->assertSee('AWB-SEARCH-TARGET');
        $response->assertDontSee('AWB-SEARCH-OTHER');

        // Search by keyword
        $responseSearch = $this->actingAs($this->franchiseManager)->get(route('hub.reports.index', [
            'search' => 'TARGET',
        ]));
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('AWB-SEARCH-TARGET');
        $responseSearch->assertDontSee('AWB-SEARCH-OTHER');
    }

    public function test_franchise_manager_can_export_csv(): void
    {
        $this->createShipment([
            'awb_number'      => 'AWB-EXPORT-CSV-TEST',
            'order_id'        => 'ORD-EXP-999',
            'receiver_name'   => 'Export Receiver',
            'is_cod'          => 1,
            'cod_amount'      => 1750.00,
            'shipping_charge' => 125.00,
            'status'          => 'Delivered',
        ]);

        $response = $this->actingAs($this->franchiseManager)->get(route('hub.reports.export'));

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', (string) $response->headers->get('Content-Type'));

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertStringContainsString('AWB-EXPORT-CSV-TEST', $content);
        $this->assertStringContainsString('ORD-EXP-999', $content);
        $this->assertStringContainsString('Export Receiver', $content);
        $this->assertStringContainsString('1750.00', $content);
        $this->assertStringContainsString('125.00', $content);
    }
}
