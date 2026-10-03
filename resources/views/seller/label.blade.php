<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Label - {{ $shipment->awb_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; margin: 0; padding: 20px; background: #f3f4f6; display: flex; justify-content: center; }
        .label-container { width: 100%; max-width: 384px; min-height: 576px; background: #fff; border: 2px solid #000; padding: 15px; box-sizing: border-box; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); display: flex; flex-direction: column; }
        .header { border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { margin: 0; font-size: 20px; font-weight: bold; }
        .courier { font-size: 16px; font-weight: bold; }
        .barcode-area { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 10px; }
        .barcode-bars { height: 60px; background: repeating-linear-gradient(90deg, #000, #000 2px, #fff 2px, #fff 4px, #000 4px, #000 8px, #fff 8px, #fff 10px, #000 10px, #000 14px, #fff 14px, #fff 18px); margin-bottom: 10px; width: 100%; }
        .barcode-text { font-family: 'Courier New', Courier, monospace; font-size: 26px; font-weight: bold; letter-spacing: 2px; }
        .shipment-type { font-size: 12px; font-weight: bold; margin-top: 5px; }
        .addresses { display: flex; justify-content: space-between; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 10px; font-size: 12px; line-height: 1.4; gap: 15px; }
        .address-box { flex: 1; word-wrap: break-word; }
        .address-box strong { display: block; margin-bottom: 4px; font-size: 13px; text-transform: uppercase; }
        .details { font-size: 12px; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 10px; }
        .details table { width: 100%; text-align: left; border-collapse: collapse; }
        .details th { font-weight: bold; padding: 4px 0; vertical-align: top; white-space: nowrap; padding-right: 8px; }
        .details td { padding: 4px 0; vertical-align: top; }
        .cod-box { font-size: 22px; font-weight: bold; text-align: center; padding: 10px; border: 2px solid #000; margin-top: 15px; margin-bottom: 15px; border-radius: 4px; }
        .cod-box.prepaid { border-color: #4CAF50; color: #4CAF50; }
        .footer { text-align: center; font-size: 10px; font-weight: bold; line-height: 1.5; margin-top: auto; }
        
        @media print {
            body { padding: 0; background: #fff; display: block; }
            .label-container { border: none; width: 4in; height: 6in; max-width: 4in; min-height: 6in; margin: 0; padding: 0; box-shadow: none; }
            .barcode-bars { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .cod-box.prepaid { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
        @media (max-width: 400px) {
            body { padding: 10px; }
            .header h1 { font-size: 18px; }
            .barcode-text { font-size: 22px; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="label-container">
        <div class="header">
            <h1>ONESTALL CARGO</h1>
            <div class="courier">{{ strtoupper($shipment->courier_partner) }}</div>
        </div>
        
        <div class="barcode-area">
            <div class="barcode-bars"></div>
            <div class="barcode-text">{{ $shipment->awb_number }}</div>
            <div class="shipment-type">{{ strtoupper($shipment->shipment_type) }}</div>
        </div>
        
        <div class="addresses">
            <div class="address-box">
                <strong>TO:</strong>
                {{ $shipment->receiver_name }}<br>
                @if(!empty($settings['enable_consignee_address'])){{ $shipment->delivery_address }}<br>@else<i>[Address Hidden]</i><br>@endif
                {{ $shipment->delivery_city }} - {{ $shipment->delivery_pincode }}<br>
                {{ strtoupper($shipment->destination_country) }}<br>
                Ph: {{ !empty($settings['enable_consignee_contact']) ? $shipment->receiver_phone : substr($shipment->receiver_phone, 0, 4) . '******' }}
            </div>
            <div class="address-box">
                <strong>FROM:</strong>
                {{ $shipment->user->company_name ?? $shipment->user->name }}<br>
                Seller Hub<br>
                Ph: {{ $shipment->user->phone ?? 'N/A' }}
            </div>
        </div>
        
        <div class="details">
            <table>
                <tr>
                    <th>Date:</th><td>{{ $shipment->created_at->format('d/m/Y') }}</td>
                    <th>Weight:</th><td>{{ $shipment->weight_kg }} KG</td>
                </tr>
                <tr>
                    <th>Dimensions:</th><td>{{ $shipment->length_cm ?? 0 }}x{{ $shipment->width_cm ?? 0 }}x{{ $shipment->height_cm ?? 0 }} cm</td>
                    <th>Payment:</th><td>{{ $shipment->is_cod ? 'COD' : 'PREPAID' }}</td>
                </tr>
                @if($shipment->shipment_type == 'International')
                <tr>
                    <th>Customs Val:</th><td>${{ $shipment->customs_value }}</td>
                    <th>HS Code:</th><td>{{ $shipment->hs_code }}</td>
                </tr>
                @endif
                @if($shipment->shipment_type == 'B2B')
                <tr>
                    <th>Vehicle:</th><td colspan="3">{{ $shipment->vehicle_type }}</td>
                </tr>
                @endif
            </table>
        </div>
        
        @if($shipment->is_cod)
        <div class="cod-box">
            COD TO COLLECT: ₹{{ number_format($shipment->total_amount, 2) }}
        </div>
        @else
        <div class="cod-box prepaid">
            PREPAID
        </div>
        @endif
        
        <div class="footer">
            @if(!empty($settings['enable_rto_address']))<p>Return Address: If undelivered, return to Origin Hub.</p>@endif 
            @if(!empty($settings['enable_support_contact']) || !empty($settings['enable_support_email']))
            <p>
                @if(!empty($settings['enable_support_contact'])) Support: {{ $shipment->user->support_phone ?? '1800-XXX-XXXX' }} @endif 
                @if(!empty($settings['enable_support_contact']) && !empty($settings['enable_support_email'])) | @endif
                @if(!empty($settings['enable_support_email'])) Email: support@onestallcargo.com @endif
            </p>
            @endif
        </div>
    </div>
</body>
</html>
