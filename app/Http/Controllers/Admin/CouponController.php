<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCouponRequest;
use App\Http\Requests\Admin\UpdateCouponRequest;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Coupon::withCount('usages')
            ->with(['products:id,name', 'categories:id,name']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('discount_type')) {
            $query->where('discount_type', $request->input('discount_type'));
        }

        $coupons = $query->latest('id')->paginate(12)->withQueryString();

        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $products = Product::select('id', 'name', 'product_type')->orderBy('name')->get();
        $categories = Category::select('id', 'name')->orderBy('name')->get();

        return view('admin.coupons.create', compact('products', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCouponRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $productIds = $data['product_ids'] ?? [];
        $categoryIds = $data['category_ids'] ?? [];
        unset($data['product_ids'], $data['category_ids']);

        $coupon = Coupon::create($data);

        if (! empty($productIds)) {
            $coupon->products()->sync($productIds);
        }

        if (! empty($categoryIds)) {
            $coupon->categories()->sync($categoryIds);
        }

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', "Coupon '{$coupon->code}' has been created successfully.");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon): View
    {
        $coupon->load(['products:id', 'categories:id']);
        $products = Product::select('id', 'name', 'product_type')->orderBy('name')->get();
        $categories = Category::select('id', 'name')->orderBy('name')->get();

        return view('admin.coupons.edit', compact('coupon', 'products', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCouponRequest $request, Coupon $coupon): RedirectResponse
    {
        $data = $request->validated();
        $productIds = $data['product_ids'] ?? [];
        $categoryIds = $data['category_ids'] ?? [];
        unset($data['product_ids'], $data['category_ids']);

        $coupon->update($data);

        $coupon->products()->sync($productIds);
        $coupon->categories()->sync($categoryIds);

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', "Coupon '{$coupon->code}' has been updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon): RedirectResponse
    {
        $code = $coupon->code;
        $coupon->delete();

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', "Coupon '{$code}' has been deleted successfully.");
    }
}
