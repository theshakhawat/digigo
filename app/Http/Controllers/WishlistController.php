<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    /**
     * Show user wishlist page.
     */
    public function index(): View
    {
        $wishlist = session('wishlist_items', []);

        // Fetch fresh product models to reflect updated prices/stock
        $productIds = array_keys($wishlist);
        $products = Product::whereIn('id', $productIds)->active()->get()->keyBy('id');

        $wishlistItems = [];
        foreach ($wishlist as $id => $item) {
            $fresh = $products->get($id);
            $wishlistItems[] = [
                'id' => $id,
                'title' => $fresh ? $fresh->name : $item['title'],
                'slug' => $fresh ? $fresh->slug : $item['slug'],
                'price' => $fresh ? $fresh->effective_price : $item['price'],
                'old_price' => $fresh && $fresh->sale_price ? $fresh->price : ($item['old_price'] ?? null),
                'stock' => $fresh ? $fresh->stock_status_label : 'In Stock',
                'image' => $fresh && $fresh->thumbnail_url ? $fresh->thumbnail_url : $item['image'],
            ];
        }

        return view('wishlist', compact('wishlistItems'));
    }

    /**
     * Toggle product in wishlist via AJAX.
     */
    public function toggle(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = (int) $request->input('product_id');
        $product = Product::findOrFail($productId);
        $wishlist = session()->get('wishlist_items', []);

        if (isset($wishlist[$productId])) {
            unset($wishlist[$productId]);
            $added = false;
            $message = 'Removed from your wishlist!';
        } else {
            $wishlist[$productId] = [
                'id' => $product->id,
                'title' => $product->name,
                'slug' => $product->slug,
                'price' => (float) $product->effective_price,
                'old_price' => $product->sale_price ? (float) $product->price : null,
                'image' => $product->thumbnail_url ?: 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=400&q=80',
            ];
            $added = true;
            $message = 'Added to your wishlist!';
        }

        session()->put('wishlist_items', $wishlist);

        return response()->json([
            'success' => true,
            'added' => $added,
            'message' => $message,
            'wishlist_count' => count($wishlist),
        ]);
    }

    /**
     * Remove item from wishlist via AJAX.
     */
    public function remove(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required',
        ]);

        $productId = (int) $request->input('product_id');
        $wishlist = session()->get('wishlist_items', []);

        if (isset($wishlist[$productId])) {
            unset($wishlist[$productId]);
            session()->put('wishlist_items', $wishlist);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item removed from wishlist!',
            'wishlist_count' => count($wishlist),
        ]);
    }
}
