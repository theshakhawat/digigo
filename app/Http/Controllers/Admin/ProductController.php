<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of products with filtering.
     */
    public function index(Request $request): View
    {
        $query = Product::query()->with(['category', 'brand', 'plans']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($productType = $request->input('product_type')) {
            $query->where('product_type', $productType);
        }

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($brandId = $request->input('brand_id')) {
            $query->where('brand_id', $brandId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->boolean('is_featured'));
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        // Count metrics for header cards
        $metrics = [
            'total' => Product::count(),
            'physical' => Product::where('product_type', 'physical')->count(),
            'digital' => Product::where('product_type', 'digital')->count(),
            'low_stock' => Product::where('product_type', 'physical')->whereColumn('stock_quantity', '<=', 'low_stock_threshold')->count(),
        ];

        return view('admin.products.index', compact('products', 'categories', 'brands', 'metrics'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('admin.products.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['has_plans'] = $request->boolean('has_plans');

        // Generate unique slug
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        $baseSlug = $validated['slug'];
        $count = 1;
        while (Product::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = "{$baseSlug}-{$count}";
            $count++;
        }

        // Clean features array
        if (! empty($validated['features']) && is_array($validated['features'])) {
            $validated['features'] = array_values(array_filter(array_map('trim', $validated['features'])));
        } else {
            $validated['features'] = null;
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('products/thumbnails', 'public');
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery')) {
            $galleryPaths = [];
            foreach ($request->file('gallery') as $image) {
                $galleryPaths[] = $image->store('products/gallery', 'public');
            }
            $validated['gallery'] = $galleryPaths;
        } else {
            $validated['gallery'] = null;
        }

        // Handle digital file upload
        if ($request->hasFile('download_file')) {
            $validated['download_file'] = $request->file('download_file')->store('products/digital_files', 'public');
        }

        $plansData = $request->input('plans', []);

        DB::transaction(function () use ($validated, $plansData) {
            $product = Product::create($validated);

            // If digital product with multiple subscription plans
            if ($product->isDigital() && $product->has_plans && ! empty($plansData) && is_array($plansData)) {
                $sort = 0;
                foreach ($plansData as $plan) {
                    if (empty($plan['name']) || ! isset($plan['price'])) {
                        continue;
                    }
                    ProductPlan::create([
                        'product_id' => $product->id,
                        'name' => $plan['name'],
                        'validity' => $plan['validity'] ?? null,
                        'price' => $plan['price'],
                        'sale_price' => ! empty($plan['sale_price']) ? $plan['sale_price'] : null,
                        'stock_quantity' => $plan['stock_quantity'] ?? 0,
                        'sku' => $plan['sku'] ?? null,
                        'is_default' => ! empty($plan['is_default']),
                        'sort_order' => $sort++,
                    ]);
                }
            }
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $product->load('plans');
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['has_plans'] = $request->boolean('has_plans');

        // Generate unique slug except current
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        $baseSlug = $validated['slug'];
        $count = 1;
        while (Product::where('slug', $validated['slug'])->where('id', '!=', $product->id)->exists()) {
            $validated['slug'] = "{$baseSlug}-{$count}";
            $count++;
        }

        // Clean features array
        if (! empty($validated['features']) && is_array($validated['features'])) {
            $validated['features'] = array_values(array_filter(array_map('trim', $validated['features'])));
        } else {
            $validated['features'] = null;
        }

        // Handle thumbnail update
        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('products/thumbnails', 'public');
        }

        // Handle gallery updates & removals
        $existingGallery = is_array($product->gallery) ? $product->gallery : [];
        if ($request->filled('remove_gallery_images')) {
            $toRemove = (array) $request->input('remove_gallery_images');
            foreach ($toRemove as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
            $existingGallery = array_values(array_diff($existingGallery, $toRemove));
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $existingGallery[] = $image->store('products/gallery', 'public');
            }
        }
        $validated['gallery'] = ! empty($existingGallery) ? $existingGallery : null;

        // Handle digital download file update
        if ($request->hasFile('download_file')) {
            if ($product->download_file && Storage::disk('public')->exists($product->download_file)) {
                Storage::disk('public')->delete($product->download_file);
            }
            $validated['download_file'] = $request->file('download_file')->store('products/digital_files', 'public');
        }

        $plansData = $request->input('plans', []);

        DB::transaction(function () use ($product, $validated, $plansData) {
            $product->update($validated);

            // Sync subscription plans
            if ($product->isDigital() && $product->has_plans) {
                $keptPlanIds = [];
                $sort = 0;

                foreach ($plansData as $plan) {
                    if (empty($plan['name']) || ! isset($plan['price'])) {
                        continue;
                    }

                    $planAttributes = [
                        'name' => $plan['name'],
                        'validity' => $plan['validity'] ?? null,
                        'price' => $plan['price'],
                        'sale_price' => ! empty($plan['sale_price']) ? $plan['sale_price'] : null,
                        'stock_quantity' => $plan['stock_quantity'] ?? 0,
                        'sku' => $plan['sku'] ?? null,
                        'is_default' => ! empty($plan['is_default']),
                        'sort_order' => $sort++,
                    ];

                    if (! empty($plan['id'])) {
                        $existingPlan = ProductPlan::where('product_id', $product->id)->where('id', $plan['id'])->first();
                        if ($existingPlan) {
                            $existingPlan->update($planAttributes);
                            $keptPlanIds[] = $existingPlan->id;

                            continue;
                        }
                    }

                    $newPlan = $product->plans()->create($planAttributes);
                    $keptPlanIds[] = $newPlan->id;
                }

                // Delete plans that were removed by admin
                $product->plans()->whereNotIn('id', $keptPlanIds)->delete();
            } else {
                // If has_plans was toggled off or product type is physical, remove any plans
                $product->plans()->delete();
            }
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        // Delete thumbnail
        if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        // Delete gallery images
        if (is_array($product->gallery)) {
            foreach ($product->gallery as $galleryImage) {
                if (Storage::disk('public')->exists($galleryImage)) {
                    Storage::disk('public')->delete($galleryImage);
                }
            }
        }

        // Delete downloadable file
        if ($product->download_file && Storage::disk('public')->exists($product->download_file)) {
            Storage::disk('public')->delete($product->download_file);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Export products catalog to CSV / Excel compatible format.
     */
    public function export(Request $request): StreamedResponse
    {
        $query = Product::query()->with(['category', 'brand', 'plans']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($productType = $request->input('product_type')) {
            $query->where('product_type', $productType);
        }

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($brandId = $request->input('brand_id')) {
            $query->where('brand_id', $brandId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $products = $query->latest()->get();

        $filename = 'products_catalog_'.now()->format('Y_m_d_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($products) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM for Microsoft Excel
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'ID',
                'Product Name',
                'Slug',
                'SKU',
                'Type',
                'Category',
                'Brand',
                'Regular Price (BDT)',
                'Sale Price (BDT)',
                'Effective Price (BDT)',
                'Stock Quantity',
                'Low Stock Alert Threshold',
                'Status',
                'Is Featured',
                'Has Plans',
                'Plans Count',
                'Plans List',
                'Digital Delivery Type',
                'Validity',
                'Rating',
                'Reviews Count',
                'Created At',
            ]);

            foreach ($products as $prod) {
                $plansList = $prod->plans->map(function ($plan) {
                    return "{$plan->name} (BDT {$plan->price})";
                })->join(' | ');

                fputcsv($handle, [
                    $prod->id,
                    $prod->name,
                    $prod->slug,
                    $prod->sku ?? 'N/A',
                    strtoupper($prod->product_type),
                    $prod->category->name ?? 'Uncategorized',
                    $prod->brand->name ?? 'N/A',
                    $prod->price,
                    $prod->sale_price ?? 'N/A',
                    $prod->effective_price,
                    $prod->stock_quantity ?? 0,
                    $prod->low_stock_threshold ?? 5,
                    strtoupper($prod->status),
                    $prod->is_featured ? 'Yes' : 'No',
                    $prod->has_plans ? 'Yes' : 'No',
                    $prod->plans->count(),
                    $plansList ?: 'N/A',
                    $prod->digital_delivery_type ? str_replace('_', ' ', $prod->digital_delivery_type) : 'N/A',
                    $prod->default_validity ?? 'N/A',
                    $prod->rating ?? 0,
                    $prod->reviews_count ?? 0,
                    $prod->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
