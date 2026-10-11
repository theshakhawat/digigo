<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Show single product details page.
     */
    public function show(string $slug = 'chatgpt-plus-subscription'): View
    {
        $product = [
            'title' => 'ChatGPT Plus 1 Month Private Account / Shared Access',
            'slug' => $slug,
            'category' => 'Artificial Intelligence (AI)',
            'rating' => 4.9,
            'reviews_count' => 128,
            'price' => 1450,
            'old_price' => 2200,
            'discount' => '34% OFF',
            'stock_status' => 'In Stock (Instant Delivery)',
            'image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=800&q=80',
            'features' => [
                'Full access to GPT-4o, DALL·E 3, and Web Browsing',
                'Advanced Data Analysis & Custom GPTs Access',
                'Private mail credentials or instant activation on your email',
                '1 Month full replacement warranty & 24/7 dedicated support',
            ],
            'description' => 'Supercharge your productivity with ChatGPT Plus. Access OpenAI’s most capable model, create custom GPT agents, generate high-definition visual assets, and browse the live web seamlessly with ultra-fast response times.',
        ];

        return view('product-detail', compact('product'));
    }

    /**
     * Search results page.
     */
    public function search(Request $request): View
    {
        $query = $request->query('q', '');

        // Sample search results
        $results = [
            [
                'title' => 'ChatGPT Plus Subscription (1 Month)',
                'slug' => 'chatgpt-plus-subscription',
                'category' => 'Artificial Intelligence (AI)',
                'price' => 1450,
                'old_price' => 2200,
                'rating' => 4.9,
                'image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'title' => 'Canva Pro Lifetime / 1 Year Activation',
                'slug' => 'canva-pro-activation',
                'category' => 'Office & Productivity',
                'price' => 299,
                'old_price' => 750,
                'rating' => 4.8,
                'image' => 'https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'title' => 'Perplexity AI Pro 1 Year Subscription',
                'slug' => 'perplexity-ai-pro',
                'category' => 'Artificial Intelligence (AI)',
                'price' => 1850,
                'old_price' => 3500,
                'rating' => 5.0,
                'image' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'title' => 'Surfshark VPN 1 Year Dedicated Key',
                'slug' => 'surfshark-vpn-1-year',
                'category' => 'Privacy & Security',
                'price' => 1250,
                'old_price' => 2800,
                'rating' => 4.9,
                'image' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&w=600&q=80',
            ],
        ];

        return view('search-results', compact('query', 'results'));
    }
}
