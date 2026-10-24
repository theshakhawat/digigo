<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProductCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->user = User::factory()->create([
            'role' => 'user',
            'status' => 'active',
        ]);
    }

    public function test_guests_cannot_access_product_management(): void
    {
        $response = $this->get(route('admin.products.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_regular_users_cannot_access_product_management(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.products.index'));
        $response->assertRedirect(route('user.dashboard'));
    }

    public function test_admin_can_view_products_list_and_metrics(): void
    {
        Product::factory()->create([
            'name' => 'Fast Charger 65W',
            'product_type' => 'physical',
        ]);
        Product::factory()->digital()->create([
            'name' => 'Prime Video Subscription',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.products.index'));

        $response->assertStatus(200);
        $response->assertSee('Products Catalog');
        $response->assertSee('Fast Charger 65W');
        $response->assertSee('Prime Video Subscription');
    }

    public function test_admin_can_filter_products_by_type(): void
    {
        Product::factory()->create([
            'name' => 'Physical Earbuds',
            'product_type' => 'physical',
        ]);
        Product::factory()->digital()->create([
            'name' => 'Digital Netflix Sub',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.products.index', ['product_type' => 'physical']));
        $response->assertStatus(200);
        $response->assertSee('Physical Earbuds');
        $response->assertDontSee('Digital Netflix Sub');
    }

    public function test_admin_can_view_create_product_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.products.create'));
        $response->assertStatus(200);
        $response->assertSee('Add New Product');
        $response->assertSee('Physical Product');
        $response->assertSee('Digital Product / Subscription');
    }

    public function test_admin_can_create_physical_product(): void
    {
        Storage::fake('public');
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();

        $payload = [
            'name' => 'Baseus 65W GaN Charger',
            'slug' => 'baseus-65w-gan-charger',
            'product_type' => 'physical',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'short_description' => 'Fast charging power adapter',
            'description' => 'Full description of charger',
            'features' => ['Fast charging', 'GaN technology'],
            'price' => 1950.00,
            'sale_price' => 1650.00,
            'cost_price' => 1100.00,
            'status' => 'active',
            'is_featured' => true,
            'sku' => 'BS-65W-GAN',
            'stock_quantity' => 25,
            'low_stock_threshold' => 5,
            'weight' => '120g',
            'warranty_period' => '1 Year Replacement',
            'package_contents' => '1x Charger, 1x Manual',
            'thumbnail' => UploadedFile::fake()->image('charger.jpg'),
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), $payload);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => 'Baseus 65W GaN Charger',
            'slug' => 'baseus-65w-gan-charger',
            'product_type' => 'physical',
            'sku' => 'BS-65W-GAN',
            'stock_quantity' => 25,
            'warranty_period' => '1 Year Replacement',
        ]);

        $product = Product::where('slug', 'baseus-65w-gan-charger')->first();
        $this->assertNotNull($product->thumbnail);
        Storage::disk('public')->assertExists($product->thumbnail);
    }

    public function test_admin_can_create_digital_product_with_subscription_plans(): void
    {
        $category = Category::factory()->create();

        $payload = [
            'name' => 'YouTube Premium Family Plan',
            'slug' => 'youtube-premium-family',
            'product_type' => 'digital',
            'category_id' => $category->id,
            'digital_delivery_type' => 'direct_activation',
            'access_instructions' => 'Invitation link will be emailed to you.',
            'default_validity' => '1 Month',
            'has_plans' => true,
            'price' => 180.00,
            'sale_price' => 140.00,
            'status' => 'active',
            'plans' => [
                [
                    'name' => '1 Month Plan',
                    'validity' => '1 Month',
                    'price' => 180.00,
                    'sale_price' => 140.00,
                    'stock_quantity' => 100,
                    'is_default' => 1,
                ],
                [
                    'name' => '6 Months Plan',
                    'validity' => '6 Months',
                    'price' => 1000.00,
                    'sale_price' => 800.00,
                    'stock_quantity' => 50,
                    'is_default' => 0,
                ],
                [
                    'name' => '1 Year Plan',
                    'validity' => '1 Year',
                    'price' => 1900.00,
                    'sale_price' => 1500.00,
                    'stock_quantity' => 20,
                    'is_default' => 0,
                ],
            ],
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), $payload);

        $response->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'YouTube Premium Family Plan',
            'product_type' => 'digital',
            'digital_delivery_type' => 'direct_activation',
            'has_plans' => true,
        ]);

        $product = Product::where('slug', 'youtube-premium-family')->first();
        $this->assertCount(3, $product->plans);
        $this->assertDatabaseHas('product_plans', [
            'product_id' => $product->id,
            'name' => '1 Month Plan',
            'validity' => '1 Month',
            'price' => 180.00,
        ]);
        $this->assertDatabaseHas('product_plans', [
            'product_id' => $product->id,
            'name' => '1 Year Plan',
            'validity' => '1 Year',
            'price' => 1900.00,
        ]);
    }

    public function test_admin_can_view_edit_product_form(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('admin.products.edit', $product));

        $response->assertStatus(200);
        $response->assertSee('Edit Product');
        $response->assertSee($product->name);
    }

    public function test_admin_can_update_product(): void
    {
        $product = Product::factory()->create([
            'name' => 'Original Gadget Name',
            'product_type' => 'physical',
            'stock_quantity' => 10,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.products.update', $product), [
            'name' => 'Updated Gadget Name',
            'slug' => $product->slug,
            'product_type' => 'physical',
            'price' => 2500.00,
            'status' => 'active',
            'stock_quantity' => 30,
            'warranty_period' => '2 Years Official Warranty',
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Gadget Name',
            'stock_quantity' => 30,
            'warranty_period' => '2 Years Official Warranty',
        ]);
    }

    public function test_admin_can_delete_product(): void
    {
        Storage::fake('public');
        $image = UploadedFile::fake()->image('test.jpg')->store('products/thumbnails', 'public');

        $product = Product::factory()->digital()->create([
            'thumbnail' => $image,
            'has_plans' => true,
        ]);

        ProductPlan::create([
            'product_id' => $product->id,
            'name' => '1 Month',
            'price' => 100,
        ]);

        $this->assertDatabaseHas('products', ['id' => $product->id]);
        $this->assertDatabaseHas('product_plans', ['product_id' => $product->id]);

        $response = $this->actingAs($this->admin)->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        $this->assertDatabaseMissing('product_plans', ['product_id' => $product->id]);
        Storage::disk('public')->assertMissing($image);
    }

    public function test_product_validation_rules_require_name_price_and_valid_type(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name' => '',
            'price' => 'not-a-number',
            'product_type' => 'invalid_type',
        ]);

        $response->assertSessionHasErrors(['name', 'price', 'product_type']);
    }
}
