<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the application homepage with dynamic banners and featured categories.
     */
    public function index(): View
    {
        $banners = Banner::active()->orderBy('order', 'asc')->get();

        $featuredCategories = Category::active()
            ->withCount(['products' => function ($q) {
                $q->active();
            }])
            ->orderByRaw('is_featured DESC, name ASC')
            ->take(10)
            ->get();

        $featuredBrands = Brand::active()
            ->whereHas('products', function ($q) {
                $q->active();
            })
            ->withCount(['products' => function ($q) {
                $q->active();
            }])
            ->orderBy('name', 'asc')
            ->get();

        $newArrivals = Product::with(['category', 'brand', 'plans'])
            ->active()
            ->latest()
            ->take(12)
            ->get();

        $mostSold = Product::with(['category', 'brand', 'plans'])
            ->active()
            ->withSum('orderItems as total_sold', 'quantity')
            ->orderByDesc('total_sold')
            ->orderByDesc('reviews_count')
            ->latest('id')
            ->take(10)
            ->get();

        $digitalDeals = Product::with(['category', 'brand', 'plans'])
            ->active()
            ->where('product_type', 'digital')
            ->latest()
            ->take(12)
            ->get();

        $viewedIds = session('recently_viewed_products', []);
        $recentlyViewed = collect();

        if (! empty($viewedIds) && is_array($viewedIds)) {
            $recentProducts = Product::with(['category', 'brand', 'plans'])
                ->active()
                ->whereIn('id', $viewedIds)
                ->get()
                ->keyBy('id');

            // Preserve the exact viewed order and take max 5 items
            $recentlyViewed = collect($viewedIds)
                ->map(fn ($id) => $recentProducts->get($id))
                ->filter()
                ->take(5)
                ->values();
        }

        return view('homepage', compact(
            'banners',
            'featuredCategories',
            'featuredBrands',
            'newArrivals',
            'mostSold',
            'digitalDeals',
            'recentlyViewed'
        ));
    }
}
