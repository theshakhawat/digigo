@extends('layouts.site')

@section('title', 'Secure Checkout - DigiGo')

@section('content')
<div class="max-w-[1440px] mx-auto px-4 sm:px-6 py-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-6">
        <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Home</a>
        <span>/</span>
        <a href="{{ route('cart') }}" class="hover:text-blue-600 transition">Cart</a>
        <span>/</span>
        <span class="text-slate-900 font-semibold">Checkout</span>
    </nav>

    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-8">
        Checkout & Payment
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Customer Information & Payment Method (Col 7) -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Step 1: Contact / Delivery Information -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs flex items-center justify-center font-bold">1</span>
                    Customer & Delivery Information
                </h2>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Your Full Name *</label>
                            <input type="text" value="{{ auth()->check() ? auth()->user()->name : '' }}" placeholder="e.g. John Doe" class="w-full h-11 px-3 rounded-lg border border-slate-300 text-sm focus:outline-none focus:border-blue-600">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Address (To receive licenses) *</label>
                            <input type="email" value="{{ auth()->check() ? auth()->user()->email : '' }}" placeholder="name@example.com" class="w-full h-11 px-3 rounded-lg border border-slate-300 text-sm focus:outline-none focus:border-blue-600">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">WhatsApp / Contact Phone *</label>
                        <input type="tel" value="{{ auth()->check() ? auth()->user()->phone : '' }}" placeholder="017XXXXXXXX" class="w-full h-11 px-3 rounded-lg border border-slate-300 text-sm focus:outline-none focus:border-blue-600">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Order Notes (Optional)</label>
                        <textarea rows="2" placeholder="Any specific email or instructions for the subscription activation..." class="w-full p-3 rounded-lg border border-slate-300 text-sm focus:outline-none focus:border-blue-600"></textarea>
                    </div>
                </div>
            </div>

            <!-- Step 2: Payment Method Selection -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs flex items-center justify-center font-bold">2</span>
                    Select Instant Payment Gateway
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- bKash -->
                    <label class="flex items-center gap-3 p-4 rounded-xl border-2 border-blue-600 bg-blue-50/50 cursor-pointer">
                        <input type="radio" name="payment_method" value="bkash" checked class="accent-blue-600 w-4 h-4">
                        <div class="flex-1">
                            <span class="block font-bold text-slate-900 text-sm">bKash Online</span>
                            <span class="text-xs text-slate-500">Instant automatic verification</span>
                        </div>
                        <span class="px-2 py-1 rounded bg-pink-600 text-white text-[11px] font-bold">bKash</span>
                    </label>

                    <!-- Nagad -->
                    <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:border-slate-300 cursor-pointer transition">
                        <input type="radio" name="payment_method" value="nagad" class="accent-blue-600 w-4 h-4">
                        <div class="flex-1">
                            <span class="block font-bold text-slate-900 text-sm">Nagad Payment</span>
                            <span class="text-xs text-slate-500">Direct gateway</span>
                        </div>
                        <span class="px-2 py-1 rounded bg-orange-600 text-white text-[11px] font-bold">Nagad</span>
                    </label>

                    <!-- Rocket / Upay -->
                    <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:border-slate-300 cursor-pointer transition">
                        <input type="radio" name="payment_method" value="rocket" class="accent-blue-600 w-4 h-4">
                        <div class="flex-1">
                            <span class="block font-bold text-slate-900 text-sm">Rocket / Upay</span>
                            <span class="text-xs text-slate-500">Mobile financial service</span>
                        </div>
                        <span class="px-2 py-1 rounded bg-purple-700 text-white text-[11px] font-bold">Rocket</span>
                    </label>

                    <!-- Credit / Debit Card -->
                    <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:border-slate-300 cursor-pointer transition">
                        <input type="radio" name="payment_method" value="card" class="accent-blue-600 w-4 h-4">
                        <div class="flex-1">
                            <span class="block font-bold text-slate-900 text-sm">Debit / Credit Card</span>
                            <span class="text-xs text-slate-500">VISA, Mastercard</span>
                        </div>
                        <span class="px-2 py-1 rounded bg-blue-700 text-white text-[11px] font-bold">VISA</span>
                    </label>
                </div>
            </div>

        </div>

        <!-- Right: Order Review & Confirm (Col 5) -->
        <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
            <h2 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-200">
                Order Review
            </h2>

            <div class="divide-y divide-slate-100 text-sm">
                <div class="py-3 flex items-center justify-between">
                    <div>
                        <span class="font-bold text-slate-800 block">ChatGPT Plus (1 Month) × 1</span>
                        <span class="text-xs text-slate-400">Private Account</span>
                    </div>
                    <span class="font-bold text-slate-800">৳1,450</span>
                </div>
                <div class="py-3 flex items-center justify-between">
                    <div>
                        <span class="font-bold text-slate-800 block">Canva Pro Lifetime × 2</span>
                        <span class="text-xs text-slate-400">Pro License</span>
                    </div>
                    <span class="font-bold text-slate-800">৳598</span>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-200 space-y-2 text-sm">
                <div class="flex justify-between text-slate-600">
                    <span>Subtotal</span>
                    <span class="font-bold text-slate-900">৳{{ number_format($orderSummary['subtotal']) }}</span>
                </div>
                <div class="flex justify-between text-emerald-600 font-semibold">
                    <span>Promo Discount</span>
                    <span>-৳{{ number_format($orderSummary['discount']) }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Delivery Charge</span>
                    <span class="text-emerald-600 font-bold">FREE (Instant Delivery)</span>
                </div>
                <div class="pt-4 border-t border-slate-200 flex justify-between items-baseline font-extrabold">
                    <span class="text-base text-slate-900">Total Payable</span>
                    <span class="text-2xl text-blue-700">৳{{ number_format($orderSummary['total']) }}</span>
                </div>
            </div>

            <!-- Complete Order Button -->
            <button type="button" class="w-full h-12 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold flex items-center justify-center gap-2 shadow-lg shadow-blue-600/25 transition">
                <i class="fa-solid fa-lock text-sm"></i> Confirm & Pay ৳{{ number_format($orderSummary['total']) }}
            </button>

            <p class="text-[11px] text-slate-400 text-center leading-relaxed">
                By placing this order, you agree to DigiGo’s <a href="#" class="underline">Terms of Service</a> and <a href="#" class="underline">Privacy Policy</a>. Credentials are automatically delivered to your email.
            </p>
        </div>

    </div>

</div>
@endsection
