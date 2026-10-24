<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Services\ShippingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminShippingManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->user = User::factory()->create([
            'role' => 'user',
        ]);
    }

    public function test_admin_can_view_shipping_settings_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.settings.shipping'));
        $response->assertOk();
        $response->assertSee('Inside Dhaka Charge');
        $response->assertSee('Outside Dhaka Charge');
    }

    public function test_admin_can_update_global_shipping_rates(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.settings.shipping.global'), [
            'shipping_default_inside_dhaka' => 70,
            'shipping_default_outside_dhaka' => 120,
            'shipping_free_threshold' => 2500,
            'shipping_estimated_time_inside' => '24 Hours',
            'shipping_estimated_time_outside' => '48 Hours',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('70', Setting::get('shipping_default_inside_dhaka'));
        $this->assertEquals('120', Setting::get('shipping_default_outside_dhaka'));
        $this->assertEquals('2500', Setting::get('shipping_free_threshold'));
    }

    public function test_admin_can_update_category_delivery_charges(): void
    {
        $category = Category::create([
            'name' => 'Heavy Electronics',
            'slug' => 'heavy-electronics',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.settings.shipping.categories'), [
            'categories' => [
                [
                    'id' => $category->id,
                    'inside' => 80,
                    'outside' => 150,
                ],
            ],
        ]);

        $response->assertSessionHas('success');

        $category->refresh();
        $this->assertEquals(80.00, $category->delivery_charge_inside_dhaka);
        $this->assertEquals(150.00, $category->delivery_charge_outside_dhaka);
    }

    public function test_shipping_calculation_service_detects_inside_and_outside_dhaka(): void
    {
        $category = Category::create([
            'name' => 'Gadgets',
            'slug' => 'gadgets',
            'status' => 'active',
        ]);

        $physicalProduct = Product::factory()->create([
            'product_type' => 'physical',
            'category_id' => $category->id,
            'price' => 500,
        ]);

        $cartItems = [
            [
                'id' => $physicalProduct->id,
                'product_id' => $physicalProduct->id,
                'category_id' => $category->id,
                'product_type' => 'physical',
                'price' => 500,
                'quantity' => 1,
            ],
        ];

        // Inside Dhaka -> Default 60
        $inside = ShippingService::calculate($cartItems, 'Dhaka', 'Dhaka');
        $this->assertTrue($inside['is_inside_dhaka']);
        $this->assertEquals(60.00, $inside['charge']);

        // Outside Dhaka -> Default 100
        $outside = ShippingService::calculate($cartItems, 'Chattogram', 'Chattogram');
        $this->assertFalse($outside['is_inside_dhaka']);
        $this->assertEquals(100.00, $outside['charge']);

        // Set category specific rate
        $category->update([
            'delivery_charge_inside_dhaka' => 85,
            'delivery_charge_outside_dhaka' => 140,
        ]);

        $insideCat = ShippingService::calculate($cartItems, 'Dhaka', 'Dhaka');
        $this->assertEquals(85.00, $insideCat['charge']);

        $outsideCat = ShippingService::calculate($cartItems, 'Chattogram', 'Cox\'s Bazar');
        $this->assertEquals(140.00, $outsideCat['charge']);
    }

    public function test_public_shipping_calculate_endpoint_works(): void
    {
        $response = $this->postJson(route('shipping.calculate'), [
            'division' => 'Dhaka',
            'district' => 'Dhaka',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'is_inside_dhaka' => true,
        ]);
    }
}
