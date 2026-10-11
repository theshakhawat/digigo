<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    use HasFactory;

    /**
     * Cache key for all site settings.
     */
    public const CACHE_KEY = 'digigo_site_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
    ];

    /**
     * Retrieve a setting value by its key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = static::allCached();

        if (array_key_exists($key, $settings)) {
            return $settings[$key] !== null && $settings[$key] !== '' ? $settings[$key] : $default;
        }

        return $default;
    }

    /**
     * Retrieve all settings as key => value array from cache.
     *
     * @return array<string, mixed>
     */
    public static function allCached(): array
    {
        try {
            return Cache::rememberForever(self::CACHE_KEY, function () {
                return static::query()->pluck('value', 'key')->toArray();
            });
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * Set / update a setting value.
     */
    public static function set(string $key, mixed $value, string $group = 'general', string $type = 'text'): self
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'group' => $group,
                'value' => $value,
                'type' => $type,
            ]
        );

        static::clearCache();

        return $setting;
    }

    /**
     * Get publicly accessible storage URL for an image setting.
     */
    public static function url(string $key, ?string $default = null): ?string
    {
        $path = static::get($key);

        if (! empty($path) && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        return $default;
    }

    /**
     * Flush settings cache.
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
