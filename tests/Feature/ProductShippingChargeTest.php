<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\ShippingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductShippingChargeTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);
    }

    public function test_can_create_physical_product_with_custom_shipping_charge(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name' => 'Mechanical Gaming Keyboard',
            'product_type' => 'physical',
            'category_id' => $category->id,
            'price' => 2500,
            'status' => 'active',
            'stock_quantity' => 10,
            'shipping_charge' => 45.00,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('products', [
            'name' => 'Mechanical Gaming Keyboard',
            'shipping_charge' => 45.00,
        ]);
    }

    public function test_checkout_shipping_calculation_uses_product_defined_shipping_charge(): void
    {
        $smallItem = Product::factory()->create([
            'name' => 'Small USB Cable',
            'product_type' => 'physical',
            'price' => 150,
            'shipping_charge' => 20.00,
        ]);

        $largeItem = Product::factory()->create([
            'name' => 'Big Gaming Chair',
            'product_type' => 'physical',
            'price' => 12000,
            'shipping_charge' => 150.00,
        ]);

        $cart = [
            [
                'id' => $smallItem->id,
                'product_id' => $smallItem->id,
                'price' => 150,
                'quantity' => 1,
                'product_type' => 'physical',
            ],
            [
                'id' => $largeItem->id,
                'product_id' => $largeItem->id,
                'price' => 12000,
                'quantity' => 1,
                'product_type' => 'physical',
            ],
        ];

        // Region delivery charge (inside Dhaka default = 60) + Product shipping charges (20 + 150 = 170) -> Total = 230
        $result = ShippingService::calculate($cart, 'Dhaka', 'Dhaka');
        $this->assertEquals(60.00, $result['delivery_charge']);
        $this->assertEquals(170.00, $result['shipping_charge']);
        $this->assertEquals(230.00, $result['charge']);
    }

    public function test_digital_product_has_zero_shipping_charge(): void
    {
        $digitalItem = Product::factory()->create([
            'name' => 'ChatGPT Plus Key',
            'product_type' => 'digital',
            'price' => 2450,
            'shipping_charge' => 0.00,
        ]);

        $cart = [
            [
                'id' => $digitalItem->id,
                'product_id' => $digitalItem->id,
                'price' => 2450,
                'quantity' => 1,
                'product_type' => 'digital',
            ],
        ];

        $result = ShippingService::calculate($cart, 'Dhaka', 'Dhaka');
        $this->assertEquals(0.00, $result['charge']);
        $this->assertTrue($result['is_free']);
    }
}
