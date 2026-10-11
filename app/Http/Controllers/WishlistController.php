<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class WishlistController extends Controller
{
    /**
     * Show user wishlist page.
     */
    public function index(): View
    {
        $wishlistItems = [
            [
                'id' => 1,
                'title' => 'ChatGPT Plus 1 Month Private Account',
                'slug' => 'chatgpt-plus-subscription',
                'price' => 1450,
                'old_price' => 2200,
                'stock' => 'In Stock',
                'image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'id' => 2,
                'title' => 'Perplexity AI Pro 1 Year Subscription',
                'slug' => 'perplexity-ai-pro',
                'price' => 1850,
                'old_price' => 3500,
                'stock' => 'In Stock',
                'image' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?auto=format&fit=crop&w=400&q=80',
            ],
        ];

        return view('wishlist', compact('wishlistItems'));
    }
}
