@extends('layouts.admin')

@section('title', 'Customer Growth Report')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-user-plus text-sm"></i>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">Customer Growth Analytics</h1>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Monitor registration trends, user acquisitions over time, and new account growth. Current View: <strong>{{ $dateRangeTitle }}</strong>
            </p>
        </div>

        <div class="flex items-center gap-2">
            <!-- Export to Excel (CSV) Button -->
            <a href="{{ route('admin.reports.customer-growth.export', ['period' => $period, 'start_date' => $startDate, 'end_date' => $endDate]) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition cursor-pointer">
                <i class="fa-solid fa-file-excel text-sm"></i>
                <span>Export to Excel (CSV)</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-xs transition">
                <i class="fa-solid fa-users"></i>
                <span>View All Customers</span>
            </a>
        </div>
    </div>

    <!-- Filter Toolbar (Today, This Week, Last Month, This Month, This Year, Custom Date Range) -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.reports.customer-growth') }}" class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            
            <!-- Quick Period Filter Tabs -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 lg:pb-0">
                <a href="{{ route('admin.reports.customer-growth', ['period' => 'daily']) }}"
                   class="px-3.5 py-2 text-xs font-bold rounded-lg transition {{ $period === 'daily' ? 'bg-brand-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 dark:bg-slate-700/60 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300' }}">
                    Today
                </a>
                <a href="{{ route('admin.reports.customer-growth', ['period' => 'week']) }}"
                   class="px-3.5 py-2 text-xs font-bold rounded-lg transition {{ $period === 'week' ? 'bg-brand-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 dark:bg-slate-700/60 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300' }}">
                    This Week
                </a>
                <a href="{{ route('admin.reports.customer-growth', ['period' => 'month']) }}"
                   class="px-3.5 py-2 text-xs font-bold rounded-lg transition {{ $period === 'month' ? 'bg-brand-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 dark:bg-slate-700/60 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300' }}">
                    This Month
                </a>
                <a href="{{ route('admin.reports.customer-growth', ['period' => 'last_month']) }}"
                   class="px-3.5 py-2 text-xs font-bold rounded-lg transition {{ $period === 'last_month' ? 'bg-brand-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 dark:bg-slate-700/60 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300' }}">
                    Last Month
                </a>
                <a href="{{ route('admin.reports.customer-growth', ['period' => 'year']) }}"
                   class="px-3.5 py-2 text-xs font-bold rounded-lg transition {{ $period === 'year' ? 'bg-brand-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 dark:bg-slate-700/60 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300' }}">
                    This Year
                </a>
            </div>

            <!-- Custom Date Range Filter -->
            <div class="flex items-center gap-2 shrink-0">
                <input type="hidden" name="period" value="custom">
                <div class="flex items-center gap-1 text-xs text-slate-500">
                    <label for="growth_start_date" class="font-semibold text-slate-700 dark:text-slate-300">From:</label>
                    <input type="date"
                           id="growth_start_date"
                           name="start_date"
                           value="{{ $startDate }}"
                           class="h-9 px-2.5 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-600">
                </div>
                <div class="flex items-center gap-1 text-xs text-slate-500">
                    <label for="growth_end_date" class="font-semibold text-slate-700 dark:text-slate-300">To:</label>
                    <input type="date"
                           id="growth_end_date"
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

    <!-- Registration Overview Metrics Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-4 shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Today</span>
            <div class="text-xl font-black text-slate-900 dark:text-white mt-1">
                +{{ $todayCount }}
            </div>
            <p class="text-[10px] text-emerald-600 font-semibold mt-0.5">New customers</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-4 shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">This Week</span>
            <div class="text-xl font-black text-slate-900 dark:text-white mt-1">
                +{{ $thisWeekCount }}
            </div>
            <p class="text-[10px] text-blue-600 font-semibold mt-0.5">Current week</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-4 shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">This Month</span>
            <div class="text-xl font-black text-slate-900 dark:text-white mt-1">
                +{{ $thisMonthCount }}
            </div>
            <p class="text-[10px] text-purple-600 font-semibold mt-0.5">Month to date</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-4 shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Last Month</span>
            <div class="text-xl font-black text-slate-900 dark:text-white mt-1">
                +{{ $lastMonthCount }}
            </div>
            <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Previous month</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-4 shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">This Year</span>
            <div class="text-xl font-black text-slate-900 dark:text-white mt-1">
                +{{ $thisYearCount }}
            </div>
            <p class="text-[10px] text-indigo-600 font-semibold mt-0.5">Year {{ date('Y') }}</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-4 shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">In Range</span>
            <div class="text-xl font-black text-slate-900 dark:text-white mt-1">
                {{ $periodTotalNewUsers }}
            </div>
            <p class="text-[10px] text-amber-600 font-semibold mt-0.5">Selected filter</p>
        </div>
    </div>

    <!-- Customer Growth Chart -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-5 shadow-xs space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                New Customer Registrations Trend
            </h2>
            <span class="text-xs font-medium text-slate-400">{{ $dateRangeTitle }}</span>
        </div>
        
        <div class="w-full h-80 relative">
            <canvas id="customerGrowthChartCanvas"></canvas>
        </div>
    </div>

    <!-- Customers Registered in Selected Range -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-200 dark:border-slate-700/80 flex items-center justify-between">
            <h2 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                Customers Registered in Selected Period (Recent 20)
            </h2>
            <a href="{{ route('admin.users.index', ['role' => 'user']) }}" class="text-xs font-bold text-brand-600 hover:underline">
                View All Users →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 dark:bg-slate-900/60 border-b border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                        <th class="px-5 py-3.5">Customer Name</th>
                        <th class="px-5 py-3.5">Email Address</th>
                        <th class="px-5 py-3.5">Phone</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Joined Date</th>
                        <th class="px-5 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/70 text-slate-700 dark:text-slate-300">
                    @forelse($periodUsers as $usr)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-750 transition">
                            <td class="px-5 py-3.5 font-bold text-slate-900 dark:text-white">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300 font-black text-xs flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($usr->name, 0, 1)) }}
                                    </div>
                                    <span>{{ $usr->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600 dark:text-slate-400">
                                {{ $usr->email }}
                            </td>
                            <td class="px-5 py-3.5 font-mono text-slate-500">
                                {{ $usr->phone ?? 'N/A' }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $usr->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950 dark:text-rose-300' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $usr->status === 'active' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                    {{ $usr->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-400 text-[11px]">
                                {{ $usr->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('admin.users.edit', $usr->id) }}" class="text-brand-600 hover:text-brand-700 font-semibold" title="Edit Customer">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">
                                No new customer registrations in this selected period.
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
        const ctx = document.getElementById('customerGrowthChartCanvas').getContext('2d');
        const labels = @json($labels);
        const growthData = @json($growthData);

        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.06)';
        const textColor = isDark ? '#94a3b8' : '#64748b';

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'New Customers',
                    data: growthData,
                    backgroundColor: 'rgba(37, 99, 235, 0.75)',
                    hoverBackgroundColor: '#2563eb',
                    borderRadius: 6,
                    borderSkipped: false,
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
                                return 'Joined: ' + Number(context.raw) + ' customers';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor, font: { size: 11 } }
                    },
                    y: {
                        grid: { color: gridColor },
                        ticks: {
                            color: textColor,
                            font: { size: 11 },
                            stepSize: 1,
                            precision: 0
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endpush

