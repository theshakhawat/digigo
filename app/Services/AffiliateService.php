<?php

namespace App\Services;

use App\Models\AffiliateEarning;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AffiliateService
{
    /**
     * Resolve an affiliate user from a code.
     */
    public function resolveUserByCode(?string $code): ?User
    {
        if (! $code) {
            return null;
        }

        return User::where('affiliate_code', trim($code))->where('status', 'active')->first();
    }

    /**
     * Calculate potential commission for an order item based on settings and category hierarchy.
     */
    public function calculateItemCommission(OrderItem|array $item): float
    {
        $enabled = Setting::get('affiliate_enabled', '1');
        if ($enabled !== '1') {
            return 0.0;
        }

        $productType = is_array($item) ? ($item['product_type'] ?? 'digital') : ($item->product_type ?? 'digital');
        $itemTotal = (float) (is_array($item) ? (($item['price'] ?? 0) * ($item['quantity'] ?? 1)) : $item->subtotal);

        if ($itemTotal <= 0) {
            return 0.0;
        }

        // Try to find the product's category
        $productId = is_array($item) ? ($item['product_id'] ?? null) : $item->product_id;
        $category = null;
        if ($productId) {
            $product = Product::with('category')->find($productId);
            $category = $product?->category;
        }

        $rate = null;
        $type = 'percentage';

        if ($category) {
            if ($productType === 'physical') {
                $rate = $category->affiliate_commission_physical;
                $type = $category->affiliate_commission_type_physical ?? 'percentage';
            } else {
                $rate = $category->affiliate_commission_digital;
                $type = $category->affiliate_commission_type_digital ?? 'percentage';
            }
        }

        // Fallback to global defaults if category-specific rate is not defined
        if ($rate === null || $rate === '') {
            if ($productType === 'physical') {
                $rate = Setting::get('affiliate_default_physical', '5');
                $type = Setting::get('affiliate_default_physical_type', 'percentage');
            } else {
                $rate = Setting::get('affiliate_default_digital', '10');
                $type = Setting::get('affiliate_default_digital_type', 'percentage');
            }
        }

        $rate = (float) $rate;
        if ($rate <= 0) {
            return 0.0;
        }

        if ($type === 'fixed') {
            $qty = is_array($item) ? ($item['quantity'] ?? 1) : ($item->quantity ?? 1);

            return round($rate * $qty, 2);
        }

        // Percentage
        return round(($itemTotal * $rate) / 100, 2);
    }

    /**
     * Calculate total commission for an entire order.
     */
    public function calculateOrderCommission(Order $order): float
    {
        $order->loadMissing('items');
        $totalCommission = 0.0;

        foreach ($order->items as $item) {
            $totalCommission += $this->calculateItemCommission($item);
        }

        return round($totalCommission, 2);
    }

    /**
     * Credit commission to affiliate user if order is confirmed/delivered or paid online.
     */
    public function processOrderCommission(Order $order): bool
    {
        if (! $order->affiliate_user_id || $order->affiliate_commission_paid) {
            return false;
        }

        // Criteria:
        // Online (DGePay) & payment_status === 'paid'
        // OR COD delivered/completed (or payment_status === 'paid' or order_status in ['completed', 'confirmed'] when COD is verified)
        $shouldCredit = false;

        if ($order->payment_method === 'dgepay' && $order->payment_status === 'paid') {
            $shouldCredit = true;
        } elseif ($order->payment_method === 'cod') {
            if ($order->payment_status === 'paid' || $order->order_status === 'completed') {
                $shouldCredit = true;
            }
        }

        if (! $shouldCredit) {
            return false;
        }

        $commissionAmount = (float) $order->affiliate_commission;
        if ($commissionAmount <= 0) {
            $commissionAmount = $this->calculateOrderCommission($order);
            $order->affiliate_commission = $commissionAmount;
        }

        if ($commissionAmount <= 0) {
            return false;
        }

        return DB::transaction(function () use ($order, $commissionAmount) {
            /** @var User|null $affiliateUser */
            $affiliateUser = User::lockForUpdate()->find($order->affiliate_user_id);
            if (! $affiliateUser) {
                return false;
            }

            // Mark commission paid on order
            $order->affiliate_commission = $commissionAmount;
            $order->affiliate_commission_paid = true;
            $order->saveQuietly();

            // Record or update earning
            AffiliateEarning::updateOrCreate(
                [
                    'user_id' => $affiliateUser->id,
                    'order_id' => $order->id,
                ],
                [
                    'amount' => $commissionAmount,
                    'status' => 'credited',
                    'credited_at' => now(),
                ]
            );

            // Increment user affiliate balance
            $affiliateUser->increment('affiliate_balance', $commissionAmount);
            $affiliateUser->increment('total_affiliate_earned', $commissionAmount);

            Log::info("Affiliate commission of ৳{$commissionAmount} credited to User #{$affiliateUser->id} for Order #{$order->order_number}");

            return true;
        });
    }
}
