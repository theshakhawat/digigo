<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutAjaxAndBackendPricingTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Category $category;

    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->category = Category::create([
            'name' => 'Software & Tools',
            'slug' => 'software-tools',
            'status' => 'active',
        ]);

        $this->product = Product::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'Premium VPN Access',
            'slug' => 'premium-vpn-access',
            'price' => 500,
            'status' => 'active',
            'product_type' => 'digital',
        ]);
    }

    public function test_checkout_calculates_prices_from_database_models(): void
    {
        // Try tampering price in session (e.g., trying to pay 10 tk instead of 500 tk)
        session([
            'cart_items' => [
                (string) $this->product->id => [
                    'id' => $this->product->id,
                    'product_id' => $this->product->id,
                    'price' => 10, // Tampered client-side price
                    'quantity' => 2,
                ],
            ],
        ]);

        $response = $this->actingAs($this->user)->get(route('checkout'));
        $response->assertOk();

        // Must show real DB price 500 * 2 = 1,000 tk, completely ignoring the tampered 10 tk
        $response->assertSee('৳1,000');
    }

    public function test_coupon_applied_via_ajax_on_checkout_calculates_backend_totals(): void
    {
        $coupon = Coupon::create([
            'code' => 'SAVE100',
            'discount_type' => 'fixed',
            'discount_value' => 100,
            'status' => 'active',
        ]);

        // Put legitimate product into cart session
        $this->withSession([
            'cart_items' => [
                (string) $this->product->id => [
                    'id' => $this->product->id,
                    'product_id' => $this->product->id,
                    'quantity' => 1, // unit price is 500
                ],
            ],
        ]);

        $response = $this->actingAs($this->user)->postJson(route('cart.coupon.apply'), [
            'code' => 'SAVE100',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'discount' => 100,
            'code' => 'SAVE100',
            'summary' => [
                'subtotal' => 500,
                'discount' => 100,
                'total' => 400, // 500 - 100 + 0 (digital shipping)
            ],
        ]);

        $this->assertEquals('SAVE100', session('applied_coupon')['code']);
        $this->assertEquals(100, session('applied_coupon')['discount']);
    }

    public function test_coupon_removed_via_ajax_on_checkout_recalculates_backend_totals(): void
    {
        session([
            'cart_items' => [
                (string) $this->product->id => [
                    'id' => $this->product->id,
                    'product_id' => $this->product->id,
                    'quantity' => 1,
                ],
            ],
            'applied_coupon' => [
                'code' => 'SAVE100',
                'discount' => 100,
            ],
        ]);

        $response = $this->actingAs($this->user)->postJson(route('cart.coupon.remove'), [
            'division' => 'Dhaka',
            'district' => 'Dhaka',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'summary' => [
                'subtotal' => 500,
                'discount' => 0,
                'total' => 500,
            ],
        ]);

        $this->assertNull(session('applied_coupon'));
    }

    public function test_checkout_calculate_endpoint_returns_accurate_backend_breakdown(): void
    {
        $this->withSession([
            'cart_items' => [
                (string) $this->product->id => [
                    'id' => $this->product->id,
                    'product_id' => $this->product->id,
                    'quantity' => 3, // 3 * 500 = 1500
                ],
            ],
        ]);

        $response = $this->actingAs($this->user)->postJson(route('checkout.calculate'), [
            'division' => 'Dhaka',
            'district' => 'Dhaka',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'subtotal' => 1500,
            'total' => 1500,
        ]);
    }

    public function test_coupon_discount_exceeding_total_price_results_in_zero_payable_and_free_confirmation_without_gateway(): void
    {
        // Product price is 500 TK, coupon is 600 TK (or 300 TK on 250 TK product)
        $coupon = Coupon::create([
            'code' => 'SUPERFREE600',
            'discount_type' => 'fixed',
            'discount_value' => 600,
            'status' => 'active',
        ]);

        $this->withSession([
            'cart_items' => [
                (string) $this->product->id => [
                    'id' => $this->product->id,
                    'product_id' => $this->product->id,
                    'quantity' => 1, // unit price is 500 tk
                ],
            ],
        ]);

        // 1. Applying coupon via ajax calculates 0 total
        $applyResponse = $this->actingAs($this->user)->postJson(route('cart.coupon.apply'), [
            'code' => 'SUPERFREE600',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
        ]);

        $applyResponse->assertOk();
        $applyResponse->assertJson([
            'success' => true,
            'code' => 'SUPERFREE600',
            'summary' => [
                'subtotal' => 500,
                'discount' => 600,
                'total' => 0,
            ],
        ]);

        // 2. Placing order with 0 total confirms immediately as paid/free without requiring dgepay
        $orderResponse = $this->actingAs($this->user)->postJson(route('checkout.place'), [
            'customer_name' => 'Free Order Customer',
            'customer_email' => 'free@example.com',
            'customer_phone' => '01700000000',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'street_address' => 'Mirpur 10, Dhaka',
        ]);

        $orderResponse->assertOk();
        $orderResponse->assertJson([
            'success' => true,
        ]);

        $order = Order::where('customer_email', 'free@example.com')->first();
        $this->assertNotNull($order);
        $this->assertEquals(0, (float) $order->total);
        $this->assertEquals(600, (float) $order->discount);
        $this->assertEquals('SUPERFREE600', $order->coupon_code);
        $this->assertEquals('free', $order->payment_method);
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('confirmed', $order->order_status);
        $this->assertNotNull($order->paid_at);

        // Ensure coupon usage was tracked
        $coupon->refresh();
        $this->assertEquals(1, $coupon->used_count);
        $this->assertDatabaseHas('coupon_usages', [
            'coupon_id' => $coupon->id,
            'user_email' => 'free@example.com',
            'discount_amount' => 600,
        ]);
    }
}
