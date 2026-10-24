@extends('layouts.site')

@section('title', $category->name . ' - DigiGo')

@section('content')
<div class="max-w-[1440px] mx-auto px-4 sm:px-6 py-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-6">
        <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Home</a>
        <span>/</span>
        <span class="text-slate-400">Categories</span>
        <span>/</span>
        <span class="text-slate-900 font-semibold">{{ $category->name }}</span>
    </nav>

    <!-- Category Banner Header -->
    <div class="bg-gradient-to-r from-blue-700 via-indigo-700 to-purple-800 rounded-2xl p-6 sm:p-10 text-white shadow-lg mb-8 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4 sm:gap-6">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center p-2 shrink-0 border border-white/20 shadow-inner">
                    @if($category->image_url)
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-full h-full object-cover rounded-xl">
                    @elseif($category->image && str_starts_with($category->image, 'http'))
                        <img src="{{ $category->image }}" alt="{{ $category->name }}" class="w-full h-full object-cover rounded-xl">
                    @else
                        <i class="fa-solid fa-layer-group text-2xl sm:text-3xl text-white"></i>
                    @endif
                </div>
                <div>
                    <span class="px-2.5 py-0.5 rounded-full bg-white/20 text-white text-xs font-semibold backdrop-blur-sm">Verified Category</span>
                    <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight mt-1">{{ $category->name }}</h1>
                    <p class="text-xs sm:text-sm text-white/80 mt-1">
                        Showing {{ $products->total() }} available {{ Str::plural('product', $products->total()) }}
                    </p>
                </div>
            </div>

            <!-- Search Inside Category -->
            <div class="w-full md:w-80">
                <form action="{{ route('category.show', $category->slug) }}" method="GET" class="relative">
                    @if(request('type'))
                        <input type="hidden" name="type" value="{{ request('type') }}">
                    @endif
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           placeholder="Search in {{ $category->name }}..."
                           class="w-full h-11 pl-4 pr-11 rounded-xl bg-white/15 backdrop-blur-md border border-white/30 text-white placeholder-white/70 text-sm focus:outline-none focus:bg-white focus:text-slate-900 focus:placeholder-slate-400 transition">
                    <button type="submit" class="absolute right-2 top-2 w-7 h-7 rounded-lg bg-white text-blue-700 flex items-center justify-center text-xs font-bold shadow-sm">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Left Sidebar: Category Filter & Other Categories (3 cols) -->
        <aside class="lg:col-span-3 space-y-6">

            <!-- Other Categories Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 pb-3 border-b border-slate-100 flex items-center justify-between">
                    <span>All Categories</span>
                    <i class="fa-solid fa-list text-slate-400"></i>
                </h3>
                <ul class="mt-3 space-y-1 text-sm">
                    @foreach($allCategories as $otherCat)
                        <li>
                            <a href="{{ route('category.show', $otherCat->slug) }}"
                               class="flex items-center justify-between px-3 py-2 rounded-lg transition {{ $otherCat->id === $category->id ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }}">
                                <span class="truncate">{{ $otherCat->name }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $otherCat->id === $category->id ? 'bg-blue-200/60 text-blue-800' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $otherCat->products_count }}
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Product Type Filter -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 pb-3 border-b border-slate-100">
                    Product Type
                </h3>
                <div class="mt-3 space-y-2 text-sm">
                    <a href="{{ route('category.show', array_merge(['slug' => $category->slug], request()->except('type', 'page'))) }}"
                       class="flex items-center gap-2 px-3 py-1.5 rounded-lg {{ !request('type') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                        <i class="fa-solid fa-border-all text-xs"></i>
                        <span>All Types</span>
                    </a>
                    <a href="{{ route('category.show', array_merge(['slug' => $category->slug, 'type' => 'digital'], request()->except('type', 'page'))) }}"
                       class="flex items-center gap-2 px-3 py-1.5 rounded-lg {{ request('type') === 'digital' ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                        <i class="fa-solid fa-bolt text-amber-500 text-xs"></i>
                        <span>Digital Subscriptions & Keys</span>
                    </a>
                    <a href="{{ route('category.show', array_merge(['slug' => $category->slug, 'type' => 'physical'], request()->except('type', 'page'))) }}"
                       class="flex items-center gap-2 px-3 py-1.5 rounded-lg {{ request('type') === 'physical' ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                        <i class="fa-solid fa-box-open text-indigo-500 text-xs"></i>
                        <span>Physical Products</span>
                    </a>
                </div>
            </div>

        </aside>

        <!-- Right Content: Products Grid (9 cols) -->
        <main class="lg:col-span-9 space-y-6">

            <!-- Sorting & Filters Bar -->
            <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs flex flex-wrap items-center justify-between gap-4">
                <div class="text-xs text-slate-500">
                    Showing <span class="font-bold text-slate-800">{{ $products->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-800">{{ $products->lastItem() ?? 0 }}</span> of <span class="font-bold text-slate-800">{{ $products->total() }}</span> items
                </div>

                <div class="flex items-center gap-2">
                    <label for="sortSelect" class="text-xs font-semibold text-slate-500">Sort by:</label>
                    <select id="sortSelect"
                            onchange="location = this.value;"
                            class="h-9 px-3 rounded-lg border border-slate-300 text-xs font-semibold bg-white text-slate-800 focus:outline-none focus:border-blue-600">
                        <option value="{{ route('category.show', array_merge(['slug' => $category->slug, 'sort' => 'latest'], request()->except('sort', 'page'))) }}" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Latest Items</option>
                        <option value="{{ route('category.show', array_merge(['slug' => $category->slug, 'sort' => 'popular'], request()->except('sort', 'page'))) }}" {{ request('sort') === 'popular' ? 'selected' : '' }}>Popular & Top Rated</option>
                        <option value="{{ route('category.show', array_merge(['slug' => $category->slug, 'sort' => 'price_low'], request()->except('sort', 'page'))) }}" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="{{ route('category.show', array_merge(['slug' => $category->slug, 'sort' => 'price_high'], request()->except('sort', 'page'))) }}" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="{{ route('category.show', array_merge(['slug' => $category->slug, 'sort' => 'name_asc'], request()->except('sort', 'page'))) }}" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                    </select>
                </div>
            </div>

            <!-- Products Grid -->
            @if($products->isEmpty())
                <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-xs">
                    <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">No products found in this category</h3>
                    <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">
                        @if(request('q'))
                            No products matched your search term "{{ request('q') }}". Try searching with different keywords.
                        @else
                            Products are being added to this category soon. Please check back later!
                        @endif
                    </p>
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 mt-5 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs shadow-sm transition">
                        <i class="fa-solid fa-arrow-left"></i> Back to Homepage
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach($products as $prod)
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md hover:border-blue-400 transition duration-300 flex flex-col group">
                            
                            <!-- Product Image & Badges -->
                            <div class="relative aspect-square overflow-hidden bg-slate-50">
                                @if($prod->thumbnail_url)
                                    <img src="{{ $prod->thumbnail_url }}" alt="{{ $prod->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300 {{ ($prod->isPhysical() && $prod->stock_quantity <= 0) ? 'grayscale opacity-75' : '' }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300 text-4xl">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif

                                <!-- Type Badge -->
                                <span class="absolute top-3 left-3 {{ $prod->isDigital() ? 'bg-purple-600' : 'bg-blue-600' }} text-white text-[10px] font-extrabold uppercase px-2 py-0.5 rounded shadow">
                                    {{ $prod->product_type }}
                                </span>

                                @if($prod->isPhysical() && $prod->stock_quantity <= 0)
                                    <span class="absolute top-3 right-3 bg-slate-800 text-white text-[10px] font-extrabold uppercase px-2 py-0.5 rounded shadow">
                                        Sold Out
                                    </span>
                                @elseif($prod->discount_percentage)
                                    <span class="absolute top-3 right-3 bg-rose-600 text-white text-[10px] font-extrabold px-2 py-0.5 rounded shadow">
                                        {{ $prod->discount_percentage }}% OFF
                                    </span>
                                @endif

                                @php
                                    $inWishlist = in_array($prod->id, array_keys(session('wishlist_items', [])));
                                @endphp
                                <button type="button"
                                        onclick="digigoToggleWishlist({{ $prod->id }}, this)"
                                        class="absolute bottom-3 right-3 w-8 h-8 rounded-full bg-white/90 {{ $inWishlist ? 'text-red-500' : 'text-slate-500 hover:text-red-500' }} hover:bg-white flex items-center justify-center transition shadow-xs cursor-pointer"
                                        title="{{ $inWishlist ? 'Remove from wishlist' : 'Add to wishlist' }}">
                                    <i class="{{ $inWishlist ? 'fa-solid fa-heart text-red-500' : 'fa-regular fa-heart' }} text-xs"></i>
                                </button>
                            </div>

                            <!-- Product Info -->
                            <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                                <div>
                                    <!-- Rating -->
                                    <div class="flex items-center gap-1.5 text-xs text-amber-400 mb-1">
                                        <i class="fa-solid fa-star text-[10px]"></i>
                                        <span class="font-bold text-slate-700">{{ number_format((float)$prod->rating, 1) }}</span>
                                        <span class="text-slate-400 text-[11px]">({{ $prod->reviews_count }})</span>
                                    </div>

                                    <h3 class="font-bold text-slate-900 text-sm leading-snug group-hover:text-blue-600 transition line-clamp-2">
                                        <a href="{{ route('product.show', $prod->slug) }}">
                                            {{ $prod->name }}
                                        </a>
                                    </h3>

                                    @if($prod->isDigital() && $prod->default_validity)
                                        <p class="text-[11px] text-slate-400 mt-1 flex items-center gap-1">
                                            <i class="fa-solid fa-clock text-indigo-500 text-[10px]"></i>
                                            <span>{{ is_numeric($prod->default_validity) ? $prod->default_validity . ' Days Validity' : $prod->default_validity }}</span>
                                        </p>
                                    @endif
                                </div>

                                <!-- Price & Details -->
                                <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                                    <div>
                                        <span class="block text-lg font-extrabold text-blue-700">৳{{ number_format($prod->effective_price) }}</span>
                                        @if($prod->sale_price)
                                            <span class="text-xs text-slate-400 line-through">৳{{ number_format($prod->price) }}</span>
                                        @endif
                                    </div>

                                    @if($prod->isPhysical() && $prod->stock_quantity <= 0)
                                        <button type="button"
                                                disabled
                                                class="h-9 px-3.5 rounded-xl bg-slate-300 text-slate-500 font-semibold text-xs flex items-center gap-1.5 cursor-not-allowed"
                                                title="Out of stock">
                                            <i class="fa-solid fa-ban text-xs"></i>
                                            <span>Sold Out</span>
                                        </button>
                                    @else
                                        <button type="button"
                                                onclick="digigoAddToCart({{ $prod->id }}, null, 1, this)"
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

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="pt-6">
                        {{ $products->links() }}
                    </div>
                @endif
            @endif

        </main>
    </div>

</div>
@endsection
