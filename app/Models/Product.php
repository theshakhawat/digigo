<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'product_type',
        'category_id',
        'brand_id',
        'thumbnail',
        'gallery',
        'short_description',
        'description',
        'features',
        'price',
        'sale_price',
        'cost_price',
        'status',
        'is_featured',
        'rating',
        'reviews_count',
        'sku',
        'stock_quantity',
        'low_stock_threshold',
        'shipping_charge',
        'weight',
        'dimensions',
        'color',
        'warranty_period',
        'package_contents',
        'digital_delivery_type',
        'access_instructions',
        'download_link',
        'download_file',
        'default_validity',
        'has_plans',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'features' => 'array',
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'rating' => 'decimal:2',
            'reviews_count' => 'integer',
            'stock_quantity' => 'integer',
            'low_stock_threshold' => 'integer',
            'shipping_charge' => 'decimal:2',
            'is_featured' => 'boolean',
            'has_plans' => 'boolean',
        ];
    }

    /**
     * Category relationship.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Brand relationship.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Plans / Variations relationship.
     */
    public function plans(): HasMany
    {
        return $this->hasMany(ProductPlan::class)->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Reviews relationship.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', 'approved')->latest();
    }

    /**
     * Recalculate average rating and reviews count.
     */
    public function recalculateRating(): void
    {
        $approvedReviews = $this->reviews()->get();
        $count = $approvedReviews->count();
        if ($count > 0) {
            $avg = $approvedReviews->avg('rating');
            $this->rating = round($avg, 2);
            $this->reviews_count = $count;
        } else {
            $this->rating = 5.00;
            $this->reviews_count = 0;
        }
        $this->saveQuietly();
    }

    /**
     * Determine if a given user is eligible to review this product.
     *
     * Rules:
     * - Physical product: User must have an order with this product where order_status is 'completed' or 'confirmed' (or 'delivered').
     * - Digital product: User must be logged in and have an order with this product where payment_status is 'paid'.
     */
    public function canUserReview(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        $query = Order::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhere('customer_email', $user->email);
        })->whereHas('items', function ($itemQ) {
            $itemQ->where('product_id', $this->id);
        });

        if ($this->isPhysical()) {
            // For physical product: must be completed or confirmed/delivered
            return $query->whereIn('order_status', ['completed', 'delivered', 'confirmed'])->exists();
        }

        // For digital product: payment must be successful ('paid')
        return $query->where('payment_status', 'paid')->exists();
    }

    /**
     * Check if product is physical.
     */
    public function isPhysical(): bool
    {
        return $this->product_type === 'physical';
    }

    /**
     * Check if product is digital.
     */
    public function isDigital(): bool
    {
        return $this->product_type === 'digital';
    }

    /**
     * Get public thumbnail URL.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->thumbnail && str_starts_with($this->thumbnail, 'http')) {
            return $this->thumbnail;
        }

        if ($this->thumbnail && Storage::disk('public')->exists($this->thumbnail)) {
            return Storage::disk('public')->url($this->thumbnail);
        }

        return null;
    }

    /**
     * Get public gallery image URLs.
     *
     * @return list<string>
     */
    public function getGalleryUrlsAttribute(): array
    {
        if (! is_array($this->gallery)) {
            return [];
        }

        $urls = [];
        foreach ($this->gallery as $path) {
            if (str_starts_with($path, 'http')) {
                $urls[] = $path;
            } elseif (Storage::disk('public')->exists($path)) {
                $urls[] = Storage::disk('public')->url($path);
            }
        }

        return $urls;
    }

    /**
     * Get effective price (sale price if active, otherwise regular price).
     */
    public function getEffectivePriceAttribute(): float
    {
        if ($this->sale_price !== null && $this->sale_price > 0 && $this->sale_price < $this->price) {
            return (float) $this->sale_price;
        }

        return (float) $this->price;
    }

    /**
     * Get discount percentage if on sale.
     */
    public function getDiscountPercentageAttribute(): ?int
    {
        if ($this->sale_price !== null && $this->sale_price > 0 && $this->sale_price < $this->price) {
            return (int) round((($this->price - $this->sale_price) / $this->price) * 100);
        }

        return null;
    }

    /**
     * Determine if product has stock.
     */
    public function getIsInStockAttribute(): bool
    {
        if ($this->isDigital()) {
            return true;
        }

        return (int) $this->stock_quantity > 0;
    }

    /**
     * Human-friendly stock badge description.
     */
    public function getStockStatusLabelAttribute(): string
    {
        if ($this->isDigital()) {
            return 'In Stock (Instant Delivery)';
        }

        if ((int) $this->stock_quantity <= 0) {
            return 'Out of Stock';
        }

        if ((int) $this->stock_quantity <= (int) $this->low_stock_threshold) {
            return "Low Stock ({$this->stock_quantity} left)";
        }

        return "In Stock ({$this->stock_quantity} available)";
    }

    /**
     * Decrement stock quantity safely for product or plan and trigger low stock notification to admin.
     */
    public function deductStock(int $quantity = 1, ?ProductPlan $plan = null): void
    {
        if ($plan && $plan->stock_quantity > 0) {
            $plan->decrement('stock_quantity', min($quantity, $plan->stock_quantity));
        }

        if ($this->stock_quantity !== null) {
            $deducted = min($quantity, max(0, (int) $this->stock_quantity));
            $this->decrement('stock_quantity', $deducted);
            $this->refresh();

            // Low stock check: Alert admin if current stock hits or drops below low_stock_threshold (or default 5)
            $threshold = $this->low_stock_threshold ?? 5;
            if ($this->stock_quantity <= $threshold) {
                $this->notifyAdminLowStock();
            }
        }
    }

    /**
     * Notify Admin about low stock or out of stock condition.
     */
    public function notifyAdminLowStock(): void
    {
        try {
            $isOutOfStock = $this->stock_quantity <= 0;
            $title = $isOutOfStock
                ? "Out of Stock Alert: {$this->name}"
                : "Low Stock Alert: {$this->name}";

            $message = $isOutOfStock
                ? "Product '{$this->name}' is now OUT OF STOCK (0 units remaining). Please restock."
                : "Product '{$this->name}' is running low on stock ({$this->stock_quantity} units remaining, threshold: {$this->low_stock_threshold}).";

            // Avoid duplicating identical unread low stock notification for the same product
            $existing = AdminNotification::where('type', 'product')
                ->where('is_read', false)
                ->whereJsonContains('data->product_id', $this->id)
                ->first();

            if (! $existing) {
                AdminNotification::create([
                    'type' => 'product',
                    'title' => $title,
                    'message' => $message,
                    'link' => route('admin.products.edit', $this->id),
                    'data' => [
                        'product_id' => $this->id,
                        'product_name' => $this->name,
                        'stock_quantity' => $this->stock_quantity,
                        'low_stock_threshold' => $this->low_stock_threshold,
                    ],
                    'is_read' => false,
                ]);
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * Scope query to active products.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope query to featured products.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('status', 'active')->where('is_featured', true);
    }

    /**
     * Scope query to physical products.
     */
    public function scopePhysical(Builder $query): Builder
    {
        return $query->where('product_type', 'physical');
    }

    /**
     * Scope query to digital products.
     */
    public function scopeDigital(Builder $query): Builder
    {
        return $query->where('product_type', 'digital');
    }

    /**
     * Relationship with order items for sales tracking.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
