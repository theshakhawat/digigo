<?php

namespace App\Models;

use Database\Factories\BrandFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Brand extends Model
{
    /** @use HasFactory<BrandFactory> */
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
    ];

    /**
     * Get the resolved public URL of the brand logo/image.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        if (Storage::disk('public')->exists($this->image)) {
            return Storage::disk('public')->url($this->image);
        }

        return asset('storage/'.ltrim($this->image, '/'));
    }

    /**
     * Alias for getImageUrlAttribute for views referencing logo_url.
     */
    public function getLogoUrlAttribute(): ?string
    {
        return $this->image_url;
    }

    /**
     * Backward-compatible alias for views referencing $brand->logo.
     */
    public function getLogoAttribute(): ?string
    {
        return $this->image_url;
    }

    /**
     * Scope a query to only include active brands.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get products for this brand.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
