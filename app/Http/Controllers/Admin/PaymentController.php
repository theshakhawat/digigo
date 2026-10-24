<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Display a list of successful/paid transactions.
     */
    public function index(Request $request): View
    {
        $search = trim($request->query('search', ''));
        $method = $request->query('method'); // 'dgepay' or 'cod'

        $paymentsQuery = Order::with('items')
            ->where('payment_status', 'paid')
            ->latest('paid_at');

        if ($method && in_array($method, ['dgepay', 'cod'])) {
            $paymentsQuery->where('payment_method', $method);
        }

        if ($search !== '') {
            $paymentsQuery->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('dgepay_txn_number', 'like', "%{$search}%")
                    ->orWhere('dgepay_third_party_txn_number', 'like', "%{$search}%");
            });
        }

        $payments = $paymentsQuery->paginate(15)->withQueryString();

        // Metrics for successful payments
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $dgepayRevenue = Order::where('payment_status', 'paid')->where('payment_method', 'dgepay')->sum('total');
        $codRevenue = Order::where('payment_status', 'paid')->where('payment_method', 'cod')->sum('total');
        $totalPaidCount = Order::where('payment_status', 'paid')->count();

        return view('admin.payments.index', compact(
            'payments',
            'search',
            'method',
            'totalRevenue',
            'dgepayRevenue',
            'codRevenue',
            'totalPaidCount'
        ));
    }

    /**
     * Display printable, clean invoice for a payment / order.
     */
    public function invoice(Order $order): View
    {
        $order->load(['items.product', 'items.plan', 'user']);

        return view('admin.payments.invoice', compact('order'));
    }
}
