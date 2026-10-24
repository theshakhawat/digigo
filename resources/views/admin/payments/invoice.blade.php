<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Invoice #{{ $order->order_number }} - {{ setting('site_name', 'DigiGo') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
                padding: 0 !important;
            }
            .invoice-box {
                box-shadow: none !important;
                border: none !important;
                max-width: 100% !important;
                padding: 0 !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-8 px-4 text-slate-800 antialiased flex flex-col items-center">

    <!-- Top Action Bar (No Print) -->
    <div class="w-full max-w-3xl flex items-center justify-between mb-6 no-print">
        <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-blue-600 transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Payments</span>
        </a>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold shadow-sm transition cursor-pointer">
                <i class="fa-solid fa-print"></i>
                <span>Print Invoice</span>
            </button>
        </div>
    </div>

    <!-- Official Invoice Container -->
    <div class="invoice-box w-full max-w-3xl bg-white rounded-2xl border border-slate-200 shadow-sm p-8 sm:p-12 relative overflow-hidden">
        
        <!-- Paid Watermark Stamp -->
        <div class="absolute right-8 top-28 sm:top-24 rotate-[-12deg] select-none pointer-events-none opacity-85">
            <div class="border-4 border-emerald-600 text-emerald-600 rounded-xl px-4 py-1 text-xl sm:text-2xl font-black uppercase tracking-widest text-center shadow-xs">
                PAID & VERIFIED
            </div>
            <div class="text-[9px] text-center font-bold text-emerald-700 uppercase tracking-wider mt-0.5">
                {{ ($order->paid_at ?? $order->created_at)->format('d M, Y') }}
            </div>
        </div>

        <!-- Header: Brand & Invoice Meta -->
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between pb-8 border-b border-slate-200 gap-6">
            <div>
                <img src="{{ default_site_logo() }}" alt="{{ setting('site_name', 'DigiGo') }}" class="h-10 max-w-[160px] object-contain mb-2">
                <p class="text-xs text-slate-500 max-w-xs mt-1">
                    Authentic Digital Goods, Licenses & Lifestyle Gadgets.<br>
                    Hotline: {{ setting('topbar_support_phone', '+8809611678088') }} &bull; Dhaka, Bangladesh
                </p>
            </div>

            <div class="text-left sm:text-right">
                <h2 class="text-xl sm:text-2xl font-extrabold uppercase tracking-tight text-slate-900">Official Invoice</h2>
                <div class="text-sm font-mono font-bold text-blue-600 mt-1">#{{ $order->order_number }}</div>
                <div class="text-xs text-slate-500 mt-1">
                    Date: <strong class="text-slate-800">{{ $order->created_at->format('d M, Y') }}</strong>
                </div>
            </div>
        </div>

        <!-- Bill To & Payment Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 py-6 border-b border-slate-200 text-xs">
            <div>
                <span class="font-bold uppercase tracking-wider text-slate-400 block mb-1">Invoiced To:</span>
                <div class="text-sm font-extrabold text-slate-900">{{ $order->customer_name }}</div>
                <div class="text-slate-600 mt-0.5">{{ $order->customer_email }}</div>
                <div class="text-slate-600">{{ $order->customer_phone }}</div>
                <div class="text-slate-500 mt-1.5 leading-relaxed">
                    {{ $order->street_address }}<br>
                    {{ $order->district }}, {{ $order->division }} @if($order->postal_code)- {{ $order->postal_code }} @endif
                </div>
            </div>

            <div class="sm:text-right space-y-1.5">
                <span class="font-bold uppercase tracking-wider text-slate-400 block mb-1">Payment Details:</span>
                <div>
                    <span class="text-slate-400">Method:</span>
                    <strong class="text-slate-900 uppercase">
                        {{ $order->payment_method === 'cod' ? 'Cash on Delivery (COD)' : 'DGePay Online Gateway' }}
                    </strong>
                </div>
                @if($order->dgepay_channel)
                    <div>
                        <span class="text-slate-400">Channel:</span>
                        <strong class="text-slate-900">{{ $order->dgepay_channel }}</strong>
                    </div>
                @endif
                @php
                    $primaryTrx = $order->dgepay_txn_number ?? $order->dgepay_unique_txn_id;
                @endphp
                @if($primaryTrx)
                    <div>
                        <span class="text-slate-400">Gateway Trx ID:</span>
                        <span class="font-mono font-bold text-slate-900">{{ $primaryTrx }}</span>
                    </div>
                @endif
                @if($order->dgepay_third_party_txn_number)
                    <div>
                        <span class="text-slate-400">MFS/Bank Trx:</span>
                        <span class="font-mono text-slate-900">{{ $order->dgepay_third_party_txn_number }}</span>
                    </div>
                @endif
                <div>
                    <span class="text-slate-400">Settled On:</span>
                    <strong class="text-slate-900">{{ ($order->paid_at ?? $order->created_at)->format('d M, Y h:i A') }}</strong>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="py-6 border-b border-slate-200">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase text-[10px] tracking-wider">
                        <th class="py-2.5">Item Description</th>
                        <th class="py-2.5 text-center">Type</th>
                        <th class="py-2.5 text-center">Qty</th>
                        <th class="py-2.5 text-right">Unit Price</th>
                        <th class="py-2.5 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="py-3 font-bold text-slate-900 text-sm">
                                {{ $item->title }}
                            </td>
                            <td class="py-3 text-center capitalize text-slate-500">
                                {{ $item->product_type }}
                            </td>
                            <td class="py-3 text-center font-bold text-slate-800">
                                {{ $item->quantity }}
                            </td>
                            <td class="py-3 text-right text-slate-600">
                                ৳{{ number_format($item->unit_price, 2) }}
                            </td>
                            <td class="py-3 text-right font-bold text-slate-900 text-sm">
                                ৳{{ number_format($item->subtotal, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Calculation Summary -->
        <div class="pt-6 flex flex-col sm:flex-row justify-between items-start gap-4">
            <div class="text-xs text-slate-500 max-w-sm">
                <span class="font-bold text-slate-800 uppercase block text-[11px] mb-1">Notice & Terms:</span>
                <p class="leading-relaxed">
                    This is an electronically generated and digitally signed receipt for your payment with DigiGo. All subscription credentials and activation details are protected by our satisfaction guarantee.
                </p>
            </div>

            <div class="w-full sm:w-64 space-y-2 text-xs">
                <div class="flex justify-between text-slate-600">
                    <span>Subtotal:</span>
                    <span class="font-bold text-slate-900">৳{{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->discount > 0)
                    <div class="flex justify-between text-emerald-600 font-semibold">
                        <span>Discount ({{ $order->coupon_code }}):</span>
                        <span>-৳{{ number_format($order->discount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between text-slate-600">
                    <span>Shipping / Delivery:</span>
                    <span class="font-bold text-slate-900">
                        {{ $order->delivery_charge > 0 ? '৳' . number_format($order->delivery_charge, 2) : 'FREE' }}
                    </span>
                </div>
                <div class="pt-3 border-t-2 border-slate-900 flex justify-between items-baseline text-sm font-extrabold text-slate-900">
                    <span>Total Paid:</span>
                    <span class="text-xl text-blue-600">৳{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer Seal -->
        <div class="mt-12 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between text-[11px] text-slate-400 gap-2">
            <span>Customer Support: {{ setting('topbar_support_phone', '+8809611678088') }}</span>
            <span>Generated on {{ date('l, d F Y') }} &bull; DigiGo Accounting</span>
        </div>

    </div>

</body>
</html>
