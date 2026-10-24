<?php

namespace Tests\Feature;

use App\Mail\OrderInvoiceMail;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\DgePayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Product $digitalProduct;

    protected Product $physicalProduct;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => 'user',
            'email' => 'buyer@example.com',
            'phone' => '01711223344',
        ]);

        $category = Category::create([
            'name' => 'AI Tools',
            'slug' => 'ai-tools',
            'is_featured' => true,
            'status' => 'active',
        ]);

        $this->digitalProduct = Product::create([
            'category_id' => $category->id,
            'name' => 'ChatGPT Plus Subscription',
            'slug' => 'chatgpt-plus-subscription',
            'price' => 2500,
            'sale_price' => 2450,
            'status' => 'active',
            'product_type' => 'digital',
            'shipping_charge' => 0,
        ]);

        $this->physicalProduct = Product::create([
            'category_id' => $category->id,
            'name' => 'Wi-Fi 6 Router',
            'slug' => 'wifi-6-router',
            'price' => 4500,
            'sale_price' => 4200,
            'status' => 'active',
            'product_type' => 'physical',
            'shipping_charge' => 60,
            'stock_quantity' => 10,
        ]);
    }

    public function test_guest_is_redirected_to_login_when_accessing_checkout(): void
    {
        $response = $this->get(route('checkout'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_place_order_and_is_redirected_to_login(): void
    {
        $payload = [
            'customer_name' => 'Guest User',
            'customer_email' => 'guest@example.com',
            'customer_phone' => '01711999888',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'street_address' => 'House 1, Road 2, Block C, Dhanmondi',
            'payment_method' => 'cod',
        ];

        $response = $this->post(route('checkout.place'), $payload);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('orders', [
            'customer_email' => 'guest@example.com',
        ]);
    }

    public function test_checkout_page_loads_successfully_and_shows_cod_only_for_pure_physical(): void
    {
        // 1. Digital product in cart -> COD should NOT be allowed
        $response = $this->actingAs($this->user)
            ->withSession([
                'cart_items' => [
                    (string) $this->digitalProduct->id => [
                        'id' => $this->digitalProduct->id,
                        'product_id' => $this->digitalProduct->id,
                        'product_type' => 'digital',
                        'quantity' => 1,
                    ],
                ],
            ])
            ->get(route('checkout'));

        $response->assertStatus(200);
        $response->assertSee('DgePay');
        $response->assertSee('hidden', false); // COD option is hidden
        $response->assertSee('Cash on Delivery (COD) is unavailable because your cart contains digital software');

        // 2. Physical product only -> COD is allowed
        $responsePhysical = $this->actingAs($this->user)
            ->withSession([
                'cart_items' => [
                    (string) $this->physicalProduct->id => [
                        'id' => $this->physicalProduct->id,
                        'product_id' => $this->physicalProduct->id,
                        'product_type' => 'physical',
                        'quantity' => 1,
                    ],
                ],
            ])
            ->get(route('checkout'));

        $responsePhysical->assertStatus(200);
        $responsePhysical->assertSee('Cash on Delivery (COD)');
        $responsePhysical->assertDontSee('Cash on Delivery (COD) is unavailable');
    }

    public function test_user_cannot_place_cod_order_when_cart_has_digital_product(): void
    {
        $sessionCart = [
            (string) $this->digitalProduct->id => [
                'id' => $this->digitalProduct->id,
                'product_id' => $this->digitalProduct->id,
                'product_type' => 'digital',
                'plan_id' => null,
                'quantity' => 1,
            ],
        ];

        $response = $this->actingAs($this->user)
            ->withSession(['cart_items' => $sessionCart])
            ->postJson(route('checkout.place'), [
                'customer_name' => 'Tanvir Buyer',
                'customer_email' => 'tanvir@example.com',
                'customer_phone' => '01711223344',
                'division' => 'Dhaka',
                'district' => 'Dhaka',
                'street_address' => 'House 12, Road 5, Dhanmondi',
                'payment_method' => 'cod',
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Cash on Delivery (COD) is not available for digital products or mixed orders. Please pay online via DGePay.',
        ]);

        $this->assertDatabaseMissing('orders', [
            'customer_email' => 'tanvir@example.com',
        ]);
    }

    public function test_user_cannot_place_cod_order_when_cart_is_mixed_with_physical_and_digital(): void
    {
        $sessionCart = [
            (string) $this->physicalProduct->id => [
                'id' => $this->physicalProduct->id,
                'product_id' => $this->physicalProduct->id,
                'product_type' => 'physical',
                'quantity' => 1,
            ],
            (string) $this->digitalProduct->id => [
                'id' => $this->digitalProduct->id,
                'product_id' => $this->digitalProduct->id,
                'product_type' => 'digital',
                'quantity' => 1,
            ],
        ];

        $response = $this->actingAs($this->user)
            ->withSession(['cart_items' => $sessionCart])
            ->postJson(route('checkout.place'), [
                'customer_name' => 'Tanvir Buyer',
                'customer_email' => 'tanvir@example.com',
                'customer_phone' => '01711223344',
                'division' => 'Dhaka',
                'district' => 'Dhaka',
                'street_address' => 'House 12, Road 5, Dhanmondi',
                'payment_method' => 'cod',
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Cash on Delivery (COD) is not available for digital products or mixed orders. Please pay online via DGePay.',
        ]);
    }

    public function test_cash_on_delivery_places_order_immediately_for_physical_product(): void
    {
        Mail::fake();

        $sessionCart = [
            (string) $this->physicalProduct->id => [
                'id' => $this->physicalProduct->id,
                'product_id' => $this->physicalProduct->id,
                'product_type' => 'physical',
                'plan_id' => null,
                'quantity' => 1,
            ],
        ];

        $response = $this->actingAs($this->user)
            ->withSession(['cart_items' => $sessionCart])
            ->postJson(route('checkout.place'), [
                'customer_name' => 'Tanvir Buyer',
                'customer_email' => 'tanvir@example.com',
                'customer_phone' => '01711223344',
                'division' => 'Dhaka',
                'district' => 'Dhaka',
                'street_address' => 'House 12, Road 5, Dhanmondi',
                'postal_code' => '1205',
                'notes' => 'Urgent parcel please',
                'payment_method' => 'cod',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $order = Order::where('customer_email', 'tanvir@example.com')->first();
        $this->assertNotNull($order);
        $this->assertEquals('confirmed', $order->order_status);
        $this->assertEquals('cod', $order->payment_method);
        $this->assertEquals('unpaid', $order->payment_status);

        // Check order item
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'title' => 'Wi-Fi 6 Router',
        ]);

        // Check Admin Notification
        $this->assertDatabaseHas('admin_notifications', [
            'type' => 'order',
        ]);

        // Check Email Queued
        Mail::assertQueued(OrderInvoiceMail::class, function ($mail) use ($order) {
            return $mail->hasTo('tanvir@example.com') && $mail->order->id === $order->id;
        });

        // Check customer address saved in addresses table
        $this->assertDatabaseHas('addresses', [
            'user_id' => $this->user->id,
            'full_name' => 'Tanvir Buyer',
            'phone' => '01711223344',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'street_address' => 'House 12, Road 5, Dhanmondi',
        ]);
    }

    public function test_dgepay_payment_initiation_redirects_user(): void
    {
        // Mock DgePayService
        $mockService = $this->createMock(DgePayService::class);
        $mockService->method('initiatePayment')
            ->willReturn([
                'success' => true,
                'payment_url' => 'https://checkout-uat.dgepay.net/payment/payment-methods?data=mocked_url',
                'transaction_id' => 'DG1234567890',
            ]);

        $this->app->instance(DgePayService::class, $mockService);

        $sessionCart = [
            (string) $this->digitalProduct->id => [
                'id' => $this->digitalProduct->id,
                'product_id' => $this->digitalProduct->id,
                'product_type' => 'digital',
                'plan_id' => null,
                'quantity' => 1,
            ],
        ];

        $response = $this->actingAs($this->user)
            ->withSession(['cart_items' => $sessionCart])
            ->postJson(route('checkout.place'), [
                'customer_name' => 'Tanvir Online',
                'customer_email' => 'tanvir_online@example.com',
                'customer_phone' => '01711223344',
                'division' => 'Dhaka',
                'district' => 'Dhaka',
                'street_address' => 'House 12, Road 5, Dhanmondi',
                'postal_code' => '1205',
                'payment_method' => 'dgepay',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'payment_url' => 'https://checkout-uat.dgepay.net/payment/payment-methods?data=mocked_url',
        ]);

        // Order exists in pending status
        $order = Order::where('customer_email', 'tanvir_online@example.com')->first();
        $this->assertNotNull($order);
        $this->assertEquals('pending', $order->order_status);
        $this->assertEquals('unpaid', $order->payment_status);
    }

    public function test_dgepay_callback_marks_order_paid_and_confirmed(): void
    {
        Mail::fake();

        $order = Order::create([
            'order_number' => 'ORD-TEST-99',
            'user_id' => $this->user->id,
            'customer_name' => 'Tanvir Paid',
            'customer_email' => 'tanvir_paid@example.com',
            'customer_phone' => '01711223344',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'street_address' => 'House 12, Road 5',
            'subtotal' => 2450,
            'discount' => 0,
            'delivery_charge' => 0,
            'total' => 2450,
            'payment_method' => 'dgepay',
            'payment_status' => 'unpaid',
            'order_status' => 'pending',
            'dgepay_unique_txn_id' => 'DG_TXN_VERIFY_123',
        ]);

        $mockService = $this->createMock(DgePayService::class);
        $mockService->method('decryptCallbackData')->willReturn(['unique_txn_id' => 'DG_TXN_VERIFY_123']);
        $mockService->method('getTransactionStatus')->willReturn([
            'success' => true,
            'data' => [
                'status_code' => '3',
                'status' => 'COMPLETED',
                'txn_number' => 'DG_GATEWAY_TXN_888',
                'payment_method' => 'bKash',
            ],
        ]);

        $this->app->instance(DgePayService::class, $mockService);

        $response = $this->get(route('checkout.dgepay.callback', ['data' => 'encrypted_mock_data']));

        $response->assertRedirect(route('checkout.success', $order->id));

        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('confirmed', $order->order_status);
        $this->assertEquals('DG_GATEWAY_TXN_888', $order->dgepay_txn_number);
        $this->assertEquals('bKash', $order->dgepay_channel);

        // Check Mail queued to customer
        Mail::assertQueued(OrderInvoiceMail::class);

        // Check Admin Notification
        $this->assertDatabaseHas('admin_notifications', [
            'type' => 'order',
        ]);
    }
}
