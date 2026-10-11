@extends('layouts.site')

@section('title', $product['title'] . ' - DigiGo')

@section('content')
<div class="max-w-[1440px] mx-auto px-4 sm:px-6 py-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-6">
        <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Home</a>
        <span>/</span>
        <a href="{{ url('/search') }}" class="hover:text-blue-600 transition">{{ $product['category'] }}</a>
        <span>/</span>
        <span class="text-slate-900 font-semibold truncate max-w-xs">{{ $product['title'] }}</span>
    </nav>

    <!-- Product Main Grid (Image + Purchasing Box) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-sm mb-12">

        <!-- Left: Product Media Gallery (Col 5) -->
        <div class="lg:col-span-5 space-y-4">
            <div class="relative aspect-square rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shadow-inner group">
                <img src="{{ $product['image'] }}" alt="{{ $product['title'] }}" class="w-full h-full object-cover object-center group-hover:scale-105 transition duration-300">
                <span class="absolute top-4 left-4 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">
                    {{ $product['discount'] }}
                </span>
            </div>

            <!-- Thumbnail preview icons -->
            <div class="grid grid-cols-4 gap-3">
                <button type="button" class="aspect-square rounded-lg border-2 border-blue-600 overflow-hidden">
                    <img src="{{ $product['image'] }}" class="w-full h-full object-cover">
                </button>
                <button type="button" class="aspect-square rounded-lg border border-slate-200 overflow-hidden hover:border-blue-400 opacity-75 hover:opacity-100 transition">
                    <img src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=400&q=80" class="w-full h-full object-cover">
                </button>
                <button type="button" class="aspect-square rounded-lg border border-slate-200 overflow-hidden hover:border-blue-400 opacity-75 hover:opacity-100 transition">
                    <img src="https://images.unsplash.com/photo-1677442136019-21780ecad995?auto=format&fit=crop&w=400&q=80" class="w-full h-full object-cover">
                </button>
                <button type="button" class="aspect-square rounded-lg border border-slate-200 overflow-hidden hover:border-blue-400 opacity-75 hover:opacity-100 transition">
                    <img src="https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&w=400&q=80" class="w-full h-full object-cover">
                </button>
            </div>
        </div>

        <!-- Right: Product Details & Purchase Form (Col 7) -->
        <div class="lg:col-span-7 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full">
                        {{ $product['category'] }}
                    </span>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200">
                        <i class="fa-solid fa-circle-check text-[10px]"></i> {{ $product['stock_status'] }}
                    </span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-snug">
                    {{ $product['title'] }}
                </h1>

                <!-- Rating -->
                <div class="flex items-center gap-3 text-sm">
                    <div class="flex text-amber-400 text-xs">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star-half-stroke"></i>
                    </div>
                    <span class="font-bold text-slate-800">{{ $product['rating'] }}</span>
                    <span class="text-slate-400">|</span>
                    <span class="text-slate-500 font-medium">({{ $product['reviews_count'] }} verified customer reviews)</span>
                </div>

                <!-- Price Box -->
                <div class="bg-slate-50 rounded-xl p-4 sm:p-5 border border-slate-200 flex items-baseline gap-3">
                    <span class="text-3xl sm:text-4xl font-extrabold text-blue-700">৳{{ number_format($product['price']) }}</span>
                    <span class="text-lg text-slate-400 line-through">৳{{ number_format($product['old_price']) }}</span>
                    <span class="text-xs font-bold text-emerald-600 bg-emerald-100 px-2.5 py-0.5 rounded-full ml-auto">
                        Save ৳{{ number_format($product['old_price'] - $product['price']) }}
                    </span>
                </div>

                <!-- Key Highlights -->
                <div class="space-y-2 pt-2">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Key Service Features:</h3>
                    <ul class="space-y-2 text-sm text-slate-700">
                        @foreach($product['features'] as $feature)
                            <li class="flex items-start gap-2.5">
                                <i class="fa-solid fa-check text-blue-600 mt-1"></i>
                                <span>{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Action Buttons Form -->
            <div class="pt-8 border-t border-slate-200 mt-6 space-y-4">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">

                    <!-- Quantity picker -->
                    <div class="flex items-center border border-slate-300 rounded-xl bg-white h-12 w-32 shrink-0">
                        <button type="button" class="w-10 h-full flex items-center justify-center text-slate-500 hover:text-slate-900 transition" onclick="const q=document.getElementById('productQty'); if(q.value>1) q.value--;">-</button>
                        <input type="number" id="productQty" value="1" min="1" class="w-12 h-full text-center border-none font-bold text-slate-900 focus:outline-none">
                        <button type="button" class="w-10 h-full flex items-center justify-center text-slate-500 hover:text-slate-900 transition" onclick="const q=document.getElementById('productQty'); q.value++;">+</button>
                    </div>

                    <!-- Add to Cart -->
                    <a href="{{ route('cart') }}" class="flex-1 h-12 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold flex items-center justify-center gap-2 shadow-lg shadow-blue-600/25 transition">
                        <i class="fa-solid fa-bag-shopping"></i> Add to Cart
                    </a>

                    <!-- Direct Checkout Buy Now -->
                    <a href="{{ route('checkout') }}" class="h-12 px-6 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold flex items-center justify-center gap-2 transition">
                        Buy Now
                    </a>

                    <!-- Add to Wishlist -->
                    <a href="{{ route('wishlist') }}" class="w-12 h-12 rounded-xl border border-slate-200 hover:border-red-400 hover:text-red-500 flex items-center justify-center text-slate-500 text-lg transition" title="Add to Wishlist">
                        <i class="fa-regular fa-heart"></i>
                    </a>
                </div>

                <div class="flex items-center gap-6 text-xs text-slate-500 pt-2">
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-bolt text-amber-500"></i> Instant email delivery</span>
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-shield text-blue-600"></i> 100% Genuine guaranteed</span>
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-rotate-left text-emerald-600"></i> Replacement warranty</span>
                </div>
            </div>

        </div>
    </div>

    <!-- Tabbed Description & Support Section -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-sm mb-12">
        <div class="border-b border-slate-200 pb-4 mb-6 flex gap-6 text-sm font-bold">
            <button type="button" class="text-blue-700 border-b-2 border-blue-700 pb-4 -mb-4">Product Description</button>
            <button type="button" class="text-slate-500 hover:text-slate-900 transition">How It Works</button>
            <button type="button" class="text-slate-500 hover:text-slate-900 transition">Customer Reviews ({{ $product['reviews_count'] }})</button>
        </div>

        <div class="prose max-w-none text-slate-700 leading-relaxed space-y-4 text-sm sm:text-base">
            <p>{{ $product['description'] }}</p>
            <h4 class="text-base font-bold text-slate-900">How will you receive your order?</h4>
            <p>Once your payment is confirmed, you will immediately receive account login credentials and quick setup guidelines on your registered email address and in your <a href="{{ route('user.downloads') }}" class="text-blue-600 font-semibold underline">Downloads Dashboard</a>.</p>
        </div>
    </div>

</div>
@endsection
