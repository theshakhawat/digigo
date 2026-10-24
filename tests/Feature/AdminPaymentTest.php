<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPaymentTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@digigo.test',
        ]);

        $this->customer = User::factory()->create([
            'name' => 'John Customer',
            'email' => 'customer@example.com',
            'role' => 'user',
        ]);
    }

    public function test_non_admin_cannot_access_payments(): void
    {
        $response = $this->actingAs($this->customer)->get(route('admin.payments.index'));
        $response->assertRedirect(route('user.dashboard'));
    }

    public function test_admin_can_view_payments_list_with_only_paid_orders(): void
    {
        $paidOrder = Order::create([
            'order_number' => 'ORD-PAID-001',
            'user_id' => $this->customer->id,
            'customer_name' => 'John Paid',
            'customer_email' => 'paid@example.com',
            'customer_phone' => '01711111111',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'street_address' => 'Dhaka, Bangladesh',
            'order_status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_method' => 'dgepay',
            'dgepay_unique_txn_id' => 'TXN-PAID-999',
            'subtotal' => 500,
            'delivery_charge' => 50,
            'discount' => 0,
            'total' => 550,
            'paid_at' => now(),
        ]);

        $unpaidOrder = Order::create([
            'order_number' => 'ORD-UNPAID-002',
            'user_id' => $this->customer->id,
            'customer_name' => 'Sarah Unpaid',
            'customer_email' => 'unpaid@example.com',
            'customer_phone' => '01822222222',
            'division' => 'Chittagong',
            'district' => 'Chittagong',
            'street_address' => 'Chittagong, Bangladesh',
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'cod',
            'subtotal' => 300,
            'delivery_charge' => 50,
            'discount' => 0,
            'total' => 350,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.payments.index'));

        $response->assertOk();
        $response->assertSee('ORD-PAID-001');
        $response->assertSee('TXN-PAID-999');
        $response->assertSee('John Paid');
        $response->assertDontSee('ORD-UNPAID-002');
        $response->assertDontSee('Sarah Unpaid');
    }

    public function test_admin_can_filter_payments_by_method(): void
    {
        Order::create([
            'order_number' => 'ORD-DGEPAY-1',
            'user_id' => $this->customer->id,
            'customer_name' => 'Dgepay Buyer',
            'customer_email' => 'dgepay@example.com',
            'customer_phone' => '01711111111',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'street_address' => 'Dhaka',
            'order_status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_method' => 'dgepay',
            'subtotal' => 1000,
            'total' => 1000,
            'paid_at' => now(),
        ]);

        Order::create([
            'order_number' => 'ORD-COD-PAID',
            'user_id' => $this->customer->id,
            'customer_name' => 'COD Paid Buyer',
            'customer_email' => 'cod@example.com',
            'customer_phone' => '01811111111',
            'division' => 'Sylhet',
            'district' => 'Sylhet',
            'street_address' => 'Sylhet',
            'order_status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'cod',
            'subtotal' => 600,
            'total' => 600,
            'paid_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.payments.index', ['method' => 'dgepay']));
        $response->assertOk();
        $response->assertSee('ORD-DGEPAY-1');
        $response->assertDontSee('ORD-COD-PAID');
    }

    public function test_admin_can_view_official_invoice_for_paid_order(): void
    {
        $product = Product::factory()->create([
            'name' => 'Windows 11 Pro Key',
            'price' => 1200,
        ]);

        $order = Order::create([
            'order_number' => 'ORD-INV-2026',
            'user_id' => $this->customer->id,
            'customer_name' => 'Invoice Customer',
            'customer_email' => 'inv@example.com',
            'customer_phone' => '01912345678',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'street_address' => 'Gulshan 2, Dhaka',
            'order_status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_method' => 'dgepay',
            'dgepay_unique_txn_id' => 'DG-INV-555',
            'subtotal' => 1200,
            'delivery_charge' => 0,
            'discount' => 100,
            'total' => 1100,
            'paid_at' => now(),
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'title' => 'Windows 11 Pro Key',
            'product_type' => 'digital',
            'product_id' => $product->id,
            'unit_price' => 1200,
            'quantity' => 1,
            'subtotal' => 1200,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.payments.invoice', $order));

        $response->assertOk();
        $response->assertSee('ORD-INV-2026');
        $response->assertSee('DG-INV-555');
        $response->assertSee('Windows 11 Pro Key');
        $response->assertSee('Official Invoice');
        $response->assertSee('Gulshan 2, Dhaka');
    }
}
