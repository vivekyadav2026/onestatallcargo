<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

App\Models\ApiDoc::truncate();

$docs = [
    [
        'endpoint_key' => 'serviceability',
        'title' => 'Check Pincode Serviceability',
        'method' => 'POST',
        'path' => '/v1/external/serviceability',
        'description' => 'Check if OneStall Cargo or its franchise network can deliver to a specific pincode.',
        'curl_example' => "curl -X POST https://api.onestallcargo.com/api/v1/external/serviceability \\\n  -H 'Authorization: Bearer OSC_YOUR_KEY' \\\n  -H 'Content-Type: application/json' \\\n  -H 'Accept: application/json' \\\n  -d '{\n    \"pincode\": \"110001\"\n  }'",
        'json_response' => "{\n  \"status\": \"success\",\n  \"is_serviceable\": true,\n  \"franchise_area\": false,\n  \"city\": \"Serviceable City\",\n  \"state\": \"Serviceable State\"\n}",
        'sort_order' => 1
    ],
    [
        'endpoint_key' => 'rate_calculator',
        'title' => 'Calculate Shipping Rates',
        'method' => 'POST',
        'path' => '/v1/external/rate',
        'description' => 'Get real-time dynamic shipping rates based on pickup, delivery pincodes, and physical weight.',
        'curl_example' => "curl -X POST https://api.onestallcargo.com/api/v1/external/rate \\\n  -H 'Authorization: Bearer OSC_YOUR_KEY' \\\n  -H 'Content-Type: application/json' \\\n  -H 'Accept: application/json' \\\n  -d '{\n    \"pickup_pincode\": \"110001\",\n    \"delivery_pincode\": \"400001\",\n    \"weight_kg\": 1.5\n  }'",
        'json_response' => "{\n  \"status\": \"success\",\n  \"data\": {\n    \"courier_name\": \"Onestall Cargo\",\n    \"base_rate\": 125.0,\n    \"estimated_delivery_days\": 3\n  }\n}",
        'sort_order' => 2
    ],
    [
        'endpoint_key' => 'create_shipment',
        'title' => 'Create Order & Generate AWB',
        'method' => 'POST',
        'path' => '/v1/external/shipment',
        'description' => 'Create a new shipment booking and generate an AWB tracking number across partner courier networks.',
        'curl_example' => "curl -X POST https://api.onestallcargo.com/api/v1/external/shipment \\\n  -H 'Authorization: Bearer OSC_YOUR_KEY' \\\n  -H 'Content-Type: application/json' \\\n  -H 'Accept: application/json' \\\n  -d '{\n    \"order_id\": \"ORD-99321\",\n    \"pickup_pincode\": \"110001\",\n    \"delivery_pincode\": \"400001\",\n    \"receiver_name\": \"John Doe\",\n    \"receiver_phone\": \"9876543210\",\n    \"delivery_address\": \"123 Tech Street, Navi Mumbai\",\n    \"weight_kg\": 1.5,\n    \"is_cod\": true,\n    \"invoice_value\": 1499.00\n  }'",
        'json_response' => "{\n  \"status\": \"success\",\n  \"message\": \"Shipment created successfully via Onestall Cargo API\",\n  \"data\": {\n    \"order_id\": \"ORD-99321\",\n    \"awb_number\": \"OSCABC123XYZ\",\n    \"label_url\": \"https://api.onestallcargo.com/api/v1/external/label/OSCABC123XYZ\",\n    \"routing_code\": \"OSC-EXTERNAL\"\n  }\n}",
        'sort_order' => 3
    ],
    [
        'endpoint_key' => 'track_shipment',
        'title' => 'Track Shipment',
        'method' => 'GET',
        'path' => '/v1/external/track/{awb}',
        'description' => 'Fetch real-time tracking scans and current status for a specific AWB number.',
        'curl_example' => "curl -X GET https://api.onestallcargo.com/api/v1/external/track/OSCABC123XYZ \\\n  -H 'Authorization: Bearer OSC_YOUR_KEY' \\\n  -H 'Accept: application/json'",
        'json_response' => "{\n  \"status\": \"success\",\n  \"data\": {\n    \"awb_number\": \"OSCABC123XYZ\",\n    \"current_status\": \"In Transit\",\n    \"scans\": [\n      {\n        \"status\": \"In Transit\",\n        \"location\": \"Mumbai Hub\",\n        \"timestamp\": \"2026-09-28T10:00:00Z\"\n      }\n    ]\n  }\n}",
        'sort_order' => 4
    ]
];

foreach ($docs as $doc) {
    App\Models\ApiDoc::create($doc);
}
echo "Seeded ApiDocs.\n";
