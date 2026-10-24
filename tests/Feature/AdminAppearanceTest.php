<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminAppearanceTest extends TestCase
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

    public function test_guest_cannot_access_appearance_header_settings(): void
    {
        $response = $this->get(route('admin.appearance.header'));

        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_appearance_header_settings(): void
    {
        $response = $this->actingAs($this->regularUser)->get(route('admin.appearance.header'));

        $response->assertRedirect(route('user.dashboard'));
    }

    public function test_admin_can_view_appearance_header_settings_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.appearance.header'));

        $response->assertStatus(200);
        $response->assertSee('Header & Appearance Settings', false);
        $response->assertSee('Website Appearance');
        $response->assertSee('Logo & Branding', false);
        $response->assertSee('Top Bar & Hotline', false);
        $response->assertSee('SEO & Meta Data', false);
    }

    public function test_admin_can_view_dashboard_with_appearance_sidebar_link(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Website Appearance');
        $response->assertSee(route('admin.appearance.header'));
        $response->assertSee(route('admin.appearance.banners.index'));
        $response->assertSee(route('admin.appearance.footer'));
    }

    public function test_admin_can_update_header_and_topbar_settings(): void
    {
        $payload = [
            'site_name' => 'DigiGo SuperStore',
            'header_search_placeholder' => 'Find the best digital keys & software...',
            'topbar_support_label' => 'HOTLINE 24/7',
            'topbar_support_phone' => '+8801999999999',
            'topbar_whatsapp_label' => 'WHATSAPP HELP',
            'topbar_whatsapp_phone' => '+8801888888888',
            'topbar_announcement_enabled' => '1',
            'topbar_announcement_text' => 'Flash Sale 20% Off!',
            'topbar_announcement_link' => 'https://digigo.com.bd/offers',
            'meta_title' => 'DigiGo SuperStore - Software Keys',
            'meta_description' => 'Best software and subscriptions marketplace in BD.',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.appearance.header.update'), $payload);

        $response->assertRedirect(route('admin.appearance.header'));
        $response->assertSessionHas('success');

        $this->assertSame('DigiGo SuperStore', Setting::get('site_name'));
        $this->assertSame('+8801999999999', Setting::get('topbar_support_phone'));
        $this->assertSame('+8801888888888', Setting::get('topbar_whatsapp_phone'));
        $this->assertSame('1', Setting::get('topbar_announcement_enabled'));
        $this->assertSame('DigiGo SuperStore - Software Keys', Setting::get('meta_title'));

        // Verify that homepage reflects the updated settings
        $homeResponse = $this->get(route('homepage'));
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('+8801999999999');
        $homeResponse->assertSee('+8801888888888');
        $homeResponse->assertSee('Flash Sale 20% Off!');
        $homeResponse->assertSee('DigiGo SuperStore - Software Keys');
    }

    public function test_admin_can_upload_and_remove_logo_and_favicon(): void
    {
        Storage::fake('public');

        $logoFile = UploadedFile::fake()->image('custom_logo.png', 300, 80);
        $faviconFile = UploadedFile::fake()->image('custom_favicon.png', 64, 64);
        $ogFile = UploadedFile::fake()->image('custom_og.jpg', 1200, 630);

        $payload = [
            'site_name' => 'DigiGo',
            'site_logo' => $logoFile,
            'site_favicon' => $faviconFile,
            'og_image' => $ogFile,
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.appearance.header.update'), $payload);

        $response->assertRedirect(route('admin.appearance.header'));
        $response->assertSessionHas('success');

        $logoPath = Setting::get('site_logo');
        $faviconPath = Setting::get('site_favicon');
        $ogPath = Setting::get('og_image');

        $this->assertNotNull($logoPath);
        $this->assertNotNull($faviconPath);
        $this->assertNotNull($ogPath);

        Storage::disk('public')->assertExists($logoPath);
        Storage::disk('public')->assertExists($faviconPath);
        Storage::disk('public')->assertExists($ogPath);

        // Verify removal of custom files
        $removePayload = [
            'site_name' => 'DigiGo',
            'remove_site_logo' => '1',
            'remove_site_favicon' => '1',
            'remove_og_image' => '1',
        ];

        $removeResponse = $this->actingAs($this->admin)->put(route('admin.appearance.header.update'), $removePayload);

        $removeResponse->assertRedirect(route('admin.appearance.header'));

        $this->assertNull(Setting::get('site_logo'));
        $this->assertNull(Setting::get('site_favicon'));
        $this->assertNull(Setting::get('og_image'));

        Storage::disk('public')->assertMissing($logoPath);
        Storage::disk('public')->assertMissing($faviconPath);
        Storage::disk('public')->assertMissing($ogPath);
    }

    public function test_guest_cannot_access_appearance_footer_settings(): void
    {
        $response = $this->get(route('admin.appearance.footer'));

        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_appearance_footer_settings(): void
    {
        $response = $this->actingAs($this->regularUser)->get(route('admin.appearance.footer'));

        $response->assertRedirect(route('user.dashboard'));
    }

    public function test_admin_can_view_appearance_footer_settings_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.appearance.footer'));

        $response->assertStatus(200);
        $response->assertSee('Footer Section Settings', false);
        $response->assertSee('Brand & Description', false);
        $response->assertSee('Navigation Link Columns', false);
        $response->assertSee('Apps & Social Links', false);
        $response->assertSee('Copyright & Payment Badges', false);
    }

    public function test_admin_can_update_footer_settings_and_reflects_on_site(): void
    {
        $payload = [
            'footer_about_text' => 'Custom bio for DigiGo store testing.',
            'footer_col2_title' => 'Quick Policies',
            'footer_col2_link1_text' => 'Refunds & Returns',
            'footer_col2_link1_url' => 'https://digigo.com.bd/refunds',
            'footer_col3_title' => 'Customer Care',
            'footer_col3_link1_text' => 'Support Portal',
            'footer_col3_link1_url' => 'https://digigo.com.bd/support',
            'footer_app_title' => 'Get the Mobile App:',
            'footer_play_store_enabled' => '1',
            'footer_play_store_url' => 'https://play.google.com/store/apps/details?id=custom.digigo',
            'footer_app_store_enabled' => '0',
            'footer_social_title' => 'Connect With Us:',
            'footer_facebook_url' => 'https://facebook.com/customdigigo',
            'footer_instagram_url' => 'https://instagram.com/customdigigo',
            'footer_whatsapp_url' => 'https://wa.me/8801999999999',
            'footer_copyright_text' => '© 2026 DigiGo BD. Custom copyright info.',
            'footer_payment_methods_enabled' => '1',
            'footer_payment_dgepay' => '1',
            'footer_payment_bkash' => '1',
            'footer_payment_nagad' => '1',
            'footer_payment_rocket' => '0',
            'footer_payment_upay' => '0',
            'footer_payment_cards' => '1',
            'footer_floating_chat_enabled' => '1',
            'footer_floating_chat_link' => 'https://m.me/customdigigo',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.appearance.footer.update'), $payload);

        $response->assertRedirect(route('admin.appearance.footer'));
        $response->assertSessionHas('success');

        $this->assertSame('Custom bio for DigiGo store testing.', Setting::get('footer_about_text'));
        $this->assertSame('Quick Policies', Setting::get('footer_col2_title'));
        $this->assertSame('Refunds & Returns', Setting::get('footer_col2_link1_text'));
        $this->assertSame('© 2026 DigiGo BD. Custom copyright info.', Setting::get('footer_copyright_text'));
        $this->assertSame('1', Setting::get('footer_payment_dgepay'));
        $this->assertSame('0', Setting::get('footer_payment_rocket'));
        $this->assertSame('1', Setting::get('footer_payment_bkash'));

        // Verify public layout renders updated values
        $homeResponse = $this->get(route('homepage'));
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('Custom bio for DigiGo store testing.');
        $homeResponse->assertSee('Quick Policies');
        $homeResponse->assertSee('Refunds & Returns');
        $homeResponse->assertSee('Customer Care');
        $homeResponse->assertSee('Get the Mobile App:');
        $homeResponse->assertSee('© 2026 DigiGo BD. Custom copyright info.');
        $homeResponse->assertSee('assets/images/dgepay.png');
    }

    public function test_admin_can_upload_and_remove_custom_footer_logo(): void
    {
        Storage::fake('public');

        $footerLogo = UploadedFile::fake()->image('custom_footer_logo.png', 250, 70);

        $payload = [
            'footer_about_text' => 'Sample footer bio',
            'footer_col2_title' => 'Policies',
            'footer_col3_title' => 'Links',
            'footer_copyright_text' => 'Copyright text',
            'footer_custom_logo' => $footerLogo,
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.appearance.footer.update'), $payload);

        $response->assertRedirect(route('admin.appearance.footer'));
        $response->assertSessionHas('success');

        $logoPath = Setting::get('footer_custom_logo');
        $this->assertNotNull($logoPath);
        Storage::disk('public')->assertExists($logoPath);

        // Remove the custom footer logo
        $removePayload = [
            'footer_about_text' => 'Sample footer bio',
            'footer_col2_title' => 'Policies',
            'footer_col3_title' => 'Links',
            'footer_copyright_text' => 'Copyright text',
            'remove_footer_custom_logo' => '1',
        ];

        $removeResponse = $this->actingAs($this->admin)->put(route('admin.appearance.footer.update'), $removePayload);
        $removeResponse->assertRedirect(route('admin.appearance.footer'));

        $this->assertNull(Setting::get('footer_custom_logo'));
        Storage::disk('public')->assertMissing($logoPath);
    }
}
