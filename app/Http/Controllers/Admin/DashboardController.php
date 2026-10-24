<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Subscriber;
use App\Models\User;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the enhanced admin dashboard with 10 key ecommerce stats, charts, recent orders, and low stock items.
     */
    public function index(): View
    {
        $user = Auth::user();

        // 10 Key Ecommerce Stats (Displayed cleanly across 2 rows of 5 cards)
        $stats = [
            'total_sales' => (float) Order::where('payment_status', 'paid')->sum('total'),
            'total_orders' => Order::count(),
            'pending_orders' => Order::whereIn('order_status', ['pending', 'processing'])->count(),
            'paid_orders' => Order::where('payment_status', 'paid')->count(),
            'pending_subscriptions' => UserSubscription::where('status', 'pending')->count(),
            'total_customers' => User::where('role', 'user')->count(),
            'total_products' => Product::count(),
            'low_stock_products_count' => Product::where('stock_quantity', '<=', 10)->count(),
            'total_categories' => Category::count(),
            'total_subscribers' => Subscriber::count(),
        ];

        // Recent Orders (Latest 6)
        $recentOrders = Order::latest()->take(6)->get();

        // Low Stock Products (Stock <= 10, prioritized by lowest stock quantity)
        $lowStockProducts = Product::where('stock_quantity', '<=', 10)
            ->orderBy('stock_quantity', 'asc')
            ->take(6)
            ->get();

        // Recent Registered Users (Latest 5)
        $recentUsers = User::latest()->take(5)->get();

        // Sales Growth Data (Last 7 Days) for Line/Area Graph
        $salesLabels = [];
        $salesData = [];
        $today = Carbon::today();
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $salesLabels[] = $date->format('D, M j');
            $salesData[] = (float) Order::where('payment_status', 'paid')
                ->whereDate('created_at', $date)
                ->sum('total');
        }

        // Customer Growth Data (Last 7 Days) for Bar Chart
        $customerLabels = [];
        $customerData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $customerLabels[] = $date->format('D, M j');
            $customerData[] = User::where('role', 'user')
                ->whereDate('created_at', $date)
                ->count();
        }

        // Order Status Distribution for Pie / Donut Chart
        $orderStatusCounts = [
            'completed' => Order::where('order_status', 'completed')->count(),
            'processing' => Order::where('order_status', 'processing')->count(),
            'pending' => Order::where('order_status', 'pending')->count(),
            'cancelled' => Order::where('order_status', 'cancelled')->count(),
        ];

        // Catalog Distribution for Pie / Donut Chart (Digital vs Physical)
        $productTypeCounts = [
            'digital' => Product::where('product_type', 'digital')->count(),
            'physical' => Product::where('product_type', 'physical')->count(),
        ];

        return view('admin.dashboard', compact(
            'user',
            'stats',
            'recentOrders',
            'lowStockProducts',
            'recentUsers',
            'salesLabels',
            'salesData',
            'customerLabels',
            'customerData',
            'orderStatusCounts',
            'productTypeCounts'
        ));
    }
}
