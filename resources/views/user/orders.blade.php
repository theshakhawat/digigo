@extends('layouts.user')

@section('title', 'Orders - My Account - DigiGo')
@section('breadcrumb_current', 'Orders')

@section('account_content')
<div class="content-card-box">
    <h2>
        <i class="fa-regular fa-file-lines" style="color: var(--theme-accent);"></i> My Orders
    </h2>

    @if($orders->isEmpty())
        <div style="text-align: center; padding: 3.5rem 1rem; color: #64748b;">
            <i class="fa-solid fa-box-open" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1.25rem;"></i>
            <h3 style="font-size: 1.15rem; color: #1e293b; margin-bottom: 0.5rem; border: none; padding: 0;">No order has been made yet.</h3>
            <p style="font-size: 0.925rem; margin-bottom: 1.75rem; color: #64748b;">Browse our store for top digital products, subscriptions, and AI tools.</p>
            <a href="{{ url('/') }}" class="ribbon-pill-btn" style="background: var(--theme-header); color: #fff; text-decoration: none; display: inline-flex; padding: 0.65rem 1.75rem;">
                Browse Products
            </a>
        </div>
    @else
        <div style="overflow-x: auto; margin-top: 1rem;">
            <table class="account-table" style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid var(--account-border); text-align: left;">
                        <th style="padding: 12px 14px;">Order</th>
                        <th style="padding: 12px 14px;">Date</th>
                        <th style="padding: 12px 14px;">Payment</th>
                        <th style="padding: 12px 14px;">Status</th>
                        <th style="padding: 12px 14px; text-align: right;">Total</th>
                        <th style="padding: 12px 14px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody style="border-bottom: 1px solid var(--account-border);">
                    @foreach($orders as $order)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 14px; font-weight: 700; color: #0f172a;">
                                #{{ $order->order_number }}
                                <div style="font-size: 0.75rem; color: #64748b; font-weight: normal;">
                                    {{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}
                                </div>
                            </td>
                            <td style="padding: 14px; color: #475569;">
                                {{ $order->created_at->format('M d, Y') }}
                            </td>
                            <td style="padding: 14px;">
                                <div style="text-transform: uppercase; font-weight: 600; font-size: 0.8rem; color: #0f172a;">
                                    {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'DGePay' }}
                                </div>
                                <span style="font-size: 0.75rem; font-weight: 700; color: {{ $order->payment_status === 'paid' ? '#059669' : '#d97706' }};">
                                    {{ strtoupper($order->payment_status) }}
                                </span>
                            </td>
                            <td style="padding: 14px;">
                                @php
                                    $badgeBg = '#f1f5f9';
                                    $badgeColor = '#475569';
                                    if ($order->order_status === 'confirmed' || $order->order_status === 'completed') {
                                        $badgeBg = '#ecfdf5';
                                        $badgeColor = '#059669';
                                    } elseif ($order->order_status === 'cancelled') {
                                        $badgeBg = '#fef2f2';
                                        $badgeColor = '#dc2626';
                                    }
                                @endphp
                                <span style="display: inline-block; padding: 3px 10px; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; background: {{ $badgeBg }}; color: {{ $badgeColor }}; text-transform: uppercase;">
                                    {{ $order->order_status }}
                                </span>
                            </td>
                            <td style="padding: 14px; text-align: right; font-weight: 800; color: #0f172a;">
                                ৳{{ number_format($order->total, 2) }}
                            </td>
                            <td style="padding: 14px; text-align: center; white-space: nowrap;">
                                <div style="display: inline-flex; align-items: center; gap: 6px;">
                                    <a href="{{ route('user.orders.invoice', $order->id) }}" target="_blank" class="btn-primary" style="padding: 4px 10px; font-size: 0.75rem; text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; background: #059669; color: #fff;">
                                        <i class="fa-solid fa-print"></i> Invoice
                                    </a>
                                    <a href="{{ route('checkout.success', $order->id) }}" class="btn-secondary" style="padding: 4px 10px; font-size: 0.75rem; text-decoration: none; border-radius: 6px;">
                                        View
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
