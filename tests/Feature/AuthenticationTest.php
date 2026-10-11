<?php

namespace Tests\Feature;

use App\Mail\ResetPasswordMail;
use App\Mail\WelcomeUserMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Sign In');
    }

    public function test_register_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Create an Account');
    }

    public function test_user_can_register_successfully(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '01711223344',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '01711223344',
            'role' => 'user',
            'status' => 'active',
        ]);
        $response->assertRedirect(route('user.dashboard'));
    }

    public function test_user_can_login_and_redirects_to_user_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('user.dashboard'));
    }

    public function test_admin_can_login_and_redirects_to_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_inactive_user_cannot_login(): void
    {
        User::factory()->create([
            'email' => 'inactive@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'status' => 'inactive',
        ]);

        $response = $this->post('/login', [
            'email' => 'inactive@example.com',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHas('error');
    }

    public function test_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertRedirect(route('user.dashboard'));
    }

    public function test_admin_cannot_access_user_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->get(route('user.dashboard'));

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_forgot_password_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
        $response->assertSee('Forgot Password?');
    }

    public function test_password_reset_email_is_queued_when_requested(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'customer@example.com',
        ]);

        $response = $this->post('/forgot-password', [
            'email' => 'customer@example.com',
        ]);

        $response->assertSessionHas('status');

        Mail::assertQueued(ResetPasswordMail::class, function ($mail) {
            return $mail->hasTo('customer@example.com');
        });
    }

    public function test_welcome_email_is_queued_upon_user_registration(): void
    {
        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'New Customer',
            'email' => 'newcustomer@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('user.dashboard'));

        Mail::assertQueued(WelcomeUserMail::class, function ($mail) {
            return $mail->hasTo('newcustomer@example.com');
        });
    }
}
