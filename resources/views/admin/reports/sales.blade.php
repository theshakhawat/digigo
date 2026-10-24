@extends('layouts.admin')

@section('title', 'Sales Report')

@section('content')
<div class="space-y-6">

    <!-- Header Section with Date Range & Excel Export -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-chart-line text-sm"></i>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">Sales Revenue Report</h1>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Visual sales performance chart, order count, and revenue breakdown. Current View: <strong>{{ $dateRangeTitle }}</strong>
            </p>
        </div>

        <div class="flex items-center gap-2">
            <!-- Excel / CSV Export Button -->
            <a href="{{ route('admin.reports.sales.export', ['period' => $period, 'start_date' => $startDate, 'end_date' => $endDate]) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition cursor-pointer">
                <i class="fa-solid fa-file-excel text-sm"></i>
                <span>Export to Excel (CSV)</span>
            </a>
        </div>
    </div>

    <!-- Filter Toolbar (Daily, Week, Month, Year, Custom Date Range) -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.reports.sales') }}" class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            
            <!-- Quick Period Filter Tabs -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 lg:pb-0">
                <a href="{{ route('admin.reports.sales', ['period' => 'daily']) }}"
                   class="px-3.5 py-2 text-xs font-bold rounded-lg transition {{ $period === 'daily' ? 'bg-brand-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 dark:bg-slate-700/60 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300' }}">
                    Daily (Today)
                </a>
                <a href="{{ route('admin.reports.sales', ['period' => 'week']) }}"
                   class="px-3.5 py-2 text-xs font-bold rounded-lg transition {{ $period === 'week' ? 'bg-brand-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 dark:bg-slate-700/60 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300' }}">
                    This Week
                </a>
                <a href="{{ route('admin.reports.sales', ['period' => 'month']) }}"
                   class="px-3.5 py-2 text-xs font-bold rounded-lg transition {{ $period === 'month' ? 'bg-brand-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 dark:bg-slate-700/60 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300' }}">
                    This Month
                </a>
                <a href="{{ route('admin.reports.sales', ['period' => 'year']) }}"
                   class="px-3.5 py-2 text-xs font-bold rounded-lg transition {{ $period === 'year' ? 'bg-brand-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 dark:bg-slate-700/60 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300' }}">
                    This Year
                </a>
            </div>

            <!-- Custom Date Range Filter -->
            <div class="flex items-center gap-2 shrink-0">
                <input type="hidden" name="period" value="custom">
                <div class="flex items-center gap-1 text-xs text-slate-500">
                    <label for="start_date" class="font-semibold text-slate-700 dark:text-slate-300">From:</label>
                    <input type="date"
                           id="start_date"
                           name="start_date"
                           value="{{ $startDate }}"
                           class="h-9 px-2.5 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600">
                </div>
                <div class="flex items-center gap-1 text-xs text-slate-500">
                    <label for="end_date" class="font-semibold text-slate-700 dark:text-slate-300">To:</label>
                    <input type="date"
                           id="end_date"
                           name="end_date"
                           value="{{ $endDate }}"
                           class="h-9 px-2.5 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600">
                </div>
                <button type="submit"
                        class="px-3 py-2 text-xs font-bold rounded-lg bg-slate-900 dark:bg-slate-700 text-white hover:bg-slate-800 transition cursor-pointer">
                    Apply Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-5 shadow-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Period Sales</span>
            <div class="text-2xl font-black text-slate-900 dark:text-white mt-1.5 flex items-baseline gap-1">
                <span>৳{{ number_format($totalSales, 2) }}</span>
            </div>
            <p class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold mt-1">Paid customer purchases</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-5 shadow-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Paid Orders</span>
            <div class="text-2xl font-black text-slate-900 dark:text-white mt-1.5 flex items-baseline gap-1">
                <span>{{ number_format($totalOrdersCount) }}</span>
            </div>
            <p class="text-[11px] text-blue-600 dark:text-blue-400 font-semibold mt-1">Orders processed in range</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-5 shadow-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Average Order Value</span>
            <div class="text-2xl font-black text-slate-900 dark:text-white mt-1.5 flex items-baseline gap-1">
                <span>৳{{ number_format($averageOrderValue, 2) }}</span>
            </div>
            <p class="text-[11px] text-purple-600 dark:text-purple-400 font-semibold mt-1">Per transaction revenue</p>
        </div>
    </div>

    <!-- Interactive Sales Chart Section -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-5 shadow-xs space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                Sales Revenue Chart (৳ BDT)
            </h2>
            <span class="text-xs font-medium text-slate-400">{{ $dateRangeTitle }}</span>
        </div>
        
        <div class="w-full h-80 relative">
            <canvas id="salesChartCanvas"></canvas>
        </div>
    </div>

    <!-- Recent Period Orders Table -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-200 dark:border-slate-700/80 flex items-center justify-between">
            <h2 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                Orders in Selected Period (Recent 20)
            </h2>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-brand-600 hover:underline">
                View All Orders →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 dark:bg-slate-900/60 border-b border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                        <th class="px-5 py-3.5">Order</th>
                        <th class="px-5 py-3.5">Customer</th>
                        <th class="px-5 py-3.5">Total Amount</th>
                        <th class="px-5 py-3.5">Payment</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Date</th>
                        <th class="px-5 py-3.5 text-right">Invoice</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/70 text-slate-700 dark:text-slate-300">
                    @forelse($periodOrders as $ord)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-750 transition">
                            <td class="px-5 py-3.5 font-bold font-mono text-slate-900 dark:text-white">
                                <a href="{{ route('admin.orders.show', $ord->id) }}" class="text-brand-600 hover:underline">
                                    #{{ $ord->order_number }}
                                </a>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="font-semibold text-slate-900 dark:text-white">{{ $ord->customer_name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $ord->customer_email }}</div>
                            </td>
                            <td class="px-5 py-3.5 font-black text-slate-900 dark:text-white">
                                ৳{{ number_format($ord->total, 2) }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="capitalize text-slate-600 dark:text-slate-400 font-medium">{{ $ord->payment_method }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950 dark:text-emerald-300">
                                    {{ $ord->order_status }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-400 text-[11px]">
                                {{ $ord->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('admin.payments.invoice', $ord->id) }}" target="_blank" class="text-brand-600 hover:text-brand-700 font-semibold" title="View Invoice">
                                    <i class="fa-solid fa-file-invoice"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-slate-400">
                                No completed sales found in this period.
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
        const ctx = document.getElementById('salesChartCanvas').getContext('2d');
        const labels = @json($labels);
        const salesData = @json($salesData);

        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.06)';
        const textColor = isDark ? '#94a3b8' : '#64748b';

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sales Revenue (৳)',
                    data: salesData,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#ffffff',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: isDark ? '#0f172a' : '#1e293b',
                        padding: 10,
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12 },
                        callbacks: {
                            label: function(context) {
                                return 'Sales: ৳' + Number(context.raw).toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: gridColor },
                        ticks: { color: textColor, font: { size: 11 } }
                    },
                    y: {
                        grid: { color: gridColor },
                        ticks: {
                            color: textColor,
                            font: { size: 11 },
                            callback: function(value) {
                                return '৳' + value.toLocaleString();
                            }
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endpush

