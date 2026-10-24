<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'rating',
        'comment',
        'images',
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
            'rating' => 'integer',
            'images' => 'array',
        ];
    }

    /**
     * User who wrote this review.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Product being reviewed.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Associated order if any.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get accessible URLs for review images.
     *
     * @return list<string>
     */
    public function getImageUrlsAttribute(): array
    {
        if (! is_array($this->images)) {
            return [];
        }

        $urls = [];
        foreach ($this->images as $path) {
            if (str_starts_with($path, 'http')) {
                $urls[] = $path;
            } elseif (Storage::disk('public')->exists($path)) {
                $urls[] = Storage::disk('public')->url($path);
            }
        }

        return $urls;
    }
}
