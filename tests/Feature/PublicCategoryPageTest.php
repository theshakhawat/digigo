<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicCategoryPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_shows_active_categories(): void
    {
        $activeCat = Category::create([
            'name' => 'Streaming Services',
            'slug' => 'streaming-services',
            'status' => 'active',
            'is_featured' => true,
        ]);

        $inactiveCat = Category::create([
            'name' => 'Hidden Secret Cat',
            'slug' => 'hidden-secret-cat',
            'status' => 'inactive',
            'is_featured' => false,
        ]);

        $response = $this->get(route('homepage'));
        $response->assertOk();
        $response->assertSee('Streaming Services');
        $response->assertDontSee('Hidden Secret Cat');
    }

    public function test_category_page_displays_its_products(): void
    {
        $category = Category::create([
            'name' => 'Cloud Storage',
            'slug' => 'cloud-storage',
            'status' => 'active',
        ]);

        $product1 = Product::factory()->create([
            'name' => 'Google One 2TB Cloud Storage',
            'slug' => 'google-one-2tb',
            'category_id' => $category->id,
            'status' => 'active',
        ]);

        $otherCat = Category::create([
            'name' => 'Gaming Cards',
            'slug' => 'gaming-cards',
            'status' => 'active',
        ]);

        $product2 = Product::factory()->create([
            'name' => 'Steam Wallet Card $50',
            'slug' => 'steam-wallet-50',
            'category_id' => $otherCat->id,
            'status' => 'active',
        ]);

        $response = $this->get(route('category.show', $category->slug));
        $response->assertOk();
        $response->assertSee('Cloud Storage');
        $response->assertSee('Google One 2TB Cloud Storage');
        $response->assertDontSee('Steam Wallet Card $50');
    }

    public function test_inactive_category_returns_404(): void
    {
        $inactiveCat = Category::create([
            'name' => 'Archived Cat',
            'slug' => 'archived-cat',
            'status' => 'inactive',
        ]);

        $response = $this->get(route('category.show', $inactiveCat->slug));
        $response->assertNotFound();
    }
}
