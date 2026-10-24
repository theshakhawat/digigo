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
        Your Shopping Cart (<span id="cartCountTitle">{{ count($cartItems) }}</span>)
    </h1>

    @if(count($cartItems) > 0)
        <div id="cartContentGrid" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left: Cart Items Table (Col 8) -->
            <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 overflow-hidden">
                <div class="divide-y divide-slate-200" id="cartItemsContainer">
                    @foreach($cartItems as $key => $item)
                        <div class="py-6 first:pt-0 last:pb-0 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 cart-row" data-key="{{ $key }}">
                            
                            <!-- Product Info -->
                            <div class="flex items-center gap-4 flex-1">
                                <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-20 h-20 rounded-xl object-cover border border-slate-200 shrink-0">
                                <div>
                                    <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider">{{ $item['category'] }}</span>
                                    <h3 class="font-bold text-slate-900 text-base leading-snug hover:text-blue-600 transition">
                                        <a href="{{ route('product.show', $item['slug']) }}">{{ $item['title'] }}</a>
                                    </h3>
                                    <p class="text-xs text-slate-400 mt-1 capitalize">{{ $item['product_type'] ?? 'digital' }} Item</p>
                                </div>
                            </div>

                            <!-- Price, Qty & Subtotal -->
                            <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto pt-2 sm:pt-0 border-t sm:border-0 border-slate-100">
                                <div class="text-right">
                                    <span class="block font-bold text-slate-900 text-lg item-subtotal">৳{{ number_format($item['price'] * $item['quantity']) }}</span>
                                    <div class="flex items-center border border-slate-300 rounded-lg bg-white h-7 mt-1 inline-flex">
                                        <button type="button" class="w-6 h-full flex items-center justify-center text-slate-500 hover:text-slate-900 transition" onclick="updateItemQty('{{ $key }}', {{ $item['quantity'] - 1 }})">-</button>
                                        <span class="w-8 text-center text-xs font-bold text-slate-800">{{ $item['quantity'] }}</span>
                                        <button type="button" class="w-6 h-full flex items-center justify-center text-slate-500 hover:text-slate-900 transition" onclick="updateItemQty('{{ $key }}', {{ $item['quantity'] + 1 }})">+</button>
                                    </div>
                                </div>

                                <button type="button"
                                        onclick="removeCartItem('{{ $key }}', this)"
                                        class="w-9 h-9 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center transition cursor-pointer"
                                        title="Remove item">
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
                    <button type="button" onclick="clearCart()" class="text-xs font-semibold text-rose-500 hover:text-rose-700 transition cursor-pointer">
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
                        <span>Items Subtotal</span>
                        <span id="summarySubtotal" class="font-bold text-slate-900">৳{{ number_format($subtotal) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-500 text-xs">
                        <span>Shipping & Coupons</span>
                        <span class="italic">Calculated at Checkout</span>
                    </div>
                    <div class="pt-4 border-t border-slate-200 flex justify-between items-baseline text-slate-900 font-extrabold text-lg">
                        <span>Estimated Total</span>
                        <span id="summaryTotal" class="text-2xl text-blue-700">৳{{ number_format($subtotal) }}</span>
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
    @else
        <!-- Empty Cart -->
        <div id="emptyCartView" class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-sm space-y-4">
            <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto text-2xl">
                <i class="fa-solid fa-bag-shopping"></i>
            </div>
            <h2 class="text-xl font-bold text-slate-900">Your shopping cart is empty</h2>
            <p class="text-sm text-slate-500 max-w-md mx-auto">
                Explore our catalog to find your desired digital subscription licenses, software, and gadgets.
            </p>
            <div class="pt-2">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-600/20 transition">
                    <i class="fa-solid fa-arrow-left"></i> Start Shopping
                </a>
            </div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    function updateItemQty(cartKey, newQty) {
        if (newQty < 1) {
            removeCartItem(cartKey);
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        fetch('/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                cart_key: cartKey,
                quantity: newQty
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                window.showDigigoToast(data.message || 'Failed to update', 'error');
            }
        });
    }

    function removeCartItem(cartKey, btn = null) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        fetch('/cart/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                cart_key: cartKey
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Update badge numbers
                document.querySelectorAll('.header-cart-badge').forEach(badge => {
                    badge.textContent = data.cart_count;
                    if (data.cart_count > 0) {
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                });

                location.reload();
            }
        });
    }

    function clearCart() {
        if (!confirm('Are you sure you want to clear your shopping cart?')) return;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        fetch('/cart/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
</script>
@endpush
