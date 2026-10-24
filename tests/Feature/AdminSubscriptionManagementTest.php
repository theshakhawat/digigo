<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductPlan;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSubscriptionManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $customer;

    private Product $digitalProduct;

    private ProductPlan $plan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@digigo.test',
        ]);

        $this->customer = User::factory()->create([
            'role' => 'user',
            'email' => 'subscriber@digigo.test',
            'phone' => '01700112233',
        ]);

        $category = Category::factory()->create([
            'name' => 'Streaming',
        ]);

        $this->digitalProduct = Product::factory()->create([
            'name' => 'YouTube Premium Family',
            'product_type' => 'digital',
            'category_id' => $category->id,
            'default_validity' => '1 Month',
            'access_instructions' => 'We will invite your email to our Family group.',
        ]);

        $this->plan = ProductPlan::create([
            'product_id' => $this->digitalProduct->id,
            'name' => '1 Month Slot',
            'validity' => '1 Month',
            'price' => 250,
            'is_default' => true,
            'sort_order' => 1,
        ]);
    }

    public function test_paid_order_provisions_pending_user_subscription(): void
    {
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => $this->customer->id,
            'customer_name' => $this->customer->name,
            'customer_email' => $this->customer->email,
            'customer_phone' => $this->customer->phone,
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'street_address' => 'Banani, Dhaka',
            'subtotal' => 250,
            'delivery_charge' => 0,
            'total' => 250,
            'payment_method' => 'dgepay',
            'payment_status' => 'paid',
            'order_status' => 'confirmed',
            'paid_at' => now(),
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->digitalProduct->id,
            'plan_id' => $this->plan->id,
            'title' => $this->digitalProduct->name,
            'product_type' => 'digital',
            'unit_price' => 250,
            'quantity' => 1,
            'subtotal' => 250,
        ]);

        // When Order becomes paid via Admin update
        $response = $this->actingAs($this->admin)->put(route('admin.orders.update', $order->id), [
            'customer_name' => $order->customer_name,
            'customer_email' => $order->customer_email,
            'customer_phone' => $order->customer_phone,
            'division' => $order->division,
            'district' => $order->district,
            'street_address' => $order->street_address,
            'order_status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_method' => 'dgepay',
        ]);

        $response->assertRedirect();

        // Assert pending subscription exists for customer
        $this->assertDatabaseHas('user_subscriptions', [
            'user_id' => $this->customer->id,
            'order_id' => $order->id,
            'product_id' => $this->digitalProduct->id,
            'product_name' => 'YouTube Premium Family',
            'status' => 'pending',
        ]);
    }

    public function test_customer_can_see_pending_subscription_in_their_panel(): void
    {
        $subscription = UserSubscription::create([
            'subscription_code' => UserSubscription::generateCode(),
            'user_id' => $this->customer->id,
            'product_id' => $this->digitalProduct->id,
            'product_name' => 'YouTube Premium Family',
            'plan_name' => '1 Month Slot',
            'validity' => '1 Month',
            'price' => 250,
            'status' => 'pending',
            'starts_at' => now(),
        ]);

        $response = $this->actingAs($this->customer)->get(route('user.subscriptions'));
        $response->assertOk();
        $response->assertSee('YouTube Premium Family');
        $response->assertSee('Pending Activation');
        $response->assertSee('Subscription under processing!');
    }

    public function test_admin_can_activate_subscription_and_provide_credentials(): void
    {
        $subscription = UserSubscription::create([
            'subscription_code' => UserSubscription::generateCode(),
            'user_id' => $this->customer->id,
            'product_id' => $this->digitalProduct->id,
            'product_name' => 'YouTube Premium Family',
            'plan_name' => '1 Month Slot',
            'validity' => '1 Month',
            'price' => 250,
            'status' => 'pending',
            'starts_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.subscriptions.update', $subscription->id), [
            'status' => 'active',
            'credentials' => "Email: family@digigo.test\nPassword: securePassword99",
            'login_url' => 'https://youtube.com',
            'access_instructions' => 'Enjoy ad-free YouTube and Music.',
        ]);

        $response->assertRedirect();
        $subscription->refresh();

        $this->assertEquals('active', $subscription->status);
        $this->assertStringContainsString('family@digigo.test', $subscription->credentials);
        $this->assertNotNull($subscription->activated_at);

        // Customer sees active credentials
        $custResponse = $this->actingAs($this->customer)->get(route('user.subscriptions'));
        $custResponse->assertOk();
        $custResponse->assertSee('Active');
        $custResponse->assertSee('family@digigo.test');
        $custResponse->assertSee('Enjoy ad-free YouTube and Music.');
    }

    public function test_admin_can_quick_update_subscription_status(): void
    {
        $subscription = UserSubscription::create([
            'subscription_code' => UserSubscription::generateCode(),
            'user_id' => $this->customer->id,
            'product_name' => 'Netflix Premium',
            'status' => 'pending',
            'price' => 350,
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.subscriptions.quick-status', $subscription->id), [
            'status' => 'active',
        ]);

        $response->assertRedirect();
        $subscription->refresh();
        $this->assertEquals('active', $subscription->status);
    }

    public function test_cron_command_checks_and_expires_subscriptions_at_scheduled_time(): void
    {
        // 1. Subscription past expiry
        $expiredSub = UserSubscription::create([
            'subscription_code' => 'SUB-EXP1234',
            'user_id' => $this->customer->id,
            'product_name' => 'Netflix Premium 4K',
            'status' => 'active',
            'price' => 350,
            'starts_at' => now()->subMonths(2),
            'expires_at' => now()->subDay(), // Expired yesterday
        ]);

        // 2. Subscription still valid
        $validSub = UserSubscription::create([
            'subscription_code' => 'SUB-VAL5678',
            'user_id' => $this->customer->id,
            'product_name' => 'Spotify Premium',
            'status' => 'active',
            'price' => 200,
            'starts_at' => now(),
            'expires_at' => now()->addDays(20), // Valid for 20 more days
        ]);

        // Run the console command
        $this->artisan('subscriptions:check-expired')
            ->expectsOutputToContain('Found 1 expired subscription(s)')
            ->expectsOutputToContain('Marked #SUB-EXP1234')
            ->assertExitCode(0);

        $expiredSub->refresh();
        $validSub->refresh();

        $this->assertEquals('expired', $expiredSub->status);
        $this->assertEquals('active', $validSub->status);

        // Check AdminNotification was generated
        $this->assertDatabaseHas('admin_notifications', [
            'type' => 'subscription_expired',
            'title' => 'Subscription Expired: #SUB-EXP1234',
        ]);
    }

    public function test_order_with_target_delivery_info_persists_and_provisions_target_email_and_phone(): void
    {
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => $this->customer->id,
            'customer_name' => $this->customer->name,
            'customer_email' => $this->customer->email,
            'customer_phone' => $this->customer->phone,
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'street_address' => 'Banani, Dhaka',
            'subtotal' => 250,
            'delivery_charge' => 0,
            'total' => 250,
            'payment_method' => 'dgepay',
            'payment_status' => 'paid',
            'order_status' => 'confirmed',
            'digital_target_email' => 'mycustomgmail@gmail.com',
            'digital_target_phone' => '01899999999',
            'digital_account_notes' => 'Please invite this gmail to family plan slot.',
            'paid_at' => now(),
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->digitalProduct->id,
            'plan_id' => $this->plan->id,
            'title' => $this->digitalProduct->name,
            'product_type' => 'digital',
            'unit_price' => 250,
            'quantity' => 1,
            'subtotal' => 250,
        ]);

        // Trigger provisioning via order status update
        $this->actingAs($this->admin)->put(route('admin.orders.update', $order->id), [
            'customer_name' => $order->customer_name,
            'customer_email' => $order->customer_email,
            'customer_phone' => $order->customer_phone,
            'division' => $order->division,
            'district' => $order->district,
            'street_address' => $order->street_address,
            'order_status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_method' => 'dgepay',
        ]);

        $this->assertDatabaseHas('user_subscriptions', [
            'order_id' => $order->id,
            'user_id' => $this->customer->id,
            'target_email' => 'mycustomgmail@gmail.com',
            'target_phone' => '01899999999',
        ]);

        // Admin order show view displays target email
        $adminOrderView = $this->actingAs($this->admin)->get(route('admin.orders.show', $order->id));
        $adminOrderView->assertOk();
        $adminOrderView->assertSee('mycustomgmail@gmail.com');
        $adminOrderView->assertSee('01899999999');

        // Admin subscription show view displays target email
        $sub = UserSubscription::where('order_id', $order->id)->first();
        $adminSubView = $this->actingAs($this->admin)->get(route('admin.subscriptions.show', $sub->id));
        $adminSubView->assertOk();
        $adminSubView->assertSee('mycustomgmail@gmail.com');
        $adminSubView->assertSee('01899999999');

        // User subscriptions view displays target account
        $userView = $this->actingAs($this->customer)->get(route('user.subscriptions'));
        $userView->assertOk();
        $userView->assertSee('Activation for: mycustomgmail@gmail.com');
    }

    public function test_past_due_subscription_is_displayed_as_expired_even_if_cron_has_not_run(): void
    {
        // Given an active subscription where expires_at is in the past
        $expiredSub = UserSubscription::create([
            'user_id' => $this->customer->id,
            'product_id' => $this->digitalProduct->id,
            'subscription_code' => 'SUB-PASTDUE123',
            'product_name' => 'Past Due Netflix Subscription',
            'status' => 'active', // DB status is still active (e.g., cron hasn't executed yet)
            'price' => 300,
            'starts_at' => now()->subDays(30),
            'expires_at' => now()->subDays(5),
        ]);

        $this->assertTrue($expiredSub->isExpired());
        $this->assertEquals('expired', $expiredSub->effective_status);

        // When user visits their subscriptions page
        $userResponse = $this->actingAs($this->customer)->get(route('user.subscriptions'));
        $userResponse->assertOk();
        $userResponse->assertSee('Expired');
        $userResponse->assertSee('This subscription has expired!');
        $userResponse->assertSee('Renew Subscription');

        // When admin visits the subscriptions index
        $adminResponse = $this->actingAs($this->admin)->get(route('admin.subscriptions.index'));
        $adminResponse->assertOk();
        $adminResponse->assertSee('EXPIRED');

        // When admin visits the subscription detail page
        $adminShowResponse = $this->actingAs($this->admin)->get(route('admin.subscriptions.show', $expiredSub->id));
        $adminShowResponse->assertOk();
        $adminShowResponse->assertSee('Currently Past Expiration Date');
    }
}
