@extends('layouts.site')

@section('title', 'Shopping Cart - DigiGo')

@section('content')
<div class="max-w-[1440px] mx-auto px-4 sm:px-6 py-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-6">
        <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Home</a>
        <span>/</span>
        <span class="text-slate-900 font-semibold">Shopping Cart</span>
    </nav>

    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-8">
        Your Shopping Cart ({{ count($cartItems) }})
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Cart Items Table (Col 8) -->
        <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 overflow-hidden">
            <div class="divide-y divide-slate-200">
                @foreach($cartItems as $item)
                    <div class="py-6 first:pt-0 last:pb-0 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        
                        <!-- Product Info -->
                        <div class="flex items-center gap-4 flex-1">
                            <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-20 h-20 rounded-xl object-cover border border-slate-200 shrink-0">
                            <div>
                                <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider">{{ $item['category'] }}</span>
                                <h3 class="font-bold text-slate-900 text-base leading-snug hover:text-blue-600 transition">
                                    <a href="{{ route('product.show', $item['slug']) }}">{{ $item['title'] }}</a>
                                </h3>
                                <p class="text-xs text-slate-400 mt-1">Instant Digital License</p>
                            </div>
                        </div>

                        <!-- Price, Qty & Subtotal -->
                        <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto pt-2 sm:pt-0 border-t sm:border-0 border-slate-100">
                            <div class="text-right">
                                <span class="block font-bold text-slate-900 text-lg">৳{{ number_format($item['price']) }}</span>
                                <span class="text-xs text-slate-400">Qty: {{ $item['quantity'] }}</span>
                            </div>

                            <button type="button" class="w-9 h-9 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center transition" title="Remove item">
                                <i class="fa-regular fa-trash-can text-sm"></i>
                            </button>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Cart Buttons -->
            <div class="pt-6 border-t border-slate-200 mt-6 flex flex-wrap items-center justify-between gap-4">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-700 transition">
                    <i class="fa-solid fa-arrow-left"></i> Continue Shopping
                </a>
                <button type="button" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
                    Clear Shopping Cart
                </button>
            </div>
        </div>

        <!-- Right: Order Summary Sidebar (Col 4) -->
        <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
            <h2 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-200">
                Order Summary
            </h2>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between text-slate-600">
                    <span>Subtotal</span>
                    <span class="font-bold text-slate-900">৳{{ number_format($subtotal) }}</span>
                </div>
                <div class="flex justify-between text-emerald-600 font-semibold">
                    <span>Discount Coupon</span>
                    <span>-৳{{ number_format($discount) }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Delivery Charge</span>
                    <span class="text-emerald-600 font-bold">FREE (Digital)</span>
                </div>
                <div class="pt-4 border-t border-slate-200 flex justify-between items-baseline text-slate-900 font-extrabold text-lg">
                    <span>Total Amount</span>
                    <span class="text-2xl text-blue-700">৳{{ number_format($total) }}</span>
                </div>
            </div>

            <!-- Promo code input -->
            <div class="pt-2">
                <div class="flex gap-2">
                    <input type="text" placeholder="Promo code" class="flex-1 h-10 px-3 rounded-lg border border-slate-300 text-sm focus:outline-none focus:border-blue-600 uppercase font-semibold">
                    <button type="button" class="px-4 h-10 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs font-bold transition">Apply</button>
                </div>
            </div>

            <!-- Proceed to Checkout Button -->
            <a href="{{ route('checkout') }}" class="w-full h-12 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold flex items-center justify-center gap-2 shadow-lg shadow-blue-600/25 transition">
                Proceed to Checkout <i class="fa-solid fa-arrow-right"></i>
            </a>

            <!-- Security perks -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-center gap-4 text-xs text-slate-400">
                <span><i class="fa-solid fa-lock text-slate-500 mr-1"></i> SSL 256-bit Encrypted</span>
                <span><i class="fa-solid fa-shield text-slate-500 mr-1"></i> Buyer Protection</span>
            </div>
        </div>

    </div>

</div>
@endsection
