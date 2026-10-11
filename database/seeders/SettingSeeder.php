<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            // Header & Branding
            ['group' => 'header', 'key' => 'site_name', 'value' => 'DigiGo', 'type' => 'text'],
            ['group' => 'header', 'key' => 'site_logo', 'value' => null, 'type' => 'image'],
            ['group' => 'header', 'key' => 'site_favicon', 'value' => null, 'type' => 'image'],
            ['group' => 'header', 'key' => 'header_search_placeholder', 'value' => 'Search for AI tools, software, gift cards, subscriptions...', 'type' => 'text'],

            // Top Bar Information
            ['group' => 'header', 'key' => 'topbar_announcement_enabled', 'value' => '0', 'type' => 'boolean'],
            ['group' => 'header', 'key' => 'topbar_announcement_text', 'value' => 'Special discount on AI tools! Use code DIGIGO10 at checkout.', 'type' => 'text'],
            ['group' => 'header', 'key' => 'topbar_announcement_link', 'value' => null, 'type' => 'text'],
            ['group' => 'header', 'key' => 'topbar_support_label', 'value' => '24/7 SUPPORT', 'type' => 'text'],
            ['group' => 'header', 'key' => 'topbar_support_phone', 'value' => '+8809611678088', 'type' => 'text'],
            ['group' => 'header', 'key' => 'topbar_whatsapp_label', 'value' => 'WHATSAPP', 'type' => 'text'],
            ['group' => 'header', 'key' => 'topbar_whatsapp_phone', 'value' => '+8801600793325', 'type' => 'text'],

            // SEO & Meta Data
            ['group' => 'seo', 'key' => 'meta_title', 'value' => 'DigiGo - Digital Products, Software & Subscriptions', 'type' => 'text'],
            ['group' => 'seo', 'key' => 'meta_title_separator', 'value' => '|', 'type' => 'text'],
            ['group' => 'seo', 'key' => 'meta_description', 'value' => 'Bangladesh\'s leading digital software, subscription keys, gift cards, and AI tools marketplace.', 'type' => 'text'],
            ['group' => 'seo', 'key' => 'meta_keywords', 'value' => 'digital products, software keys, subscription, ai tools, bangladesh, gift cards', 'type' => 'text'],
            ['group' => 'seo', 'key' => 'meta_author', 'value' => 'DigiGo Bangladesh', 'type' => 'text'],
            ['group' => 'seo', 'key' => 'meta_robots', 'value' => 'index, follow', 'type' => 'text'],
            ['group' => 'seo', 'key' => 'og_title', 'value' => 'DigiGo - Digital Products, Software & Subscriptions', 'type' => 'text'],
            ['group' => 'seo', 'key' => 'og_description', 'value' => 'Get genuine software license keys, subscriptions, and AI tools instantly in Bangladesh.', 'type' => 'text'],
            ['group' => 'seo', 'key' => 'og_image', 'value' => null, 'type' => 'image'],
            ['group' => 'seo', 'key' => 'twitter_card', 'value' => 'summary_large_image', 'type' => 'text'],

            // SMTP & Email Delivery
            ['group' => 'smtp', 'key' => 'mail_mailer', 'value' => 'smtp', 'type' => 'text'],
            ['group' => 'smtp', 'key' => 'mail_host', 'value' => 'smtp.gmail.com', 'type' => 'text'],
            ['group' => 'smtp', 'key' => 'mail_port', 'value' => '587', 'type' => 'text'],
            ['group' => 'smtp', 'key' => 'mail_username', 'value' => null, 'type' => 'text'],
            ['group' => 'smtp', 'key' => 'mail_password', 'value' => null, 'type' => 'password'],
            ['group' => 'smtp', 'key' => 'mail_encryption', 'value' => 'tls', 'type' => 'text'],
            ['group' => 'smtp', 'key' => 'mail_from_address', 'value' => 'no-reply@digigo.com.bd', 'type' => 'text'],
            ['group' => 'smtp', 'key' => 'mail_from_name', 'value' => 'DigiGo', 'type' => 'text'],

            // Footer Appearance & Navigation Links
            ['group' => 'footer', 'key' => 'footer_about_text', 'value' => 'We specialize in providing exclusively authentic digital products, gadgets and strategic solutions to help turn your ambitious ideas into reality.', 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_custom_logo', 'value' => null, 'type' => 'image'],

            // Column 2 (Popular Categories / Policies)
            ['group' => 'footer', 'key' => 'footer_col2_title', 'value' => 'Popular Categories', 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_col2_link1_text', 'value' => 'Privacy Policy', 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_col2_link1_url', 'value' => '#privacy-policy', 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_col2_link2_text', 'value' => 'Refund Policy', 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_col2_link2_url', 'value' => '#refund-policy', 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_col2_link3_text', 'value' => 'Delivery Policy', 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_col2_link3_url', 'value' => '#delivery-policy', 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_col2_link4_text', 'value' => 'Support & FAQ', 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_col2_link4_url', 'value' => '#support-faq', 'type' => 'text'],

            // Column 3 (Useful Links)
            ['group' => 'footer', 'key' => 'footer_col3_title', 'value' => 'Useful Links', 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_col3_link1_text', 'value' => 'About Us', 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_col3_link1_url', 'value' => '#about-us', 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_col3_link2_text', 'value' => 'Contact Us', 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_col3_link2_url', 'value' => '#contact-us', 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_col3_link3_text', 'value' => 'Corporate Partnership', 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_col3_link3_url', 'value' => '#corporate-partnership', 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_col3_link4_text', 'value' => 'Career', 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_col3_link4_url', 'value' => '#career', 'type' => 'text'],

            // Column 4 (App Badges & Social Links)
            ['group' => 'footer', 'key' => 'footer_app_title', 'value' => 'Available On:', 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_play_store_enabled', 'value' => '1', 'type' => 'boolean'],
            ['group' => 'footer', 'key' => 'footer_play_store_url', 'value' => '#google-play', 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_app_store_enabled', 'value' => '1', 'type' => 'boolean'],
            ['group' => 'footer', 'key' => 'footer_app_store_url', 'value' => '#app-store', 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_social_title', 'value' => 'Social links:', 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_facebook_url', 'value' => 'https://facebook.com', 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_instagram_url', 'value' => 'https://instagram.com', 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_whatsapp_url', 'value' => 'https://whatsapp.com', 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_youtube_url', 'value' => null, 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_twitter_url', 'value' => null, 'type' => 'text'],

            // Bottom Copyright & Payment Methods
            ['group' => 'footer', 'key' => 'footer_copyright_text', 'value' => 'DigiGo Copyright 2018-2026 All Right Reserved.', 'type' => 'text'],
            ['group' => 'footer', 'key' => 'footer_payment_methods_enabled', 'value' => '1', 'type' => 'boolean'],
            ['group' => 'footer', 'key' => 'footer_payment_bkash', 'value' => '1', 'type' => 'boolean'],
            ['group' => 'footer', 'key' => 'footer_payment_nagad', 'value' => '1', 'type' => 'boolean'],
            ['group' => 'footer', 'key' => 'footer_payment_rocket', 'value' => '1', 'type' => 'boolean'],
            ['group' => 'footer', 'key' => 'footer_payment_upay', 'value' => '1', 'type' => 'boolean'],
            ['group' => 'footer', 'key' => 'footer_payment_cards', 'value' => '1', 'type' => 'boolean'],

            // Floating Support Chat Widget
            ['group' => 'footer', 'key' => 'footer_floating_chat_enabled', 'value' => '1', 'type' => 'boolean'],
            ['group' => 'footer', 'key' => 'footer_floating_chat_link', 'value' => 'https://wa.me/8801600793325', 'type' => 'text'],

            // Hero Banner Carousel Settings
            ['group' => 'banner', 'key' => 'banner_autoplay', 'value' => '1', 'type' => 'boolean'],
            ['group' => 'banner', 'key' => 'banner_delay', 'value' => '4000', 'type' => 'text'],
            ['group' => 'banner', 'key' => 'banner_arrows', 'value' => '1', 'type' => 'boolean'],
            ['group' => 'banner', 'key' => 'banner_dots', 'value' => '1', 'type' => 'boolean'],

            // Under-Banner Scrolling Trust Features Marquee
            ['group' => 'banner', 'key' => 'trust_features_enabled', 'value' => '1', 'type' => 'boolean'],
            ['group' => 'banner', 'key' => 'trust_features_speed', 'value' => '28', 'type' => 'text'],
            ['group' => 'banner', 'key' => 'trust_features_items', 'value' => json_encode([
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
            ]), 'type' => 'text'],
        ];

        foreach ($defaults as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'group' => $setting['group'],
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                ]
            );
        }

        Setting::clearCache();
    }
}
