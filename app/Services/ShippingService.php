<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;

class ShippingService
{
    /**
     * List of Bangladesh divisions and their districts.
     *
     * @var array<string, array<int, string>>
     */
    public const BANGLADESH_LOCATIONS = [
        'Dhaka' => [
            'Dhaka', 'Gazipur', 'Narayanganj', 'Tangail', 'Faridpur', 'Manikganj',
            'Munshiganj', 'Narsingdi', 'Kishoreganj', 'Gopalganj', 'Madaripur',
            'Rajbari', 'Shariatpur',
        ],
        'Chattogram' => [
            'Chattogram', 'Cox\'s Bazar', 'Cumilla', 'Feni', 'Brahmanbaria',
            'Rangamati', 'Noakhali', 'Chandpur', 'Lakshmipur', 'Khagrachhari', 'Bandarban',
        ],
        'Rajshahi' => [
            'Rajshahi', 'Bogura', 'Pabna', 'Sirajganj', 'Naogaon', 'Natore',
            'Chapai Nawabganj', 'Joypurhat',
        ],
        'Khulna' => [
            'Khulna', 'Jashore', 'Kushtia', 'Satkhira', 'Jhenaidah', 'Bagerhat',
            'Chuadanga', 'Magura', 'Meherpur', 'Narail',
        ],
        'Barishal' => [
            'Barishal', 'Patuakhali', 'Bhola', 'Pirojpur', 'Barguna', 'Jhalokati',
        ],
        'Sylhet' => [
            'Sylhet', 'Moulvibazar', 'Habiganj', 'Sunamganj',
        ],
        'Rangpur' => [
            'Rangpur', 'Dinajpur', 'Kurigram', 'Gaibandha', 'Nilphamari',
            'Panchagarh', 'Thakurgaon', 'Lalmonirhat',
        ],
        'Mymensingh' => [
            'Mymensingh', 'Jamalpur', 'Netrokona', 'Sherpur',
        ],
    ];

    /**
     * Check if a given division or district belongs to "Inside Dhaka".
     */
    public static function isInsideDhaka(?string $division, ?string $district): bool
    {
        $normalizedDistrict = strtolower(trim((string) $district));
        $normalizedDivision = strtolower(trim((string) $division));

        // Exact Dhaka city/district match
        if ($normalizedDistrict === 'dhaka' || $normalizedDistrict === 'dhaka city') {
            return true;
        }

        // Check if user specifically passed 'inside_dhaka'
        if ($normalizedDistrict === 'inside_dhaka' || $normalizedDivision === 'inside_dhaka') {
            return true;
        }

        return false;
    }

    /**
     * Calculate delivery charge (region-based flat delivery) and per-product shipping charges based on destination location and cart items.
     *
     * @param  array<int, array<string, mixed>>  $cartItems
     * @return array{
     *     charge: float,
     *     delivery_charge: float,
     *     shipping_charge: float,
     *     is_inside_dhaka: bool,
     *     label: string,
     *     is_free: bool,
     *     has_physical: bool
     * }
     */
    public static function calculate(array $cartItems, ?string $division, ?string $district): array
    {
        $isInside = self::isInsideDhaka($division, $district);

        // Global default delivery charges from settings (Admin panel > Shipping Settings)
        // Default: 60 tk inside Dhaka, 100 tk outside Dhaka
        $defaultInside = (float) Setting::get('shipping_default_inside_dhaka', 60.00);
        $defaultOutside = (float) Setting::get('shipping_default_outside_dhaka', 100.00);

        // Check if free shipping threshold is active
        $freeShippingThreshold = Setting::get('shipping_free_threshold');
        $hasFreeThreshold = is_numeric($freeShippingThreshold) && (float) $freeShippingThreshold > 0;

        $hasPhysicalItems = false;
        $totalProductShippingCharge = 0.0;
        $subtotal = 0.0;

        foreach ($cartItems as $item) {
            $price = (float) ($item['price'] ?? 0);
            $qty = (int) ($item['quantity'] ?? 1);
            $subtotal += ($price * $qty);

            // Determine if item is physical or digital
            $productId = $item['product_id'] ?? ($item['id'] ?? null);
            $isPhysical = false;
            $prod = null;

            if ($productId) {
                $prod = Product::find($productId);
            }

            if (isset($item['product_type'])) {
                $isPhysical = $item['product_type'] === 'physical';
            } elseif ($prod && $prod->isPhysical()) {
                $isPhysical = true;
            }

            if ($isPhysical) {
                $hasPhysicalItems = true;

                // Per-product shipping charge:
                // Check if product has explicit shipping_charge defined
                if ($prod && $prod->shipping_charge !== null && is_numeric($prod->shipping_charge)) {
                    $itemShipping = (float) $prod->shipping_charge;
                    $totalProductShippingCharge += ($itemShipping * $qty);
                }
            }
        }

        // If order contains only digital goods, both delivery charge and shipping charge are 0
        if (! $hasPhysicalItems) {
            return [
                'charge' => 0.0,
                'delivery_charge' => 0.0,
                'shipping_charge' => 0.0,
                'is_inside_dhaka' => $isInside,
                'label' => 'FREE (Digital Delivery)',
                'is_free' => true,
                'has_physical' => false,
            ];
        }

        // Delivery charge based on Region (Inside vs Outside Dhaka) from Admin Panel Settings
        $deliveryCharge = $isInside ? $defaultInside : $defaultOutside;

        // Check Category-specific location rate override if present for physical category in cart
        foreach ($cartItems as $item) {
            $categoryId = $item['category_id'] ?? null;
            if ($categoryId) {
                $cat = Category::find($categoryId);
                if ($cat) {
                    $catLocationRate = $isInside ? $cat->delivery_charge_inside_dhaka : $cat->delivery_charge_outside_dhaka;
                    // Only override if category rate is set and greater than 0
                    if ($catLocationRate !== null && is_numeric($catLocationRate) && (float) $catLocationRate > 0) {
                        $deliveryCharge = (float) $catLocationRate;
                        break;
                    }
                }
            }
        }

        // Free delivery condition
        $isFree = false;
        if ($hasFreeThreshold && $subtotal >= (float) $freeShippingThreshold) {
            $deliveryCharge = 0.0;
            $isFree = true;
        }

        $totalCombinedCharge = $deliveryCharge + $totalProductShippingCharge;
        $label = $isInside
            ? 'Inside Dhaka (৳'.number_format($deliveryCharge).')'
            : 'Outside Dhaka (৳'.number_format($deliveryCharge).')';

        return [
            'charge' => round($totalCombinedCharge, 2),
            'delivery_charge' => round($deliveryCharge, 2),
            'shipping_charge' => round($totalProductShippingCharge, 2),
            'is_inside_dhaka' => $isInside,
            'label' => $label,
            'is_free' => $isFree,
            'has_physical' => true,
        ];
    }
}
