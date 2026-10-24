<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }} - {{ setting('site_name', 'DigiGo') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        @page {
            size: A4 portrait;
            margin: 12mm 10mm 12mm 10mm;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                color: #0f172a !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .invoice-wrapper {
                box-shadow: none !important;
                border: none !important;
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
                border-radius: 0 !important;
            }
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-8 px-4 text-slate-800 antialiased flex flex-col items-center">

    <!-- Top Action Bar (hidden in print) -->
    <div class="w-full max-w-4xl flex items-center justify-between mb-5 no-print">
        <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-slate-900 bg-white px-3.5 py-2 rounded-lg border border-slate-200 shadow-xs transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Order #{{ $order->order_number }}</span>
        </a>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow-md transition cursor-pointer">
                <i class="fa-solid fa-print"></i>
                <span>Print Invoice</span>
            </button>
        </div>
    </div>

    <!-- Main Printable Invoice Card -->
    <div class="invoice-wrapper w-full max-w-4xl bg-white rounded-2xl border border-slate-200 shadow-sm p-8 sm:p-12 relative overflow-hidden">
        
        <!-- Background Accent Bar -->
        <div class="h-2 w-full bg-gradient-to-r from-brand-600 via-indigo-600 to-emerald-500 absolute top-0 left-0"></div>

        <!-- HEADER SECTION: Logo + Company Info (Left) | QR Code + Meta (Right) -->
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between pb-8 border-b-2 border-slate-100 gap-6">
            <!-- Left: Brand Logo & Company Info -->
            <div class="max-w-md">
                <div class="flex items-center gap-3 mb-2.5">
                    <img src="{{ default_site_logo() }}" alt="{{ setting('site_name', 'DigiGo') }}" class="h-11 max-w-[190px] object-contain">
                </div>
                <div class="text-xs text-slate-500 space-y-1">
                    <p class="font-bold text-slate-800 text-sm tracking-tight">{{ setting('site_name', 'DigiGo') }}</p>
                    <p class="leading-relaxed">
                        {{ setting('site_tagline', 'Authentic Digital Goods, Licenses & Lifestyle Gadgets') }}
                    </p>
                    <p class="text-[11px] text-slate-500">
                        <span class="font-semibold text-slate-700"><i class="fa-solid fa-phone text-[10px] text-brand-600 mr-1"></i>Hotline:</span> {{ setting('topbar_support_phone', '+8809611678088') }}
                        @if(setting('support_email'))
                            <span class="mx-1">&bull;</span>
                            <span class="font-semibold text-slate-700"><i class="fa-solid fa-envelope text-[10px] text-brand-600 mr-1"></i>Email:</span> {{ setting('support_email') }}
                        @endif
                    </p>
                    <p class="text-[11px] text-slate-500">
                        <span class="font-semibold text-slate-700"><i class="fa-solid fa-location-dot text-[10px] text-brand-600 mr-1"></i>Address:</span> {{ setting('contact_address', 'Dhaka, Bangladesh') }}
                    </p>
                </div>
            </div>

            <!-- Right: QR Code & Invoice Details -->
            <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-start gap-4">
                @php
                    $verifyData = route('admin.orders.show', $order->id);
                    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=140x140&margin=4&data=' . urlencode($verifyData);
                @endphp
                <div class="flex items-center gap-3.5 bg-slate-50 border border-slate-200 p-2.5 rounded-xl">
                    <img src="{{ $qrUrl }}" alt="Invoice Verification QR" class="w-20 h-20 sm:w-22 sm:h-22 rounded-md object-contain bg-white p-1 shadow-2xs">
                    <div class="text-left text-[10px] text-slate-500 leading-tight">
                        @if($order->payment_status === 'paid')
                            <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-emerald-600 text-white font-black text-[10px] uppercase tracking-wider mb-1.5 shadow-xs">
                                <i class="fa-solid fa-circle-check text-[9px]"></i>
                                <span>PAID</span>
                            </div>
                        @elseif($order->order_status === 'cancelled')
                            <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-rose-600 text-white font-black text-[10px] uppercase tracking-wider mb-1.5 shadow-xs">
                                <i class="fa-solid fa-circle-xmark text-[9px]"></i>
                                <span>CANCELLED</span>
                            </div>
                        @else
                            <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-amber-500 text-white font-black text-[10px] uppercase tracking-wider mb-1.5 shadow-xs">
                                <i class="fa-solid fa-clock text-[9px]"></i>
                                <span>{{ strtoupper($order->payment_status) }}</span>
                            </div>
                        @endif
                        <span class="font-bold uppercase tracking-wider text-slate-800 block mb-0.5">Scan to Verify</span>
                        <span class="font-mono text-slate-600">#{{ $order->order_number }}</span><br>
                        <span class="text-slate-400">DigiGo Secured</span>
                    </div>
                </div>

                <div class="text-left sm:text-right mt-1">
                    <h2 class="text-xl sm:text-2xl font-black uppercase tracking-tight text-slate-900">
                        Tax Invoice
                    </h2>
                    <div class="text-xs font-mono font-bold text-brand-600 mt-0.5">
                        Invoice No: <span class="text-slate-900 font-extrabold">#{{ $order->order_number }}</span>
                    </div>
                    <div class="text-xs text-slate-500 mt-0.5">
                        Invoice Date: <strong class="text-slate-800">{{ $order->created_at->format('d M, Y') }}</strong>
                    </div>
                    <div class="text-xs text-slate-500 mt-0.5">
                        Order Status: <strong class="capitalize text-slate-800">{{ $order->order_status }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- MIDDLE SECTION: Customer Bill To & Payment Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 py-6 border-b border-slate-200 text-xs">
            <!-- Left: Bill To & Shipping Address -->
            <div>
                <span class="font-extrabold uppercase tracking-wider text-slate-400 text-[10px] block mb-1.5">
                    <i class="fa-regular fa-user mr-1 text-slate-400"></i> Billed & Shipped To:
                </span>
                <div class="text-base font-extrabold text-slate-900">{{ $order->customer_name }}</div>
                <div class="text-slate-600 mt-0.5 flex items-center gap-1.5">
                    <i class="fa-regular fa-envelope text-[11px] text-slate-400"></i>
                    <span>{{ $order->customer_email }}</span>
                </div>
                <div class="text-slate-600 mt-0.5 flex items-center gap-1.5">
                    <i class="fa-solid fa-phone text-[11px] text-slate-400"></i>
                    <span class="font-semibold">{{ $order->customer_phone }}</span>
                </div>
                <div class="text-slate-600 mt-2 bg-slate-50 p-2.5 rounded-lg border border-slate-100 leading-relaxed">
                    <span class="font-bold text-slate-700 text-[11px] block mb-0.5">Delivery Address:</span>
                    {{ $order->street_address }}<br>
                    {{ $order->district }}, {{ $order->division }} @if($order->postal_code)- {{ $order->postal_code }} @endif
                </div>
            </div>

            <!-- Right: Payment & Transaction Details -->
            <div class="sm:text-right space-y-2">
                <span class="font-extrabold uppercase tracking-wider text-slate-400 text-[10px] block mb-1.5">
                    <i class="fa-regular fa-credit-card mr-1 text-slate-400"></i> Payment Information:
                </span>
                
                <div class="flex sm:justify-end justify-between items-center gap-2">
                    <span class="text-slate-500">Payment Method:</span>
                    <strong class="text-slate-900 uppercase">
                        {{ $order->payment_method === 'cod' ? 'Cash on Delivery (COD)' : 'DGePay Online Gateway' }}
                    </strong>
                </div>

                <div class="flex sm:justify-end justify-between items-center gap-2">
                    <span class="text-slate-500">Payment Status:</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ $order->payment_status }}
                    </span>
                </div>

                @if($order->dgepay_channel)
                    <div class="flex sm:justify-end justify-between items-center gap-2">
                        <span class="text-slate-500">Channel / Network:</span>
                        <strong class="text-slate-900">{{ $order->dgepay_channel }}</strong>
                    </div>
                @endif

                @php
                    $primaryTrx = $order->dgepay_txn_number ?? $order->dgepay_unique_txn_id;
                @endphp
                @if($primaryTrx)
                    <div class="flex sm:justify-end justify-between items-center gap-2">
                        <span class="text-slate-500">Gateway Trx ID:</span>
                        <span class="font-mono font-bold text-slate-900">{{ $primaryTrx }}</span>
                    </div>
                @endif

                @if($order->dgepay_third_party_txn_number)
                    <div class="flex sm:justify-end justify-between items-center gap-2">
                        <span class="text-slate-500">MFS/Bank Trx ID:</span>
                        <span class="font-mono font-bold text-slate-900">{{ $order->dgepay_third_party_txn_number }}</span>
                    </div>
                @endif

                <div class="flex sm:justify-end justify-between items-center gap-2 text-slate-500">
                    <span>Paid / Settled At:</span>
                    <strong class="text-slate-800">{{ ($order->paid_at ?? $order->created_at)->format('d M, Y h:i A') }}</strong>
                </div>
            </div>
        </div>

        <!-- MIDDLE SECTION: Itemized Table -->
        <div class="py-6 border-b border-slate-200">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-y border-slate-200 text-slate-600 font-bold uppercase text-[10px] tracking-wider">
                        <th class="py-3 px-3 w-12 text-center">#</th>
                        <th class="py-3 px-3">Item & Description</th>
                        <th class="py-3 px-3 text-center">Type</th>
                        <th class="py-3 px-3 text-center">Qty</th>
                        <th class="py-3 px-3 text-right">Unit Price</th>
                        <th class="py-3 px-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($order->items as $index => $item)
                        <tr>
                            <td class="py-3.5 px-3 text-center text-slate-400 font-bold">
                                {{ $index + 1 }}
                            </td>
                            <td class="py-3.5 px-3">
                                <div class="font-extrabold text-slate-900 text-sm">
                                    {{ $item->title }}
                                </div>
                                @if($item->plan)
                                    <div class="text-[11px] text-slate-500 font-medium mt-0.5">
                                        Plan: <span class="font-semibold text-slate-700">{{ $item->plan->name ?? $item->plan->title }}</span>
                                    </div>
                                @endif
                            </td>
                            <td class="py-3.5 px-3 text-center">
                                <span class="capitalize px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                                    {{ $item->product_type }}
                                </span>
                            </td>
                            <td class="py-3.5 px-3 text-center font-extrabold text-slate-800">
                                {{ $item->quantity }}
                            </td>
                            <td class="py-3.5 px-3 text-right text-slate-600 font-medium">
                                ৳{{ number_format($item->unit_price, 2) }}
                            </td>
                            <td class="py-3.5 px-3 text-right font-extrabold text-slate-900 text-sm">
                                ৳{{ number_format($item->subtotal, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- MIDDLE / LOWER SECTION: Calculation Summary & Order Notes -->
        <div class="pt-6 flex flex-col sm:flex-row justify-between items-start gap-8">
            <!-- Left: Order Notes / Terms Notice -->
            <div class="text-xs text-slate-500 max-w-sm space-y-3">
                @if($order->notes)
                    <div class="p-3 bg-amber-50/70 border border-amber-200/80 rounded-lg text-amber-900">
                        <span class="font-bold text-[11px] uppercase tracking-wider block mb-1">Customer Order Note:</span>
                        <p class="italic leading-relaxed">"{{ $order->notes }}"</p>
                    </div>
                @endif

                <div>
                    <span class="font-extrabold text-slate-800 uppercase tracking-wider block text-[10px] mb-1">Terms & Conditions:</span>
                    <p class="leading-relaxed text-[11px] text-slate-500">
                        All digital subscriptions & serial keys are verified upon delivery. For warranty support, return requests, or replacement assistance, open a support ticket from your DigiGo account.
                    </p>
                </div>
            </div>

            <!-- Right: Subtotal, Discounts, Charges, Grand Total -->
            <div class="w-full sm:w-72 space-y-2 text-xs">
                <div class="flex justify-between text-slate-600">
                    <span>Subtotal:</span>
                    <span class="font-bold text-slate-900">৳{{ number_format($order->subtotal, 2) }}</span>
                </div>
                
                @if($order->discount > 0)
                    <div class="flex justify-between text-emerald-600 font-semibold">
                        <span>Discount {{ $order->coupon_code ? '(' . $order->coupon_code . ')' : '' }}:</span>
                        <span>-৳{{ number_format($order->discount, 2) }}</span>
                    </div>
                @endif

                <div class="flex justify-between text-slate-600">
                    <span>Delivery Charge:</span>
                    <span class="font-bold text-slate-900">
                        {{ $order->delivery_charge > 0 ? '৳' . number_format($order->delivery_charge, 2) : 'FREE' }}
                    </span>
                </div>

                @if(($order->shipping_charge ?? 0) > 0)
                    <div class="flex justify-between text-slate-600">
                        <span>Product Shipping:</span>
                        <span class="font-bold text-slate-900">
                            ৳{{ number_format($order->shipping_charge, 2) }}
                        </span>
                    </div>
                @endif

                <div class="pt-3 border-t-2 border-slate-900 flex justify-between items-baseline text-sm font-black text-slate-900">
                    <span>Grand Total:</span>
                    <span class="text-2xl text-brand-600 font-extrabold">৳{{ number_format($order->total, 2) }}</span>
                </div>

                <div class="text-[11px] text-slate-400 text-right">
                    <span>(Amount inclusive of all applicable VAT/Taxes)</span>
                </div>
            </div>
        </div>

        <!-- FOOTER SECTION: Authorized Signature & Company Verification Info -->
        <div class="mt-14 pt-8 border-t border-slate-200">
            <div class="flex flex-col sm:flex-row items-end justify-between gap-8">
                <!-- Contact info & website -->
                <div class="text-[11px] text-slate-500 space-y-1">
                    <p class="font-bold text-slate-700">Thank you for choosing {{ setting('site_name', 'DigiGo') }}!</p>
                    <p>Web: <a href="{{ url('/') }}" class="text-brand-600 underline">{{ url('/') }}</a></p>
                    <p>Support Hotline: {{ setting('topbar_support_phone', '+8809611678088') }}</p>
                    <p class="text-[10px] text-slate-400">Generated on {{ now()->format('l, d F Y - h:i A') }}</p>
                </div>

                <!-- Signature -->
                <div class="text-center sm:text-right min-w-[180px]">
                    <div class="w-40 border-b border-slate-400 sm:ml-auto mb-1.5 h-12 flex items-end justify-center sm:justify-end">
                        <span class="font-serif italic text-slate-600 text-sm opacity-70">DigiGo Authorized</span>
                    </div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-700 block">Authorized Signature</span>
                    <span class="text-[9px] text-slate-400">DigiGo Digital Commerce</span>
                </div>
            </div>
        </div>

    </div>

</body>
</html>

