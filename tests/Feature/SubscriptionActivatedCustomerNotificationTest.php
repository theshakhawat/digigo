<?php

namespace Tests\Feature;

use App\Mail\SubscriptionActivatedMail;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SubscriptionActivatedCustomerNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_activating_subscription_from_admin_show_page_sends_notification_and_email(): void
    {
        Mail::fake();

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $customer = User::factory()->create([
            'role' => 'user',
            'email' => 'premium.user@example.com',
        ]);

        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Canva Pro 1 Year',
            'product_type' => 'digital',
        ]);

        $subscription = UserSubscription::create([
            'subscription_code' => UserSubscription::generateCode(),
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'plan_name' => '1 Year Plan',
            'validity' => '1 Year',
            'price' => 1500,
            'target_email' => 'premium.user@example.com',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->put(route('admin.subscriptions.update', $subscription->id), [
                'status' => 'active',
                'credentials' => "Email: canva@pro.com\nPass: Secret123",
                'access_instructions' => 'Do not modify team settings.',
                'license_key' => 'CANVA-PRO-KEY-999',
                'login_url' => 'https://canva.com/login',
                'validity' => '1 Year',
            ]);

        $response->assertRedirect();

        // 1. Check database subscription is active and fields updated
        $this->assertDatabaseHas('user_subscriptions', [
            'id' => $subscription->id,
            'status' => 'active',
            'license_key' => 'CANVA-PRO-KEY-999',
        ]);

        // 2. Check UserNotification record is created for the customer
        $this->assertDatabaseHas('user_notifications', [
            'user_id' => $customer->id,
            'type' => 'subscription',
            'is_read' => false,
        ]);

        // 3. Check Mail was queued/sent to customer email
        Mail::assertQueued(SubscriptionActivatedMail::class, function ($mail) use ($customer, $subscription) {
            return $mail->hasTo($customer->email) &&
                   $mail->subscription->id === $subscription->id;
        });
    }

    public function test_quick_activating_subscription_from_admin_table_sends_notification_and_email(): void
    {
        Mail::fake();

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $customer = User::factory()->create([
            'role' => 'user',
            'email' => 'client@example.com',
        ]);

        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Netflix 1 Month',
            'product_type' => 'digital',
        ]);

        $subscription = UserSubscription::create([
            'subscription_code' => UserSubscription::generateCode(),
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'plan_name' => '1 Month Plan',
            'validity' => '1 Month',
            'price' => 500,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->patch(route('admin.subscriptions.quick-status', $subscription->id), [
                'status' => 'active',
            ]);

        $response->assertRedirect();

        // Check subscription status
        $this->assertDatabaseHas('user_subscriptions', [
            'id' => $subscription->id,
            'status' => 'active',
        ]);

        // Check customer notification
        $this->assertDatabaseHas('user_notifications', [
            'user_id' => $customer->id,
            'type' => 'subscription',
            'is_read' => false,
        ]);

        // Check email queued
        Mail::assertQueued(SubscriptionActivatedMail::class, function ($mail) use ($customer) {
            return $mail->hasTo($customer->email);
        });
    }
}
