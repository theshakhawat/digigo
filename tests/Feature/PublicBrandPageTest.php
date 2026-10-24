<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicBrandPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_brand_page_loads_and_displays_its_products(): void
    {
        $brand = Brand::create([
            'name' => 'Apple',
            'slug' => 'apple',
            'status' => 'active',
        ]);

        $otherBrand = Brand::create([
            'name' => 'Sony',
            'slug' => 'sony',
            'status' => 'active',
        ]);

        $category = Category::create([
            'name' => 'Gadgets',
            'slug' => 'gadgets',
            'status' => 'active',
        ]);

        $appleProduct = Product::create([
            'name' => 'Apple AirPods Pro 2',
            'slug' => 'apple-airpods-pro-2',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'price' => 28000,
            'product_type' => 'physical',
            'status' => 'active',
        ]);

        $sonyProduct = Product::create([
            'name' => 'Sony WH-1000XM5',
            'slug' => 'sony-wh-1000xm5',
            'category_id' => $category->id,
            'brand_id' => $otherBrand->id,
            'price' => 38000,
            'product_type' => 'physical',
            'status' => 'active',
        ]);

        $response = $this->get(route('brand.show', $brand->slug));

        $response->assertStatus(200);
        $response->assertSee('Apple AirPods Pro 2');
        $response->assertDontSee('Sony WH-1000XM5');
        $response->assertSee('Official Brand Collection');
    }

    public function test_inactive_brand_returns_404(): void
    {
        $brand = Brand::create([
            'name' => 'Hidden Brand',
            'slug' => 'hidden-brand',
            'status' => 'inactive',
        ]);

        $response = $this->get(route('brand.show', $brand->slug));

        $response->assertStatus(404);
    }
}
