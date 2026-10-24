<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Reply - #{{ $ticket->ticket_number }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1e293b; line-height: 1.6;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f1f5f9; padding: 30px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">

                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #1e40af, #2563eb); padding: 32px 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0 0 8px 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;">
                                {{ setting('site_name', 'DigiGo') }} Support
                            </h1>
                            <p style="color: #bfdbfe; margin: 0; font-size: 14px; font-weight: 500;">
                                Update on Support Ticket #{{ $ticket->ticket_number }}
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 30px;">
                            <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 16px; margin-bottom: 24px;">
                                <div style="font-size: 13px; color: #1e40af; margin-bottom: 4px; font-weight: 700; text-transform: uppercase;">
                                    Ticket Subject:
                                </div>
                                <h2 style="color: #1e3a8a; font-size: 16px; margin: 0 0 6px 0; font-weight: 700;">
                                    {{ $ticket->subject }}
                                </h2>
                                <p style="color: #3b82f6; margin: 0; font-size: 12px;">
                                    Department: <strong>{{ $ticket->department_label }}</strong> | Status: <strong>{{ strtoupper($ticket->status) }}</strong>
                                </p>
                            </div>

                            <p style="font-size: 14px; color: #475569; margin: 0 0 16px 0;">
                                Hello <strong>{{ $ticket->user->name ?? 'Valued Customer' }}</strong>,<br>
                                Our support team has responded to your ticket. Here is the reply:
                            </p>

                            <!-- Staff Reply Box -->
                            <div style="background-color: #f8fafc; border-left: 4px solid #2563eb; border-radius: 4px 8px 8px 4px; padding: 18px; margin-bottom: 24px;">
                                <div style="font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 8px;">
                                    Support Staff Reply:
                                </div>
                                <div style="font-size: 14px; color: #0f172a; white-space: pre-wrap; line-height: 1.6;">{{ $ticketMessage->message }}</div>

                                @if(!empty($ticketMessage->attachments))
                                    <div style="margin-top: 14px; padding-top: 10px; border-top: 1px dashed #cbd5e1; font-size: 12px; color: #64748b;">
                                        <strong>Attachments included:</strong> {{ count($ticketMessage->attachments) }} file(s). Please log in to your dashboard to view or download files.
                                    </div>
                                @endif
                            </div>

                            <!-- CTA Button -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top: 24px; margin-bottom: 16px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('user.tickets.show', $ticket->id) }}" target="_blank" style="display: inline-block; background-color: #2563eb; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 700; padding: 12px 28px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.25);">
                                            View & Reply in Your Account →
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 12px; color: #64748b; text-align: center; margin: 0;">
                                If your issue is resolved, you may close the ticket directly from your account dashboard.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 30px; text-align: center; font-size: 12px; color: #94a3b8;">
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

