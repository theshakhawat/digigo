<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display the specified category and its products.
     */
    public function show(Request $request, string $slug): View
    {
        $category = Category::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $query = $category->products()
            ->with(['category', 'brand', 'plans'])
            ->active();

        // Optional search inside category
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

        // Sorting
        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            'popular' => $query->orderByDesc('reviews_count')->orderByDesc('rating'),
            default => $query->latest('id'),
        };

        $products = $query->paginate(12)->withQueryString();

        $allCategories = Category::active()
            ->withCount(['products' => function ($q) {
                $q->active();
            }])
            ->orderBy('name')
            ->get();

        return view('category-products', compact('category', 'products', 'allCategories'));
    }
}
