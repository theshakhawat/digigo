<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Show checkout page.
     */
    public function index(): View
    {
        $orderSummary = [
            'items_count' => 3,
            'subtotal' => 2048,
            'discount' => 100,
            'total' => 1948,
        ];

        return view('checkout', compact('orderSummary'));
    }
}
