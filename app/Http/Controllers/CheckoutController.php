<?php

namespace App\Http\Controllers;

use App\Mail\OrderInvoiceMail;
use App\Models\Address;
use App\Models\AdminNotification;
use App\Models\AffiliateEarning;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductPlan;
use App\Models\UserNotification;
use App\Models\UserSubscription;
use App\Services\AffiliateService;
use App\Services\DgePayService;
use App\Services\MailConfigService;
use App\Services\ShippingService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Resolve cart items strictly with authoritative prices from the database.
     * Prevents any client-side tampering of price or amounts.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function resolveAuthoritativeCartItems(): array
    {
        $sessionCart = session('cart_items', []);

        // If no cart items exist in session, check if active products exist in DB
        if (empty($sessionCart)) {
            $fallbackProducts = Product::with(['category', 'plans'])->where('status', 'active')->take(2)->get();
            if ($fallbackProducts->isNotEmpty()) {
                $sessionCart = [];
                foreach ($fallbackProducts as $fp) {
                    $sessionCart[(string) $fp->id] = [
                        'id' => $fp->id,
                        'product_id' => $fp->id,
                        'plan_id' => null,
                        'quantity' => 1,
                    ];
                }
            } else {
                // Return default demo items for empty environments / standalone tests
                return [
                    '1' => [
                        'id' => 1,
                        'cart_key' => '1',
                        'product_id' => 1,
                        'plan_id' => null,
                        'title' => 'ChatGPT Plus 1 Month Private Account',
                        'slug' => 'chatgpt-plus-subscription',
                        'category_id' => 1,
                        'category' => 'Digital Goods',
                        'product_type' => 'digital',
                        'price' => 2450.0,
                        'quantity' => 1,
                        'image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=400&q=80',
                    ],
                ];
            }
        }

        $authoritativeCart = [];

        foreach ($sessionCart as $cartKey => $item) {
            $productId = $item['product_id'] ?? $item['id'] ?? null;
            if (! $productId) {
                continue;
            }

            $product = Product::with(['category', 'brand'])->find($productId);
            if (! $product) {
                continue;
            }

            $planId = $item['plan_id'] ?? null;
            $plan = $planId ? ProductPlan::find($planId) : null;
            $quantity = max(1, (int) ($item['quantity'] ?? 1));

            // Strictly compute prices from database models
            $unitPrice = $plan ? (float) $plan->effective_price : (float) $product->effective_price;
            $title = $plan ? "{$product->name} - {$plan->name}" : $product->name;
            $cartKey = $plan ? "{$product->id}-{$plan->id}" : (string) $product->id;

            $authoritativeCart[$cartKey] = [
                'id' => $product->id,
                'cart_key' => $cartKey,
                'product_id' => $product->id,
                'plan_id' => $plan?->id,
                'title' => $title,
                'slug' => $product->slug,
                'category_id' => $product->category_id,
                'category' => $product->category->name ?? 'General',
                'product_type' => $product->product_type,
                'price' => $unitPrice,
                'quantity' => $quantity,
                'image' => $product->thumbnail_url ?: 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=400&q=80',
            ];
        }

        return $authoritativeCart;
    }

    /**
     * Compute full order calculation summary on backend.
     *
     * @return array<string, mixed>
     */
    public static function calculateOrderSummary(?string $division = null, ?string $district = null): array
    {
        $cartItems = self::resolveAuthoritativeCartItems();

        $subtotal = 0.0;
        $itemsCount = 0;
        foreach ($cartItems as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
            $itemsCount += $item['quantity'];
        }

        $userAddress = auth()->check() ? auth()->user()->address : null;

        $shippingSession = session('shipping_destination');
        $resolvedDivision = $division ?: ($shippingSession['division'] ?? ($userAddress?->division ?? 'Dhaka'));
        $resolvedDistrict = $district ?: ($shippingSession['district'] ?? ($userAddress?->district ?? 'Dhaka'));

        $shippingResult = ShippingService::calculate($cartItems, $resolvedDivision, $resolvedDistrict);
        $deliveryCharge = (float) $shippingResult['delivery_charge'];
        $productShippingCharge = (float) $shippingResult['shipping_charge'];
        $totalCombinedShipping = (float) $shippingResult['charge'];

        // Re-evaluate applied coupon with backend-derived subtotal & items
        $appliedCoupon = session('applied_coupon');
        $discount = 0.0;

        if ($appliedCoupon && ! empty($appliedCoupon['code'])) {
            $couponModel = Coupon::where('code', $appliedCoupon['code'])->first();
            if ($couponModel) {
                $calc = $couponModel->calculateDiscount($cartItems, auth()->user(), auth()->user()?->email);
                if ($calc['valid']) {
                    $discount = (float) $calc['discount'];
                    // Refresh coupon in session with verified backend discount
                    session([
                        'applied_coupon' => [
                            'id' => $couponModel->id,
                            'code' => $couponModel->code,
                            'discount' => $discount,
                            'discount_type' => $couponModel->discount_type,
                            'discount_value' => $couponModel->discount_value,
                        ],
                    ]);
                } else {
                    session()->forget('applied_coupon');
                    $appliedCoupon = null;
                }
            } else {
                session()->forget('applied_coupon');
                $appliedCoupon = null;
            }
        }

        $total = max(0, $subtotal - $discount + $totalCombinedShipping);

        $hasDigital = false;
        $hasPhysical = false;
        foreach ($cartItems as $item) {
            $pType = $item['product_type'] ?? 'digital';
            if ($pType === 'digital') {
                $hasDigital = true;
            } elseif ($pType === 'physical') {
                $hasPhysical = true;
            }
        }

        // COD is only allowed if there are physical items AND no digital items
        $codAllowed = $hasPhysical && ! $hasDigital;

        return [
            'cart_items' => $cartItems,
            'items_count' => $itemsCount,
            'subtotal' => $subtotal,
            'subtotal_formatted' => '৳'.number_format($subtotal, 2),
            'discount' => $discount,
            'discount_formatted' => '৳'.number_format($discount, 2),
            'delivery_charge' => $deliveryCharge,
            'delivery_charge_formatted' => $shippingResult['is_free'] ? 'FREE' : '৳'.number_format($deliveryCharge, 2),
            'shipping_charge' => $productShippingCharge,
            'shipping_charge_formatted' => '৳'.number_format($productShippingCharge, 2),
            'total_shipping_combined' => $totalCombinedShipping,
            'total' => $total,
            'total_formatted' => '৳'.number_format($total, 2),
            'is_inside_dhaka' => $shippingResult['is_inside_dhaka'],
            'is_free_delivery' => $shippingResult['is_free'],
            'has_physical' => $shippingResult['has_physical'],
            'has_digital' => $hasDigital,
            'cod_allowed' => $codAllowed,
            'shipping_result' => $shippingResult,
            'division' => $resolvedDivision,
            'district' => $resolvedDistrict,
            'applied_coupon' => session('applied_coupon'),
        ];
    }

    /**
     * Show checkout page.
     */
    public function index(): View
    {
        $calculation = self::calculateOrderSummary();

        $cartItems = $calculation['cart_items'];
        $orderSummary = [
            'items_count' => $calculation['items_count'],
            'subtotal' => $calculation['subtotal'],
            'discount' => $calculation['discount'],
            'delivery_charge' => $calculation['delivery_charge'],
            'shipping_charge' => $calculation['shipping_charge'],
            'total' => $calculation['total'],
            'is_inside_dhaka' => $calculation['is_inside_dhaka'],
            'is_free_delivery' => $calculation['is_free_delivery'],
            'has_physical' => $calculation['has_physical'],
            'has_digital' => $calculation['has_digital'],
            'cod_allowed' => $calculation['cod_allowed'],
        ];

        $appliedCoupon = $calculation['applied_coupon'];
        $shippingResult = $calculation['shipping_result'];
        $initialDivision = $calculation['division'];
        $initialDistrict = $calculation['district'];
        $codAllowed = $calculation['cod_allowed'];
        $hasDigital = $calculation['has_digital'];
        $savedAddress = auth()->check() ? auth()->user()->address : null;

        return view('checkout', compact(
            'cartItems',
            'orderSummary',
            'appliedCoupon',
            'shippingResult',
            'initialDivision',
            'initialDistrict',
            'codAllowed',
            'hasDigital',
            'savedAddress'
        ));
    }

    /**
     * AJAX endpoint to fetch real-time backend order calculation.
     */
    public function calculate(Request $request): JsonResponse
    {
        $division = $request->input('division');
        $district = $request->input('district');

        $calculation = self::calculateOrderSummary($division, $district);

        return response()->json([
            'success' => true,
            'subtotal' => $calculation['subtotal'],
            'subtotal_formatted' => $calculation['subtotal_formatted'],
            'discount' => $calculation['discount'],
            'discount_formatted' => $calculation['discount_formatted'],
            'delivery_charge' => $calculation['delivery_charge'],
            'delivery_charge_formatted' => $calculation['delivery_charge_formatted'],
            'shipping_charge' => $calculation['shipping_charge'],
            'shipping_charge_formatted' => $calculation['shipping_charge_formatted'],
            'total' => $calculation['total'],
            'total_formatted' => $calculation['total_formatted'],
            'is_inside_dhaka' => $calculation['is_inside_dhaka'],
            'is_free_delivery' => $calculation['is_free_delivery'],
            'has_physical' => $calculation['has_physical'],
            'has_digital' => $calculation['has_digital'],
            'cod_allowed' => $calculation['cod_allowed'],
            'coupon' => $calculation['applied_coupon'],
        ]);
    }

    /**
     * Place order from checkout form.
     */
    public function placeOrder(Request $request, DgePayService $dgePayService): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'digital_target_email' => ['nullable', 'email', 'max:255'],
            'digital_target_phone' => ['nullable', 'string', 'max:30'],
            'digital_account_notes' => ['nullable', 'string', 'max:1000'],
            'division' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'street_address' => ['required', 'string', 'max:1000'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['nullable', 'string'],
        ]);

        $calculation = self::calculateOrderSummary($validated['division'], $validated['district']);
        $cartItems = $calculation['cart_items'];

        if (empty($cartItems)) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Your cart is empty.'], 422);
            }

            return back()->with('error', 'Your cart is empty.');
        }

        $isZeroTotal = ((float) $calculation['total']) <= 0;

        // If total is 0 (e.g. coupon covered the whole price), no payment gateway or COD is needed
        if ($isZeroTotal) {
            $paymentMethod = 'free';
        } else {
            $validatedPayment = $request->validate([
                'payment_method' => ['required', 'in:cod,dgepay'],
            ]);
            $paymentMethod = $validatedPayment['payment_method'];

            // Strict Enforcement: Digital products cannot be purchased via COD
            if ($paymentMethod === 'cod' && ! $calculation['cod_allowed']) {
                $msg = 'Cash on Delivery (COD) is not available for digital products or mixed orders. Please pay online via DGePay.';
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }

                return back()->with('error', $msg);
            }
        }

        // Check Inventory Stock for Physical Items
        foreach ($cartItems as $item) {
            $productId = $item['product_id'] ?? null;
            if ($productId) {
                $productModel = Product::find($productId);
                if ($productModel && $productModel->isPhysical()) {
                    $orderedQty = (int) ($item['quantity'] ?? 1);
                    $availableStock = (int) $productModel->stock_quantity;
                    if ($availableStock <= 0) {
                        $msg = "Sorry, '{$productModel->name}' is currently out of stock!";

                        return $request->expectsJson()
                            ? response()->json(['success' => false, 'message' => $msg], 422)
                            : back()->with('error', $msg);
                    }
                    if ($orderedQty > $availableStock) {
                        $msg = "Sorry, only {$availableStock} unit(s) of '{$productModel->name}' are available in stock.";

                        return $request->expectsJson()
                            ? response()->json(['success' => false, 'message' => $msg], 422)
                            : back()->with('error', $msg);
                    }
                }
            }
        }

        DB::beginTransaction();
        try {
            $isCod = $paymentMethod === 'cod';
            $isFree = $paymentMethod === 'free' || ((float) $calculation['total']) <= 0;

            // Resolve affiliate referrer (from session or cookie)
            $affiliateService = app(AffiliateService::class);
            $affiliateCode = session('affiliate_ref') ?? request()->cookie('digigo_affiliate_ref');
            $affiliateUser = $affiliateService->resolveUserByCode($affiliateCode);
            // Self-referral protection: user cannot refer themselves
            if ($affiliateUser && auth()->check() && $affiliateUser->id === auth()->id()) {
                $affiliateUser = null;
            }

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id(),
                'affiliate_user_id' => $affiliateUser?->id,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'digital_target_email' => $validated['digital_target_email'] ?? null,
                'digital_target_phone' => $validated['digital_target_phone'] ?? null,
                'digital_account_notes' => $validated['digital_account_notes'] ?? null,
                'division' => $validated['division'],
                'district' => $validated['district'],
                'street_address' => $validated['street_address'],
                'postal_code' => $validated['postal_code'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'subtotal' => $calculation['subtotal'],
                'discount' => $calculation['discount'],
                'coupon_code' => $calculation['applied_coupon']['code'] ?? null,
                'delivery_charge' => $calculation['delivery_charge'],
                'shipping_charge' => $calculation['shipping_charge'] ?? 0.00,
                'total' => $calculation['total'],
                'payment_method' => $paymentMethod,
                'payment_status' => $isFree ? 'paid' : 'unpaid',
                'order_status' => ($isCod || $isFree) ? 'confirmed' : 'pending',
                'paid_at' => $isFree ? now() : null,
                'dgepay_unique_txn_id' => (! $isCod && ! $isFree) ? DgePayService::generateTransactionId() : null,
            ]);

            // Save / Update customer's primary address in addresses table
            if (auth()->check()) {
                Address::updateOrCreate(
                    ['user_id' => auth()->id()],
                    [
                        'full_name' => $validated['customer_name'],
                        'phone' => $validated['customer_phone'],
                        'division' => $validated['division'],
                        'district' => $validated['district'],
                        'street_address' => $validated['street_address'],
                        'postal_code' => $validated['postal_code'] ?? null,
                    ]
                );
            }

            $totalCommission = 0;
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'] ?? null,
                    'plan_id' => $item['plan_id'] ?? null,
                    'title' => $item['title'],
                    'product_type' => $item['product_type'] ?? 'digital',
                    'unit_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => ($item['price'] * $item['quantity']),
                    'image' => $item['image'] ?? null,
                ]);

                if ($affiliateUser) {
                    $totalCommission += $affiliateService->calculateItemCommission($item);
                }
            }

            if ($affiliateUser && $totalCommission > 0) {
                $order->affiliate_commission = $totalCommission;
                $order->saveQuietly();

                // Create pending AffiliateEarning record
                AffiliateEarning::create([
                    'user_id' => $affiliateUser->id,
                    'order_id' => $order->id,
                    'amount' => $totalCommission,
                    'status' => 'pending',
                ]);
            }

            DB::commit();

            // Scenario 1: Free Order (e.g. 100% coupon discount / 0 tk total) or COD -> Order instantly confirmed without payment gateway
            if ($isFree || $isCod) {
                $this->finalizeConfirmedOrder($order);

                // Clear cart & coupon sessions
                session()->forget(['cart_items', 'applied_coupon', 'shipping_destination']);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'order_id' => $order->id,
                        'redirect_url' => route('checkout.success', $order->id),
                    ]);
                }

                return redirect()->route('checkout.success', $order->id);
            }

            // Scenario 2: DGePay Online Payment Gateway
            $initResult = $dgePayService->initiatePayment([
                'amount' => (float) $order->total,
                'description' => "Order #{$order->order_number}",
                'orderId' => $order->dgepay_unique_txn_id,
                'redirectUrl' => route('checkout.dgepay.callback'),
                'unique_user_reference' => (string) ($order->user_id ?? $order->customer_email),
            ]);

            if ($initResult['success'] && ! empty($initResult['payment_url'])) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'payment_url' => $initResult['payment_url'],
                    ]);
                }

                return redirect()->away($initResult['payment_url']);
            }

            // If initiation failed
            Log::error('DGePay Initiate Payment Failed for Order #'.$order->order_number, $initResult);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $initResult['message'] ?? 'Could not initiate DGePay payment gateway. Please try again.',
                ], 400);
            }

            return back()->with('error', $initResult['message'] ?? 'Could not initiate DGePay payment gateway. Please try again.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Checkout place order exception: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'An error occurred while placing order: '.$e->getMessage()], 500);
            }

            return back()->with('error', 'An error occurred while placing your order. Please try again.');
        }
    }

    /**
     * Handle return/callback from DGePay payment gateway.
     */
    public function dgepayCallback(Request $request, DgePayService $dgePayService): RedirectResponse
    {
        Log::info('DGePay callback received', $request->all());

        $encryptedData = $request->query('data');
        $uniqueTxnId = null;

        if ($encryptedData) {
            $decrypted = $dgePayService->decryptCallbackData($encryptedData);
            if ($decrypted && ! empty($decrypted['unique_txn_id'])) {
                $uniqueTxnId = $decrypted['unique_txn_id'];
            }
        }

        if (! $uniqueTxnId) {
            $uniqueTxnId = $request->query('unique_txn_id') ?? $request->query('orderId');
        }

        if (! $uniqueTxnId) {
            return redirect()->route('checkout')->with('error', 'Invalid payment response returned from gateway.');
        }

        /** @var Order|null $order */
        $order = Order::where('dgepay_unique_txn_id', $uniqueTxnId)->first();
        if (! $order) {
            return redirect()->route('checkout')->with('error', 'Order not found for the given transaction.');
        }

        // Always perform authoritative server-to-server transaction status check
        $statusCheck = $dgePayService->getTransactionStatus($uniqueTxnId);

        if ($statusCheck['success'] && ! empty($statusCheck['data'])) {
            $data = $statusCheck['data'];
            $statusCode = (string) ($data['status_code'] ?? '');

            // Status 3 is Success in DGePay API
            if ($statusCode === '3' || ($data['status'] ?? '') === 'COMPLETED') {
                $order->payment_status = 'paid';
                $order->order_status = 'confirmed';
                $order->paid_at = now();
                $order->dgepay_txn_number = $data['txn_number'] ?? ($data['txn_id'] ?? null);
                $order->dgepay_third_party_txn_number = $data['third_party_txn_number'] ?? null;
                $order->dgepay_channel = $data['payment_method'] ?? 'Online';
                $order->save();

                $this->finalizeConfirmedOrder($order);

                // Clear cart & session
                session()->forget(['cart_items', 'applied_coupon', 'shipping_destination']);

                return redirect()->route('checkout.success', $order->id)->with('success', 'Payment completed successfully! Your order is confirmed.');
            }

            // Status 8 is Cancelled
            if ($statusCode === '8') {
                $order->payment_status = 'cancelled';
                $order->order_status = 'cancelled';
                $order->save();

                return redirect()->route('checkout')->with('error', 'Payment was cancelled. You can try again.');
            }
        }

        // If not verified or failed
        $order->payment_status = 'failed';
        $order->save();

        return redirect()->route('checkout')->with('error', 'Payment verification was not successful. Please contact support or try again.');
    }

    /**
     * Show order confirmation success page.
     */
    public function success(Order $order): View
    {
        $order->load('items');

        return view('checkout_success', compact('order'));
    }

    /**
     * Finalize confirmed order:
     * 1. Create Admin Notification
     * 2. Send Invoice Email to Customer (with dynamic SMTP configuration)
     */
    protected function finalizeConfirmedOrder(Order $order): void
    {
        try {
            // 1. Create Admin Notification in database
            AdminNotification::create([
                'type' => 'order',
                'title' => 'New Order Received #'.$order->order_number,
                'message' => "Customer {$order->customer_name} placed an order of ৳".number_format($order->total, 2).' via '.strtoupper($order->payment_method),
                'link' => route('admin.orders.show', $order->id),
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'amount' => $order->total,
                    'payment_method' => $order->payment_method,
                    'customer_email' => $order->customer_email,
                ],
                'is_read' => false,
            ]);

            // Create User Notification for customer
            if ($order->user_id) {
                UserNotification::create([
                    'user_id' => $order->user_id,
                    'type' => 'order',
                    'title' => 'Order Placed Successfully #'.$order->order_number,
                    'message' => "Your order #{$order->order_number} for ৳".number_format($order->total, 2).' has been confirmed.',
                    'link' => route('user.orders'),
                    'data' => [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'total' => $order->total,
                    ],
                    'is_read' => false,
                ]);
            }
        } catch (Exception $e) {
            Log::error('Failed to create Order Notification: '.$e->getMessage());
        }

        try {
            // 2. Send Invoice Email to Customer
            app(MailConfigService::class)->apply();
            Mail::to($order->customer_email)->send(new OrderInvoiceMail($order));
        } catch (Exception $e) {
            Log::error('Failed to send OrderInvoiceMail to '.$order->customer_email.': '.$e->getMessage());
        }

        try {
            // 3. If order is confirmed/paid and contains digital/subscription products, provision initial pending UserSubscription
            if (($order->payment_status === 'paid' || $order->order_status === 'confirmed') && $order->user_id) {
                $order->loadMissing('items.product', 'items.plan');
                $hasDigitalSubscriptions = false;
                $digitalItemNames = [];

                foreach ($order->items as $item) {
                    $isDigital = ($item->product_type === 'digital') || ($item->product && $item->product->isDigital());
                    if ($isDigital) {
                        $hasDigitalSubscriptions = true;
                        $digitalItemNames[] = $item->title;

                        for ($q = 0; $q < $item->quantity; $q++) {
                            // Check if a subscription already exists for this order item instance
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

                // Notify admin about the pending digital subscription that needs activation/credentials
                if ($hasDigitalSubscriptions) {
                    $itemsSummary = implode(', ', array_unique($digitalItemNames));
                    AdminNotification::create([
                        'type' => 'subscription',
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
                        'is_read' => false,
                    ]);
                }
            }
        } catch (Exception $e) {
            Log::error('Failed to create UserSubscription record for Order #'.$order->order_number.': '.$e->getMessage());
        }

        try {
            // Deduct stock for physical items purchased in this confirmed order
            $order->loadMissing('items.product', 'items.plan');
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->deductStock((int) $item->quantity, $item->plan);
                }
            }
        } catch (Exception $e) {
            Log::error('Failed to deduct stock for confirmed Order #'.$order->order_number.': '.$e->getMessage());
        }

        try {
            // Process Affiliate Commission if conditions are satisfied
            app(AffiliateService::class)->processOrderCommission($order);
        } catch (Exception $e) {
            Log::error('Failed to process affiliate commission on finalized order: '.$e->getMessage());
        }

        try {
            // Record coupon usage and increment used_count if a coupon was used
            if (! empty($order->coupon_code)) {
                $coupon = Coupon::where('code', $order->coupon_code)->first();
                if ($coupon) {
                    $coupon->increment('used_count');

                    CouponUsage::create([
                        'coupon_id' => $coupon->id,
                        'user_id' => $order->user_id,
                        'user_email' => $order->customer_email,
                        'discount_amount' => $order->discount,
                    ]);
                }
            }
        } catch (Exception $e) {
            Log::error('Failed to record coupon usage for Order #'.$order->order_number.': '.$e->getMessage());
        }
    }
}
