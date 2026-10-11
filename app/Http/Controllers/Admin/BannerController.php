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
}
