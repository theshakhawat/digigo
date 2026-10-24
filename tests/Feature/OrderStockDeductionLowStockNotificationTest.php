<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderStockDeductionLowStockNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirming_order_deducts_product_stock_and_alerts_admin_on_low_stock(): void
    {
        $category = Category::factory()->create();

        // Product with stock 6, threshold 5
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Logitech Wireless Mouse',
            'product_type' => 'physical',
            'price' => 1200,
            'stock_quantity' => 6,
            'low_stock_threshold' => 5,
            'status' => 'active',
        ]);

        $customer = User::factory()->create([
            'role' => 'user',
        ]);

        // Place a COD order (which is confirmed immediately)
        $orderData = [
            'customer_name' => 'Shopper One',
            'customer_email' => 'shopper@example.com',
            'customer_phone' => '01712345678',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'street_address' => 'Mirpur-10, Dhaka',
            'payment_method' => 'cod',
            'cart_items' => [
                [
                    'product_id' => $product->id,
                    'title' => $product->name,
                    'price' => 1200,
                    'quantity' => 2,
                    'product_type' => 'physical',
                ],
            ],
        ];

        $response = $this->actingAs($customer)
            ->withSession(['cart_items' => $orderData['cart_items']])
            ->post('/checkout/place-order', $orderData);

        $response->assertRedirect();

        // 1. Check stock is deducted by 2 (6 - 2 = 4)
        $product->refresh();
        $this->assertEquals(4, $product->stock_quantity);

        // 2. Check AdminNotification is created because 4 <= 5 (low stock alert)
        $this->assertDatabaseHas('admin_notifications', [
            'type' => 'product',
            'title' => "Low Stock Alert: {$product->name}",
            'is_read' => false,
        ]);
    }

    public function test_stock_depletion_to_zero_creates_out_of_stock_admin_alert(): void
    {
        $category = Category::factory()->create();

        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Limited Gaming Headset',
            'product_type' => 'physical',
            'stock_quantity' => 2,
            'low_stock_threshold' => 5,
            'status' => 'active',
        ]);

        // Directly call deductStock by 2 units
        $product->deductStock(2);

        $product->refresh();
        $this->assertEquals(0, $product->stock_quantity);

        // Check Out of stock alert
        $this->assertDatabaseHas('admin_notifications', [
            'type' => 'product',
            'title' => "Out of Stock Alert: {$product->name}",
            'is_read' => false,
        ]);
    }
}
