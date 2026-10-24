<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'digital_target_email',
        'digital_target_phone',
        'digital_account_notes',
        'division',
        'district',
        'street_address',
        'postal_code',
        'notes',
        'subtotal',
        'discount',
        'coupon_code',
        'delivery_charge',
        'shipping_charge',
        'total',
        'payment_method',
        'payment_status',
        'order_status',
        'dgepay_unique_txn_id',
        'dgepay_txn_number',
        'dgepay_third_party_txn_number',
        'dgepay_channel',
        'paid_at',
        'affiliate_user_id',
        'affiliate_commission',
        'affiliate_commission_paid',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'delivery_charge' => 'decimal:2',
        'total' => 'decimal:2',
        'affiliate_commission' => 'decimal:2',
        'affiliate_commission_paid' => 'boolean',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the affiliate referrer user.
     */
    public function affiliateUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'affiliate_user_id');
    }

    /**
     * Get the items for the order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Generate next order number.
     */
    public static function generateOrderNumber(): string
    {
        $prefix = 'ORD-';
        $timestamp = date('ymd');
        $random = strtoupper(substr(uniqid(), -4));

        return $prefix.$timestamp.'-'.$random;
    }
}
