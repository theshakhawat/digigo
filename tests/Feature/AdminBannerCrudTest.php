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

    public function test_admin_can_view_promotional_showcase_banner_tab(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.appearance.banners.index', ['tab' => 'promo']));

        $response->assertStatus(200);
        $response->assertSee('Promotional Showcase Banner', false);
        $response->assertSee('promoBannerForm', false);
        $response->assertSee('promo_badge', false);
        $response->assertSee('promo_title', false);
        $response->assertSee('promo_subtitle', false);
        $response->assertSee('promo_button_text', false);
        $response->assertSee('promo_button_link', false);
        $response->assertSee('Floating Visual 1', false);
        $response->assertSee('Floating Visual 2', false);
        $response->assertSee('Lifestyle / Background Backdrop Image', false);
    }

    public function test_admin_can_update_promotional_showcase_banner_settings(): void
    {
        Storage::fake('public');

        $visual1 = UploadedFile::fake()->image('earbud1.png', 400, 400);
        $visual2 = UploadedFile::fake()->image('earbud2.png', 400, 400);
        $backdrop = UploadedFile::fake()->image('lifestyle.jpg', 1200, 600);

        $payload = [
            'promo_banner_enabled' => '1',
            'promo_badge' => 'CUSTOM-WF2000',
            'promo_title' => 'দারুণ সব গ্যাজেট ডিসকাউন্ট অফার!',
            'promo_subtitle' => 'আজই লুফে নিন আপনার পছন্দের হেডফোন ও অডিও ডিভাইস।',
            'promo_button_text' => 'Order Special',
            'promo_button_link' => '/product/custom-wf2000',
            'promo_visual_1' => $visual1,
            'promo_visual_2' => $visual2,
            'promo_backdrop' => $backdrop,
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.appearance.banners.promo.update'), $payload);

        $response->assertRedirect(route('admin.appearance.banners.index', ['tab' => 'promo']));
        $response->assertSessionHas('success');

        $this->assertSame('1', Setting::get('promo_banner_enabled'));
        $this->assertSame('CUSTOM-WF2000', Setting::get('promo_badge'));
        $this->assertSame('দারুণ সব গ্যাজেট ডিসকাউন্ট অফার!', Setting::get('promo_title'));
        $this->assertSame('আজই লুফে নিন আপনার পছন্দের হেডফোন ও অডিও ডিভাইস।', Setting::get('promo_subtitle'));
        $this->assertSame('Order Special', Setting::get('promo_button_text'));
        $this->assertSame('/product/custom-wf2000', Setting::get('promo_button_link'));

        $this->assertNotEmpty(Setting::get('promo_visual_1'));
        $this->assertNotEmpty(Setting::get('promo_visual_2'));
        $this->assertNotEmpty(Setting::get('promo_backdrop'));

        Storage::disk('public')->assertExists(Setting::get('promo_visual_1'));
        Storage::disk('public')->assertExists(Setting::get('promo_visual_2'));
        Storage::disk('public')->assertExists(Setting::get('promo_backdrop'));
    }

    public function test_homepage_renders_customized_promotional_showcase_banner(): void
    {
        Setting::set('promo_banner_enabled', '1', 'promo', 'boolean');
        Setting::set('promo_badge', 'SPECIAL-AUDIO-EDITION', 'promo', 'text');
        Setting::set('promo_title', 'অসাধারণ মিউজিক এক্সপেরিয়েন্স', 'promo', 'text');
        Setting::set('promo_subtitle', 'বেস্ট নয়েজ ক্যান্সেলেশন হেডফোন', 'promo', 'text');
        Setting::set('promo_button_text', 'Grab Deal Now', 'promo', 'text');
        Setting::set('promo_button_link', '/product/audio-edition', 'promo', 'text');
        Setting::clearCache();

        $response = $this->get(route('homepage'));

        $response->assertStatus(200);
        $response->assertSee('SPECIAL-AUDIO-EDITION', false);
        $response->assertSee('অসাধারণ মিউজিক এক্সপেরিয়েন্স', false);
        $response->assertSee('বেস্ট নয়েজ ক্যান্সেলেশন হেডফোন', false);
        $response->assertSee('Grab Deal Now', false);
        $response->assertSee('/product/audio-edition', false);
    }

    public function test_homepage_hides_promotional_showcase_banner_when_disabled(): void
    {
        Setting::set('promo_banner_enabled', '0', 'promo', 'boolean');
        Setting::set('promo_badge', 'HIDDEN-PROMO-BADGE', 'promo', 'text');
        Setting::set('promo_title', 'This Title Must Be Hidden', 'promo', 'text');
        Setting::clearCache();

        $response = $this->get(route('homepage'));

        $response->assertStatus(200);
        $response->assertDontSee('HIDDEN-PROMO-BADGE', false);
        $response->assertDontSee('This Title Must Be Hidden', false);
    }

    public function test_admin_can_view_tri_promo_tab(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.appearance.banners.index', ['tab' => 'tri_promo']));

        $response->assertStatus(200);
        $response->assertSee('3-Card Feature Highlights', false);
        $response->assertSee('triPromoForm', false);
        $response->assertSee('tri_promo_1_title', false);
        $response->assertSee('tri_promo_2_title', false);
        $response->assertSee('tri_promo_3_title', false);
    }

    public function test_admin_can_update_tri_promo_cards(): void
    {
        Storage::fake('public');

        $img1 = UploadedFile::fake()->image('custom_watch.png', 400, 400);

        $payload = [
            'tri_promo_enabled' => '1',
            'tri_promo_1_theme' => 'light',
            'tri_promo_1_title' => 'স্মার্ট ওয়াচ এক্সট্রিম',
            'tri_promo_1_desc' => 'মিলিটারি গ্রেড বডি ও অ্যামোলেড ডিসপ্লে',
            'tri_promo_1_button_text' => 'এখনই অর্ডার করুন',
            'tri_promo_1_button_link' => '/product/smartwatch-extreme',
            'tri_promo_1_img' => $img1,

            'tri_promo_2_theme' => 'dark',
            'tri_promo_2_title' => 'ডুয়াল টোন ইয়ারবাডস প্রো',
            'tri_promo_2_desc' => 'ডিপ ব্যাস ও অ্যাক্টিভ নয়েজ ক্যান্সেলেশন',
            'tri_promo_2_button_text' => 'লুফে নিন',
            'tri_promo_2_button_link' => '/product/earbuds-pro',

            'tri_promo_3_theme' => 'light',
            'tri_promo_3_title' => 'প্রফেশনাল আরজিবি মাইক',
            'tri_promo_3_desc' => 'গেমিং ও পডকাস্টের জন্য সেরা মাইক্রোফোন',
            'tri_promo_3_button_text' => 'বিস্তারিত দেখুন',
            'tri_promo_3_button_link' => '/product/rgb-mic',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.appearance.banners.tri-promo.update'), $payload);

        $response->assertRedirect(route('admin.appearance.banners.index', ['tab' => 'tri_promo']));
        $response->assertSessionHas('success');

        $this->assertSame('1', Setting::get('tri_promo_enabled'));
        $this->assertSame('স্মার্ট ওয়াচ এক্সট্রিম', Setting::get('tri_promo_1_title'));
        $this->assertSame('ডুয়াল টোন ইয়ারবাডস প্রো', Setting::get('tri_promo_2_title'));
        $this->assertSame('প্রফেশনাল আরজিবি মাইক', Setting::get('tri_promo_3_title'));
        $this->assertNotEmpty(Setting::get('tri_promo_1_img'));
        Storage::disk('public')->assertExists(Setting::get('tri_promo_1_img'));
    }

    public function test_homepage_renders_dynamic_tri_promo_cards(): void
    {
        Setting::set('tri_promo_enabled', '1', 'tri_promo', 'boolean');
        Setting::set('tri_promo_1_title', 'স্পেশাল ওয়াচ কালেকশন', 'tri_promo', 'text');
        Setting::set('tri_promo_2_title', 'গেমিং ইয়ারবাডস আল্ট্রা', 'tri_promo', 'text');
        Setting::set('tri_promo_3_title', 'স্টুডিও কোয়ালিটি মাইক', 'tri_promo', 'text');
        Setting::clearCache();

        $response = $this->get(route('homepage'));

        $response->assertStatus(200);
        $response->assertSee('স্পেশাল ওয়াচ কালেকশন', false);
        $response->assertSee('গেমিং ইয়ারবাডস আল্ট্রা', false);
        $response->assertSee('স্টুডিও কোয়ালিটি মাইক', false);
    }

    public function test_homepage_hides_tri_promo_when_disabled(): void
    {
        Setting::set('tri_promo_enabled', '0', 'tri_promo', 'boolean');
        Setting::set('tri_promo_1_title', 'Hidden Tri Promo Watch', 'tri_promo', 'text');
        Setting::clearCache();

        $response = $this->get(route('homepage'));

        $response->assertStatus(200);
        $response->assertDontSee('Hidden Tri Promo Watch', false);
    }

    public function test_admin_can_view_remini_banner_tab(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.appearance.banners.index', ['tab' => 'remini']));

        $response->assertStatus(200);
        $response->assertSee('AI Tool Promotional Banner', false);
        $response->assertSee('reminiBannerForm', false);
        $response->assertSee('remini_badge', false);
        $response->assertSee('remini_title', false);
        $response->assertSee('remini_app_name', false);
        $response->assertSee('remini_bg_color', false);
    }

    public function test_admin_can_update_remini_banner(): void
    {
        Storage::fake('public');

        $appIcon = UploadedFile::fake()->image('custom_icon.png', 200, 200);
        $landscape = UploadedFile::fake()->image('landscape.jpg', 1000, 600);

        $payload = [
            'remini_banner_enabled' => '1',
            'remini_badge' => 'TOP AI ENHANCER',
            'remini_title' => 'ঝাপসা ছবিকে স্পষ্ট করার জাদুকরী টুল',
            'remini_title_highlight' => 'আল্ট্রা এইচডি!',
            'remini_button_text' => 'এখনই নিন',
            'remini_button_link' => '/product/remini-super',
            'remini_app_name' => 'Remini Pro Max',
            'remini_bg_color' => '#f87171',
            'remini_badge_color' => '#991b1b',
            'remini_app_bg_color' => '#7f1d1d',
            'remini_app_icon' => $appIcon,
            'remini_landscape_img' => $landscape,
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.appearance.banners.remini.update'), $payload);

        $response->assertRedirect(route('admin.appearance.banners.index', ['tab' => 'remini']));
        $response->assertSessionHas('success');

        $this->assertSame('1', Setting::get('remini_banner_enabled'));
        $this->assertSame('TOP AI ENHANCER', Setting::get('remini_badge'));
        $this->assertSame('ঝাপসা ছবিকে স্পষ্ট করার জাদুকরী টুল', Setting::get('remini_title'));
        $this->assertSame('আল্ট্রা এইচডি!', Setting::get('remini_title_highlight'));
        $this->assertSame('Remini Pro Max', Setting::get('remini_app_name'));
        $this->assertNotEmpty(Setting::get('remini_app_icon'));
        $this->assertNotEmpty(Setting::get('remini_landscape_img'));

        Storage::disk('public')->assertExists(Setting::get('remini_app_icon'));
        Storage::disk('public')->assertExists(Setting::get('remini_landscape_img'));
    }

    public function test_homepage_renders_dynamic_remini_banner(): void
    {
        Setting::set('remini_banner_enabled', '1', 'remini', 'boolean');
        Setting::set('remini_badge', 'CUSTOM AI BADGE', 'remini', 'text');
        Setting::set('remini_title', 'আপনার সাধারণ ছবিকে করে তুলুন', 'remini', 'text');
        Setting::set('remini_title_highlight', 'হাই রেজোলিউশন!', 'remini', 'text');
        Setting::set('remini_app_name', 'Remini Premium', 'remini', 'text');
        Setting::clearCache();

        $response = $this->get(route('homepage'));

        $response->assertStatus(200);
        $response->assertSee('CUSTOM AI BADGE', false);
        $response->assertSee('আপনার সাধারণ ছবিকে করে তুলুন', false);
        $response->assertSee('হাই রেজোলিউশন!', false);
        $response->assertSee('Remini Premium', false);
    }

    public function test_homepage_hides_remini_banner_when_disabled(): void
    {
        Setting::set('remini_banner_enabled', '0', 'remini', 'boolean');
        Setting::set('remini_badge', 'HIDDEN REMINI BADGE', 'remini', 'text');
        Setting::clearCache();

        $response = $this->get(route('homepage'));

        $response->assertStatus(200);
        $response->assertDontSee('HIDDEN REMINI BADGE', false);
    }

    public function test_admin_can_view_dual_promo_tab(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.appearance.banners.index', ['tab' => 'dual_promo']));

        $response->assertStatus(200);
        $response->assertSee('Coupon & Deals Announcement Banners', false);
        $response->assertSee('dualPromoForm', false);
        $response->assertSee('dual_promo_1_title', false);
        $response->assertSee('dual_promo_1_coupon_code', false);
        $response->assertSee('dual_promo_2_title', false);
    }

    public function test_admin_can_update_dual_promo_banners(): void
    {
        Storage::fake('public');

        $img1 = UploadedFile::fake()->image('mounting_new.png', 500, 300);

        $payload = [
            'dual_promo_enabled' => '1',
            'dual_promo_1_title' => 'নতুন কুপন অফার',
            'dual_promo_1_offer_label' => 'বিশেষ ২০% ছাড়; প্রোমোকোড:',
            'dual_promo_1_coupon_code' => 'DIGIGO20',
            'dual_promo_1_coupon_bg_color' => '#dc2626',
            'dual_promo_1_desc' => 'সীমিত সময়ের জন্য এই ডিসকাউন্ট অফারটি কার্যকর।',
            'dual_promo_1_disclaimer' => '*শর্ত প্রযোজ্য',
            'dual_promo_1_link' => '/coupons',
            'dual_promo_1_bg_start' => '#0284c7',
            'dual_promo_1_bg_end' => '#38bdf8',
            'dual_promo_1_img' => $img1,

            'dual_promo_2_title' => 'সামার কুলিং অফার',
            'dual_promo_2_offer_label' => 'ফ্ল্যাশ সেল',
            'dual_promo_2_coupon_code' => 'COOL50',
            'dual_promo_2_coupon_bg_color' => '#b91c1c',
            'dual_promo_2_desc' => 'পোর্টেবল টার্বো ফ্যানে পাবেন সর্বোচ্চ বাতাস।',
            'dual_promo_2_disclaimer' => '',
            'dual_promo_2_link' => '/summer-sale',
            'dual_promo_2_bg_start' => '#be185d',
            'dual_promo_2_bg_end' => '#f472b6',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.appearance.banners.dual-promo.update'), $payload);

        $response->assertRedirect(route('admin.appearance.banners.index', ['tab' => 'dual_promo']));
        $response->assertSessionHas('success');

        $this->assertSame('1', Setting::get('dual_promo_enabled'));
        $this->assertSame('নতুন কুপন অফার', Setting::get('dual_promo_1_title'));
        $this->assertSame('DIGIGO20', Setting::get('dual_promo_1_coupon_code'));
        $this->assertSame('সামার কুলিং অফার', Setting::get('dual_promo_2_title'));
        $this->assertSame('COOL50', Setting::get('dual_promo_2_coupon_code'));
        $this->assertNotEmpty(Setting::get('dual_promo_1_img'));

        Storage::disk('public')->assertExists(Setting::get('dual_promo_1_img'));
    }

    public function test_homepage_renders_dynamic_dual_promo_banners(): void
    {
        Setting::set('dual_promo_enabled', '1', 'dual_promo', 'boolean');
        Setting::set('dual_promo_1_title', 'লাইভ মেগা ডিসকাউন্ট ডিল', 'dual_promo', 'text');
        Setting::set('dual_promo_1_coupon_code', 'SAVE99', 'dual_promo', 'text');
        Setting::set('dual_promo_2_title', 'স্পেশাল হট অফার ব্যান', 'dual_promo', 'text');
        Setting::clearCache();

        $response = $this->get(route('homepage'));

        $response->assertStatus(200);
        $response->assertSee('লাইভ মেগা ডিসকাউন্ট ডিল', false);
        $response->assertSee('SAVE99', false);
        $response->assertSee('স্পেশাল হট অফার ব্যান', false);
    }

    public function test_homepage_hides_dual_promo_when_disabled(): void
    {
        Setting::set('dual_promo_enabled', '0', 'dual_promo', 'boolean');
        Setting::set('dual_promo_1_title', 'Hidden Dual Promo Deal', 'dual_promo', 'text');
        Setting::clearCache();

        $response = $this->get(route('homepage'));

        $response->assertStatus(200);
        $response->assertDontSee('Hidden Dual Promo Deal', false);
    }
}
