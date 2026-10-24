@extends('layouts.admin')

@section('title', 'Admin Dashboard - ' . setting('site_name', 'DigiGo'))

@section('admin_content')
<div class="space-y-8">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white">
                Dashboard Overview
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Welcome back, <strong class="text-slate-800 dark:text-slate-200">{{ $user->name }}</strong>! Real-time operations summary for DigiGo storefront.
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
         1. 10 Important Ecommerce Stats (2 Rows x 5 Cards)
         ========================================================================= -->
    <div class="space-y-4">
        <!-- Row 1: Sales, Orders & Subscriptions -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            
            <!-- 1. Total Revenue / Paid Sales -->
            <a href="{{ route('admin.reports.sales') }}" class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-xl shadow-xs hover:border-emerald-500 transition">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Sales</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                    </div>
                </div>
                <div class="text-lg sm:text-xl font-black text-slate-900 dark:text-white truncate">
                    ৳{{ number_format($stats['total_sales'], 2) }}
                </div>
                <p class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold mt-1">Paid customer sales</p>
            </a>

            <!-- 2. Total Orders -->
            <a href="{{ route('admin.orders.index') }}" class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-xl shadow-xs hover:border-blue-500 transition">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Orders</span>
                    <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                </div>
                <div class="text-lg sm:text-xl font-black text-slate-900 dark:text-white">
                    {{ number_format($stats['total_orders']) }}
                </div>
                <p class="text-[10px] text-slate-500 mt-1">All time customer orders</p>
            </a>

            <!-- 3. Pending Orders -->
            <a href="{{ route('admin.orders.index', ['order_status' => 'pending']) }}" class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-xl shadow-xs hover:border-amber-500 transition">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pending Orders</span>
                    <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                </div>
                <div class="text-lg sm:text-xl font-black text-amber-600 dark:text-amber-400">
                    {{ number_format($stats['pending_orders']) }}
                </div>
                <p class="text-[10px] text-slate-500 mt-1">Awaiting delivery/action</p>
            </a>

            <!-- 4. Paid Invoices / Transactions -->
            <a href="{{ route('admin.payments.index') }}" class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-xl shadow-xs hover:border-indigo-500 transition">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Paid Invoices</span>
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-credit-card"></i>
                    </div>
                </div>
                <div class="text-lg sm:text-xl font-black text-slate-900 dark:text-white">
                    {{ number_format($stats['paid_orders']) }}
                </div>
                <p class="text-[10px] text-indigo-600 font-semibold mt-1">Verified payments</p>
            </a>

            <!-- 5. Pending Subscriptions -->
            <a href="{{ route('admin.subscriptions.index', ['status' => 'pending']) }}" class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-xl shadow-xs hover:border-purple-500 transition">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pending Subs</span>
                    <div class="w-8 h-8 rounded-lg bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-arrows-spin"></i>
                    </div>
                </div>
                <div class="text-lg sm:text-xl font-black {{ $stats['pending_subscriptions'] > 0 ? 'text-purple-600 dark:text-purple-400' : 'text-slate-900 dark:text-white' }}">
                    {{ number_format($stats['pending_subscriptions']) }}
                </div>
                <p class="text-[10px] text-slate-500 mt-1">Need credentials delivery</p>
            </a>

        </div>

        <!-- Row 2: Customers, Products, Categories, Low Stock & Subscribers -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">

            <!-- 6. Total Customers -->
            <a href="{{ route('admin.users.index', ['role' => 'user']) }}" class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-xl shadow-xs hover:border-blue-500 transition">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Customers</span>
                    <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-brand-600 dark:text-brand-400 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <div class="text-lg sm:text-xl font-black text-slate-900 dark:text-white">
                    {{ number_format($stats['total_customers']) }}
                </div>
                <p class="text-[10px] text-slate-500 mt-1">Registered buyers</p>
            </a>

            <!-- 7. Catalog Products -->
            <a href="{{ route('admin.products.index') }}" class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-xl shadow-xs hover:border-emerald-500 transition">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Products</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-cubes"></i>
                    </div>
                </div>
                <div class="text-lg sm:text-xl font-black text-slate-900 dark:text-white">
                    {{ number_format($stats['total_products']) }}
                </div>
                <p class="text-[10px] text-slate-500 mt-1">Live digital & physical items</p>
            </a>

            <!-- 8. Low Stock Items Alert -->
            <a href="{{ route('admin.products.index') }}" class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-xl shadow-xs hover:border-rose-500 transition">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Low Stock Alert</span>
                    <div class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                </div>
                <div class="text-lg sm:text-xl font-black {{ $stats['low_stock_products_count'] > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-900 dark:text-white' }}">
                    {{ number_format($stats['low_stock_products_count']) }}
                </div>
                <p class="text-[10px] text-slate-500 mt-1">≤ 10 items remaining</p>
            </a>

            <!-- 9. Categories -->
            <a href="{{ route('admin.categories.index') }}" class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-xl shadow-xs hover:border-indigo-500 transition">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Categories</span>
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                </div>
                <div class="text-lg sm:text-xl font-black text-slate-900 dark:text-white">
                    {{ number_format($stats['total_categories']) }}
                </div>
                <p class="text-[10px] text-slate-500 mt-1">Catalog taxonomy</p>
            </a>

            <!-- 10. Newsletter Subscribers -->
            <a href="{{ route('admin.subscribers.index') }}" class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-xl shadow-xs hover:border-teal-500 transition">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Subscribers</span>
                    <div class="w-8 h-8 rounded-lg bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-envelope-open-text"></i>
                    </div>
                </div>
                <div class="text-lg sm:text-xl font-black text-slate-900 dark:text-white">
                    {{ number_format($stats['total_subscribers']) }}
                </div>
                <p class="text-[10px] text-slate-500 mt-1">Marketing email audience</p>
            </a>

        </div>
    </div>

    <!-- =========================================================================
         2. Charts Section: Sales Area Chart, Customer Growth Bar & Distribution Pie
         ========================================================================= -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Sales Revenue Graph (2 Columns) -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-xs space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                        Sales Growth Trend (Last 7 Days)
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Daily completed revenue volume in Bangladeshi Taka (৳)</p>
                </div>
                <a href="{{ route('admin.reports.sales') }}" class="text-xs font-bold text-brand-600 hover:underline">
                    Detailed Report →
                </a>
            </div>
            <div class="w-full h-72 relative">
                <canvas id="dashboardSalesChart"></canvas>
            </div>
        </div>

        <!-- Orders & Catalog Distribution Pie / Donut Chart (1 Column) -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-xs space-y-4 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                        Order Status Breakdown
                    </h2>
                    <span class="text-[10px] font-semibold text-slate-400">All Time</span>
                </div>
                <p class="text-xs text-slate-400 mt-0.5">Ratio of completed vs pending vs processing orders</p>
            </div>
            
            <div class="w-full h-56 relative flex items-center justify-center">
                <canvas id="dashboardOrderPieChart"></canvas>
            </div>

            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-100 dark:border-slate-800 text-xs">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                    <span class="text-slate-600 dark:text-slate-400 truncate">Completed ({{ $orderStatusCounts['completed'] }})</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 shrink-0"></span>
                    <span class="text-slate-600 dark:text-slate-400 truncate">Pending ({{ $orderStatusCounts['pending'] }})</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500 shrink-0"></span>
                    <span class="text-slate-600 dark:text-slate-400 truncate">Processing ({{ $orderStatusCounts['processing'] }})</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shrink-0"></span>
                    <span class="text-slate-600 dark:text-slate-400 truncate">Cancelled ({{ $orderStatusCounts['cancelled'] }})</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Customer Growth Bar Chart -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-xs space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                    Customer Registrations Growth (Last 7 Days)
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">New buyer accounts created per day</p>
            </div>
            <a href="{{ route('admin.reports.customer-growth') }}" class="text-xs font-bold text-brand-600 hover:underline">
                Full Growth Analytics →
            </a>
        </div>
        <div class="w-full h-64 relative">
            <canvas id="dashboardCustomerChart"></canvas>
        </div>
    </div>

    <!-- =========================================================================
         3. Recent Orders & Low Stock Products (2 Columns)
         ========================================================================= -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Recent Customer Orders -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <h2 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                        Recent Orders
                    </h2>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-brand-600 hover:underline">
                    View All →
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 dark:bg-slate-950/60 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                            <th class="px-4 py-3">Order</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                        @forelse($recentOrders as $order)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                                <td class="px-4 py-3 font-mono font-bold text-slate-900 dark:text-white">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-brand-600 hover:underline">
                                        #{{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-slate-900 dark:text-white truncate max-w-[130px]">{{ $order->customer_name }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $order->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-4 py-3 font-black text-slate-900 dark:text-white">
                                    ৳{{ number_format($order->total, 2) }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950 dark:text-amber-300' }}">
                                        {{ $order->payment_status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="p-1 text-slate-400 hover:text-brand-600 transition" title="View Order">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400">
                                    No customer orders yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Low Stock Products Alert -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <h2 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                        Low Stock Products (≤ 10 Items)
                    </h2>
                </div>
                <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-brand-600 hover:underline">
                    View Catalog →
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 dark:bg-slate-950/60 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                            <th class="px-4 py-3">Product</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Price</th>
                            <th class="px-4 py-3">Stock Left</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                        @forelse($lowStockProducts as $prod)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                                <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white">
                                    <div class="flex items-center gap-2">
                                        @if($prod->thumbnail_url)
                                            <img src="{{ $prod->thumbnail_url }}" alt="" class="w-7 h-7 rounded object-cover shrink-0">
                                        @endif
                                        <span class="truncate max-w-[150px]">{{ $prod->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 uppercase text-[10px] font-bold text-slate-400">
                                    {{ $prod->product_type }}
                                </td>
                                <td class="px-4 py-3 font-bold text-slate-900 dark:text-white">
                                    ৳{{ number_format($prod->effective_price) }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-black {{ $prod->stock_quantity <= 5 ? 'bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950 dark:text-rose-300' : 'bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950 dark:text-amber-300' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $prod->stock_quantity <= 5 ? 'bg-rose-500 animate-pulse' : 'bg-amber-500' }}"></span>
                                        {{ $prod->stock_quantity <= 0 ? 'Out of Stock' : $prod->stock_quantity . ' left' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.products.edit', $prod->id) }}" class="p-1 text-slate-400 hover:text-brand-600 transition" title="Update Stock">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400">
                                    <i class="fa-solid fa-circle-check text-emerald-500 text-lg mb-1 block"></i>
                                    All inventory levels are healthy! No low stock products.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- =========================================================================
         4. Recently Registered Users
         ========================================================================= -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <div>
                <h2 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-users text-brand-600"></i>
                    <span>Recently Registered Users</span>
                </h2>
                <p class="text-[11px] text-slate-400 mt-0.5">Latest customer registrations on DigiGo</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-brand-600 hover:underline">
                View All Users →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 dark:bg-slate-950/60 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                        <th class="px-4 py-3">User</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Phone</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Registered</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @forelse ($recentUsers as $u)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                            <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300 font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <span>{{ $u->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-400">
                                {{ $u->email }}
                            </td>
                            <td class="px-4 py-3 font-mono text-slate-500">
                                {{ $u->phone ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase {{ $u->role === 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300' : 'bg-blue-100 text-brand-800 dark:bg-blue-950 dark:text-brand-300' }} rounded-full">
                                    {{ $u->role }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950 dark:text-emerald-300 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    {{ $u->status ?? 'Active' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-400 text-[11px]">
                                {{ $u->created_at ? $u->created_at->diffForHumans() : '—' }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.users.edit', $u->id) }}" class="p-1 text-slate-400 hover:text-brand-600 transition" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-400">
                                No registered users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.06)';
        const textColor = isDark ? '#94a3b8' : '#64748b';

        // 1. Sales Growth Line / Area Chart
        const salesCanvas = document.getElementById('dashboardSalesChart');
        if (salesCanvas) {
            new Chart(salesCanvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: @json($salesLabels),
                    datasets: [{
                        label: 'Sales (৳)',
                        data: @json($salesData),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => '৳' + Number(ctx.raw).toLocaleString()
                            }
                        }
                    },
                    scales: {
                        x: { grid: { color: gridColor }, ticks: { color: textColor, font: { size: 10 } } },
                        y: {
                            grid: { color: gridColor },
                            ticks: {
                                color: textColor,
                                font: { size: 10 },
                                callback: val => '৳' + val.toLocaleString()
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // 2. Order Status Donut / Pie Chart
        const pieCanvas = document.getElementById('dashboardOrderPieChart');
        if (pieCanvas) {
            new Chart(pieCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Completed', 'Pending', 'Processing', 'Cancelled'],
                    datasets: [{
                        data: [
                            {{ $orderStatusCounts['completed'] }},
                            {{ $orderStatusCounts['pending'] }},
                            {{ $orderStatusCounts['processing'] }},
                            {{ $orderStatusCounts['cancelled'] }}
                        ],
                        backgroundColor: ['#10b981', '#f59e0b', '#3b82f6', '#ef4444'],
                        borderWidth: 2,
                        borderColor: isDark ? '#0f172a' : '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }

        // 3. Customer Growth Bar Chart
        const customerCanvas = document.getElementById('dashboardCustomerChart');
        if (customerCanvas) {
            new Chart(customerCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: @json($customerLabels),
                    datasets: [{
                        label: 'New Customers',
                        data: @json($customerData),
                        backgroundColor: '#3b82f6',
                        borderRadius: 6,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: textColor, font: { size: 10 } } },
                        y: {
                            grid: { color: gridColor },
                            ticks: { color: textColor, font: { size: 10 }, stepSize: 1, precision: 0 },
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    });
</script>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.06)';
        const textColor = isDark ? '#94a3b8' : '#64748b';

        // 1. Sales Growth Line / Area Chart
        const salesCanvas = document.getElementById('dashboardSalesChart');
        if (salesCanvas) {
            new Chart(salesCanvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: @json($salesLabels),
                    datasets: [{
                        label: 'Sales (৳)',
                        data: @json($salesData),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => '৳' + Number(ctx.raw).toLocaleString()
                            }
                        }
                    },
                    scales: {
                        x: { grid: { color: gridColor }, ticks: { color: textColor, font: { size: 10 } } },
                        y: {
                            grid: { color: gridColor },
                            ticks: {
                                color: textColor,
                                font: { size: 10 },
                                callback: val => '৳' + val.toLocaleString()
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // 2. Order Status Donut / Pie Chart
        const pieCanvas = document.getElementById('dashboardOrderPieChart');
        if (pieCanvas) {
            new Chart(pieCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Completed', 'Pending', 'Processing', 'Cancelled'],
                    datasets: [{
                        data: [
                            {{ $orderStatusCounts['completed'] }},
                            {{ $orderStatusCounts['pending'] }},
                            {{ $orderStatusCounts['processing'] }},
                            {{ $orderStatusCounts['cancelled'] }}
                        ],
                        backgroundColor: ['#10b981', '#f59e0b', '#3b82f6', '#ef4444'],
                        borderWidth: 2,
                        borderColor: isDark ? '#0f172a' : '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }

        // 3. Customer Growth Bar Chart
        const customerCanvas = document.getElementById('dashboardCustomerChart');
        if (customerCanvas) {
            new Chart(customerCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: @json($customerLabels),
                    datasets: [{
                        label: 'New Customers',
                        data: @json($customerData),
                        backgroundColor: '#3b82f6',
                        borderRadius: 6,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: textColor, font: { size: 10 } } },
                        y: {
                            grid: { color: gridColor },
                            ticks: { color: textColor, font: { size: 10 }, stepSize: 1, precision: 0 },
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    });
</script>
@endpush

