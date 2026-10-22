-- ============================================================
-- ONESTALL CARGO: API DOCS TABLE & SEED DATA
-- Run this in phpMyAdmin or MySQL CLI on your live server
-- ============================================================

-- 1. Ensure table exists:
CREATE TABLE IF NOT EXISTS `api_docs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `endpoint_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `curl_example` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `json_response` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_docs_endpoint_key_unique` (`endpoint_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Insert or Update all 8 API Documentation endpoints:
INSERT INTO `api_docs` (`endpoint_key`, `title`, `method`, `path`, `description`, `curl_example`, `json_response`, `sort_order`, `is_active`, `created_at`, `updated_at`)
VALUES ('auth_user', 'Verify Authentication & Profile', 'GET', '/v1/user', 'Test your API Bearer token, verify your authenticated seller account, check wallet balance, KYC verification status, and registered merchant details.', 'curl -X GET \"https://api.onestallcargo.com/api/v1/user\" \\
  -H \"Authorization: Bearer YOUR_API_TOKEN\" \\
  -H \"Accept: application/json\"', '{
  \"id\": 142,
  \"name\": \"Acme Retailers Pvt Ltd\",
  \"email\": \"api@acme.com\",
  \"role\": \"seller\",
  \"status\": \"active\",
  \"wallet_balance\": \"8450.00\",
  \"kyc_status\": \"approved\",
  \"brand_name\": \"AcmeStore\",
  \"company_city\": \"Mumbai\",
  \"company_state\": \"Maharashtra\",
  \"company_pincode\": \"400001\"
}', 1, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE
  `title` = VALUES(`title`),
  `method` = VALUES(`method`),
  `path` = VALUES(`path`),
  `description` = VALUES(`description`),
  `curl_example` = VALUES(`curl_example`),
  `json_response` = VALUES(`json_response`),
  `sort_order` = VALUES(`sort_order`),
  `is_active` = VALUES(`is_active`),
  `updated_at` = NOW();

INSERT INTO `api_docs` (`endpoint_key`, `title`, `method`, `path`, `description`, `curl_example`, `json_response`, `sort_order`, `is_active`, `created_at`, `updated_at`)
VALUES ('calculate_rates', 'Courier Rates & Delivery ETA', 'POST', '/v1/rates', 'Calculate and compare real-time shipping freight charges, COD handling fees, and delivery ETAs across courier partners (Delhivery, Blue Dart, Shadowfax, Xpressbees) based on weight, dimensions, and COD invoice value.', 'curl -X POST \"https://api.onestallcargo.com/api/v1/rates\" \\
  -H \"Authorization: Bearer YOUR_API_TOKEN\" \\
  -H \"Content-Type: application/json\" \\
  -d \'{
    \"pickup_pincode\": \"110020\",
    \"delivery_pincode\": \"400001\",
    \"weight_kg\": 0.5,
    \"length_cm\": 15,
    \"width_cm\": 10,
    \"height_cm\": 5,
    \"is_cod\": true,
    \"invoice_value\": 1499.00
  }\'', '{
  \"success\": true,
  \"data\": [
    {
      \"courier_name\": \"Delhivery Surface\",
      \"fulfillment_type\": \"surface\",
      \"freight_charge\": 45.00,
      \"cod_charge\": 35.00,
      \"gst_amount\": 14.40,
      \"total_amount\": 94.40,
      \"estimated_days\": \"2-3 Days\",
      \"serviceable\": true
    },
    {
      \"courier_name\": \"Blue Dart Air Express\",
      \"fulfillment_type\": \"express\",
      \"freight_charge\": 75.00,
      \"cod_charge\": 35.00,
      \"gst_amount\": 19.80,
      \"total_amount\": 129.80,
      \"estimated_days\": \"1-2 Days\",
      \"serviceable\": true
    }
  ]
}', 2, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE
  `title` = VALUES(`title`),
  `method` = VALUES(`method`),
  `path` = VALUES(`path`),
  `description` = VALUES(`description`),
  `curl_example` = VALUES(`curl_example`),
  `json_response` = VALUES(`json_response`),
  `sort_order` = VALUES(`sort_order`),
  `is_active` = VALUES(`is_active`),
  `updated_at` = NOW();

INSERT INTO `api_docs` (`endpoint_key`, `title`, `method`, `path`, `description`, `curl_example`, `json_response`, `sort_order`, `is_active`, `created_at`, `updated_at`)
VALUES ('create_shipment', 'Create & Book Shipment (Generate AWB)', 'POST', '/v1/shipments', 'Book a new shipment order. Validates account KYC, calculates exact courier rate, deducts shipping charge from merchant wallet, assigns nearest courier hub routing, and generates an official AWB tracking number.', 'curl -X POST \"https://api.onestallcargo.com/api/v1/shipments\" \\
  -H \"Authorization: Bearer YOUR_API_TOKEN\" \\
  -H \"Content-Type: application/json\" \\
  -d \'{
    \"pickup_pincode\": \"110020\",
    \"shipment_type\": \"Forward\",
    \"receiver_name\": \"Rahul Sharma\",
    \"receiver_phone\": \"9876543210\",
    \"delivery_address\": \"Flat 402, Sunshine Towers, MG Road\",
    \"delivery_city\": \"Mumbai\",
    \"delivery_pincode\": \"400001\",
    \"weight_kg\": 0.75,
    \"length_cm\": 20,
    \"width_cm\": 15,
    \"height_cm\": 10,
    \"is_cod\": true,
    \"invoice_value\": 1299.00,
    \"product_name\": \"Wireless Bluetooth Earbuds\",
    \"product_sku\": \"SKU-EARBUD-01\",
    \"product_qty\": 1
  }\'', '{
  \"success\": true,
  \"message\": \"Shipment created successfully\",
  \"awb_number\": \"OSC98421376\",
  \"shipping_charge\": 88.50,
  \"data\": {
    \"id\": 5892,
    \"awb_number\": \"OSC98421376\",
    \"status\": \"Pending\",
    \"receiver_name\": \"Rahul Sharma\",
    \"delivery_city\": \"Mumbai\",
    \"delivery_pincode\": \"400001\",
    \"weight_kg\": 0.75,
    \"is_cod\": true,
    \"invoice_value\": 1299.00,
    \"product_name\": \"Wireless Bluetooth Earbuds\",
    \"created_at\": \"2026-10-22T10:30:00.000000Z\"
  }
}', 3, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE
  `title` = VALUES(`title`),
  `method` = VALUES(`method`),
  `path` = VALUES(`path`),
  `description` = VALUES(`description`),
  `curl_example` = VALUES(`curl_example`),
  `json_response` = VALUES(`json_response`),
  `sort_order` = VALUES(`sort_order`),
  `is_active` = VALUES(`is_active`),
  `updated_at` = NOW();

INSERT INTO `api_docs` (`endpoint_key`, `title`, `method`, `path`, `description`, `curl_example`, `json_response`, `sort_order`, `is_active`, `created_at`, `updated_at`)
VALUES ('track_shipment', 'Track Shipment by AWB Number', 'GET', '/v1/shipments/{awb}', 'Retrieve real-time tracking status, courier transit milestones, hub scan logs, out-for-delivery status, and delivery confirmation by AWB number.', 'curl -X GET \"https://api.onestallcargo.com/api/v1/shipments/OSC98421376\" \\
  -H \"Authorization: Bearer YOUR_API_TOKEN\" \\
  -H \"Accept: application/json\"', '{
  \"success\": true,
  \"data\": {
    \"awb_number\": \"OSC98421376\",
    \"status\": \"In Transit\",
    \"receiver_name\": \"Rahul Sharma\",
    \"delivery_city\": \"Mumbai\",
    \"courier_partner\": \"Delhivery Express\",
    \"current_location\": \"Bhiwandi Sorting Hub, Mumbai\",
    \"is_cod\": true,
    \"invoice_value\": 1299.00,
    \"tracking_history\": [
      {
        \"status\": \"In Transit\",
        \"location\": \"Bhiwandi Central Hub\",
        \"timestamp\": \"2026-10-22T08:15:00Z\"
      },
      {
        \"status\": \"Pickup Completed\",
        \"location\": \"Okhla Hub, Delhi\",
        \"timestamp\": \"2026-10-21T18:30:00Z\"
      },
      {
        \"status\": \"Manifested\",
        \"location\": \"Delhi\",
        \"timestamp\": \"2026-10-21T11:00:00Z\"
      }
    ]
  }
}', 4, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE
  `title` = VALUES(`title`),
  `method` = VALUES(`method`),
  `path` = VALUES(`path`),
  `description` = VALUES(`description`),
  `curl_example` = VALUES(`curl_example`),
  `json_response` = VALUES(`json_response`),
  `sort_order` = VALUES(`sort_order`),
  `is_active` = VALUES(`is_active`),
  `updated_at` = NOW();

INSERT INTO `api_docs` (`endpoint_key`, `title`, `method`, `path`, `description`, `curl_example`, `json_response`, `sort_order`, `is_active`, `created_at`, `updated_at`)
VALUES ('list_shipments', 'List All Shipments (Paginated)', 'GET', '/v1/shipments', 'Retrieve a paginated list of all consignments created by your merchant account. Filter by status, payment mode, or creation date range.', 'curl -X GET \"https://api.onestallcargo.com/api/v1/shipments?page=1\" \\
  -H \"Authorization: Bearer YOUR_API_TOKEN\" \\
  -H \"Accept: application/json\"', '{
  \"success\": true,
  \"data\": {
    \"current_page\": 1,
    \"data\": [
      {
        \"id\": 5892,
        \"awb_number\": \"OSC98421376\",
        \"receiver_name\": \"Rahul Sharma\",
        \"delivery_city\": \"Mumbai\",
        \"status\": \"In Transit\",
        \"total_amount\": 88.50,
        \"created_at\": \"2026-10-22T10:30:00Z\"
      }
    ],
    \"total\": 145,
    \"per_page\": 20,
    \"last_page\": 8
  }
}', 5, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE
  `title` = VALUES(`title`),
  `method` = VALUES(`method`),
  `path` = VALUES(`path`),
  `description` = VALUES(`description`),
  `curl_example` = VALUES(`curl_example`),
  `json_response` = VALUES(`json_response`),
  `sort_order` = VALUES(`sort_order`),
  `is_active` = VALUES(`is_active`),
  `updated_at` = NOW();

INSERT INTO `api_docs` (`endpoint_key`, `title`, `method`, `path`, `description`, `curl_example`, `json_response`, `sort_order`, `is_active`, `created_at`, `updated_at`)
VALUES ('cancel_shipment', 'Cancel Shipment & Auto Refund', 'POST', '/v1/shipments/{awb}/cancel', 'Cancel an unpicked or manifested shipment. The total booking fee deducted during creation is instantly credited back into your merchant wallet.', 'curl -X POST \"https://api.onestallcargo.com/api/v1/shipments/OSC98421376/cancel\" \\
  -H \"Authorization: Bearer YOUR_API_TOKEN\" \\
  -H \"Accept: application/json\"', '{
  \"success\": true,
  \"message\": \"Shipment cancelled and amount refunded.\",
  \"awb_number\": \"OSC98421376\",
  \"refunded_amount\": 88.50,
  \"status\": \"Cancelled\"
}', 6, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE
  `title` = VALUES(`title`),
  `method` = VALUES(`method`),
  `path` = VALUES(`path`),
  `description` = VALUES(`description`),
  `curl_example` = VALUES(`curl_example`),
  `json_response` = VALUES(`json_response`),
  `sort_order` = VALUES(`sort_order`),
  `is_active` = VALUES(`is_active`),
  `updated_at` = NOW();

INSERT INTO `api_docs` (`endpoint_key`, `title`, `method`, `path`, `description`, `curl_example`, `json_response`, `sort_order`, `is_active`, `created_at`, `updated_at`)
VALUES ('serviceability', 'Pincode Serviceability & Reach', 'POST', '/v1/external/serviceability', 'Check whether a 6-digit Indian postal pincode is covered by OneStall Cargo nationwide fulfillment network and whether Cash on Delivery (COD) is available.', 'curl -X POST \"https://api.onestallcargo.com/api/v1/external/serviceability\" \\
  -H \"Authorization: Bearer YOUR_API_TOKEN\" \\
  -H \"Content-Type: application/json\" \\
  -d \'{
    \"pincode\": \"400001\"
  }\'', '{
  \"status\": \"success\",
  \"is_serviceable\": true,
  \"pincode\": \"400001\",
  \"city\": \"Mumbai\",
  \"state\": \"Maharashtra\",
  \"cod_available\": true,
  \"prepaid_available\": true,
  \"franchise_area\": true
}', 7, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE
  `title` = VALUES(`title`),
  `method` = VALUES(`method`),
  `path` = VALUES(`path`),
  `description` = VALUES(`description`),
  `curl_example` = VALUES(`curl_example`),
  `json_response` = VALUES(`json_response`),
  `sort_order` = VALUES(`sort_order`),
  `is_active` = VALUES(`is_active`),
  `updated_at` = NOW();

INSERT INTO `api_docs` (`endpoint_key`, `title`, `method`, `path`, `description`, `curl_example`, `json_response`, `sort_order`, `is_active`, `created_at`, `updated_at`)
VALUES ('store_webhook', 'E-Commerce Store Order Webhook', 'POST', '/v1/integrations/{platform}/webhook/{user_id}', 'Receive automated incoming order creation webhooks from Shopify, WooCommerce, or custom storefronts to instantly generate shipments and manifest labels.', 'curl -X POST \"https://api.onestallcargo.com/api/v1/integrations/shopify/webhook/142\" \\
  -H \"Content-Type: application/json\" \\
  -H \"X-Shopify-Topic: orders/create\" \\
  -d \'{
    \"id\": 1092837465,
    \"order_number\": 1054,
    \"financial_status\": \"paid\",
    \"shipping_address\": {
      \"first_name\": \"Aman\",
      \"last_name\": \"Verma\",
      \"address1\": \"House 12, Sector 14\",
      \"city\": \"Gurugram\",
      \"province\": \"Haryana\",
      \"zip\": \"122001\",
      \"phone\": \"9811223344\"
    },
    \"total_price\": \"899.00\"
  }\'', '{
  \"success\": true,
  \"message\": \"Webhook processed successfully\",
  \"order_id\": 1054,
  \"awb_number\": \"OSC88392104\",
  \"status\": \"Manifested\"
}', 8, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE
  `title` = VALUES(`title`),
  `method` = VALUES(`method`),
  `path` = VALUES(`path`),
  `description` = VALUES(`description`),
  `curl_example` = VALUES(`curl_example`),
  `json_response` = VALUES(`json_response`),
  `sort_order` = VALUES(`sort_order`),
  `is_active` = VALUES(`is_active`),
  `updated_at` = NOW();

