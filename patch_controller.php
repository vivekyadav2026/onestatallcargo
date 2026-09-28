<?php
$file = 'app/Http/Controllers/SellerShipmentController.php';
$content = file_get_contents($file);

// Replace the entire store method with regex
$pattern = '/public function store\(Request \\).*?public function bulkCreate/s';

$replacement = <<<EOD
public function store(Request \, \App\Services\ShipmentService \)
    {
        \ = \->validate([
            'shipment_id' => 'nullable|integer',
            'mode' => 'nullable|string',
            'ship_now' => 'nullable',
            'shipment_type' => 'required|string',
            'receiver_name' => 'required|string',
            'receiver_phone' => 'required|string',
            'delivery_address' => 'required|string',
            'delivery_city' => 'required|string',
            'delivery_pincode' => 'required|string',
            'pickup_pincode' => 'nullable|string',
            'destination_country' => 'nullable|string',
            'customs_value' => 'nullable|numeric',
            'hs_code' => 'nullable|string',
            'vehicle_type' => 'nullable|string',
            'weight_kg' => 'required|numeric',
            'is_cod' => 'nullable|boolean',
            'invoice_value' => 'nullable|numeric',
        ]);

        \ = \Illuminate\Support\Facades\Auth::user();

        // KYC Check
        \ = \App\Models\Kyc::where('user_id', \->id)->first();
        if (!\ || \->status !== 'approved') {
            return redirect()->route('seller.settings', ['view' => 'kyc'])
                ->with('error', 'KYC Verification Required! Please complete and get your KYC approved before shipping orders.');
        }
        
        \ = (\['mode'] ?? '') === 'edit' && !empty(\['shipment_id']);

        if (\) {
            \ = \App\Models\Shipment::where('user_id', \->id)->findOrFail(\['shipment_id']);
        } else {
            // New Shipment logic
            \['pickup_pincode'] = \['pickup_pincode'] ?? \->company_pincode ?? '000000';
            
            // Product details handling
            \ = \->input('product_name', []);
            \ = \->input('product_sku', []);
            \ = \->input('product_qty', []);
            \ = \->input('product_price', []);

            \ = 'General Parcel';
            \ = 'N/A';
            \ = 1;
            \ = [];
            
            if (!empty(\) && is_array(\)) {
                \ = array_filter(\);
                \ = !empty(\) ? implode(', ', \) : 'General Parcel';
                \ = implode(', ', array_filter(\)) ?: 'N/A';
                \ = array_sum(array_map('intval', \)) ?: 1;

                for (\ = 0; \ < count(\); \++) {
                    if (!empty(\[\])) {
                        \[] = [
                            'name' => \[\],
                            'price' => \[\] ?? 0,
                            'qty' => \[\] ?? 1,
                            'sku' => \[\] ?? '',
                        ];
                    }
                }
            }

            \['product_name'] = \;
            \['product_sku'] = \;
            \['product_qty'] = \;
            \['product_details'] = json_encode(\);

            try {
                // Rate Calc
                \ = app(\App\Services\PricingService::class)->getAvailableRates(\['pickup_pincode'], \['delivery_pincode'], \['weight_kg']);
                \ = \[0]['final_rate'] ?? 50; 
                
                \ = \['is_cod'] ?? false;
                if (\->wallet_balance < \ && !\) {
                    return back()->with('error', 'Insufficient wallet balance.');
                }

                \['shipping_charge'] = \;
                \['total_amount'] = \;
                \['order_id'] = 'ORD' . time();

                if (!\) {
                    \->wallet_balance -= \;
                    \->save();
                }

                \ = \->createShipment(\, \->id);

            } catch (\Exception \) {
                return back()->with('error', 'Failed to create shipment: ' . \->getMessage());
            }
        }

        \ = !empty(\['ship_now']) && \['ship_now'] == '1';

        if (\) {
            // Update logic for edit
            \->update(\);
            if (\) {
                return redirect()->route('seller.label', \->awb_number)->with('success', 'Order updated and label generated.');
            }
            return redirect()->route('seller.shipments.index')->with('success', 'Order updated successfully.');
        }

        return redirect()->route('seller.shipments.index')->with('success', 'Order created successfully.');
    }

    public function bulkCreate
EOD;

$newContent = preg_replace($pattern, $replacement, $content, 1);
file_put_contents($file, $newContent);
echo "Patched SellerShipmentController\n";
