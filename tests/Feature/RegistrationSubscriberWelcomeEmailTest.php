<?php

namespace Tests\Feature;

use App\Mail\WelcomeUserMail;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationSubscriberWelcomeEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_registration_creates_active_subscriber_and_queues_welcome_mail(): void
    {
        Mail::fake();

        $category = Category::factory()->create();

        // Seed 2 digital products
        Product::factory()->count(2)->create([
            'category_id' => $category->id,
            'product_type' => 'digital',
            'status' => 'active',
            'is_featured' => true,
        ]);

        // Seed 2 physical products
        Product::factory()->count(2)->create([
            'category_id' => $category->id,
            'product_type' => 'physical',
            'status' => 'active',
            'is_featured' => true,
        ]);

        $userData = [
            'name' => 'John Subscriber',
            'email' => 'john.subscriber@example.com',
            'phone' => '01700000000',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ];

        $response = $this->post('/register', $userData);

        $response->assertRedirect(route('user.dashboard'));

        // Assert user exists
        $this->assertDatabaseHas('users', [
            'email' => 'john.subscriber@example.com',
            'role' => 'user',
        ]);

        // Assert subscriber table contains the email and status active
        $this->assertDatabaseHas('subscribers', [
            'email' => 'john.subscriber@example.com',
            'status' => 'active',
        ]);

        $subscriber = Subscriber::where('email', 'john.subscriber@example.com')->first();
        $this->assertNotNull($subscriber);
        $this->assertNotEmpty($subscriber->uuid);

        // Assert AdminNotification is created
        $this->assertDatabaseHas('admin_notifications', [
            'type' => 'user',
            'title' => 'New Customer Registration',
            'is_read' => false,
        ]);

        // Assert Welcome mail is queued
        Mail::assertQueued(WelcomeUserMail::class, function (WelcomeUserMail $mail) use ($userData) {
            return $mail->hasTo($userData['email'])
                && $mail->digitalProducts->count() === 2
                && $mail->physicalProducts->count() === 2;
        });
    }

    public function test_subscriber_can_unsubscribe_via_uuid(): void
    {
        $subscriber = Subscriber::create([
            'email' => 'user.to.unsubscribe@example.com',
            'status' => 'active',
        ]);

        $this->assertEquals('active', $subscriber->status);

        $response = $this->get(route('newsletter.unsubscribe', $subscriber->uuid));

        $response->assertOk();
        $response->assertSee('Unsubscribed Successfully');
        $response->assertSee('user.to.unsubscribe@example.com');

        $this->assertDatabaseHas('subscribers', [
            'id' => $subscriber->id,
            'status' => 'inactive',
        ]);
    }
}
