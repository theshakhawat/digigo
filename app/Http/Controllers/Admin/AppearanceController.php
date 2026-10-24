<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateFooterSettingsRequest;
use App\Http\Requests\Admin\UpdateHeaderSettingsRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AppearanceController extends Controller
{
    /**
     * Display website header, appearance, and SEO settings page.
     */
    public function header(): View
    {
        $settings = Setting::allCached();

        return view('admin.appearance.header', [
            'settings' => $settings,
        ]);
    }

    /**
     * Update website header, appearance, and SEO settings.
     */
    public function updateHeader(UpdateHeaderSettingsRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // 1. Handle Site Logo Upload / Removal
        if ($request->boolean('remove_site_logo')) {
            $currentLogo = Setting::get('site_logo');
            if ($currentLogo && Storage::disk('public')->exists($currentLogo)) {
                Storage::disk('public')->delete($currentLogo);
            }
            Setting::set('site_logo', null, 'header', 'image');
        } elseif ($request->hasFile('site_logo')) {
            $currentLogo = Setting::get('site_logo');
            if ($currentLogo && Storage::disk('public')->exists($currentLogo)) {
                Storage::disk('public')->delete($currentLogo);
            }
            $logoPath = $request->file('site_logo')->store('settings/logos', 'public');
            Setting::set('site_logo', $logoPath, 'header', 'image');
        }

        // 2. Handle Favicon Upload / Removal
        if ($request->boolean('remove_site_favicon')) {
            $currentFavicon = Setting::get('site_favicon');
            if ($currentFavicon && Storage::disk('public')->exists($currentFavicon)) {
                Storage::disk('public')->delete($currentFavicon);
            }
            Setting::set('site_favicon', null, 'header', 'image');
        } elseif ($request->hasFile('site_favicon')) {
            $currentFavicon = Setting::get('site_favicon');
            if ($currentFavicon && Storage::disk('public')->exists($currentFavicon)) {
                Storage::disk('public')->delete($currentFavicon);
            }
            $faviconPath = $request->file('site_favicon')->store('settings/favicons', 'public');
            Setting::set('site_favicon', $faviconPath, 'header', 'image');
        }

        // 3. Handle OG Social Share Image Upload / Removal
        if ($request->boolean('remove_og_image')) {
            $currentOg = Setting::get('og_image');
            if ($currentOg && Storage::disk('public')->exists($currentOg)) {
                Storage::disk('public')->delete($currentOg);
            }
            Setting::set('og_image', null, 'seo', 'image');
        } elseif ($request->hasFile('og_image')) {
            $currentOg = Setting::get('og_image');
            if ($currentOg && Storage::disk('public')->exists($currentOg)) {
                Storage::disk('public')->delete($currentOg);
            }
            $ogPath = $request->file('og_image')->store('settings/seo', 'public');
            Setting::set('og_image', $ogPath, 'seo', 'image');
        }

        // 4. Header & Top Bar Text Settings
        $headerFields = [
            'site_name',
            'header_search_placeholder',
            'topbar_announcement_text',
            'topbar_announcement_link',
            'topbar_support_label',
            'topbar_support_phone',
            'topbar_whatsapp_label',
            'topbar_whatsapp_phone',
        ];

        foreach ($headerFields as $field) {
            if (array_key_exists($field, $validated)) {
                Setting::set($field, $validated[$field] ?? '', 'header', 'text');
            }
        }

        Setting::set(
            'topbar_announcement_enabled',
            $request->boolean('topbar_announcement_enabled') ? '1' : '0',
            'header',
            'boolean'
        );

        // 5. SEO & Meta Settings
        $seoFields = [
            'meta_title',
            'meta_title_separator',
            'meta_description',
            'meta_keywords',
            'meta_author',
            'meta_robots',
            'og_title',
            'og_description',
            'twitter_card',
        ];

        foreach ($seoFields as $field) {
            if (array_key_exists($field, $validated)) {
                Setting::set($field, $validated[$field] ?? '', 'seo', 'text');
            }
        }

        Setting::clearCache();

        return redirect()
            ->route('admin.appearance.header')
            ->with('success', 'Header, appearance, and SEO settings updated successfully!');
    }

    /**
     * Display website footer appearance and content settings page.
     */
    public function footer(): View
    {
        $settings = Setting::allCached();

        return view('admin.appearance.footer', [
            'settings' => $settings,
        ]);
    }

    /**
     * Update website footer appearance and content settings.
     */
    public function updateFooter(UpdateFooterSettingsRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // 1. Handle Footer Custom Logo Upload / Removal
        if ($request->boolean('remove_footer_custom_logo')) {
            $currentLogo = Setting::get('footer_custom_logo');
            if ($currentLogo && Storage::disk('public')->exists($currentLogo)) {
                Storage::disk('public')->delete($currentLogo);
            }
            Setting::set('footer_custom_logo', null, 'footer', 'image');
        } elseif ($request->hasFile('footer_custom_logo')) {
            $currentLogo = Setting::get('footer_custom_logo');
            if ($currentLogo && Storage::disk('public')->exists($currentLogo)) {
                Storage::disk('public')->delete($currentLogo);
            }
            $logoPath = $request->file('footer_custom_logo')->store('settings/footer', 'public');
            Setting::set('footer_custom_logo', $logoPath, 'footer', 'image');
        }

        // 2. Text and URL Fields
        $textFields = [
            'footer_about_text',
            'footer_col2_title',
            'footer_col2_link1_text',
            'footer_col2_link1_url',
            'footer_col2_link2_text',
            'footer_col2_link2_url',
            'footer_col2_link3_text',
            'footer_col2_link3_url',
            'footer_col2_link4_text',
            'footer_col2_link4_url',
            'footer_col3_title',
            'footer_col3_link1_text',
            'footer_col3_link1_url',
            'footer_col3_link2_text',
            'footer_col3_link2_url',
            'footer_col3_link3_text',
            'footer_col3_link3_url',
            'footer_col3_link4_text',
            'footer_col3_link4_url',
            'footer_app_title',
            'footer_play_store_url',
            'footer_app_store_url',
            'footer_social_title',
            'footer_facebook_url',
            'footer_instagram_url',
            'footer_whatsapp_url',
            'footer_youtube_url',
            'footer_twitter_url',
            'footer_copyright_text',
            'footer_floating_chat_link',
        ];

        foreach ($textFields as $field) {
            if (array_key_exists($field, $validated)) {
                Setting::set($field, $validated[$field] ?? '', 'footer', 'text');
            }
        }

        // 3. Boolean Toggles
        $booleanFields = [
            'footer_play_store_enabled',
            'footer_app_store_enabled',
            'footer_payment_methods_enabled',
            'footer_payment_dgepay',
            'footer_payment_bkash',
            'footer_payment_nagad',
            'footer_payment_rocket',
            'footer_payment_upay',
            'footer_payment_cards',
            'footer_floating_chat_enabled',
        ];

        foreach ($booleanFields as $field) {
            Setting::set(
                $field,
                $request->boolean($field) ? '1' : '0',
                'footer',
                'boolean'
            );
        }

        Setting::clearCache();

        return redirect()
            ->route('admin.appearance.footer')
            ->with('success', 'Footer appearance and settings updated successfully!');
    }
}
