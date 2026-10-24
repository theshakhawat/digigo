<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - {{ $order->order_number }}</title>
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
                                {{ setting('site_name', 'DigiGo') }}
                            </h1>
                            <p style="color: #bfdbfe; margin: 0; font-size: 14px; font-weight: 500;">
                                Order Confirmation & Invoice Details
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 30px;">
                            <div style="background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px; padding: 16px; margin-bottom: 24px; text-align: center;">
                                <h2 style="color: #065f46; font-size: 18px; margin: 0 0 6px 0; font-weight: 700;">
                                    Thank you for your order!
                                </h2>
                                <p style="color: #047857; margin: 0; font-size: 13px;">
                                    Your order <strong>#{{ $order->order_number }}</strong> has been confirmed.
                                </p>
                            </div>

                            <p style="font-size: 14px; color: #475569; margin: 0 0 20px 0;">
                                Hello <strong>{{ $order->customer_name }}</strong>,<br>
                                We have received your order details. Here is your complete invoice and purchase summary:
                            </p>

                            <!-- Order Meta Info -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px; margin-bottom: 24px; font-size: 13px;">
                                <tr>
                                    <td style="padding: 4px 8px; color: #64748b; width: 40%;"><strong>Order Number:</strong></td>
                                    <td style="padding: 4px 8px; color: #0f172a; font-weight: 600;">#{{ $order->order_number }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 4px 8px; color: #64748b;"><strong>Order Date:</strong></td>
                                    <td style="padding: 4px 8px; color: #0f172a;">{{ $order->created_at->format('d M, Y h:i A') }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 4px 8px; color: #64748b;"><strong>Payment Method:</strong></td>
                                    <td style="padding: 4px 8px; color: #0f172a; text-transform: uppercase; font-weight: 700;">
                                        {{ $order->payment_method === 'cod' ? 'Cash on Delivery (COD)' : 'DGePay Online (' . ($order->dgepay_channel ?? 'Instant') . ')' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 4px 8px; color: #64748b;"><strong>Payment Status:</strong></td>
                                    <td style="padding: 4px 8px; color: {{ $order->payment_status === 'paid' ? '#059669' : '#d97706' }}; font-weight: 700; text-transform: uppercase;">
                                        {{ $order->payment_status }}
                                    </td>
                                </tr>
                                @if($order->dgepay_txn_number)
                                <tr>
                                    <td style="padding: 4px 8px; color: #64748b;"><strong>Transaction ID:</strong></td>
                                    <td style="padding: 4px 8px; color: #0f172a; font-family: monospace;">{{ $order->dgepay_txn_number }}</td>
                                </tr>
                                @endif
                            </table>

                            <!-- Items Table -->
                            <h3 style="font-size: 15px; color: #0f172a; margin: 0 0 12px 0; border-bottom: 2px solid #e2e8f0; padding-bottom: 8px;">
                                Ordered Products & Plans
                            </h3>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px; border-collapse: collapse; font-size: 13px;">
                                <thead>
                                    <tr style="background-color: #f1f5f9; text-align: left; color: #475569;">
                                        <th style="padding: 10px; border-bottom: 1px solid #e2e8f0;">Item</th>
                                        <th style="padding: 10px; border-bottom: 1px solid #e2e8f0; text-align: center;">Qty</th>
                                        <th style="padding: 10px; border-bottom: 1px solid #e2e8f0; text-align: right;">Price</th>
                                        <th style="padding: 10px; border-bottom: 1px solid #e2e8f0; text-align: right;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td style="padding: 12px 10px; border-bottom: 1px solid #f1f5f9; color: #1e293b;">
                                                <strong>{{ $item->title }}</strong>
                                                <div style="font-size: 11px; color: #64748b; text-transform: capitalize;">Type: {{ $item->product_type }}</div>
                                            </td>
                                            <td style="padding: 12px 10px; border-bottom: 1px solid #f1f5f9; text-align: center; color: #475569;">
                                                {{ $item->quantity }}
                                            </td>
                                            <td style="padding: 12px 10px; border-bottom: 1px solid #f1f5f9; text-align: right; color: #475569;">
                                                ৳{{ number_format($item->unit_price, 2) }}
                                            </td>
                                            <td style="padding: 12px 10px; border-bottom: 1px solid #f1f5f9; text-align: right; color: #0f172a; font-weight: 700;">
                                                ৳{{ number_format($item->subtotal, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Price Breakdown -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px; font-size: 13px;">
                                <tr>
                                    <td style="text-align: right; padding: 4px 10px; color: #64748b;">Subtotal:</td>
                                    <td style="text-align: right; padding: 4px 10px; width: 120px; color: #0f172a; font-weight: 600;">৳{{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                @if($order->discount > 0)
                                <tr>
                                    <td style="text-align: right; padding: 4px 10px; color: #059669;">Coupon Discount ({{ $order->coupon_code }}):</td>
                                    <td style="text-align: right; padding: 4px 10px; color: #059669; font-weight: 600;">-৳{{ number_format($order->discount, 2) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="text-align: right; padding: 4px 10px; color: #64748b;">Shipping / Delivery Charge:</td>
                                    <td style="text-align: right; padding: 4px 10px; color: #0f172a; font-weight: 600;">
                                        {{ $order->delivery_charge > 0 ? '৳' . number_format($order->delivery_charge, 2) : 'FREE' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right; padding: 10px 10px; border-top: 2px solid #e2e8f0; color: #0f172a; font-size: 16px; font-weight: 800;">Total Payable:</td>
                                    <td style="text-align: right; padding: 10px 10px; border-top: 2px solid #e2e8f0; color: #1d4ed8; font-size: 18px; font-weight: 800;">
                                        ৳{{ number_format($order->total, 2) }}
                                    </td>
                                </tr>
                            </table>

                            <!-- Shipping / Delivery Address -->
                            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px; font-size: 13px;">
                                <h4 style="margin: 0 0 6px 0; color: #0f172a; font-size: 14px;">Delivery Address:</h4>
                                <p style="margin: 0; color: #475569; line-height: 1.5;">
                                    <strong>{{ $order->customer_name }}</strong> ({{ $order->customer_phone }})<br>
                                    {{ $order->street_address }}<br>
                                    {{ $order->district }}, {{ $order->division }} @if($order->postal_code)- {{ $order->postal_code }} @endif
                                </p>
                            </div>

                            @if($order->notes)
                            <div style="margin-top: 15px; font-size: 12px; color: #64748b;">
                                <strong>Order Note:</strong> {{ $order->notes }}
                            </div>
                            @endif

                            <div style="text-align: center; margin-top: 30px;">
                                <a href="{{ route('user.orders') }}" style="display: inline-block; background-color: #2563eb; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 8px; font-weight: 700; font-size: 14px;">
                                    View in Dashboard
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px; text-align: center; font-size: 12px; color: #94a3b8;">
                            &copy; {{ date('Y') }} {{ setting('site_name', 'DigiGo') }}. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>

