@extends('layouts.admin')

@section('title', 'Add New Product - DigiGo')

@push('styles')
<!-- Quill.js Rich Text Editor CSS -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
<style>
    .ql-toolbar.ql-snow {
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
        border-color: #e2e8f0;
        background-color: #f8fafc;
    }
    .ql-container.ql-snow {
        border-bottom-left-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
        border-color: #e2e8f0;
        background-color: #ffffff;
        font-family: inherit;
        font-size: 0.875rem;
        min-height: 220px;
    }
    .dark .ql-toolbar.ql-snow {
        border-color: #334155;
        background-color: #1e293b;
    }
    .dark .ql-container.ql-snow {
        border-color: #334155;
        background-color: #0f172a;
        color: #f8fafc;
    }
    .dark .ql-snow .ql-stroke { stroke: #94a3b8; }
    .dark .ql-snow .ql-fill { fill: #94a3b8; }
    .dark .ql-snow .ql-picker { color: #94a3b8; }
    .dark .ql-snow .ql-picker-options { background-color: #1e293b; border-color: #334155; }
</style>
@endpush

@section('admin_content')
<!-- Page Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <nav class="flex items-center gap-2 text-xs text-slate-500 mb-1" aria-label="Breadcrumb">
            <a href="{{ route('admin.products.index') }}" class="hover:text-brand-600 transition">Products</a>
            <span>/</span>
            <span class="text-slate-800 dark:text-slate-200 font-semibold">New Product</span>
        </nav>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white">
            Add New Product
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
            Create a physical product or a digital subscription / license key.
        </p>
    </div>

    <div>
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 text-slate-700 dark:text-slate-200 text-xs sm:text-sm font-semibold rounded-lg shadow-xs transition">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            <span>Back to Products</span>
        </a>
    </div>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm" class="space-y-6">
    @csrf

    <!-- 1. PRODUCT TYPE SELECTOR (Visual Choice Cards) -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs p-5 sm:p-6">
        <label class="block text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-3">
            Select Product Type <span class="text-rose-500">*</span>
        </label>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Physical Option -->
            <label class="relative flex items-start gap-4 p-4 rounded-xl border-2 cursor-pointer transition select-none product-type-card {{ old('product_type', 'physical') === 'physical' ? 'border-brand-600 bg-brand-50/40 dark:bg-brand-950/20 dark:border-brand-500' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600' }}">
                <input type="radio" name="product_type" value="physical" class="sr-only" {{ old('product_type', 'physical') === 'physical' ? 'checked' : '' }} onchange="switchProductType('physical')">
                <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 flex items-center justify-center shrink-0 text-lg">
                    <i class="fa-solid fa-box"></i>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-slate-900 dark:text-white">Physical Product</span>
                        <span class="w-4 h-4 rounded-full border border-slate-300 dark:border-slate-600 flex items-center justify-center type-indicator">
                            <span class="w-2 h-2 rounded-full bg-brand-600 {{ old('product_type', 'physical') === 'physical' ? '' : 'hidden' }}"></span>
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Tangible goods requiring inventory stock & courier shipping (e.g., Chargers, Earbuds, Gadgets, Cables).
                    </p>
                </div>
            </label>

            <!-- Digital Option -->
            <label class="relative flex items-start gap-4 p-4 rounded-xl border-2 cursor-pointer transition select-none product-type-card {{ old('product_type') === 'digital' ? 'border-brand-600 bg-brand-50/40 dark:bg-brand-950/20 dark:border-brand-500' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600' }}">
                <input type="radio" name="product_type" value="digital" class="sr-only" {{ old('product_type') === 'digital' ? 'checked' : '' }} onchange="switchProductType('digital')">
                <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-700 dark:bg-purple-950 dark:text-purple-300 flex items-center justify-center shrink-0 text-lg">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-slate-900 dark:text-white">Digital Product / Subscription</span>
                        <span class="w-4 h-4 rounded-full border border-slate-300 dark:border-slate-600 flex items-center justify-center type-indicator">
                            <span class="w-2 h-2 rounded-full bg-brand-600 {{ old('product_type') === 'digital' ? '' : 'hidden' }}"></span>
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Digital subscriptions, validity plans (1 Mo, 6 Mo, 1 Yr), license keys, accounts (e.g. Prime Video, YouTube, Office 365).
                    </p>
                </div>
            </label>
        </div>
        @error('product_type')
            <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- LEFT COLUMN (Main details: 8 cols) -->
        <div class="lg:col-span-8 space-y-6">

            <!-- Basic Information Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs p-5 sm:p-6 space-y-4">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-brand-600"></i>
                    Basic Information
                </h2>

                <!-- Product Title -->
                <div>
                    <label for="name" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                        Product Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name') }}"
                           placeholder="e.g. Microsoft 365 Personal or DigiGo Fast Type-C Charger 30W"
                           class="w-full h-11 px-3.5 text-sm bg-slate-50 border @error('name') border-rose-500 @else border-slate-200 @enderror rounded-lg focus:outline-none focus:border-brand-600 focus:bg-white text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 dark:focus:border-brand-500 transition"
                           required
                           autofocus>
                    @error('name')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                        Slug <span class="text-slate-400 font-normal text-xs">(Auto-generated from title if left blank)</span>
                    </label>
                    <input type="text"
                           name="slug"
                           id="slug"
                           value="{{ old('slug') }}"
                           placeholder="e.g. microsoft-365-personal"
                           class="w-full h-11 px-3.5 font-mono text-sm bg-slate-50 border @error('slug') border-rose-500 @else border-slate-200 @enderror rounded-lg focus:outline-none focus:border-brand-600 focus:bg-white text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 dark:focus:border-brand-500 transition">
                    @error('slug')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category & Brand -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="category_id" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                            Category
                        </label>
                        <select name="category_id" id="category_id" class="w-full h-11 px-3 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-800 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="brand_id" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                            Brand
                        </label>
                        <select name="brand_id" id="brand_id" class="w-full h-11 px-3 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-800 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Short Description -->
                <div>
                    <label for="short_description" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                        Short Description <span class="text-slate-400 font-normal text-xs">(Appears on preview cards)</span>
                    </label>
                    <textarea name="short_description"
                              id="short_description"
                              rows="2"
                              placeholder="Brief highlights of this product..."
                              class="w-full p-3 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 focus:bg-white text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 transition">{{ old('short_description') }}</textarea>
                    @error('short_description')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Full Description with Rich Text Editor -->
                <div>
                    <label for="description" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5 flex items-center justify-between">
                        <span>Full Product Details & Overview (Rich Formatted)</span>
                        <span class="text-[11px] font-normal text-slate-400">Headings, bold, lists, bullets & tables</span>
                    </label>
                    <input type="hidden" name="description" id="hiddenDescription" value="{{ old('description') }}">
                    <div id="quillEditorContainer">
                        {!! old('description') !!}
                    </div>
                    @error('description')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Key Highlights / Bullet Features Repeater -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-300">
                            Key Highlight Bullets
                        </label>
                        <button type="button" onclick="addFeatureRow()" class="text-xs text-brand-600 hover:text-brand-700 font-bold flex items-center gap-1 cursor-pointer">
                            <i class="fa-solid fa-plus text-[10px]"></i> Add Bullet
                        </button>
                    </div>
                    <div id="featuresContainer" class="space-y-2">
                        @php
                            $oldFeatures = old('features', ['100% Genuine and authentic guaranteed', 'Fast delivery & full warranty support']);
                        @endphp
                        @foreach($oldFeatures as $feature)
                            <div class="flex items-center gap-2 feature-row">
                                <input type="text"
                                       name="features[]"
                                       value="{{ $feature }}"
                                       placeholder="e.g. 1 Month full replacement warranty"
                                       class="flex-1 h-9 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100">
                                <button type="button" onclick="this.closest('.feature-row').remove()" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-rose-600 rounded-lg transition" title="Remove bullet">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- 2. PHYSICAL SPECIFIC CARD (Toggled when physical is selected) -->
            <div id="physicalFieldsCard" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs p-5 sm:p-6 space-y-4 {{ old('product_type', 'physical') === 'physical' ? '' : 'hidden' }}">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-box"></i>
                    </span>
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200">
                            Physical Inventory & Specs
                        </h2>
                        <p class="text-[11px] text-slate-400">Stock quantity, weight, dimensions, warranty and box contents.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <!-- SKU -->
                    <div>
                        <label for="sku" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            SKU (Item Code)
                        </label>
                        <input type="text"
                               name="sku"
                               id="sku"
                               value="{{ old('sku') }}"
                               placeholder="e.g. DG-CHG-30W"
                               class="w-full h-10 px-3 font-mono text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100">
                        @error('sku')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock Quantity -->
                    <div>
                        <label for="stock_quantity" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Current Stock
                        </label>
                        <input type="number"
                               name="stock_quantity"
                               id="stock_quantity"
                               value="{{ old('stock_quantity', 10) }}"
                               min="0"
                               class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100">
                        @error('stock_quantity')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Shipping Charge -->
                    <div>
                        <label for="shipping_charge" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 flex items-center justify-between">
                            <span>Shipping Charge (৳)</span>
                            <span class="text-[10px] text-emerald-600 font-semibold">Def: ৳20</span>
                        </label>
                        <input type="number"
                               step="0.01"
                               min="0"
                               name="shipping_charge"
                               id="shipping_charge"
                               value="{{ old('shipping_charge', '20.00') }}"
                               placeholder="20.00"
                               class="w-full h-10 px-3 text-xs sm:text-sm font-bold text-brand-700 dark:text-brand-400 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 dark:bg-slate-800 dark:border-slate-700">
                        @error('shipping_charge')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Low Stock Alert -->
                    <div>
                        <label for="low_stock_threshold" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Low Stock Alert
                        </label>
                        <input type="number"
                               name="low_stock_threshold"
                               id="low_stock_threshold"
                               value="{{ old('low_stock_threshold', 5) }}"
                               min="1"
                               class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Weight -->
                    <div>
                        <label for="weight" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Weight
                        </label>
                        <input type="text"
                               name="weight"
                               id="weight"
                               value="{{ old('weight') }}"
                               placeholder="e.g. 250g / 0.5kg"
                               class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100">
                    </div>

                    <!-- Dimensions -->
                    <div>
                        <label for="dimensions" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Dimensions
                        </label>
                        <input type="text"
                               name="dimensions"
                               id="dimensions"
                               value="{{ old('dimensions') }}"
                               placeholder="e.g. 10 x 5 x 2 cm"
                               class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100">
                    </div>

                    <!-- Color -->
                    <div>
                        <label for="color" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Color / Variant
                        </label>
                        <input type="text"
                               name="color"
                               id="color"
                               value="{{ old('color') }}"
                               placeholder="e.g. Phantom Black"
                               class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Warranty Period -->
                    <div>
                        <label for="warranty_period" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Warranty Period
                        </label>
                        <input type="text"
                               name="warranty_period"
                               id="warranty_period"
                               value="{{ old('warranty_period') }}"
                               placeholder="e.g. 6 Months Brand Replacement Warranty"
                               class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100">
                    </div>

                    <!-- Package Contents -->
                    <div>
                        <label for="package_contents" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Package Contents (What's in the Box)
                        </label>
                        <input type="text"
                               name="package_contents"
                               id="package_contents"
                               value="{{ old('package_contents') }}"
                               placeholder="e.g. 1x Earbuds, 1x Charging Case, 1x Type-C Cable"
                               class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100">
                    </div>
                </div>
            </div>

            <!-- 3. DIGITAL SPECIFIC CARD (Toggled when digital is selected) -->
            <div id="digitalFieldsCard" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs p-5 sm:p-6 space-y-4 {{ old('product_type') === 'digital' ? '' : 'hidden' }}">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <span class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 dark:bg-purple-950 dark:text-purple-400 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-bolt"></i>
                    </span>
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200">
                            Digital Product & Subscription Setup
                        </h2>
                        <p class="text-[11px] text-slate-400">Delivery method, validity plans (1 Mo, 6 Mo, 1 Yr), credentials or software downloads.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Delivery Type -->
                    <div>
                        <label for="digital_delivery_type" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Digital Delivery Method <span class="text-rose-500">*</span>
                        </label>
                        <select name="digital_delivery_type" id="digital_delivery_type" class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-800 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                            <option value="credentials" {{ old('digital_delivery_type', 'credentials') === 'credentials' ? 'selected' : '' }}>🔑 Private Account / Login Credentials (Prime Video, Netflix)</option>
                            <option value="direct_activation" {{ old('digital_delivery_type') === 'direct_activation' ? 'selected' : '' }}>✉️ Direct Activation on Customer Email (YouTube, Canva Pro)</option>
                            <option value="license_key" {{ old('digital_delivery_type') === 'license_key' ? 'selected' : '' }}>🎟️ License Key / Serial Code (Office 365, Windows)</option>
                            <option value="file_download" {{ old('digital_delivery_type') === 'file_download' ? 'selected' : '' }}>📦 File Download / Direct Cloud Link</option>
                        </select>
                        @error('digital_delivery_type')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Single Default Validity (in Days) -->
                    <div id="defaultValidityWrapper">
                        <label for="default_validity" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Subscription Validity (Days) <span class="text-slate-400 font-normal text-[11px]">(e.g. 30, 90, 365)</span>
                        </label>
                        <div class="relative">
                            <input type="number"
                                   name="default_validity"
                                   id="default_validity"
                                   min="1"
                                   step="1"
                                   value="{{ old('default_validity', '30') }}"
                                   placeholder="30"
                                   class="w-full h-10 pl-3 pr-14 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 font-medium">
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs font-semibold text-slate-400 dark:text-slate-500 pointer-events-none">
                                Days
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Access / Setup Instructions for Customer -->
                <div>
                    <label for="access_instructions" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Post-Purchase Access / Setup Guidelines (Sent to customer upon purchase)
                    </label>
                    <textarea name="access_instructions"
                              id="access_instructions"
                              rows="3"
                              placeholder="e.g. Credentials will be sent to your email. Login at primevideo.com or open app. For support contact our WhatsApp."
                              class="w-full p-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 focus:bg-white text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 transition">{{ old('access_instructions') }}</textarea>
                </div>

                <!-- Optional Download Link & File -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label for="download_link" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Direct Download Link <span class="text-slate-400 font-normal text-[11px]">(Optional Google Drive / Cloud URL)</span>
                        </label>
                        <input type="url"
                               name="download_link"
                               id="download_link"
                               value="{{ old('download_link') }}"
                               placeholder="https://..."
                               class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100">
                    </div>

                    <div>
                        <label for="download_file" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Digital File Upload <span class="text-slate-400 font-normal text-[11px]">(Optional zip/pdf/exe up to 50MB)</span>
                        </label>
                        <input type="file"
                               name="download_file"
                               id="download_file"
                               class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 dark:file:bg-slate-800 dark:file:text-purple-400 border border-slate-200 dark:border-slate-700 rounded-lg p-1">
                    </div>
                </div>

                <!-- MULTI-PLAN SUBSCRIPTION REPEATER (e.g. 1 Month, 6 Months, 1 Year) -->
                <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox"
                                   name="has_plans"
                                   id="has_plans"
                                   value="1"
                                   {{ old('has_plans') ? 'checked' : '' }}
                                   onchange="togglePlansSection(this.checked)"
                                   class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-slate-300">
                            <div>
                                <span class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200">
                                    Enable Multiple Subscription Plans / Durations
                                </span>
                                <p class="text-[11px] text-slate-400">Offer multiple durations (e.g. 1 Month, 6 Months, 1 Year) with different pricing.</p>
                            </div>
                        </label>

                        <button type="button"
                                id="addPlanBtn"
                                onclick="addPlanRow()"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-lg transition {{ old('has_plans') ? '' : 'hidden' }}">
                            <i class="fa-solid fa-plus text-[10px]"></i> Add Plan Tier
                        </button>
                    </div>

                    <div id="plansContainer" class="space-y-3 {{ old('has_plans') ? '' : 'hidden' }}">
                        <div id="plansList" class="space-y-3">
                            <!-- Javascript populated or pre-populated rows -->
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN (Pricing, Images, Publishing: 4 cols) -->
        <div class="lg:col-span-4 space-y-6">

            <!-- Publishing & Status Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs p-5 sm:p-6 space-y-4">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-cloud-arrow-up text-brand-600"></i>
                    Publishing
                </h2>

                <div>
                    <label for="status" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Status
                    </label>
                    <select name="status" id="status" class="w-full h-10 px-3 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-800 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active (Visible on Store)</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive (Hidden)</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>

                <div class="pt-2 border-t border-slate-100 dark:border-slate-800">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox"
                               name="is_featured"
                               id="is_featured"
                               value="1"
                               {{ old('is_featured') ? 'checked' : '' }}
                               class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-slate-300">
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                            Feature on Homepage
                        </span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-brand-800 hover:bg-brand-700 text-white font-bold rounded-lg shadow-sm transition flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-floppy-disk text-sm"></i>
                        <span>Save & Publish Product</span>
                    </button>
                </div>
            </div>

            <!-- Pricing Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs p-5 sm:p-6 space-y-4">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-bangladeshi-taka-sign text-brand-600"></i>
                    Pricing (BDT ৳)
                </h2>

                <div>
                    <label for="price" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Regular Price (৳) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number"
                           name="price"
                           id="price"
                           value="{{ old('price') }}"
                           step="0.01"
                           min="0"
                           placeholder="0.00"
                           class="w-full h-10 px-3 text-sm bg-slate-50 border @error('price') border-rose-500 @else border-slate-200 @enderror rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 font-semibold"
                           required>
                    @error('price')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sale_price" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Sale / Discounted Price (৳) <span class="text-slate-400 font-normal text-[11px]">(Optional)</span>
                    </label>
                    <input type="number"
                           name="sale_price"
                           id="sale_price"
                           value="{{ old('sale_price') }}"
                           step="0.01"
                           min="0"
                           placeholder="0.00"
                           class="w-full h-10 px-3 text-sm bg-slate-50 border @error('sale_price') border-rose-500 @else border-slate-200 @enderror rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 font-semibold">
                    @error('sale_price')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cost_price" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Cost / Purchase Price (৳) <span class="text-slate-400 font-normal text-[11px]">(For admin profit analytics)</span>
                    </label>
                    <input type="number"
                           name="cost_price"
                           id="cost_price"
                           value="{{ old('cost_price') }}"
                           step="0.01"
                           min="0"
                           placeholder="0.00"
                           class="w-full h-10 px-3 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100">
                </div>
            </div>

            <!-- Thumbnail Image Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs p-5 sm:p-6 space-y-4">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-image text-brand-600"></i>
                    Main Product Image
                </h2>

                <div class="space-y-3">
                    <div id="thumbPreviewContainer" class="w-full aspect-square rounded-xl border-2 border-dashed border-slate-200 dark:border-slate-700 flex items-center justify-center bg-slate-50 dark:bg-slate-800 overflow-hidden text-slate-400">
                        <img id="thumbPreview" src="" alt="Thumbnail preview" class="w-full h-full object-cover hidden">
                        <div id="thumbPlaceholder" class="flex flex-col items-center justify-center p-4 text-center">
                            <i class="fa-regular fa-image text-3xl mb-1 text-slate-300 dark:text-slate-600"></i>
                            <span class="text-xs text-slate-400">No image selected</span>
                        </div>
                    </div>

                    <input type="file"
                           name="thumbnail"
                           id="thumbnail"
                           accept="image/*"
                           class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 dark:file:bg-slate-800 dark:file:text-brand-400 cursor-pointer border border-slate-200 dark:border-slate-700 rounded-lg p-1">
                    <p class="text-[11px] text-slate-400">PNG, JPG, WEBP up to 3MB.</p>
                    @error('thumbnail')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Gallery Images Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs p-5 sm:p-6 space-y-4">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-images text-brand-600"></i>
                    Additional Gallery Images
                </h2>

                <div>
                    <input type="file"
                           name="gallery[]"
                           id="gallery"
                           multiple
                           accept="image/*"
                           class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 dark:file:bg-slate-800 dark:file:text-brand-400 cursor-pointer border border-slate-200 dark:border-slate-700 rounded-lg p-1">
                    <p class="text-[11px] text-slate-400 mt-1">Select multiple photos for product gallery.</p>
                    @error('gallery')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

        </div>

    </div>
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
    // 1. Switch between Physical and Digital Product Types
    function switchProductType(type) {
        const physicalCard = document.getElementById('physicalFieldsCard');
        const digitalCard = document.getElementById('digitalFieldsCard');

        document.querySelectorAll('.product-type-card').forEach(card => {
            const input = card.querySelector('input[name="product_type"]');
            const dot = card.querySelector('.type-indicator span');
            if (input.value === type) {
                card.classList.add('border-brand-600', 'bg-brand-50/40', 'dark:bg-brand-950/20', 'dark:border-brand-500');
                card.classList.remove('border-slate-200', 'dark:border-slate-700');
                dot?.classList.remove('hidden');
            } else {
                card.classList.remove('border-brand-600', 'bg-brand-50/40', 'dark:bg-brand-950/20', 'dark:border-brand-500');
                card.classList.add('border-slate-200', 'dark:border-slate-700');
                dot?.classList.add('hidden');
            }
        });

        if (type === 'physical') {
            physicalCard.classList.remove('hidden');
            digitalCard.classList.add('hidden');
            const shipInput = document.getElementById('shipping_charge');
            if (shipInput && (!shipInput.value || shipInput.value === '0' || shipInput.value === '0.00')) {
                shipInput.value = '20.00';
            }
        } else {
            physicalCard.classList.add('hidden');
            digitalCard.classList.remove('hidden');
            const shipInput = document.getElementById('shipping_charge');
            if (shipInput) {
                shipInput.value = '0.00';
            }
        }
    }

    // 2. Toggle Subscription Multi-Plans Section
    function togglePlansSection(enable) {
        const container = document.getElementById('plansContainer');
        const addBtn = document.getElementById('addPlanBtn');
        const validityWrapper = document.getElementById('defaultValidityWrapper');

        if (enable) {
            container.classList.remove('hidden');
            addBtn.classList.remove('hidden');
            if (document.querySelectorAll('.plan-row').length === 0) {
                addPlanRow('1 Month', '30', '', '', 100, true);
                addPlanRow('6 Months', '180', '', '', 50, false);
                addPlanRow('1 Year', '365', '', '', 25, false);
            }
        } else {
            container.classList.add('hidden');
            addBtn.classList.add('hidden');
        }
    }

    // 3. Add Feature Highlight Bullet Row
    function addFeatureRow(text = '') {
        const container = document.getElementById('featuresContainer');
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2 feature-row';
        div.innerHTML = `
            <input type="text"
                   name="features[]"
                   value="${text}"
                   placeholder="e.g. 1 Month full replacement warranty"
                   class="flex-1 h-9 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100">
            <button type="button" onclick="this.closest('.feature-row').remove()" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-rose-600 rounded-lg transition" title="Remove bullet">
                <i class="fa-solid fa-trash-can text-xs"></i>
            </button>
        `;
        container.appendChild(div);
    }

    // 4. Add Subscription Plan Row
    let planIndex = 0;
    function addPlanRow(name = '', validity = '', price = '', salePrice = '', stock = 100, isDefault = false) {
        const list = document.getElementById('plansList');
        const idx = planIndex++;
        const row = document.createElement('div');
        row.className = 'p-3.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl space-y-3 plan-row relative';
        row.innerHTML = `
            <div class="flex items-center justify-between pb-1 border-b border-slate-200/60 dark:border-slate-700/60">
                <span class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                    <i class="fa-solid fa-layer-group text-indigo-500"></i> Plan Tier
                </span>
                <button type="button" onclick="this.closest('.plan-row').remove()" class="text-xs text-rose-500 hover:text-rose-700 font-semibold flex items-center gap-1">
                    <i class="fa-solid fa-trash-can text-[10px]"></i> Remove
                </button>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">Plan Name *</label>
                    <input type="text" name="plans[${idx}][name]" value="${name}" placeholder="e.g. 1 Month" class="w-full h-8 px-2.5 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg" required>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">Validity (Days)</label>
                    <div class="relative">
                        <input type="number" min="1" step="1" name="plans[${idx}][validity]" value="${validity}" placeholder="30" class="w-full h-8 pl-2.5 pr-11 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg">
                        <span class="absolute inset-y-0 right-0 pr-2 flex items-center text-[10px] font-semibold text-slate-400 dark:text-slate-500 pointer-events-none">Days</span>
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">Regular Price (৳) *</label>
                    <input type="number" step="0.01" min="0" name="plans[${idx}][price]" value="${price}" placeholder="250" class="w-full h-8 px-2.5 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg font-semibold" required>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">Sale Price (৳)</label>
                    <input type="number" step="0.01" min="0" name="plans[${idx}][sale_price]" value="${salePrice}" placeholder="199" class="w-full h-8 px-2.5 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg font-semibold">
                </div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 pt-1">
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">Available Slots / Stock</label>
                    <input type="number" min="0" name="plans[${idx}][stock_quantity]" value="${stock}" class="w-full h-8 px-2.5 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">Plan SKU (Optional)</label>
                    <input type="text" name="plans[${idx}][sku]" placeholder="PV-1M" class="w-full h-8 px-2.5 text-xs font-mono bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg">
                </div>
                <div class="flex items-center pt-5">
                    <label class="inline-flex items-center gap-1.5 cursor-pointer text-xs text-slate-700 dark:text-slate-300 font-medium">
                        <input type="radio" name="default_plan_radio" ${isDefault ? 'checked' : ''} onchange="setDefaultPlan(${idx})" class="text-brand-600">
                        <span>Default Option</span>
                    </label>
                    <input type="hidden" name="plans[${idx}][is_default]" value="${isDefault ? '1' : '0'}" class="plan-default-hidden">
                </div>
            </div>
        `;
        list.appendChild(row);
    }

    function setDefaultPlan(activeIdx) {
        document.querySelectorAll('.plan-default-hidden').forEach((input, i) => {
            input.value = (i === activeIdx) ? '1' : '0';
        });
    }

    // 5. Image Preview Handling
    const thumbInput = document.getElementById('thumbnail');
    const thumbPreview = document.getElementById('thumbPreview');
    const thumbPlaceholder = document.getElementById('thumbPlaceholder');

    thumbInput?.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                thumbPreview.src = event.target.result;
                thumbPreview.classList.remove('hidden');
                thumbPlaceholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // 6. Quill.js Rich Text Editor Initialization
    const quillContainer = document.getElementById('quillEditorContainer');
    if (quillContainer) {
        const quill = new Quill('#quillEditorContainer', {
            theme: 'snow',
            placeholder: 'Write comprehensive product details, specifications, package contents, warranty details...',
            modules: {
                toolbar: [
                    [{ 'header': [2, 3, 4, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    ['blockquote', 'code-block'],
                    ['link', 'clean']
                ]
            }
        });

        const productForm = document.getElementById('productForm');
        if (productForm) {
            productForm.addEventListener('submit', function() {
                const hiddenDesc = document.getElementById('hiddenDescription');
                if (hiddenDesc) {
                    // If editor is empty or only whitespace/empty paragraph, save blank
                    if (quill.getText().trim().length === 0 && !quill.root.querySelector('img')) {
                        hiddenDesc.value = '';
                    } else {
                        hiddenDesc.value = quill.root.innerHTML;
                    }
                }
            });
        }
    }
</script>
@endpush

