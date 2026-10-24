@extends('layouts.admin')

@section('title', 'Subscriptions Management - DigiGo')

@section('admin_content')
<!-- Page Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white flex items-center gap-2.5">
            <i class="fa-solid fa-arrows-spin text-brand-600 dark:text-brand-400"></i>
            <span>Digital Subscriptions & Licenses</span>
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            Manage customer subscription activations, deliver credentials, provision access keys, and track validity periods.
        </p>
    </div>
</div>

<!-- Alert messages -->
@if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between text-sm dark:bg-emerald-950/50 dark:border-emerald-800 dark:text-emerald-300">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif

<!-- Quick Metrics Badges -->
<div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-6">
    <a href="{{ route('admin.subscriptions.index') }}" class="p-3.5 bg-white dark:bg-slate-900 border {{ empty($status) ? 'border-brand-600 ring-2 ring-brand-600/10' : 'border-slate-200 dark:border-slate-800' }} rounded-xl flex items-center justify-between">
        <span class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider">All</span>
        <span class="px-2 py-0.5 rounded-full text-xs font-extrabold bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200">{{ $counts['all'] }}</span>
    </a>
    <a href="{{ route('admin.subscriptions.index', ['status' => 'pending']) }}" class="p-3.5 bg-white dark:bg-slate-900 border {{ $status === 'pending' ? 'border-amber-600 ring-2 ring-amber-600/10' : 'border-slate-200 dark:border-slate-800' }} rounded-xl flex items-center justify-between">
        <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Pending Activation</span>
        <span class="px-2 py-0.5 rounded-full text-xs font-extrabold bg-amber-50 text-amber-700 dark:bg-amber-950 dark:text-amber-400">{{ $counts['pending'] }}</span>
    </a>
    <a href="{{ route('admin.subscriptions.index', ['status' => 'active']) }}" class="p-3.5 bg-white dark:bg-slate-900 border {{ $status === 'active' ? 'border-emerald-600 ring-2 ring-emerald-600/10' : 'border-slate-200 dark:border-slate-800' }} rounded-xl flex items-center justify-between">
        <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Active</span>
        <span class="px-2 py-0.5 rounded-full text-xs font-extrabold bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400">{{ $counts['active'] }}</span>
    </a>
    <a href="{{ route('admin.subscriptions.index', ['status' => 'expired']) }}" class="p-3.5 bg-white dark:bg-slate-900 border {{ $status === 'expired' ? 'border-slate-600 ring-2 ring-slate-600/10' : 'border-slate-200 dark:border-slate-800' }} rounded-xl flex items-center justify-between">
        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Expired</span>
        <span class="px-2 py-0.5 rounded-full text-xs font-extrabold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400">{{ $counts['expired'] }}</span>
    </a>
    <a href="{{ route('admin.subscriptions.index', ['status' => 'cancelled']) }}" class="p-3.5 bg-white dark:bg-slate-900 border {{ $status === 'cancelled' ? 'border-rose-600 ring-2 ring-rose-600/10' : 'border-slate-200 dark:border-slate-800' }} rounded-xl flex items-center justify-between col-span-2 sm:col-span-1">
        <span class="text-xs font-bold text-rose-600 uppercase tracking-wider">Cancelled</span>
        <span class="px-2 py-0.5 rounded-full text-xs font-extrabold bg-rose-50 text-rose-700 dark:bg-rose-950 dark:text-rose-400">{{ $counts['cancelled'] }}</span>
    </a>
</div>

<!-- Search Form -->
<div class="mb-6 p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs">
    <form action="{{ route('admin.subscriptions.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center gap-3">
        @if($status)
            <input type="hidden" name="status" value="{{ $status }}">
        @endif
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </div>
            <input type="text"
                   name="search"
                   value="{{ $search }}"
                   placeholder="Search by Code, Product, Customer, Email, License Key..."
                   class="w-full h-10 pl-9 pr-4 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 focus:bg-white text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 dark:focus:border-brand-500 transition">
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <button type="submit" class="px-4 h-10 bg-brand-800 hover:bg-brand-700 text-white dark:bg-brand-600 text-xs sm:text-sm font-semibold rounded-lg transition flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-filter text-xs"></i>
                <span>Filter</span>
            </button>
            @if($search || $status)
                <a href="{{ route('admin.subscriptions.index') }}" class="px-3 h-10 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs sm:text-sm font-semibold rounded-lg transition flex items-center" title="Reset Filters">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Subscriptions Table -->
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs sm:text-sm divide-y divide-slate-200 dark:divide-slate-800">
            <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-semibold uppercase text-[11px] tracking-wider">
                <tr>
                    <th class="px-5 py-3.5 whitespace-nowrap min-w-[140px]">Subscription</th>
                    <th class="px-5 py-3.5 whitespace-nowrap min-w-[180px]">Customer</th>
                    <th class="px-5 py-3.5 min-w-[220px]">Product & Plan</th>
                    <th class="px-5 py-3.5 whitespace-nowrap min-w-[130px]">Status</th>
                    <th class="px-5 py-3.5 whitespace-nowrap min-w-[150px]">Activation Status</th>
                    <th class="px-5 py-3.5 text-right whitespace-nowrap min-w-[100px]">Price</th>
                    <th class="px-5 py-3.5 text-center whitespace-nowrap min-w-[110px]">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse($subscriptions as $sub)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                        <td class="px-5 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.subscriptions.show', $sub->id) }}" class="font-mono font-bold text-brand-600 dark:text-brand-400 hover:underline">
                                #{{ $sub->subscription_code }}
                            </a>
                            <div class="text-[11px] text-slate-400 mt-0.5">
                                {{ $sub->created_at->format('M d, Y h:i A') }}
                            </div>
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="font-semibold text-slate-900 dark:text-white">{{ $sub->user->name ?? 'Guest User' }}</div>
                            <div class="text-xs text-slate-500">{{ $sub->user->email ?? 'N/A' }}</div>
                            @if(!empty($sub->target_email) && $sub->target_email !== ($sub->user->email ?? ''))
                                <div class="mt-1 inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                                    <i class="fa-solid fa-paper-plane text-[9px]"></i>
                                    <span>Target: {{ $sub->target_email }}</span>
                                </div>
                            @endif
                            @if(!empty($sub->user->phone))
                                <div class="text-xs text-slate-400 flex items-center gap-1 mt-0.5">
                                    <i class="fa-solid fa-phone text-[10px]"></i>
                                    <span>{{ $sub->user->phone }}</span>
                                </div>
                            @endif
                        </td>

                        <td class="px-5 py-4">
                            <div class="font-bold text-slate-900 dark:text-white text-sm">
                                {{ $sub->product_name }}
                            </div>
                            <div class="text-xs text-slate-500 mt-0.5 flex items-center gap-1.5 flex-wrap">
                                @if($sub->plan_name)
                                    <span class="px-2 py-0.5 rounded bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 font-bold text-[10px]">{{ $sub->plan_name }}</span>
                                @endif
                                @if($sub->validity)
                                    <span class="text-slate-400">• Validity: {{ $sub->validity }}</span>
                                @endif
                            </div>
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap">
                            @php
                                $effectiveStatus = $sub->effective_status;
                            @endphp
                            @if($effectiveStatus === 'active')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-extrabold uppercase bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>ACTIVE</span>
                                </span>
                            @elseif($effectiveStatus === 'pending')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-extrabold uppercase bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300">
                                    <i class="fa-solid fa-clock text-[9px]"></i>
                                    <span>PENDING</span>
                                </span>
                            @elseif($effectiveStatus === 'expired')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-extrabold uppercase bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                                    EXPIRED
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-extrabold uppercase bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300">
                                    {{ strtoupper($effectiveStatus) }}
                                </span>
                            @endif
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap">
                            <!-- Direct Inline Status Switcher -->
                            <form action="{{ route('admin.subscriptions.quick-status', $sub->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="text-xs font-bold rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 px-2.5 py-1.5 focus:ring-2 focus:ring-brand-500 cursor-pointer">
                                    <option value="pending" {{ $sub->status === 'pending' ? 'selected' : '' }}>Set Pending</option>
                                    <option value="active" {{ $sub->status === 'active' ? 'selected' : '' }}>Activate Subscription</option>
                                    <option value="expired" {{ $sub->status === 'expired' ? 'selected' : '' }}>Set Expired</option>
                                    <option value="cancelled" {{ $sub->status === 'cancelled' ? 'selected' : '' }}>Set Cancelled</option>
                                </select>
                            </form>
                        </td>

                        <td class="px-5 py-4 text-right whitespace-nowrap">
                            <span class="font-extrabold text-slate-900 dark:text-white text-sm">
                                ৳{{ number_format($sub->price, 2) }}
                            </span>
                        </td>

                        <td class="px-5 py-4 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.subscriptions.show', $sub->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-brand-800 hover:bg-brand-700 text-white rounded-lg text-xs font-bold transition shadow-xs">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                    <span>Manage</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-slate-400 dark:text-slate-500">
                            <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-3 text-slate-400">
                                <i class="fa-solid fa-arrows-spin text-xl"></i>
                            </div>
                            <p class="font-medium text-slate-700 dark:text-slate-300">No subscriptions found.</p>
                            <p class="text-xs text-slate-400 mt-1">Paid digital orders will automatically generate subscriptions here.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($subscriptions->hasPages())
        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $subscriptions->links() }}
        </div>
    @endif
</div>
@endsection

