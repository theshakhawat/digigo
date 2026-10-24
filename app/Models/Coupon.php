<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type',
        'discount_value',
        'min_spend',
        'max_discount_amount',
        'starts_at',
        'expires_at',
        'usage_limit_total',
        'usage_limit_per_user',
        'used_count',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'min_spend' => 'decimal:2',
            'max_discount_amount' => 'decimal:2',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'usage_limit_total' => 'integer',
            'usage_limit_per_user' => 'integer',
            'used_count' => 'integer',
        ];
    }

    /**
     * Scope for active coupons.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for currently valid coupons (by date range).
     */
    public function scopeCurrentlyValid(Builder $query): Builder
    {
        $now = now();

        return $query->where('status', 'active')
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
            });
    }

    /**
     * Check if coupon is currently expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    /**
     * Check if coupon has started.
     */
    public function hasStarted(): bool
    {
        return $this->starts_at === null || $this->starts_at->isPast();
    }

    /**
     * Check if total usage limit reached.
     */
    public function hasReachedTotalLimit(): bool
    {
        return $this->usage_limit_total !== null && $this->used_count >= $this->usage_limit_total;
    }

    /**
     * Products restricted to this coupon.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'coupon_product');
    }

    /**
     * Categories restricted to this coupon.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_coupon');
    }

    /**
     * Usages history of this coupon.
     */
    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    /**
     * Check if a specific user can use this coupon.
     */
    public function canBeUsedByUser(?User $user, ?string $email = null): array
    {
        if ($this->status !== 'active') {
            return ['allowed' => false, 'message' => 'This coupon is not active.'];
        }

        if (! $this->hasStarted()) {
            return ['allowed' => false, 'message' => 'This coupon is not yet valid.'];
        }

        if ($this->isExpired()) {
            return ['allowed' => false, 'message' => 'This coupon has expired.'];
        }

        if ($this->hasReachedTotalLimit()) {
            return ['allowed' => false, 'message' => 'This coupon usage limit has been reached.'];
        }

        if ($this->usage_limit_per_user !== null) {
            $userUsageQuery = $this->usages();
            if ($user) {
                $userUsageQuery->where(function ($q) use ($user, $email) {
                    $q->where('user_id', $user->id);
                    if ($email) {
                        $q->orWhere('user_email', $email);
                    }
                });
            } elseif ($email) {
                $userUsageQuery->where('user_email', $email);
            }

            if ($user || $email) {
                $timesUsed = $userUsageQuery->count();
                if ($timesUsed >= $this->usage_limit_per_user) {
                    return ['allowed' => false, 'message' => 'You have already used this coupon the maximum allowed number of times.'];
                }
            }
        }

        return ['allowed' => true, 'message' => 'Coupon is valid.'];
    }

    /**
     * Calculate discount for given cart items and subtotal.
     * Items format: array of items, each having 'price', 'quantity', 'product_id' (or 'product' => Product model), 'category_id'.
     *
     * @param  array<int, array<string, mixed>>  $cartItems
     * @return array{valid: bool, discount: float, message: string, applicable_subtotal: float}
     */
    public function calculateDiscount(array $cartItems, ?User $user = null, ?string $email = null): array
    {
        $userCheck = $this->canBeUsedByUser($user, $email);
        if (! $userCheck['allowed']) {
            return [
                'valid' => false,
                'discount' => 0.0,
                'message' => $userCheck['message'],
                'applicable_subtotal' => 0.0,
            ];
        }

        $restrictedProductIds = $this->products()->pluck('products.id')->all();
        $restrictedCategoryIds = $this->categories()->pluck('categories.id')->all();

        $hasProductRestriction = count($restrictedProductIds) > 0;
        $hasCategoryRestriction = count($restrictedCategoryIds) > 0;

        $applicableSubtotal = 0.0;
        $totalSubtotal = 0.0;

        foreach ($cartItems as $item) {
            $itemPrice = (float) ($item['price'] ?? 0);
            $qty = (int) ($item['quantity'] ?? 1);
            $lineTotal = $itemPrice * $qty;
            $totalSubtotal += $lineTotal;

            $productId = $item['product_id'] ?? ($item['id'] ?? null);
            $categoryId = $item['category_id'] ?? null;

            $isProductMatch = ! $hasProductRestriction || in_array($productId, $restrictedProductIds);
            $isCategoryMatch = ! $hasCategoryRestriction || in_array($categoryId, $restrictedCategoryIds);

            // If coupon is restricted to both, or either
            if ($hasProductRestriction && $hasCategoryRestriction) {
                if ($isProductMatch || $isCategoryMatch) {
                    $applicableSubtotal += $lineTotal;
                }
            } elseif ($hasProductRestriction) {
                if ($isProductMatch) {
                    $applicableSubtotal += $lineTotal;
                }
            } elseif ($hasCategoryRestriction) {
                if ($isCategoryMatch) {
                    $applicableSubtotal += $lineTotal;
                }
            } else {
                $applicableSubtotal += $lineTotal;
            }
        }

        if ($this->min_spend !== null && $totalSubtotal < (float) $this->min_spend) {
            return [
                'valid' => false,
                'discount' => 0.0,
                'message' => 'Minimum spend of ৳'.number_format((float) $this->min_spend, 2).' is required for this coupon.',
                'applicable_subtotal' => $applicableSubtotal,
            ];
        }

        if ($applicableSubtotal <= 0) {
            return [
                'valid' => false,
                'discount' => 0.0,
                'message' => 'This coupon is not applicable to the items in your cart.',
                'applicable_subtotal' => 0.0,
            ];
        }

        $discount = 0.0;
        if ($this->discount_type === 'percentage') {
            $discount = ($applicableSubtotal * (float) $this->discount_value) / 100;
            if ($this->max_discount_amount !== null && $discount > (float) $this->max_discount_amount) {
                $discount = (float) $this->max_discount_amount;
            }
        } else {
            // Fixed discount
            $discount = (float) $this->discount_value;
        }

        $discount = round($discount, 2);

        return [
            'valid' => true,
            'discount' => $discount,
            'message' => 'Coupon applied successfully!',
            'applicable_subtotal' => $applicableSubtotal,
        ];
    }
}
