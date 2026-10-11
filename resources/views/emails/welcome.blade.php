<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to DigiGo</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f1f5f9; margin: 0; padding: 40px 15px; color: #1e293b; line-height: 1.6;">
    <div style="max-width: 580px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0;">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #1e3a8a, #2563eb); padding: 36px 24px; text-align: center; color: #ffffff;">
            <h1 style="margin: 0; font-size: 28px; font-weight: 800; letter-spacing: -0.5px;">DigiGo<span style="color: #93c5fd;">.com.bd</span></h1>
            <p style="margin: 8px 0 0 0; font-size: 14px; opacity: 0.9;">Bangladesh's Premier Digital Marketplace</p>
        </div>

        <!-- Body -->
        <div style="padding: 36px 32px;">
            <h2 style="font-size: 22px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 16px;">Welcome to DigiGo, {{ $user->name }}! 🎉</h2>
            
            <p style="margin-bottom: 16px; color: #475569; font-size: 15px;">
                Thank you for creating an account with us. We are excited to have you in our community of digital creators, professionals, and gadget enthusiasts!
            </p>

            <div style="background-color: #f8fafc; border-left: 4px solid #2563eb; padding: 16px 20px; border-radius: 0 8px 8px 0; margin: 24px 0;">
                <p style="margin: 0; font-size: 14px; color: #334155; font-weight: 600;">Here is what you can do with your DigiGo account:</p>
                <ul style="margin: 10px 0 0 0; padding-left: 20px; font-size: 13px; color: #64748b;">
                    <li>Instant delivery of verified software license keys & AI tools</li>
                    <li>Track your active orders and download license keys anytime</li>
                    <li>Save favorite products to your personal wishlist</li>
                    <li>24/7 dedicated support via hotline and WhatsApp</li>
                </ul>
            </div>

            <!-- Action CTA -->
            <div style="text-align: center; margin: 32px 0;">
                <a href="{{ route('user.dashboard') }}" style="background: #2563eb; color: #ffffff; padding: 14px 34px; font-size: 15px; font-weight: 700; text-decoration: none; border-radius: 8px; display: inline-block; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);">
                    Access Your Dashboard
                </a>
            </div>

            <p style="font-size: 13px; color: #64748b; margin-bottom: 0;">
                Need help or have questions about a product? Reach out to our 24/7 support hotline: <strong>{{ setting('topbar_support_phone', '+8809611678088') }}</strong> or reply directly to this email.
            </p>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 24px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8;">
            <p style="margin: 0 0 6px 0;">© {{ date('Y') }} DigiGo.com.bd. All rights reserved.</p>
            <p style="margin: 0;">Dhaka, Bangladesh</p>
        </div>
    </div>
</body>
</html>
