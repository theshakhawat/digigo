@extends('layouts.admin')

@section('title', 'Admin Dashboard - DigiGo')

@section('admin_content')
<!-- Page Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white">
            Dashboard Overview
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            Welcome back, <strong class="text-slate-800 dark:text-slate-200">{{ $user->name }}</strong>! Here is an overview of DigiGo digital products and customers.
        </p>
    </div>

    <!-- Quick Action / Date Badge -->
    <div class="flex items-center gap-2">
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-white border border-slate-200 text-slate-700 dark:bg-slate-900 dark:border-slate-800 dark:text-slate-300 rounded-lg shadow-xs">
            <i class="fa-regular fa-calendar text-slate-400"></i>
            {{ date('l, d F Y') }}
        </span>
        <a href="{{ url('/') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-brand-800 hover:bg-brand-700 text-white dark:bg-brand-600 dark:hover:bg-brand-700 rounded-lg transition shadow-xs">
            <i class="fa-solid fa-store"></i>
            <span>Live Store</span>
        </a>
    </div>
</div>

<!-- =========================================================================
     1. Metric Stats Cards (Normal Rounded Corners)
     ========================================================================= -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

    <!-- Card 1: Total Customers -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-xl shadow-xs">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Customers</span>
            <div class="w-10 h-10 bg-blue-50 text-brand-600 dark:bg-blue-950 dark:text-brand-400 flex items-center justify-center rounded-lg text-sm">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">{{ $totalUsers }}</span>
            <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 flex items-center gap-0.5">
                <i class="fa-solid fa-arrow-up text-[10px]"></i> Active
            </span>
        </div>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Registered buyer accounts</p>
    </div>

    <!-- Card 2: Administrators -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-xl shadow-xs">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Administrators</span>
            <div class="w-10 h-10 bg-purple-50 text-purple-600 dark:bg-purple-950 dark:text-purple-400 flex items-center justify-center rounded-lg text-sm">
                <i class="fa-solid fa-user-shield"></i>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">{{ $totalAdmins }}</span>
            <span class="text-xs font-semibold text-purple-600 dark:text-purple-400">Staff Members</span>
        </div>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Management and support staff</p>
    </div>

    <!-- Card 3: Digital Products & Services -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-xl shadow-xs">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Catalog Items</span>
            <div class="w-10 h-10 bg-emerald-50 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400 flex items-center justify-center rounded-lg text-sm">
                <i class="fa-solid fa-cubes"></i>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">48+</span>
            <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">Live Items</span>
        </div>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">AI tools, gift cards & keys</p>
    </div>

    <!-- Card 4: Platform Status -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-xl shadow-xs">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Platform Status</span>
            <div class="w-10 h-10 bg-emerald-50 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400 flex items-center justify-center rounded-lg text-sm">
                <i class="fa-solid fa-server"></i>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">Healthy</span>
            <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> 99.9%
            </span>
        </div>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Payment gateways online</p>
    </div>

</div>

<!-- =========================================================================
     2. Quick Action Shortcut Grid (Normal Rounded)
     ========================================================================= -->
<div class="mb-8 p-5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs">
    <div class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3">
        Quick Actions
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <a href="#add-product" class="p-3 border border-slate-200 dark:border-slate-800 hover:border-brand-500 hover:bg-brand-50/50 dark:hover:bg-slate-800 transition flex items-center gap-3 rounded-lg">
            <div class="w-9 h-9 bg-blue-100 text-brand-600 dark:bg-slate-800 dark:text-brand-400 flex items-center justify-center rounded-lg text-xs">
                <i class="fa-solid fa-plus"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-800 dark:text-slate-200">Add Product</p>
                <p class="text-[11px] text-slate-400">Create new item</p>
            </div>
        </a>

        <a href="#orders" class="p-3 border border-slate-200 dark:border-slate-800 hover:border-brand-500 hover:bg-brand-50/50 dark:hover:bg-slate-800 transition flex items-center gap-3 rounded-lg">
            <div class="w-9 h-9 bg-amber-100 text-amber-700 dark:bg-slate-800 dark:text-amber-400 flex items-center justify-center rounded-lg text-xs">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-800 dark:text-slate-200">Review Orders</p>
                <p class="text-[11px] text-slate-400">Check purchases</p>
            </div>
        </a>

        <a href="#license-pool" class="p-3 border border-slate-200 dark:border-slate-800 hover:border-brand-500 hover:bg-brand-50/50 dark:hover:bg-slate-800 transition flex items-center gap-3 rounded-lg">
            <div class="w-9 h-9 bg-purple-100 text-purple-700 dark:bg-slate-800 dark:text-purple-400 flex items-center justify-center rounded-lg text-xs">
                <i class="fa-solid fa-key"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-800 dark:text-slate-200">License Keys</p>
                <p class="text-[11px] text-slate-400">Upload key pool</p>
            </div>
        </a>

        <a href="#coupons" class="p-3 border border-slate-200 dark:border-slate-800 hover:border-brand-500 hover:bg-brand-50/50 dark:hover:bg-slate-800 transition flex items-center gap-3 rounded-lg">
            <div class="w-9 h-9 bg-rose-100 text-rose-700 dark:bg-slate-800 dark:text-rose-400 flex items-center justify-center rounded-lg text-xs">
                <i class="fa-solid fa-ticket"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-800 dark:text-slate-200">Promo Code</p>
                <p class="text-[11px] text-slate-400">Create discount</p>
            </div>
        </a>
    </div>
</div>

<!-- =========================================================================
     3. Recent Registered Users Table (Normal Rounded)
     ========================================================================= -->
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">

    <!-- Table Header with Search & Filter -->
    <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-users-viewfinder text-brand-600 dark:text-brand-400"></i>
                <span>Recently Registered Users</span>
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Latest customer and staff signups on the DigiGo portal
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="#export" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 dark:border-slate-700 rounded-lg transition">
                <i class="fa-solid fa-download text-[11px]"></i>
                <span>Export CSV</span>
            </a>
            <a href="#all-users" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-brand-600 hover:bg-brand-700 text-white dark:bg-brand-500 dark:hover:bg-brand-600 rounded-lg transition">
                <span>View All Users →</span>
            </a>
        </div>
    </div>

    <!-- Data Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs sm:text-sm">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 dark:bg-slate-950 dark:border-slate-800 dark:text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                <tr>
                    <th class="px-5 py-3.5">User</th>
                    <th class="px-5 py-3.5">Email</th>
                    <th class="px-5 py-3.5">Phone</th>
                    <th class="px-5 py-3.5">Role</th>
                    <th class="px-5 py-3.5">Status</th>
                    <th class="px-5 py-3.5">Registered</th>
                    <th class="px-5 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse ($recentUsers as $u)
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                        <!-- User Name & Avatar (Circular Avatar) -->
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($u->name) }}&background=e2e8f0&color=334155&bold=true&size=64"
                                     alt="{{ $u->name }}"
                                     class="w-8 h-8 rounded-full object-cover shrink-0 border border-slate-200 dark:border-slate-700">
                                <div>
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $u->name }}</div>
                                    <div class="text-[10px] text-slate-400">ID: #{{ $u->id }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Email -->
                        <td class="px-5 py-3.5 text-slate-600 dark:text-slate-300">
                            {{ $u->email }}
                        </td>

                        <!-- Phone -->
                        <td class="px-5 py-3.5 text-slate-600 dark:text-slate-300">
                            {{ $u->phone ?? '—' }}
                        </td>

                        <!-- Role Badge (Normal Rounded Pill) -->
                        <td class="px-5 py-3.5">
                            @if ($u->role === 'admin')
                                <span class="inline-flex items-center px-2.5 py-0.5 text-[10px] font-bold uppercase bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300 rounded-full border border-purple-200 dark:border-purple-800">
                                    Admin
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 text-[10px] font-bold uppercase bg-blue-100 text-brand-800 dark:bg-blue-950 dark:text-brand-300 rounded-full border border-blue-200 dark:border-blue-900">
                                    Customer
                                </span>
                            @endif
                        </td>

                        <!-- Status Badge (Normal Rounded Pill) -->
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 rounded-full border border-emerald-200 dark:border-emerald-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                {{ $u->status ?? 'Active' }}
                            </span>
                        </td>

                        <!-- Registered Date -->
                        <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400 text-xs whitespace-nowrap">
                            {{ $u->created_at ? $u->created_at->diffForHumans() : '—' }}
                        </td>

                        <!-- Actions (Normal Rounded Buttons) -->
                        <td class="px-5 py-3.5 text-right">
                            <div class="inline-flex items-center gap-1">
                                <a href="#user-{{ $u->id }}" class="p-1.5 text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800 transition" title="View Profile">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>
                                <a href="#edit-{{ $u->id }}" class="p-1.5 text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800 transition" title="Edit">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-slate-400 dark:text-slate-500">
                            No registered users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Table Footer Pagination note -->
    <div class="p-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
        <span>Showing latest {{ $recentUsers->count() }} of {{ $totalUsers + $totalAdmins }} total accounts</span>
        <span>DigiGo Admin Security active</span>
    </div>

</div>
@endsection
