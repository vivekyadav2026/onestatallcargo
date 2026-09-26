<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shipping Label - {{ $shipment->awb_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; margin: 0; padding: 20px; background: #fff; }
        .label-container { width: 100%; max-width: 4in; height: 6in; border: 2px solid #000; margin: 0 auto; padding: 10px; box-sizing: border-box; }
        .header { border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 10px; display: flex; justify-content: space-between; }
        .header h1 { margin: 0; font-size: 24px; font-weight: bold; }
        .courier { font-size: 18px; font-weight: bold; }
        .barcode-area { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 10px; }
        .barcode { font-family: 'Courier New', Courier, monospace; font-size: 32px; font-weight: bold; letter-spacing: 2px; }
        .addresses { display: flex; justify-content: space-between; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 10px; font-size: 12px; }
        .address-box { width: 48%; }
        .address-box strong { display: block; margin-bottom: 3px; font-size: 14px; }
        .details { font-size: 12px; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 10px; }
        .details table { width: 100%; text-align: left; }
        .footer { text-align: center; font-size: 10px; font-weight: bold; }
        .cod-box { font-size: 24px; font-weight: bold; text-align: center; padding: 5px; border: 2px solid #000; margin-top: 10px; }
        
        @media print {
            body { padding: 0; }
            .label-container { border: none; width: 4in; height: 6in; margin: 0; }
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
            <!-- Simulated Barcode Line -->
            <div style="height: 60px; background: repeating-linear-gradient(90deg, #000, #000 2px, #fff 2px, #fff 4px, #000 4px, #000 8px, #fff 8px, #fff 10px, #000 10px, #000 14px, #fff 14px, #fff 18px); margin-bottom: 10px;"></div>
            <div class="barcode">{{ $shipment->awb_number }}</div>
            <div style="font-size: 12px; font-weight: bold; margin-top: 5px;">{{ $shipment->shipment_type }}</div>
        </div>
        
        <div class="addresses">
            <div class="address-box">
                <strong>TO:</strong>
                {{ $shipment->receiver_name }}<br>
                {{ $shipment->delivery_address }}<br>
                {{ $shipment->delivery_city }} - {{ $shipment->delivery_pincode }}<br>
                {{ $shipment->destination_country }}<br>
                Ph: {{ $shipment->receiver_phone }}
            </div>
            <div class="address-box">
                <strong>FROM:</strong>
                {{ $shipment->user->company_name ?? $shipment->user->name }}<br>
                Seller Hub<br>
                Ph: {{ $shipment->user->phone ?? 'N/A' }}
            </div>
        </div>
        
        <div class="details">
            <div class="overflow-x-auto w-full">
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
        </div>
        
        @if($shipment->is_cod)
        <div class="cod-box">
            COD TO COLLECT: Rs {{  number_format($shipment->total_amount, 2) }}
        </div>
        @else
        <div class="cod-box" style="border-color: #4CAF50; color: #4CAF50;">
            PREPAID
        </div>
        @endif
        
        <div class="footer">
            <p>Return Address: If undelivered, return to Origin Hub.</p>
        </div>
    </div>
</body>
</html>
