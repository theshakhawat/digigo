@extends('layouts.site')

@section('title', ($query ? 'Search: ' . $query : 'Browse All Products') . ' - DigiGo')

@section('content')
<div class="max-w-[1440px] mx-auto px-4 sm:px-6 py-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-6">
        <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Home</a>
        <span>/</span>
        <span class="text-slate-900 font-semibold">Search Catalog</span>
        @if($query)
            <span>/</span>
            <span class="text-blue-600 font-medium truncate max-w-xs">"{{ $query }}"</span>
        @endif
    </nav>

    <!-- Top Filter & Search Header Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full border border-blue-100">
                    <i class="fa-solid fa-sparkles text-[10px]"></i> Smart Product Search
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-2">
                    @if($query)
                        Results for "<span class="text-blue-600">{{ $query }}</span>"
                    @else
                        Explore All Products & Subscriptions
                    @endif
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Showing <strong class="text-slate-800">{{ $results->total() }}</strong> verified {{ Str::plural('product', $results->total()) }} found
                </p>
            </div>

            <!-- Fast Search Input inside page -->
            <form action="{{ route('search') }}" method="GET" class="flex items-center gap-2 max-w-md w-full">
                @if($categoryId)<input type="hidden" name="category" value="{{ $categoryId }}">@endif
                @if($brandId)<input type="hidden" name="brand" value="{{ $brandId }}">@endif
                @if($productType)<input type="hidden" name="type" value="{{ $productType }}">@endif
                @if($minPrice)<input type="hidden" name="min_price" value="{{ $minPrice }}">@endif
                @if($maxPrice)<input type="hidden" name="max_price" value="{{ $maxPrice }}">@endif
                @if($sort)<input type="hidden" name="sort" value="{{ $sort }}">@endif

                <div class="relative flex-1">
                    <input type="text"
                           name="q"
                           value="{{ $query }}"
                           placeholder="Search by title, SKU, keywords..."
                           class="w-full h-11 pl-10 pr-4 rounded-xl border border-slate-300 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition shadow-xs">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-slate-400 text-sm"></i>
                </div>
                <button type="submit" class="h-11 px-5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-600/20 transition flex items-center gap-1.5 shrink-0">
                    <span>Search</span>
                </button>
            </form>
        </div>

        <!-- Active Filter Tags (if any applied) -->
        @php
            $hasActiveFilters = $query || $categoryId || $brandId || $productType || $minPrice || $maxPrice || $inStock || $featuredOnly || $hasDiscount;
        @endphp
        @if($hasActiveFilters)
            <div class="flex flex-wrap items-center gap-2 pt-4 mt-4 border-t border-slate-100 text-xs">
                <span class="font-bold text-slate-600">Active Filters:</span>

                @if($query)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 text-slate-800 rounded-full font-medium">
                        Title: "{{ $query }}"
                        <a href="{{ request()->fullUrlWithQuery(['q' => null]) }}" class="text-slate-400 hover:text-red-500"><i class="fa-solid fa-xmark"></i></a>
                    </span>
                @endif

                @if($categoryId)
                    @php $matchedCat = $categories->firstWhere('id', $categoryId) ?: $categories->firstWhere('slug', $categoryId); @endphp
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-800 rounded-full font-medium">
                        Category: {{ $matchedCat->name ?? $categoryId }}
                        <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="text-blue-400 hover:text-red-500"><i class="fa-solid fa-xmark"></i></a>
                    </span>
                @endif

                @if($brandId)
                    @php $matchedBrand = $brands->firstWhere('id', $brandId) ?: $brands->firstWhere('slug', $brandId); @endphp
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 text-purple-800 rounded-full font-medium">
                        Brand: {{ $matchedBrand->name ?? $brandId }}
                        <a href="{{ request()->fullUrlWithQuery(['brand' => null]) }}" class="text-purple-400 hover:text-red-500"><i class="fa-solid fa-xmark"></i></a>
                    </span>
                @endif

                @if($productType)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-800 rounded-full font-medium capitalize">
                        Type: {{ $productType }}
                        <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}" class="text-emerald-400 hover:text-red-500"><i class="fa-solid fa-xmark"></i></a>
                    </span>
                @endif

                @if($minPrice || $maxPrice)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-800 rounded-full font-medium">
                        Price: ৳{{ number_format($minPrice ?: $priceBoundaries['min']) }} - ৳{{ number_format($maxPrice ?: $priceBoundaries['max']) }}
                        <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => null]) }}" class="text-amber-400 hover:text-red-500"><i class="fa-solid fa-xmark"></i></a>
                    </span>
                @endif

                @if($inStock)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 text-slate-800 rounded-full font-medium">
                        In Stock Only
                        <a href="{{ request()->fullUrlWithQuery(['in_stock' => null]) }}" class="text-slate-400 hover:text-red-500"><i class="fa-solid fa-xmark"></i></a>
                    </span>
                @endif

                @if($featuredOnly)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 text-slate-800 rounded-full font-medium">
                        Featured Only
                        <a href="{{ request()->fullUrlWithQuery(['featured' => null]) }}" class="text-slate-400 hover:text-red-500"><i class="fa-solid fa-xmark"></i></a>
                    </span>
                @endif

                @if($hasDiscount)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 text-red-800 rounded-full font-medium">
                        On Sale
                        <a href="{{ request()->fullUrlWithQuery(['has_discount' => null]) }}" class="text-red-400 hover:text-red-500"><i class="fa-solid fa-xmark"></i></a>
                    </span>
                @endif

                <a href="{{ route('search') }}" class="text-xs font-bold text-red-600 hover:underline ml-2">Clear All</a>
            </div>
        @endif
    </div>

    <!-- Main Content Layout (Sidebar Filters + Products Grid) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">


        <!-- ==========================================
             RIGHT COLUMN: Products Listing (Col 9)
             ========================================== -->
        <main class="lg:col-span-9 space-y-6">

            <!-- Sorting & View Bar -->
            <div class="bg-white rounded-2xl border border-slate-200 px-5 py-3.5 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="text-xs text-slate-600 font-medium">
                    Showing <strong class="text-slate-900">{{ $results->firstItem() ?: 0 }}</strong> to <strong class="text-slate-900">{{ $results->lastItem() ?: 0 }}</strong> of <strong class="text-slate-900">{{ $results->total() }}</strong> results
                </div>

                <div class="flex items-center gap-3 self-end sm:self-auto">
                    <label for="sortSelector" class="text-xs font-semibold text-slate-500 shrink-0">Sort By:</label>
                    <select id="sortSelector"
                            onchange="const u = new URL(window.location.href); u.searchParams.set('sort', this.value); window.location.href = u.toString();"
                            class="h-9 px-3 rounded-xl border border-slate-300 text-xs font-semibold bg-white text-slate-800 focus:outline-none focus:border-blue-600 transition shadow-xs">
                        <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Latest Arrivals</option>
                        <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="rating" {{ $sort === 'rating' ? 'selected' : '' }}>Customer Rating</option>
                        <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Most Popular</option>
                        <option value="title_asc" {{ $sort === 'title_asc' ? 'selected' : '' }}>Alphabetical (A - Z)</option>
                    </select>
                </div>
            </div>

            <!-- Products Grid -->
            @if($results->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($results as $item)
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md hover:border-blue-300 transition duration-200 flex flex-col group">
                            
                            <!-- Product Image & Badges -->
                            <div class="relative aspect-square overflow-hidden bg-slate-50">
                                <a href="{{ route('product.show', $item->slug) }}" class="block w-full h-full">
                                    <img src="{{ $item->thumbnail_url ?: 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=400&q=80' }}"
                                         alt="{{ $item->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                </a>

                                <!-- Badges -->
                                <div class="absolute top-2.5 left-2.5 flex flex-col gap-1">
                                    @if($item->isPhysical() && $item->stock_quantity <= 0)
                                        <span class="bg-slate-800 text-white text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-md shadow-xs">
                                            Sold Out
                                        </span>
                                    @elseif($item->discount_percentage)
                                        <span class="bg-red-600 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-md shadow-xs">
                                            -{{ $item->discount_percentage }}%
                                        </span>
                                    @endif
                                    <span class="bg-slate-900/80 backdrop-blur-xs text-white text-[10px] font-bold px-2 py-0.5 rounded-md capitalize shadow-xs">
                                        {{ $item->product_type }}
                                    </span>
                                </div>

                                <!-- Wishlist Shortcut -->
                                @php
                                    $inWishlist = in_array($item->id, array_keys(session('wishlist_items', [])));
                                @endphp
                                <button type="button"
                                        onclick="digigoToggleWishlist({{ $item->id }}, this)"
                                        class="absolute top-2.5 right-2.5 w-8 h-8 rounded-full bg-white/90 {{ $inWishlist ? 'text-red-500' : 'text-slate-500 hover:text-red-500' }} hover:bg-white flex items-center justify-center transition shadow-xs cursor-pointer"
                                        title="{{ $inWishlist ? 'Remove from wishlist' : 'Add to wishlist' }}">
                                    <i class="{{ $inWishlist ? 'fa-solid fa-heart text-red-500' : 'fa-regular fa-heart' }} text-xs"></i>
                                </button>
                            </div>

                            <!-- Product Information -->
                            <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                                <div>
                                    <!-- Category & Brand -->
                                    <div class="flex items-center gap-1.5 text-[11px] text-slate-500 mb-1 truncate">
                                        @if($item->category)
                                            <a href="{{ route('category.show', $item->category->slug) }}" class="text-blue-600 hover:underline font-semibold truncate">{{ $item->category->name }}</a>
                                        @endif
                                        @if($item->brand)
                                            <span>•</span>
                                            <span class="truncate">{{ $item->brand->name }}</span>
                                        @endif
                                    </div>

                                    <!-- Title -->
                                    <h3 class="font-bold text-slate-900 text-sm leading-snug group-hover:text-blue-600 transition line-clamp-2">
                                        <a href="{{ route('product.show', $item->slug) }}">{{ $item->name }}</a>
                                    </h3>

                                    <!-- Rating -->
                                    <div class="flex items-center gap-1.5 text-xs text-amber-400 mt-1.5">
                                        <i class="fa-solid fa-star text-[10px]"></i>
                                        <span class="font-bold text-slate-700 text-xs">{{ number_format($item->rating ?: 4.8, 1) }}</span>
                                        <span class="text-[11px] text-slate-400 font-normal">({{ $item->reviews_count ?: 5 }})</span>
                                    </div>
                                </div>

                                <!-- Price & Action -->
                                <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                                    <div>
                                        <span class="block text-base font-extrabold text-blue-700">৳{{ number_format($item->effective_price) }}</span>
                                        @if($item->sale_price && $item->sale_price < $item->price)
                                            <span class="text-[11px] text-slate-400 line-through">৳{{ number_format($item->price) }}</span>
                                        @endif
                                    </div>

                                    @if($item->isPhysical() && $item->stock_quantity <= 0)
                                        <button type="button"
                                                disabled
                                                class="h-9 px-3.5 rounded-xl bg-slate-300 text-slate-500 font-semibold text-xs flex items-center gap-1.5 cursor-not-allowed"
                                                title="Out of stock">
                                            <i class="fa-solid fa-ban text-xs"></i>
                                            <span>Sold Out</span>
                                        </button>
                                    @else
                                        <button type="button"
                                                onclick="digigoAddToCart({{ $item->id }}, null, 1, this)"
                                                class="h-9 px-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs flex items-center gap-1.5 transition shadow-sm cursor-pointer"
                                                title="Add to Cart">
                                            <i class="fa-solid fa-cart-shopping text-xs"></i>
                                            <span>Add to Cart</span>
                                        </button>
                                    @endif
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                <!-- Pagination Links -->
                <div class="pt-4">
                    {{ $results->links() }}
                </div>

            @else
                <!-- Empty State -->
                <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-xs space-y-4">
                    <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-2xl">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">No products matched your criteria</h3>
                    <p class="text-sm text-slate-500 max-w-md mx-auto">
                        Try adjusting your keywords, widening the price range, or clearing filters to see more results.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('search') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/20 transition">
                            <i class="fa-solid fa-rotate-left"></i> Reset All Filters
                        </a>
                    </div>
                </div>
            @endif

        </main>

        
        <!-- ==========================================
             RIGHT SIDEBAR: Filters (Col 3)
             ========================================== -->
        <aside class="lg:col-span-3">
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-6 sticky top-24">

                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-sliders text-blue-600"></i> Filters
                    </h2>
                    @if($hasActiveFilters)
                        <a href="{{ route('search') }}" class="text-xs text-blue-600 hover:underline font-semibold">Reset</a>
                    @endif
                </div>

                <form id="filterForm" action="{{ route('search') }}" method="GET" class="space-y-6">
                    <!-- Preserve search query -->
                    @if($query)
                        <input type="hidden" name="q" value="{{ $query }}">
                    @endif

                    <!-- Filter 1: Product Type (Tabs) -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Product Type</label>
                        <div class="grid grid-cols-3 gap-1 p-1 bg-slate-100 rounded-xl text-xs font-bold">
                            <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}"
                               class="py-1.5 text-center rounded-lg transition {{ !$productType ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                                All
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['type' => 'digital']) }}"
                               class="py-1.5 text-center rounded-lg transition {{ $productType === 'digital' ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                                Digital
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['type' => 'physical']) }}"
                               class="py-1.5 text-center rounded-lg transition {{ $productType === 'physical' ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                                Physical
                            </a>
                        </div>
                    </div>

                    <!-- Filter 2: Pricing Range Slider / Inputs -->
                    <div class="pt-4 border-t border-slate-100">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2 flex items-center justify-between">
                            <span>Price Range (৳)</span>
                            <span class="text-[11px] text-slate-400 font-normal">Min / Max</span>
                        </label>
                        <div class="flex items-center gap-2">
                            <div class="relative flex-1">
                                <span class="absolute left-2.5 top-2 text-xs font-semibold text-slate-400">৳</span>
                                <input type="number"
                                       name="min_price"
                                       placeholder="{{ $priceBoundaries['min'] }}"
                                       value="{{ $minPrice }}"
                                       class="w-full h-9 pl-6 pr-2 rounded-lg border border-slate-300 text-xs text-slate-800 focus:outline-none focus:border-blue-600">
                            </div>
                            <span class="text-slate-400 font-bold">-</span>
                            <div class="relative flex-1">
                                <span class="absolute left-2.5 top-2 text-xs font-semibold text-slate-400">৳</span>
                                <input type="number"
                                       name="max_price"
                                       placeholder="{{ $priceBoundaries['max'] }}"
                                       value="{{ $maxPrice }}"
                                       class="w-full h-9 pl-6 pr-2 rounded-lg border border-slate-300 text-xs text-slate-800 focus:outline-none focus:border-blue-600">
                            </div>
                        </div>
                        <button type="submit" class="w-full mt-2 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition">
                            Apply Price
                        </button>
                    </div>

                    <!-- Filter 3: Categories -->
                    <div class="pt-4 border-t border-slate-100">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2.5 flex items-center justify-between">
                            <span>Category</span>
                            <span class="text-[11px] text-slate-400 font-normal">{{ $categories->count() }}</span>
                        </label>
                        <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1 text-xs">
                            <label class="flex items-center justify-between py-1 px-2 rounded-md hover:bg-slate-50 cursor-pointer transition {{ !$categoryId ? 'font-bold text-blue-600' : 'text-slate-700' }}">
                                <span class="flex items-center gap-2">
                                    <input type="radio" name="category" value="" onchange="this.form.submit()" {{ !$categoryId ? 'checked' : '' }} class="text-blue-600 focus:ring-0">
                                    <span>All Categories</span>
                                </span>
                            </label>
                            @foreach($categories as $cat)
                                <label class="flex items-center justify-between py-1 px-2 rounded-md hover:bg-slate-50 cursor-pointer transition {{ $categoryId == $cat->id || $categoryId == $cat->slug ? 'font-bold text-blue-600 bg-blue-50/50' : 'text-slate-700' }}">
                                    <span class="flex items-center gap-2 truncate">
                                        <input type="radio" name="category" value="{{ $cat->id }}" onchange="this.form.submit()" {{ $categoryId == $cat->id || $categoryId == $cat->slug ? 'checked' : '' }} class="text-blue-600 focus:ring-0">
                                        <span class="truncate">{{ $cat->name }}</span>
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-medium ml-1">({{ $cat->products_count }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Filter 4: Brands -->
                    @if($brands->isNotEmpty())
                        <div class="pt-4 border-t border-slate-100">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2.5 flex items-center justify-between">
                                <span>Brand</span>
                                <span class="text-[11px] text-slate-400 font-normal">{{ $brands->count() }}</span>
                            </label>
                            <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1 text-xs">
                                <label class="flex items-center justify-between py-1 px-2 rounded-md hover:bg-slate-50 cursor-pointer transition {{ !$brandId ? 'font-bold text-blue-600' : 'text-slate-700' }}">
                                    <span class="flex items-center gap-2">
                                        <input type="radio" name="brand" value="" onchange="this.form.submit()" {{ !$brandId ? 'checked' : '' }} class="text-blue-600 focus:ring-0">
                                        <span>All Brands</span>
                                    </span>
                                </label>
                                @foreach($brands as $b)
                                    <label class="flex items-center justify-between py-1 px-2 rounded-md hover:bg-slate-50 cursor-pointer transition {{ $brandId == $b->id || $brandId == $b->slug ? 'font-bold text-blue-600 bg-blue-50/50' : 'text-slate-700' }}">
                                        <span class="flex items-center gap-2 truncate">
                                            <input type="radio" name="brand" value="{{ $b->id }}" onchange="this.form.submit()" {{ $brandId == $b->id || $brandId == $b->slug ? 'checked' : '' }} class="text-blue-600 focus:ring-0">
                                            <span class="truncate">{{ $b->name }}</span>
                                        </span>
                                        <span class="text-[10px] text-slate-400 font-medium ml-1">({{ $b->products_count }})</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Filter 5: Additional Features / Stock -->
                    <div class="pt-4 border-t border-slate-100 space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Preferences & Deals</label>
                        
                        <label class="flex items-center gap-2 text-xs text-slate-700 cursor-pointer select-none">
                            <input type="checkbox" name="in_stock" value="1" onchange="this.form.submit()" {{ $inStock ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-0">
                            <span>In Stock Available Only</span>
                        </label>

                        <label class="flex items-center gap-2 text-xs text-slate-700 cursor-pointer select-none">
                            <input type="checkbox" name="featured" value="1" onchange="this.form.submit()" {{ $featuredOnly ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-0">
                            <span>Featured Products Only</span>
                        </label>

                        <label class="flex items-center gap-2 text-xs text-slate-700 cursor-pointer select-none">
                            <input type="checkbox" name="has_discount" value="1" onchange="this.form.submit()" {{ $hasDiscount ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-0">
                            <span>On Sale / Discounted</span>
                        </label>
                    </div>

                    <!-- Preserve Sort Option -->
                    <input type="hidden" name="sort" value="{{ $sort }}">
                </form>

            </div>
        </aside>

    </div>

</div>
@endsection
