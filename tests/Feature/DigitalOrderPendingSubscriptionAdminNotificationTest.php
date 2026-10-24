<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DigitalOrderPendingSubscriptionAdminNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_ordering_digital_product_creates_pending_subscription_admin_notification(): void
    {
        $category = Category::factory()->create();

        $digitalProduct = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'ChatGPT Plus 1 Month',
            'product_type' => 'digital',
            'price' => 0,
            'status' => 'active',
            'stock_quantity' => 50,
        ]);

        $customer = User::factory()->create([
            'role' => 'user',
            'email' => 'subscriber.customer@example.com',
        ]);

        // Place an order with 100% discount / free so it confirms immediately
        $orderData = [
            'customer_name' => 'Sub Customer',
            'customer_email' => 'subscriber.customer@example.com',
            'customer_phone' => '01712345678',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'street_address' => 'Mirpur, Dhaka',
        ];

        // Simulate confirmed free / paid order or place order
        $response = $this->actingAs($customer)
            ->withSession([
                'cart_items' => [
                    (string) $digitalProduct->id => [
                        'id' => $digitalProduct->id,
                        'product_id' => $digitalProduct->id,
                        'quantity' => 1,
                    ],
                ],
            ])
            ->post('/checkout/place-order', $orderData);

        $response->assertRedirect();

        // 1. Assert user subscription record was created with 'pending' status
        $this->assertDatabaseHas('user_subscriptions', [
            'user_id' => $customer->id,
            'product_id' => $digitalProduct->id,
            'status' => 'pending',
        ]);

        // 2. Assert AdminNotification was created alerting admin that a pending subscription needs activation
        $this->assertDatabaseHas('admin_notifications', [
            'type' => 'subscription',
            'is_read' => false,
        ]);

        $this->assertDatabaseHas('admin_notifications', [
            'type' => 'subscription',
            'link' => route('admin.subscriptions.index', ['status' => 'pending']),
        ]);
    }
}
