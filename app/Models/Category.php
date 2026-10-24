<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'image',
        'status',
        'is_featured',
        'delivery_charge_inside_dhaka',
        'delivery_charge_outside_dhaka',
        'affiliate_commission_physical',
        'affiliate_commission_type_physical',
        'affiliate_commission_digital',
        'affiliate_commission_type_digital',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_featured' => 'boolean',
        'delivery_charge_inside_dhaka' => 'decimal:2',
        'delivery_charge_outside_dhaka' => 'decimal:2',
        'affiliate_commission_physical' => 'decimal:2',
        'affiliate_commission_digital' => 'decimal:2',
    ];

    /**
     * Get the resolved public URL of the category image.
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image && str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return Storage::disk('public')->url($this->image);
        }

        return null;
    }

    /**
     * Scope a query to only include active categories.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include active featured categories.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeFeatured($query)
    {
        return $query->where('status', 'active')->where('is_featured', true);
    }

    /**
     * Get products in this category.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
