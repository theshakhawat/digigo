<?php

namespace Tests\Feature;

use App\Jobs\SendEmailJob;
use App\Mail\SmtpTestMail;
use App\Models\Setting;
use App\Models\User;
use App\Services\MailConfigService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminSmtpSettingTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->regularUser = User::factory()->create([
            'role' => 'user',
            'status' => 'active',
        ]);
    }

    public function test_guest_cannot_access_smtp_settings(): void
    {
        $response = $this->get(route('admin.settings.smtp'));

        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_smtp_settings(): void
    {
        $response = $this->actingAs($this->regularUser)->get(route('admin.settings.smtp'));

        $response->assertRedirect(route('user.dashboard'));
    }

    public function test_admin_can_view_smtp_settings_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.settings.smtp'));

        $response->assertStatus(200);
        $response->assertSee('SMTP & Email Configuration', false);
        $response->assertSee('Mail Server Connection');
        $response->assertSee('Authentication Credentials');
        $response->assertSee('Send Test Email');
    }

    public function test_admin_can_update_smtp_settings(): void
    {
        $data = [
            'mail_mailer' => 'smtp',
            'mail_host' => 'smtp.mailtrap.io',
            'mail_port' => '2525',
            'mail_username' => 'testuser123',
            'mail_password' => 'secretpass456',
            'mail_encryption' => 'tls',
            'mail_from_address' => 'hello@digigo.com.bd',
            'mail_from_name' => 'DigiGo SuperStore',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.settings.smtp.update'), $data);

        $response->assertRedirect(route('admin.settings.smtp'));
        $response->assertSessionHas('success');

        $this->assertEquals('smtp.mailtrap.io', Setting::get('mail_host'));
        $this->assertEquals('2525', Setting::get('mail_port'));
        $this->assertEquals('testuser123', Setting::get('mail_username'));
        $this->assertEquals('secretpass456', Setting::get('mail_password'));
        $this->assertEquals('tls', Setting::get('mail_encryption'));
        $this->assertEquals('hello@digigo.com.bd', Setting::get('mail_from_address'));
        $this->assertEquals('DigiGo SuperStore', Setting::get('mail_from_name'));

        // Verify runtime configuration was updated
        $this->assertEquals('smtp.mailtrap.io', config('mail.mailers.smtp.host'));
        $this->assertEquals(2525, config('mail.mailers.smtp.port'));
        $this->assertEquals('testuser123', config('mail.mailers.smtp.username'));
        $this->assertEquals('secretpass456', config('mail.mailers.smtp.password'));
        $this->assertEquals('hello@digigo.com.bd', config('mail.from.address'));
        $this->assertEquals('DigiGo SuperStore', config('mail.from.name'));
    }

    public function test_admin_can_update_smtp_settings_without_overwriting_password_if_blank(): void
    {
        Setting::set('mail_password', 'original_secure_password', 'smtp', 'password');

        $data = [
            'mail_mailer' => 'smtp',
            'mail_host' => 'smtp.gmail.com',
            'mail_port' => '587',
            'mail_username' => 'support@digigo.com.bd',
            'mail_password' => '', // Left empty
            'mail_encryption' => 'tls',
            'mail_from_address' => 'support@digigo.com.bd',
            'mail_from_name' => 'DigiGo Support',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.settings.smtp.update'), $data);

        $response->assertRedirect(route('admin.settings.smtp'));
        $response->assertSessionHas('success');

        // Password should remain unchanged
        $this->assertEquals('original_secure_password', Setting::get('mail_password'));
    }

    public function test_admin_can_send_test_email(): void
    {
        Mail::fake();

        $response = $this->actingAs($this->admin)->post(route('admin.settings.smtp.test'), [
            'test_email' => 'client@example.com',
        ]);

        $response->assertRedirect(route('admin.settings.smtp'));
        $response->assertSessionHas('success');

        Mail::assertSent(SmtpTestMail::class, function ($mail) {
            return $mail->hasTo('client@example.com');
        });
    }

    public function test_admin_can_send_test_email_via_queue(): void
    {
        Mail::fake();

        $response = $this->actingAs($this->admin)->post(route('admin.settings.smtp.test'), [
            'test_email' => 'client@example.com',
            'send_via_queue' => '1',
        ]);

        $response->assertRedirect(route('admin.settings.smtp'));
        $response->assertSessionHas('success');

        Mail::assertQueued(SmtpTestMail::class, function ($mail) {
            return $mail->hasTo('client@example.com');
        });
    }

    public function test_test_email_requires_valid_email(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.settings.smtp.test'), [
            'test_email' => 'not-an-email',
        ]);

        $response->assertSessionHasErrors(['test_email']);
    }

    public function test_mail_config_service_applies_settings_from_database(): void
    {
        Setting::set('mail_mailer', 'smtp', 'smtp');
        Setting::set('mail_host', 'custom.smtp.server.com', 'smtp');
        Setting::set('mail_port', '465', 'smtp');
        Setting::set('mail_encryption', 'ssl', 'smtp');
        Setting::set('mail_username', 'smtp_user', 'smtp');
        Setting::set('mail_password', 'smtp_secret', 'smtp');
        Setting::set('mail_from_address', 'orders@digigo.com.bd', 'smtp');
        Setting::set('mail_from_name', 'DigiGo Orders', 'smtp');

        $service = app(MailConfigService::class);
        $service->apply();

        $this->assertEquals('custom.smtp.server.com', config('mail.mailers.smtp.host'));
        $this->assertEquals(465, config('mail.mailers.smtp.port'));
        $this->assertEquals('smtps', config('mail.mailers.smtp.scheme'));
        $this->assertEquals('smtp_user', config('mail.mailers.smtp.username'));
        $this->assertEquals('smtp_secret', config('mail.mailers.smtp.password'));
        $this->assertEquals('orders@digigo.com.bd', config('mail.from.address'));
        $this->assertEquals('DigiGo Orders', config('mail.from.name'));
    }

    public function test_send_email_job_executes_successfully(): void
    {
        Mail::fake();

        $mailable = new SmtpTestMail(['sent_at' => 'now']);
        $job = new SendEmailJob('queued-user@example.com', $mailable);
        $job->handle();

        Mail::assertSent(SmtpTestMail::class, function ($mail) {
            return $mail->hasTo('queued-user@example.com');
        });
    }
}
