<?php
$content = file_get_contents('app/Http/Controllers/Api/V1/ExternalCourierController.php');
$content = str_replace(
    'use App\Services\PricingService;',
    "use App\Services\PricingService;\nuse App\Services\ServiceabilityService;",
    $content
);
$content = preg_replace(
    '/protected \$pricingService;\s+public function __construct\(ShipmentService \$shipmentService, PricingService \$pricingService\)\s+\{/',
    "protected \$pricingService;\n    protected \$serviceabilityService;\n\n    public function __construct(ShipmentService \$shipmentService, PricingService \$pricingService, ServiceabilityService \$serviceabilityService)\n    {",
    $content
);
$content = preg_replace(
    '/\$this->pricingService = \$pricingService;/',
    "\$this->pricingService = \$pricingService;\n        \$this->serviceabilityService = \$serviceabilityService;",
    $content
);
$content = preg_replace(
    '/\$this->shipmentService->getFranchiseForPincode\(\$request->pincode\)/',
    "\$this->serviceabilityService->getFranchiseForPincode(\$request->pincode)",
    $content
);
file_put_contents('app/Http/Controllers/Api/V1/ExternalCourierController.php', $content);
echo "Done";
