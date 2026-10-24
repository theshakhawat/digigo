<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable(['name', 'email', 'phone', 'photo', 'role', 'status', 'password', 'affiliate_code', 'affiliate_balance', 'total_affiliate_earned', 'total_affiliate_withdrawn'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get or generate affiliate code for user.
     */
    public function getAffiliateCode(): string
    {
        if (! $this->affiliate_code) {
            $code = 'DG'.strtoupper(Str::random(6));
            while (self::where('affiliate_code', $code)->exists()) {
                $code = 'DG'.strtoupper(Str::random(6));
            }
            $this->affiliate_code = $code;
            $this->saveQuietly();
        }

        return $this->affiliate_code;
    }

    /**
     * Get user affiliate referrals/earnings.
     */
    public function affiliateEarnings(): HasMany
    {
        return $this->hasMany(AffiliateEarning::class);
    }

    /**
     * Get user withdrawal requests.
     */
    public function withdrawalRequests(): HasMany
    {
        return $this->hasMany(WithdrawalRequest::class);
    }

    /**
     * Check if the user has an admin role.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user has a regular user role.
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Check if the user is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get the resolved public URL of the avatar / profile photo.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            return Storage::disk('public')->url($this->photo);
        }

        $bg = $this->role === 'admin' ? '1e40af' : '3b82f6';

        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background='.$bg.'&color=fff&bold=true&size=128';
    }

    /**
     * Scope a query by role.
     *
     * @param  Builder  $query
     * @param  string  $role
     * @return Builder
     */
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope a query to only include active users.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get the single primary address of the user.
     */
    public function address(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
