<?php

namespace Tests\Feature;

use App\Models\Franchise;
use App\Models\Kyc;
use App\Models\Rider;
use App\Models\Shipment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerReportsAndHubRunSheetTest extends TestCase
{
    use RefreshDatabase;

    private User $seller;
    private User $seller2;
    private User $franchiseManager;
    private Franchise $franchise;
    private Rider $rider;
    private User $riderUser;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Verified Delhi Seller
        $this->seller = User::factory()->create([
            'role'            => 'seller',
            'status'          => 'active',
            'company_name'    => 'Alpha Retailers Ltd',
            'brand_name'      => 'AlphaStore',
            'gstin'           => '07AAAAA1234A1Z5',
            'pan_number'      => 'AAAAA1234A',
            'company_address' => 'Shop 12, Connaught Place',
            'company_city'    => 'New Delhi',
            'company_state'   => 'Delhi',
            'company_pincode' => '110001',
        ]);
        Kyc::create(['user_id' => $this->seller->id, 'status' => 'approved', 'document_type' => 'gst']);

        // 2. Create Second Seller (Maharashtra)
        $this->seller2 = User::factory()->create([
            'role'            => 'seller',
            'status'          => 'active',
            'company_name'    => 'Mumbai Traders Corp',
            'brand_name'      => 'MumbaiKart',
            'gstin'           => '27BBBBB5678B1Z2',
            'pan_number'      => 'BBBBB5678B',
            'company_address' => 'Andheri East',
            'company_city'    => 'Mumbai',
            'company_state'   => 'Maharashtra',
            'company_pincode' => '400069',
        ]);
        Kyc::create(['user_id' => $this->seller2->id, 'status' => 'approved', 'document_type' => 'gst']);

        // 3. Create Franchise Manager & Franchise Hub
        $this->franchiseManager = User::factory()->create([
            'role'   => 'franchise',
            'status' => 'active',
            'name'   => 'Delhi Hub Master',
        ]);
        Kyc::create(['user_id' => $this->franchiseManager->id, 'status' => 'approved', 'document_type' => 'aadhaar']);

        $this->franchise = Franchise::create([
            'user_id'             => $this->franchiseManager->id,
            'company_name'        => 'Delhi Central Cargo Hub',
            'owner_name'          => 'Delhi Hub Master',
            'phone'               => '9811002233',
            'email'               => 'delhihub@onestall.com',
            'address'             => 'Mahipalpur Logistics Zone',
            'city'                => 'New Delhi',
            'state'               => 'Delhi',
            'status'              => 'approved',
            'franchise_level'     => 'district',
            'state_royalty_type'  => 'percent',
            'state_royalty_rate'  => 5.00,
        ]);

        // 4. Create Rider under this Franchise
        $this->riderUser = User::factory()->create([
            'role'   => 'rider',
            'status' => 'active',
            'name'   => 'Suresh Kumar Rider',
            'phone'  => '9876543210',
        ]);

        $this->rider = Rider::create([
            'user_id'        => $this->riderUser->id,
            'franchise_id'   => $this->franchise->id,
            'vehicle_type'   => 'Bike',
            'vehicle_number' => 'DL-01-AB-1234',
            'status'         => 'active',
            'is_active'      => true,
        ]);
    }

    private function createShipment(array $attributes = []): Shipment
    {
        return Shipment::create(array_merge([
            'user_id'          => $this->seller->id,
            'awb_number'       => 'AWB-' . uniqid(),
            'order_id'         => 'ORD-' . rand(1000, 9999),
            'receiver_name'    => 'Test Receiver',
            'receiver_phone'   => '9876543210',
            'delivery_address' => '123 Market Road',
            'delivery_city'    => 'New Delhi',
            'delivery_state'   => 'Delhi',
            'delivery_pincode' => '110001',
            'weight_kg'        => 1.0,
            'invoice_value'    => 1000.00,
            'total_amount'     => 118.00,
            'shipping_charge'  => 118.00,
            'status'           => 'Delivered',
            'is_cod'           => 0,
        ], $attributes));
    }

    /* =========================================================================
     * PART 1: SELLER MONTHLY REPORT & GST TAX BILL TESTS
     * ========================================================================= */

    public function test_seller_monthly_report_renders_with_correct_financial_aggregates(): void
    {
        $currentMonth = now()->format('Y-m');

        // Shipment 1: Delivered Prepaid, Shipping charge = 118 (Taxable 100 + GST 18)
        $this->createShipment([
            'user_id'         => $this->seller->id,
            'awb_number'      => 'AWB-TEST-001',
            'order_id'        => 'ORD-101',
            'receiver_name'   => 'Rahul Sharma',
            'receiver_phone'  => '9876500001',
            'delivery_address'=> '45 Palace Road',
            'delivery_city'   => 'Jaipur',
            'delivery_state'  => 'Rajasthan',
            'delivery_pincode'=> '302001',
            'weight_kg'       => 2.5,
            'is_cod'          => 0,
            'invoice_value'   => 1500,
            'shipping_charge' => 118.00,
            'total_amount'    => 118.00,
            'status'          => 'Delivered',
            'created_at'      => now()->startOfMonth()->addDays(2),
            'updated_at'      => now()->startOfMonth()->addDays(3),
        ]);

        // Shipment 2: Out for Delivery COD, Shipping charge = 236 (Taxable 200 + GST 36), COD Amount = 2000
        $this->createShipment([
            'user_id'         => $this->seller->id,
            'awb_number'      => 'AWB-TEST-002',
            'order_id'        => 'ORD-102',
            'receiver_name'   => 'Pooja Verma',
            'receiver_phone'  => '9876500002',
            'delivery_address'=> '78 Civil Lines',
            'delivery_city'   => 'Agra',
            'delivery_state'  => 'Uttar Pradesh',
            'delivery_pincode'=> '282001',
            'weight_kg'       => 5.0,
            'is_cod'          => 1,
            'cod_amount'      => 2000.00,
            'invoice_value'   => 2000.00,
            'shipping_charge' => 236.00,
            'total_amount'    => 236.00,
            'status'          => 'Out for Delivery',
            'cod_remitted'    => 0,
            'created_at'      => now()->startOfMonth()->addDays(4),
            'updated_at'      => now()->startOfMonth()->addDays(4),
        ]);

        $response = $this->actingAs($this->seller)->get(route('seller.reports.index', ['month' => $currentMonth]));

        $response->assertStatus(200);
        $response->assertSee('Seller Monthly Report & GST Billing', false);
        $response->assertSee('AWB-TEST-001');
        $response->assertSee('AWB-TEST-002');
        // Total freight = 118 + 236 = 354
        $response->assertSee('354.00');
        // Taxable freight = 354 / 1.18 = 300
        $response->assertSee('300.00');
        // GST = 54
        $response->assertSee('54.00');
    }

    public function test_seller_monthly_report_filters_by_status_and_payment(): void
    {
        $currentMonth = now()->format('Y-m');

        $this->createShipment([
            'user_id'         => $this->seller->id,
            'awb_number'      => 'AWB-DELIVERED-COD',
            'order_id'        => 'ORD-103',
            'receiver_name'   => 'Amit Patel',
            'receiver_phone'  => '9876500003',
            'delivery_address'=> '12 Ring Road',
            'delivery_city'   => 'Surat',
            'delivery_pincode'=> '395001',
            'weight_kg'       => 1.0,
            'is_cod'          => 1,
            'cod_amount'      => 800,
            'shipping_charge' => 100.00,
            'status'          => 'Delivered',
            'created_at'      => now()->startOfMonth()->addDay(),
        ]);

        $this->createShipment([
            'user_id'         => $this->seller->id,
            'awb_number'      => 'AWB-NDR-PREPAID',
            'order_id'        => 'ORD-104',
            'receiver_name'   => 'Vikas Singh',
            'receiver_phone'  => '9876500004',
            'delivery_address'=> '56 Hazratganj',
            'delivery_city'   => 'Lucknow',
            'delivery_pincode'=> '226001',
            'weight_kg'       => 1.0,
            'is_cod'          => 0,
            'shipping_charge' => 100.00,
            'status'          => 'NDR',
            'created_at'      => now()->startOfMonth()->addDay(),
        ]);

        // Filter by status = Delivered
        $response = $this->actingAs($this->seller)->get(route('seller.reports.index', [
            'month'  => $currentMonth,
            'status' => 'Delivered',
        ]));
        $response->assertStatus(200);
        $response->assertSee('AWB-DELIVERED-COD');
        $response->assertDontSee('AWB-NDR-PREPAID');

        // Filter by payment_mode = COD
        $responseCod = $this->actingAs($this->seller)->get(route('seller.reports.index', [
            'month'        => $currentMonth,
            'payment_mode' => 'COD',
        ]));
        $responseCod->assertStatus(200);
        $responseCod->assertSee('AWB-DELIVERED-COD');
        $responseCod->assertDontSee('AWB-NDR-PREPAID');
    }

    public function test_seller_monthly_csv_export_streams_all_month_consignments(): void
    {
        $currentMonth = now()->format('Y-m');

        $this->createShipment([
            'user_id'         => $this->seller->id,
            'awb_number'      => 'AWB-CSV-EXP-01',
            'order_id'        => 'ORD-CSV-01',
            'receiver_name'   => 'Rohan Joshi',
            'receiver_phone'  => '9876500005',
            'delivery_address'=> 'Sector 62',
            'delivery_city'   => 'Noida',
            'delivery_pincode'=> '201301',
            'weight_kg'       => 3.0,
            'is_cod'          => 1,
            'cod_amount'      => 1200,
            'shipping_charge' => 118.00,
            'status'          => 'Delivered',
            'created_at'      => now()->startOfMonth()->addDays(2),
        ]);

        $response = $this->actingAs($this->seller)->get(route('seller.reports.monthly-csv', ['month' => $currentMonth]));

        $response->assertStatus(200);
        $this->assertEquals('text/csv; charset=UTF-8', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment; filename="Onestatall_Monthly_Report_', (string) $response->headers->get('Content-Disposition'));

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertStringContainsString('AWB-CSV-EXP-01', $content);
        $this->assertStringContainsString('Rohan Joshi', $content);
        $this->assertStringContainsString('118.00', $content);
        $this->assertStringContainsString('100.00', $content); // Taxable
        $this->assertStringContainsString('18.00', $content);  // GST
    }

    public function test_seller_gst_tax_invoice_calculates_intra_state_cgst_and_sgst_for_delhi_seller(): void
    {
        $currentMonth = now()->format('Y-m');

        // Billed: 1180 INR total (1000 taxable + 180 GST: 90 CGST + 90 SGST)
        $this->createShipment([
            'user_id'         => $this->seller->id,
            'awb_number'      => 'AWB-GST-DELHI-01',
            'receiver_name'   => 'Delhi Buyer',
            'delivery_address'=> 'Connaught Place Block A',
            'delivery_city'   => 'Delhi',
            'delivery_pincode'=> '110001',
            'weight_kg'       => 10.0,
            'shipping_charge' => 1180.00,
            'total_amount'    => 1180.00,
            'status'          => 'Delivered',
            'created_at'      => now()->startOfMonth()->addDays(5),
        ]);

        $response = $this->actingAs($this->seller)->get(route('seller.reports.gst-invoice', ['month' => $currentMonth]));

        $response->assertStatus(200);
        $response->assertSee('TAX INVOICE');
        $response->assertSee('996511'); // Logistics freight SAC code
        $response->assertSee('Alpha Retailers Ltd');
        $response->assertSee('07AAAAA1234A1Z5'); // Seller GSTIN
        $response->assertSee('Central Tax (CGST)');
        $response->assertSee('State Tax (SGST)');
        $response->assertSee('90.00'); // 9% CGST & 9% SGST on 1000 taxable
        $response->assertSee('1,180.00'); // Total invoice amount
        $response->assertSee('Rupees One Thousand One Hundred Eighty Only');
    }

    public function test_seller_gst_tax_invoice_calculates_inter_state_igst_for_maharashtra_seller(): void
    {
        $currentMonth = now()->format('Y-m');

        // Billed: 590 INR total (500 taxable + 90 IGST at 18%)
        $this->createShipment([
            'user_id'         => $this->seller2->id,
            'awb_number'      => 'AWB-GST-MUMBAI-01',
            'receiver_name'   => 'Mumbai Buyer',
            'delivery_address'=> 'Nariman Point',
            'delivery_city'   => 'Mumbai',
            'delivery_pincode'=> '400001',
            'weight_kg'       => 5.0,
            'shipping_charge' => 590.00,
            'total_amount'    => 590.00,
            'status'          => 'Delivered',
            'created_at'      => now()->startOfMonth()->addDays(3),
        ]);

        $response = $this->actingAs($this->seller2)->get(route('seller.reports.gst-invoice', ['month' => $currentMonth]));

        $response->assertStatus(200);
        $response->assertSee('TAX INVOICE');
        $response->assertSee('Mumbai Traders Corp');
        $response->assertSee('27BBBBB5678B1Z2');
        $response->assertSee('Integrated Tax (IGST)');
        $response->assertSee('90.00'); // 18% IGST on 500
        $response->assertSee('590.00');
    }

    public function test_sellers_cannot_access_each_others_monthly_report_data(): void
    {
        $currentMonth = now()->format('Y-m');

        $this->createShipment([
            'user_id'         => $this->seller->id,
            'awb_number'      => 'AWB-SELLER-1-PRIVATE',
            'shipping_charge' => 150.00,
            'total_amount'    => 150.00,
            'status'          => 'Delivered',
            'created_at'      => now()->startOfMonth()->addDay(),
        ]);

        $this->createShipment([
            'user_id'         => $this->seller2->id,
            'awb_number'      => 'AWB-SELLER-2-PRIVATE',
            'shipping_charge' => 250.00,
            'total_amount'    => 250.00,
            'status'          => 'Delivered',
            'created_at'      => now()->startOfMonth()->addDay(),
        ]);

        // Seller 1 sees only their own shipment
        $res1 = $this->actingAs($this->seller)->get(route('seller.reports.index', ['month' => $currentMonth]));
        $res1->assertSee('AWB-SELLER-1-PRIVATE');
        $res1->assertDontSee('AWB-SELLER-2-PRIVATE');

        // Seller 2 sees only their own shipment
        $res2 = $this->actingAs($this->seller2)->get(route('seller.reports.index', ['month' => $currentMonth]));
        $res2->assertSee('AWB-SELLER-2-PRIVATE');
        $res2->assertDontSee('AWB-SELLER-1-PRIVATE');
    }

    /* =========================================================================
     * PART 2: FRANCHISE DAILY RIDER HANDOVER & RUN SHEET (DRS) TESTS
     * ========================================================================= */

    public function test_franchise_daily_report_displays_given_and_received_parcels_and_cash(): void
    {
        $today = today()->toDateString();

        // 1. Parcel given for delivery - successfully delivered with Hard Cash (₹1500)
        $this->createShipment([
            'user_id'         => $this->seller->id,
            'rider_id'        => $this->riderUser->id,
            'franchise_id'    => $this->franchise->id,
            'awb_number'      => 'AWB-RUN-OFD-DELIVERED',
            'receiver_name'   => 'Karan Johar',
            'receiver_phone'  => '9876543001',
            'delivery_address'=> 'Mahipalpur Main Road',
            'delivery_city'   => 'New Delhi',
            'delivery_pincode'=> '110037',
            'weight_kg'       => 2.0,
            'is_cod'          => 1,
            'cod_amount'      => 1500.00,
            'payment_type'    => 'Cash',
            'status'          => 'Delivered',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // 2. Parcel given for delivery - still Out for Delivery
        $this->createShipment([
            'user_id'         => $this->seller->id,
            'rider_id'        => $this->riderUser->id,
            'franchise_id'    => $this->franchise->id,
            'awb_number'      => 'AWB-RUN-OFD-PENDING',
            'receiver_name'   => 'Simran Kaur',
            'receiver_phone'  => '9876543002',
            'delivery_address'=> 'Aerocity Towers',
            'delivery_city'   => 'New Delhi',
            'delivery_pincode'=> '110037',
            'weight_kg'       => 1.5,
            'is_cod'          => 0,
            'status'          => 'Out for Delivery',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // 3. Parcel given for delivery - Failed delivery (NDR) returned to hub
        $this->createShipment([
            'user_id'         => $this->seller->id,
            'rider_id'        => $this->riderUser->id,
            'franchise_id'    => $this->franchise->id,
            'awb_number'      => 'AWB-RUN-NDR-RETURN',
            'receiver_name'   => 'Deepak Gupta',
            'receiver_phone'  => '9876543003',
            'delivery_address'=> 'Kapashera Border',
            'delivery_city'   => 'New Delhi',
            'delivery_pincode'=> '110037',
            'weight_kg'       => 1.0,
            'is_cod'          => 1,
            'cod_amount'      => 500.00,
            'status'          => 'NDR',
            'ndr_reason'      => 'Customer Door Locked',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // 4. Inward Parcel: Pickup collected from seller by rider and brought into hub
        $this->createShipment([
            'user_id'         => $this->seller->id,
            'rider_id'        => $this->riderUser->id,
            'franchise_id'    => $this->franchise->id,
            'awb_number'      => 'AWB-RUN-PICKUP-RECD',
            'receiver_name'   => 'Bangalore Client',
            'delivery_address'=> 'Indiranagar 100ft road',
            'delivery_city'   => 'Bangalore',
            'delivery_pincode'=> '560001',
            'weight_kg'       => 4.2,
            'status'          => 'In Transit',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        $response = $this->actingAs($this->franchiseManager)
            ->get(route('hub.fleet.report', ['id' => $this->rider->id, 'date' => $today]));

        $response->assertStatus(200);
        $response->assertSee('Suresh Kumar Rider');
        $response->assertSee('DL-01-AB-1234');

        // Check Outward parcels given for delivery
        $response->assertSee('AWB-RUN-OFD-DELIVERED');
        $response->assertSee('AWB-RUN-OFD-PENDING');
        $response->assertSee('AWB-RUN-NDR-RETURN');

        // Check Inward pickups received at hub
        $response->assertSee('AWB-RUN-PICKUP-RECD');

        // Check Cash handover counter (₹1500)
        $response->assertSee('1,500.00');
    }

    public function test_franchise_daily_run_sheet_drs_renders_printable_manifest_with_dual_signatures(): void
    {
        $today = today()->toDateString();

        $this->createShipment([
            'user_id'         => $this->seller->id,
            'rider_id'        => $this->riderUser->id,
            'franchise_id'    => $this->franchise->id,
            'awb_number'      => 'AWB-DRS-PRINT-01',
            'receiver_name'   => 'Harish Chandra',
            'receiver_phone'  => '9876543004',
            'delivery_address'=> 'Block C, Vasant Kunj',
            'delivery_city'   => 'New Delhi',
            'delivery_pincode'=> '110070',
            'weight_kg'       => 1.8,
            'is_cod'          => 1,
            'cod_amount'      => 750.00,
            'payment_type'    => 'Cash',
            'status'          => 'Delivered',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        $response = $this->actingAs($this->franchiseManager)
            ->get(route('hub.fleet.runsheet', ['id' => $this->rider->id, 'date' => $today]));

        $response->assertStatus(200);
        $response->assertSee('Daily Run Sheet & Handover Manifest (DRS)', false);
        $response->assertSee('AWB-DRS-PRINT-01');
        $response->assertSee('Harish Chandra');
        $response->assertSee('Block C, Vasant Kunj');
        $response->assertSee('750.00');

        // Dual Signature verification blocks
        $response->assertSee('Rider / Driver Declaration & Signature', false);
        $response->assertSee('Hub Supervisor / Manager Verification', false);
    }

    public function test_franchise_daily_run_sheet_csv_export(): void
    {
        $today = today()->toDateString();

        $this->createShipment([
            'user_id'         => $this->seller->id,
            'rider_id'        => $this->riderUser->id,
            'franchise_id'    => $this->franchise->id,
            'awb_number'      => 'AWB-DRS-CSV-01',
            'receiver_name'   => 'Manish Malhotra',
            'receiver_phone'  => '9876543005',
            'delivery_address'=> 'South Ex Part 1',
            'delivery_city'   => 'New Delhi',
            'delivery_pincode'=> '110049',
            'weight_kg'       => 2.0,
            'is_cod'          => 1,
            'cod_amount'      => 999.00,
            'status'          => 'Delivered',
            'payment_type'    => 'Cash',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        $response = $this->actingAs($this->franchiseManager)
            ->get(route('hub.fleet.runsheet.csv', ['id' => $this->rider->id, 'date' => $today]));

        $response->assertStatus(200);
        $this->assertEquals('text/csv; charset=UTF-8', $response->headers->get('Content-Type'));

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertStringContainsString('DAILY RUN SHEET & HANDOVER MANIFEST (DRS)', $content);
        $this->assertStringContainsString('AWB-DRS-CSV-01', $content);
        $this->assertStringContainsString('Manish Malhotra', $content);
        $this->assertStringContainsString('999.00', $content);
    }

    public function test_unauthorized_franchise_cannot_access_another_franchises_rider(): void
    {
        // Another independent district franchise
        $otherManager = User::factory()->create(['role' => 'franchise', 'status' => 'active']);
        Kyc::create(['user_id' => $otherManager->id, 'status' => 'approved', 'document_type' => 'aadhaar']);
        Franchise::create([
            'user_id'         => $otherManager->id,
            'company_name'    => 'Kolkata Apex Cargo',
            'owner_name'      => 'Kolkata Lead',
            'phone'           => '9833001122',
            'email'           => 'kolkata@onestall.com',
            'address'         => 'Howrah Hub',
            'city'            => 'Kolkata',
            'state'           => 'West Bengal',
            'status'          => 'approved',
            'franchise_level' => 'district',
        ]);

        // Kolkata manager tries to access Suresh Kumar (Delhi rider)
        $response = $this->actingAs($otherManager)
            ->get(route('hub.fleet.report', ['id' => $this->rider->id]));

        $response->assertStatus(403);
    }
}
