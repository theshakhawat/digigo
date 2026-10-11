<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@digigo.com.bd',
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->customer = User::factory()->create([
            'name' => 'John Customer',
            'email' => 'john@example.com',
            'role' => 'user',
            'status' => 'active',
        ]);
    }

    public function test_guest_cannot_access_user_management(): void
    {
        $response = $this->get(route('admin.users.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_user_management(): void
    {
        $response = $this->actingAs($this->customer)->get(route('admin.users.index'));
        $response->assertRedirect(route('user.dashboard'));
    }

    public function test_admin_can_view_users_list_and_filter_by_role(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));
        $response->assertStatus(200);
        $response->assertSee('Users Management');
        $response->assertSee('John Customer');
        $response->assertSee('Super Admin');

        // Filter role=admin
        $adminResponse = $this->actingAs($this->admin)->get(route('admin.users.index', ['role' => 'admin']));
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('Super Admin');

        // Filter role=user
        $userResponse = $this->actingAs($this->admin)->get(route('admin.users.index', ['role' => 'user']));
        $userResponse->assertStatus(200);
        $userResponse->assertSee('John Customer');
    }

    public function test_admin_can_view_create_user_form(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.create'));
        $response->assertStatus(200);
        $response->assertSee('Create User Account');
    }

    public function test_admin_can_create_new_customer(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'name' => 'New Customer',
            'email' => 'newcustomer@example.com',
            'phone' => '01711223344',
            'role' => 'user',
            'status' => 'active',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('admin.users.index', ['role' => 'user']));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'New Customer',
            'email' => 'newcustomer@example.com',
            'role' => 'user',
            'status' => 'active',
        ]);

        $newUser = User::where('email', 'newcustomer@example.com')->first();
        $this->assertTrue(Hash::check('password123', $newUser->password));
    }

    public function test_admin_can_create_new_staff_administrator(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'name' => 'Staff Admin',
            'email' => 'staffadmin@digigo.com.bd',
            'phone' => '01811223344',
            'role' => 'admin',
            'status' => 'active',
            'password' => 'adminSecure123!',
            'password_confirmation' => 'adminSecure123!',
        ]);

        $response->assertRedirect(route('admin.users.index', ['role' => 'admin']));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Staff Admin',
            'email' => 'staffadmin@digigo.com.bd',
            'role' => 'admin',
            'status' => 'active',
        ]);
    }

    public function test_admin_can_view_edit_user_form(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.edit', $this->customer));
        $response->assertStatus(200);
        $response->assertSee('Edit Account: John Customer');
    }

    public function test_admin_can_update_user_details(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $this->customer), [
            'name' => 'John Updated',
            'email' => 'john.updated@example.com',
            'phone' => '01999887766',
            'role' => 'user',
            'status' => 'inactive',
        ]);

        $response->assertRedirect(route('admin.users.index', ['role' => 'user']));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $this->customer->id,
            'name' => 'John Updated',
            'email' => 'john.updated@example.com',
            'status' => 'inactive',
        ]);
    }

    public function test_admin_cannot_demote_their_own_account(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $this->admin), [
            'name' => 'Super Admin Attempt',
            'email' => $this->admin->email,
            'role' => 'user', // attempt to change to user
            'status' => 'active',
        ]);

        $response->assertSessionHas('error');

        $this->admin->refresh();
        $this->assertEquals('admin', $this->admin->role);
    }

    public function test_admin_cannot_delete_their_own_account(): void
    {
        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $this->admin));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
        ]);
    }

    public function test_admin_can_delete_another_user(): void
    {
        $userToDelete = User::factory()->create([
            'name' => 'To Be Deleted',
            'email' => 'delete_me@example.com',
            'role' => 'user',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $userToDelete));

        $response->assertRedirect(route('admin.users.index', ['role' => 'user']));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', [
            'id' => $userToDelete->id,
        ]);
    }
}
