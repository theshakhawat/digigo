<?php

namespace Tests\Feature;

use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSubscriberManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_subscriber_management(): void
    {
        $response = $this->get(route('admin.subscribers.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_subscriber_management(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.subscribers.index'));

        $response->assertRedirect(route('user.dashboard'));
    }

    public function test_admin_can_view_and_search_subscribers(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Subscriber::factory()->create(['email' => 'alpha@digigo.test', 'status' => 'active']);
        Subscriber::factory()->create(['email' => 'beta@digigo.test', 'status' => 'inactive']);

        $response = $this->actingAs($admin)->get(route('admin.subscribers.index'));

        $response->assertOk();
        $response->assertSee('alpha@digigo.test');
        $response->assertSee('beta@digigo.test');

        // Test search filter
        $searchResponse = $this->actingAs($admin)->get(route('admin.subscribers.index', ['search' => 'alpha']));
        $searchResponse->assertOk();
        $searchResponse->assertSee('alpha@digigo.test');
        $searchResponse->assertDontSee('beta@digigo.test');
    }

    public function test_admin_can_toggle_subscriber_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $subscriber = Subscriber::factory()->create(['status' => 'active']);

        $response = $this->actingAs($admin)->post(route('admin.subscribers.toggle-status', $subscriber->id));

        $response->assertRedirect();
        $this->assertEquals('inactive', $subscriber->fresh()->status);

        $response2 = $this->actingAs($admin)->post(route('admin.subscribers.toggle-status', $subscriber->id));
        $response2->assertRedirect();
        $this->assertEquals('active', $subscriber->fresh()->status);
    }

    public function test_admin_can_delete_subscriber(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $subscriber = Subscriber::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.subscribers.destroy', $subscriber->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('subscribers', ['id' => $subscriber->id]);
    }
}
