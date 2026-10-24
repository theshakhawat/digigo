<?php

namespace App\Http\Controllers;

use App\Services\ShippingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    /**
     * Get list of Bangladesh divisions and districts.
     */
    public function locations(): JsonResponse
    {
        return response()->json([
            'divisions' => array_keys(ShippingService::BANGLADESH_LOCATIONS),
            'locations' => ShippingService::BANGLADESH_LOCATIONS,
        ]);
    }

    /**
     * Calculate delivery charge for a given destination.
     */
    public function calculate(Request $request): JsonResponse
    {
        $division = $request->input('division');
        $district = $request->input('district');

        $cartItems = session('cart_items', [
            [
                'id' => 1,
                'title' => 'ChatGPT Plus 1 Month Private Account',
                'product_type' => 'digital',
                'price' => 1450,
                'quantity' => 1,
            ],
            [
                'id' => 2,
                'title' => 'Canva Pro 1 Year Subscription',
                'product_type' => 'digital',
                'price' => 299,
                'quantity' => 2,
            ],
        ]);

        $result = ShippingService::calculate($cartItems, $division, $district);

        // Store selected shipping location in session
        session([
            'shipping_destination' => [
                'division' => $division,
                'district' => $district,
                'charge' => $result['charge'],
                'label' => $result['label'],
                'is_inside_dhaka' => $result['is_inside_dhaka'],
            ],
        ]);

        return response()->json([
            'success' => true,
            'is_inside_dhaka' => $result['is_inside_dhaka'],
            'charge' => $result['charge'],
            'charge_formatted' => '৳'.number_format($result['charge'], 2),
            'label' => $result['label'],
            'is_free' => $result['is_free'],
            'has_physical' => $result['has_physical'],
        ]);
    }
}
