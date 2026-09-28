<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { background-color: #ffffff; padding: 30px; border-radius: 8px; max-width: 600px; margin: 0 auto; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { text-align: center; border-bottom: 2px solid #D4AF37; padding-bottom: 20px; margin-bottom: 20px; }
        .title { color: #1e293b; font-size: 24px; font-weight: bold; margin: 0; }
        .content { color: #4a5568; line-height: 1.6; font-size: 16px; }
        .tracking-box { background-color: #f8fafc; border: 1px dashed #cbd5e1; padding: 15px; text-align: center; border-radius: 6px; margin: 20px 0; font-size: 18px; font-weight: bold; color: #1e293b; }
        .footer { text-align: center; color: #94a3b8; font-size: 12px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="title">OneStall Cargo</h1>
        </div>
        <div class="content">
            <p>Hello <strong>{{ $shipment->receiver_name }}</strong>,</p>
            <p>{{ $statusMsg }}</p>
            
            <div class="tracking-box">
                AWB / Tracking Number: <br>
                <span style="color: #D4AF37; font-size: 22px;">{{ $shipment->awb_number }}</span>
            </div>
            
            <p><strong>Delivery Address:</strong><br>
               {{ $shipment->delivery_address }}, {{ $shipment->delivery_city }} - {{ $shipment->delivery_pincode }}
            </p>
            
            <p>Thank you for choosing OneStall Cargo!</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} OneStall Cargo. All rights reserved.
        </div>
    </div>
</body>
</html>
