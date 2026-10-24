@extends('layouts.site')

@section('title', $brand->name . ' Products - DigiGo')

@section('content')
<div class="max-w-[1440px] mx-auto px-4 sm:px-6 py-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-6">
        <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Home</a>
        <span>/</span>
        <a href="{{ route('search') }}" class="hover:text-blue-600 transition">Brands</a>
        <span>/</span>
        <span class="text-slate-900 font-semibold">{{ $brand->name }}</span>
    </nav>

    <!-- Brand Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-900 rounded-2xl p-6 sm:p-10 text-white shadow-lg mb-8 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-56 h-56 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4 sm:gap-6">
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-white flex items-center justify-center p-3 shrink-0 border border-white/20 shadow-xl overflow-hidden">
                    @if($brand->image_url)
                        <img src="{{ $brand->image_url }}" alt="{{ $brand->name }}" class="w-full h-full object-contain">
                    @else
                        <span class="text-2xl sm:text-3xl font-black text-slate-900">{{ substr($brand->name, 0, 2) }}</span>
                    @endif
                </div>
                <div>
                    <span class="px-2.5 py-0.5 rounded-full bg-white/20 text-white text-xs font-semibold backdrop-blur-sm">
                        Official Brand Collection
                    </span>
                    <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight mt-1.5">{{ $brand->name }}</h1>
                    <p class="text-xs sm:text-sm text-white/80 mt-1">
                        {{ $products->total() }} genuine {{ Str::plural('product', $products->total()) }} available from {{ $brand->name }}
                    </p>
                </div>
            </div>

            <!-- Search Inside Brand -->
            <div class="w-full md:w-80">
                <form action="{{ route('brand.show', $brand->slug) }}" method="GET" class="relative">
                    @if(request('type'))
                        <input type="hidden" name="type" value="{{ request('type') }}">
                    @endif
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           placeholder="Search in {{ $brand->name }}..."
                           class="w-full h-11 pl-4 pr-11 rounded-xl bg-white/15 backdrop-blur-md border border-white/30 text-white placeholder-white/70 text-sm focus:outline-none focus:bg-white focus:text-slate-900 focus:placeholder-slate-400 transition">
                    <button type="submit" class="absolute right-2 top-2 w-7 h-7 rounded-lg bg-white text-blue-700 flex items-center justify-center text-xs font-bold shadow-sm">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Left Sidebar: Filter Options (3 cols) -->
        <aside class="lg:col-span-3 space-y-6">

            <!-- Brand Categories Filter (if any) -->
            @if($brandCategories->isNotEmpty())
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 pb-3 border-b border-slate-100 flex items-center justify-between">
                        <span>{{ $brand->name }} Categories</span>
                        <i class="fa-solid fa-layer-group text-slate-400"></i>
                    </h3>
                    <ul class="mt-3 space-y-1 text-sm">
                        <li>
                            <a href="{{ route('brand.show', array_merge(['slug' => $brand->slug], request()->except('category', 'page'))) }}"
                               class="flex items-center justify-between px-3 py-2 rounded-lg transition {{ !request('category') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }}">
                                <span>All Categories</span>
                                <span class="text-xs px-2 py-0.5 rounded-full {{ !request('category') ? 'bg-blue-200/60 text-blue-800' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $products->total() }}
                                </span>
                            </a>
                        </li>
                        @foreach($brandCategories as $bCat)
                            <li>
                                <a href="{{ route('brand.show', array_merge(['slug' => $brand->slug, 'category' => $bCat->slug], request()->except('page'))) }}"
                                   class="flex items-center justify-between px-3 py-2 rounded-lg transition {{ request('category') == $bCat->slug || request('category') == $bCat->id ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }}">
                                    <span class="truncate">{{ $bCat->name }}</span>
                                    <span class="text-xs px-2 py-0.5 rounded-full {{ request('category') == $bCat->slug || request('category') == $bCat->id ? 'bg-blue-200/60 text-blue-800' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $bCat->products_count }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Product Type Tabs -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 pb-3 border-b border-slate-100">
                    Product Type
                </h3>
                <div class="grid grid-cols-3 gap-1 p-1 bg-slate-100 rounded-xl text-xs font-bold mt-3">
                    <a href="{{ route('brand.show', array_merge(['slug' => $brand->slug], request()->except('type', 'page'))) }}"
                       class="py-1.5 text-center rounded-lg transition {{ !request('type') ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        All
                    </a>
                    <a href="{{ route('brand.show', array_merge(['slug' => $brand->slug, 'type' => 'digital'], request()->except('page'))) }}"
                       class="py-1.5 text-center rounded-lg transition {{ request('type') === 'digital' ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Digital
                    </a>
                    <a href="{{ route('brand.show', array_merge(['slug' => $brand->slug, 'type' => 'physical'], request()->except('page'))) }}"
                       class="py-1.5 text-center rounded-lg transition {{ request('type') === 'physical' ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Physical
                    </a>
                </div>
            </div>

            <!-- Other Brands List -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 pb-3 border-b border-slate-100 flex items-center justify-between">
                    <span>Other Top Brands</span>
                    <i class="fa-solid fa-tags text-slate-400"></i>
                </h3>
                <ul class="mt-3 space-y-1 text-sm max-h-72 overflow-y-auto pr-1">
                    @foreach($allBrands as $otherBrand)
                        <li>
                            <a href="{{ route('brand.show', $otherBrand->slug) }}"
                               class="flex items-center justify-between px-3 py-2 rounded-lg transition {{ $otherBrand->id === $brand->id ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }}">
                                <span class="truncate">{{ $otherBrand->name }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $otherBrand->id === $brand->id ? 'bg-blue-200/60 text-blue-800' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $otherBrand->products_count }}
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

        </aside>

        <!-- Right Main: Products Grid (9 cols) -->
        <main class="lg:col-span-9 space-y-6">

            <!-- Sort Bar -->
            <div class="bg-white rounded-2xl border border-slate-200 px-5 py-3.5 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="text-xs text-slate-500">
                    Showing <strong class="text-slate-900">{{ $products->firstItem() ?: 0 }} - {{ $products->lastItem() ?: 0 }}</strong> of <strong class="text-slate-900">{{ $products->total() }}</strong> products for <strong class="text-blue-600">{{ $brand->name }}</strong>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-xs font-semibold text-slate-500">Sort:</span>
                    <select onchange="const u = new URL(window.location.href); u.searchParams.set('sort', this.value); window.location.href = u.toString();"
                            class="h-9 px-3 rounded-lg border border-slate-300 text-xs font-semibold bg-white text-slate-800 focus:outline-none focus:border-blue-600">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest Products</option>
                        <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                        <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                    </select>
                </div>
            </div>

            <!-- Product Cards Grid -->
            @if($products->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($products as $item)
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md hover:border-blue-300 transition duration-200 flex flex-col group">
                            
                            <!-- Product Image -->
                            <div class="relative aspect-square overflow-hidden bg-slate-50">
                                <a href="{{ route('product.show', $item->slug) }}" class="block w-full h-full">
                                    <img src="{{ $item->thumbnail_url ?: 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=400&q=80' }}"
                                         alt="{{ $item->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300 {{ ($item->isPhysical() && $item->stock_quantity <= 0) ? 'grayscale opacity-75' : '' }}">
                                </a>

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
                                    <div class="text-[11px] text-slate-500 mb-1 truncate">
                                        @if($item->category)
                                            <a href="{{ route('category.show', $item->category->slug) }}" class="text-blue-600 hover:underline font-semibold">{{ $item->category->name }}</a>
                                        @endif
                                    </div>

                                    <h3 class="font-bold text-slate-900 text-sm leading-snug group-hover:text-blue-600 transition line-clamp-2">
                                        <a href="{{ route('product.show', $item->slug) }}">{{ $item->name }}</a>
                                    </h3>

                                    <div class="flex items-center gap-1.5 text-xs text-amber-400 mt-1.5">
                                        <i class="fa-solid fa-star text-[10px]"></i>
                                        <span class="font-bold text-slate-700 text-xs">{{ number_format($item->rating ?: 4.8, 1) }}</span>
                                        <span class="text-[11px] text-slate-400 font-normal">({{ $item->reviews_count ?: 5 }})</span>
                                    </div>
                                </div>

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

                <!-- Pagination -->
                <div class="pt-4">
                    {{ $products->links() }}
                </div>
            @else
                <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-xs space-y-3">
                    <div class="w-14 h-14 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-xl">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">No products found for {{ $brand->name }}</h3>
                    <p class="text-xs text-slate-500">Try clearing filters or search query to see all items.</p>
                    <a href="{{ route('brand.show', $brand->slug) }}" class="inline-block mt-2 px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 transition">
                        Reset Filters
                    </a>
                </div>
            @endif

        </main>

    </div>

</div>
@endsection

