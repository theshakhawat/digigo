<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Sales Report with Daily, This Week, This Month, This Year, and Custom Date Range.
     */
    public function sales(Request $request): View
    {
        $period = $request->query('period', 'month'); // daily, week, month, year, custom
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $now = Carbon::now();
        $dateRangeTitle = '';
        $labels = [];
        $salesData = [];
        $ordersCountData = [];

        $query = Order::query()->where('payment_status', 'paid');

        switch ($period) {
            case 'daily':
                $dateRangeTitle = 'Today ('.$now->format('M d, Y').')';
                $start = $now->copy()->startOfDay();
                $end = $now->copy()->endOfDay();

                // 24 hours buckets
                for ($h = 0; $h < 24; $h++) {
                    $labels[] = sprintf('%02d:00', $h);
                    $salesData[$h] = 0.0;
                    $ordersCountData[$h] = 0;
                }

                $orders = (clone $query)->whereBetween('created_at', [$start, $end])->get();
                foreach ($orders as $order) {
                    $h = (int) $order->created_at->format('G');
                    $salesData[$h] += (float) $order->total;
                    $ordersCountData[$h] += 1;
                }
                break;

            case 'week':
                $start = $now->copy()->startOfWeek();
                $end = $now->copy()->endOfWeek();
                $dateRangeTitle = 'This Week ('.$start->format('M d').' - '.$end->format('M d, Y').')';

                for ($d = 0; $d < 7; $d++) {
                    $day = $start->copy()->addDays($d);
                    $labels[] = $day->format('D (M d)');
                    $salesData[$d] = 0.0;
                    $ordersCountData[$d] = 0;
                }

                $orders = (clone $query)->whereBetween('created_at', [$start, $end])->get();
                foreach ($orders as $order) {
                    $diff = $start->diffInDays($order->created_at->copy()->startOfDay(), false);
                    if ($diff >= 0 && $diff < 7) {
                        $salesData[$diff] += (float) $order->total;
                        $ordersCountData[$diff] += 1;
                    }
                }
                break;

            case 'year':
                $start = $now->copy()->startOfYear();
                $end = $now->copy()->endOfYear();
                $dateRangeTitle = 'This Year ('.$now->format('Y').')';

                for ($m = 1; $m <= 12; $m++) {
                    $labels[] = Carbon::create($now->year, $m, 1)->format('M');
                    $salesData[$m - 1] = 0.0;
                    $ordersCountData[$m - 1] = 0;
                }

                $orders = (clone $query)->whereBetween('created_at', [$start, $end])->get();
                foreach ($orders as $order) {
                    $m = (int) $order->created_at->format('n') - 1;
                    $salesData[$m] += (float) $order->total;
                    $ordersCountData[$m] += 1;
                }
                break;

            case 'custom':
                $start = $startDate ? Carbon::parse($startDate)->startOfDay() : $now->copy()->subDays(30)->startOfDay();
                $end = $endDate ? Carbon::parse($endDate)->endOfDay() : $now->copy()->endOfDay();
                $dateRangeTitle = $start->format('M d, Y').' - '.$end->format('M d, Y');

                $daysCount = $start->diffInDays($end) + 1;
                $orders = (clone $query)->whereBetween('created_at', [$start, $end])->get();

                for ($i = 0; $i < $daysCount; $i++) {
                    $current = $start->copy()->addDays($i);
                    $labels[] = $current->format('M d');
                    $salesData[$i] = 0.0;
                    $ordersCountData[$i] = 0;
                }

                foreach ($orders as $order) {
                    $diff = $start->diffInDays($order->created_at->copy()->startOfDay(), false);
                    if ($diff >= 0 && $diff < $daysCount) {
                        $salesData[$diff] += (float) $order->total;
                        $ordersCountData[$diff] += 1;
                    }
                }
                break;

            case 'month':
            default:
                $period = 'month';
                $start = $now->copy()->startOfMonth();
                $end = $now->copy()->endOfMonth();
                $dateRangeTitle = 'This Month ('.$now->format('F Y').')';

                $daysInMonth = $now->daysInMonth;
                for ($d = 1; $d <= $daysInMonth; $d++) {
                    $labels[] = (string) $d;
                    $salesData[$d - 1] = 0.0;
                    $ordersCountData[$d - 1] = 0;
                }

                $orders = (clone $query)->whereBetween('created_at', [$start, $end])->get();
                foreach ($orders as $order) {
                    $day = (int) $order->created_at->format('j') - 1;
                    $salesData[$day] += (float) $order->total;
                    $ordersCountData[$day] += 1;
                }
                break;
        }

        // Aggregate summary cards
        $totalSales = array_sum($salesData);
        $totalOrdersCount = array_sum($ordersCountData);
        $averageOrderValue = $totalOrdersCount > 0 ? $totalSales / $totalOrdersCount : 0.0;

        // Recent orders within the filtered period for the bottom table
        $periodOrders = (clone $query)->whereBetween('created_at', [$start, $end])
            ->latest()
            ->take(20)
            ->get();

        return view('admin.reports.sales', compact(
            'period',
            'startDate',
            'endDate',
            'dateRangeTitle',
            'labels',
            'salesData',
            'ordersCountData',
            'totalSales',
            'totalOrdersCount',
            'averageOrderValue',
            'periodOrders'
        ));
    }

    /**
     * Export sales data to CSV / Excel compatible format.
     */
    public function exportSales(Request $request): StreamedResponse
    {
        $period = $request->query('period', 'month');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $now = Carbon::now();
        $query = Order::query()->where('payment_status', 'paid');

        switch ($period) {
            case 'daily':
                $start = $now->copy()->startOfDay();
                $end = $now->copy()->endOfDay();
                break;
            case 'week':
                $start = $now->copy()->startOfWeek();
                $end = $now->copy()->endOfWeek();
                break;
            case 'year':
                $start = $now->copy()->startOfYear();
                $end = $now->copy()->endOfYear();
                break;
            case 'custom':
                $start = $startDate ? Carbon::parse($startDate)->startOfDay() : $now->copy()->subDays(30)->startOfDay();
                $end = $endDate ? Carbon::parse($endDate)->endOfDay() : $now->copy()->endOfDay();
                break;
            case 'month':
            default:
                $start = $now->copy()->startOfMonth();
                $end = $now->copy()->endOfMonth();
                break;
        }

        $orders = $query->whereBetween('created_at', [$start, $end])->latest()->get();

        $filename = 'sales_report_'.$period.'_'.Carbon::now()->format('Y_m_d_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM for Microsoft Excel
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Order ID',
                'Order Number',
                'Customer Name',
                'Customer Email',
                'Customer Phone',
                'Subtotal (BDT)',
                'Discount (BDT)',
                'Coupon Code',
                'Delivery Charge (BDT)',
                'Total Amount (BDT)',
                'Payment Method',
                'Payment Status',
                'Order Status',
                'Transaction ID',
                'Date & Time',
            ]);

            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->id,
                    $order->order_number,
                    $order->customer_name,
                    $order->customer_email,
                    $order->customer_phone,
                    $order->subtotal,
                    $order->discount,
                    $order->coupon_code ?? 'None',
                    $order->delivery_charge ?? 0,
                    $order->total,
                    $order->payment_method,
                    $order->payment_status,
                    $order->order_status,
                    $order->dgepay_unique_txn_id ?? 'N/A',
                    $order->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Customer Growth Report (Today, This Week, Last Month, This Month, This Year, Custom Date Range).
     */
    public function customerGrowth(Request $request): View
    {
        $period = $request->query('period', 'month');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $now = Carbon::now();
        $dateRangeTitle = '';
        $labels = [];
        $growthData = [];

        $query = User::query()->where('role', 'user');

        switch ($period) {
            case 'daily':
                $start = $now->copy()->startOfDay();
                $end = $now->copy()->endOfDay();
                $dateRangeTitle = 'Today ('.$now->format('M d, Y').')';

                for ($h = 0; $h < 24; $h++) {
                    $labels[] = sprintf('%02d:00', $h);
                    $growthData[$h] = 0;
                }

                $users = (clone $query)->whereBetween('created_at', [$start, $end])->get();
                foreach ($users as $user) {
                    $h = (int) $user->created_at->format('G');
                    $growthData[$h] += 1;
                }
                break;

            case 'week':
                $start = $now->copy()->startOfWeek();
                $end = $now->copy()->endOfWeek();
                $dateRangeTitle = 'This Week ('.$start->format('M d').' - '.$end->format('M d, Y').')';

                for ($d = 0; $d < 7; $d++) {
                    $day = $start->copy()->addDays($d);
                    $labels[] = $day->format('D (M d)');
                    $growthData[$d] = 0;
                }

                $users = (clone $query)->whereBetween('created_at', [$start, $end])->get();
                foreach ($users as $user) {
                    $diff = $start->diffInDays($user->created_at->copy()->startOfDay(), false);
                    if ($diff >= 0 && $diff < 7) {
                        $growthData[$diff] += 1;
                    }
                }
                break;

            case 'last_month':
                $start = $now->copy()->subMonth()->startOfMonth();
                $end = $now->copy()->subMonth()->endOfMonth();
                $dateRangeTitle = 'Last Month ('.$start->format('F Y').')';

                $daysInLastMonth = $start->daysInMonth;
                for ($d = 1; $d <= $daysInLastMonth; $d++) {
                    $labels[] = (string) $d;
                    $growthData[$d - 1] = 0;
                }

                $users = (clone $query)->whereBetween('created_at', [$start, $end])->get();
                foreach ($users as $user) {
                    $day = (int) $user->created_at->format('j') - 1;
                    $growthData[$day] += 1;
                }
                break;

            case 'year':
                $start = $now->copy()->startOfYear();
                $end = $now->copy()->endOfYear();
                $dateRangeTitle = 'This Year ('.$now->format('Y').')';

                for ($m = 1; $m <= 12; $m++) {
                    $labels[] = Carbon::create($now->year, $m, 1)->format('M');
                    $growthData[$m - 1] = 0;
                }

                $users = (clone $query)->whereBetween('created_at', [$start, $end])->get();
                foreach ($users as $user) {
                    $m = (int) $user->created_at->format('n') - 1;
                    $growthData[$m] += 1;
                }
                break;

            case 'custom':
                $start = $startDate ? Carbon::parse($startDate)->startOfDay() : $now->copy()->subDays(30)->startOfDay();
                $end = $endDate ? Carbon::parse($endDate)->endOfDay() : $now->copy()->endOfDay();
                $dateRangeTitle = $start->format('M d, Y').' - '.$end->format('M d, Y');

                $daysCount = $start->diffInDays($end) + 1;
                $users = (clone $query)->whereBetween('created_at', [$start, $end])->get();

                for ($i = 0; $i < $daysCount; $i++) {
                    $current = $start->copy()->addDays($i);
                    $labels[] = $current->format('M d');
                    $growthData[$i] = 0;
                }

                foreach ($users as $user) {
                    $diff = $start->diffInDays($user->created_at->copy()->startOfDay(), false);
                    if ($diff >= 0 && $diff < $daysCount) {
                        $growthData[$diff] += 1;
                    }
                }
                break;

            case 'month':
            default:
                $period = 'month';
                $start = $now->copy()->startOfMonth();
                $end = $now->copy()->endOfMonth();
                $dateRangeTitle = 'This Month ('.$now->format('F Y').')';

                $daysInMonth = $now->daysInMonth;
                for ($d = 1; $d <= $daysInMonth; $d++) {
                    $labels[] = (string) $d;
                    $growthData[$d - 1] = 0;
                }

                $users = (clone $query)->whereBetween('created_at', [$start, $end])->get();
                foreach ($users as $user) {
                    $day = (int) $user->created_at->format('j') - 1;
                    $growthData[$day] += 1;
                }
                break;
        }

        // KPI metrics
        $todayCount = User::where('role', 'user')->whereDate('created_at', Carbon::today())->count();
        $thisWeekCount = User::where('role', 'user')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $thisMonthCount = User::where('role', 'user')->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count();
        $lastMonthCount = User::where('role', 'user')->whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
        $thisYearCount = User::where('role', 'user')->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->count();
        $periodTotalNewUsers = array_sum($growthData);

        // Recent registrations within selected range
        $periodUsers = (clone $query)->whereBetween('created_at', [$start, $end])
            ->latest()
            ->take(20)
            ->get();

        return view('admin.reports.customer_growth', compact(
            'period',
            'startDate',
            'endDate',
            'dateRangeTitle',
            'labels',
            'growthData',
            'todayCount',
            'thisWeekCount',
            'thisMonthCount',
            'lastMonthCount',
            'thisYearCount',
            'periodTotalNewUsers',
            'periodUsers'
        ));
    }

    /**
     * Export customer growth / registrations to CSV / Excel compatible format.
     */
    public function exportCustomerGrowth(Request $request): StreamedResponse
    {
        $period = $request->query('period', 'month');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $now = Carbon::now();
        $query = User::query()->where('role', 'user');

        switch ($period) {
            case 'daily':
                $start = $now->copy()->startOfDay();
                $end = $now->copy()->endOfDay();
                break;
            case 'week':
                $start = $now->copy()->startOfWeek();
                $end = $now->copy()->endOfWeek();
                break;
            case 'last_month':
                $start = $now->copy()->subMonth()->startOfMonth();
                $end = $now->copy()->subMonth()->endOfMonth();
                break;
            case 'year':
                $start = $now->copy()->startOfYear();
                $end = $now->copy()->endOfYear();
                break;
            case 'custom':
                $start = $startDate ? Carbon::parse($startDate)->startOfDay() : $now->copy()->subDays(30)->startOfDay();
                $end = $endDate ? Carbon::parse($endDate)->endOfDay() : $now->copy()->endOfDay();
                break;
            case 'month':
            default:
                $start = $now->copy()->startOfMonth();
                $end = $now->copy()->endOfMonth();
                break;
        }

        $users = $query->whereBetween('created_at', [$start, $end])->latest()->get();

        $filename = 'customer_growth_'.$period.'_'.Carbon::now()->format('Y_m_d_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($users) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM for Microsoft Excel
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Customer ID',
                'Full Name',
                'Email Address',
                'Phone Number',
                'Account Status',
                'Role',
                'Registered Date & Time',
            ]);

            foreach ($users as $user) {
                fputcsv($handle, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->phone ?? 'N/A',
                    $user->status ?? 'active',
                    $user->role,
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
