<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Show cart view.
     */
    public function index(): View
    {
        $cartItems = session('cart_items', []);

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        $appliedCoupon = session('applied_coupon');
        $discount = $appliedCoupon ? (float) $appliedCoupon['discount'] : 0.0;
        $total = max(0, $subtotal - $discount);

        return view('cart', compact('cartItems', 'subtotal', 'discount', 'total', 'appliedCoupon'));
    }

    /**
     * Add product to cart via AJAX.
     */
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'plan_id' => 'nullable|exists:product_plans,id',
            'quantity' => 'nullable|integer|min:1|max:50',
        ]);

        $product = Product::with(['category', 'brand'])->findOrFail($request->input('product_id'));
        $plan = $request->filled('plan_id') ? ProductPlan::find($request->input('plan_id')) : null;
        $quantity = (int) $request->input('quantity', 1);

        // Inventory Stock Check for Physical Products
        if ($product->isPhysical()) {
            $availableStock = (int) $product->stock_quantity;
            if ($availableStock <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, this product is currently out of stock!',
                ], 422);
            }

            $currentCartQty = session('cart_items.'.$product->id.'.quantity', 0);
            if (($currentCartQty + $quantity) > $availableStock) {
                return response()->json([
                    'success' => false,
                    'message' => "Only {$availableStock} item(s) available in stock.",
                ], 422);
            }
        }

        $cartKey = $plan ? "{$product->id}-{$plan->id}" : (string) $product->id;
        $price = $plan ? $plan->effective_price : $product->effective_price;
        $title = $plan ? "{$product->name} - {$plan->name}" : $product->name;

        $cart = session()->get('cart_items', []);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'id' => $product->id,
                'cart_key' => $cartKey,
                'plan_id' => $plan?->id,
                'title' => $title,
                'slug' => $product->slug,
                'category_id' => $product->category_id,
                'category' => $product->category->name ?? 'General',
                'price' => (float) $price,
                'quantity' => $quantity,
                'image' => $product->thumbnail_url ?: 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=400&q=80',
                'product_type' => $product->product_type,
            ];
        }

        session()->put('cart_items', $cart);

        $totalCount = array_sum(array_column($cart, 'quantity'));
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => $totalCount,
            'cart_subtotal' => $subtotal,
            'item' => $cart[$cartKey],
        ]);
    }

    /**
     * Update item quantity via AJAX.
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'cart_key' => 'required',
            'quantity' => 'required|integer|min:1|max:50',
        ]);

        $cartKey = $request->input('cart_key');
        $quantity = (int) $request->input('quantity');

        $cart = session()->get('cart_items', []);

        if (isset($cart[$cartKey])) {
            $productId = $cart[$cartKey]['product_id'] ?? $cart[$cartKey]['id'] ?? null;
            $productModel = $productId ? Product::find($productId) : null;
            if ($productModel && $productModel->isPhysical()) {
                $availableStock = (int) $productModel->stock_quantity;
                if ($quantity > $availableStock) {
                    return response()->json([
                        'success' => false,
                        'message' => "Only {$availableStock} item(s) available in stock.",
                    ], 422);
                }
            }

            $cart[$cartKey]['quantity'] = $quantity;
            session()->put('cart_items', $cart);
        }

        $totalCount = array_sum(array_column($cart, 'quantity'));
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully!',
            'cart_count' => $totalCount,
            'cart_subtotal' => $subtotal,
        ]);
    }

    /**
     * Remove item from cart via AJAX.
     */
    public function remove(Request $request): JsonResponse
    {
        $request->validate([
            'cart_key' => 'required',
        ]);

        $cartKey = $request->input('cart_key');
        $cart = session()->get('cart_items', []);

        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            session()->put('cart_items', $cart);
        }

        $totalCount = array_sum(array_column($cart, 'quantity'));
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart!',
            'cart_count' => $totalCount,
            'cart_subtotal' => $subtotal,
        ]);
    }

    /**
     * Clear entire cart via AJAX.
     */
    public function clear(): JsonResponse
    {
        session()->forget('cart_items');
        session()->forget('applied_coupon');

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully!',
            'cart_count' => 0,
            'cart_subtotal' => 0,
        ]);
    }
}
