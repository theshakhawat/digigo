<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - DigiGo</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f1f5f9; margin: 0; padding: 40px 15px; color: #1e293b; line-height: 1.6;">
    <div style="max-width: 560px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0;">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #1e3a8a, #2563eb); padding: 32px 24px; text-align: center; color: #ffffff;">
            <h1 style="margin: 0; font-size: 26px; font-weight: 800; letter-spacing: -0.5px;">DigiGo<span style="color: #93c5fd;">.com.bd</span></h1>
            <p style="margin: 8px 0 0 0; font-size: 14px; opacity: 0.9;">Digital Products & Subscription Services</p>
        </div>

        <!-- Body -->
        <div style="padding: 36px 32px;">
            <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 16px;">Password Reset Request</h2>
            
            <p style="margin-bottom: 16px; color: #475569; font-size: 15px;">
                Hello <strong>{{ $user->name ?? 'Valued Customer' }}</strong>,
            </p>
            <p style="margin-bottom: 24px; color: #475569; font-size: 15px;">
                You are receiving this email because we received a password reset request for your DigiGo account. Click the button below to reset your password:
            </p>

            <!-- Reset Button -->
            <div style="text-align: center; margin: 32px 0;">
                <a href="{{ $resetUrl }}" style="background: #2563eb; color: #ffffff; padding: 14px 32px; font-size: 15px; font-weight: 600; text-decoration: none; border-radius: 8px; display: inline-block; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);">
                    Reset Password
                </a>
            </div>

            <p style="font-size: 13px; color: #64748b; margin-bottom: 20px;">
                This password reset link will expire in <strong>60 minutes</strong>. If you did not request a password reset, no further action is required.
            </p>

            <div style="border-top: 1px solid #e2e8f0; padding-top: 20px; margin-top: 24px;">
                <p style="font-size: 12px; color: #94a3b8; word-break: break-all; margin: 0;">
                    If you're having trouble clicking the button, copy and paste the URL below into your web browser:<br>
                    <a href="{{ $resetUrl }}" style="color: #2563eb;">{{ $resetUrl }}</a>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 20px 24px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 13px; color: #94a3b8;">
            © {{ date('Y') }} DigiGo.com.bd. All rights reserved.
        </div>
    </div>
</body>
</html>
