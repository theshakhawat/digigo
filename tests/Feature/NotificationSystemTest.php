<?php

namespace Tests\Feature;

use App\Models\AdminNotification;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationSystemTest extends TestCase
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
            'role' => 'user',
            'email' => 'customer@digigo.test',
        ]);
    }

    public function test_admin_notification_lifecycle_including_read_redirect_toggle_and_mark_all(): void
    {
        // 1. Create notifications
        $notif1 = AdminNotification::create([
            'type' => 'order',
            'title' => 'New Order #1001',
            'message' => 'Customer placed order for Tk 1000',
            'link' => route('admin.orders.index'),
            'is_read' => false,
        ]);

        $notif2 = AdminNotification::create([
            'type' => 'user',
            'title' => 'New Customer Registered',
            'message' => 'Customer registered account',
            'link' => route('admin.users.index'),
            'is_read' => false,
        ]);

        // 2. View notifications page as admin
        $response = $this->actingAs($this->admin)->get(route('admin.notifications.index'));
        $response->assertOk();
        $response->assertSee('New Order #1001');
        $response->assertSee('New Customer Registered');

        // 3. Click notification: marks as read and redirects to target link
        $readResponse = $this->actingAs($this->admin)->get(route('admin.notifications.read', $notif1->id));
        $readResponse->assertRedirect(route('admin.orders.index'));
        $this->assertTrue($notif1->fresh()->is_read);
        $this->assertFalse($notif2->fresh()->is_read);

        // 4. Toggle unread back
        $this->actingAs($this->admin)->post(route('admin.notifications.toggle', $notif1->id));
        $this->assertFalse($notif1->fresh()->is_read);

        // 5. Mark all as read
        $this->actingAs($this->admin)->post(route('admin.notifications.mark-all-read'));
        $this->assertTrue($notif1->fresh()->is_read);
        $this->assertTrue($notif2->fresh()->is_read);

        // 6. Delete single notification
        $this->actingAs($this->admin)->delete(route('admin.notifications.destroy', $notif1->id));
        $this->assertDatabaseMissing('admin_notifications', ['id' => $notif1->id]);

        // 7. Delete all notifications
        $this->actingAs($this->admin)->delete(route('admin.notifications.destroy-all'));
        $this->assertDatabaseCount('admin_notifications', 0);
    }

    public function test_user_notification_lifecycle_including_read_redirect_toggle_and_mark_all(): void
    {
        // 1. Create customer notification
        $userNotif1 = UserNotification::create([
            'user_id' => $this->customer->id,
            'type' => 'order',
            'title' => 'Your Order #2001 Confirmed',
            'message' => 'Your order has been confirmed successfully.',
            'link' => route('user.orders'),
            'is_read' => false,
        ]);

        $userNotif2 = UserNotification::create([
            'user_id' => $this->customer->id,
            'type' => 'subscription',
            'title' => 'Subscription Activated',
            'message' => 'Your subscription is now active.',
            'link' => route('user.subscriptions'),
            'is_read' => false,
        ]);

        // 2. View user notifications list
        $viewResponse = $this->actingAs($this->customer)->get(route('user.notifications.index'));
        $viewResponse->assertOk();
        $viewResponse->assertSee('Your Order #2001 Confirmed');
        $viewResponse->assertSee('Subscription Activated');

        // 3. Click notification: marks as read and redirects to user.orders
        $clickResponse = $this->actingAs($this->customer)->get(route('user.notifications.read', $userNotif1->id));
        $clickResponse->assertRedirect(route('user.orders'));
        $this->assertTrue($userNotif1->fresh()->is_read);
        $this->assertFalse($userNotif2->fresh()->is_read);

        // 4. Toggle unread
        $this->actingAs($this->customer)->post(route('user.notifications.toggle', $userNotif1->id));
        $this->assertFalse($userNotif1->fresh()->is_read);

        // 5. Mark all read
        $this->actingAs($this->customer)->post(route('user.notifications.mark-all-read'));
        $this->assertTrue($userNotif1->fresh()->is_read);
        $this->assertTrue($userNotif2->fresh()->is_read);

        // 6. Delete single
        $this->actingAs($this->customer)->delete(route('user.notifications.destroy', $userNotif1->id));
        $this->assertDatabaseMissing('user_notifications', ['id' => $userNotif1->id]);

        // 7. Delete all
        $this->actingAs($this->customer)->delete(route('user.notifications.destroy-all'));
        $this->assertDatabaseCount('user_notifications', 0);
    }

    public function test_user_cannot_access_or_modify_another_users_notification(): void
    {
        $otherUser = User::factory()->create(['role' => 'user']);

        $secretNotif = UserNotification::create([
            'user_id' => $otherUser->id,
            'type' => 'order',
            'title' => 'Secret User Notification',
            'message' => 'Private information',
            'link' => route('user.orders'),
            'is_read' => false,
        ]);

        // Customer tries to read another user's notification
        $response = $this->actingAs($this->customer)->get(route('user.notifications.read', $secretNotif->id));
        $response->assertForbidden();

        // Customer tries to delete another user's notification
        $deleteResponse = $this->actingAs($this->customer)->delete(route('user.notifications.destroy', $secretNotif->id));
        $deleteResponse->assertForbidden();
    }
}
