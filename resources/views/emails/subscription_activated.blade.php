<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Activated - {{ $subscription->subscription_code }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1e293b; line-height: 1.6;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f1f5f9; padding: 30px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #4f46e5, #7c3aed); padding: 32px 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0 0 8px 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;">
                                {{ setting('site_name', 'DigiGo') }}
                            </h1>
                            <p style="color: #e0e7ff; margin: 0; font-size: 14px; font-weight: 500;">
                                Digital Subscription Activated & Access Credentials
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 30px;">
                            <div style="background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px; padding: 18px; margin-bottom: 24px; text-align: center;">
                                <div style="display: inline-block; width: 40px; height: 40px; line-height: 40px; border-radius: 50%; background-color: #10b981; color: #ffffff; font-size: 20px; font-weight: bold; margin-bottom: 8px;">
                                    ✓
                                </div>
                                <h2 style="color: #065f46; font-size: 18px; margin: 0 0 6px 0; font-weight: 700;">
                                    Your Subscription is Now Active!
                                </h2>
                                <p style="color: #047857; margin: 0; font-size: 13px;">
                                    Subscription Code: <strong>#{{ $subscription->subscription_code }}</strong>
                                </p>
                            </div>

                            <p style="font-size: 14px; color: #475569; margin: 0 0 20px 0;">
                                Hello <strong>{{ $subscription->user->name ?? 'Valued Customer' }}</strong>,<br>
                                Great news! Your digital subscription for <strong>{{ $subscription->product_name }}</strong> has been officially activated by our team. You can find your login credentials and access instructions below:
                            </p>

                            <!-- Subscription Overview -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px; margin-bottom: 24px; font-size: 13px;">
                                <tr>
                                    <td style="padding: 5px 8px; color: #64748b; width: 35%;"><strong>Product:</strong></td>
                                    <td style="padding: 5px 8px; color: #0f172a; font-weight: 700;">{{ $subscription->product_name }}</td>
                                </tr>
                                @if($subscription->plan_name)
                                <tr>
                                    <td style="padding: 5px 8px; color: #64748b;"><strong>Plan / Variant:</strong></td>
                                    <td style="padding: 5px 8px; color: #0f172a; font-weight: 600;">{{ $subscription->plan_name }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="padding: 5px 8px; color: #64748b;"><strong>Validity:</strong></td>
                                    <td style="padding: 5px 8px; color: #0f172a;">{{ $subscription->validity ?? 'Standard' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px 8px; color: #64748b;"><strong>Activated At:</strong></td>
                                    <td style="padding: 5px 8px; color: #0f172a;">{{ $subscription->activated_at ? $subscription->activated_at->format('d M, Y h:i A') : now()->format('d M, Y h:i A') }}</td>
                                </tr>
                                @if($subscription->expires_at)
                                <tr>
                                    <td style="padding: 5px 8px; color: #64748b;"><strong>Expires At:</strong></td>
                                    <td style="padding: 5px 8px; color: #b91c1c; font-weight: 700;">{{ $subscription->expires_at->format('d M, Y h:i A') }}</td>
                                </tr>
                                @endif
                            </table>

                            <!-- Credentials Box (if provided) -->
                            @if(!empty($subscription->credentials) || !empty($subscription->license_key) || !empty($subscription->login_url))
                                <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 18px; margin-bottom: 24px;">
                                    <h3 style="color: #166534; font-size: 14px; margin: 0 0 12px 0; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">
                                        🔑 Access Credentials & License
                                    </h3>

                                    @if(!empty($subscription->login_url))
                                        <div style="margin-bottom: 12px;">
                                            <span style="font-size: 12px; font-weight: 700; color: #15803d; display: block; margin-bottom: 4px;">Service / Login URL:</span>
                                            <a href="{{ $subscription->login_url }}" target="_blank" style="font-size: 13px; color: #2563eb; font-weight: 600; text-decoration: underline; word-break: break-all;">
                                                {{ $subscription->login_url }}
                                            </a>
                                        </div>
                                    @endif

                                    @if(!empty($subscription->license_key))
                                        <div style="margin-bottom: 12px;">
                                            <span style="font-size: 12px; font-weight: 700; color: #15803d; display: block; margin-bottom: 4px;">License Key / Activation Code:</span>
                                            <div style="background-color: #ffffff; border: 1px solid #86efac; border-radius: 6px; padding: 8px 12px; font-family: monospace; font-size: 13px; color: #0f172a; font-weight: 700; word-break: break-all;">
                                                {{ $subscription->license_key }}
                                            </div>
                                        </div>
                                    @endif

                                    @if(!empty($subscription->credentials))
                                        <div>
                                            <span style="font-size: 12px; font-weight: 700; color: #15803d; display: block; margin-bottom: 4px;">Login Details / Account Info:</span>
                                            <div style="background-color: #ffffff; border: 1px solid #86efac; border-radius: 6px; padding: 12px; font-family: monospace; font-size: 13px; color: #0f172a; white-space: pre-wrap; word-break: break-all; line-height: 1.5;">{{ $subscription->credentials }}</div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Access Instructions (if provided) -->
                            @if(!empty($subscription->access_instructions))
                                <div style="background-color: #fefce8; border: 1px solid #fef08a; border-radius: 8px; padding: 16px; margin-bottom: 24px;">
                                    <h3 style="color: #854d0e; font-size: 13px; margin: 0 0 8px 0; font-weight: 800; text-transform: uppercase;">
                                        📌 Important Instructions / Rules
                                    </h3>
                                    <div style="font-size: 13px; color: #713f12; white-space: pre-wrap; line-height: 1.5;">{{ $subscription->access_instructions }}</div>
                                </div>
                            @endif

                            <!-- CTA Button to Dashboard -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top: 24px; margin-bottom: 16px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('user.subscriptions') }}" target="_blank" style="display: inline-block; background-color: #4f46e5; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 700; padding: 13px 28px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.25);">
                                            View Subscription in Your Dashboard →
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 12px; color: #64748b; text-align: center; margin: 0;">
                                You can log in anytime to your customer account to view your active subscriptions, renewal dates, and access credentials.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 24px 30px; text-align: center; font-size: 12px; color: #94a3b8;">
                            <p style="margin: 0 0 6px 0;">
                                Questions or need support? Reply directly to this email or contact us at <a href="mailto:{{ setting('support_email', 'support@digigo.com') }}" style="color: #4f46e5; text-decoration: none;">{{ setting('support_email', 'support@digigo.com') }}</a>
                            </p>
                            <p style="margin: 0;">
                                &copy; {{ date('Y') }} {{ setting('site_name', 'DigiGo') }}. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>

