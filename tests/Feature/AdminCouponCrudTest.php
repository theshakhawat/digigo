<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCouponCrudTest extends TestCase
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

    public function test_guest_cannot_access_admin_coupons(): void
    {
        $response = $this->get(route('admin.coupons.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_admin_coupons(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.coupons.index'));
        $response->assertRedirect(route('user.dashboard'));
    }

    public function test_admin_can_view_coupons_index(): void
    {
        $coupon = Coupon::create([
            'code' => 'SAVE10',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.coupons.index'));
        $response->assertOk();
        $response->assertSee('SAVE10');
    }

    public function test_admin_can_create_coupon_with_restrictions(): void
    {
        $category = Category::create([
            'name' => 'Streaming',
            'slug' => 'streaming',
            'status' => 'active',
        ]);

        $product = Product::factory()->create([
            'category_id' => $category->id,
        ]);

        $payload = [
            'code' => 'MEGA50',
            'name' => 'Mega 50 Taka Off',
            'description' => 'Flat 50 taka off on streaming items',
            'discount_type' => 'fixed',
            'discount_value' => 50,
            'min_spend' => 200,
            'starts_at' => now()->toDateTimeString(),
            'expires_at' => now()->addDays(30)->toDateTimeString(),
            'usage_limit_total' => 100,
            'usage_limit_per_user' => 1,
            'status' => 'active',
            'category_ids' => [$category->id],
            'product_ids' => [$product->id],
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.coupons.store'), $payload);
        $response->assertRedirect(route('admin.coupons.index'));

        $this->assertDatabaseHas('coupons', [
            'code' => 'MEGA50',
            'discount_value' => 50.00,
            'discount_type' => 'fixed',
            'usage_limit_total' => 100,
            'usage_limit_per_user' => 1,
        ]);

        $created = Coupon::where('code', 'MEGA50')->first();
        $this->assertTrue($created->categories->contains($category->id));
        $this->assertTrue($created->products->contains($product->id));
    }

    public function test_admin_can_update_coupon(): void
    {
        $coupon = Coupon::create([
            'code' => 'OLDCODE',
            'discount_type' => 'fixed',
            'discount_value' => 20,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.coupons.update', $coupon), [
            'code' => 'NEWCODE',
            'discount_type' => 'percentage',
            'discount_value' => 15,
            'status' => 'inactive',
        ]);

        $response->assertRedirect(route('admin.coupons.index'));

        $this->assertDatabaseHas('coupons', [
            'id' => $coupon->id,
            'code' => 'NEWCODE',
            'discount_type' => 'percentage',
            'discount_value' => 15,
            'status' => 'inactive',
        ]);
    }

    public function test_admin_can_delete_coupon(): void
    {
        $coupon = Coupon::create([
            'code' => 'DELCODE',
            'discount_type' => 'fixed',
            'discount_value' => 20,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.coupons.destroy', $coupon));
        $response->assertRedirect(route('admin.coupons.index'));

        $this->assertDatabaseMissing('coupons', [
            'id' => $coupon->id,
        ]);
    }

    public function test_coupon_can_be_applied_and_removed_on_cart(): void
    {
        $coupon = Coupon::create([
            'code' => 'FLAT100',
            'discount_type' => 'fixed',
            'discount_value' => 100,
            'status' => 'active',
        ]);

        // Apply coupon
        $response = $this->post(route('cart.coupon.apply'), [
            'code' => 'FLAT100',
        ]);

        $response->assertSessionHas('applied_coupon');
        $this->assertEquals(100, session('applied_coupon.discount'));

        // View Checkout where coupon is active
        $checkoutResponse = $this->actingAs($this->admin)->get(route('checkout'));
        $checkoutResponse->assertOk();
        $checkoutResponse->assertSee('FLAT100');

        // Remove coupon
        $removeResponse = $this->post(route('cart.coupon.remove'));
        $removeResponse->assertSessionMissing('applied_coupon');
    }
}
