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

if (! function_exists('default_site_logo')) {
    /**
     * Get the default site logo URL.
     */
    function default_site_logo(): string
    {
        return setting_url('site_logo', asset('assets/images/digigo logo.png'));
    }
}

if (! function_exists('default_site_favicon')) {
    /**
     * Get the default site favicon URL.
     */
    function default_site_favicon(): string
    {
        return setting_url('site_favicon', asset('assets/images/digigo_vartical_logo.png'));
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

if (! function_exists('tri_promo_cards')) {
    /**
     * Retrieve the 3 feature highlight cards for the homepage tri-promo section.
     *
     * @return array<int, array{
     *     theme: string,
     *     title: string,
     *     desc: string,
     *     button_text: string,
     *     button_link: string,
     *     image_url: string
     * }>
     */
    function tri_promo_cards(): array
    {
        $defaults = [
            1 => [
                'theme' => 'light',
                'title' => 'Next level adventure',
                'desc' => 'Elevate your adventure through the perfect fusion of military-grade toughness and striking silver aesthetics.',
                'button_text' => 'Shop Now',
                'button_link' => '#smartwatch-deals',
                'default_img' => 'assets/images/promos/promo_smartwatch.png',
            ],
            2 => [
                'theme' => 'dark',
                'title' => 'Dare To Leap',
                'desc' => 'Experience the ultimate blend of striking aesthetics and deep audio immersion with its premium dual-tone design.',
                'button_text' => 'Shop Now',
                'button_link' => '#earbuds-deals',
                'default_img' => 'assets/images/promos/promo_earbuds.png',
            ],
            3 => [
                'theme' => 'light',
                'title' => 'Stream Like A Pro',
                'desc' => 'Level up your stream with real-time game/chat mixing, dual-mic noise cancellation, and dynamic RGB.',
                'button_text' => 'Shop Now',
                'button_link' => '#streaming-deals',
                'default_img' => 'assets/images/promos/promo_mic.png',
            ],
        ];

        $cards = [];
        for ($i = 1; $i <= 3; $i++) {
            $def = $defaults[$i];
            $customImg = setting_url("tri_promo_{$i}_img");
            $imageUrl = $customImg ?: asset($def['default_img']);

            $cards[$i] = [
                'theme' => (string) setting("tri_promo_{$i}_theme", $def['theme']),
                'title' => (string) setting("tri_promo_{$i}_title", $def['title']),
                'desc' => (string) setting("tri_promo_{$i}_desc", $def['desc']),
                'button_text' => (string) setting("tri_promo_{$i}_button_text", $def['button_text']),
                'button_link' => (string) setting("tri_promo_{$i}_button_link", $def['button_link']),
                'image_url' => $imageUrl,
                'has_custom_img' => ! empty(setting("tri_promo_{$i}_img")),
            ];
        }

        return $cards;
    }
}

if (! function_exists('dual_promo_banners')) {
    /**
     * Retrieve the 2 announcement & coupon discount banners for the homepage dual-promo section.
     *
     * @return array<int, array{
     *     title: string,
     *     offer_label: string,
     *     coupon_code: string,
     *     coupon_bg_color: string,
     *     desc: string,
     *     disclaimer: string,
     *     link: string,
     *     bg_gradient_start: string,
     *     bg_gradient_end: string,
     *     image_url: string,
     *     has_custom_img: bool
     * }>
     */
    function dual_promo_banners(): array
    {
        $defaults = [
            1 => [
                'title' => "Heavy-Duty Dual\nDevice Mounting",
                'offer_label' => 'Get 10% OFF; Promo code:',
                'coupon_code' => 'ULNJI2026M',
                'coupon_bg_color' => '#e53935',
                'desc' => '',
                'disclaimer' => '*Not combined with promotional offers and discounts',
                'link' => '#mounting-offers',
                'bg_gradient_start' => '#1976d2',
                'bg_gradient_end' => '#29b6f6',
                'default_img' => 'assets/images/promos/visual_mounting.png',
            ],
            2 => [
                'title' => "Stay Cool In\nStyle",
                'offer_label' => '',
                'coupon_code' => '',
                'coupon_bg_color' => '#e53935',
                'desc' => 'Stay effortlessly cool with an ultra-compact 40,000 RPM turbo airflow.',
                'disclaimer' => '',
                'link' => '#fan-offers',
                'bg_gradient_start' => '#ad1457',
                'bg_gradient_end' => '#ec407a',
                'default_img' => 'assets/images/promos/visual_fancool.png',
            ],
        ];

        $banners = [];
        for ($i = 1; $i <= 2; $i++) {
            $def = $defaults[$i];
            $customImg = setting_url("dual_promo_{$i}_img");
            $imageUrl = $customImg ?: asset($def['default_img']);

            $banners[$i] = [
                'title' => (string) setting("dual_promo_{$i}_title", $def['title']),
                'offer_label' => (string) setting("dual_promo_{$i}_offer_label", $def['offer_label']),
                'coupon_code' => (string) setting("dual_promo_{$i}_coupon_code", $def['coupon_code']),
                'coupon_bg_color' => (string) setting("dual_promo_{$i}_coupon_bg_color", $def['coupon_bg_color']),
                'desc' => (string) setting("dual_promo_{$i}_desc", $def['desc']),
                'disclaimer' => (string) setting("dual_promo_{$i}_disclaimer", $def['disclaimer']),
                'link' => (string) setting("dual_promo_{$i}_link", $def['link']),
                'bg_gradient_start' => (string) setting("dual_promo_{$i}_bg_start", $def['bg_gradient_start']),
                'bg_gradient_end' => (string) setting("dual_promo_{$i}_bg_end", $def['bg_gradient_end']),
                'image_url' => $imageUrl,
                'has_custom_img' => ! empty(setting("dual_promo_{$i}_img")),
            ];
        }

        return $banners;
    }
}
