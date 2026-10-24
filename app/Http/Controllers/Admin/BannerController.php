<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBannerRequest;
use App\Http\Requests\Admin\UpdateBannerRequest;
use App\Http\Requests\Admin\UpdateBannerSettingsRequest;
use App\Http\Requests\Admin\UpdateTrustFeaturesRequest;
use App\Models\Banner;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BannerController extends Controller
{
    /**
     * Display a listing of banner slides and carousel settings.
     */
    public function index(Request $request): View
    {
        $query = Banner::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('subtitle', 'like', "%{$search}%")
                    ->orWhere('button_text', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $banners = $query->orderBy('order', 'asc')->latest('id')->paginate(12)->withQueryString();

        $totalBanners = Banner::count();
        $activeBanners = Banner::where('status', 'active')->count();

        return view('admin.appearance.banners.index', compact('banners', 'totalBanners', 'activeBanners'));
    }

    /**
     * Show the form for creating a new banner slide.
     */
    public function create(): View
    {
        $nextOrder = (Banner::max('order') ?? 0) + 1;

        return view('admin.appearance.banners.create', compact('nextOrder'));
    }

    /**
     * Store a newly created banner slide in storage.
     */
    public function store(StoreBannerRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('banners', 'public');
            $validated['image'] = $path;
        } elseif (! empty($validated['image_url'])) {
            $validated['image'] = $validated['image_url'];
        }

        unset($validated['image_file'], $validated['image_url']);

        if (! isset($validated['order']) || $validated['order'] === null) {
            $validated['order'] = (Banner::max('order') ?? 0) + 1;
        }

        Banner::create($validated);

        return redirect()->route('admin.appearance.banners.index')
            ->with('success', 'Banner slide created successfully.');
    }

    /**
     * Show the form for editing the specified banner slide.
     */
    public function edit(Banner $banner): View
    {
        return view('admin.appearance.banners.edit', compact('banner'));
    }

    /**
     * Update the specified banner slide in storage.
     */
    public function update(UpdateBannerRequest $request, Banner $banner): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image_file')) {
            // Delete old file from storage if stored locally
            if ($banner->image && ! str_starts_with($banner->image, 'http') && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
            $path = $request->file('image_file')->store('banners', 'public');
            $validated['image'] = $path;
        } elseif (! empty($validated['image_url'])) {
            // If replacing with external URL and old file was local, delete old file
            if ($banner->image && ! str_starts_with($banner->image, 'http') && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
            $validated['image'] = $validated['image_url'];
        }

        unset($validated['image_file'], $validated['image_url']);

        $banner->update($validated);

        return redirect()->route('admin.appearance.banners.index')
            ->with('success', 'Banner slide updated successfully.');
    }

    /**
     * Remove the specified banner slide from storage.
     */
    public function destroy(Banner $banner): RedirectResponse
    {
        if ($banner->image && ! str_starts_with($banner->image, 'http') && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return redirect()->route('admin.appearance.banners.index')
            ->with('success', 'Banner slide deleted successfully.');
    }

    /**
     * Update global carousel settings.
     */
    public function updateSettings(UpdateBannerSettingsRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Setting::set('banner_autoplay', $request->boolean('banner_autoplay') ? '1' : '0', 'banner', 'boolean');
        Setting::set('banner_delay', (string) $validated['banner_delay'], 'banner', 'text');
        Setting::set('banner_arrows', $request->boolean('banner_arrows') ? '1' : '0', 'banner', 'boolean');
        Setting::set('banner_dots', $request->boolean('banner_dots') ? '1' : '0', 'banner', 'boolean');

        Setting::clearCache();

        return redirect()->route('admin.appearance.banners.index')
            ->with('success', 'Hero banner carousel settings saved successfully.');
    }

    /**
     * Toggle banner active / inactive status.
     */
    public function toggleStatus(Banner $banner): RedirectResponse
    {
        $banner->status = $banner->status === 'active' ? 'inactive' : 'active';
        $banner->save();

        return redirect()->route('admin.appearance.banners.index')
            ->with('success', 'Banner status updated to '.ucfirst($banner->status).'.');
    }

    /**
     * Update under-banner scrolling trust features marquee settings.
     */
    public function updateTrustFeatures(UpdateTrustFeaturesRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $items = [];
        if (! empty($validated['items']) && is_array($validated['items'])) {
            foreach ($validated['items'] as $item) {
                $text = trim($item['text'] ?? '');
                if ($text !== '') {
                    $items[] = [
                        'icon' => trim($item['icon'] ?? 'fa-solid fa-circle-check'),
                        'text' => $text,
                        'link' => trim($item['link'] ?? ''),
                    ];
                }
            }
        }

        Setting::set('trust_features_enabled', $request->boolean('trust_features_enabled') ? '1' : '0', 'banner', 'boolean');
        Setting::set('trust_features_speed', (string) $validated['trust_features_speed'], 'banner', 'text');
        Setting::set('trust_features_items', json_encode($items), 'banner', 'text');

        Setting::clearCache();

        return redirect()->route('admin.appearance.banners.index', ['tab' => 'ticker'])
            ->with('success', 'Scrolling trust features ticker updated successfully.');
    }

    /**
     * Update homepage promotional showcase banner settings.
     */
    public function updatePromoBanner(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'promo_banner_enabled' => ['nullable'],
            'promo_badge' => ['nullable', 'string', 'max:100'],
            'promo_title' => ['nullable', 'string', 'max:255'],
            'promo_subtitle' => ['nullable', 'string', 'max:500'],
            'promo_button_text' => ['nullable', 'string', 'max:100'],
            'promo_button_link' => ['nullable', 'string', 'max:255'],
            'promo_visual_1' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif', 'max:3072'],
            'promo_visual_2' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif', 'max:3072'],
            'promo_backdrop' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif', 'max:4096'],
            'remove_visual_1' => ['nullable', 'boolean'],
            'remove_visual_2' => ['nullable', 'boolean'],
            'remove_backdrop' => ['nullable', 'boolean'],
        ]);

        Setting::set('promo_banner_enabled', $request->boolean('promo_banner_enabled') ? '1' : '0', 'promo', 'boolean');
        Setting::set('promo_badge', (string) ($validated['promo_badge'] ?? ''), 'promo', 'text');
        Setting::set('promo_title', (string) ($validated['promo_title'] ?? ''), 'promo', 'text');
        Setting::set('promo_subtitle', (string) ($validated['promo_subtitle'] ?? ''), 'promo', 'text');
        Setting::set('promo_button_text', (string) ($validated['promo_button_text'] ?? ''), 'promo', 'text');
        Setting::set('promo_button_link', (string) ($validated['promo_button_link'] ?? ''), 'promo', 'text');

        // Handle visual 1
        if ($request->boolean('remove_visual_1')) {
            $old = Setting::get('promo_visual_1');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            Setting::set('promo_visual_1', '', 'promo', 'image');
        } elseif ($request->hasFile('promo_visual_1')) {
            $old = Setting::get('promo_visual_1');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('promo_visual_1')->store('promos', 'public');
            Setting::set('promo_visual_1', $path, 'promo', 'image');
        }

        // Handle visual 2
        if ($request->boolean('remove_visual_2')) {
            $old = Setting::get('promo_visual_2');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            Setting::set('promo_visual_2', '', 'promo', 'image');
        } elseif ($request->hasFile('promo_visual_2')) {
            $old = Setting::get('promo_visual_2');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('promo_visual_2')->store('promos', 'public');
            Setting::set('promo_visual_2', $path, 'promo', 'image');
        }

        // Handle backdrop image
        if ($request->boolean('remove_backdrop')) {
            $old = Setting::get('promo_backdrop');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            Setting::set('promo_backdrop', '', 'promo', 'image');
        } elseif ($request->hasFile('promo_backdrop')) {
            $old = Setting::get('promo_backdrop');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('promo_backdrop')->store('promos', 'public');
            Setting::set('promo_backdrop', $path, 'promo', 'image');
        }

        Setting::clearCache();

        return redirect()->route('admin.appearance.banners.index', ['tab' => 'promo'])
            ->with('success', 'Promotional Showcase Banner updated successfully.');
    }

    /**
     * Update 3-card highlights promotional grid settings.
     */
    public function updateTriPromoGrid(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tri_promo_enabled' => ['nullable'],
            // Card 1
            'tri_promo_1_theme' => ['required', 'in:light,dark'],
            'tri_promo_1_title' => ['required', 'string', 'max:255'],
            'tri_promo_1_desc' => ['nullable', 'string', 'max:500'],
            'tri_promo_1_button_text' => ['nullable', 'string', 'max:100'],
            'tri_promo_1_button_link' => ['nullable', 'string', 'max:255'],
            'tri_promo_1_img' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif', 'max:3072'],
            'remove_card_1_img' => ['nullable', 'boolean'],
            // Card 2
            'tri_promo_2_theme' => ['required', 'in:light,dark'],
            'tri_promo_2_title' => ['required', 'string', 'max:255'],
            'tri_promo_2_desc' => ['nullable', 'string', 'max:500'],
            'tri_promo_2_button_text' => ['nullable', 'string', 'max:100'],
            'tri_promo_2_button_link' => ['nullable', 'string', 'max:255'],
            'tri_promo_2_img' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif', 'max:3072'],
            'remove_card_2_img' => ['nullable', 'boolean'],
            // Card 3
            'tri_promo_3_theme' => ['required', 'in:light,dark'],
            'tri_promo_3_title' => ['required', 'string', 'max:255'],
            'tri_promo_3_desc' => ['nullable', 'string', 'max:500'],
            'tri_promo_3_button_text' => ['nullable', 'string', 'max:100'],
            'tri_promo_3_button_link' => ['nullable', 'string', 'max:255'],
            'tri_promo_3_img' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif', 'max:3072'],
            'remove_card_3_img' => ['nullable', 'boolean'],
        ]);

        Setting::set('tri_promo_enabled', $request->boolean('tri_promo_enabled') ? '1' : '0', 'tri_promo', 'boolean');

        for ($i = 1; $i <= 3; $i++) {
            Setting::set("tri_promo_{$i}_theme", (string) $validated["tri_promo_{$i}_theme"], 'tri_promo', 'text');
            Setting::set("tri_promo_{$i}_title", (string) $validated["tri_promo_{$i}_title"], 'tri_promo', 'text');
            Setting::set("tri_promo_{$i}_desc", (string) ($validated["tri_promo_{$i}_desc"] ?? ''), 'tri_promo', 'text');
            Setting::set("tri_promo_{$i}_button_text", (string) ($validated["tri_promo_{$i}_button_text"] ?? ''), 'tri_promo', 'text');
            Setting::set("tri_promo_{$i}_button_link", (string) ($validated["tri_promo_{$i}_button_link"] ?? ''), 'tri_promo', 'text');

            if ($request->boolean("remove_card_{$i}_img")) {
                $old = Setting::get("tri_promo_{$i}_img");
                if ($old && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
                Setting::set("tri_promo_{$i}_img", '', 'tri_promo', 'image');
            } elseif ($request->hasFile("tri_promo_{$i}_img")) {
                $old = Setting::get("tri_promo_{$i}_img");
                if ($old && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
                $path = $request->file("tri_promo_{$i}_img")->store('promos', 'public');
                Setting::set("tri_promo_{$i}_img", $path, 'tri_promo', 'image');
            }
        }

        Setting::clearCache();

        return redirect()->route('admin.appearance.banners.index', ['tab' => 'tri_promo'])
            ->with('success', '3-Card Feature Highlights updated successfully.');
    }

    /**
     * Update 2-card coupon & discount announcement banner grid settings.
     */
    public function updateDualPromoGrid(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'dual_promo_enabled' => ['nullable'],
            // Banner 1
            'dual_promo_1_title' => ['required', 'string', 'max:255'],
            'dual_promo_1_offer_label' => ['nullable', 'string', 'max:150'],
            'dual_promo_1_coupon_code' => ['nullable', 'string', 'max:60'],
            'dual_promo_1_coupon_bg_color' => ['nullable', 'string', 'max:30'],
            'dual_promo_1_desc' => ['nullable', 'string', 'max:500'],
            'dual_promo_1_disclaimer' => ['nullable', 'string', 'max:255'],
            'dual_promo_1_link' => ['nullable', 'string', 'max:255'],
            'dual_promo_1_bg_start' => ['nullable', 'string', 'max:30'],
            'dual_promo_1_bg_end' => ['nullable', 'string', 'max:30'],
            'dual_promo_1_img' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif', 'max:4096'],
            'remove_dual_1_img' => ['nullable', 'boolean'],
            // Banner 2
            'dual_promo_2_title' => ['required', 'string', 'max:255'],
            'dual_promo_2_offer_label' => ['nullable', 'string', 'max:150'],
            'dual_promo_2_coupon_code' => ['nullable', 'string', 'max:60'],
            'dual_promo_2_coupon_bg_color' => ['nullable', 'string', 'max:30'],
            'dual_promo_2_desc' => ['nullable', 'string', 'max:500'],
            'dual_promo_2_disclaimer' => ['nullable', 'string', 'max:255'],
            'dual_promo_2_link' => ['nullable', 'string', 'max:255'],
            'dual_promo_2_bg_start' => ['nullable', 'string', 'max:30'],
            'dual_promo_2_bg_end' => ['nullable', 'string', 'max:30'],
            'dual_promo_2_img' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif', 'max:4096'],
            'remove_dual_2_img' => ['nullable', 'boolean'],
        ]);

        Setting::set('dual_promo_enabled', $request->boolean('dual_promo_enabled') ? '1' : '0', 'dual_promo', 'boolean');

        for ($i = 1; $i <= 2; $i++) {
            Setting::set("dual_promo_{$i}_title", (string) $validated["dual_promo_{$i}_title"], 'dual_promo', 'text');
            Setting::set("dual_promo_{$i}_offer_label", (string) ($validated["dual_promo_{$i}_offer_label"] ?? ''), 'dual_promo', 'text');
            Setting::set("dual_promo_{$i}_coupon_code", (string) ($validated["dual_promo_{$i}_coupon_code"] ?? ''), 'dual_promo', 'text');
            Setting::set("dual_promo_{$i}_coupon_bg_color", (string) ($validated["dual_promo_{$i}_coupon_bg_color"] ?? '#e53935'), 'dual_promo', 'text');
            Setting::set("dual_promo_{$i}_desc", (string) ($validated["dual_promo_{$i}_desc"] ?? ''), 'dual_promo', 'text');
            Setting::set("dual_promo_{$i}_disclaimer", (string) ($validated["dual_promo_{$i}_disclaimer"] ?? ''), 'dual_promo', 'text');
            Setting::set("dual_promo_{$i}_link", (string) ($validated["dual_promo_{$i}_link"] ?? ''), 'dual_promo', 'text');
            Setting::set("dual_promo_{$i}_bg_start", (string) ($validated["dual_promo_{$i}_bg_start"] ?? ($i === 1 ? '#1976d2' : '#ad1457')), 'dual_promo', 'text');
            Setting::set("dual_promo_{$i}_bg_end", (string) ($validated["dual_promo_{$i}_bg_end"] ?? ($i === 1 ? '#29b6f6' : '#ec407a')), 'dual_promo', 'text');

            if ($request->boolean("remove_dual_{$i}_img")) {
                $old = Setting::get("dual_promo_{$i}_img");
                if ($old && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
                Setting::set("dual_promo_{$i}_img", '', 'dual_promo', 'image');
            } elseif ($request->hasFile("dual_promo_{$i}_img")) {
                $old = Setting::get("dual_promo_{$i}_img");
                if ($old && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
                $path = $request->file("dual_promo_{$i}_img")->store('promos', 'public');
                Setting::set("dual_promo_{$i}_img", $path, 'dual_promo', 'image');
            }
        }

        Setting::clearCache();

        return redirect()->route('admin.appearance.banners.index', ['tab' => 'dual_promo'])
            ->with('success', 'Coupon & Deals Announcement Banners updated successfully.');
    }

    /**
     * Update Remini AI image editing promotional banner settings.
     */
    public function updateReminiBanner(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'remini_banner_enabled' => ['nullable'],
            'remini_badge' => ['nullable', 'string', 'max:100'],
            'remini_title' => ['required', 'string', 'max:255'],
            'remini_title_highlight' => ['nullable', 'string', 'max:150'],
            'remini_button_text' => ['nullable', 'string', 'max:100'],
            'remini_button_link' => ['nullable', 'string', 'max:255'],
            'remini_app_name' => ['nullable', 'string', 'max:100'],
            'remini_bg_color' => ['nullable', 'string', 'max:30'],
            'remini_badge_color' => ['nullable', 'string', 'max:30'],
            'remini_app_bg_color' => ['nullable', 'string', 'max:30'],
            'remini_app_icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif', 'max:3072'],
            'remini_landscape_img' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif', 'max:4096'],
            'remove_remini_icon' => ['nullable', 'boolean'],
            'remove_remini_landscape' => ['nullable', 'boolean'],
        ]);

        Setting::set('remini_banner_enabled', $request->boolean('remini_banner_enabled') ? '1' : '0', 'remini', 'boolean');
        Setting::set('remini_badge', (string) ($validated['remini_badge'] ?? 'AI Image Editing Tool'), 'remini', 'text');
        Setting::set('remini_title', (string) $validated['remini_title'], 'remini', 'text');
        Setting::set('remini_title_highlight', (string) ($validated['remini_title_highlight'] ?? ''), 'remini', 'text');
        Setting::set('remini_button_text', (string) ($validated['remini_button_text'] ?? 'Get Now'), 'remini', 'text');
        Setting::set('remini_button_link', (string) ($validated['remini_button_link'] ?? '#remini-pro'), 'remini', 'text');
        Setting::set('remini_app_name', (string) ($validated['remini_app_name'] ?? 'Remini'), 'remini', 'text');
        Setting::set('remini_bg_color', (string) ($validated['remini_bg_color'] ?? '#f3c4c4'), 'remini', 'text');
        Setting::set('remini_badge_color', (string) ($validated['remini_badge_color'] ?? '#8b1d24'), 'remini', 'text');
        Setting::set('remini_app_bg_color', (string) ($validated['remini_app_bg_color'] ?? '#991b1b'), 'remini', 'text');

        // Handle app icon
        if ($request->boolean('remove_remini_icon')) {
            $old = Setting::get('remini_app_icon');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            Setting::set('remini_app_icon', '', 'remini', 'image');
        } elseif ($request->hasFile('remini_app_icon')) {
            $old = Setting::get('remini_app_icon');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('remini_app_icon')->store('promos', 'public');
            Setting::set('remini_app_icon', $path, 'remini', 'image');
        }

        // Handle right landscape comparison visual
        if ($request->boolean('remove_remini_landscape')) {
            $old = Setting::get('remini_landscape_img');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            Setting::set('remini_landscape_img', '', 'remini', 'image');
        } elseif ($request->hasFile('remini_landscape_img')) {
            $old = Setting::get('remini_landscape_img');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('remini_landscape_img')->store('promos', 'public');
            Setting::set('remini_landscape_img', $path, 'remini', 'image');
        }

        Setting::clearCache();

        return redirect()->route('admin.appearance.banners.index', ['tab' => 'remini'])
            ->with('success', 'AI Tool Promotional Banner updated successfully.');
    }
}
