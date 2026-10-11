<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminBannerCrudTest extends TestCase
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

    public function test_guest_cannot_access_banner_management(): void
    {
        $response = $this->get(route('admin.appearance.banners.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_banner_management(): void
    {
        $response = $this->actingAs($this->regularUser)->get(route('admin.appearance.banners.index'));

        $response->assertRedirect(route('user.dashboard'));
    }

    public function test_admin_can_view_banners_index_page(): void
    {
        Banner::factory()->create([
            'title' => 'Smart Devices ⌚ & Wearable Tech',
            'status' => 'active',
            'order' => 1,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.appearance.banners.index'));

        $response->assertStatus(200);
        $response->assertSee('Hero Banner Management', false);
        $response->assertSee('Smart Devices', false);
        $response->assertSee('Carousel Controls', false);
        $response->assertSee('customDeleteModal', false);
        $response->assertSee('js-custom-delete', false);
    }

    public function test_admin_can_view_create_banner_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.appearance.banners.create'));

        $response->assertStatus(200);
        $response->assertSee('Add Hero Banner Slide', false);
        $response->assertSee('Publish Banner', false);
    }

    public function test_admin_can_create_banner_with_uploaded_file(): void
    {
        Storage::fake('public');

        $imageFile = UploadedFile::fake()->image('hero_banner.jpg', 1600, 500);

        $payload = [
            'title' => 'Gaming Laptops & Consoles 🎮',
            'subtitle' => 'Unleash ultra-performance gaming with RTX 40-series machines.',
            'button_text' => 'SHOP GAMING',
            'button_link' => '/category/gaming',
            'image_file' => $imageFile,
            'order' => 5,
            'status' => 'active',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.appearance.banners.store'), $payload);

        $response->assertRedirect(route('admin.appearance.banners.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('banners', [
            'title' => 'Gaming Laptops & Consoles 🎮',
            'button_text' => 'SHOP GAMING',
            'button_link' => '/category/gaming',
            'order' => 5,
            'status' => 'active',
        ]);

        $banner = Banner::where('title', 'Gaming Laptops & Consoles 🎮')->first();
        $this->assertNotNull($banner);
        Storage::disk('public')->assertExists($banner->image);
    }

    public function test_admin_can_create_banner_with_external_image_url(): void
    {
        $payload = [
            'title' => 'AI Power Tools 🚀',
            'subtitle' => 'Supercharge your team with licensed software.',
            'button_text' => 'EXPLORE AI',
            'button_link' => 'https://digigo.com.bd/ai-tools',
            'image_url' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe',
            'order' => 2,
            'status' => 'active',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.appearance.banners.store'), $payload);

        $response->assertRedirect(route('admin.appearance.banners.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('banners', [
            'title' => 'AI Power Tools 🚀',
            'image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe',
        ]);
    }

    public function test_admin_can_view_edit_banner_page(): void
    {
        $banner = Banner::factory()->create([
            'title' => 'Editable Slide Headline',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.appearance.banners.edit', $banner));

        $response->assertStatus(200);
        $response->assertSee('Edit Hero Banner Slide', false);
        $response->assertSee('Editable Slide Headline', false);
    }

    public function test_admin_can_update_banner_details(): void
    {
        $banner = Banner::factory()->create([
            'title' => 'Old Headline',
            'button_text' => 'OLD CTA',
            'order' => 1,
        ]);

        $payload = [
            'title' => 'New Updated Headline 🔥',
            'subtitle' => 'Updated description copy.',
            'button_text' => 'NEW CTA',
            'button_link' => '/new-link',
            'order' => 2,
            'status' => 'active',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.appearance.banners.update', $banner), $payload);

        $response->assertRedirect(route('admin.appearance.banners.index'));
        $response->assertSessionHas('success');

        $banner->refresh();
        $this->assertSame('New Updated Headline 🔥', $banner->title);
        $this->assertSame('NEW CTA', $banner->button_text);
        $this->assertSame(2, $banner->order);
    }

    public function test_admin_can_toggle_banner_status(): void
    {
        $banner = Banner::factory()->create([
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.appearance.banners.toggle-status', $banner));

        $response->assertRedirect(route('admin.appearance.banners.index'));
        $this->assertSame('inactive', $banner->fresh()->status);

        $this->actingAs($this->admin)->post(route('admin.appearance.banners.toggle-status', $banner));
        $this->assertSame('active', $banner->fresh()->status);
    }

    public function test_admin_can_delete_banner_and_removes_file_from_storage(): void
    {
        Storage::fake('public');

        $imageFile = UploadedFile::fake()->image('to_delete.jpg', 800, 400);
        $path = $imageFile->store('banners', 'public');

        $banner = Banner::factory()->create([
            'title' => 'Banner To Delete',
            'image' => $path,
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($this->admin)->delete(route('admin.appearance.banners.destroy', $banner));

        $response->assertRedirect(route('admin.appearance.banners.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('banners', ['id' => $banner->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_admin_can_update_carousel_settings(): void
    {
        $payload = [
            'banner_autoplay' => '1',
            'banner_delay' => '6000',
            'banner_arrows' => '1',
            'banner_dots' => '0',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.appearance.banners.settings.update'), $payload);

        $response->assertRedirect(route('admin.appearance.banners.index'));
        $response->assertSessionHas('success');

        $this->assertSame('1', Setting::get('banner_autoplay'));
        $this->assertSame('6000', Setting::get('banner_delay'));
        $this->assertSame('1', Setting::get('banner_arrows'));
        $this->assertSame('0', Setting::get('banner_dots'));
    }

    public function test_homepage_displays_active_banners_and_respects_settings(): void
    {
        Banner::factory()->create([
            'title' => 'Active Slide On Homepage 🚀',
            'subtitle' => 'Homepage description testing.',
            'button_text' => 'HOMEPAGE CTA',
            'status' => 'active',
            'order' => 1,
        ]);

        Banner::factory()->create([
            'title' => 'Hidden Inactive Slide 🙈',
            'status' => 'inactive',
            'order' => 2,
        ]);

        Setting::set('banner_dots', '1', 'banner', 'boolean');
        Setting::set('banner_arrows', '1', 'banner', 'boolean');
        Setting::set('banner_delay', '5000', 'banner', 'text');
        Setting::clearCache();

        $response = $this->get(route('homepage'));

        $response->assertStatus(200);
        $response->assertSee('Active Slide On Homepage 🚀', false);
        $response->assertSee('Homepage description testing.', false);
        $response->assertSee('HOMEPAGE CTA', false);
        $response->assertDontSee('Hidden Inactive Slide 🙈', false);
        $response->assertSee('data-delay="5000"', false);
    }

    public function test_admin_can_view_trust_features_ticker_tab(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.appearance.banners.index', ['tab' => 'ticker']));

        $response->assertStatus(200);
        $response->assertSee('Scrolling Trust Features Ticker', false);
        $response->assertSee('Live Marquee Preview', false);
        $response->assertSee('Trust Feature Badges', false);
        $response->assertSee('Popular Icon Quick Presets', false);
    }

    public function test_admin_can_update_trust_features_marquee_settings(): void
    {
        $payload = [
            'trust_features_enabled' => '1',
            'trust_features_speed' => '24',
            'items' => [
                [
                    'icon' => 'fa-solid fa-bolt',
                    'text' => 'Instant Digital Key Delivery ⚡',
                    'link' => '/instant-delivery',
                ],
                [
                    'icon' => 'fa-solid fa-lock',
                    'text' => '256-bit SSL Encrypted Checkout 🔒',
                    'link' => '/secure-payment',
                ],
            ],
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.appearance.banners.trust-features.update'), $payload);

        $response->assertRedirect(route('admin.appearance.banners.index', ['tab' => 'ticker']));
        $response->assertSessionHas('success');

        $this->assertSame('1', Setting::get('trust_features_enabled'));
        $this->assertSame('24', Setting::get('trust_features_speed'));

        $items = json_decode((string) Setting::get('trust_features_items'), true);
        $this->assertCount(2, $items);
        $this->assertSame('Instant Digital Key Delivery ⚡', $items[0]['text']);
        $this->assertSame('fa-solid fa-bolt', $items[0]['icon']);
        $this->assertSame('/instant-delivery', $items[0]['link']);
    }

    public function test_homepage_renders_dynamic_trust_features_marquee(): void
    {
        Setting::set('trust_features_enabled', '1', 'banner', 'boolean');
        Setting::set('trust_features_speed', '22', 'banner', 'text');
        Setting::set('trust_features_items', json_encode([
            [
                'icon' => 'fa-solid fa-award',
                'text' => '100% Genuine Digital Licenses Only',
                'link' => '/genuine-guarantee',
            ],
        ]), 'banner', 'text');
        Setting::clearCache();

        $response = $this->get(route('homepage'));

        $response->assertStatus(200);
        $response->assertSee('100% Genuine Digital Licenses Only', false);
        $response->assertSee('fa-solid fa-award', false);
        $response->assertSee('/genuine-guarantee', false);
        $response->assertSee('animation-duration: 22s', false);
    }

    public function test_homepage_hides_trust_features_marquee_when_disabled(): void
    {
        Setting::set('trust_features_enabled', '0', 'banner', 'boolean');
        Setting::set('trust_features_items', json_encode([
            [
                'icon' => 'fa-solid fa-award',
                'text' => 'This Badge Should Be Hidden',
                'link' => '',
            ],
        ]), 'banner', 'text');
        Setting::clearCache();

        $response = $this->get(route('homepage'));

        $response->assertStatus(200);
        $response->assertDontSee('This Badge Should Be Hidden', false);
    }
}
