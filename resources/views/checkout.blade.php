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

    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-8 flex items-center gap-2.5">
        <i class="fa-solid fa-shield-halved text-blue-600"></i>
        <span>Secure Checkout</span>
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Customer Information & Payment Method (Col 7) -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Step 1: Contact / Delivery Information -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
                <div class="flex items-center justify-between mb-6 flex-wrap gap-2">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs flex items-center justify-center font-bold">1</span>
                        <span>Customer & Delivery Information</span>
                    </h2>

                    @if($savedAddress)
                        <div class="flex items-center gap-2 text-xs">
                            <span class="text-slate-500 hidden sm:inline">Saved Profile Address</span>
                            <button type="button"
                                    id="toggleEditAddressBtn"
                                    onclick="toggleEditAddress()"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 font-bold transition cursor-pointer text-xs">
                                <i class="fa-solid fa-pen-to-square text-xs" id="editAddressIcon"></i>
                                <span id="editAddressBtnText">Change / Edit Address</span>
                            </button>
                        </div>
                    @endif
                </div>

                @if($savedAddress)
                    <!-- Saved Address Summary Card -->
                    <div id="savedAddressCard" class="mb-5 p-4 rounded-xl border-2 border-blue-500 bg-blue-50/40 relative">
                        <div class="flex items-start justify-between gap-3">
                            <div class="space-y-1.5 flex-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-bold text-slate-900 text-sm" id="cardFullName">{{ $savedAddress->full_name }}</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-800 tracking-wider">
                                        <i class="fa-solid fa-check text-[9px] mr-0.5"></i> Selected Address
                                    </span>
                                </div>
                                <div class="text-xs text-slate-600 flex items-center gap-3 flex-wrap">
                                    <span><i class="fa-solid fa-phone text-slate-400 mr-1 text-[11px]"></i><span id="cardPhone">{{ $savedAddress->phone }}</span></span>
                                    <span><i class="fa-solid fa-envelope text-slate-400 mr-1 text-[11px]"></i>{{ auth()->user()->email }}</span>
                                </div>
                                <div class="text-xs text-slate-700 pt-1 border-t border-blue-200/60 leading-relaxed">
                                    <i class="fa-solid fa-location-dot text-blue-600 mr-1 text-[11px]"></i>
                                    <span id="cardAddressText">{{ $savedAddress->street_address }}, {{ $savedAddress->district }}, {{ $savedAddress->division }}</span>
                                    @if($savedAddress->postal_code)
                                        - <span id="cardPostalCode">{{ $savedAddress->postal_code }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Address Input Fields Form (Can be toggled or visible) -->
                <div id="addressInputsContainer" class="space-y-4 {{ $savedAddress ? 'hidden' : '' }}">
                    @if($savedAddress)
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <span class="text-xs font-bold text-slate-700 uppercase tracking-wide">Edit Delivery Details</span>
                            <button type="button" onclick="cancelAddressEdit()" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                                <i class="fa-solid fa-xmark mr-1"></i> Use Saved Address
                            </button>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Your Full Name *</label>
                            <input type="text" id="customerName" value="{{ auth()->check() ? ($savedAddress->full_name ?? auth()->user()->name) : '' }}" placeholder="e.g. John Doe" class="w-full h-11 px-3 rounded-lg border border-slate-300 text-sm focus:outline-none focus:border-blue-600 font-medium">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Address (For License & Order) *</label>
                            <input type="email" id="customerEmail" value="{{ auth()->check() ? auth()->user()->email : '' }}" placeholder="name@example.com" class="w-full h-11 px-3 rounded-lg border border-slate-300 text-sm focus:outline-none focus:border-blue-600 font-medium">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">WhatsApp / Contact Phone *</label>
                        <input type="tel" id="customerPhone" value="{{ auth()->check() ? ($savedAddress->phone ?? auth()->user()->phone) : '' }}" placeholder="017XXXXXXXX" class="w-full h-11 px-3 rounded-lg border border-slate-300 text-sm focus:outline-none focus:border-blue-600 font-medium">
                    </div>

                    <!-- Delivery Destination (Division & District) -->
                    <div class="pt-2 border-t border-slate-100">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2 flex items-center justify-between">
                            <span><i class="fa-solid fa-location-dot text-blue-600 mr-1"></i> Delivery Area & Destination</span>
                            <span id="shippingZoneBadge" class="text-[11px] font-bold px-2 py-0.5 rounded {{ $orderSummary['is_inside_dhaka'] ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-800' }}">
                                {{ $orderSummary['is_inside_dhaka'] ? 'Inside Dhaka (৳' . $orderSummary['delivery_charge'] . ')' : 'Outside Dhaka (৳' . $orderSummary['delivery_charge'] . ')' }}
                            </span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Division *</label>
                                <select id="shippingDivision" onchange="onDivisionChange()" class="w-full h-11 px-3 rounded-lg border border-slate-300 text-sm focus:outline-none focus:border-blue-600 bg-white font-medium">
                                    <option value="Dhaka" {{ $initialDivision === 'Dhaka' ? 'selected' : '' }}>Dhaka</option>
                                    <option value="Chattogram" {{ $initialDivision === 'Chattogram' ? 'selected' : '' }}>Chattogram</option>
                                    <option value="Rajshahi" {{ $initialDivision === 'Rajshahi' ? 'selected' : '' }}>Rajshahi</option>
                                    <option value="Khulna" {{ $initialDivision === 'Khulna' ? 'selected' : '' }}>Khulna</option>
                                    <option value="Barishal" {{ $initialDivision === 'Barishal' ? 'selected' : '' }}>Barishal</option>
                                    <option value="Sylhet" {{ $initialDivision === 'Sylhet' ? 'selected' : '' }}>Sylhet</option>
                                    <option value="Rangpur" {{ $initialDivision === 'Rangpur' ? 'selected' : '' }}>Rangpur</option>
                                    <option value="Mymensingh" {{ $initialDivision === 'Mymensingh' ? 'selected' : '' }}>Mymensingh</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">District *</label>
                                <select id="shippingDistrict" onchange="calculateDeliveryCharge()" class="w-full h-11 px-3 rounded-lg border border-slate-300 text-sm focus:outline-none focus:border-blue-600 bg-white font-medium">
                                    <!-- Populated dynamically via JS -->
                                </select>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Full Street Address *</label>
                        <input type="text" id="shippingAddress" value="{{ auth()->check() ? ($savedAddress->street_address ?? '') : '' }}" placeholder="House/Road/Area (e.g. House #12, Road #4, Dhanmondi, Dhaka)" class="w-full h-11 px-3 rounded-lg border border-slate-300 text-sm focus:outline-none focus:border-blue-600">
                    </div>

                    @if($savedAddress)
                        <div class="pt-2">
                            <button type="button" onclick="applyAddressEdits()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-lg transition shadow-sm cursor-pointer">
                                <i class="fa-solid fa-check mr-1"></i> Update & Use This Address
                            </button>
                        </div>
                    @endif
                </div>

                <div class="space-y-4 pt-2">

                    <!-- Digital Subscription Target Details (Shows if cart contains digital goods) -->
                    @if($hasDigital)
                        <div class="p-4 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200/80 space-y-3">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-black uppercase tracking-wider text-blue-900 flex items-center gap-1.5">
                                    <i class="fa-solid fa-wand-magic-sparkles text-blue-600"></i>
                                    <span>Digital Subscription Delivery Destination</span>
                                </label>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-blue-600 text-white">Digital Order</span>
                            </div>
                            <p class="text-[11px] text-blue-800 leading-relaxed">
                                Want this subscription (e.g. YouTube Premium, Netflix, Canva, ChatGPT) activated on a specific email or WhatsApp number different from your account? Provide it below:
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">
                                        Target Gmail / Subscription Email
                                    </label>
                                    <input type="email"
                                           id="digitalTargetEmail"
                                           placeholder="e.g. your_desired_gmail@gmail.com"
                                           value="{{ auth()->check() ? auth()->user()->email : '' }}"
                                           class="w-full h-10 px-3 rounded-lg border border-slate-300 text-xs sm:text-sm bg-white focus:outline-none focus:border-blue-600 font-medium">
                                    <span class="text-[10px] text-slate-500 mt-0.5 block">Admin will invite / activate subscription on this email.</span>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">
                                        Target WhatsApp / Phone (Optional)
                                    </label>
                                    <input type="tel"
                                           id="digitalTargetPhone"
                                           placeholder="e.g. 01XXXXXXXXX"
                                           value="{{ auth()->check() ? auth()->user()->phone : '' }}"
                                           class="w-full h-10 px-3 rounded-lg border border-slate-300 text-xs sm:text-sm bg-white focus:outline-none focus:border-blue-600 font-medium">
                                    <span class="text-[10px] text-slate-500 mt-0.5 block">For instant verification or credentials handover.</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">
                                    Custom Activation Instructions (Optional)
                                </label>
                                <input type="text"
                                       id="digitalAccountNotes"
                                       placeholder="e.g. Existing account / Renew my current profile / Need family slot invitation"
                                       class="w-full h-10 px-3 rounded-lg border border-slate-300 text-xs bg-white focus:outline-none focus:border-blue-600">
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Order Notes (Optional)</label>
                        <textarea id="orderNotes" rows="2" placeholder="Any additional notes for delivery or processing..." class="w-full p-3 rounded-lg border border-slate-300 text-sm focus:outline-none focus:border-blue-600"></textarea>
                    </div>
                </div>
            </div>

            <!-- Step 2: Payment Method Selection (DgePay & Cash on Delivery) -->
            <div id="paymentSelectionContainer" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 {{ ((float) $orderSummary['total']) <= 0 ? 'hidden' : '' }}">
                <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs flex items-center justify-center font-bold">2</span>
                    Select Payment Method
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="paymentMethodsGrid">
                    <!-- Option 1: DgePay (Online Instant Gateway - Default) -->
                    <label class="payment-method-card relative flex items-start gap-3.5 p-4 rounded-xl border-2 border-blue-600 bg-blue-50/50 cursor-pointer transition select-none {{ !$codAllowed ? 'sm:col-span-2' : '' }}">
                        <input type="radio"
                               name="payment_method"
                               value="dgepay"
                               checked
                               onchange="onPaymentMethodChange(this)"
                               class="accent-blue-600 w-4 h-4 mt-1">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-extrabold text-slate-900 text-sm">DgePay</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-600 text-white">Recommended</span>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Pay instantly via bKash, Nagad, Rocket, Upay, Visa, Mastercard & Internet Banking.
                            </p>
                            <div class="flex items-center gap-1.5 mt-2.5">
                                <span class="px-1.5 py-0.5 rounded bg-pink-100 text-pink-700 text-[10px] font-bold">bKash</span>
                                <span class="px-1.5 py-0.5 rounded bg-orange-100 text-orange-700 text-[10px] font-bold">Nagad</span>
                                <span class="px-1.5 py-0.5 rounded bg-purple-100 text-purple-700 text-[10px] font-bold">Rocket</span>
                                <span class="px-1.5 py-0.5 rounded bg-blue-100 text-blue-700 text-[10px] font-bold">Card</span>
                            </div>
                        </div>
                    </label>

                    <!-- Option 2: Cash on Delivery (COD) - Hidden if digital products are present in cart -->
                    <label id="codPaymentOption" class="payment-method-card relative flex items-start gap-3.5 p-4 rounded-xl border border-slate-200 hover:border-slate-300 cursor-pointer transition select-none {{ !$codAllowed ? 'hidden' : '' }}">
                        <input type="radio"
                               name="payment_method"
                               value="cod"
                               id="paymentCodRadio"
                               onchange="onPaymentMethodChange(this)"
                               class="accent-blue-600 w-4 h-4 mt-1">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-extrabold text-slate-900 text-sm">Cash on Delivery (COD)</span>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Pay with cash upon delivery of your physical parcel.
                            </p>
                            <span class="inline-block mt-2 text-[11px] text-amber-700 font-medium">
                                <i class="fa-solid fa-truck text-xs mr-1"></i> Pay when courier arrives
                            </span>
                        </div>
                    </label>
                </div>

                @if(!$codAllowed && $hasDigital)
                    <div id="codDisabledNotice" class="mt-3 p-3 rounded-xl bg-amber-50/70 border border-amber-200/80 text-amber-900 text-xs flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-amber-600 text-sm shrink-0"></i>
                        <span>Cash on Delivery (COD) is unavailable because your cart contains digital software or subscriptions. Please complete payment using DGePay.</span>
                    </div>
                @endif
            </div>

            <!-- Free Order Notice when 100% covered by coupon -->
            <div id="freeOrderNotice" class="bg-emerald-50 rounded-2xl border-2 border-emerald-500 shadow-sm p-6 sm:p-8 {{ ((float) $orderSummary['total']) <= 0 ? 'flex' : 'hidden' }} items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0 text-lg shadow-md shadow-emerald-600/30">
                    <i class="fa-solid fa-gift"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-emerald-950 mb-1">100% Free Order!</h3>
                    <p class="text-xs sm:text-sm text-emerald-800 leading-relaxed">
                        Your coupon discount completely covers the total price. No payment via DGePay is required! Click <strong>"Complete Free Order"</strong> below to instantly confirm your order.
                    </p>
                </div>
            </div>

        </div>

        <!-- Right: Order Review & Products List (Col 5) -->
        <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <h2 class="text-lg font-bold text-slate-900">
                    Order Summary
                </h2>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-50 text-blue-700">
                    {{ $orderSummary['items_count'] }} {{ Str::plural('Item', $orderSummary['items_count']) }}
                </span>
            </div>

            <!-- Products List in Order Review -->
            <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto pr-1">
                @foreach($cartItems as $item)
                    <div class="py-3.5 flex items-center gap-3">
                        <a href="{{ !empty($item['slug']) ? route('product.show', $item['slug']) : '#' }}" target="_blank" class="w-14 h-14 rounded-xl border border-slate-200 bg-slate-50 overflow-hidden shrink-0 hover:opacity-85 transition block">
                            @if(!empty($item['image']))
                                <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                    <i class="fa-solid fa-box text-sm"></i>
                                </div>
                            @endif
                        </a>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-slate-900 text-xs sm:text-sm truncate">
                                <a href="{{ !empty($item['slug']) ? route('product.show', $item['slug']) : '#' }}" target="_blank" class="hover:text-blue-600 transition" title="{{ $item['title'] }}">
                                    {{ $item['title'] }}
                                </a>
                            </h4>
                            <div class="flex items-center gap-2 text-xs text-slate-500 mt-0.5">
                                <span>Qty: {{ $item['quantity'] }}</span>
                                <span>•</span>
                                <span>৳{{ number_format($item['price']) }}</span>
                            </div>
                        </div>
                        <span class="font-extrabold text-slate-900 text-sm shrink-0">
                            ৳{{ number_format($item['price'] * $item['quantity']) }}
                        </span>
                    </div>
                @endforeach
            </div>

            <!-- Coupon Code Section inside Checkout (AJAX) -->
            <div class="pt-4 border-t border-slate-200" id="checkoutCouponContainer">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                    Have a Discount Coupon?
                </label>

                <!-- Coupon alert box for AJAX feedback -->
                <div id="couponAlertBox" class="hidden mb-3 p-2.5 rounded-lg text-xs font-semibold items-center justify-between">
                    <span id="couponAlertText"></span>
                </div>

                <!-- Applied coupon view -->
                <div id="appliedCouponCard" class="{{ !empty($appliedCoupon) ? 'flex' : 'hidden' }} p-3 bg-indigo-50 border border-indigo-200 rounded-xl items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-500">Applied Coupon</span>
                        <p class="font-mono font-extrabold text-sm text-indigo-900" id="appliedCouponDisplay">
                            @if(!empty($appliedCoupon))
                                {{ $appliedCoupon['code'] }} (-৳{{ number_format($appliedCoupon['discount']) }})
                            @endif
                        </p>
                    </div>
                    <button type="button"
                            id="removeCouponBtn"
                            onclick="removeCouponAjax()"
                            class="px-2.5 py-1 text-xs text-rose-600 hover:text-rose-800 font-bold hover:bg-rose-50 rounded transition cursor-pointer">
                        Remove
                    </button>
                </div>

                <!-- Coupon input form -->
                <form id="couponApplyForm" onsubmit="applyCouponAjax(event)" class="{{ !empty($appliedCoupon) ? 'hidden' : 'flex' }} gap-2">
                    <input type="text"
                           id="couponCodeInput"
                           name="code"
                           placeholder="Enter Coupon code"
                           class="flex-1 h-10 px-3 rounded-lg border border-slate-300 text-xs sm:text-sm focus:outline-none focus:border-blue-600 uppercase font-mono font-bold">
                    <button type="submit"
                            id="applyCouponBtn"
                            class="px-4 h-10 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer">
                        <span>Apply</span>
                    </button>
                </form>
            </div>

            <!-- Price Breakdown Calculation (Purely Backend Computed) -->
            <div class="pt-3 border-t border-slate-200 space-y-2.5 text-sm">
                <div class="flex justify-between text-slate-600">
                    <span>Subtotal</span>
                    <span id="summarySubtotal" class="font-bold text-slate-900">৳{{ number_format($orderSummary['subtotal']) }}</span>
                </div>
                
                <div id="summaryDiscountRow" class="{{ $orderSummary['discount'] > 0 ? 'flex' : 'hidden' }} justify-between text-emerald-600 font-semibold">
                    <span>Coupon Discount</span>
                    <span id="summaryDiscountAmount">-৳{{ number_format($orderSummary['discount']) }}</span>
                </div>

                <div class="flex justify-between text-slate-600">
                    <span>Delivery Charge</span>
                    <span id="summaryDeliveryCharge" class="{{ $orderSummary['is_free_delivery'] ? 'text-emerald-600 font-bold' : 'text-blue-700 font-bold' }}">
                        @if($orderSummary['is_free_delivery'])
                            FREE
                        @else
                            ৳{{ number_format($orderSummary['delivery_charge']) }}
                        @endif
                    </span>
                </div>

                <div id="summaryShippingChargeRow" class="{{ ($orderSummary['shipping_charge'] ?? 0) > 0 ? 'flex' : 'hidden' }} justify-between text-slate-600">
                    <span>Shipping Charge</span>
                    <span id="summaryShippingCharge" class="font-bold text-slate-900">৳{{ number_format($orderSummary['shipping_charge'] ?? 0) }}</span>
                </div>

                <div class="pt-4 border-t border-slate-200 flex justify-between items-baseline font-extrabold">
                    <span class="text-base text-slate-900">Total Payable</span>
                    <span id="summaryTotalAmount" class="text-2xl text-blue-700">৳{{ number_format($orderSummary['total']) }}</span>
                </div>
            </div>

            <!-- Complete Order Button -->
            <button type="button" id="proceedPaymentBtn" class="w-full h-12 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold flex items-center justify-center gap-2 shadow-lg shadow-blue-600/25 transition cursor-pointer text-sm sm:text-base">
                <span>Proceed to Payment</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>

            <p class="text-[11px] text-slate-400 text-center leading-relaxed">
                By placing this order, you agree to DigiGo’s <a href="#" class="underline">Terms of Service</a> and <a href="#" class="underline">Privacy Policy</a>. Safe & encrypted transactions.
            </p>
        </div>

    </div>

</div>

@push('scripts')
<script>
    const BD_LOCATIONS = {
        'Dhaka': ['Dhaka', 'Gazipur', 'Narayanganj', 'Tangail', 'Faridpur', 'Manikganj', 'Munshiganj', 'Narsingdi', 'Kishoreganj', 'Gopalganj', 'Madaripur', 'Rajbari', 'Shariatpur'],
        'Chattogram': ['Chattogram', 'Cox\'s Bazar', 'Cumilla', 'Feni', 'Brahmanbaria', 'Rangamati', 'Noakhali', 'Chandpur', 'Lakshmipur', 'Khagrachhari', 'Bandarban'],
        'Rajshahi': ['Rajshahi', 'Bogura', 'Pabna', 'Sirajganj', 'Naogaon', 'Natore', 'Chapai Nawabganj', 'Joypurhat'],
        'Khulna': ['Khulna', 'Jashore', 'Kushtia', 'Satkhira', 'Jhenaidah', 'Bagerhat', 'Chuadanga', 'Magura', 'Meherpur', 'Narail'],
        'Barishal': ['Barishal', 'Patuakhali', 'Bhola', 'Pirojpur', 'Barguna', 'Jhalokati'],
        'Sylhet': ['Sylhet', 'Moulvibazar', 'Habiganj', 'Sunamganj'],
        'Rangpur': ['Rangpur', 'Dinajpur', 'Kurigram', 'Gaibandha', 'Nilphamari', 'Panchagarh', 'Thakurgaon', 'Lalmonirhat'],
        'Mymensingh': ['Mymensingh', 'Jamalpur', 'Netrokona', 'Sherpur']
    };

    function showCouponAlert(message, type = 'error') {
        const box = document.getElementById('couponAlertBox');
        const text = document.getElementById('couponAlertText');
        box.classList.remove('hidden', 'flex', 'bg-emerald-50', 'border-emerald-200', 'text-emerald-700', 'bg-rose-50', 'border-rose-200', 'text-rose-700');

        if (type === 'success') {
            box.classList.add('flex', 'bg-emerald-50', 'border', 'border-emerald-200', 'text-emerald-700');
            text.innerHTML = '<i class="fa-solid fa-circle-check mr-1.5"></i>' + message;
        } else {
            box.classList.add('flex', 'bg-rose-50', 'border', 'border-rose-200', 'text-rose-700');
            text.innerHTML = '<i class="fa-solid fa-circle-exclamation mr-1.5"></i>' + message;
        }
    }

    function updateOrderSummaryUI(summary) {
        if (!summary) return;

        // Subtotal
        if (summary.subtotal_formatted) {
            document.getElementById('summarySubtotal').textContent = summary.subtotal_formatted;
        }

        // Discount
        const discountRow = document.getElementById('summaryDiscountRow');
        const discountAmount = document.getElementById('summaryDiscountAmount');
        if (summary.discount > 0) {
            discountRow.classList.remove('hidden');
            discountRow.classList.add('flex');
            discountAmount.textContent = '-' + (summary.discount_formatted || ('৳' + Number(summary.discount).toLocaleString()));
        } else {
            discountRow.classList.add('hidden');
            discountRow.classList.remove('flex');
        }

        // Delivery Charge
        const summaryCharge = document.getElementById('summaryDeliveryCharge');
        if (summary.is_free_delivery) {
            summaryCharge.textContent = 'FREE';
            summaryCharge.className = 'text-emerald-600 font-bold';
        } else {
            summaryCharge.textContent = summary.delivery_charge_formatted || ('৳' + Number(summary.delivery_charge).toLocaleString());
            summaryCharge.className = 'text-blue-700 font-bold';
        }

        // Product Shipping Charge
        const shippingRow = document.getElementById('summaryShippingChargeRow');
        const shippingChargeElem = document.getElementById('summaryShippingCharge');
        if (summary.shipping_charge > 0) {
            if (shippingRow) {
                shippingRow.classList.remove('hidden');
                shippingRow.classList.add('flex');
            }
            if (shippingChargeElem) {
                shippingChargeElem.textContent = summary.shipping_charge_formatted || ('৳' + Number(summary.shipping_charge).toLocaleString());
            }
        } else {
            if (shippingRow) {
                shippingRow.classList.add('hidden');
                shippingRow.classList.remove('flex');
            }
        }

        // Total
        let numericTotal = 0;
        if (typeof summary.total !== 'undefined') {
            numericTotal = Number(summary.total);
        }

        if (summary.total_formatted) {
            document.getElementById('summaryTotalAmount').textContent = summary.total_formatted;
        } else {
            document.getElementById('summaryTotalAmount').textContent = '৳' + numericTotal.toLocaleString();
        }

        // Toggle Free Order state if coupon completely discounts the total (0 tk)
        const paymentSelectionContainer = document.getElementById('paymentSelectionContainer');
        const freeOrderNotice = document.getElementById('freeOrderNotice');
        const proceedBtn = document.getElementById('proceedPaymentBtn');

        if (numericTotal <= 0) {
            if (paymentSelectionContainer) paymentSelectionContainer.classList.add('hidden');
            if (freeOrderNotice) {
                freeOrderNotice.classList.remove('hidden');
                freeOrderNotice.classList.add('flex');
            }
            if (proceedBtn) {
                proceedBtn.innerHTML = '<span>Complete Free Order</span> <i class="fa-solid fa-gift text-xs"></i>';
                proceedBtn.className = 'w-full h-12 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold flex items-center justify-center gap-2 shadow-lg shadow-emerald-600/25 transition cursor-pointer text-sm sm:text-base';
            }
        } else {
            if (paymentSelectionContainer) paymentSelectionContainer.classList.remove('hidden');
            if (freeOrderNotice) {
                freeOrderNotice.classList.add('hidden');
                freeOrderNotice.classList.remove('flex');
            }
            // Restore button styling according to selected radio
            const selectedPaymentRadio = document.querySelector('input[name="payment_method"]:checked');
            if (selectedPaymentRadio) {
                onPaymentMethodChange(selectedPaymentRadio);
            }
        }

        // Handle COD availability dynamically if returned
        if (typeof summary.cod_allowed !== 'undefined') {
            const codOption = document.getElementById('codPaymentOption');
            const codRadio = document.getElementById('paymentCodRadio');
            const dgepayRadio = document.querySelector('input[name="payment_method"][value="dgepay"]');
            const codNotice = document.getElementById('codDisabledNotice');

            if (!summary.cod_allowed) {
                if (codOption) codOption.classList.add('hidden');
                if (codNotice) codNotice.classList.remove('hidden');
                if (codRadio && codRadio.checked && dgepayRadio) {
                    dgepayRadio.checked = true;
                    onPaymentMethodChange(dgepayRadio);
                }
            } else {
                if (codOption) codOption.classList.remove('hidden');
                if (codNotice) codNotice.classList.add('hidden');
            }
        }
    }

    // Apply Coupon via AJAX
    function applyCouponAjax(e) {
        e.preventDefault();
        const codeInput = document.getElementById('couponCodeInput');
        const code = codeInput ? codeInput.value.trim() : '';
        const btn = document.getElementById('applyCouponBtn');

        if (!code) {
            showCouponAlert('Please enter a coupon code.', 'error');
            return;
        }

        const division = document.getElementById('shippingDivision')?.value || 'Dhaka';
        const district = document.getElementById('shippingDistrict')?.value || 'Dhaka';

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> <span>Applying...</span>';

        fetch('{{ route('cart.coupon.apply') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                code: code,
                division: division,
                district: district
            })
        })
        .then(res => res.json().then(data => ({ status: res.status, body: data })))
        .then(({ status, body }) => {
            btn.disabled = false;
            btn.innerHTML = '<span>Apply</span>';

            if (status === 200 && body.success) {
                showCouponAlert(body.message, 'success');
                // Switch forms
                document.getElementById('couponApplyForm').classList.add('hidden');
                document.getElementById('couponApplyForm').classList.remove('flex');

                const appliedCard = document.getElementById('appliedCouponCard');
                appliedCard.classList.remove('hidden');
                appliedCard.classList.add('flex');
                document.getElementById('appliedCouponDisplay').textContent = body.code + ' (-৳' + Number(body.discount).toLocaleString() + ')';

                if (body.summary) {
                    updateOrderSummaryUI(body.summary);
                }
            } else {
                showCouponAlert(body.message || 'Failed to apply coupon.', 'error');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<span>Apply</span>';
            showCouponAlert('An unexpected error occurred while applying the coupon.', 'error');
            console.error(err);
        });
    }

    // Remove Coupon via AJAX
    function removeCouponAjax() {
        const btn = document.getElementById('removeCouponBtn');
        const division = document.getElementById('shippingDivision')?.value || 'Dhaka';
        const district = document.getElementById('shippingDistrict')?.value || 'Dhaka';

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i>';

        fetch('{{ route('cart.coupon.remove') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                division: division,
                district: district
            })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.textContent = 'Remove';

            if (data.success) {
                showCouponAlert(data.message, 'success');
                // Switch back to input form
                document.getElementById('appliedCouponCard').classList.add('hidden');
                document.getElementById('appliedCouponCard').classList.remove('flex');

                const applyForm = document.getElementById('couponApplyForm');
                applyForm.classList.remove('hidden');
                applyForm.classList.add('flex');
                document.getElementById('couponCodeInput').value = '';

                if (data.summary) {
                    updateOrderSummaryUI(data.summary);
                }
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.textContent = 'Remove';
            showCouponAlert('An error occurred while removing coupon.', 'error');
            console.error(err);
        });
    }

    // Saved Address Toggle and Update logic
    function toggleEditAddress() {
        const container = document.getElementById('addressInputsContainer');
        const card = document.getElementById('savedAddressCard');
        const btnText = document.getElementById('editAddressBtnText');
        const icon = document.getElementById('editAddressIcon');

        if (!container) return;

        if (container.classList.contains('hidden')) {
            container.classList.remove('hidden');
            if (btnText) btnText.textContent = 'Hide Edit Form';
            if (icon) icon.className = 'fa-solid fa-chevron-up text-xs';
        } else {
            container.classList.add('hidden');
            if (btnText) btnText.textContent = 'Change / Edit Address';
            if (icon) icon.className = 'fa-solid fa-pen-to-square text-xs';
        }
    }

    function cancelAddressEdit() {
        const container = document.getElementById('addressInputsContainer');
        const btnText = document.getElementById('editAddressBtnText');
        const icon = document.getElementById('editAddressIcon');

        if (container) {
            container.classList.add('hidden');
            if (btnText) btnText.textContent = 'Change / Edit Address';
            if (icon) icon.className = 'fa-solid fa-pen-to-square text-xs';
        }
    }

    function applyAddressEdits() {
        const name = document.getElementById('customerName')?.value.trim();
        const phone = document.getElementById('customerPhone')?.value.trim();
        const division = document.getElementById('shippingDivision')?.value;
        const district = document.getElementById('shippingDistrict')?.value;
        const street = document.getElementById('shippingAddress')?.value.trim();

        if (!name || !phone || !street) {
            alert('Please ensure Name, Phone, and Street address are filled out.');
            return;
        }

        // Update card UI preview
        const cardFullName = document.getElementById('cardFullName');
        const cardPhone = document.getElementById('cardPhone');
        const cardAddressText = document.getElementById('cardAddressText');

        if (cardFullName) cardFullName.textContent = name;
        if (cardPhone) cardPhone.textContent = phone;
        if (cardAddressText) cardAddressText.textContent = street + ', ' + district + ', ' + division;

        calculateDeliveryCharge();
        cancelAddressEdit();
    }

    function populateDistricts(division, selectedDistrict = 'Dhaka') {
        const districtSelect = document.getElementById('shippingDistrict');
        districtSelect.innerHTML = '';
        const districts = BD_LOCATIONS[division] || [];

        districts.forEach(dist => {
            const opt = document.createElement('option');
            opt.value = dist;
            opt.textContent = dist;
            if (dist === selectedDistrict) {
                opt.selected = true;
            }
            districtSelect.appendChild(opt);
        });
    }

    function onDivisionChange() {
        const division = document.getElementById('shippingDivision').value;
        const defaultDist = division === 'Dhaka' ? 'Dhaka' : (BD_LOCATIONS[division] ? BD_LOCATIONS[division][0] : '');
        populateDistricts(division, defaultDist);
        calculateDeliveryCharge();
    }

    // Backend calculation for delivery charge & total summary
    function calculateDeliveryCharge() {
        const division = document.getElementById('shippingDivision').value;
        const district = document.getElementById('shippingDistrict').value;

        fetch('{{ route('checkout.calculate') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ division, district })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const badge = document.getElementById('shippingZoneBadge');
                if (badge) {
                    if (data.is_inside_dhaka) {
                        badge.className = 'text-[11px] font-bold px-2 py-0.5 rounded bg-blue-100 text-blue-700';
                        badge.textContent = 'Inside Dhaka (' + data.delivery_charge_formatted + ')';
                    } else {
                        badge.className = 'text-[11px] font-bold px-2 py-0.5 rounded bg-amber-100 text-amber-800';
                        badge.textContent = 'Outside Dhaka (' + data.delivery_charge_formatted + ')';
                    }
                }

                updateOrderSummaryUI(data);
            }
        })
        .catch(err => console.error(err));
    }

    function onPaymentMethodChange(radio) {
        document.querySelectorAll('.payment-method-card').forEach(card => {
            card.classList.remove('border-2', 'border-blue-600', 'bg-blue-50/50');
            card.classList.add('border-slate-200');
        });

        const activeCard = radio.closest('.payment-method-card');
        if (activeCard) {
            activeCard.classList.remove('border-slate-200');
            activeCard.classList.add('border-2', 'border-blue-600', 'bg-blue-50/50');
        }

        const proceedBtn = document.getElementById('proceedPaymentBtn');
        if (proceedBtn) {
            if (radio.value === 'cod') {
                proceedBtn.innerHTML = '<span>Confirm Order (Cash on Delivery)</span> <i class="fa-solid fa-check text-xs"></i>';
                proceedBtn.className = 'w-full h-12 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold flex items-center justify-center gap-2 shadow-lg shadow-emerald-600/25 transition cursor-pointer text-sm sm:text-base';
            } else {
                proceedBtn.innerHTML = '<span>Proceed to DGePay Payment</span> <i class="fa-solid fa-arrow-right text-xs"></i>';
                proceedBtn.className = 'w-full h-12 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold flex items-center justify-center gap-2 shadow-lg shadow-blue-600/25 transition cursor-pointer text-sm sm:text-base';
            }
        }
    }

    function handlePlaceOrder() {
        const btn = document.getElementById('proceedPaymentBtn');
        const customer_name = document.getElementById('customerName')?.value.trim();
        const customer_email = document.getElementById('customerEmail')?.value.trim();
        const customer_phone = document.getElementById('customerPhone')?.value.trim();
        const division = document.getElementById('shippingDivision')?.value;
        const district = document.getElementById('shippingDistrict')?.value;
        const street_address = document.getElementById('shippingAddress')?.value.trim();
        const notes = document.getElementById('orderNotes')?.value.trim();
        const digital_target_email = document.getElementById('digitalTargetEmail')?.value.trim() || null;
        const digital_target_phone = document.getElementById('digitalTargetPhone')?.value.trim() || null;
        const digital_account_notes = document.getElementById('digitalAccountNotes')?.value.trim() || null;
        const paymentRadio = document.querySelector('input[name="payment_method"]:checked');
        const payment_method = paymentRadio ? paymentRadio.value : 'dgepay';

        // Front-end validation
        if (!customer_name) {
            alert('Please enter your full name.');
            document.getElementById('customerName')?.focus();
            return;
        }
        if (!customer_email || !customer_email.includes('@')) {
            alert('Please enter a valid email address.');
            document.getElementById('customerEmail')?.focus();
            return;
        }
        if (!customer_phone || customer_phone.length < 8) {
            alert('Please enter your WhatsApp or contact phone number.');
            document.getElementById('customerPhone')?.focus();
            return;
        }
        if (!division || !district) {
            alert('Please select your division and district.');
            return;
        }
        if (!street_address) {
            alert('Please enter your full street address.');
            document.getElementById('shippingAddress')?.focus();
            return;
        }

        btn.disabled = true;
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span>Processing Order...</span>';

        fetch('{{ route('checkout.place') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                customer_name,
                customer_email,
                customer_phone,
                division,
                district,
                street_address,
                notes,
                digital_target_email,
                digital_target_phone,
                digital_account_notes,
                payment_method
            })
        })
        .then(res => res.json().then(data => ({ status: res.status, body: data })))
        .then(({ status, body }) => {
            if (status === 200 && body.success) {
                if (body.redirect_url) {
                    window.location.href = body.redirect_url;
                } else if (body.payment_url) {
                    window.location.href = body.payment_url;
                }
            } else {
                btn.disabled = false;
                btn.innerHTML = originalContent;
                alert(body.message || 'Could not complete order. Please check all details.');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = originalContent;
            console.error(err);
            alert('An unexpected network error occurred while processing your order.');
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        populateDistricts('{{ $initialDivision }}', '{{ $initialDistrict }}');
        calculateDeliveryCharge();

        // Initial check for 0 total (e.g., if page loaded with an existing coupon covering 100%)
        const initialTotal = {{ (float) ($orderSummary['total'] ?? 0) }};
        if (initialTotal <= 0) {
            updateOrderSummaryUI({
                total: 0,
                total_formatted: '৳0.00'
            });
        }

        const proceedBtn = document.getElementById('proceedPaymentBtn');
        if (proceedBtn) {
            proceedBtn.addEventListener('click', handlePlaceOrder);
        }
    });
</script>
@endpush
@endsection
