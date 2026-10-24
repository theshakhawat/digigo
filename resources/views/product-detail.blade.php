@extends('layouts.site')

@section('title', $product['title'] . ' - DigiGo')

@push('styles')
<style>
    .rich-html-content {
        color: #334155;
        font-size: 0.95rem;
        line-height: 1.75;
    }
    .rich-html-content p {
        margin-bottom: 0.85rem;
    }
    .rich-html-content h1,
    .rich-html-content h2,
    .rich-html-content h3,
    .rich-html-content h4,
    .rich-html-content h5,
    .rich-html-content h6 {
        color: #0f172a;
        font-weight: 700;
        margin-top: 1.25rem;
        margin-bottom: 0.5rem;
        line-height: 1.35;
    }
    .rich-html-content h2 { font-size: 1.3rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.4rem; }
    .rich-html-content h3 { font-size: 1.15rem; }
    .rich-html-content h4 { font-size: 1.025rem; }
    .rich-html-content ul {
        list-style-type: disc !important;
        margin-left: 1.5rem !important;
        margin-bottom: 1rem !important;
        space-y: 0.35rem;
    }
    .rich-html-content ol {
        list-style-type: decimal !important;
        margin-left: 1.5rem !important;
        margin-bottom: 1rem !important;
    }
    .rich-html-content li {
        margin-bottom: 0.35rem;
    }
    .rich-html-content strong, .rich-html-content b {
        color: #0f172a;
        font-weight: 700;
    }
    .rich-html-content a {
        color: #2563eb;
        text-decoration: underline;
        font-weight: 600;
    }
    .rich-html-content a:hover {
        color: #1d4ed8;
    }
    .rich-html-content blockquote {
        border-left: 4px solid #3b82f6;
        padding-left: 1rem;
        color: #475569;
        font-style: italic;
        margin: 1rem 0;
        background: #f8fafc;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        border-radius: 0 8px 8px 0;
    }
    .rich-html-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 1rem 0;
        font-size: 0.9rem;
    }
    .rich-html-content table th,
    .rich-html-content table td {
        border: 1px solid #e2e8f0;
        padding: 0.5rem 0.75rem;
        text-align: left;
    }
    .rich-html-content table th {
        background-color: #f8fafc;
        font-weight: 700;
        color: #0f172a;
    }
    .rich-html-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.75rem;
        margin: 1rem 0;
    }
</style>
@endpush

@section('content')
<div class="max-w-[1440px] mx-auto px-4 sm:px-6 py-8 pb-24 md:pb-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-6 flex-wrap">
        <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Home</a>
        <span>/</span>
        @if(!empty($product['category_slug']))
            <a href="{{ route('category.show', $product['category_slug']) }}" class="hover:text-blue-600 transition">{{ $product['category'] }}</a>
        @else
            <span class="text-slate-600">{{ $product['category'] }}</span>
        @endif
        <span>/</span>
        <span class="text-slate-900 font-semibold truncate max-w-xs">{{ $product['title'] }}</span>
    </nav>

    <!-- Product Main Grid (Image + Purchasing Box) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-sm mb-12">

        <!-- Left: Product Media Gallery (Col 5) -->
        <div class="lg:col-span-5 space-y-4">
            <div class="relative aspect-square rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shadow-inner group">
                <img id="mainProductImage" src="{{ $product['image'] }}" alt="{{ $product['title'] }}" class="w-full h-full object-cover object-center group-hover:scale-105 transition duration-300">
                @if($product['discount'])
                    <span class="absolute top-4 left-4 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">
                        {{ $product['discount'] }}
                    </span>
                @endif
            </div>

            <!-- Thumbnail preview icons -->
            @php
                $allImages = array_values(array_unique(array_filter(array_merge([$product['image']], $product['gallery'] ?? []))));
            @endphp
            @if(count($allImages) > 1)
                <div class="grid grid-cols-4 gap-3">
                    @foreach($allImages as $idx => $img)
                        <button type="button" onclick="selectProductThumb(this, '{{ $img }}')" class="product-thumb-btn aspect-square rounded-lg border {{ $idx === 0 ? 'border-blue-600 ring-2 ring-blue-600/20' : 'border-slate-200 hover:border-blue-400' }} overflow-hidden transition cursor-pointer">
                            <img src="{{ $img }}" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right: Product Details & Purchase Form (Col 7) -->
        <div class="lg:col-span-7 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    @if(!empty($product['category_slug']))
                        <a href="{{ route('category.show', $product['category_slug']) }}" class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full hover:bg-blue-200 transition">
                            {{ $product['category'] }}
                        </a>
                    @else
                        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full">
                            {{ $product['category'] }}
                        </span>
                    @endif
                    @if(($product['product_type'] ?? '') === 'physical' && ($product['stock_quantity'] ?? 0) <= 0)
                        <span class="inline-flex items-center gap-1 text-xs font-bold text-rose-700 bg-rose-50 px-2.5 py-1 rounded-md border border-rose-200">
                            <i class="fa-solid fa-circle-xmark text-[10px]"></i> Out of Stock
                        </span>
                    @elseif(($product['product_type'] ?? '') === 'physical' && ($product['stock_quantity'] ?? 0) <= ($product['model']?->low_stock_threshold ?? 5))
                        <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-md border border-amber-200">
                            <i class="fa-solid fa-triangle-exclamation text-[10px]"></i> {{ $product['stock_status'] }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200">
                            <i class="fa-solid fa-circle-check text-[10px]"></i> {{ $product['stock_status'] }}
                        </span>
                    @endif
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
                    <span class="text-slate-500 font-medium">
                        @if($product['reviews_count'] > 0)
                            ({{ $product['reviews_count'] }} verified customer {{ Str::plural('review', $product['reviews_count']) }})
                        @else
                            (No reviews yet)
                        @endif
                    </span>
                </div>

                <!-- Price Box -->
                <div class="bg-slate-50 rounded-xl p-4 sm:p-5 border border-slate-200 flex items-baseline gap-3">
                    <span id="displayPrice" class="text-3xl sm:text-4xl font-extrabold text-blue-700">৳{{ number_format($product['price']) }}</span>
                    @if($product['old_price'] && $product['old_price'] > $product['price'])
                        <span id="displayOldPrice" class="text-lg text-slate-400 line-through">৳{{ number_format($product['old_price']) }}</span>
                        <span id="displayDiscount" class="text-xs font-bold text-emerald-600 bg-emerald-100 px-2.5 py-0.5 rounded-full ml-auto">
                            Save ৳{{ number_format($product['old_price'] - $product['price']) }}
                        </span>
                    @endif
                </div>

                <!-- Digital Subscription Plan Options (if any) -->
                @php
                    $defaultPlanId = null;
                    if (!empty($product['plans']) && count($product['plans']) > 0) {
                        $def = $product['plans']->where('is_default', true)->first() ?: $product['plans']->first();
                        $defaultPlanId = $def?->id;
                    }
                @endphp
                <input type="hidden" id="selectedPlanId" value="{{ $defaultPlanId }}">

                @if(!empty($product['plans']) && count($product['plans']) > 0)
                    <div class="space-y-2 pt-1">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Select Subscription Plan / Validity:
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                            @foreach($product['plans'] as $plan)
                                <button type="button"
                                        onclick="selectProductPlan(this, {{ $plan->id }}, {{ $plan->effective_price }}, {{ $plan->sale_price ? $plan->price : 'null' }})"
                                        class="plan-btn p-3 rounded-xl border text-left transition {{ ($defaultPlanId == $plan->id) ? 'border-blue-600 bg-blue-50/60 ring-2 ring-blue-600/20' : 'border-slate-200 bg-white hover:border-slate-300' }}">
                                    <p class="text-xs font-bold text-slate-900 leading-tight">{{ $plan->name }}</p>
                                    <div class="flex items-baseline gap-1.5 mt-1">
                                        <span class="text-sm font-extrabold text-blue-700">৳{{ number_format($plan->effective_price) }}</span>
                                        @if($plan->sale_price)
                                            <span class="text-[10px] text-slate-400 line-through">৳{{ number_format($plan->price) }}</span>
                                        @endif
                                    </div>
                                    @if($plan->validity)
                                        <p class="text-[10px] text-slate-500 mt-0.5"><i class="fa-solid fa-clock text-[9px] mr-0.5"></i>{{ is_numeric($plan->validity) ? $plan->validity . ' Days' : $plan->validity }}</p>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Physical Product Specs (Warranty, In-Box contents) -->
                @if(!empty($product['warranty_period']) || !empty($product['package_contents']))
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs space-y-1">
                        @if(!empty($product['warranty_period']))
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-shield-halved text-blue-600"></i>
                                <span><strong class="text-slate-800">Warranty:</strong> {{ $product['warranty_period'] }}</span>
                            </div>
                        @endif
                        @if(!empty($product['package_contents']))
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-box-open text-emerald-600"></i>
                                <span><strong class="text-slate-800">Package Includes:</strong> {{ $product['package_contents'] }}</span>
                            </div>
                        @endif
                    </div>
                @endif

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
                @php
                    $isPhysicalOutOfStock = (($product['product_type'] ?? '') === 'physical') && (($product['stock_quantity'] ?? 0) <= 0);
                @endphp

                @if($isPhysicalOutOfStock)
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center gap-3">
                        <i class="fa-solid fa-circle-exclamation text-rose-500 text-xl shrink-0"></i>
                        <div>
                            <h4 class="font-bold text-sm">Product Currently Out of Stock!</h4>
                            <p class="text-xs text-rose-600 mt-0.5">This item is currently sold out. We will restock soon. You can add it to your wishlist to get notified!</p>
                        </div>
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">

                    <!-- Quantity picker & Wishlist on Mobile (Row) -->
                    <div class="flex items-center justify-between sm:justify-start gap-3">
                        <!-- Quantity picker -->
                        <div class="flex items-center border border-slate-300 rounded-xl bg-white h-12 w-32 shrink-0 {{ $isPhysicalOutOfStock ? 'opacity-50 pointer-events-none' : '' }}">
                            <button type="button" class="w-10 h-full flex items-center justify-center text-slate-500 hover:text-slate-900 transition" onclick="const q=document.getElementById('productQty'); if(q.value>1) q.value--;">-</button>
                            <input type="number" id="productQty" value="1" min="1" max="{{ ($product['product_type'] ?? '') === 'physical' ? max(1, $product['stock_quantity'] ?? 1) : 50 }}" class="w-12 h-full text-center border-none font-bold text-slate-900 focus:outline-none">
                            <button type="button" class="w-10 h-full flex items-center justify-center text-slate-500 hover:text-slate-900 transition" onclick="const q=document.getElementById('productQty'); const max = parseInt(q.getAttribute('max') || 50); if(parseInt(q.value) < max) q.value++;">+</button>
                        </div>

                        <!-- Add to Wishlist on mobile (alongside quantity) -->
                        @php
                            $isWishlisted = in_array($product['id'] ?? ($product['model']?->id ?? 0), array_keys(session('wishlist_items', [])));
                        @endphp
                        <button type="button"
                                onclick="digigoToggleWishlist({{ $product['id'] ?? ($product['model']?->id ?? 1) }}, this)"
                                class="sm:hidden w-12 h-12 rounded-xl border border-slate-200 hover:border-red-400 {{ $isWishlisted ? 'text-red-500 border-red-300 bg-red-50/50' : 'text-slate-500 hover:text-red-500 bg-white' }} flex items-center justify-center text-lg transition cursor-pointer"
                                title="{{ $isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                            <i class="{{ $isWishlisted ? 'fa-solid fa-heart' : 'fa-regular fa-heart' }}"></i>
                        </button>
                    </div>

                    <!-- Add to Cart & Buy Now container -->
                    <div class="grid grid-cols-2 sm:flex sm:flex-1 items-center gap-3">
                        <!-- Add to Cart -->
                        @if($isPhysicalOutOfStock)
                            <button type="button"
                                    disabled
                                    class="w-full sm:flex-1 h-12 rounded-xl bg-slate-200 text-slate-400 font-bold flex items-center justify-center gap-2 cursor-not-allowed text-sm">
                                <i class="fa-solid fa-ban"></i> Sold Out
                            </button>
                        @else
                            <button type="button"
                                    onclick="digigoAddToCart({{ $product['id'] ?? ($product['model']?->id ?? 1) }}, document.getElementById('selectedPlanId')?.value || null, parseInt(document.getElementById('productQty')?.value || 1), this)"
                                    class="w-full sm:flex-1 h-12 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold flex items-center justify-center gap-2 shadow-lg shadow-blue-600/25 transition cursor-pointer text-sm">
                                <i class="fa-solid fa-bag-shopping"></i> Add to Cart
                            </button>
                        @endif

                        <!-- Direct Checkout Buy Now -->
                        @if($isPhysicalOutOfStock)
                            <button type="button"
                                    disabled
                                    class="w-full sm:w-auto h-12 px-6 rounded-xl bg-slate-200 text-slate-400 font-bold flex items-center justify-center gap-2 cursor-not-allowed text-sm">
                                Out of Stock
                            </button>
                        @else
                            <button type="button"
                                    onclick="digigoBuyNow({{ $product['id'] ?? ($product['model']?->id ?? 1) }}, document.getElementById('selectedPlanId')?.value || null, parseInt(document.getElementById('productQty')?.value || 1))"
                                    class="w-full sm:w-auto h-12 px-6 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold flex items-center justify-center gap-2 transition cursor-pointer text-sm">
                                Buy Now
                            </button>
                        @endif
                    </div>

                    <!-- Add to Wishlist (Desktop) -->
                    <button type="button"
                            onclick="digigoToggleWishlist({{ $product['id'] ?? ($product['model']?->id ?? 1) }}, this)"
                            class="hidden sm:flex w-12 h-12 rounded-xl border border-slate-200 hover:border-red-400 {{ $isWishlisted ? 'text-red-500 border-red-300 bg-red-50/50' : 'text-slate-500 hover:text-red-500 bg-white' }} items-center justify-center text-lg transition cursor-pointer shrink-0"
                            title="{{ $isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                        <i class="{{ $isWishlisted ? 'fa-solid fa-heart' : 'fa-regular fa-heart' }}"></i>
                    </button>
                </div>

                <div class="flex items-center gap-6 text-xs text-slate-500 pt-2">
                    @if(($product['product_type'] ?? '') === 'digital')
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-bolt text-amber-500"></i> Instant digital delivery</span>
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-shield text-blue-600"></i> 100% Genuine subscription</span>
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-rotate-left text-emerald-600"></i> Replacement guarantee</span>
                    @else
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-truck-fast text-blue-600"></i> Fast nationwide delivery</span>
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-shield-halved text-emerald-600"></i> 100% Original product</span>
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-box-check text-amber-500"></i> Official warranty covered</span>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Tabbed Description & Support Section -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-sm mb-12">
        <div class="border-b border-slate-200 pb-4 mb-6 flex gap-6 text-sm font-bold flex-wrap">
            <button type="button" id="tabBtnDesc" onclick="switchProductTab('desc')" class="tab-nav-btn text-blue-700 border-b-2 border-blue-700 pb-4 -mb-4 transition cursor-pointer">
                Product Description
            </button>
            <button type="button" id="tabBtnReviews" onclick="switchProductTab('reviews')" class="tab-nav-btn text-slate-500 hover:text-slate-900 pb-4 -mb-4 transition cursor-pointer flex items-center gap-1.5">
                <span>Customer Reviews</span>
                <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-xs font-bold">{{ $product['reviews_count'] }}</span>
            </button>
        </div>

        <!-- Tab 1: Description -->
        <div id="tabContentDesc" class="product-rich-description text-slate-700 leading-relaxed text-sm sm:text-base">
            <div class="rich-html-content space-y-4">
                {!! $product['description'] ? (str_contains($product['description'], '<') ? $product['description'] : nl2br(e($product['description']))) : '<p class="text-slate-400">No detailed description provided for this product.</p>' !!}
            </div>

            @if(($product['product_type'] ?? '') === 'digital')
                <div class="mt-8 pt-6 border-t border-slate-100">
                    <h4 class="text-base font-bold text-slate-900 mb-2">How will you receive your order?</h4>
                    @if(!empty($product['access_instructions']))
                        <div class="rich-html-content">
                            {!! str_contains($product['access_instructions'], '<') ? $product['access_instructions'] : nl2br(e($product['access_instructions'])) !!}
                        </div>
                    @else
                        <p class="text-slate-600">Once your payment is confirmed, you will immediately receive account login credentials and quick setup guidelines on your registered email address and in your <a href="{{ route('user.subscriptions') }}" class="text-blue-600 font-semibold underline">Subscriptions Dashboard</a>.</p>
                    @endif
                </div>
            @else
                <div class="mt-8 pt-6 border-t border-slate-100">
                    <h4 class="text-base font-bold text-slate-900 mb-2">Delivery & Returns</h4>
                    <p class="text-slate-600">Standard delivery inside Dhaka within 24-48 hours and outside Dhaka within 2-4 working days. Cash on delivery and secure online payments available. Official warranty claim supported with invoice.</p>
                </div>
            @endif
        </div>

        <!-- Tab 2: Customer Reviews -->
        <div id="tabContentReviews" class="hidden space-y-8">

            <!-- Review Summary Top -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-6">
                    <div class="text-center">
                        <span class="text-4xl sm:text-5xl font-black text-slate-900 leading-none">
                            {{ number_format((float)($product['rating'] ?: 5.0), 1) }}
                        </span>
                        <div class="flex items-center justify-center gap-1 text-amber-400 text-sm mt-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-solid fa-star {{ $i <= round($product['rating'] ?: 5) ? 'text-amber-400' : 'text-slate-300' }}"></i>
                            @endfor
                        </div>
                        <span class="text-xs text-slate-500 mt-1 block">
                            @if($product['reviews_count'] > 0)
                                Based on {{ $product['reviews_count'] }} {{ Str::plural('review', $product['reviews_count']) }}
                            @else
                                No reviews submitted yet
                            @endif
                        </span>
                    </div>
                    <div class="hidden sm:block h-16 w-px bg-slate-200"></div>
                    <div class="space-y-1 text-xs text-slate-600">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-shield-check text-emerald-600"></i>
                            <span class="font-bold">100% Verified Customer Reviews</span>
                        </div>
                        <p class="text-[11px] text-slate-500 max-w-sm">
                            Only customers who bought and received physical products, or completed paid digital purchases can submit reviews.
                        </p>
                    </div>
                </div>

                @if($canReview)
                    <button type="button"
                            onclick="document.getElementById('reviewFormWrapper')?.scrollIntoView({ behavior: 'smooth' }); document.getElementById('reviewComment')?.focus();"
                            class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-blue-600/20 transition cursor-pointer">
                        <i class="fa-solid fa-pen mr-1.5"></i> Write a Review
                    </button>
                @endif
            </div>

            <!-- Review Submission Form (Only shown if user meets verification criteria) -->
            @if($canReview && isset($product['model']))
                <div id="reviewFormWrapper" class="p-6 rounded-2xl border-2 border-blue-200 bg-blue-50/30 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-blue-100">
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                                <i class="fa-solid fa-star text-amber-500"></i>
                                <span>{{ $userReview ? 'Update Your Review' : 'Share Your Experience' }}</span>
                            </h4>
                            <span class="text-xs text-emerald-700 font-semibold flex items-center gap-1 mt-0.5">
                                <i class="fa-solid fa-circle-check text-[11px]"></i> Verified Purchaser
                            </span>
                        </div>
                    </div>

                    <form action="{{ route('product.review.store', $product['model']->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <!-- Rating Stars Input -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Your Rating *
                            </label>
                            <div class="flex items-center gap-2" id="starRatingContainer">
                                <input type="hidden" name="rating" id="reviewRatingInput" value="{{ old('rating', $userReview->rating ?? 5) }}">
                                @for($s = 1; $s <= 5; $s++)
                                    <button type="button"
                                            onclick="setReviewStarRating({{ $s }})"
                                            class="star-pick-btn text-2xl text-amber-400 hover:scale-110 transition cursor-pointer"
                                            data-star="{{ $s }}">
                                        <i class="fa-solid fa-star"></i>
                                    </button>
                                @endfor
                                <span id="ratingLabel" class="text-xs font-bold text-slate-700 ml-2">5 Stars (Excellent)</span>
                            </div>
                        </div>

                        <!-- Review Comment -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Review Details & Experience *
                            </label>
                            <textarea name="comment"
                                      id="reviewComment"
                                      rows="3"
                                      required
                                      placeholder="How was the product quality, performance, and delivery experience? Share your feedback..."
                                      class="w-full p-3.5 rounded-xl border border-slate-300 text-sm bg-white focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10">{{ old('comment', $userReview->comment ?? '') }}</textarea>
                        </div>

                        <!-- Upload Multiple Photos -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Add Photos of Product (Optional, up to 5 photos)
                            </label>
                            <input type="file"
                                   name="photos[]"
                                   multiple
                                   accept="image/*"
                                   class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                            <span class="text-[11px] text-slate-400 mt-1 block">Supported formats: JPG, PNG, WEBP (Max 5MB each).</span>
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs sm:text-sm shadow-sm transition cursor-pointer">
                                <i class="fa-solid fa-paper-plane mr-1.5"></i> Submit Review
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Reviews List -->
            @if(isset($product['model']) && $product['model']->reviews->isNotEmpty())
                <div class="space-y-4 divide-y divide-slate-100">
                    @foreach($product['model']->reviews as $rev)
                        <div class="pt-5 first:pt-0 space-y-2.5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 font-extrabold flex items-center justify-center text-sm uppercase">
                                        {{ substr($rev->user->name ?? 'User', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-slate-900 text-sm">{{ $rev->user->name ?? 'Verified Buyer' }}</span>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                                <i class="fa-solid fa-circle-check text-[9px] mr-0.5"></i> Verified Purchase
                                            </span>
                                        </div>
                                        <span class="text-[11px] text-slate-400">{{ $rev->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1 text-amber-400 text-xs">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-solid fa-star {{ $i <= $rev->rating ? 'text-amber-400' : 'text-slate-200' }}"></i>
                                    @endfor
                                </div>
                            </div>

                            <p class="text-sm text-slate-700 leading-relaxed">{{ $rev->comment }}</p>

                            <!-- Attached Multiple Photos -->
                            @if(!empty($rev->image_urls))
                                <div class="flex items-center gap-2.5 pt-1 flex-wrap">
                                    @foreach($rev->image_urls as $imgUrl)
                                        <a href="{{ $imgUrl }}" target="_blank" class="w-16 h-16 rounded-xl border border-slate-200 overflow-hidden shrink-0 hover:opacity-90 transition block">
                                            <img src="{{ $imgUrl }}" alt="Review photo" class="w-full h-full object-cover">
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-12 text-center space-y-2">
                    <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center mx-auto text-xl">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <h5 class="font-bold text-slate-800 text-sm">No reviews yet</h5>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto">
                        Be the first verified customer to share your thoughts after completing your purchase!
                    </p>
                </div>
            @endif

        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    // Product detail tabs switching
    function switchProductTab(tabName) {
        const tabs = ['desc', 'reviews'];
        tabs.forEach(t => {
            const btn = document.getElementById('tabBtn' + t.charAt(0).toUpperCase() + t.slice(1));
            const content = document.getElementById('tabContent' + t.charAt(0).toUpperCase() + t.slice(1));
            if (btn && content) {
                if (t === tabName) {
                    btn.classList.add('text-blue-700', 'border-b-2', 'border-blue-700');
                    btn.classList.remove('text-slate-500');
                    content.classList.remove('hidden');
                } else {
                    btn.classList.remove('text-blue-700', 'border-b-2', 'border-blue-700');
                    btn.classList.add('text-slate-500');
                    content.classList.add('hidden');
                }
            }
        });
    }

    // Thumbnail preview switcher
    function selectProductThumb(btn, imgUrl) {
        const mainImg = document.getElementById('mainProductImage');
        if (mainImg) {
            mainImg.src = imgUrl;
        }
        document.querySelectorAll('.product-thumb-btn').forEach(b => {
            b.classList.remove('border-blue-600', 'ring-2', 'ring-blue-600/20');
            b.classList.add('border-slate-200');
        });
        if (btn) {
            btn.classList.add('border-blue-600', 'ring-2', 'ring-blue-600/20');
            btn.classList.remove('border-slate-200');
        }
    }

    // Dynamic Star Rating Picker
    function setReviewStarRating(rating) {
        const input = document.getElementById('reviewRatingInput');
        if (input) input.value = rating;

        const labels = {
            1: '1 Star (Poor)',
            2: '2 Stars (Fair)',
            3: '3 Stars (Good)',
            4: '4 Stars (Very Good)',
            5: '5 Stars (Excellent)'
        };

        const labelElem = document.getElementById('ratingLabel');
        if (labelElem && labels[rating]) {
            labelElem.textContent = labels[rating];
        }

        document.querySelectorAll('.star-pick-btn').forEach(btn => {
            const starVal = parseInt(btn.getAttribute('data-star') || 1);
            const icon = btn.querySelector('i');
            if (icon) {
                if (starVal <= rating) {
                    btn.className = 'star-pick-btn text-2xl text-amber-400 hover:scale-110 transition cursor-pointer';
                    icon.className = 'fa-solid fa-star';
                } else {
                    btn.className = 'star-pick-btn text-2xl text-slate-300 hover:scale-110 transition cursor-pointer';
                    icon.className = 'fa-solid fa-star';
                }
            }
        });
    }

    function selectProductPlan(btn, planId, price, oldPrice) {
        document.querySelectorAll('.plan-btn').forEach(b => {
            b.classList.remove('border-blue-600', 'bg-blue-50/60', 'ring-2', 'ring-blue-600/20');
            b.classList.add('border-slate-200', 'bg-white');
        });
        btn.classList.add('border-blue-600', 'bg-blue-50/60', 'ring-2', 'ring-blue-600/20');
        btn.classList.remove('border-slate-200', 'bg-white');

        const planInput = document.getElementById('selectedPlanId');
        if (planInput) {
            planInput.value = planId;
        }

        const dispPrice = document.getElementById('displayPrice');
        const dispOldPrice = document.getElementById('displayOldPrice');
        const dispDiscount = document.getElementById('displayDiscount');

        if (dispPrice) {
            dispPrice.textContent = '৳' + Number(price).toLocaleString();
        }
        if (dispOldPrice && oldPrice && oldPrice > price) {
            dispOldPrice.textContent = '৳' + Number(oldPrice).toLocaleString();
            dispOldPrice.classList.remove('hidden');
            if (dispDiscount) {
                const diff = oldPrice - price;
                dispDiscount.textContent = 'Save ৳' + Number(diff).toLocaleString();
                dispDiscount.classList.remove('hidden');
            }
        } else if (dispOldPrice) {
            dispOldPrice.classList.add('hidden');
            if (dispDiscount) dispDiscount.classList.add('hidden');
        }
    }

    function digigoBuyNow(productId, planId, quantity) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                product_id: productId,
                plan_id: planId,
                quantity: quantity
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.href = "{{ route('checkout') }}";
            } else {
                window.showDigigoToast(data.message || 'Error processing item', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            window.location.href = "{{ route('checkout') }}";
        });
    }
</script>
@endpush

