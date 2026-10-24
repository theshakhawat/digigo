<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSystemClearTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_trigger_system_cache_clear(): void
    {
        $response = $this->post(route('admin.system.clear-cache'));

        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_trigger_system_cache_clear(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->post(route('admin.system.clear-cache'));

        $response->assertRedirect(route('user.dashboard'));
    }

    public function test_admin_can_successfully_clear_system_cache(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->postJson(route('admin.system.clear-cache'));

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);
    }
}
