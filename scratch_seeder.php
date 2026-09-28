<?php
require __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ApiDoc;

ApiDoc::updateOrCreate(
    ["endpoint_key" => "create_shipment_new"],
    [
        "title" => "Create Shipment (New)",
        "method" => "POST",
        "path" => "/v1/shipments",
        "description" => "Create a new shipment directly from seller balance.",
        "curl_example" => "curl -X POST https://api.onestallcargo.com/v1/shipments \\\n  -H 'Authorization: Bearer YOUR_API_TOKEN' \\\n  -H 'Content-Type: application/json' \\\n  -d '{\"pickup_pincode\": \"110001\", \"weight_kg\": 1.2, \"is_cod\": true}'",
        "json_response" => "{\n  \"success\": true,\n  \"message\": \"Shipment created successfully\",\n  \"awb_number\": \"OSCABC123\"\n}",
        "is_active" => true,
        "sort_order" => 5
    ]
);

ApiDoc::updateOrCreate(
    ["endpoint_key" => "cancel_shipment"],
    [
        "title" => "Cancel Shipment",
        "method" => "POST",
        "path" => "/v1/shipments/{awb}/cancel",
        "description" => "Cancel an unmanifested shipment and refund wallet.",
        "curl_example" => "curl -X POST https://api.onestallcargo.com/v1/shipments/OSCABC123/cancel \\\n  -H 'Authorization: Bearer YOUR_API_TOKEN'",
        "json_response" => "{\n  \"success\": true,\n  \"message\": \"Shipment cancelled and amount refunded.\"\n}",
        "is_active" => true,
        "sort_order" => 6
    ]
);

ApiDoc::updateOrCreate(
    ["endpoint_key" => "list_shipments"],
    [
        "title" => "List Shipments",
        "method" => "GET",
        "path" => "/v1/shipments",
        "description" => "Retrieve recent shipments for the authenticated seller.",
        "curl_example" => "curl -X GET https://api.onestallcargo.com/v1/shipments \\\n  -H 'Authorization: Bearer YOUR_API_TOKEN'",
        "json_response" => "{\n  \"success\": true,\n  \"data\": {\n    \"data\": [\n      { \"awb_number\": \"OSCABC123\", \"status\": \"Pending\" }\n    ]\n  }\n}",
        "is_active" => true,
        "sort_order" => 7
    ]
);

echo "API Docs seeded successfully!\n";
