<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kyc;
use App\Models\Franchise;
use App\Models\Hub;
use App\Models\Rider;
use App\Models\Warehouse;
use App\Models\Courier;
use App\Models\Shipment;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Settings & API Docs
        $this->call([
            SettingSeeder::class,
            ApiDocSeeder::class,
        ]);

        // 2. Couriers
        $couriers = [
            ['name' => 'Delhivery', 'mode' => 'sandbox', 'is_active' => true],
            ['name' => 'Blue Dart', 'mode' => 'sandbox', 'is_active' => true],
            ['name' => 'Shadowfax', 'mode' => 'sandbox', 'is_active' => true],
            ['name' => 'Xpressbees', 'mode' => 'sandbox', 'is_active' => true],
        ];
        foreach ($couriers as $c) {
            Courier::firstOrCreate(['name' => $c['name']], $c);
        }

        // 3. Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@onestallcargo.com'],
            [
                'name'         => 'Super Admin',
                'password'     => Hash::make('password123'),
                'role'         => 'admin',
                'status'       => 'active',
                'phone'        => '9999999991',
                'company_name' => 'OneStall Cargo HQ',
            ]
        );

        // 4. Seller User (with Approved KYC and Warehouse)
        $seller = User::firstOrCreate(
            ['email' => 'seller@onestallcargo.com'],
            [
                'name'            => 'Demo Seller',
                'password'        => Hash::make('password123'),
                'role'            => 'seller',
                'status'          => 'active',
                'phone'           => '9999999992',
                'company_name'    => 'Trendz Fashion Pvt Ltd',
                'brand_name'      => 'Trendz',
                'gstin'           => '07AAAAA0000A1Z5',
                'pan_number'      => 'AAAAA0000A',
                'business_type'   => 'private_limited',
                'wallet_balance'  => 5000.00,
                'company_address' => 'Plot 45, Okhla Phase 3',
                'company_city'    => 'New Delhi',
                'company_state'   => 'Delhi',
                'company_pincode' => '110020',
            ]
        );

        Kyc::firstOrCreate(
            ['user_id' => $seller->id],
            [
                'business_type'   => 'private_limited',
                'document_type'   => 'gst',
                'document_number' => '07AAAAA0000A1Z5',
                'pan_number'      => 'AAAAA0000A',
                'gst_number'      => '07AAAAA0000A1Z5',
                'status'          => 'approved',
                'approved_at'     => now(),
            ]
        );

        Warehouse::firstOrCreate(
            ['user_id' => $seller->id, 'name' => 'Primary Okhla Hub'],
            [
                'contact_person' => 'Ramesh Sharma',
                'phone'          => '9999999992',
                'email'          => 'warehouse@trendz.com',
                'address'        => 'Plot 45, Okhla Phase 3',
                'city'           => 'New Delhi',
                'state'          => 'Delhi',
                'pincode'        => '110020',
                'is_default'     => true,
            ]
        );

        // 5. Hub / Franchise User (with Approved KYC, Franchise, and Hub)
        $hubUser = User::firstOrCreate(
            ['email' => 'hub@onestallcargo.com'],
            [
                'name'         => 'Delhi Central Hub Manager',
                'password'     => Hash::make('password123'),
                'role'         => 'franchise',
                'status'       => 'active',
                'phone'        => '9999999993',
                'company_name' => 'OneStall Delhi Apex Hub',
            ]
        );

        Kyc::firstOrCreate(
            ['user_id' => $hubUser->id],
            [
                'business_type'   => 'proprietorship',
                'document_type'   => 'aadhaar',
                'document_number' => '123456789012',
                'pan_number'      => 'BBBBB0000B',
                'status'          => 'approved',
                'approved_at'     => now(),
            ]
        );

        $franchise = Franchise::firstOrCreate(
            ['user_id' => $hubUser->id],
            [
                'company_name'         => 'OneStall Delhi Apex Hub',
                'owner_name'           => 'Delhi Central Hub Manager',
                'phone'                => '9999999993',
                'email'                => 'hub@onestallcargo.com',
                'address'              => 'Warehouse B-12, Okhla Industrial Area',
                'city'                 => 'New Delhi',
                'district'             => 'South Delhi',
                'state'                => 'Delhi',
                'status'               => 'approved',
                'franchise_level'      => 'district',
                'serviceable_pincodes' => ['110001', '110020', '110025', '110044', '110065'],
            ]
        );

        $hub = Hub::firstOrCreate(
            ['hub_code' => 'DEL-01'],
            [
                'manager_id' => $hubUser->id,
                'name'       => 'Delhi Central Hub',
                'city'       => 'New Delhi',
                'pincode'    => '110020',
                'is_active'  => true,
            ]
        );

        // 6. Rider User (with Rider Record)
        $riderUser = User::firstOrCreate(
            ['email' => 'rider@onestallcargo.com'],
            [
                'name'         => 'Rahul Sharma (Rider)',
                'password'     => Hash::make('password123'),
                'role'         => 'rider',
                'status'       => 'active',
                'phone'        => '9999999994',
                'company_name' => 'OneStall Delhi Fleet',
            ]
        );

        $rider = Rider::firstOrCreate(
            ['user_id' => $riderUser->id],
            [
                'hub_id'            => $hub->id,
                'franchise_id'      => $franchise->id,
                'vehicle_type'      => 'Bike',
                'vehicle_number'    => 'DL-01-AB-1234',
                'status'            => 'active',
                'is_active'         => true,
                'current_latitude'  => 28.5355,
                'current_longitude' => 77.2610,
            ]
        );

        // 7. Seed Sample Shipments for Seller & Hub Reporting
        $statuses = [
            'Delivered'         => 6,
            'In Transit'        => 3,
            'Out for Delivery'  => 2,
            'RTO Delivered'     => 1,
            'Pickup Pending'    => 2,
        ];

        $awbIndex = 1001;
        $now = Carbon::now();

        foreach ($statuses as $st => $count) {
            for ($i = 0; $i < $count; $i++) {
                $awb = 'OSC' . ($awbIndex++);
                $isCod = ($i % 2 === 0);
                $codAmt = $isCod ? (500 + ($i * 150)) : 0;
                $charge = 75.00 + ($i * 12);
                $createdAt = $now->copy()->subDays(rand(1, 20));

                Shipment::firstOrCreate(
                    ['awb_number' => $awb],
                    [
                        'user_id'          => $seller->id,
                        'franchise_id'     => $franchise->id,
                        'rider_id'         => $riderUser->id,
                        'courier_partner'  => 'Delhivery',
                        'order_id'         => 'ORD-' . $awbIndex,
                        'receiver_name'    => 'Customer ' . ($i + 1),
                        'receiver_phone'   => '98765432' . sprintf('%02d', $i),
                        'delivery_address' => 'House ' . ($i + 10) . ', Connaught Place',
                        'delivery_city'    => 'New Delhi',
                        'delivery_state'   => 'Delhi',
                        'delivery_pincode' => '110001',
                        'pickup_address'   => 'Plot 45, Okhla Phase 3',
                        'pickup_city'      => 'New Delhi',
                        'pickup_pincode'   => '110020',
                        'weight_kg'        => 0.5 + ($i * 0.2),
                        'is_cod'           => $isCod,
                        'cod_amount'       => $codAmt,
                        'invoice_value'    => $isCod ? $codAmt : 1200,
                        'total_amount'     => $charge,
                        'shipping_charge'  => $charge,
                        'product_name'     => 'Apparel Item #' . ($i + 1),
                        'status'           => $st,
                        'payment_type'     => $isCod ? 'Cash' : 'Prepaid',
                        'created_at'       => $createdAt,
                        'updated_at'       => $st === 'Delivered' ? $now : $createdAt,
                    ]
                );
            }
        }
    }
}
