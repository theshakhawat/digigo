@extends('layouts.admin')

@section('title', 'Edit Coupon - ' . $coupon->code . ' - DigiGo')

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Select2 Dark Mode & Tailwind Integration */
    .select2-container--default .select2-selection--multiple {
        background-color: rgb(248 250 252);
        border: 1px solid rgb(226 232 240);
        border-radius: 0.5rem;
        min-height: 42px;
        padding: 2px 4px;
    }
    .dark .select2-container--default .select2-selection--multiple {
        background-color: rgb(30 41 59);
        border-color: rgb(51 65 85);
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #2563eb;
        outline: none;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #2563eb;
        border: none;
        color: #ffffff;
        border-radius: 0.375rem;
        padding: 2px 8px;
        font-size: 11px;
        font-weight: 600;
        margin-top: 4px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #ffffff;
        margin-right: 5px;
        font-weight: bold;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #fee2e2;
    }
    .select2-dropdown {
        background-color: #ffffff;
        border: 1px solid rgb(226 232 240);
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        z-index: 50;
    }
    .dark .select2-dropdown {
        background-color: rgb(15 23 42);
        border-color: rgb(51 65 85);
    }
    .select2-container--default .select2-search--dropdown .select2-search__field {
        background-color: rgb(248 250 252);
        border: 1px solid rgb(226 232 240);
        border-radius: 0.375rem;
        padding: 6px 10px;
        font-size: 12px;
        color: #1e293b;
    }
    .dark .select2-container--default .select2-search--dropdown .select2-search__field {
        background-color: rgb(30 41 59);
        border-color: rgb(51 65 85);
        color: #f1f5f9;
    }
    .select2-container--default .select2-results__option {
        font-size: 12px;
        padding: 6px 10px;
        color: #334155;
    }
    .dark .select2-container--default .select2-results__option {
        color: #cbd5e1;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #2563eb;
        color: #ffffff;
    }
    .select2-container--default .select2-search--inline .select2-search__field {
        font-size: 12px;
        color: #334155;
    }
    .dark .select2-container--default .select2-search--inline .select2-search__field {
        color: #f1f5f9;
    }
</style>
@endpush

@section('admin_content')
<!-- Page Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-1">
            <a href="{{ route('admin.coupons.index') }}" class="hover:text-brand-600 transition">Coupons</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <span class="text-slate-800 dark:text-slate-200 font-semibold">{{ $coupon->code }}</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white flex items-center gap-2.5">
            <i class="fa-solid fa-pen-to-square text-brand-600 dark:text-brand-400"></i>
            <span>Edit Coupon: {{ $coupon->code }}</span>
        </h1>
    </div>

    <div class="flex items-center gap-2">
        <button type="button"
                class="js-custom-delete inline-flex items-center gap-1.5 px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-lg text-xs sm:text-sm font-semibold transition cursor-pointer"
                data-action="{{ route('admin.coupons.destroy', $coupon) }}"
                data-title="Delete Coupon: {{ $coupon->code }}"
                data-message="Are you sure you want to delete coupon '{{ $coupon->code }}'? This action cannot be undone.">
            <i class="fa-solid fa-trash-can text-xs"></i>
            <span>Delete</span>
        </button>

        <a href="{{ route('admin.coupons.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-300 text-xs sm:text-sm font-semibold rounded-lg transition">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            <span>Back to Coupons</span>
        </a>
    </div>
</div>

<form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- LEFT COLUMN: Main Configuration (8 cols) -->
        <div class="lg:col-span-8 space-y-6">

            <!-- Basic Details Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs p-5 sm:p-6 space-y-4">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-tag text-brand-600"></i>
                    Coupon Identity
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Coupon Code -->
                    <div>
                        <label for="code" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Coupon Code <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="code"
                               id="code"
                               value="{{ old('code', $coupon->code) }}"
                               placeholder="e.g. SUMMER2026, SAVE50, FLASH10"
                               class="w-full h-10 px-3 uppercase font-mono font-bold text-sm bg-slate-50 border @error('code') border-rose-500 @else border-slate-200 @enderror rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100"
                               required>
                        @error('code')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Coupon Name / Title -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Coupon Title / Campaign Name <span class="text-slate-400 font-normal text-[11px]">(Optional)</span>
                        </label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name', $coupon->name) }}"
                               placeholder="e.g. Summer Mega Sale, Eid Special"
                               class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100">
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Internal Note / Description <span class="text-slate-400 font-normal text-[11px]">(Optional)</span>
                    </label>
                    <textarea name="description"
                              id="description"
                              rows="2"
                              class="w-full p-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100">{{ old('description', $coupon->description) }}</textarea>
                </div>
            </div>

            <!-- Discount Type & Values Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs p-5 sm:p-6 space-y-4">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-percent text-brand-600"></i>
                    Discount & Calculation
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Discount Type -->
                    <div>
                        <label for="discount_type" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Discount Type <span class="text-rose-500">*</span>
                        </label>
                        <select name="discount_type"
                                id="discount_type"
                                onchange="toggleDiscountType(this.value)"
                                class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-800 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                            <option value="fixed" {{ old('discount_type', $coupon->discount_type) === 'fixed' ? 'selected' : '' }}>Flat Amount (৳ Fixed Off)</option>
                            <option value="percentage" {{ old('discount_type', $coupon->discount_type) === 'percentage' ? 'selected' : '' }}>Percentage (% Off)</option>
                        </select>
                    </div>

                    <!-- Discount Value -->
                    <div>
                        <label for="discount_value" id="discountValueLabel" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            {{ old('discount_type', $coupon->discount_type) === 'percentage' ? 'Discount Percentage (%)' : 'Discount Amount (৳)' }} <span class="text-rose-500">*</span>
                        </label>
                        <input type="number"
                               name="discount_value"
                               id="discount_value"
                               value="{{ old('discount_value', $coupon->discount_value) }}"
                               step="0.01"
                               min="0.01"
                               class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border @error('discount_value') border-rose-500 @else border-slate-200 @enderror rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 font-bold"
                               required>
                        @error('discount_value')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <!-- Minimum Spend -->
                    <div>
                        <label for="min_spend" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Minimum Spend (৳) <span class="text-slate-400 font-normal text-[11px]">(Optional minimum cart subtotal)</span>
                        </label>
                        <input type="number"
                               name="min_spend"
                               id="min_spend"
                               value="{{ old('min_spend', $coupon->min_spend) }}"
                               step="0.01"
                               min="0"
                               placeholder="e.g. 500"
                               class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100">
                    </div>

                    <!-- Max Discount Cap (for Percentage) -->
                    <div id="maxDiscountWrapper" class="{{ old('discount_type', $coupon->discount_type) === 'percentage' ? '' : 'hidden' }}">
                        <label for="max_discount_amount" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Maximum Discount Cap (৳) <span class="text-slate-400 font-normal text-[11px]">(Optional cap for %)</span>
                        </label>
                        <input type="number"
                               name="max_discount_amount"
                               id="max_discount_amount"
                               value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}"
                               step="0.01"
                               min="0"
                               placeholder="e.g. 300"
                               class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100">
                    </div>
                </div>
            </div>

            <!-- Product & Category Applicability Restrictions -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs p-5 sm:p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200 flex items-center gap-2">
                        <i class="fa-solid fa-bullseye text-brand-600"></i>
                        Applicable Products & Categories
                    </h2>
                    <span class="text-[11px] text-slate-400">Leave blank for all store products</span>
                </div>

                @php
                    $selectedCategories = old('category_ids', $coupon->categories->pluck('id')->all());
                    $selectedProducts = old('product_ids', $coupon->products->pluck('id')->all());
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Specific Categories with Checkboxes -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                Applicable Categories
                            </label>
                            <div class="flex items-center gap-2 text-[11px]">
                                <button type="button" onclick="selectAllCategories(true)" class="text-brand-600 hover:underline font-semibold cursor-pointer">All</button>
                                <span class="text-slate-300 dark:text-slate-600">|</span>
                                <button type="button" onclick="selectAllCategories(false)" class="text-slate-500 hover:underline font-semibold cursor-pointer">Clear</button>
                            </div>
                        </div>

                        <!-- Checkbox Container with Quick Search & Scroll -->
                        <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-3 bg-slate-50/70 dark:bg-slate-800/50 space-y-2">
                            <div class="relative">
                                <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-2.5 text-slate-400 text-[11px]"></i>
                                <input type="text"
                                       id="categorySearchFilter"
                                       placeholder="Filter categories..."
                                       onkeyup="filterCategoriesList(this.value)"
                                       class="w-full h-8 pl-7 pr-3 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-600">
                            </div>

                            <div id="categoryCheckboxesList" class="max-h-56 overflow-y-auto space-y-1.5 pr-1 divide-y divide-slate-100 dark:divide-slate-800/40">
                                @forelse($categories as $category)
                                    <label class="category-item flex items-center justify-between gap-2 p-1.5 rounded-lg hover:bg-white dark:hover:bg-slate-900/60 cursor-pointer transition">
                                        <div class="flex items-center gap-2.5">
                                            <input type="checkbox"
                                                   name="category_ids[]"
                                                   value="{{ $category->id }}"
                                                   {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}
                                                   class="category-checkbox w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-slate-300">
                                            <span class="category-name text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $category->name }}</span>
                                        </div>
                                        <span class="text-[10px] text-slate-400 font-mono">ID: #{{ $category->id }}</span>
                                    </label>
                                @empty
                                    <p class="text-xs text-slate-400 p-2">No categories available.</p>
                                @endforelse
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1.5">If selected, coupon applies to items from these categories.</p>
                    </div>

                    <!-- Specific Products with Select2 Search Box -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="product_ids" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                Applicable Products (Select2 Search)
                            </label>
                            <span class="text-[11px] text-slate-400">Search by title or type</span>
                        </div>

                        <div>
                            <select name="product_ids[]"
                                    id="product_ids"
                                    multiple="multiple"
                                    class="w-full select2-products"
                                    data-placeholder="Search and select products...">
                                @foreach($products as $prod)
                                    <option value="{{ $prod->id }}" {{ in_array($prod->id, $selectedProducts) ? 'selected' : '' }}>
                                        [{{ ucfirst($prod->product_type) }}] {{ $prod->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2">Type product name to search and select multiple products.</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN: Validity, Usage Limits & Status (4 cols) -->
        <div class="lg:col-span-4 space-y-6">

            <!-- Publishing & Status -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs p-5 sm:p-6 space-y-4">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-power-off text-brand-600"></i>
                    Coupon Status
                </h2>

                <div>
                    <label for="status" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Status <span class="text-rose-500">*</span>
                    </label>
                    <select name="status" id="status" class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-800 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                        <option value="active" {{ old('status', $coupon->status) === 'active' ? 'selected' : '' }}>Active (Usable)</option>
                        <option value="inactive" {{ old('status', $coupon->status) === 'inactive' ? 'selected' : '' }}>Inactive (Disabled)</option>
                    </select>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-brand-800 hover:bg-brand-700 text-white font-bold rounded-lg shadow-sm transition flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-floppy-disk text-sm"></i>
                        <span>Update Coupon</span>
                    </button>
                </div>
            </div>

            <!-- Validity Date Range -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs p-5 sm:p-6 space-y-4">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-days text-brand-600"></i>
                    Validity Period
                </h2>

                <div>
                    <label for="starts_at" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Starts At <span class="text-slate-400 font-normal text-[11px]">(Optional)</span>
                    </label>
                    <input type="datetime-local"
                           name="starts_at"
                           id="starts_at"
                           value="{{ old('starts_at', $coupon->starts_at?->format('Y-m-d\TH:i')) }}"
                           class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100">
                </div>

                <div>
                    <label for="expires_at" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Expires At <span class="text-slate-400 font-normal text-[11px]">(Optional)</span>
                    </label>
                    <input type="datetime-local"
                           name="expires_at"
                           id="expires_at"
                           value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d\TH:i')) }}"
                           class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border @error('expires_at') border-rose-500 @else border-slate-200 @enderror rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100">
                    @error('expires_at')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Usage Limits -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs p-5 sm:p-6 space-y-4">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-users-gear text-brand-600"></i>
                    Usage Limits & Stats
                </h2>

                <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-between">
                    <span class="text-xs text-slate-600 dark:text-slate-400">Times Used So Far:</span>
                    <span class="font-bold text-indigo-600 dark:text-indigo-400 text-sm">{{ $coupon->used_count }}</span>
                </div>

                <div>
                    <label for="usage_limit_total" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Max Total Users / Redemptions
                    </label>
                    <input type="number"
                           name="usage_limit_total"
                           id="usage_limit_total"
                           value="{{ old('usage_limit_total', $coupon->usage_limit_total) }}"
                           min="1"
                           placeholder="e.g. 100 (Blank = unlimited)"
                           class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100">
                </div>

                <div>
                    <label for="usage_limit_per_user" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Max Limit Per User
                    </label>
                    <input type="number"
                           name="usage_limit_per_user"
                           id="usage_limit_per_user"
                           value="{{ old('usage_limit_per_user', $coupon->usage_limit_per_user) }}"
                           min="1"
                           placeholder="e.g. 1"
                           class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100">
                </div>
            </div>

        </div>
    </div>
</form>

@push('scripts')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#product_ids').select2({
            placeholder: "Search and select products...",
            allowClear: true,
            width: '100%'
        });
    });

    function toggleDiscountType(type) {
        const label = document.getElementById('discountValueLabel');
        const maxWrapper = document.getElementById('maxDiscountWrapper');

        if (type === 'percentage') {
            label.innerHTML = 'Discount Percentage (%) <span class="text-rose-500">*</span>';
            maxWrapper.classList.remove('hidden');
        } else {
            label.innerHTML = 'Discount Amount (৳) <span class="text-rose-500">*</span>';
            maxWrapper.classList.add('hidden');
        }
    }

    function selectAllCategories(check) {
        document.querySelectorAll('.category-checkbox').forEach(cb => {
            cb.checked = check;
        });
    }

    function filterCategoriesList(query) {
        const term = query.toLowerCase().trim();
        document.querySelectorAll('.category-item').forEach(item => {
            const text = item.querySelector('.category-name')?.textContent.toLowerCase() || '';
            if (text.includes(term)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }
</script>
@endpush
@endsection

