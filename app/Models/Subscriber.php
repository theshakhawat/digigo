<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'email',
        'status',
    ];

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Subscriber $subscriber) {
            if (empty($subscriber->uuid)) {
                $subscriber->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Scope active subscribers.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
