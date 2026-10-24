<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartWishlistAjaxTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_add_product_to_cart_via_ajax(): void
    {
        $category = Category::create([
            'name' => 'Software',
            'slug' => 'software',
            'status' => 'active',
        ]);

        $product = Product::create([
            'name' => 'Canva Pro',
            'slug' => 'canva-pro',
            'category_id' => $category->id,
            'price' => 500,
            'product_type' => 'digital',
            'status' => 'active',
        ]);

        $response = $this->postJson(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'cart_count' => 2,
            'cart_subtotal' => 1000,
        ]);

        $this->assertEquals(2, session('cart_items')[$product->id]['quantity']);
    }

    public function test_can_toggle_wishlist_via_ajax(): void
    {
        $category = Category::create([
            'name' => 'AI Tools',
            'slug' => 'ai-tools',
            'status' => 'active',
        ]);

        $product = Product::create([
            'name' => 'ChatGPT Plus',
            'slug' => 'chatgpt-plus',
            'category_id' => $category->id,
            'price' => 1500,
            'product_type' => 'digital',
            'status' => 'active',
        ]);

        // First toggle: Add to wishlist
        $res1 = $this->postJson(route('wishlist.toggle'), [
            'product_id' => $product->id,
        ]);

        $res1->assertStatus(200);
        $res1->assertJson([
            'success' => true,
            'added' => true,
            'wishlist_count' => 1,
        ]);

        // Second toggle: Remove from wishlist
        $res2 = $this->postJson(route('wishlist.toggle'), [
            'product_id' => $product->id,
        ]);

        $res2->assertStatus(200);
        $res2->assertJson([
            'success' => true,
            'added' => false,
            'wishlist_count' => 0,
        ]);
    }
}
