@extends('layouts.admin')

@section('title', 'Manage Orders - DigiGo')

@section('admin_content')
<!-- Page Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white flex items-center gap-2.5">
            <i class="fa-solid fa-cart-shopping text-brand-600 dark:text-brand-400"></i>
            <span>Orders Management</span>
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            Review confirmed customer orders, track payments via DGePay or Cash on Delivery, update fulfillment statuses, and manage records.
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
    <a href="{{ route('admin.orders.index') }}" class="p-3.5 bg-white dark:bg-slate-900 border {{ empty($status) ? 'border-brand-600 ring-2 ring-brand-600/10' : 'border-slate-200 dark:border-slate-800' }} rounded-xl flex items-center justify-between">
        <span class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider">All Orders</span>
        <span class="px-2 py-0.5 rounded-full text-xs font-extrabold bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200">{{ $counts['all'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}" class="p-3.5 bg-white dark:bg-slate-900 border {{ $status === 'confirmed' ? 'border-emerald-600 ring-2 ring-emerald-600/10' : 'border-slate-200 dark:border-slate-800' }} rounded-xl flex items-center justify-between">
        <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Confirmed</span>
        <span class="px-2 py-0.5 rounded-full text-xs font-extrabold bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400">{{ $counts['confirmed'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="p-3.5 bg-white dark:bg-slate-900 border {{ $status === 'pending' ? 'border-amber-600 ring-2 ring-amber-600/10' : 'border-slate-200 dark:border-slate-800' }} rounded-xl flex items-center justify-between">
        <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Pending</span>
        <span class="px-2 py-0.5 rounded-full text-xs font-extrabold bg-amber-50 text-amber-700 dark:bg-amber-950 dark:text-amber-400">{{ $counts['pending'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="p-3.5 bg-white dark:bg-slate-900 border {{ $status === 'completed' ? 'border-blue-600 ring-2 ring-blue-600/10' : 'border-slate-200 dark:border-slate-800' }} rounded-xl flex items-center justify-between">
        <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Completed</span>
        <span class="px-2 py-0.5 rounded-full text-xs font-extrabold bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-400">{{ $counts['completed'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="p-3.5 bg-white dark:bg-slate-900 border {{ $status === 'cancelled' ? 'border-rose-600 ring-2 ring-rose-600/10' : 'border-slate-200 dark:border-slate-800' }} rounded-xl flex items-center justify-between col-span-2 sm:col-span-1">
        <span class="text-xs font-bold text-rose-600 uppercase tracking-wider">Cancelled</span>
        <span class="px-2 py-0.5 rounded-full text-xs font-extrabold bg-rose-50 text-rose-700 dark:bg-rose-950 dark:text-rose-400">{{ $counts['cancelled'] }}</span>
    </a>
</div>

<!-- Search & Filter Card -->
<div class="mb-6 p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center gap-3">
        @if($status)
            <input type="hidden" name="status" value="{{ $status }}">
        @endif

        <!-- Search Input -->
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </div>
            <input type="text"
                   name="search"
                   value="{{ $search }}"
                   placeholder="Search by Order #, Customer Name, Email, Phone..."
                   class="w-full h-10 pl-9 pr-4 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 focus:bg-white text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 dark:focus:border-brand-500 transition">
        </div>

        <!-- Payment Status Filter -->
        <div class="w-full sm:w-44">
            <select name="payment_status" class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-700 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                <option value="">All Payment Statuses</option>
                <option value="paid" {{ $paymentStatus === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="unpaid" {{ $paymentStatus === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                <option value="failed" {{ $paymentStatus === 'failed' ? 'selected' : '' }}>Failed</option>
                <option value="cancelled" {{ $paymentStatus === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="flex items-center gap-2 shrink-0">
            <button type="submit" class="px-4 h-10 bg-brand-800 hover:bg-brand-700 text-white dark:bg-brand-600 text-xs sm:text-sm font-semibold rounded-lg transition flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-filter text-xs"></i>
                <span>Filter</span>
            </button>
            @if($search || $paymentStatus || $status)
                <a href="{{ route('admin.orders.index') }}" class="px-3 h-10 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs sm:text-sm font-semibold rounded-lg transition flex items-center" title="Reset Filters">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs sm:text-sm divide-y divide-slate-200 dark:divide-slate-800">
            <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-semibold uppercase text-[11px] tracking-wider">
                <tr>
                    <th class="px-5 py-3.5 whitespace-nowrap min-w-[150px]">Order Info</th>
                    <th class="px-5 py-3.5 whitespace-nowrap min-w-[180px]">Customer</th>
                    <th class="px-5 py-3.5 min-w-[260px]">Items</th>
                    <th class="px-5 py-3.5 whitespace-nowrap min-w-[140px]">Payment</th>
                    <th class="px-5 py-3.5 whitespace-nowrap min-w-[150px]">Status</th>
                    <th class="px-5 py-3.5 text-right whitespace-nowrap min-w-[110px]">Total</th>
                    <th class="px-5 py-3.5 text-center whitespace-nowrap min-w-[110px]">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse($orders as $order)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                        <td class="px-5 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="font-mono font-bold text-brand-600 dark:text-brand-400 hover:underline">
                                #{{ $order->order_number }}
                            </a>
                            <div class="text-[11px] text-slate-400 mt-0.5">
                                {{ $order->created_at->format('M d, Y h:i A') }}
                            </div>
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="font-semibold text-slate-900 dark:text-white">{{ $order->customer_name }}</div>
                            <div class="text-xs text-slate-500">{{ $order->customer_email }}</div>
                            <div class="text-xs text-slate-400 flex items-center gap-1 mt-0.5">
                                <i class="fa-solid fa-phone text-[10px]"></i>
                                <span>{{ $order->customer_phone }}</span>
                            </div>
                        </td>

                        <td class="px-5 py-4">
                            <div class="flex items-center gap-1.5 mb-0.5">
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $order->items->count() }}</span>
                                <span class="text-xs text-slate-500">{{ Str::plural('item', $order->items->count()) }}</span>
                            </div>
                            <div class="text-xs text-slate-400 leading-snug line-clamp-2 max-w-sm" title="{{ $order->items->pluck('title')->implode(', ') }}">
                                {{ $order->items->pluck('title')->implode(', ') }}
                            </div>
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="font-semibold uppercase text-xs text-slate-900 dark:text-slate-200">
                                {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'DGePay' }}
                            </div>
                            <div class="mt-1">
                                @if($order->payment_status === 'paid')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                                        <i class="fa-solid fa-circle-check text-[9px]"></i> PAID
                                    </span>
                                @elseif($order->payment_status === 'unpaid')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300">
                                        <i class="fa-solid fa-clock text-[9px]"></i> UNPAID
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300">
                                        {{ strtoupper($order->payment_status) }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap">
                            <form action="{{ route('admin.orders.quick-status', $order->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <select name="order_status" onchange="this.form.submit()" class="text-xs font-bold rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 px-2.5 py-1.5 focus:ring-2 focus:ring-brand-500 cursor-pointer">
                                    <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>PENDING</option>
                                    <option value="confirmed" {{ $order->order_status === 'confirmed' ? 'selected' : '' }}>CONFIRMED</option>
                                    <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>PROCESSING</option>
                                    <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>COMPLETED</option>
                                    <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>CANCELLED</option>
                                </select>
                            </form>
                        </td>

                        <td class="px-5 py-4 text-right whitespace-nowrap">
                            <span class="font-extrabold text-slate-900 dark:text-white text-base">
                                ৳{{ number_format($order->total, 2) }}
                            </span>
                        </td>

                        <td class="px-5 py-4 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-600 hover:text-brand-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 transition" title="View Order Details">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.orders.edit', $order->id) }}" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-600 hover:text-amber-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 transition" title="Edit Order">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete order #{{ $order->order_number }}? This cannot be undone.');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 flex items-center justify-center text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/50 transition cursor-pointer" title="Delete Order">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-slate-400 dark:text-slate-500">
                            <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-3 text-slate-400">
                                <i class="fa-solid fa-box-open text-xl"></i>
                            </div>
                            <p class="font-medium text-slate-700 dark:text-slate-300">No orders found.</p>
                            <p class="text-xs text-slate-400 mt-1">Orders placed by customers will appear here.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection

