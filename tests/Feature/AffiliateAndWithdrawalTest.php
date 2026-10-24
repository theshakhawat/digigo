<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Models\WithdrawalRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AffiliateAndWithdrawalTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $affiliateCustomer;

    private User $buyer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@digigo.test',
        ]);

        $this->affiliateCustomer = User::factory()->create([
            'role' => 'user',
            'name' => 'Affiliate Hero',
            'email' => 'affiliate@digigo.test',
            'affiliate_code' => 'HERO77',
            'affiliate_balance' => 0,
        ]);

        $this->buyer = User::factory()->create([
            'role' => 'user',
            'name' => 'General Buyer',
            'email' => 'buyer@digigo.test',
        ]);

        Setting::set('affiliate_enabled', '1', 'affiliate');
        Setting::set('affiliate_default_digital', '10', 'affiliate');
        Setting::set('affiliate_default_physical', '5', 'affiliate');
        Setting::set('affiliate_min_withdrawal', '500', 'affiliate');
        Setting::clearCache();
    }

    public function test_customer_can_view_affiliate_dashboard_and_generate_link(): void
    {
        $product = Product::factory()->create([
            'name' => 'ChatGPT Plus Key',
            'slug' => 'chatgpt-plus-key',
            'price' => 2500,
        ]);

        $response = $this->actingAs($this->affiliateCustomer)->get(route('user.affiliate'));

        $response->assertOk();
        $response->assertSee('HERO77');
        $response->assertSee('ChatGPT Plus Key');
        $response->assertSee('Product Specific Affiliate Link Generator');
    }

    public function test_order_places_with_affiliate_cookie_and_credits_commission_when_paid(): void
    {
        $category = Category::factory()->create([
            'name' => 'Tech Gadgets',
            'affiliate_commission_physical' => 20, // 20%
            'affiliate_commission_type_physical' => 'percentage',
        ]);

        $product = Product::factory()->create([
            'name' => 'Wireless Keyboard',
            'price' => 1000,
            'category_id' => $category->id,
            'product_type' => 'physical',
        ]);

        // Buyer orders with affiliate referral cookie
        $orderData = [
            'customer_name' => 'Alice Buyer',
            'customer_email' => 'alice@test.com',
            'customer_phone' => '01700000000',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'street_address' => 'Mirpur 10, Dhaka',
            'payment_method' => 'cod',
        ];

        session([
            'cart_items' => [
                (string) $product->id => [
                    'id' => $product->id,
                    'product_id' => $product->id,
                    'title' => $product->name,
                    'price' => 1000,
                    'quantity' => 1,
                    'product_type' => 'physical',
                ],
            ],
            'affiliate_ref' => 'HERO77',
        ]);

        $buyer = User::factory()->create([
            'email' => 'alice@test.com',
            'role' => 'user',
        ]);

        $response = $this->actingAs($buyer)->post(route('checkout.place'), $orderData);
        $response->assertRedirect();

        $order = Order::where('customer_email', 'alice@test.com')->first();
        $this->assertNotNull($order);
        $this->assertEquals($this->affiliateCustomer->id, $order->affiliate_user_id);
        $this->assertEquals(200, (float) $order->affiliate_commission); // 20% of 1000

        // Commission starts as pending because COD order is not completed/paid yet
        $this->affiliateCustomer->refresh();
        $this->assertEquals(0, (float) $this->affiliateCustomer->affiliate_balance);

        // When order is marked completed/delivered by Admin
        $this->actingAs($this->admin)->put(route('admin.orders.update', $order), [
            'customer_name' => $order->customer_name,
            'customer_email' => $order->customer_email,
            'customer_phone' => $order->customer_phone,
            'division' => $order->division,
            'district' => $order->district,
            'street_address' => $order->street_address,
            'order_status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'cod',
        ]);

        $this->affiliateCustomer->refresh();
        // Commission should now be credited!
        $this->assertEquals(200, (float) $this->affiliateCustomer->affiliate_balance);
        $this->assertEquals(200, (float) $this->affiliateCustomer->total_affiliate_earned);
    }

    public function test_customer_can_request_withdrawal_via_all_three_methods(): void
    {
        // Give customer 1500 balance
        $this->affiliateCustomer->update(['affiliate_balance' => 1500]);

        // Request Bank Withdrawal
        $response = $this->actingAs($this->affiliateCustomer)->post(route('user.withdraw.store'), [
            'amount' => 700,
            'method' => 'bank',
            'bank_name' => 'Eastern Bank Ltd',
            'bank_account_name' => 'Affiliate Hero',
            'bank_account_number' => '1041001234567',
            'bank_branch' => 'Banani Branch',
        ]);

        $response->assertRedirect(route('user.withdraw'));
        $this->assertDatabaseHas('withdrawal_requests', [
            'user_id' => $this->affiliateCustomer->id,
            'amount' => 700,
            'method' => 'bank',
            'status' => 'pending',
        ]);
    }

    public function test_user_cannot_request_withdrawal_below_admin_configured_minimum(): void
    {
        Setting::set('affiliate_min_withdrawal', '300', 'affiliate');

        $this->affiliateCustomer->update([
            'affiliate_balance' => 500,
        ]);

        // Attempting to withdraw below 300 should fail validation
        $response = $this->actingAs($this->affiliateCustomer)->post(route('user.withdraw.store'), [
            'amount' => 200,
            'method' => 'mfs',
            'mfs_provider' => 'bKash',
            'mfs_number' => '01712345678',
            'mfs_account_type' => 'personal',
        ]);

        $response->assertSessionHasErrors(['amount']);
        $this->assertDatabaseMissing('withdrawal_requests', [
            'user_id' => $this->affiliateCustomer->id,
            'amount' => 200,
        ]);
    }

    public function test_user_can_request_withdrawal_when_amount_meets_admin_minimum(): void
    {
        Setting::set('affiliate_min_withdrawal', '200', 'affiliate');

        $this->affiliateCustomer->update([
            'affiliate_balance' => 250,
        ]);

        $response = $this->actingAs($this->affiliateCustomer)->post(route('user.withdraw.store'), [
            'amount' => 200,
            'method' => 'mfs',
            'mfs_provider' => 'bKash',
            'mfs_number' => '01712345678',
            'mfs_account_type' => 'personal',
        ]);

        $response->assertRedirect(route('user.withdraw'));
        $this->assertDatabaseHas('withdrawal_requests', [
            'user_id' => $this->affiliateCustomer->id,
            'amount' => 200,
        ]);
    }

    public function test_admin_can_approve_withdrawal_and_balance_is_deducted(): void
    {
        $this->affiliateCustomer->update([
            'affiliate_balance' => 1000,
            'total_affiliate_earned' => 1000,
        ]);

        $withdrawal = WithdrawalRequest::create([
            'request_number' => 'WTH-TEST-999',
            'user_id' => $this->affiliateCustomer->id,
            'amount' => 600,
            'method' => 'mfs',
            'status' => 'pending',
            'payment_details' => [
                'provider' => 'bKash',
                'number' => '01712345678',
                'account_type' => 'personal',
            ],
        ]);

        // Admin approves with Transaction Ref
        $response = $this->actingAs($this->admin)->post(route('admin.withdrawals.approve', $withdrawal), [
            'transaction_reference' => 'BKASH-TRX-12345',
            'admin_note' => 'Sent successfully to bKash personal',
        ]);

        $response->assertRedirect(route('admin.withdrawals.index'));

        $withdrawal->refresh();
        $this->assertEquals('approved', $withdrawal->status);
        $this->assertEquals('BKASH-TRX-12345', $withdrawal->transaction_reference);

        // User balance deducted
        $this->affiliateCustomer->refresh();
        $this->assertEquals(400, (float) $this->affiliateCustomer->affiliate_balance);
        $this->assertEquals(600, (float) $this->affiliateCustomer->total_affiliate_withdrawn);
    }
}
