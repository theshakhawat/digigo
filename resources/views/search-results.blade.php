@extends('layouts.site')

@section('title', 'Search Results - DigiGo')

@section('content')
<div class="max-w-[1440px] mx-auto px-4 sm:px-6 py-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-6">
        <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Home</a>
        <span>/</span>
        <span class="text-slate-900 font-semibold">Search Results</span>
    </nav>

    <!-- Search Header -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Search Catalog</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-1">
                @if($query)
                    Search results for: "<span class="text-blue-600">{{ $query }}</span>"
                @else
                    All Available Products & Services
                @endif
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Showing {{ count($results) }} verified digital products</p>
        </div>

        <!-- Filter / Sort Dropdown -->
        <div class="flex items-center gap-3">
            <span class="text-xs font-semibold text-slate-500">Sort by:</span>
            <select class="h-10 px-3 rounded-lg border border-slate-300 text-xs font-semibold bg-white text-slate-800 focus:outline-none focus:border-blue-600">
                <option>Most Popular</option>
                <option>Price: Low to High</option>
                <option>Price: High to Low</option>
                <option>Highest Rated</option>
            </select>
        </div>
    </div>

    <!-- Results Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($results as $item)
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md hover:border-blue-300 transition duration-300 flex flex-col group">
                
                <!-- Product Image -->
                <div class="relative aspect-video sm:aspect-square overflow-hidden bg-slate-100">
                    <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    <span class="absolute top-3 left-3 bg-blue-600 text-white text-[11px] font-bold px-2.5 py-1 rounded-md shadow">
                        {{ $item['category'] }}
                    </span>
                    <a href="{{ route('wishlist') }}" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 text-slate-500 hover:text-red-500 hover:bg-white flex items-center justify-center transition shadow" title="Add to wishlist">
                        <i class="fa-regular fa-heart text-xs"></i>
                    </a>
                </div>

                <!-- Info -->
                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div>
                        <!-- Rating -->
                        <div class="flex items-center gap-1.5 text-xs text-amber-400 mb-1.5">
                            <i class="fa-solid fa-star text-[10px]"></i>
                            <span class="font-bold text-slate-700">{{ $item['rating'] }}</span>
                        </div>

                        <h3 class="font-bold text-slate-900 text-base leading-snug group-hover:text-blue-600 transition">
                            <a href="{{ route('product.show', $item['slug']) }}">{{ $item['title'] }}</a>
                        </h3>
                    </div>

                    <!-- Price & Add button -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="block text-xl font-extrabold text-blue-700">৳{{ number_format($item['price']) }}</span>
                            <span class="text-xs text-slate-400 line-through">৳{{ number_format($item['old_price']) }}</span>
                        </div>

                        <a href="{{ route('cart') }}" class="h-9 px-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs flex items-center gap-1.5 transition shadow-sm">
                            <i class="fa-solid fa-cart-plus"></i> Buy
                        </a>
                    </div>
                </div>

            </div>
        @endforeach
    </div>

</div>
@endsection
