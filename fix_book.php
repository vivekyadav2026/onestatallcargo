<?php
$file = 'resources/views/seller/book.blade.php';
$content = file_get_contents($file);

// Replace title & header
$content = str_replace(
    "@section('title', 'Add Order - OneStall Cargo')",
    "@section('title', (\$mode ?? 'create') === 'edit' ? 'Edit Order - OneStall Cargo' : ((\$mode ?? 'create') === 'clone' ? 'Clone Order - OneStall Cargo' : 'Add Order - OneStall Cargo'))",
    $content
);

$content = str_replace(
    '<h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Add Order</h1>',
    '<h1 class="text-xl font-extrabold text-gray-900 tracking-tight">{{ ($mode ?? "create") === "edit" ? "Edit Order #".($shipment->awb_number ?? "") : (($mode ?? "create") === "clone" ? "Clone Order (Copy of #".($shipment->awb_number ?? "").")" : "Add Order") }}</h1>',
    $content
);

// Add hidden inputs for mode and shipment_id
$hiddenInputs = <<<'EOD'
        @csrf
        <input type="hidden" name="mode" value="{{ $mode ?? 'create' }}">
        <input type="hidden" name="shipment_id" value="{{ ($mode ?? '') === 'edit' ? ($shipment->id ?? '') : '' }}">
EOD;

$content = str_replace('@csrf', $hiddenInputs, $content);

// Pre-fill inputs
$content = str_replace(
    'name="receiver_phone" required placeholder="Enter mobile number"',
    'name="receiver_phone" value="{{ old(\'receiver_phone\', $shipment->receiver_phone ?? \'\') }}" required placeholder="Enter mobile number"',
    $content
);

$content = str_replace(
    'name="receiver_name" required placeholder="Enter full name"',
    'name="receiver_name" value="{{ old(\'receiver_name\', $shipment->receiver_name ?? \'\') }}" required placeholder="Enter full name"',
    $content
);

$content = str_replace(
    'name="delivery_address" required placeholder="Enter buyer\'s full address"',
    'name="delivery_address" value="{{ old(\'delivery_address\', $shipment->delivery_address ?? \'\') }}" required placeholder="Enter buyer\'s full address"',
    $content
);

$content = str_replace(
    'name="delivery_pincode" required placeholder="Enter pincode"',
    'name="delivery_pincode" value="{{ old(\'delivery_pincode\', $shipment->delivery_pincode ?? \'\') }}" required placeholder="Enter pincode"',
    $content
);

$content = str_replace(
    'name="delivery_city" required placeholder="City"',
    'name="delivery_city" value="{{ old(\'delivery_city\', $shipment->delivery_city ?? \'\') }}" required placeholder="City"',
    $content
);

// Pre-fill Alpine bookingForm defaults
$oldBookingForm = <<<'EOD'
function bookingForm() {
    return {
        shipmentType: 'B2C',
        isCod: '0',
        isDangerous: false,
        showOtherCharges: false,
        showGuidelines: false,
        showOtherDetails: false,
        shipNow: 0,
        shippingCharge: 0,
        extraFee: 0,
        discountAmount: 0,
        deadWeight: 0.5,
        lengthCm: 10,
        widthCm: 10,
        heightCm: 10,
        products: [
            { name: '', price: 0, qty: 1, sku: '', hsn: '' }
        ],
EOD;

$newBookingForm = <<<'EOD'
function bookingForm() {
    return {
        shipmentType: '{{ old('shipment_type', $shipment->shipment_type ?? 'B2C') }}',
        isCod: '{{ old('is_cod', ($shipment->is_cod ?? false) ? '1' : '0') }}',
        isDangerous: false,
        showOtherCharges: false,
        showGuidelines: false,
        showOtherDetails: false,
        shipNow: 0,
        shippingCharge: 0,
        extraFee: 0,
        discountAmount: 0,
        deadWeight: {{ old('weight_kg', $shipment->weight_kg ?? 0.5) }},
        lengthCm: 10,
        widthCm: 10,
        heightCm: 10,
        products: [
            { 
                name: '{{ old('product_name', isset($shipment) ? 'Package Item for ' . $shipment->receiver_name : '') }}', 
                price: {{ old('invoice_value', $shipment->invoice_value ?? 0) }}, 
                qty: 1, 
                sku: '{{ $shipment->awb_number ?? "" }}', 
                hsn: '' 
            }
        ],
EOD;

$content = str_replace($oldBookingForm, $newBookingForm, $content);

file_put_contents($file, $content);
echo "Done";
?>
