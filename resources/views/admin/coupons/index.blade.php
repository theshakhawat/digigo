@extends('layouts.admin')

@section('title', 'Coupons & Discounts - DigiGo')

@section('admin_content')
<!-- Page Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white flex items-center gap-2.5">
            <i class="fa-solid fa-ticket text-brand-600 dark:text-brand-400"></i>
            <span>Coupons & Promo Codes</span>
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            Create flat or percentage discount vouchers, set validity periods, usage limits, and product or category restrictions.
        </p>
    </div>

    <div>
        <a href="{{ route('admin.coupons.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-800 hover:bg-brand-700 text-white dark:bg-brand-600 dark:hover:bg-brand-700 text-sm font-semibold rounded-lg shadow-sm transition cursor-pointer">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Create New Coupon</span>
        </a>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="mb-6 p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs">
    <form action="{{ route('admin.coupons.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center gap-3">
        <!-- Search Input -->
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </div>
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Search coupon by code or name..."
                   class="w-full h-10 pl-9 pr-4 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 focus:bg-white text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 dark:focus:border-brand-500 transition">
        </div>

        <!-- Discount Type Filter -->
        <div class="w-full sm:w-44">
            <select name="discount_type" class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-700 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                <option value="">All Types</option>
                <option value="percentage" {{ request('discount_type') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                <option value="fixed" {{ request('discount_type') === 'fixed' ? 'selected' : '' }}>Flat Amount (৳)</option>
            </select>
        </div>

        <!-- Status Filter -->
        <div class="w-full sm:w-36">
            <select name="status" class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-700 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="flex items-center gap-2 shrink-0">
            <button type="submit" class="px-4 h-10 bg-slate-800 hover:bg-slate-900 text-white text-xs sm:text-sm font-semibold rounded-lg transition dark:bg-slate-700 dark:hover:bg-slate-600">
                Filter
            </button>
            @if(request()->anyFilled(['search', 'discount_type', 'status']))
                <a href="{{ route('admin.coupons.index') }}" class="px-3 h-10 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs transition dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700" title="Reset filter">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Coupons Table -->
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                    <th class="px-5 py-3.5">Coupon Code</th>
                    <th class="px-5 py-3.5">Discount</th>
                    <th class="px-5 py-3.5">Validity Period</th>
                    <th class="px-5 py-3.5">Usage Limits</th>
                    <th class="px-5 py-3.5">Restrictions</th>
                    <th class="px-5 py-3.5">Status</th>
                    <th class="px-5 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs sm:text-sm">
                @forelse ($coupons as $coupon)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                        <!-- Code & Title -->
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-bold text-xs px-2.5 py-1 rounded bg-indigo-50 border border-indigo-200 text-indigo-700 dark:bg-indigo-950/60 dark:border-indigo-800 dark:text-indigo-300 select-all">
                                    {{ $coupon->code }}
                                </span>
                            </div>
                            @if($coupon->name)
                                <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 mt-1">{{ $coupon->name }}</p>
                            @endif
                        </td>

                        <!-- Discount -->
                        <td class="px-5 py-4">
                            @if($coupon->discount_type === 'percentage')
                                <span class="font-bold text-emerald-600 dark:text-emerald-400 text-sm">
                                    {{ (float) $coupon->discount_value }}% OFF
                                </span>
                                @if($coupon->max_discount_amount)
                                    <p class="text-[11px] text-slate-400 mt-0.5">Max cap: ৳{{ number_format($coupon->max_discount_amount) }}</p>
                                @endif
                            @else
                                <span class="font-bold text-blue-600 dark:text-blue-400 text-sm">
                                    ৳{{ number_format($coupon->discount_value) }} FLAT
                                </span>
                            @endif

                            @if($coupon->min_spend)
                                <p class="text-[11px] text-slate-400 mt-0.5">Min spend: ৳{{ number_format($coupon->min_spend) }}</p>
                            @endif
                        </td>

                        <!-- Validity Period -->
                        <td class="px-5 py-4">
                            @if($coupon->starts_at || $coupon->expires_at)
                                <div class="space-y-0.5 text-xs text-slate-600 dark:text-slate-300">
                                    @if($coupon->starts_at)
                                        <p><span class="text-slate-400">From:</span> {{ $coupon->starts_at->format('d M, Y') }}</p>
                                    @endif
                                    @if($coupon->expires_at)
                                        <p>
                                            <span class="text-slate-400">To:</span> {{ $coupon->expires_at->format('d M, Y') }}
                                            @if($coupon->isExpired())
                                                <span class="ml-1 px-1.5 py-0.2 rounded text-[10px] font-bold bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-300">Expired</span>
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            @else
                                <span class="text-xs text-slate-400 font-medium">Lifetime / No Expiry</span>
                            @endif
                        </td>

                        <!-- Usage Limits -->
                        <td class="px-5 py-4">
                            <div class="text-xs space-y-0.5">
                                <p class="font-semibold text-slate-700 dark:text-slate-300">
                                    Used: <span class="text-indigo-600 font-bold">{{ $coupon->used_count }}</span>
                                    @if($coupon->usage_limit_total)
                                        / {{ $coupon->usage_limit_total }}
                                    @else
                                        <span class="text-slate-400 font-normal">/ Unlimited</span>
                                    @endif
                                </p>
                                <p class="text-[11px] text-slate-400">
                                    Limit/User: {{ $coupon->usage_limit_per_user ?: 'Unlimited' }}
                                </p>
                            </div>
                        </td>

                        <!-- Restrictions (Products/Categories) -->
                        <td class="px-5 py-4">
                            @php
                                $pCount = $coupon->products->count();
                                $cCount = $coupon->categories->count();
                            @endphp
                            @if($pCount === 0 && $cCount === 0)
                                <span class="inline-flex items-center gap-1 text-[11px] px-2 py-0.5 rounded bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300 font-medium">
                                    <i class="fa-solid fa-globe text-[10px]"></i> Store-wide (All)
                                </span>
                            @else
                                <div class="space-y-1">
                                    @if($pCount > 0)
                                        <span class="inline-flex items-center gap-1 text-[11px] px-2 py-0.5 rounded bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 font-medium" title="{{ $coupon->products->pluck('name')->join(', ') }}">
                                            <i class="fa-solid fa-box text-[9px]"></i> {{ $pCount }} {{ Str::plural('Product', $pCount) }}
                                        </span>
                                    @endif
                                    @if($cCount > 0)
                                        <span class="inline-flex items-center gap-1 text-[11px] px-2 py-0.5 rounded bg-purple-50 text-purple-700 dark:bg-purple-950/60 dark:text-purple-300 font-medium" title="{{ $coupon->categories->pluck('name')->join(', ') }}">
                                            <i class="fa-solid fa-layer-group text-[9px]"></i> {{ $cCount }} {{ Str::plural('Category', $cCount) }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="px-5 py-4">
                            @if($coupon->status === 'active')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactive
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="p-2 text-slate-500 hover:text-brand-600 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition" title="Edit Coupon">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <button type="button"
                                        class="js-custom-delete p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition cursor-pointer"
                                        data-action="{{ route('admin.coupons.destroy', $coupon) }}"
                                        data-title="Delete Coupon: {{ $coupon->code }}"
                                        data-message="Are you sure you want to delete coupon '{{ $coupon->code }}'? This action cannot be undone."
                                        title="Delete Coupon">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-solid fa-ticket text-3xl mb-3 text-slate-300 dark:text-slate-600"></i>
                                <p class="text-base font-semibold text-slate-700 dark:text-slate-300">No coupons found</p>
                                <p class="text-xs text-slate-400 mt-1">Get started by creating your first promotional coupon voucher.</p>
                                <a href="{{ route('admin.coupons.create') }}" class="mt-4 px-4 py-2 bg-brand-800 text-white rounded-lg text-xs font-semibold hover:bg-brand-700 transition">
                                    Create Coupon
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($coupons->hasPages())
        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $coupons->links() }}
        </div>
    @endif
</div>
@endsection

