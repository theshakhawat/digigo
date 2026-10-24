@extends('layouts.admin')

@section('title', 'Payments & Transactions - DigiGo')

@section('admin_content')
<!-- Page Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white flex items-center gap-2.5">
            <i class="fa-solid fa-credit-card text-emerald-600 dark:text-emerald-400"></i>
            <span>Successful Payments & Transactions</span>
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            Real-time ledger of all successfully paid customer transactions via DGePay Online and Cash on Delivery with downloadable invoices.
        </p>
    </div>
</div>

<!-- Revenue Statistics Overview -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Card 1: Total Paid Revenue -->
    <div class="p-5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Collected</span>
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400 flex items-center justify-center text-sm">
                <i class="fa-solid fa-bangladeshi-taka-sign"></i>
            </div>
        </div>
        <div class="text-2xl font-extrabold text-slate-900 dark:text-white">
            ৳{{ number_format($totalRevenue, 2) }}
        </div>
        <p class="text-[11px] text-slate-400 mt-1">{{ $totalPaidCount }} successful transactions</p>
    </div>

    <!-- Card 2: DGePay Online Gateway Revenue -->
    <div class="p-5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">DGePay Online</span>
            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-950 dark:text-blue-400 flex items-center justify-center text-sm">
                <i class="fa-solid fa-globe"></i>
            </div>
        </div>
        <div class="text-2xl font-extrabold text-blue-600 dark:text-blue-400">
            ৳{{ number_format($dgepayRevenue, 2) }}
        </div>
        <p class="text-[11px] text-slate-400 mt-1">bKash, Nagad, Rocket, Cards</p>
    </div>

    <!-- Card 3: COD Collected -->
    <div class="p-5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Cash on Delivery</span>
            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 dark:bg-purple-950 dark:text-purple-400 flex items-center justify-center text-sm">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </div>
        </div>
        <div class="text-2xl font-extrabold text-purple-600 dark:text-purple-400">
            ৳{{ number_format($codRevenue, 2) }}
        </div>
        <p class="text-[11px] text-slate-400 mt-1">Verified parcel collections</p>
    </div>

    <!-- Card 4: Gateway Health -->
    <div class="p-5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Gateway Status</span>
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
        </div>
        <div class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
            <i class="fa-solid fa-shield-halved text-emerald-600 text-sm"></i>
            <span>DGePay UAT Live</span>
        </div>
        <p class="text-[11px] text-emerald-600 font-semibold mt-1">Encrypted AES-128 & HMAC</p>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="mb-6 p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs">
    <form action="{{ route('admin.payments.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center gap-3">
        <!-- Search Input -->
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </div>
            <input type="text"
                   name="search"
                   value="{{ $search }}"
                   placeholder="Search by Order #, Trx ID, Customer Name, Phone..."
                   class="w-full h-10 pl-9 pr-4 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 focus:bg-white text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 dark:focus:border-brand-500 transition">
        </div>

        <!-- Method Filter -->
        <div class="w-full sm:w-48">
            <select name="method" class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-700 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                <option value="">All Payment Methods</option>
                <option value="dgepay" {{ $method === 'dgepay' ? 'selected' : '' }}>DGePay Online</option>
                <option value="cod" {{ $method === 'cod' ? 'selected' : '' }}>Cash on Delivery (COD)</option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="flex items-center gap-2 shrink-0">
            <button type="submit" class="px-4 h-10 bg-brand-800 hover:bg-brand-700 text-white dark:bg-brand-600 text-xs sm:text-sm font-semibold rounded-lg transition flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-filter text-xs"></i>
                <span>Filter</span>
            </button>
            @if($search || $method)
                <a href="{{ route('admin.payments.index') }}" class="px-3 h-10 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs sm:text-sm font-semibold rounded-lg transition flex items-center" title="Reset Filters">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Payments Table -->
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs sm:text-sm divide-y divide-slate-200 dark:divide-slate-800">
            <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-semibold uppercase text-[11px] tracking-wider">
                <tr>
                    <th class="px-5 py-3.5">Transaction & Order</th>
                    <th class="px-5 py-3.5">Customer</th>
                    <th class="px-5 py-3.5">Payment Method</th>
                    <th class="px-5 py-3.5">Paid At</th>
                    <th class="px-5 py-3.5 text-right">Amount</th>
                    <th class="px-5 py-3.5 text-center">Invoice</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse($payments as $payment)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                        <td class="px-5 py-4">
                            <a href="{{ route('admin.orders.show', $payment->id) }}" class="font-mono font-bold text-brand-600 dark:text-brand-400 hover:underline">
                                #{{ $payment->order_number }}
                            </a>
                            @php
                                $primaryTxn = $payment->dgepay_txn_number ?? $payment->dgepay_unique_txn_id;
                            @endphp
                            @if($primaryTxn)
                                <div class="text-[11px] font-mono text-slate-500 dark:text-slate-400 mt-0.5 flex items-center gap-1">
                                    <span class="text-slate-400 text-[10px]">TRX:</span>
                                    <span>{{ $primaryTxn }}</span>
                                </div>
                            @endif
                            @if($payment->dgepay_third_party_txn_number)
                                <div class="text-[10px] font-mono text-slate-400">
                                    MFS: {{ $payment->dgepay_third_party_txn_number }}
                                </div>
                            @endif
                        </td>

                        <td class="px-5 py-4">
                            <div class="font-semibold text-slate-900 dark:text-white">{{ $payment->customer_name }}</div>
                            <div class="text-xs text-slate-500">{{ $payment->customer_email }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $payment->customer_phone }}</div>
                        </td>

                        <td class="px-5 py-4">
                            <div class="flex items-center gap-1.5">
                                @if($payment->payment_method === 'dgepay')
                                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                    <span class="font-bold text-xs uppercase text-slate-800 dark:text-slate-200">
                                        DGePay ({{ $payment->dgepay_channel ?? 'Instant' }})
                                    </span>
                                @else
                                    <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                                    <span class="font-bold text-xs uppercase text-slate-800 dark:text-slate-200">
                                        Cash on Delivery
                                    </span>
                                @endif
                            </div>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 mt-1">
                                <i class="fa-solid fa-circle-check text-[9px]"></i> SUCCESS
                            </span>
                        </td>

                        <td class="px-5 py-4 text-slate-600 dark:text-slate-400">
                            <div>{{ ($payment->paid_at ?? $payment->created_at)->format('M d, Y') }}</div>
                            <div class="text-[11px] text-slate-400">{{ ($payment->paid_at ?? $payment->created_at)->format('h:i A') }}</div>
                        </td>

                        <td class="px-5 py-4 text-right">
                            <span class="font-extrabold text-emerald-600 dark:text-emerald-400 text-base">
                                ৳{{ number_format($payment->total, 2) }}
                            </span>
                        </td>

                        <td class="px-5 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.payments.invoice', $payment->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-brand-50 hover:text-brand-600 border border-slate-200 dark:bg-slate-800 dark:border-slate-700 dark:hover:bg-slate-700 dark:text-slate-300 text-xs font-semibold transition" title="View & Print Official Invoice">
                                    <i class="fa-solid fa-file-invoice text-xs"></i>
                                    <span>Invoice</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-slate-400 dark:text-slate-500">
                            <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-3 text-slate-400">
                                <i class="fa-solid fa-receipt text-xl"></i>
                            </div>
                            <p class="font-medium text-slate-700 dark:text-slate-300">No successful payments found.</p>
                            <p class="text-xs text-slate-400 mt-1">Completed orders will display their transaction receipts and invoices here.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($payments->hasPages())
        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $payments->links() }}
        </div>
    @endif
</div>
@endsection
