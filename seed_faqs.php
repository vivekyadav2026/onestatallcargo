<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$faqs = [
    [
        'category' => 'general',
        'q' => 'How do I get started with OneStall Cargo?',
        'a' => 'Getting started takes less than 2 minutes! Create a free account on our registration page, recharge your shipping wallet with any amount, and connect your store (Shopify/WooCommerce) or create your first manual order immediately.'
    ],
    [
        'category' => 'general',
        'q' => 'Are there any monthly subscription fees or hidden setup charges?',
        'a' => 'No! OneStall Cargo operates on a 100% pay-as-you-go model. There are zero subscription fees, zero store integration fees, and zero hidden fuel surcharges. You only pay for the shipments you actually book.'
    ],
    [
        'category' => 'shipping',
        'q' => 'Which courier partners are available on OneStall Cargo?',
        'a' => 'We provide single-dashboard access to 15+ top courier partners in India, including Delhivery, BlueDart, XpressBees, Ecom Express, Shadowfax, DTDC, and Aramex for international shipments.'
    ],
    [
        'category' => 'shipping',
        'q' => 'How does automated courier allocation work?',
        'a' => 'Our recommendation engine automatically compares real-time delivery performance SLAs, cost efficiency, and pin code serviceability to recommend or automatically assign the best courier partner for every order.'
    ],
    [
        'category' => 'cod',
        'q' => 'How fast is Cash on Delivery (COD) remittance credited?',
        'a' => 'We offer early COD remittance in just 1-2 business days directly into your bank account, ensuring your business never suffers from frozen working capital.'
    ],
    [
        'category' => 'cod',
        'q' => 'What are the COD collection charges?',
        'a' => 'COD collection charges are flat 1.5% of the invoice value or Rs30 (whichever is higher) per delivered Cash on Delivery package.'
    ],
    [
        'category' => 'ndr',
        'q' => 'How does automated NDR management reduce RTO losses?',
        'a' => 'When a delivery fails, our automated NDR engine triggers outbound IVR phone calls, SMS, and interactive WhatsApp messages to the buyer to collect corrected addresses or delivery slot requests, converting up to 40% of undelivered packages into successful sales.'
    ],
    [
        'category' => 'ndr',
        'q' => 'How do I submit weight discrepancy video evidence?',
        'a' => 'If a courier overcharges shipment weight, click Flag Weight Dispute in your dashboard and attach your packing video or photo proof. Our team verifies the evidence and settles 98% of claims in your favor.'
    ],
    [
        'category' => 'integrations',
        'q' => 'How do I integrate Shopify or WooCommerce with OneStall?',
        'a' => 'Install the OneStall Cargo plugin from your store admin, paste your API token, and click Connect. Orders will sync automatically and AWB tracking numbers will be written back to your store admin panel.'
    ],
    [
        'category' => 'integrations',
        'q' => 'Do you offer REST APIs for custom websites or mobile apps?',
        'a' => 'Yes! We provide robust RESTful JSON APIs with complete Postman collections and webhooks for rate calculation, AWB generation, live tracking, and NDR actions.'
    ]
];

App\Models\Faq::truncate();

foreach ($faqs as $index => $faq) {
    App\Models\Faq::create([
        'question' => $faq['q'],
        'answer' => $faq['a'],
        'is_active' => true,
        'sort_order' => $index
    ]);
}
echo "Seeded ".count($faqs)." FAQs.\n";
