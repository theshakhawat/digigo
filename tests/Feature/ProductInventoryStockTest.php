<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductInventoryStockTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->category = Category::factory()->create([
            'name' => 'Gadgets',
            'delivery_charge_inside_dhaka' => 60,
            'delivery_charge_outside_dhaka' => 120,
        ]);
    }

    public function test_purchasing_physical_product_deducts_stock_and_marks_sold_out(): void
    {
        // 1. Create a physical product with 1 in stock
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'Smart Fitness Band Pro',
            'slug' => 'smart-fitness-band-pro',
            'product_type' => 'physical',
            'price' => 1200,
            'stock_quantity' => 1,
            'low_stock_threshold' => 1,
            'status' => 'active',
        ]);

        $this->assertTrue($product->is_in_stock);
        $this->assertEquals(1, $product->stock_quantity);

        // Check product detail page shows in stock
        $detailResponse = $this->get(route('product.show', $product->slug));
        $detailResponse->assertOk();
        $detailResponse->assertSee('Low Stock (1 left)');
        $detailResponse->assertSee('Add to Cart');

        // Add 1 item to cart
        $addResponse = $this->postJson(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
        $addResponse->assertOk();
        $addResponse->assertJson(['success' => true]);

        // Trying to add another 1 to cart when only 1 is in stock must fail
        $secondAddResponse = $this->postJson(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
        $secondAddResponse->assertStatus(422);
        $secondAddResponse->assertJson(['success' => false]);

        // Place COD order
        $orderData = [
            'customer_name' => 'Jane Doe',
            'customer_email' => 'jane@example.com',
            'customer_phone' => '01711223344',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'street_address' => 'House 12, Road 4, Dhanmondi',
            'postal_code' => '1205',
            'payment_method' => 'cod',
        ];

        $checkoutResponse = $this->actingAs($this->user)->postJson(route('checkout.place'), $orderData);
        $checkoutResponse->assertOk();
        $checkoutResponse->assertJson(['success' => true]);

        // 2. Assert stock is deducted to 0
        $product->refresh();
        $this->assertEquals(0, $product->stock_quantity);
        $this->assertFalse($product->is_in_stock);

        // 3. Assert product detail now displays Out of Stock / Sold Out
        $outOfStockResponse = $this->get(route('product.show', $product->slug));
        $outOfStockResponse->assertOk();
        $outOfStockResponse->assertSee('Out of Stock');
        $outOfStockResponse->assertSee('Sold Out');
        $outOfStockResponse->assertSee('Product Currently Out of Stock!');

        // 4. Adding to cart now must be rejected
        $failedCartResponse = $this->postJson(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
        $failedCartResponse->assertStatus(422);
        $failedCartResponse->assertJson([
            'success' => false,
            'message' => 'Sorry, this product is currently out of stock!',
        ]);

        // 5. Assert homepage shows Sold Out badge and disabled button
        $homeResponse = $this->get(route('homepage'));
        $homeResponse->assertOk();
        $homeResponse->assertSee('Sold Out');
    }
}
