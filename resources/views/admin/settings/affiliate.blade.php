@extends('layouts.admin')

@section('title', 'Affiliate Commissions Management - DigiGo')

@section('admin_content')
<!-- Page Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-1">
            <span>Settings</span>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <span class="text-slate-800 dark:text-slate-200 font-semibold">Affiliate Commission</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white flex items-center gap-2.5">
            <i class="fa-solid fa-hand-holding-dollar text-brand-600 dark:text-brand-400"></i>
            <span>Affiliate Commission Settings</span>
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            Configure global and category-specific affiliate commission rates for physical products and digital services.
        </p>
    </div>
</div>

<div class="space-y-8">

    <!-- 1. Global Default Affiliate Commissions Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-sliders text-brand-600"></i>
                        1. Global Default Commission Rates
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        These default rates apply automatically unless a category specifies its own custom commission rule.
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.settings.affiliate.global') }}" method="POST" class="p-5 sm:p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Affiliate Program Active Switch -->
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-2">
                    <label for="affiliate_enabled" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                        Affiliate System Status
                    </label>
                    <select name="affiliate_enabled"
                            id="affiliate_enabled"
                            class="w-full h-11 px-3 text-sm font-semibold bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:text-slate-100">
                        <option value="1" {{ old('affiliate_enabled', $settings['affiliate_enabled']) === '1' ? 'selected' : '' }}>🟢 Enabled (Active)</option>
                        <option value="0" {{ old('affiliate_enabled', $settings['affiliate_enabled']) === '0' ? 'selected' : '' }}>🔴 Disabled (Inactive)</option>
                    </select>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Controls whether customers can earn commissions on referrals.</p>
                </div>

                <!-- Default Physical Product Commission -->
                <div class="p-4 rounded-xl bg-emerald-50/50 dark:bg-slate-800/60 border border-emerald-100 dark:border-slate-700 space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold uppercase tracking-wider text-emerald-900 dark:text-emerald-300">
                            Physical Products Commission
                        </label>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300">📦 Physical</span>
                    </div>
                    <div class="flex gap-2">
                        <div class="relative flex-1">
                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="affiliate_default_physical"
                                   id="affiliate_default_physical"
                                   value="{{ old('affiliate_default_physical', $settings['affiliate_default_physical']) }}"
                                   class="w-full h-11 px-3 text-sm font-bold bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:text-slate-100"
                                   required>
                        </div>
                        <select name="affiliate_default_physical_type" class="h-11 px-3 text-xs font-bold bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-200">
                            <option value="percentage" {{ old('affiliate_default_physical_type', $settings['affiliate_default_physical_type']) === 'percentage' ? 'selected' : '' }}>% Percent</option>
                            <option value="fixed" {{ old('affiliate_default_physical_type', $settings['affiliate_default_physical_type']) === 'fixed' ? 'selected' : '' }}>৳ Fixed</option>
                        </select>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Default rate given for gadgets, accessories, and physical orders.</p>
                </div>

                <!-- Default Digital Product Commission -->
                <div class="p-4 rounded-xl bg-purple-50/50 dark:bg-slate-800/60 border border-purple-100 dark:border-slate-700 space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold uppercase tracking-wider text-purple-900 dark:text-purple-300">
                            Digital / Subs Commission
                        </label>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-800 dark:bg-purple-900/60 dark:text-purple-300">⚡ Digital</span>
                    </div>
                    <div class="flex gap-2">
                        <div class="relative flex-1">
                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="affiliate_default_digital"
                                   id="affiliate_default_digital"
                                   value="{{ old('affiliate_default_digital', $settings['affiliate_default_digital']) }}"
                                   class="w-full h-11 px-3 text-sm font-bold bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:text-slate-100"
                                   required>
                        </div>
                        <select name="affiliate_default_digital_type" class="h-11 px-3 text-xs font-bold bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-200">
                            <option value="percentage" {{ old('affiliate_default_digital_type', $settings['affiliate_default_digital_type']) === 'percentage' ? 'selected' : '' }}>% Percent</option>
                            <option value="fixed" {{ old('affiliate_default_digital_type', $settings['affiliate_default_digital_type']) === 'fixed' ? 'selected' : '' }}>৳ Fixed</option>
                        </select>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Default rate given for AI tools, keys, and subscription sales.</p>
                </div>

                <!-- Min Withdrawal Amount -->
                <div class="p-4 rounded-xl bg-blue-50/50 dark:bg-slate-800/60 border border-blue-100 dark:border-slate-700 space-y-2">
                    <label for="affiliate_min_withdrawal" class="block text-xs font-bold uppercase tracking-wider text-blue-900 dark:text-blue-300">
                        Minimum Withdrawal (৳)
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-sm font-bold text-slate-400">৳</span>
                        <input type="number"
                               step="1"
                               min="0"
                               name="affiliate_min_withdrawal"
                               id="affiliate_min_withdrawal"
                               value="{{ old('affiliate_min_withdrawal', $settings['affiliate_min_withdrawal']) }}"
                               class="w-full h-11 pl-8 pr-3 text-sm font-bold bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:text-slate-100">
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Threshold required before user can request payout.</p>
                </div>

                <!-- Terms & Note -->
                <div class="md:col-span-2 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-2">
                    <label for="affiliate_terms_note" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                        Affiliate Terms / Policy Note
                    </label>
                    <input type="text"
                           name="affiliate_terms_note"
                           id="affiliate_terms_note"
                           value="{{ old('affiliate_terms_note', $settings['affiliate_terms_note']) }}"
                           class="w-full h-11 px-3 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:text-slate-100">
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Short guidelines displayed on user affiliate page.</p>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="px-5 py-2.5 bg-brand-800 hover:bg-brand-700 text-white font-semibold text-sm rounded-lg shadow-sm transition dark:bg-brand-600 dark:hover:bg-brand-700 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk mr-1.5"></i> Save Global Settings
                </button>
            </div>
        </form>
    </div>

    <!-- 2. Category-Specific Commission Settings Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-slate-800">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-tags text-brand-600"></i>
                2. Category-Wise Custom Commission Rates
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Customize affiliate commission percentage or fixed amounts per category for <strong>Physical Products</strong> and <strong>Digital Products</strong>. Leave empty to inherit global default.
            </p>
        </div>

        <form action="{{ route('admin.settings.affiliate.categories') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm min-w-[700px]">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 dark:bg-slate-950 dark:border-slate-800 dark:text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-6 py-3.5">Category Name</th>
                            <th class="px-6 py-3.5">
                                <div class="flex items-center gap-1.5 text-emerald-700 dark:text-emerald-400">
                                    <i class="fa-solid fa-box text-xs"></i>
                                    <span>Physical Products Commission</span>
                                </div>
                            </th>
                            <th class="px-6 py-3.5">
                                <div class="flex items-center gap-1.5 text-purple-700 dark:text-purple-400">
                                    <i class="fa-solid fa-bolt text-xs"></i>
                                    <span>Digital Products Commission</span>
                                </div>
                            </th>
                            <th class="px-6 py-3.5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($categories as $index => $category)
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                                <td class="px-6 py-4">
                                    <input type="hidden" name="categories[{{ $index }}][id]" value="{{ $category->id }}">
                                    <span class="font-bold text-slate-900 dark:text-white block">{{ $category->name }}</span>
                                    <span class="text-[11px] font-mono text-slate-400">/category/{{ $category->slug }}</span>
                                </td>

                                <!-- Physical Products Commission Field -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2 max-w-xs">
                                        <div class="relative flex-1">
                                            <input type="number"
                                                   step="0.01"
                                                   min="0"
                                                   name="categories[{{ $index }}][physical]"
                                                   value="{{ old("categories.{$index}.physical", $category->affiliate_commission_physical) }}"
                                                   placeholder="Default ({{ $settings['affiliate_default_physical'] }}{{ $settings['affiliate_default_physical_type'] === 'percentage' ? '%' : '৳' }})"
                                                   class="w-full h-9 px-3 text-xs font-semibold bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:text-slate-100">
                                        </div>
                                        <select name="categories[{{ $index }}][physical_type]" class="h-9 px-2 text-[11px] font-bold bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-300">
                                            <option value="percentage" {{ old("categories.{$index}.physical_type", $category->affiliate_commission_type_physical) === 'percentage' ? 'selected' : '' }}>%</option>
                                            <option value="fixed" {{ old("categories.{$index}.physical_type", $category->affiliate_commission_type_physical) === 'fixed' ? 'selected' : '' }}>৳ Fixed</option>
                                        </select>
                                    </div>
                                </td>

                                <!-- Digital Products Commission Field -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2 max-w-xs">
                                        <div class="relative flex-1">
                                            <input type="number"
                                                   step="0.01"
                                                   min="0"
                                                   name="categories[{{ $index }}][digital]"
                                                   value="{{ old("categories.{$index}.digital", $category->affiliate_commission_digital) }}"
                                                   placeholder="Default ({{ $settings['affiliate_default_digital'] }}{{ $settings['affiliate_default_digital_type'] === 'percentage' ? '%' : '৳' }})"
                                                   class="w-full h-9 px-3 text-xs font-semibold bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:text-slate-100">
                                        </div>
                                        <select name="categories[{{ $index }}][digital_type]" class="h-9 px-2 text-[11px] font-bold bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-300">
                                            <option value="percentage" {{ old("categories.{$index}.digital_type", $category->affiliate_commission_type_digital) === 'percentage' ? 'selected' : '' }}>%</option>
                                            <option value="fixed" {{ old("categories.{$index}.digital_type", $category->affiliate_commission_type_digital) === 'fixed' ? 'selected' : '' }}>৳ Fixed</option>
                                        </select>
                                    </div>
                                </td>

                                <!-- Override Status Badge -->
                                <td class="px-6 py-4 text-center">
                                    @if($category->affiliate_commission_physical !== null || $category->affiliate_commission_digital !== null)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300">
                                            <i class="fa-solid fa-check text-[9px]"></i> Custom Rates
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                                            Inheriting Global
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-400">
                                    No categories available.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-5 sm:p-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <span class="text-xs text-slate-400">Total {{ $categories->count() }} categories loaded.</span>
                <button type="submit" class="px-5 py-2.5 bg-brand-800 hover:bg-brand-700 text-white font-semibold text-sm rounded-lg shadow-sm transition dark:bg-brand-600 dark:hover:bg-brand-700 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk mr-1.5"></i> Save Category Commissions
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

