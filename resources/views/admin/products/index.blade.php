@extends('layouts.admin')

@section('title', 'Products Management - DigiGo')

@section('admin_content')
<!-- Page Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white">
            Products Catalog
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            Manage your physical gadgets & accessories and digital subscriptions & licenses.
        </p>
    </div>

    <div class="flex items-center gap-2.5">
        <!-- Export Products to Excel (CSV) Button -->
        <a href="{{ route('admin.products.export', request()->query()) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow-sm transition cursor-pointer" title="Export products catalog to Excel (CSV)">
            <i class="fa-solid fa-file-excel text-xs"></i>
            <span>Export to Excel (CSV)</span>
        </a>

        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-800 hover:bg-brand-700 text-white dark:bg-brand-600 dark:hover:bg-brand-700 text-sm font-semibold rounded-lg shadow-sm transition cursor-pointer">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Add New Product</span>
        </a>
    </div>
</div>

<!-- Metrics Quick Summary Cards -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Products</span>
            <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400 flex items-center justify-center text-xs">
                <i class="fa-solid fa-boxes-stacked"></i>
            </span>
        </div>
        <p class="text-2xl font-black text-slate-900 dark:text-white mt-2">{{ number_format($metrics['total']) }}</p>
    </div>

    <div class="p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Physical Items</span>
            <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400 flex items-center justify-center text-xs">
                <i class="fa-solid fa-truck-ramp-box"></i>
            </span>
        </div>
        <p class="text-2xl font-black text-slate-900 dark:text-white mt-2">{{ number_format($metrics['physical']) }}</p>
    </div>

    <div class="p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Digital / Subscriptions</span>
            <span class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 dark:bg-purple-950/60 dark:text-purple-400 flex items-center justify-center text-xs">
                <i class="fa-solid fa-cloud-arrow-down"></i>
            </span>
        </div>
        <p class="text-2xl font-black text-slate-900 dark:text-white mt-2">{{ number_format($metrics['digital']) }}</p>
    </div>

    <div class="p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Low Stock (Physical)</span>
            <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400 flex items-center justify-center text-xs">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </span>
        </div>
        <p class="text-2xl font-black text-slate-900 dark:text-white mt-2">{{ number_format($metrics['low_stock']) }}</p>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="mb-6 p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs">
    <form action="{{ route('admin.products.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">
        <!-- Search Input -->
        <div class="lg:col-span-4 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </div>
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Search name, SKU, or slug..."
                   class="w-full h-10 pl-9 pr-4 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 focus:bg-white text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 dark:focus:border-brand-500 transition">
        </div>

        <!-- Product Type Filter -->
        <div class="lg:col-span-2">
            <select name="product_type" class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-700 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                <option value="">All Types</option>
                <option value="physical" {{ request('product_type') === 'physical' ? 'selected' : '' }}>📦 Physical</option>
                <option value="digital" {{ request('product_type') === 'digital' ? 'selected' : '' }}>⚡ Digital / Sub</option>
            </select>
        </div>

        <!-- Category Filter -->
        <div class="lg:col-span-2">
            <select name="category_id" class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-700 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ (string) request('category_id') === (string) $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Status Filter -->
        <div class="lg:col-span-2">
            <select name="status" class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-700 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
        </div>

        <!-- Actions -->
        <div class="lg:col-span-2 flex items-center gap-2">
            <button type="submit" class="flex-1 h-10 bg-slate-800 hover:bg-slate-900 text-white text-xs sm:text-sm font-semibold rounded-lg transition dark:bg-slate-700 dark:hover:bg-slate-600">
                Filter
            </button>
            @if(request()->anyFilled(['search', 'product_type', 'category_id', 'status', 'is_featured']))
                <a href="{{ route('admin.products.index') }}" class="px-3 h-10 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm rounded-lg transition dark:bg-slate-800 dark:text-slate-300" title="Reset Filters">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Products Table Card -->
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs sm:text-sm min-w-[900px]">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 dark:bg-slate-950 dark:border-slate-800 dark:text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                <tr>
                    <th class="px-5 py-3.5 min-w-[280px]">Product</th>
                    <th class="px-5 py-3.5 whitespace-nowrap">Type</th>
                    <th class="px-5 py-3.5 whitespace-nowrap">Category & Brand</th>
                    <th class="px-5 py-3.5 whitespace-nowrap">Price</th>
                    <th class="px-5 py-3.5 whitespace-nowrap">Inventory / Validity</th>
                    <th class="px-5 py-3.5 whitespace-nowrap">Status</th>
                    <th class="px-5 py-3.5 text-right whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse ($products as $product)
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                        <!-- Product Column (Thumbnail + Title + Badges) -->
                        <td class="px-5 py-3.5">
                            <div class="flex items-start gap-3">
                                @if ($product->thumbnail_url)
                                    <img src="{{ $product->thumbnail_url }}"
                                         alt="{{ $product->name }}"
                                         class="w-12 h-12 rounded-lg object-cover border border-slate-200 dark:border-slate-700 shadow-xs shrink-0 bg-slate-50 dark:bg-slate-800">
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-400 shrink-0">
                                        <i class="fa-regular fa-image text-lg"></i>
                                    </div>
                                @endif

                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="font-bold text-slate-900 dark:text-white hover:text-brand-600 dark:hover:text-brand-400 transition text-sm">
                                            {{ $product->name }}
                                        </a>
                                        @if($product->is_featured)
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/50 dark:text-amber-300 dark:border-amber-800" title="Featured product">
                                                <i class="fa-solid fa-star text-[8px] text-amber-500"></i>
                                                Featured
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 mt-1 text-[11px] text-slate-400">
                                        <span class="font-mono text-slate-500 dark:text-slate-400 max-w-[220px] truncate" title="{{ $product->slug }}">{{ $product->slug }}</span>
                                        @if($product->sku)
                                            <span>•</span>
                                            <span class="whitespace-nowrap">SKU: <strong class="text-slate-600 dark:text-slate-300 font-mono">{{ $product->sku }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Product Type Column -->
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if ($product->isPhysical())
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800">
                                    <i class="fa-solid fa-box text-[10px]"></i>
                                    Physical
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200 dark:bg-purple-950/50 dark:text-purple-300 dark:border-purple-800">
                                    <i class="fa-solid fa-bolt text-[10px]"></i>
                                    Digital / Sub
                                </span>
                            @endif
                        </td>

                        <!-- Category & Brand -->
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <div class="text-xs">
                                <p class="font-semibold text-slate-800 dark:text-slate-200">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </p>
                                @if($product->brand)
                                    <p class="text-[11px] text-slate-400 mt-0.5">
                                        <i class="fa-solid fa-tag text-[9px] mr-1"></i>{{ $product->brand->name }}
                                    </p>
                                @endif
                            </div>
                        </td>

                        <!-- Price Column -->
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <div>
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="font-extrabold text-brand-700 dark:text-brand-400 text-sm">৳{{ number_format($product->sale_price) }}</span>
                                        <span class="text-slate-400 line-through text-xs">৳{{ number_format($product->price) }}</span>
                                    </div>
                                    <span class="inline-block mt-0.5 px-1.5 py-0.2 rounded text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-100 dark:bg-rose-950/50 dark:text-rose-400 dark:border-rose-900">
                                        -{{ $product->discount_percentage }}%
                                    </span>
                                @else
                                    <span class="font-bold text-slate-900 dark:text-white text-sm">৳{{ number_format($product->price) }}</span>
                                @endif
                            </div>
                        </td>

                        <!-- Inventory / Subscription Validity -->
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @php
                                $stock = $product->stock_quantity ?? 0;
                            @endphp
                            <div class="space-y-1">
                                <!-- Stock Badge (5 or less: Red | 5-10: Yellow/Amber | 10+: Green) -->
                                <div>
                                    @if($stock <= 5)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                            {{ $stock <= 0 ? 'Out of Stock (0)' : "Stock: {$stock}" }}
                                        </span>
                                    @elseif($stock <= 10)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Stock: {{ $stock }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Stock: {{ $stock > 999 ? '999+' : $stock }}
                                        </span>
                                    @endif
                                </div>

                                @if($product->isPhysical())
                                    <p class="text-[10px] text-slate-400">
                                        Low alert: {{ $product->low_stock_threshold ?? 5 }} pcs
                                    </p>
                                @else
                                    <div class="flex items-center gap-1.5 flex-wrap pt-0.5">
                                        @if($product->has_plans && $product->plans->count() > 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 dark:bg-indigo-950/50 dark:text-indigo-300 dark:border-indigo-800" title="{{ $product->plans->pluck('name')->join(', ') }}">
                                                <i class="fa-solid fa-list-check text-[9px]"></i>
                                                {{ $product->plans->count() }} Plans
                                            </span>
                                        @endif

                                        @if($product->default_validity)
                                            <span class="inline-flex items-center gap-1 text-[11px] text-slate-500 dark:text-slate-400">
                                                <i class="fa-solid fa-clock text-purple-500 text-[10px]"></i>
                                                {{ is_numeric($product->default_validity) ? $product->default_validity . ' Days' : $product->default_validity }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-[10px] text-slate-400 capitalize">
                                        Delivery: {{ str_replace('_', ' ', $product->digital_delivery_type ?? 'credentials') }}
                                    </p>
                                @endif
                            </div>
                        </td>

                        <!-- Status Column -->
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if ($product->status === 'active')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-[11px] font-bold uppercase bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 rounded-full border border-emerald-200 dark:border-emerald-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Active
                                </span>
                            @elseif ($product->status === 'inactive')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-[11px] font-bold uppercase bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 rounded-full border border-slate-200 dark:border-slate-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    Inactive
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-[11px] font-bold uppercase bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 rounded-full border border-amber-200 dark:border-amber-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Draft
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}" class="p-2 text-slate-500 hover:text-brand-600 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition" title="Edit Product">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <button type="button"
                                        class="js-custom-delete p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition cursor-pointer"
                                        data-action="{{ route('admin.products.destroy', $product) }}"
                                        data-title="Delete Product: {{ $product->name }}"
                                        data-message="Are you sure you want to delete '{{ $product->name }}'? Any images, media files, and subscription plans associated with it will also be permanently removed."
                                        title="Delete Product">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-solid fa-boxes-stacked text-3xl mb-3 text-slate-300 dark:text-slate-600"></i>
                                <p class="text-base font-semibold text-slate-700 dark:text-slate-300">No products found</p>
                                <p class="text-xs text-slate-400 mt-1">Get started by creating your first physical gadget or digital subscription.</p>
                                <a href="{{ route('admin.products.create') }}" class="mt-4 px-4 py-2 bg-brand-800 text-white rounded-lg text-xs font-semibold hover:bg-brand-700 transition">
                                    Add New Product
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($products->hasPages())
        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection

