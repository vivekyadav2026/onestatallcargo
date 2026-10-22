<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApiDoc;

class ApiDocSeeder extends Seeder
{
    public function run(): void
    {
        $docs = [
            [
                'endpoint_key' => 'auth_user',
                'title' => 'Verify Authentication & Profile',
                'method' => 'GET',
                'path' => '/v1/user',
                'description' => 'Test your API Bearer token, verify your authenticated seller account, check wallet balance, KYC verification status, and registered merchant details.',
                'curl_example' => "curl -X GET \"https://api.onestallcargo.com/api/v1/user\" \\\n  -H \"Authorization: Bearer YOUR_API_TOKEN\" \\\n  -H \"Accept: application/json\"",
                'json_response' => "{\n  \"id\": 142,\n  \"name\": \"Acme Retailers Pvt Ltd\",\n  \"email\": \"api@acme.com\",\n  \"role\": \"seller\",\n  \"status\": \"active\",\n  \"wallet_balance\": \"8450.00\",\n  \"kyc_status\": \"approved\",\n  \"brand_name\": \"AcmeStore\",\n  \"company_city\": \"Mumbai\",\n  \"company_state\": \"Maharashtra\",\n  \"company_pincode\": \"400001\"\n}",
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'endpoint_key' => 'calculate_rates',
                'title' => 'Courier Rates & Delivery ETA',
                'method' => 'POST',
                'path' => '/v1/rates',
                'description' => 'Calculate and compare real-time shipping freight charges, COD handling fees, and delivery ETAs across courier partners (Delhivery, Blue Dart, Shadowfax, Xpressbees) based on weight, dimensions, and COD invoice value.',
                'curl_example' => "curl -X POST \"https://api.onestallcargo.com/api/v1/rates\" \\\n  -H \"Authorization: Bearer YOUR_API_TOKEN\" \\\n  -H \"Content-Type: application/json\" \\\n  -d '{\n    \"pickup_pincode\": \"110020\",\n    \"delivery_pincode\": \"400001\",\n    \"weight_kg\": 0.5,\n    \"length_cm\": 15,\n    \"width_cm\": 10,\n    \"height_cm\": 5,\n    \"is_cod\": true,\n    \"invoice_value\": 1499.00\n  }'",
                'json_response' => "{\n  \"success\": true,\n  \"data\": [\n    {\n      \"courier_name\": \"Delhivery Surface\",\n      \"fulfillment_type\": \"surface\",\n      \"freight_charge\": 45.00,\n      \"cod_charge\": 35.00,\n      \"gst_amount\": 14.40,\n      \"total_amount\": 94.40,\n      \"estimated_days\": \"2-3 Days\",\n      \"serviceable\": true\n    },\n    {\n      \"courier_name\": \"Blue Dart Air Express\",\n      \"fulfillment_type\": \"express\",\n      \"freight_charge\": 75.00,\n      \"cod_charge\": 35.00,\n      \"gst_amount\": 19.80,\n      \"total_amount\": 129.80,\n      \"estimated_days\": \"1-2 Days\",\n      \"serviceable\": true\n    }\n  ]\n}",
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'endpoint_key' => 'create_shipment',
                'title' => 'Create & Book Shipment (Generate AWB)',
                'method' => 'POST',
                'path' => '/v1/shipments',
                'description' => 'Book a new shipment order. Validates account KYC, calculates exact courier rate, deducts shipping charge from merchant wallet, assigns nearest courier hub routing, and generates an official AWB tracking number.',
                'curl_example' => "curl -X POST \"https://api.onestallcargo.com/api/v1/shipments\" \\\n  -H \"Authorization: Bearer YOUR_API_TOKEN\" \\\n  -H \"Content-Type: application/json\" \\\n  -d '{\n    \"pickup_pincode\": \"110020\",\n    \"shipment_type\": \"Forward\",\n    \"receiver_name\": \"Rahul Sharma\",\n    \"receiver_phone\": \"9876543210\",\n    \"delivery_address\": \"Flat 402, Sunshine Towers, MG Road\",\n    \"delivery_city\": \"Mumbai\",\n    \"delivery_pincode\": \"400001\",\n    \"weight_kg\": 0.75,\n    \"length_cm\": 20,\n    \"width_cm\": 15,\n    \"height_cm\": 10,\n    \"is_cod\": true,\n    \"invoice_value\": 1299.00,\n    \"product_name\": \"Wireless Bluetooth Earbuds\",\n    \"product_sku\": \"SKU-EARBUD-01\",\n    \"product_qty\": 1\n  }'",
                'json_response' => "{\n  \"success\": true,\n  \"message\": \"Shipment created successfully\",\n  \"awb_number\": \"OSC98421376\",\n  \"shipping_charge\": 88.50,\n  \"data\": {\n    \"id\": 5892,\n    \"awb_number\": \"OSC98421376\",\n    \"status\": \"Pending\",\n    \"receiver_name\": \"Rahul Sharma\",\n    \"delivery_city\": \"Mumbai\",\n    \"delivery_pincode\": \"400001\",\n    \"weight_kg\": 0.75,\n    \"is_cod\": true,\n    \"invoice_value\": 1299.00,\n    \"product_name\": \"Wireless Bluetooth Earbuds\",\n    \"created_at\": \"2026-10-22T10:30:00.000000Z\"\n  }\n}",
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'endpoint_key' => 'track_shipment',
                'title' => 'Track Shipment by AWB Number',
                'method' => 'GET',
                'path' => '/v1/shipments/{awb}',
                'description' => 'Retrieve real-time tracking status, courier transit milestones, hub scan logs, out-for-delivery status, and delivery confirmation by AWB number.',
                'curl_example' => "curl -X GET \"https://api.onestallcargo.com/api/v1/shipments/OSC98421376\" \\\n  -H \"Authorization: Bearer YOUR_API_TOKEN\" \\\n  -H \"Accept: application/json\"",
                'json_response' => "{\n  \"success\": true,\n  \"data\": {\n    \"awb_number\": \"OSC98421376\",\n    \"status\": \"In Transit\",\n    \"receiver_name\": \"Rahul Sharma\",\n    \"delivery_city\": \"Mumbai\",\n    \"courier_partner\": \"Delhivery Express\",\n    \"current_location\": \"Bhiwandi Sorting Hub, Mumbai\",\n    \"is_cod\": true,\n    \"invoice_value\": 1299.00,\n    \"tracking_history\": [\n      {\n        \"status\": \"In Transit\",\n        \"location\": \"Bhiwandi Central Hub\",\n        \"timestamp\": \"2026-10-22T08:15:00Z\"\n      },\n      {\n        \"status\": \"Pickup Completed\",\n        \"location\": \"Okhla Hub, Delhi\",\n        \"timestamp\": \"2026-10-21T18:30:00Z\"\n      },\n      {\n        \"status\": \"Manifested\",\n        \"location\": \"Delhi\",\n        \"timestamp\": \"2026-10-21T11:00:00Z\"\n      }\n    ]\n  }\n}",
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'endpoint_key' => 'list_shipments',
                'title' => 'List All Shipments (Paginated)',
                'method' => 'GET',
                'path' => '/v1/shipments',
                'description' => 'Retrieve a paginated list of all consignments created by your merchant account. Filter by status, payment mode, or creation date range.',
                'curl_example' => "curl -X GET \"https://api.onestallcargo.com/api/v1/shipments?page=1\" \\\n  -H \"Authorization: Bearer YOUR_API_TOKEN\" \\\n  -H \"Accept: application/json\"",
                'json_response' => "{\n  \"success\": true,\n  \"data\": {\n    \"current_page\": 1,\n    \"data\": [\n      {\n        \"id\": 5892,\n        \"awb_number\": \"OSC98421376\",\n        \"receiver_name\": \"Rahul Sharma\",\n        \"delivery_city\": \"Mumbai\",\n        \"status\": \"In Transit\",\n        \"total_amount\": 88.50,\n        \"created_at\": \"2026-10-22T10:30:00Z\"\n      }\n    ],\n    \"total\": 145,\n    \"per_page\": 20,\n    \"last_page\": 8\n  }\n}",
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'endpoint_key' => 'cancel_shipment',
                'title' => 'Cancel Shipment & Auto Refund',
                'method' => 'POST',
                'path' => '/v1/shipments/{awb}/cancel',
                'description' => 'Cancel an unpicked or manifested shipment. The total booking fee deducted during creation is instantly credited back into your merchant wallet.',
                'curl_example' => "curl -X POST \"https://api.onestallcargo.com/api/v1/shipments/OSC98421376/cancel\" \\\n  -H \"Authorization: Bearer YOUR_API_TOKEN\" \\\n  -H \"Accept: application/json\"",
                'json_response' => "{\n  \"success\": true,\n  \"message\": \"Shipment cancelled and amount refunded.\",\n  \"awb_number\": \"OSC98421376\",\n  \"refunded_amount\": 88.50,\n  \"status\": \"Cancelled\"\n}",
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'endpoint_key' => 'serviceability',
                'title' => 'Pincode Serviceability & Reach',
                'method' => 'POST',
                'path' => '/v1/external/serviceability',
                'description' => 'Check whether a 6-digit Indian postal pincode is covered by OneStall Cargo nationwide fulfillment network and whether Cash on Delivery (COD) is available.',
                'curl_example' => "curl -X POST \"https://api.onestallcargo.com/api/v1/external/serviceability\" \\\n  -H \"Authorization: Bearer YOUR_API_TOKEN\" \\\n  -H \"Content-Type: application/json\" \\\n  -d '{\n    \"pincode\": \"400001\"\n  }'",
                'json_response' => "{\n  \"status\": \"success\",\n  \"is_serviceable\": true,\n  \"pincode\": \"400001\",\n  \"city\": \"Mumbai\",\n  \"state\": \"Maharashtra\",\n  \"cod_available\": true,\n  \"prepaid_available\": true,\n  \"franchise_area\": true\n}",
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'endpoint_key' => 'store_webhook',
                'title' => 'E-Commerce Store Order Webhook',
                'method' => 'POST',
                'path' => '/v1/integrations/{platform}/webhook/{user_id}',
                'description' => 'Receive automated incoming order creation webhooks from Shopify, WooCommerce, or custom storefronts to instantly generate shipments and manifest labels.',
                'curl_example' => "curl -X POST \"https://api.onestallcargo.com/api/v1/integrations/shopify/webhook/142\" \\\n  -H \"Content-Type: application/json\" \\\n  -H \"X-Shopify-Topic: orders/create\" \\\n  -d '{\n    \"id\": 1092837465,\n    \"order_number\": 1054,\n    \"financial_status\": \"paid\",\n    \"shipping_address\": {\n      \"first_name\": \"Aman\",\n      \"last_name\": \"Verma\",\n      \"address1\": \"House 12, Sector 14\",\n      \"city\": \"Gurugram\",\n      \"province\": \"Haryana\",\n      \"zip\": \"122001\",\n      \"phone\": \"9811223344\"\n    },\n    \"total_price\": \"899.00\"\n  }'",
                'json_response' => "{\n  \"success\": true,\n  \"message\": \"Webhook processed successfully\",\n  \"order_id\": 1054,\n  \"awb_number\": \"OSC88392104\",\n  \"status\": \"Manifested\"\n}",
                'sort_order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($docs as $doc) {
            ApiDoc::updateOrCreate(
                ['endpoint_key' => $doc['endpoint_key']],
                $doc
            );
        }
    }
}
