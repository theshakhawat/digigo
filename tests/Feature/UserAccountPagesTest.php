<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserAccountPagesTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => 'user',
            'status' => 'active',
            'password' => Hash::make('password123'),
        ]);
    }

    public function test_user_dashboard_page_loads_successfully(): void
    {
        $response = $this->actingAs($this->user)->get(route('user.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('My account');
        $response->assertSee('Orders');
    }

    public function test_user_orders_page_loads_successfully(): void
    {
        $response = $this->actingAs($this->user)->get(route('user.orders'));

        $response->assertStatus(200);
        $response->assertSee('No order has been made yet.');
    }

    public function test_user_subscriptions_page_loads_successfully(): void
    {
        $response = $this->actingAs($this->user)->get(route('user.subscriptions'));

        $response->assertStatus(200);
        $response->assertSee('Subscriptions');
    }

    public function test_user_addresses_page_loads_successfully(): void
    {
        $response = $this->actingAs($this->user)->get(route('user.addresses'));

        $response->assertStatus(200);
        $response->assertSee('My Address');
        $response->assertDontSee('Billing address');
        $response->assertDontSee('Shipping address');
    }

    public function test_user_can_save_and_update_address(): void
    {
        $response = $this->actingAs($this->user)->post(route('user.addresses.update'), [
            'full_name' => 'John Doe',
            'phone' => '01712345678',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'street_address' => 'House 12, Road 5, Dhanmondi',
            'postal_code' => '1205',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('addresses', [
            'user_id' => $this->user->id,
            'full_name' => 'John Doe',
            'phone' => '01712345678',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'street_address' => 'House 12, Road 5, Dhanmondi',
            'postal_code' => '1205',
        ]);
    }

    public function test_user_account_details_page_loads_and_updates(): void
    {
        $response = $this->actingAs($this->user)->get(route('user.account-details'));

        $response->assertStatus(200);
        $response->assertSee('Account Details');

        $updateResponse = $this->actingAs($this->user)->post(route('user.account-details.update'), [
            'name' => 'Updated User Name',
            'email' => $this->user->email,
            'phone' => '01799887766',
        ]);

        $updateResponse->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Updated User Name',
            'phone' => '01799887766',
        ]);
    }

    public function test_user_withdraw_page_loads_successfully(): void
    {
        $response = $this->actingAs($this->user)->get(route('user.withdraw'));

        $response->assertStatus(200);
        $response->assertSee('Withdraw');
    }

    public function test_user_affiliate_page_loads_successfully(): void
    {
        $response = $this->actingAs($this->user)->get(route('user.affiliate'));

        $response->assertStatus(200);
        $response->assertSee('Affiliate');
    }

    public function test_user_can_view_and_print_own_order_invoice(): void
    {
        $order = Order::create([
            'order_number' => 'ORD-USER-INV-1',
            'user_id' => $this->user->id,
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'customer_phone' => '01800000000',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'street_address' => 'Banani, Road 11',
            'subtotal' => 1200,
            'discount' => 0,
            'delivery_charge' => 0,
            'total' => 1200,
            'payment_method' => 'dgepay',
            'payment_status' => 'paid',
            'order_status' => 'completed',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'title' => 'ChatGPT Plus 1 Month',
            'product_type' => 'digital',
            'unit_price' => 1200,
            'quantity' => 1,
            'subtotal' => 1200,
        ]);

        // In user orders list, user sees the Invoice button
        $listResponse = $this->actingAs($this->user)->get(route('user.orders'));
        $listResponse->assertStatus(200);
        $listResponse->assertSee('Invoice');
        $listResponse->assertSee(route('user.orders.invoice', $order->id));

        // In invoice page
        $invoiceResponse = $this->actingAs($this->user)->get(route('user.orders.invoice', $order->id));
        $invoiceResponse->assertStatus(200);
        $invoiceResponse->assertSee('Tax Invoice');
        $invoiceResponse->assertSee('ORD-USER-INV-1');
        $invoiceResponse->assertSee('ChatGPT Plus 1 Month');
        $invoiceResponse->assertSee('Scan to Verify');
        $invoiceResponse->assertSee('window.print()');

        // Another user cannot access this user's invoice
        $otherUser = User::factory()->create([
            'role' => 'user',
            'email' => 'other@example.com',
        ]);
        $forbiddenResponse = $this->actingAs($otherUser)->get(route('user.orders.invoice', $order->id));
        $forbiddenResponse->assertStatus(403);
    }

    public function test_user_wishlist_page_loads_successfully(): void
    {
        $response = $this->actingAs($this->user)->get(route('user.wishlist'));

        $response->assertStatus(200);
        $response->assertSee('Wishlist');
    }
}
