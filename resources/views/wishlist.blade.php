@extends('layouts.site')

@section('title', 'My Wishlist - DigiGo')

@section('content')
<div class="max-w-[1440px] mx-auto px-4 sm:px-6 py-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-6">
        <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Home</a>
        <span>/</span>
        <span class="text-slate-900 font-semibold">Wishlist</span>
    </nav>

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
            My Wishlist ({{ count($wishlistItems) }})
        </h1>
        <a href="{{ url('/') }}" class="text-sm font-semibold text-blue-600 hover:underline">
            <i class="fa-solid fa-plus mr-1"></i> Add More Items
        </a>
    </div>

    @if(count($wishlistItems) > 0)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="divide-y divide-slate-200">
                @foreach($wishlistItems as $item)
                    <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        
                        <!-- Left info -->
                        <div class="flex items-center gap-4 flex-1">
                            <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-20 h-20 rounded-xl object-cover border border-slate-200 shrink-0">
                            <div>
                                <h3 class="font-bold text-slate-900 text-base hover:text-blue-600 transition">
                                    <a href="{{ route('product.show', $item['slug']) }}">{{ $item['title'] }}</a>
                                </h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-emerald-700 bg-emerald-50 text-xs font-semibold px-2 py-0.5 rounded border border-emerald-200">
                                        {{ $item['stock'] }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Price & Action Buttons -->
                        <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto pt-3 sm:pt-0 border-t sm:border-0 border-slate-100">
                            <div class="text-right">
                                <span class="block font-bold text-slate-900 text-lg">৳{{ number_format($item['price']) }}</span>
                                <span class="text-xs text-slate-400 line-through">৳{{ number_format($item['old_price']) }}</span>
                            </div>

                            <a href="{{ route('cart') }}" class="h-10 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm flex items-center gap-2 transition shadow-sm">
                                <i class="fa-solid fa-cart-plus"></i> Add to Cart
                            </a>

                            <button type="button" class="w-9 h-9 rounded-lg bg-slate-100 text-slate-500 hover:bg-red-50 hover:text-red-600 flex items-center justify-center transition" title="Remove from wishlist">
                                <i class="fa-regular fa-trash-can text-sm"></i>
                            </button>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
            <i class="fa-regular fa-heart text-5xl text-slate-300 mb-4 block"></i>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Your wishlist is currently empty</h3>
            <p class="text-sm text-slate-500 mb-6">Explore our catalog and bookmark your favorite software or subscription services.</p>
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-blue-600 text-white font-bold text-sm hover:bg-blue-700 transition">
                Start Exploring
            </a>
        </div>
    @endif

</div>
@endsection
