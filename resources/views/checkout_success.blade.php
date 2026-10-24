@extends('layouts.site')

@php
    $isCancelled = ($order->order_status === 'cancelled' || $order->payment_status === 'cancelled');
    $isFailed = ($order->payment_status === 'failed');
    $isPending = ($order->order_status === 'pending');
@endphp

@section('title', ($isCancelled ? 'Order Cancelled' : ($isFailed ? 'Payment Failed' : 'Order Confirmed')) . ' - DigiGo')

@section('content')
<div class="max-w-[850px] mx-auto px-4 sm:px-6 py-12">
    
    <!-- Status Banner Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-10 text-center mb-8">
        @if($isCancelled)
            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-5 text-2xl sm:text-3xl shadow-inner">
                <i class="fa-solid fa-xmark"></i>
            </div>
            
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 mb-3">
                <i class="fa-solid fa-circle-xmark text-[10px]"></i> Order Cancelled
            </span>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-2">
                Order & Payment Cancelled
            </h1>
            <p class="text-sm text-slate-500 max-w-md mx-auto mb-6">
                This order was cancelled during payment. No funds were captured. You can place a new order or retry payment anytime.
            </p>
        @elseif($isFailed)
            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-5 text-2xl sm:text-3xl shadow-inner">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 mb-3">
                <i class="fa-solid fa-triangle-exclamation text-[10px]"></i> Payment Failed
            </span>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-2">
                Payment Was Not Completed
            </h1>
            <p class="text-sm text-slate-500 max-w-md mx-auto mb-6">
                We could not verify payment for this order. Please retry or contact support if funds were deducted.
            </p>
        @else
            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-5 text-2xl sm:text-3xl shadow-inner">
                <i class="fa-solid fa-check"></i>
            </div>
            
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 mb-3">
                <i class="fa-solid fa-circle-check text-[10px]"></i> Order Confirmed
            </span>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-2">
                Thank you for your order!
            </h1>
            <p class="text-sm text-slate-500 max-w-md mx-auto mb-6">
                We have sent an invoice copy and full purchase details to <strong class="text-slate-800">{{ $order->customer_email }}</strong>.
            </p>
        @endif

        <!-- Order Summary Pills -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100 text-left text-xs">
            <div>
                <span class="text-slate-400 font-semibold block uppercase text-[10px]">Order Number</span>
                <span class="font-bold text-slate-900 font-mono text-sm">#{{ $order->order_number }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-semibold block uppercase text-[10px]">Date</span>
                <span class="font-bold text-slate-900">{{ $order->created_at->format('d M, Y') }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-semibold block uppercase text-[10px]">Payment Method</span>
                <span class="font-bold text-slate-900 uppercase">
                    {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'DGePay Online' }}
                </span>
            </div>
            <div>
                <span class="text-slate-400 font-semibold block uppercase text-[10px]">Total Amount</span>
                <span class="font-extrabold text-blue-700 text-sm">৳{{ number_format($order->total, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Order Items & Invoice Details -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
        <h2 class="text-lg font-bold text-slate-900 flex items-center justify-between pb-4 border-b border-slate-100">
            <span>Ordered Items</span>
            <span class="text-xs font-medium text-slate-500">{{ $order->items->count() }} items</span>
        </h2>

        <div class="divide-y divide-slate-100">
            @foreach($order->items as $item)
                <div class="py-4 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl border border-slate-200 bg-slate-50 overflow-hidden shrink-0">
                        @if($item->image)
                            <img src="{{ $item->image }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                <i class="fa-solid fa-box text-sm"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-slate-900 text-sm truncate">{{ $item->title }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5 capitalize">
                            Type: {{ $item->product_type }} &bull; Qty: {{ $item->quantity }} &bull; ৳{{ number_format($item->unit_price, 2) }}
                        </p>
                    </div>
                    <span class="font-extrabold text-slate-900 text-sm shrink-0">
                        ৳{{ number_format($item->subtotal, 2) }}
                    </span>
                </div>
            @endforeach
        </div>

        <!-- Breakdown -->
        <div class="pt-4 border-t border-slate-100 space-y-2 text-sm">
            <div class="flex justify-between text-slate-600">
                <span>Subtotal</span>
                <span class="font-bold text-slate-900">৳{{ number_format($order->subtotal, 2) }}</span>
            </div>
            @if($order->discount > 0)
                <div class="flex justify-between text-emerald-600 font-semibold">
                    <span>Discount ({{ $order->coupon_code }})</span>
                    <span>-৳{{ number_format($order->discount, 2) }}</span>
                </div>
            @endif
            <div class="flex justify-between text-slate-600">
                <span>Delivery Charge</span>
                <span class="font-bold text-slate-900">
                    {{ $order->delivery_charge > 0 ? '৳' . number_format($order->delivery_charge, 2) : 'FREE' }}
                </span>
            </div>
            <div class="flex justify-between items-baseline pt-3 border-t border-slate-200 text-base font-extrabold text-slate-900">
                <span>Total Payable</span>
                <span class="text-2xl text-blue-700">৳{{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <!-- Delivery Address Box -->
        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80 text-xs text-slate-600 space-y-1">
            <span class="font-bold text-slate-800 uppercase block text-[11px] mb-1">
                <i class="fa-solid fa-location-dot text-blue-600 mr-1"></i> Delivery Address
            </span>
            <p class="font-bold text-slate-900 text-sm">{{ $order->customer_name }} ({{ $order->customer_phone }})</p>
            <p>{{ $order->street_address }}</p>
            <p>{{ $order->district }}, {{ $order->division }} @if($order->postal_code)- {{ $order->postal_code }} @endif</p>
            @if($order->notes)
                <p class="pt-1.5 border-t border-slate-200 text-slate-500 italic">Note: {{ $order->notes }}</p>
            @endif
        </div>

        <!-- Actions -->
        <div class="pt-4 flex flex-col sm:flex-row items-center gap-3">
            @auth
                <a href="{{ route('user.orders.invoice', $order->id) }}" target="_blank" class="w-full sm:flex-1 h-12 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold flex items-center justify-center gap-2 transition text-sm shadow-xs">
                    <i class="fa-solid fa-print"></i>
                    <span>Print Invoice</span>
                </a>
                <a href="{{ route('user.orders') }}" class="w-full sm:flex-1 h-12 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold flex items-center justify-center gap-2 transition text-sm">
                    <i class="fa-regular fa-file-lines"></i>
                    <span>View In My Orders</span>
                </a>
            @endauth
            <a href="{{ url('/') }}" class="w-full sm:flex-1 h-12 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold flex items-center justify-center gap-2 transition text-sm">
                <i class="fa-solid fa-store"></i>
                <span>Continue Shopping</span>
            </a>
        </div>
    </div>

</div>
@endsection

