@extends('layouts.admin')

@section('title', 'Order #' . $order->order_number . ' - DigiGo')

@section('admin_content')
<!-- Page Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.orders.index') }}" class="w-9 h-9 rounded-lg border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800 transition">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white flex items-center gap-2.5">
                <span>Order #{{ $order->order_number }}</span>
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Placed on {{ $order->created_at->format('l, d F Y - h:i A') }} ({{ $order->created_at->diffForHumans() }})
            </p>
        </div>
    </div>

    <div class="flex items-center gap-2.5">
        <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold rounded-lg shadow-sm transition">
            <i class="fa-solid fa-print"></i>
            <span>Print Invoice</span>
        </a>
        <a href="{{ route('admin.orders.edit', $order->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs sm:text-sm font-semibold rounded-lg shadow-sm transition">
            <i class="fa-regular fa-pen-to-square"></i>
            <span>Edit Order</span>
        </a>
        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this order?');" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs sm:text-sm font-semibold rounded-lg shadow-sm transition cursor-pointer">
                <i class="fa-regular fa-trash-can"></i>
                <span>Delete</span>
            </button>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-2 text-sm dark:bg-emerald-950/50 dark:border-emerald-800 dark:text-emerald-300">
        <i class="fa-solid fa-circle-check text-emerald-600"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

    <!-- Left Column: Items & Summary (Col 8) -->
    <div class="lg:col-span-8 space-y-6">

        <!-- Ordered Items Table Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h3 class="font-bold text-slate-900 dark:text-white text-sm sm:text-base flex items-center gap-2">
                    <i class="fa-solid fa-box text-brand-600 dark:text-brand-400"></i>
                    <span>Order Items</span>
                </h3>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                    {{ $order->items->count() }} {{ Str::plural('Product', $order->items->count()) }}
                </span>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach($order->items as $item)
                    <div class="p-4 sm:p-5 flex items-center gap-4">
                        <div class="w-14 h-14 rounded-xl border border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-800 overflow-hidden shrink-0">
                            @if($item->image)
                                <img src="{{ $item->image }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                    <i class="fa-solid fa-box text-sm"></i>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-slate-900 dark:text-white text-sm truncate">
                                {{ $item->title }}
                            </h4>
                            <div class="flex items-center gap-2 text-xs text-slate-500 mt-1">
                                <span class="capitalize px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-medium text-[10px]">
                                    {{ $item->product_type }}
                                </span>
                                <span>&bull;</span>
                                <span>Qty: <strong>{{ $item->quantity }}</strong></span>
                                <span>&bull;</span>
                                <span>৳{{ number_format($item->unit_price, 2) }} each</span>
                            </div>
                        </div>

                        <div class="text-right shrink-0">
                            <span class="font-extrabold text-slate-900 dark:text-white text-base">
                                ৳{{ number_format($item->subtotal, 2) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Price Breakdown Summary -->
            <div class="p-5 bg-slate-50 dark:bg-slate-800/40 border-t border-slate-100 dark:border-slate-800 space-y-2 text-sm">
                <div class="flex justify-between text-slate-600 dark:text-slate-400">
                    <span>Subtotal</span>
                    <span class="font-semibold text-slate-900 dark:text-white">৳{{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->discount > 0)
                    <div class="flex justify-between text-emerald-600 font-semibold">
                        <span>Discount (Coupon: {{ $order->coupon_code }})</span>
                        <span>-৳{{ number_format($order->discount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between text-slate-600 dark:text-slate-400">
                    <span>Delivery Charge (Region)</span>
                    <span class="font-semibold text-slate-900 dark:text-white">
                        {{ $order->delivery_charge > 0 ? '৳' . number_format($order->delivery_charge, 2) : 'FREE' }}
                    </span>
                </div>
                @if(($order->shipping_charge ?? 0) > 0)
                    <div class="flex justify-between text-slate-600 dark:text-slate-400">
                        <span>Product Shipping Charge</span>
                        <span class="font-semibold text-slate-900 dark:text-white">
                            ৳{{ number_format($order->shipping_charge, 2) }}
                        </span>
                    </div>
                @endif
                <div class="pt-3 border-t border-slate-200 dark:border-slate-700 flex justify-between items-baseline font-extrabold">
                    <span class="text-base text-slate-900 dark:text-white">Total Amount</span>
                    <span class="text-2xl text-brand-600 dark:text-brand-400">৳{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        @if($order->notes)
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-xs">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Order Notes from Customer</h3>
                <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed italic">
                    "{{ $order->notes }}"
                </p>
            </div>
        @endif

    </div>

    <!-- Right Column: Status & Customer Info (Col 4) -->
    <div class="lg:col-span-4 space-y-6">

        <!-- Status Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800">
                <h3 class="font-bold text-slate-900 dark:text-white text-sm">
                    Order & Payment Status
                </h3>
                <span class="text-[10px] font-semibold text-slate-400">Instant Update</span>
            </div>

            <!-- Direct Quick Change Form for Order Status -->
            <form action="{{ route('admin.orders.quick-status', $order->id) }}" method="POST" class="space-y-1.5">
                @csrf
                @method('PATCH')
                <div class="flex items-center justify-between">
                    <label for="quick_order_status" class="block text-xs font-semibold text-slate-500">Order Fulfillment Status</label>
                    @php
                        $statusStyles = [
                            'confirmed' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300',
                            'completed' => 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300',
                            'pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300',
                            'processing' => 'bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300',
                            'cancelled' => 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300',
                        ];
                    @endphp
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $statusStyles[$order->order_status] ?? 'bg-slate-100' }}">
                        {{ $order->order_status }}
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <select name="order_status" id="quick_order_status" onchange="this.form.submit()" class="w-full px-3 py-2 text-xs font-semibold bg-slate-50 border border-slate-300 rounded-lg text-slate-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white cursor-pointer">
                        <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $order->order_status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>Completed / Delivered</option>
                        <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </form>

            <!-- Direct Quick Change Form for Payment Status -->
            <form action="{{ route('admin.orders.quick-status', $order->id) }}" method="POST" class="space-y-1.5 pt-2 border-t border-slate-100 dark:border-slate-800/60">
                @csrf
                @method('PATCH')
                <div class="flex items-center justify-between">
                    <label for="quick_payment_status" class="block text-xs font-semibold text-slate-500">Payment Status</label>
                    <div class="flex items-center gap-1.5">
                        @if($order->payment_status === 'paid')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                                <i class="fa-solid fa-circle-check text-[9px] mr-0.5"></i> PAID
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300">
                                {{ $order->payment_status }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <select name="payment_status" id="quick_payment_status" onchange="this.form.submit()" class="w-full px-3 py-2 text-xs font-semibold bg-slate-50 border border-slate-300 rounded-lg text-slate-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white cursor-pointer">
                        <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="cancelled" {{ $order->payment_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                @if($order->paid_at)
                    <div class="text-[11px] text-slate-400 mt-1">Paid on: {{ $order->paid_at->format('d M Y, h:i A') }}</div>
                @endif
            </form>

            <div class="pt-2 border-t border-slate-100 dark:border-slate-800 text-xs text-slate-600 dark:text-slate-400 space-y-1.5">
                <div class="flex justify-between">
                    <span class="text-slate-400">Method:</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200 uppercase">
                        {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'DGePay Online' }}
                    </span>
                </div>
                @if($order->dgepay_channel)
                    <div class="flex justify-between">
                        <span class="text-slate-400">Gateway Channel:</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $order->dgepay_channel }}</span>
                    </div>
                @endif
                @if($order->dgepay_txn_number)
                    <div class="flex justify-between">
                        <span class="text-slate-400">Transaction ID:</span>
                        <span class="font-mono text-slate-800 dark:text-slate-200">{{ $order->dgepay_txn_number }}</span>
                    </div>
                @endif
                @if($order->dgepay_third_party_txn_number)
                    <div class="flex justify-between">
                        <span class="text-slate-400">MFS/Bank Txn:</span>
                        <span class="font-mono text-slate-800 dark:text-slate-200">{{ $order->dgepay_third_party_txn_number }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Customer & Delivery Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-xs space-y-3">
            <h3 class="font-bold text-slate-900 dark:text-white text-sm pb-2 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <span>Customer Details</span>
                @if($order->user_id)
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-50 text-blue-700">Registered</span>
                @else
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">Guest</span>
                @endif
            </h3>

            <div class="text-xs space-y-2">
                <div>
                    <span class="text-slate-400 block text-[11px]">Full Name</span>
                    <span class="font-bold text-slate-900 dark:text-white text-sm">{{ $order->customer_name }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px]">Email</span>
                    <a href="mailto:{{ $order->customer_email }}" class="font-semibold text-brand-600 dark:text-brand-400 hover:underline">
                        {{ $order->customer_email }}
                    </a>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px]">Phone / WhatsApp</span>
                    <a href="tel:{{ $order->customer_phone }}" class="font-semibold text-slate-800 dark:text-slate-200 hover:underline">
                        {{ $order->customer_phone }}
                    </a>
                </div>
                <div class="pt-2 border-t border-slate-100 dark:border-slate-800">
                    <span class="text-slate-400 block text-[11px]">Delivery Destination</span>
                    <p class="font-semibold text-slate-800 dark:text-slate-200 leading-relaxed mt-0.5">
                        {{ $order->street_address }}<br>
                        {{ $order->district }}, {{ $order->division }} @if($order->postal_code)- {{ $order->postal_code }} @endif
                    </p>
                </div>
            </div>
        </div>

        @if($order->digital_target_email || $order->digital_target_phone || $order->digital_account_notes)
            <!-- Digital Subscription Target Card -->
            <div class="bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-950/40 dark:to-blue-950/30 border-2 border-indigo-200 dark:border-indigo-800 rounded-xl p-5 shadow-xs space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-indigo-200 dark:border-indigo-800">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-wand-magic-sparkles text-indigo-600 dark:text-indigo-400"></i>
                        <h3 class="font-bold text-indigo-950 dark:text-indigo-200 text-sm">
                            Digital Subscription Target
                        </h3>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-indigo-600 text-white uppercase tracking-wider">
                        Activation Info
                    </span>
                </div>

                <div class="text-xs space-y-2.5">
                    <div>
                        <span class="text-slate-500 dark:text-slate-400 block text-[11px] font-semibold">Target Gmail / Email for Activation:</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="font-mono font-bold text-indigo-700 dark:text-indigo-300 text-sm select-all">
                                {{ $order->digital_target_email ?: 'Same as account email (' . $order->customer_email . ')' }}
                            </span>
                        </div>
                    </div>

                    @if($order->digital_target_phone)
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 block text-[11px] font-semibold">Target Contact / WhatsApp:</span>
                            <span class="font-mono font-bold text-slate-900 dark:text-slate-100 select-all">
                                {{ $order->digital_target_phone }}
                            </span>
                        </div>
                    @endif

                    @if($order->digital_account_notes)
                        <div class="pt-2 border-t border-indigo-200 dark:border-indigo-800">
                            <span class="text-slate-500 dark:text-slate-400 block text-[11px] font-semibold">Customer Instructions:</span>
                            <p class="font-medium text-slate-800 dark:text-slate-200 italic mt-0.5">
                                "{{ $order->digital_account_notes }}"
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

    </div>

</div>
@endsection

