@extends('layouts.admin')

@section('title', 'Shipping & Delivery Management - DigiGo')

@section('admin_content')
<!-- Page Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-1">
            <span>Settings</span>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <span class="text-slate-800 dark:text-slate-200 font-semibold">Shipping & Delivery</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white flex items-center gap-2.5">
            <i class="fa-solid fa-truck-fast text-brand-600 dark:text-brand-400"></i>
            <span>Shipping & Delivery Charges</span>
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            Configure automatic location-based delivery charges (Inside Dhaka vs. Outside Dhaka) and custom category rates.
        </p>
    </div>
</div>

<div class="space-y-8">

    <!-- 1. Global Default Delivery Charges Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-slate-800">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-earth-asia text-brand-600"></i>
                1. Default Region Rates (Inside vs. Outside Dhaka)
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                When a customer enters their address at checkout, the system automatically detects if their district is within Dhaka (৳60) or outside Dhaka (৳100).
            </p>
        </div>

        <form action="{{ route('admin.settings.shipping.global') }}" method="POST" class="p-5 sm:p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Inside Dhaka -->
                <div class="p-4 rounded-xl bg-blue-50/50 dark:bg-slate-800/60 border border-blue-100 dark:border-slate-700 space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="shipping_default_inside_dhaka" class="block text-xs font-bold uppercase tracking-wider text-blue-900 dark:text-blue-300">
                            Inside Dhaka Charge (৳)
                        </label>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/60 dark:text-blue-300">Default: ৳60</span>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-sm font-bold text-slate-400">৳</span>
                        <input type="number"
                               step="0.01"
                               min="0"
                               name="shipping_default_inside_dhaka"
                               id="shipping_default_inside_dhaka"
                               value="{{ old('shipping_default_inside_dhaka', $settings['shipping_default_inside_dhaka']) }}"
                               class="w-full h-11 pl-8 pr-3 text-sm font-bold bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:text-slate-100"
                               required>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Applies when district is Dhaka / inside Dhaka zone.</p>
                </div>

                <!-- Outside Dhaka -->
                <div class="p-4 rounded-xl bg-amber-50/50 dark:bg-slate-800/60 border border-amber-100 dark:border-slate-700 space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="shipping_default_outside_dhaka" class="block text-xs font-bold uppercase tracking-wider text-amber-900 dark:text-amber-300">
                            Outside Dhaka Charge (৳)
                        </label>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-300">Default: ৳100</span>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-sm font-bold text-slate-400">৳</span>
                        <input type="number"
                               step="0.01"
                               min="0"
                               name="shipping_default_outside_dhaka"
                               id="shipping_default_outside_dhaka"
                               value="{{ old('shipping_default_outside_dhaka', $settings['shipping_default_outside_dhaka']) }}"
                               class="w-full h-11 pl-8 pr-3 text-sm font-bold bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:text-slate-100"
                               required>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Applies to all other 63 districts nationwide.</p>
                </div>

                <!-- Free Shipping Threshold -->
                <div class="p-4 rounded-xl bg-emerald-50/50 dark:bg-slate-800/60 border border-emerald-100 dark:border-slate-700 space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="shipping_free_threshold" class="block text-xs font-bold uppercase tracking-wider text-emerald-900 dark:text-emerald-300">
                            Free Shipping Threshold (৳)
                        </label>
                        <span class="text-[10px] text-slate-400">Optional</span>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-sm font-bold text-slate-400">৳</span>
                        <input type="number"
                               step="0.01"
                               min="0"
                               name="shipping_free_threshold"
                               id="shipping_free_threshold"
                               value="{{ old('shipping_free_threshold', $settings['shipping_free_threshold']) }}"
                               placeholder="e.g. 2000 (Blank = disabled)"
                               class="w-full h-11 pl-8 pr-3 text-sm font-bold bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:text-slate-100">
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Orders above this amount qualify for zero delivery charge.</p>
                </div>
            </div>

            <!-- Delivery Timelines -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label for="shipping_estimated_time_inside" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Inside Dhaka Delivery Time Estimate
                    </label>
                    <input type="text"
                           name="shipping_estimated_time_inside"
                           id="shipping_estimated_time_inside"
                           value="{{ old('shipping_estimated_time_inside', $settings['shipping_estimated_time_inside']) }}"
                           placeholder="e.g. 24 - 48 Hours"
                           class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg">
                </div>

                <div>
                    <label for="shipping_estimated_time_outside" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Outside Dhaka Delivery Time Estimate
                    </label>
                    <input type="text"
                           name="shipping_estimated_time_outside"
                           id="shipping_estimated_time_outside"
                           value="{{ old('shipping_estimated_time_outside', $settings['shipping_estimated_time_outside']) }}"
                           placeholder="e.g. 3 - 5 Days"
                           class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg">
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" class="px-5 py-2.5 bg-brand-800 hover:bg-brand-700 text-white font-bold text-xs sm:text-sm rounded-lg shadow-sm transition flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Save Region Rates</span>
                </button>
            </div>
        </form>
    </div>

    <!-- 2. Category-Wise Delivery Rates Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-layer-group text-brand-600"></i>
                    2. Category-Wise Custom Delivery Charges
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Set special delivery fees for bulky, fragile, or heavy categories. Leave fields blank to inherit default regional rates (৳60 / ৳100).
                </p>
            </div>
        </div>

        <form action="{{ route('admin.settings.shipping.categories') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                            <th class="px-5 py-3">Category</th>
                            <th class="px-5 py-3">Inside Dhaka Charge (৳)</th>
                            <th class="px-5 py-3">Outside Dhaka Charge (৳)</th>
                            <th class="px-5 py-3 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs sm:text-sm">
                        @forelse($categories as $index => $category)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                                <input type="hidden" name="categories[{{ $index }}][id]" value="{{ $category->id }}">
                                
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-lg bg-indigo-50 dark:bg-indigo-950 flex items-center justify-center text-indigo-600 dark:text-indigo-400 text-xs font-bold">
                                            {{ substr($category->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $category->name }}</span>
                                            <span class="block text-[11px] text-slate-400 font-mono">{{ $category->slug }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-3.5">
                                    <div class="relative w-44">
                                        <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-xs font-semibold text-slate-400">৳</span>
                                        <input type="number"
                                               step="0.01"
                                               min="0"
                                               name="categories[{{ $index }}][inside]"
                                               value="{{ old("categories.{$index}.inside", $category->delivery_charge_inside_dhaka !== null ? (float)$category->delivery_charge_inside_dhaka : '') }}"
                                               placeholder="Default ({{ (int)$settings['shipping_default_inside_dhaka'] }}৳)"
                                               class="w-full h-8 pl-7 pr-2.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:text-slate-100 font-semibold">
                                    </div>
                                </td>

                                <td class="px-5 py-3.5">
                                    <div class="relative w-44">
                                        <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-xs font-semibold text-slate-400">৳</span>
                                        <input type="number"
                                               step="0.01"
                                               min="0"
                                               name="categories[{{ $index }}][outside]"
                                               value="{{ old("categories.{$index}.outside", $category->delivery_charge_outside_dhaka !== null ? (float)$category->delivery_charge_outside_dhaka : '') }}"
                                               placeholder="Default ({{ (int)$settings['shipping_default_outside_dhaka'] }}৳)"
                                               class="w-full h-8 pl-7 pr-2.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:text-slate-100 font-semibold">
                                    </div>
                                </td>

                                <td class="px-5 py-3.5 text-right">
                                    @if($category->delivery_charge_inside_dhaka !== null || $category->delivery_charge_outside_dhaka !== null)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300">
                                            Custom Rates Set
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                                            Default ({{ (int)$settings['shipping_default_inside_dhaka'] }}/{{ (int)$settings['shipping_default_outside_dhaka'] }} ৳)
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-slate-400">
                                    No categories found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-5 sm:p-6 border-t border-slate-100 dark:border-slate-800 flex justify-end">
                <button type="submit" class="px-5 py-2.5 bg-brand-800 hover:bg-brand-700 text-white font-bold text-xs sm:text-sm rounded-lg shadow-sm transition flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Save Category Delivery Charges</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

