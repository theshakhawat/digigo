<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    /**
     * Get a site setting by key.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('setting_url')) {
    /**
     * Get a public URL for a setting image.
     */
    function setting_url(string $key, ?string $default = null): ?string
    {
        return Setting::url($key, $default);
    }
}

if (! function_exists('trust_features')) {
    /**
     * Retrieve the list of active trust feature items for the marquee ticker.
     *
     * @return array<int, array{icon: string, text: string, link: ?string}>
     */
    function trust_features(): array
    {
        $raw = setting('trust_features_items');
        if (! empty($raw)) {
            $decoded = json_decode((string) $raw, true);
            if (is_array($decoded) && count($decoded) > 0) {
                return array_values(array_filter($decoded, function ($item) {
                    return is_array($item) && ! empty(trim($item['text'] ?? ''));
                }));
            }
        }

        return [
            [
                'icon' => 'fa-solid fa-shield-halved',
                'text' => 'Authentic & Genuine Product.',
                'link' => '',
            ],
            [
                'icon' => 'fa-solid fa-headset',
                'text' => '24/7 Support Always Be There for You',
                'link' => '',
            ],
            [
                'icon' => 'fa-solid fa-tags',
                'text' => 'Low Prices Than in Other Stores',
                'link' => '',
            ],
            [
                'icon' => 'fa-solid fa-credit-card',
                'text' => 'Safe Payment With Multiple Method',
                'link' => '',
            ],
        ];
    }
}
