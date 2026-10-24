<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class UserSubscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'subscription_code',
        'user_id',
        'order_id',
        'product_id',
        'plan_id',
        'product_name',
        'plan_name',
        'validity',
        'price',
        'target_email',
        'target_phone',
        'credentials',
        'access_instructions',
        'license_key',
        'login_url',
        'status',
        'starts_at',
        'expires_at',
        'activated_at',
        'admin_note',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'activated_at' => 'datetime',
        ];
    }

    /**
     * User relationship.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Order relationship.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Product relationship.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Product Plan relationship.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(ProductPlan::class, 'plan_id');
    }

    /**
     * Determine if the subscription is currently expired based on date or status.
     */
    public function isExpired(): bool
    {
        if ($this->status === 'expired') {
            return true;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return true;
        }

        return false;
    }

    /**
     * Get computed/live status of the subscription.
     * Automatically returns 'expired' if expires_at is past, even before cron run.
     */
    public function getEffectiveStatusAttribute(): string
    {
        if ($this->status === 'cancelled') {
            return 'cancelled';
        }

        if ($this->isExpired()) {
            return 'expired';
        }

        return $this->status;
    }

    /**
     * Check and sync status if past expiration.
     */
    public function syncExpiredStatus(): bool
    {
        if ($this->status === 'active' && $this->expires_at && $this->expires_at->isPast()) {
            $this->status = 'expired';
            $this->saveQuietly();

            return true;
        }

        return false;
    }

    /**
     * Generate unique subscription code.
     */
    public static function generateCode(): string
    {
        return 'SUB-'.strtoupper(Str::random(8));
    }
}
