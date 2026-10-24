<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiGo SMTP Test Email</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1e293b;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f8fafc; padding: 40px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #1e40af; padding: 28px 32px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;">
                                DigiGo<span style="color: #60a5fa;">.admin</span>
                            </h1>
                            <p style="margin: 6px 0 0; color: #bfdbfe; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">
                                SMTP Configuration Test
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 36px 32px;">
                            
                            <!-- Success Banner -->
                            <div style="background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px; padding: 16px 20px; margin-bottom: 24px;">
                                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="32" valign="top">
                                            <div style="background-color: #10b981; color: #ffffff; width: 24px; height: 24px; border-radius: 50%; text-align: center; line-height: 24px; font-weight: bold; font-size: 14px;">✓</div>
                                        </td>
                                        <td valign="top">
                                            <p style="margin: 0; font-weight: 700; color: #065f46; font-size: 15px;">
                                                SMTP Credentials Verified Successfully!
                                            </p>
                                            <p style="margin: 4px 0 0; color: #047857; font-size: 13px; line-height: 1.4;">
                                                Your email delivery system is functioning properly. All system emails, order receipts, and password resets will now be delivered using these credentials.
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Details Table -->
                            <h3 style="margin: 0 0 12px; font-size: 14px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">
                                Active Connection Parameters
                            </h3>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 13px;">
                                <tr>
                                    <td style="padding: 10px 16px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600; width: 35%;">Mail Driver</td>
                                    <td style="padding: 10px 16px; border-bottom: 1px solid #e2e8f0; color: #0f172a; font-weight: 700;">{{ strtoupper($configDetails['mailer'] ?? 'SMTP') }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 16px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600;">SMTP Host</td>
                                    <td style="padding: 10px 16px; border-bottom: 1px solid #e2e8f0; color: #0f172a; font-family: monospace;">{{ $configDetails['host'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 16px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600;">SMTP Port</td>
                                    <td style="padding: 10px 16px; border-bottom: 1px solid #e2e8f0; color: #0f172a; font-family: monospace;">{{ $configDetails['port'] ?? '587' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 16px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600;">Encryption</td>
                                    <td style="padding: 10px 16px; border-bottom: 1px solid #e2e8f0; color: #0f172a; text-transform: uppercase;">{{ $configDetails['encryption'] ?? 'TLS' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 16px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600;">Sender (From)</td>
                                    <td style="padding: 10px 16px; border-bottom: 1px solid #e2e8f0; color: #0f172a;">{{ $configDetails['from_name'] ?? 'DigiGo' }} &lt;{{ $configDetails['from_address'] ?? '' }}&gt;</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 16px; color: #64748b; font-weight: 600;">Timestamp</td>
                                    <td style="padding: 10px 16px; color: #0f172a;">{{ $configDetails['sent_at'] ?? now()->toDateTimeString() }}</td>
                                </tr>
                            </table>

                            <p style="margin: 24px 0 0; color: #64748b; font-size: 12px; line-height: 1.5; text-align: center;">
                                This is an automated diagnostic email generated from your DigiGo Administration Portal. No response is required.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f1f5f9; padding: 18px 32px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0; color: #94a3b8; font-size: 12px;">
                                &copy; {{ date('Y') }} DigiGo Bangladesh. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
