<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Order;
use App\Models\UserSubscription;
use App\Services\AffiliateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $paymentStatus = $request->query('payment_status');
        $search = trim($request->query('search', ''));

        $ordersQuery = Order::with('items')->latest();

        if ($status && in_array($status, ['pending', 'confirmed', 'processing', 'completed', 'cancelled'])) {
            $ordersQuery->where('order_status', $status);
        }

        if ($paymentStatus && in_array($paymentStatus, ['paid', 'unpaid', 'failed', 'cancelled'])) {
            $ordersQuery->where('payment_status', $paymentStatus);
        }

        if ($search !== '') {
            $ordersQuery->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('dgepay_txn_number', 'like', "%{$search}%");
            });
        }

        $orders = $ordersQuery->paginate(15)->withQueryString();

        $counts = [
            'all' => Order::count(),
            'pending' => Order::where('order_status', 'pending')->count(),
            'confirmed' => Order::where('order_status', 'confirmed')->count(),
            'completed' => Order::where('order_status', 'completed')->count(),
            'cancelled' => Order::where('order_status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'counts', 'status', 'paymentStatus', 'search'));
    }

    /**
     * Show single order details.
     */
    public function show(Order $order): View
    {
        $order->load(['items.product', 'items.plan', 'user']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the printable professional invoice for this order.
     */
    public function invoice(Order $order): View
    {
        $order->load(['items.product', 'items.plan', 'user']);

        return view('admin.orders.invoice', compact('order'));
    }

    /**
     * Show the form for editing the order.
     */
    public function edit(Order $order): View
    {
        $order->load(['items', 'user']);

        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update order details, statuses and customer info.
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'division' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'street_address' => ['required', 'string', 'max:1000'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'order_status' => ['required', 'in:pending,confirmed,processing,completed,cancelled'],
            'payment_status' => ['required', 'in:unpaid,paid,failed,cancelled'],
            'payment_method' => ['required', 'in:cod,dgepay'],
        ]);

        if ($validated['payment_status'] === 'paid' && $order->payment_status !== 'paid') {
            $validated['paid_at'] = now();
        }

        $order->update($validated);

        // If order became paid, provision user subscriptions if digital
        if ($order->payment_status === 'paid' && $order->user_id) {
            $this->provisionSubscriptionsForPaidOrder($order);
        }

        // If order was delivered/completed or paid, trigger affiliate credit
        if ($order->affiliate_user_id && ! $order->affiliate_commission_paid) {
            app(AffiliateService::class)->processOrderCommission($order);
        }

        return redirect()->route('admin.orders.show', $order->id)->with('success', 'Order #'.$order->order_number.' updated successfully.');
    }

    /**
     * Quickly update order status and/or payment status directly from the order page or table.
     */
    public function updateQuickStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'order_status' => ['nullable', 'in:pending,confirmed,processing,completed,cancelled'],
            'payment_status' => ['nullable', 'in:unpaid,paid,failed,cancelled'],
        ]);

        $updates = [];

        if (! empty($validated['order_status'])) {
            $updates['order_status'] = $validated['order_status'];
        }

        if (! empty($validated['payment_status'])) {
            $updates['payment_status'] = $validated['payment_status'];
            if ($validated['payment_status'] === 'paid' && $order->payment_status !== 'paid') {
                $updates['paid_at'] = now();
            }
        }

        if (! empty($updates)) {
            $order->update($updates);

            if ($order->payment_status === 'paid' && $order->user_id) {
                $this->provisionSubscriptionsForPaidOrder($order);
            }

            if ($order->affiliate_user_id && ! $order->affiliate_commission_paid) {
                app(AffiliateService::class)->processOrderCommission($order);
            }
        }

        return back()->with('success', 'Status for Order #'.$order->order_number.' has been updated successfully.');
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order): RedirectResponse
    {
        $orderNumber = $order->order_number;
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order #'.$orderNumber.' was successfully deleted.');
    }

    /**
     * Provision user subscriptions for digital products upon successful payment.
     */
    protected function provisionSubscriptionsForPaidOrder(Order $order): void
    {
        $order->loadMissing('items.product', 'items.plan');
        $hasDigitalSubscriptions = false;
        $digitalItemNames = [];

        foreach ($order->items as $item) {
            $isDigital = ($item->product_type === 'digital') || ($item->product && $item->product->isDigital());
            if ($isDigital) {
                $hasDigitalSubscriptions = true;
                $digitalItemNames[] = $item->title;

                for ($q = 0; $q < $item->quantity; $q++) {
                    UserSubscription::firstOrCreate(
                        [
                            'order_id' => $order->id,
                            'product_id' => $item->product_id,
                            'plan_id' => $item->plan_id,
                            'user_id' => $order->user_id,
                        ],
                        [
                            'subscription_code' => UserSubscription::generateCode(),
                            'product_name' => $item->title,
                            'plan_name' => $item->plan?->name ?? 'Standard Plan',
                            'validity' => $item->plan?->validity ?? ($item->product?->default_validity ?? '1 Month'),
                            'price' => $item->unit_price,
                            'target_email' => $order->digital_target_email ?: $order->customer_email,
                            'target_phone' => $order->digital_target_phone ?: $order->customer_phone,
                            'access_instructions' => $item->product?->access_instructions ?? null,
                            'status' => 'pending',
                            'starts_at' => now(),
                        ]
                    );
                }
            }
        }

        if ($hasDigitalSubscriptions) {
            $itemsSummary = implode(', ', array_unique($digitalItemNames));
            AdminNotification::firstOrCreate(
                [
                    'type' => 'subscription',
                    'data->order_id' => $order->id,
                    'is_read' => false,
                ],
                [
                    'title' => 'Pending Subscription Needs Activation #'.$order->order_number,
                    'message' => "Customer {$order->customer_name} ordered digital product(s): {$itemsSummary}. Please provide credentials and activate the subscription.",
                    'link' => route('admin.subscriptions.index', ['status' => 'pending']),
                    'data' => [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'customer_name' => $order->customer_name,
                        'customer_email' => $order->customer_email,
                        'products' => $digitalItemNames,
                    ],
                ]
            );
        }
    }
}
