<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'product_type' => 'physical',
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'thumbnail' => null,
            'gallery' => null,
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraphs(2, true),
            'features' => ['100% Genuine', 'Official Warranty'],
            'price' => fake()->randomFloat(2, 500, 5000),
            'sale_price' => null,
            'cost_price' => fake()->randomFloat(2, 200, 2000),
            'status' => 'active',
            'is_featured' => false,
            'rating' => 5.00,
            'reviews_count' => fake()->numberBetween(0, 50),
            'sku' => strtoupper(fake()->lexify('DG-???-###')),
            'stock_quantity' => fake()->numberBetween(10, 100),
            'low_stock_threshold' => 5,
            'weight' => '250g',
            'dimensions' => '10x5x2 cm',
            'warranty_period' => '1 Year Warranty',
            'package_contents' => '1x Device, 1x Manual',
        ];
    }

    /**
     * Digital product state.
     */
    public function digital(): static
    {
        return $this->state(fn (array $attributes) => [
            'product_type' => 'digital',
            'sku' => null,
            'stock_quantity' => 0,
            'weight' => null,
            'dimensions' => null,
            'digital_delivery_type' => 'credentials',
            'access_instructions' => 'Credentials delivered to email.',
            'default_validity' => '1 Month',
            'has_plans' => false,
        ]);
    }
}
