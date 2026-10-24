<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@digigo.com.bd',
        ]);

        $this->order = Order::create([
            'order_number' => 'ORD-2026-TEST1',
            'customer_name' => 'John Customer',
            'customer_email' => 'john@example.com',
            'customer_phone' => '01700000000',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'street_address' => 'Road 4, House 12',
            'subtotal' => 1000,
            'discount' => 0,
            'delivery_charge' => 50,
            'total' => 1050,
            'payment_method' => 'cod',
            'payment_status' => 'unpaid',
            'order_status' => 'confirmed',
        ]);

        OrderItem::create([
            'order_id' => $this->order->id,
            'title' => 'Test Item',
            'product_type' => 'physical',
            'unit_price' => 1000,
            'quantity' => 1,
            'subtotal' => 1000,
        ]);
    }

    public function test_admin_can_view_orders_list(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.orders.index'));

        $response->assertStatus(200);
        $response->assertSee('Orders Management');
        $response->assertSee('#ORD-2026-TEST1');
        $response->assertSee('John Customer');
    }

    public function test_admin_can_view_order_details(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.orders.show', $this->order->id));

        $response->assertStatus(200);
        $response->assertSee('#ORD-2026-TEST1');
        $response->assertSee('Test Item');
        $response->assertSee('John Customer');
    }

    public function test_admin_can_edit_and_update_order(): void
    {
        $editPageResponse = $this->actingAs($this->admin)->get(route('admin.orders.edit', $this->order->id));
        $editPageResponse->assertStatus(200);
        $editPageResponse->assertSee('Edit Order #ORD-2026-TEST1');

        $updateResponse = $this->actingAs($this->admin)->put(route('admin.orders.update', $this->order->id), [
            'customer_name' => 'John Updated',
            'customer_email' => 'john_updated@example.com',
            'customer_phone' => '01899999999',
            'division' => 'Chattogram',
            'district' => 'Chattogram',
            'street_address' => 'GEC Circle, Chattogram',
            'postal_code' => '4000',
            'notes' => 'Updated notes from admin',
            'order_status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'cod',
        ]);

        $updateResponse->assertRedirect(route('admin.orders.show', $this->order->id));
        $updateResponse->assertSessionHas('success');

        $this->order->refresh();
        $this->assertEquals('John Updated', $this->order->customer_name);
        $this->assertEquals('completed', $this->order->order_status);
        $this->assertEquals('paid', $this->order->payment_status);
        $this->assertNotNull($this->order->paid_at);
    }

    public function test_admin_can_quick_update_order_status(): void
    {
        $response = $this->actingAs($this->admin)->patch(route('admin.orders.quick-status', $this->order->id), [
            'order_status' => 'completed',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->order->refresh();
        $this->assertEquals('completed', $this->order->order_status);
    }

    public function test_admin_can_quick_update_payment_status(): void
    {
        $response = $this->actingAs($this->admin)->patch(route('admin.orders.quick-status', $this->order->id), [
            'payment_status' => 'paid',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->order->refresh();
        $this->assertEquals('paid', $this->order->payment_status);
        $this->assertNotNull($this->order->paid_at);
    }

    public function test_admin_can_view_order_details_and_see_print_invoice_button(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.orders.show', $this->order->id));

        $response->assertStatus(200);
        $response->assertSee('Print Invoice');
        $response->assertSee(route('admin.orders.invoice', $this->order->id));
    }

    public function test_admin_can_view_and_print_professional_invoice(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.orders.invoice', $this->order->id));

        $response->assertStatus(200);
        $response->assertSee('Tax Invoice');
        $response->assertSee('ORD-2026-TEST1');
        $response->assertSee('John Customer');
        $response->assertSee('Test Item');
        $response->assertSee('Scan to Verify');
        $response->assertSee('Authorized Signature');
        $response->assertSee('window.print()');
    }

    public function test_admin_can_delete_order(): void
    {
        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.orders.destroy', $this->order->id));

        $deleteResponse->assertRedirect(route('admin.orders.index'));
        $deleteResponse->assertSessionHas('success');

        $this->assertDatabaseMissing('orders', [
            'id' => $this->order->id,
        ]);
    }
}
