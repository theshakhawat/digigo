<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShippingSettingController extends Controller
{
    /**
     * Show shipping and delivery management page.
     */
    public function index(): View
    {
        $settings = [
            'shipping_default_inside_dhaka' => Setting::get('shipping_default_inside_dhaka', 60),
            'shipping_default_outside_dhaka' => Setting::get('shipping_default_outside_dhaka', 100),
            'shipping_free_threshold' => Setting::get('shipping_free_threshold', ''),
            'shipping_estimated_time_inside' => Setting::get('shipping_estimated_time_inside', '24 - 48 Hours'),
            'shipping_estimated_time_outside' => Setting::get('shipping_estimated_time_outside', '3 - 5 Days'),
        ];

        $categories = Category::select('id', 'name', 'slug', 'delivery_charge_inside_dhaka', 'delivery_charge_outside_dhaka')
            ->orderBy('name')
            ->get();

        return view('admin.settings.shipping', compact('settings', 'categories'));
    }

    /**
     * Update global shipping default settings.
     */
    public function updateGlobal(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'shipping_default_inside_dhaka' => ['required', 'numeric', 'min:0'],
            'shipping_default_outside_dhaka' => ['required', 'numeric', 'min:0'],
            'shipping_free_threshold' => ['nullable', 'numeric', 'min:0'],
            'shipping_estimated_time_inside' => ['nullable', 'string', 'max:100'],
            'shipping_estimated_time_outside' => ['nullable', 'string', 'max:100'],
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, (string) ($value ?? ''), 'shipping', 'text');
        }

        Setting::clearCache();

        return back()->with('success', 'Default shipping settings updated successfully.');
    }

    /**
     * Update category-wise delivery rates.
     */
    public function updateCategories(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'categories' => ['required', 'array'],
            'categories.*.id' => ['required', 'integer', 'exists:categories,id'],
            'categories.*.inside' => ['nullable', 'numeric', 'min:0'],
            'categories.*.outside' => ['nullable', 'numeric', 'min:0'],
        ]);

        foreach ($validated['categories'] as $catData) {
            Category::where('id', $catData['id'])->update([
                'delivery_charge_inside_dhaka' => $catData['inside'] !== null && $catData['inside'] !== '' ? $catData['inside'] : null,
                'delivery_charge_outside_dhaka' => $catData['outside'] !== null && $catData['outside'] !== '' ? $catData['outside'] : null,
            ]);
        }

        return back()->with('success', 'Category delivery charges updated successfully.');
    }
}
