<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Show single product details page.
     */
    public function show(?string $slug = null): View
    {
        $slug = $slug ?: 'prime-video-subscription';
        $productModel = Product::with(['category', 'brand', 'plans', 'reviews.user'])->where('slug', $slug)->first();

        $canReview = false;
        $userReview = null;

        if ($productModel) {
            $user = auth()->user();
            $canReview = $productModel->canUserReview($user);
            if ($user) {
                $userReview = $productModel->reviews->where('user_id', $user->id)->first();
            }

            // Track recently viewed products in session (up to 10 unique IDs, most recent first)
            $viewedIds = session('recently_viewed_products', []);
            $viewedIds = array_values(array_diff($viewedIds, [$productModel->id]));
            array_unshift($viewedIds, $productModel->id);
            session(['recently_viewed_products' => array_slice($viewedIds, 0, 10)]);

            $defaultPlan = $productModel->plans->where('is_default', true)->first() ?: $productModel->plans->first();
            $effectivePrice = $defaultPlan ? $defaultPlan->effective_price : $productModel->effective_price;
            $oldPrice = $defaultPlan ? ($defaultPlan->sale_price ? $defaultPlan->price : null) : ($productModel->sale_price ? $productModel->price : null);

            $reviewsCount = $productModel->reviews->count();
            $effectiveRating = $reviewsCount > 0 ? (float) $productModel->rating : 5.0;

            $product = [
                'id' => $productModel->id,
                'title' => $productModel->name,
                'slug' => $productModel->slug,
                'product_type' => $productModel->product_type,
                'category' => $productModel->category->name ?? 'General',
                'category_slug' => $productModel->category->slug ?? null,
                'brand' => $productModel->brand->name ?? null,
                'brand_slug' => $productModel->brand->slug ?? null,
                'rating' => $effectiveRating,
                'reviews_count' => $reviewsCount,
                'price' => $effectivePrice,
                'old_price' => $oldPrice,
                'discount' => $productModel->discount_percentage ? "{$productModel->discount_percentage}% OFF" : null,
                'is_in_stock' => $productModel->is_in_stock,
                'stock_quantity' => $productModel->stock_quantity,
                'stock_status' => $productModel->stock_status_label,
                'image' => $productModel->thumbnail_url ?: 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=800&q=80',
                'gallery' => $productModel->gallery_urls,
                'features' => ! empty($productModel->features) ? $productModel->features : [
                    '100% Genuine guaranteed',
                    'Full support & warranty coverage',
                ],
                'description' => $productModel->description ?: $productModel->short_description,
                'plans' => $productModel->plans,
                'has_plans' => $productModel->has_plans,
                'sku' => $productModel->sku,
                'warranty_period' => $productModel->warranty_period,
                'package_contents' => $productModel->package_contents,
                'digital_delivery_type' => $productModel->digital_delivery_type,
                'access_instructions' => $productModel->access_instructions,
                'model' => $productModel,
            ];
        } else {
            $product = [
                'title' => 'ChatGPT Plus 1 Month Private Account / Shared Access',
                'slug' => $slug,
                'product_type' => 'digital',
                'category' => 'Artificial Intelligence (AI)',
                'brand' => null,
                'rating' => 4.9,
                'reviews_count' => 128,
                'price' => 1450,
                'old_price' => 2200,
                'discount' => '34% OFF',
                'stock_status' => 'In Stock (Instant Delivery)',
                'image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=800&q=80',
                'gallery' => [],
                'features' => [
                    'Full access to GPT-4o, DALL·E 3, and Web Browsing',
                    'Advanced Data Analysis & Custom GPTs Access',
                    'Private mail credentials or instant activation on your email',
                    '1 Month full replacement warranty & 24/7 dedicated support',
                ],
                'description' => 'Supercharge your productivity with ChatGPT Plus. Access OpenAI’s most capable model, create custom GPT agents, generate high-definition visual assets, and browse the live web seamlessly with ultra-fast response times.',
                'plans' => collect(),
                'has_plans' => false,
                'model' => null,
            ];
        }

        return view('product-detail', compact('product', 'canReview', 'userReview'));
    }

    /**
     * Search and filter results page.
     */
    public function search(Request $request): View
    {
        $query = trim($request->query('q', ''));
        $categoryId = $request->query('category');
        $brandId = $request->query('brand');
        $productType = $request->query('type');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $inStock = $request->boolean('in_stock');
        $featuredOnly = $request->boolean('featured');
        $hasDiscount = $request->boolean('has_discount');
        $sort = $request->query('sort', 'latest');

        $productsQuery = Product::with(['category', 'brand', 'plans'])
            ->active();

        // Title / Name / Description search
        if ($query !== '') {
            $productsQuery->where(function ($sub) use ($query) {
                $sub->where('name', 'like', "%{$query}%")
                    ->orWhere('short_description', 'like', "%{$query}%")
                    ->orWhere('sku', 'like', "%{$query}%");
            });
        }

        // Category filter (id or slug)
        if ($categoryId) {
            if (is_numeric($categoryId)) {
                $productsQuery->where('category_id', $categoryId);
            } else {
                $productsQuery->whereHas('category', function ($q) use ($categoryId) {
                    $q->where('slug', $categoryId);
                });
            }
        }

        // Brand filter (id or slug)
        if ($brandId) {
            if (is_numeric($brandId)) {
                $productsQuery->where('brand_id', $brandId);
            } else {
                $productsQuery->whereHas('brand', function ($q) use ($brandId) {
                    $q->where('slug', $brandId);
                });
            }
        }

        // Product Type filter (digital vs physical)
        if ($productType && in_array($productType, ['digital', 'physical'])) {
            $productsQuery->where('product_type', $productType);
        }

        // Price range filter
        if ($minPrice !== null && is_numeric($minPrice)) {
            $productsQuery->where(function ($q) use ($minPrice) {
                $q->where(function ($sub) use ($minPrice) {
                    $sub->whereNotNull('sale_price')->where('sale_price', '>=', (float) $minPrice);
                })->orWhere(function ($sub) use ($minPrice) {
                    $sub->whereNull('sale_price')->where('price', '>=', (float) $minPrice);
                });
            });
        }

        if ($maxPrice !== null && is_numeric($maxPrice)) {
            $productsQuery->where(function ($q) use ($maxPrice) {
                $q->where(function ($sub) use ($maxPrice) {
                    $sub->whereNotNull('sale_price')->where('sale_price', '<=', (float) $maxPrice);
                })->orWhere(function ($sub) use ($maxPrice) {
                    $sub->whereNull('sale_price')->where('price', '<=', (float) $maxPrice);
                });
            });
        }

        // Feature / Flag filters
        if ($featuredOnly) {
            $productsQuery->where('is_featured', true);
        }

        if ($inStock) {
            $productsQuery->where(function ($q) {
                $q->where('product_type', 'digital')
                    ->orWhere('stock_quantity', '>', 0);
            });
        }

        if ($hasDiscount) {
            $productsQuery->whereNotNull('sale_price')
                ->whereRaw('sale_price < price');
        }

        // Sorting
        switch ($sort) {
            case 'price_asc':
                $productsQuery->orderByRaw('COALESCE(sale_price, price) ASC');
                break;
            case 'price_desc':
                $productsQuery->orderByRaw('COALESCE(sale_price, price) DESC');
                break;
            case 'rating':
                $productsQuery->orderByDesc('rating');
                break;
            case 'popular':
                $productsQuery->orderByDesc('reviews_count');
                break;
            case 'title_asc':
                $productsQuery->orderBy('name', 'asc');
                break;
            case 'latest':
            default:
                $productsQuery->latest();
                break;
        }

        $results = $productsQuery->paginate(16)->withQueryString();

        // Data for sidebar filters
        $categories = Category::active()
            ->whereHas('products', function ($q) {
                $q->active();
            })
            ->withCount(['products' => function ($q) {
                $q->active();
            }])
            ->orderBy('name', 'asc')
            ->get();

        $brands = Brand::active()
            ->whereHas('products', function ($q) {
                $q->active();
            })
            ->withCount(['products' => function ($q) {
                $q->active();
            }])
            ->orderBy('name', 'asc')
            ->get();

        $priceBoundaries = [
            'min' => (int) Product::active()->min('price') ?: 0,
            'max' => (int) Product::active()->max('price') ?: 50000,
        ];

        return view('search-results', compact(
            'query',
            'results',
            'categories',
            'brands',
            'categoryId',
            'brandId',
            'productType',
            'minPrice',
            'maxPrice',
            'inStock',
            'featuredOnly',
            'hasDiscount',
            'sort',
            'priceBoundaries'
        ));
    }
}
