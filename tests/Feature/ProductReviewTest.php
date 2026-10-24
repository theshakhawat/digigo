<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductReviewTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Category $category;

    private Product $physicalProduct;

    private Product $digitalProduct;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => 'user',
            'email' => 'customer@digigo.test',
        ]);

        $this->category = Category::factory()->create([
            'name' => 'Gadgets',
        ]);

        $this->physicalProduct = Product::factory()->create([
            'name' => 'Anker 20W Fast Charger',
            'product_type' => 'physical',
            'category_id' => $this->category->id,
            'price' => 1200,
            'rating' => 5.00,
            'reviews_count' => 0,
        ]);

        $this->digitalProduct = Product::factory()->create([
            'name' => 'ChatGPT Plus Key',
            'product_type' => 'digital',
            'category_id' => $this->category->id,
            'price' => 2450,
            'rating' => 5.00,
            'reviews_count' => 0,
        ]);
    }

    public function test_guest_cannot_see_review_form(): void
    {
        $response = $this->get(route('product.show', $this->physicalProduct->slug));
        $response->assertOk();
        $response->assertDontSee('reviewFormWrapper');
        $response->assertDontSee('Write a Review');
    }

    public function test_logged_in_user_without_qualifying_order_cannot_see_review_form(): void
    {
        $response = $this->actingAs($this->user)->get(route('product.show', $this->physicalProduct->slug));
        $response->assertOk();
        $response->assertDontSee('reviewFormWrapper');
    }

    public function test_user_with_completed_physical_order_can_submit_review_with_multiple_photos(): void
    {
        Storage::fake('public');

        // Create completed physical order for this user
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => $this->user->id,
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'customer_phone' => '01711112222',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'street_address' => 'Mirpur, Dhaka',
            'subtotal' => 1200,
            'delivery_charge' => 60,
            'total' => 1260,
            'payment_method' => 'cod',
            'payment_status' => 'paid',
            'order_status' => 'completed',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->physicalProduct->id,
            'title' => $this->physicalProduct->name,
            'product_type' => 'physical',
            'unit_price' => 1200,
            'quantity' => 1,
            'subtotal' => 1200,
        ]);

        // Detail page displays the review form
        $response = $this->actingAs($this->user)->get(route('product.show', $this->physicalProduct->slug));
        $response->assertOk();
        $response->assertSee('reviewFormWrapper');
        $response->assertSee('Write a Review');

        // Submit review with 2 images
        $file1 = UploadedFile::fake()->image('photo1.jpg', 600, 600);
        $file2 = UploadedFile::fake()->image('photo2.png', 600, 600);

        $postResponse = $this->actingAs($this->user)->post(route('product.review.store', $this->physicalProduct->id), [
            'rating' => 5,
            'comment' => 'Excellent charger, authentic build quality!',
            'photos' => [$file1, $file2],
        ]);

        $postResponse->assertRedirect();
        $postResponse->assertSessionHas('success');

        $this->assertDatabaseHas('reviews', [
            'user_id' => $this->user->id,
            'product_id' => $this->physicalProduct->id,
            'rating' => 5,
            'comment' => 'Excellent charger, authentic build quality!',
            'status' => 'approved',
        ]);

        $this->physicalProduct->refresh();
        $this->assertEquals(5.00, (float) $this->physicalProduct->rating);
        $this->assertEquals(1, $this->physicalProduct->reviews_count);
    }

    public function test_user_with_paid_digital_order_can_submit_review(): void
    {
        // 1. Digital order unpaid -> cannot review
        $unpaidOrder = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => $this->user->id,
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'customer_phone' => '01711112222',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'street_address' => 'Online',
            'subtotal' => 2450,
            'delivery_charge' => 0,
            'total' => 2450,
            'payment_method' => 'dgepay',
            'payment_status' => 'unpaid',
            'order_status' => 'pending',
        ]);

        OrderItem::create([
            'order_id' => $unpaidOrder->id,
            'product_id' => $this->digitalProduct->id,
            'title' => $this->digitalProduct->name,
            'product_type' => 'digital',
            'unit_price' => 2450,
            'quantity' => 1,
            'subtotal' => 2450,
        ]);

        $this->assertFalse($this->digitalProduct->canUserReview($this->user));

        // 2. Mark order as paid -> now eligible
        $unpaidOrder->update([
            'payment_status' => 'paid',
            'order_status' => 'confirmed',
        ]);

        $this->assertTrue($this->digitalProduct->canUserReview($this->user));

        $postResponse = $this->actingAs($this->user)->post(route('product.review.store', $this->digitalProduct->id), [
            'rating' => 5,
            'comment' => 'Got my ChatGPT Plus account in minutes! Super fast delivery.',
        ]);

        $postResponse->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'user_id' => $this->user->id,
            'product_id' => $this->digitalProduct->id,
            'rating' => 5,
        ]);
    }
}
