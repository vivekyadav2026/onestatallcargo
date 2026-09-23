<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - OneStall Cargo</title>
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
                            <h1 style="margin: 0; font-size: 24px; font-weight: 800; color: #ffffff;">OneStall Cargo Support</h1>
                            <p style="margin: 5px 0 0 0; font-size: 13px; color: #fbbf24; font-weight: 600;">पासवर्ड रीसेट अनुरोध (Password Reset Request)</p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 35px 30px;">
                            <h2 style="font-size: 20px; color: #17233f; margin-top: 0;">नमस्ते, 👋</h2>
                            <p style="font-size: 15px; line-height: 1.6; color: #4a5568;">
                                हमें आपके OneStall Cargo अकाउंट के लिए पासवर्ड रीसेट करने की रिक्वेस्ट मिली है। अपना नया पासवर्ड सेट करने के लिए नीचे दिए गए बटन पर क्लिक करें:
                            </p>

                            <!-- CTA Button -->
                            <div align="center" style="margin: 30px 0;">
                                <a href="{{ $resetUrl }}" style="background-color: #fbbf24; color: #0f172a; text-decoration: none; padding: 14px 32px; border-radius: 10px; font-weight: 800; font-size: 15px; display: inline-block;">
                                    पासवर्ड रीसेट करें (Reset Password) &rarr;
                                </a>
                            </div>

                            <p style="font-size: 13px; line-height: 1.6; color: #64748b;">
                                यह पासवर्ड रीसेट लिंक 60 मिनट के लिए मान्य है। यदि आपने पासवर्ड रीसेट का अनुरोध नहीं किया है, तो आप इस ईमेल को सुरक्षित रूप से अनदेखा कर सकते हैं।
                            </p>

                            <div style="margin-top: 20px; border-top: 1px dashed #cbd5e1; padding-top: 15px; font-size: 12px; color: #94a3b8; word-break: break-all;">
                                बटन काम न करने पर इस लिंक को अपने ब्राउज़र में कॉपी-पेस्ट करें:<br>
                                <a href="{{ $resetUrl }}" style="color: #2563eb;">{{ $resetUrl }}</a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #f1f5f9; padding: 20px; font-size: 12px; color: #64748b; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0;">&copy; {{ date('Y') }} OneStall Cargo. All rights reserved.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>

