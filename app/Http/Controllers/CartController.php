<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Show cart view.
     */
    public function index(): View
    {
        $cartItems = [
            [
                'id' => 1,
                'title' => 'ChatGPT Plus 1 Month Private Account',
                'slug' => 'chatgpt-plus-subscription',
                'category' => 'Artificial Intelligence',
                'price' => 1450,
                'quantity' => 1,
                'image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'id' => 2,
                'title' => 'Canva Pro 1 Year Subscription',
                'slug' => 'canva-pro-activation',
                'category' => 'Design & Graphics',
                'price' => 299,
                'quantity' => 2,
                'image' => 'https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=400&q=80',
            ],
        ];

        $subtotal = 1450 + (299 * 2);
        $discount = 100;
        $total = $subtotal - $discount;

        return view('cart', compact('cartItems', 'subtotal', 'discount', 'total'));
    }
}
