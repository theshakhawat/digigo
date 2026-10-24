<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BrandController extends Controller
{
    /**
     * Display the specified brand and its products.
     */
    public function show(Request $request, string $slug): View
    {
        $brand = Brand::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $query = $brand->products()
            ->with(['category', 'brand', 'plans'])
            ->active();

        // Optional search inside brand
        if ($request->filled('q')) {
            $searchTerm = $request->input('q');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('short_description', 'like', "%{$searchTerm}%")
                    ->orWhere('sku', 'like', "%{$searchTerm}%");
            });
        }

        // Product type filter
        if ($request->filled('type') && in_array($request->input('type'), ['digital', 'physical'])) {
            $query->where('product_type', $request->input('type'));
        }

        // Category filter inside brand
        if ($request->filled('category')) {
            $cat = $request->input('category');
            if (is_numeric($cat)) {
                $query->where('category_id', $cat);
            } else {
                $query->whereHas('category', function ($q) use ($cat) {
                    $q->where('slug', $cat);
                });
            }
        }

        // Price range filter
        if ($request->filled('min_price') && is_numeric($request->input('min_price'))) {
            $minPrice = (float) $request->input('min_price');
            $query->where(function ($q) use ($minPrice) {
                $q->where(function ($sub) use ($minPrice) {
                    $sub->whereNotNull('sale_price')->where('sale_price', '>=', $minPrice);
                })->orWhere(function ($sub) use ($minPrice) {
                    $sub->whereNull('sale_price')->where('price', '>=', $minPrice);
                });
            });
        }

        if ($request->filled('max_price') && is_numeric($request->input('max_price'))) {
            $maxPrice = (float) $request->input('max_price');
            $query->where(function ($q) use ($maxPrice) {
                $q->where(function ($sub) use ($maxPrice) {
                    $sub->whereNotNull('sale_price')->where('sale_price', '<=', $maxPrice);
                })->orWhere(function ($sub) use ($maxPrice) {
                    $sub->whereNull('sale_price')->where('price', '<=', $maxPrice);
                });
            });
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'price_low' => $query->orderByRaw('COALESCE(sale_price, price) asc'),
            'price_high' => $query->orderByRaw('COALESCE(sale_price, price) desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            'popular' => $query->orderByDesc('reviews_count')->orderByDesc('rating'),
            default => $query->latest('id'),
        };

        $products = $query->paginate(12)->withQueryString();

        // Other brands for sidebar
        $allBrands = Brand::active()
            ->withCount(['products' => function ($q) {
                $q->active();
            }])
            ->orderBy('name')
            ->get();

        // Categories associated with this brand
        $brandCategories = Category::active()
            ->whereHas('products', function ($q) use ($brand) {
                $q->active()->where('brand_id', $brand->id);
            })
            ->withCount(['products' => function ($q) use ($brand) {
                $q->active()->where('brand_id', $brand->id);
            }])
            ->get();

        return view('brand-products', compact('brand', 'products', 'allBrands', 'brandCategories'));
    }
}
