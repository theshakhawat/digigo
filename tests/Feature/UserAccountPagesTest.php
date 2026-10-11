<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserAccountPagesTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => 'user',
            'status' => 'active',
            'password' => Hash::make('password123'),
        ]);
    }

    public function test_user_dashboard_page_loads_successfully(): void
    {
        $response = $this->actingAs($this->user)->get(route('user.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('My account');
        $response->assertSee('Orders');
    }

    public function test_user_orders_page_loads_successfully(): void
    {
        $response = $this->actingAs($this->user)->get(route('user.orders'));

        $response->assertStatus(200);
        $response->assertSee('No order has been made yet.');
    }

    public function test_user_downloads_page_loads_successfully(): void
    {
        $response = $this->actingAs($this->user)->get(route('user.downloads'));

        $response->assertStatus(200);
        $response->assertSee('No downloads available yet.');
    }

    public function test_user_addresses_page_loads_successfully(): void
    {
        $response = $this->actingAs($this->user)->get(route('user.addresses'));

        $response->assertStatus(200);
        $response->assertSee('Billing address');
        $response->assertSee('Shipping address');
    }

    public function test_user_account_details_page_loads_and_updates(): void
    {
        $response = $this->actingAs($this->user)->get(route('user.account-details'));

        $response->assertStatus(200);
        $response->assertSee('Account Details');

        $updateResponse = $this->actingAs($this->user)->post(route('user.account-details.update'), [
            'name' => 'Updated User Name',
            'email' => $this->user->email,
            'phone' => '01799887766',
        ]);

        $updateResponse->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Updated User Name',
            'phone' => '01799887766',
        ]);
    }

    public function test_user_coupons_page_loads_successfully(): void
    {
        $response = $this->actingAs($this->user)->get(route('user.coupons'));

        $response->assertStatus(200);
        $response->assertSee('My Coupons');
    }

    public function test_user_rewards_page_loads_successfully(): void
    {
        $response = $this->actingAs($this->user)->get(route('user.rewards'));

        $response->assertStatus(200);
        $response->assertSee('Points & Rewards');
    }

    public function test_user_wishlist_page_loads_successfully(): void
    {
        $response = $this->actingAs($this->user)->get(route('user.wishlist'));

        $response->assertStatus(200);
        $response->assertSee('Wishlist');
    }
}
