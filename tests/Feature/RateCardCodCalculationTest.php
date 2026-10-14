<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\RateCard;
use App\Models\RateCardZone;
use App\Services\PricingService;
use App\Services\Couriers\IndiaPostParcelService;
use App\Services\Couriers\IndiaPostSpeedPostService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RateCardCodCalculationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed Couriers using DB table directly to preserve specific IDs 9 and 10
        \Illuminate\Support\Facades\DB::table('couriers')->insert([
            [
                'id' => 9,
                'name' => 'India Post Parcel',
                'mode' => 'sandbox',
                'is_active' => 1,
                'markup_type' => 'percentage',
                'markup_value' => 0.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 10,
                'name' => 'India Post Speed Post',
                'mode' => 'sandbox',
                'is_active' => 1,
                'markup_type' => 'percentage',
                'markup_value' => 0.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Seed active OneStall Cargo RateCard (courier_id = null)
        $onestallCard = RateCard::create([
            'courier_id' => null,
            'version_name' => 'Official Rate Card V1',
            'effective_from' => '2026-09-01',
            'is_active' => true,
            'fsc_percent' => 10.00,
            'cod_min_charge' => 20.00,
            'cod_threshold_amount' => 1400.00,
            'cod_percent' => 1.50,
            'dto_multiplier' => 1.30,
            'qc_base_charge' => 35.00,
            'qc_included_params' => 3,
            'qc_additional_param_charge' => 5.00,
            'volumetric_divisor' => 5000.00,
            'gst_percent' => 18.00,
        ]);

        RateCardZone::create([
            'rate_card_id' => $onestallCard->id,
            'zone_name' => 'Zone 1 - Local',
            'first_0_5_kg' => 30.00,
            'addl_0_5_kg' => 15.00,
            'first_2_kg' => 60.00,
            'addl_1_kg_after_2' => 20.00,
            'first_5_kg' => 120.00,
            'addl_1_kg_after_5' => 25.00,
            'first_10_kg' => 240.00,
            'addl_1_kg_after_10' => 30.00,
            'first_20_kg' => 480.00,
            'addl_1_kg_after_20' => 35.00,
        ]);

        // Seed India Post Parcel RateCard (courier_id = 9)
        $parcelCard = RateCard::create([
            'courier_id' => 9,
            'version_name' => 'India Post Parcel Contractual',
            'effective_from' => '2026-10-06',
            'is_active' => true,
            'fsc_percent' => 0.00,
            'cod_min_charge' => 20.00,
            'cod_threshold_amount' => 2000.00,
            'cod_percent' => 1.60,
            'dto_multiplier' => 1.30,
            'qc_base_charge' => 35.00,
            'qc_included_params' => 3,
            'qc_additional_param_charge' => 5.00,
            'volumetric_divisor' => 5000.00,
            'gst_percent' => 18.00,
        ]);

        foreach (['Zone 1 - Local', 'Zone 2 - Regional', 'Zone 3 - Metros', 'Zone 4 - Rest of India'] as $zName) {
            RateCardZone::create([
                'rate_card_id' => $parcelCard->id,
                'zone_name' => $zName,
                'first_0_5_kg' => 32.40,
                'addl_0_5_kg' => 15.00,
                'first_2_kg' => 60.00,
                'addl_1_kg_after_2' => 20.00,
                'first_5_kg' => 120.00,
                'addl_1_kg_after_5' => 25.00,
                'first_10_kg' => 240.00,
                'addl_1_kg_after_10' => 30.00,
                'first_20_kg' => 480.00,
                'addl_1_kg_after_20' => 35.00,
            ]);
        }

        // Seed India Post Speed Post RateCard (courier_id = 10)
        $speedCard = RateCard::create([
            'courier_id' => 10,
            'version_name' => 'India Post Speed Post Contractual',
            'effective_from' => '2026-10-06',
            'is_active' => true,
            'fsc_percent' => 0.00,
            'cod_min_charge' => 20.00,
            'cod_threshold_amount' => 2000.00,
            'cod_percent' => 1.60,
            'dto_multiplier' => 1.30,
            'qc_base_charge' => 35.00,
            'qc_included_params' => 3,
            'qc_additional_param_charge' => 5.00,
            'volumetric_divisor' => 5000.00,
            'gst_percent' => 18.00,
        ]);

        foreach (['Zone 1 - Local', 'Zone 2 - Regional', 'Zone 3 - Metros', 'Zone 4 - Rest of India'] as $zName) {
            RateCardZone::create([
                'rate_card_id' => $speedCard->id,
                'zone_name' => $zName,
                'first_0_5_kg' => 35.00,
                'addl_0_5_kg' => 15.00,
                'first_2_kg' => 65.00,
                'addl_1_kg_after_2' => 20.00,
                'first_5_kg' => 125.00,
                'addl_1_kg_after_5' => 25.00,
                'first_10_kg' => 245.00,
                'addl_1_kg_after_10' => 30.00,
                'first_20_kg' => 485.00,
                'addl_1_kg_after_20' => 35.00,
            ]);
        }
    }

    /**
     * Test OneStall Cargo COD threshold and boundary rules on RateCard model:
     * Min ₹20, Threshold ₹1,400, 1.5%
     */
    public function test_onestall_ratecard_cod_rules()
    {
        $card = new RateCard([
            'cod_min_charge' => 20.00,
            'cod_threshold_amount' => 1400.00,
            'cod_percent' => 1.50,
        ]);

        // Scenario 1: ₹500 COD <= ₹1,400 -> Minimum ₹20.00
        $this->assertEquals(20.00, $card->calculateCodCharge(500, true));

        // Scenario 2: ₹1,400 COD <= ₹1,400 -> Minimum ₹20.00
        $this->assertEquals(20.00, $card->calculateCodCharge(1400, true));

        // Scenario 3: ₹1,401 COD > ₹1,400 -> max(20, round(1401 * 0.015, 2)) = max(20, 21.02) = ₹21.02
        $this->assertEquals(21.02, $card->calculateCodCharge(1401, true));

        // Scenario 4: ₹5,000 COD > ₹1,400 -> max(20, round(5000 * 0.015, 2)) = ₹75.00
        $this->assertEquals(75.00, $card->calculateCodCharge(5000, true));

        // Scenario 5: Prepaid ($isCod = false or amount = 0) -> ₹0.00
        $this->assertEquals(0.00, $card->calculateCodCharge(5000, false));
        $this->assertEquals(0.00, $card->calculateCodCharge(0, true));
    }

    /**
     * Test India Post COD threshold and boundary rules on RateCard model:
     * Min ₹20, Threshold ₹2,000, 1.6%
     */
    public function test_indiapost_ratecard_cod_rules()
    {
        $card = new RateCard([
            'cod_min_charge' => 20.00,
            'cod_threshold_amount' => 2000.00,
            'cod_percent' => 1.60,
        ]);

        // Scenario 1: ₹500 COD <= ₹2,000 -> Minimum ₹20.00
        $this->assertEquals(20.00, $card->calculateCodCharge(500, true));

        // Scenario 2: ₹2,000 COD <= ₹2,000 -> Minimum ₹20.00
        $this->assertEquals(20.00, $card->calculateCodCharge(2000, true));

        // Scenario 3: ₹2,001 COD > ₹2,000 -> max(20, round(2001 * 0.016, 2)) = max(20, 32.02) = ₹32.02
        $this->assertEquals(32.02, $card->calculateCodCharge(2001, true));

        // Scenario 4: ₹5,000 COD > ₹2,000 -> max(20, round(5000 * 0.016, 2)) = ₹80.00
        $this->assertEquals(80.00, $card->calculateCodCharge(5000, true));

        // Scenario 5: Prepaid -> ₹0.00
        $this->assertEquals(0.00, $card->calculateCodCharge(5000, false));
        $this->assertEquals(0.00, $card->calculateCodCharge(0, true));
    }

    /**
     * Test backwards compatibility when threshold is 0 (e.g. standard rate cards)
     */
    public function test_ratecard_without_threshold_backwards_compatibility()
    {
        $card = new RateCard([
            'cod_min_charge' => 30.00,
            'cod_threshold_amount' => 0.00,
            'cod_percent' => 1.25,
        ]);

        // Below min charge threshold: 1000 * 1.25% = 12.50 < 30 -> ₹30.00
        $this->assertEquals(30.00, $card->calculateCodCharge(1000, true));

        // Above min charge threshold: 4000 * 1.25% = 50.00 > 30 -> ₹50.00
        $this->assertEquals(50.00, $card->calculateCodCharge(4000, true));
    }

    /**
     * Test active RateCard #1 with PricingService calculateOneStallRate
     */
    public function test_pricing_service_onestall_active_card_integration()
    {
        $pricing = app(PricingService::class);

        // COD <= 1400 -> ₹20.00
        $res1400 = $pricing->calculateOneStallRate('110001', '110002', 0.5, 10, 10, 10, true, 1400);
        $this->assertEquals(20.00, $res1400['cod_charge']);

        // COD 1401 -> ₹21.02
        $res1401 = $pricing->calculateOneStallRate('110001', '110002', 0.5, 10, 10, 10, true, 1401);
        $this->assertEquals(21.02, $res1401['cod_charge']);

        // COD 5000 -> ₹75.00
        $res5000 = $pricing->calculateOneStallRate('110001', '110002', 0.5, 10, 10, 10, true, 5000);
        $this->assertEquals(75.00, $res5000['cod_charge']);

        // Prepaid -> ₹0.00
        $resPrepaid = $pricing->calculateOneStallRate('110001', '110002', 0.5, 10, 10, 10, false, 5000);
        $this->assertEquals(0.00, $resPrepaid['cod_charge']);
    }

    /**
     * Test active RateCard #5 with IndiaPostParcelService
     */
    public function test_indiapost_parcel_service_active_card_integration()
    {
        $service = new IndiaPostParcelService();

        // COD <= 2000 -> ₹20.00
        $res2000 = $service->calculateRate('110001', '110002', 0.5, true, 2000);
        $this->assertEquals('success', $res2000['status']);
        $this->assertEquals(20.00, $res2000['cod_charge']);

        // COD 2001 -> ₹32.02
        $res2001 = $service->calculateRate('110001', '110002', 0.5, true, 2001);
        $this->assertEquals(32.02, $res2001['cod_charge']);

        // COD 5000 -> ₹80.00
        $res5000 = $service->calculateRate('110001', '110002', 0.5, true, 5000);
        $this->assertEquals(80.00, $res5000['cod_charge']);

        // Prepaid -> ₹0.00
        $resPrepaid = $service->calculateRate('110001', '110002', 0.5, false, 5000);
        $this->assertEquals(0.00, $resPrepaid['cod_charge']);
    }

    /**
     * Test active RateCard #6 with IndiaPostSpeedPostService
     */
    public function test_indiapost_speedpost_service_active_card_integration()
    {
        $service = new IndiaPostSpeedPostService();

        // COD <= 2000 -> ₹20.00
        $res2000 = $service->calculateRate('110001', '110002', 0.5, true, 2000);
        $this->assertEquals('success', $res2000['status']);
        $this->assertEquals(20.00, $res2000['cod_charge']);

        // COD 2001 -> ₹32.02
        $res2001 = $service->calculateRate('110001', '110002', 0.5, true, 2001);
        $this->assertEquals(32.02, $res2001['cod_charge']);

        // COD 5000 -> ₹80.00
        $res5000 = $service->calculateRate('110001', '110002', 0.5, true, 5000);
        $this->assertEquals(80.00, $res5000['cod_charge']);

        // Prepaid -> ₹0.00
        $resPrepaid = $service->calculateRate('110001', '110002', 0.5, false, 5000);
        $this->assertEquals(0.00, $resPrepaid['cod_charge']);
    }

    /**
     * Test GST calculation and 2-decimal precision rounding
     */
    public function test_gst_and_two_decimal_precision_rounding()
    {
        $pricing = app(PricingService::class);

        // OneStall Zone 1, 0.5kg, COD ₹1,401
        // Base: ₹30.00, FSC: ₹3.00, COD: ₹21.02 -> Subtotal: ₹54.02
        // GST (18%): round(54.02 * 0.18, 2) = ₹9.72
        // Total: 54.02 + 9.72 = ₹63.74
        $res = $pricing->calculateOneStallRate('110001', '110002', 0.5, 10, 10, 10, true, 1401);

        $this->assertEquals(30.00, $res['base_freight']);
        $this->assertEquals(3.00, $res['fsc_amount']);
        $this->assertEquals(21.02, $res['cod_charge']);
        $this->assertEquals(9.72, $res['gst']);
        $this->assertEquals(63.74, $res['total']);
    }

    /**
     * Test public Quotation API endpoint parity for COD calculation
     */
    public function test_quotation_api_cod_calculation_parity()
    {
        // OneStall Courier in DB for quotation
        \Illuminate\Support\Facades\DB::table('couriers')->insert([
            'id' => 999,
            'name' => 'OneStall Cargo (Direct)',
            'mode' => 'production',
            'is_active' => 1,
            'markup_type' => 'percentage',
            'markup_value' => 0.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Make 110002 serviceable by OneStall
        \App\Models\ServiceablePincode::create([
            'pincode' => '110002',
            'city' => 'Delhi',
            'state' => 'Delhi',
            'is_active' => true,
        ]);

        // 1. OneStall: COD ₹1,400 -> COD charge ₹20.00
        $response1400 = $this->postJson('/api/v1/public/rates', [
            'pickup_pincode' => '110001',
            'delivery_pincode' => '110002',
            'weight' => 0.5,
            'payment_mode' => 'cod',
            'cod_amount' => 1400,
            'invoice_amount' => 1400,
        ]);
        $response1400->assertStatus(200);
        $data1400 = $response1400->json('data.0');
        $this->assertEquals(20.00, $data1400['cod_charges']);

        // 2. OneStall: COD ₹1,401 -> COD charge ₹21.02
        $response1401 = $this->postJson('/api/v1/public/rates', [
            'pickup_pincode' => '110001',
            'delivery_pincode' => '110002',
            'weight' => 0.5,
            'payment_mode' => 'cod',
            'cod_amount' => 1401,
            'invoice_amount' => 1401,
        ]);
        $response1401->assertStatus(200);
        $data1401 = $response1401->json('data.0');
        $this->assertEquals(21.02, $data1401['cod_charges']);

        // 3. OneStall: Prepaid -> COD charge ₹0.00
        $responsePrepaid = $this->postJson('/api/v1/public/rates', [
            'pickup_pincode' => '110001',
            'delivery_pincode' => '110002',
            'weight' => 0.5,
            'payment_mode' => 'prepaid',
            'invoice_amount' => 1401,
        ]);
        $responsePrepaid->assertStatus(200);
        $dataPrepaid = $responsePrepaid->json('data.0');
        $this->assertEquals(0.00, $dataPrepaid['cod_charges']);

        // 4. India Post (non-OneStall destination 560001): COD ₹2,001 -> COD charge ₹32.02
        $responseIndiaPost = $this->postJson('/api/v1/public/rates', [
            'pickup_pincode' => '110001',
            'delivery_pincode' => '560001',
            'weight' => 0.5,
            'payment_mode' => 'cod',
            'cod_amount' => 2001,
            'invoice_amount' => 2001,
        ]);
        $responseIndiaPost->assertStatus(200);
        $dataIP = $responseIndiaPost->json('data.0');
        $this->assertEquals(32.02, $dataIP['cod_charges']);
    }
}
