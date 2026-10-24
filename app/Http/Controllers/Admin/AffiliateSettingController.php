<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AffiliateSettingController extends Controller
{
    /**
     * Show affiliate commissions setting page.
     */
    public function index(): View
    {
        $settings = [
            'affiliate_enabled' => Setting::get('affiliate_enabled', '1'),
            'affiliate_default_physical' => Setting::get('affiliate_default_physical', '5'),
            'affiliate_default_physical_type' => Setting::get('affiliate_default_physical_type', 'percentage'),
            'affiliate_default_digital' => Setting::get('affiliate_default_digital', '10'),
            'affiliate_default_digital_type' => Setting::get('affiliate_default_digital_type', 'percentage'),
            'affiliate_min_withdrawal' => Setting::get('affiliate_min_withdrawal', '500'),
            'affiliate_terms_note' => Setting::get('affiliate_terms_note', 'Commission will be added after order is confirmed and delivered.'),
        ];

        $categories = Category::select(
            'id',
            'name',
            'slug',
            'affiliate_commission_physical',
            'affiliate_commission_type_physical',
            'affiliate_commission_digital',
            'affiliate_commission_type_digital'
        )->orderBy('name')->get();

        return view('admin.settings.affiliate', compact('settings', 'categories'));
    }

    /**
     * Update global affiliate default settings.
     */
    public function updateGlobal(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'affiliate_enabled' => ['required', 'in:0,1'],
            'affiliate_default_physical' => ['required', 'numeric', 'min:0'],
            'affiliate_default_physical_type' => ['required', 'in:percentage,fixed'],
            'affiliate_default_digital' => ['required', 'numeric', 'min:0'],
            'affiliate_default_digital_type' => ['required', 'in:percentage,fixed'],
            'affiliate_min_withdrawal' => ['nullable', 'numeric', 'min:0'],
            'affiliate_terms_note' => ['nullable', 'string', 'max:500'],
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, (string) ($value ?? ''), 'affiliate', 'text');
        }

        Setting::clearCache();

        return back()->with('success', 'Global affiliate commission settings updated successfully.');
    }

    /**
     * Update category-wise affiliate commission rates.
     */
    public function updateCategories(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'categories' => ['required', 'array'],
            'categories.*.id' => ['required', 'integer', 'exists:categories,id'],
            'categories.*.physical' => ['nullable', 'numeric', 'min:0'],
            'categories.*.physical_type' => ['required', 'in:percentage,fixed'],
            'categories.*.digital' => ['nullable', 'numeric', 'min:0'],
            'categories.*.digital_type' => ['required', 'in:percentage,fixed'],
        ]);

        foreach ($validated['categories'] as $catData) {
            Category::where('id', $catData['id'])->update([
                'affiliate_commission_physical' => $catData['physical'] !== null && $catData['physical'] !== '' ? $catData['physical'] : null,
                'affiliate_commission_type_physical' => $catData['physical_type'] ?? 'percentage',
                'affiliate_commission_digital' => $catData['digital'] !== null && $catData['digital'] !== '' ? $catData['digital'] : null,
                'affiliate_commission_type_digital' => $catData['digital_type'] ?? 'percentage',
            ]);
        }

        return back()->with('success', 'Category-wise affiliate commissions updated successfully.');
    }
}
