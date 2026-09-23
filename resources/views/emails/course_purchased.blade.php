<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Purchase Confirmation - Testwise</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #17233f;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f4f6f9; padding: 30px 0;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.08);">
                    
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #0d1527, #17233f); padding: 35px 20px; color: #ffffff;">
                            <div style="width: 50px; height: 50px; background-color: #fbbf24; border-radius: 12px; display: inline-block; line-height: 50px; font-weight: 900; font-size: 24px; color: #17233f; margin-bottom: 12px;">
                                T
                            </div>
                            <h1 style="margin: 0; font-size: 24px; font-weight: 800; color: #ffffff;">Testwise Webbooks & CBT</h1>
                            <p style="margin: 5px 0 0 0; font-size: 13px; color: #fbbf24; font-weight: 600;">कोर्स खरीद की पुष्टि (Purchase Successful)</p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 35px 30px;">
                            <h2 style="font-size: 20px; color: #17233f; margin-top: 0;">नमस्ते {{ $user->name }}, 🎉</h2>
                            <p style="font-size: 15px; line-height: 1.6; color: #4a5568;">
                                बधाई हो! आपका <strong>{{ $course->title_hi ?? $course->title_en }}</strong> का Pro सब्सक्रिप्शन सफलतापूर्वक एक्टिवेट हो गया है। अब आप सभी प्रीमियम चैप्टर्स, नोट्स और Full CBT Mock Tests एक्सेस कर सकते हैं।
                            </p>

                            <!-- Purchase Summary Box -->
                            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin: 25px 0;">
                                <h3 style="margin-top: 0; font-size: 15px; color: #0f172a; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">भुगतान का विवरण (Receipt Details)</h3>
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 14px; line-height: 2;">
                                    <tr>
                                        <td style="color: #64748b;">कोर्स (Course Name):</td>
                                        <td align="right" style="font-weight: 700; color: #0f172a;">{{ $course->title_hi ?? $course->title_en }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #64748b;">ऑर्डर ID (Order ID):</td>
                                        <td align="right" style="font-weight: 600; color: #0f172a;">{{ $payment->order_id }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #64748b;">ट्रांजैक्शन रिफ (Txn Ref):</td>
                                        <td align="right" style="font-weight: 600; color: #0f172a;">{{ $payment->transaction_ref }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #64748b;">कुल भुगतान (Amount Paid):</td>
                                        <td align="right" style="font-weight: 800; color: #16a34a; font-size: 16px;">₹{{ number_format($payment->amount, 2) }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- CTA Button -->
                            <div align="center" style="margin-top: 30px;">
                                <a href="{{ route('student.course') }}" style="background-color: #fbbf24; color: #0f172a; text-decoration: none; padding: 14px 32px; border-radius: 10px; font-weight: 800; font-size: 15px; display: inline-block; shadow: 0 4px 10px rgba(251, 191, 36, 0.3);">
                                    अभी पढ़ाई शुरू करें (Start Studying Now) &rarr;
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #f1f5f9; padding: 20px; font-size: 12px; color: #64748b; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0;">यह एक ऑटोमैटिक ईमेल है। किसी भी सहायता के लिए <a href="{{ route('contact') }}" style="color: #2563eb; text-decoration: underline;">Testwise Support</a> से संपर्क करें।</p>
                            <p style="margin: 5px 0 0 0;">&copy; {{ date('Y') }} Testwise Learning Engine. All rights reserved.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
