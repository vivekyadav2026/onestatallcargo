<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Label - {{ $shipment->awb_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; margin: 0; padding: 20px; background: #f3f4f6; display: flex; flex-direction: column; align-items: center; }
        .no-print-bar {
            width: 100%;
            max-width: 384px;
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            padding: 8px 12px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            box-sizing: border-box;
        }
        .no-print-bar .info {
            font-size: 12px;
            font-weight: 700;
            color: #1f2937;
        }
        .no-print-bar .badge {
            background: #e0e7ff;
            color: #4338ca;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 9999px;
            margin-left: 4px;
        }
        .no-print-bar .btns {
            display: flex;
            gap: 6px;
        }
        .btn-print {
            cursor: pointer;
            background: #1e1b4b;
            color: #ffffff;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .btn-print:hover {
            background: #312e81;
        }
        .btn-close {
            cursor: pointer;
            background: #f3f4f6;
            color: #4b5563;
            border: 1px solid #d1d5db;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }
        .btn-close:hover {
            background: #e5e7eb;
        }
        .label-container { width: 100%; max-width: 384px; min-height: 576px; background: #fff; border: 2px solid #000; padding: 15px; box-sizing: border-box; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); display: flex; flex-direction: column; }
        .header { border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 10px; text-align: center; }
        .header h1 { margin: 0; font-size: 20px; font-weight: bold; letter-spacing: 0.5px; }
        .barcode-area { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 10px; }
        .barcode-bars { height: 60px; background: repeating-linear-gradient(90deg, #000, #000 2px, #fff 2px, #fff 4px, #000 4px, #000 8px, #fff 8px, #fff 10px, #000 10px, #000 14px, #fff 14px, #fff 18px); margin-bottom: 10px; width: 100%; }
        .barcode-text { font-family: 'Courier New', Courier, monospace; font-size: 26px; font-weight: bold; letter-spacing: 2px; }
        .addresses { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 10px; font-size: 11px; line-height: 1.35; }
        .address-box { word-wrap: break-word; overflow-wrap: break-word; word-break: break-word; }
        .address-box strong { display: block; margin-bottom: 3px; font-size: 12px; font-weight: 800; text-transform: uppercase; border-bottom: 1px solid #ddd; padding-bottom: 2px; }
        .details { font-size: 11px; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 10px; }
        .details table { width: 100%; text-align: left; border-collapse: collapse; border: 1px solid #000; table-layout: fixed; word-wrap: break-word; }
        .details th { font-weight: bold; padding: 4px 6px; vertical-align: top; background: #f9fafb; border: 1px solid #000; font-size: 10px; }
        .details td { padding: 4px 6px; vertical-align: top; border: 1px solid #000; font-size: 10px; font-weight: bold; }
        .cod-box { font-size: 22px; font-weight: bold; text-align: center; padding: 10px; border: 2px solid #000; margin-top: 15px; margin-bottom: 15px; border-radius: 4px; }
        .cod-box.prepaid { border-color: #4CAF50; color: #4CAF50; }
        .footer { text-align: center; font-size: 9px; font-weight: bold; line-height: 1.3; margin-top: auto; word-wrap: break-word; }
        
        @media print {
            .no-print, .no-print-bar { display: none !important; }
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
    <div class="no-print-bar">
        <div class="info">
            AWB: {{ $shipment->awb_number }}
        </div>
        <div class="btns">
            <button type="button" onclick="window.print()" class="btn-print">
                Print / Save as PDF
            </button>
            <button type="button" onclick="window.close()" class="btn-close">
                Close
            </button>
        </div>
    </div>
    <div class="label-container">
        <div class="header">
            <h1>ONESTALL CARGO</h1>
        </div>
        
        <div class="barcode-area">
            <div style="margin-bottom: 5px; display: flex; justify-content: center; width: 100%;">
                @if(!empty($barcode))
                    {!! $barcode !!}
                @else
                    <div class="barcode-bars"></div>
                @endif
            </div>
            <div class="barcode-text">{{ $shipment->awb_number }}</div>
        </div>
        
        <div class="addresses">
            <div class="address-box">
                <strong>TO:</strong>
                {{ $shipment->receiver_name }}<br>
                @if(!empty($settings['enable_consignee_address'])){{ $shipment->delivery_address }}<br>@else<i>[Address Hidden]</i><br>@endif
                {{ $shipment->delivery_city }} - {{ $shipment->delivery_pincode }}<br>
                {{ strtoupper($shipment->destination_country ?? 'India') }}<br>
                Ph: {{ !empty($settings['enable_consignee_contact']) ? $shipment->receiver_phone : substr($shipment->receiver_phone, 0, 4) . '******' }}
            </div>
            <div class="address-box">
                <strong>FROM:</strong>
                {{ optional($shipment->user)->company_name ?? optional($shipment->user)->name ?? 'OneStall Shipper' }}<br>
                {{ $shipment->pickup_address ?? 'Origin Hub' }}<br>{{ $shipment->pickup_city ?? 'City' }} - {{ $shipment->pickup_pincode ?? '000000' }}<br>
                Ph: {{ optional($shipment->user)->phone ?? 'N/A' }}
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
            COD TO COLLECT: Rs. {{ number_format($shipment->cod_amount ?? $shipment->invoice_value, 2) }}
        </div>
        @else
        <div class="cod-box prepaid">
            PREPAID
        </div>
        @endif
        
        <div class="footer">
            @if(!empty($settings['enable_rto_address']))<p>Return Address: If undelivered, return to {{ $shipment->pickup_address }}, {{ $shipment->pickup_city }} - {{ $shipment->pickup_pincode }}</p>@endif 
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
