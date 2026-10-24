<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAffiliateManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->customer = User::factory()->create([
            'role' => 'user',
            'status' => 'active',
        ]);
    }

    public function test_guests_and_customers_cannot_access_affiliate_settings(): void
    {
        $response = $this->get(route('admin.settings.affiliate'));
        $response->assertRedirect(route('login'));

        $responseCustomer = $this->actingAs($this->customer)->get(route('admin.settings.affiliate'));
        $responseCustomer->assertRedirect(route('user.dashboard'));
    }

    public function test_admin_can_view_affiliate_settings_page(): void
    {
        Category::factory()->create([
            'name' => 'Antivirus & Security',
            'slug' => 'antivirus-security',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.settings.affiliate'));

        $response->assertStatus(200);
        $response->assertSee('Affiliate Commission Settings');
        $response->assertSee('Antivirus & Security');
        $response->assertSee('Physical Products Commission');
        $response->assertSee('Digital Products Commission');
    }

    public function test_admin_can_update_global_affiliate_settings(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.settings.affiliate.global'), [
            'affiliate_enabled' => '1',
            'affiliate_default_physical' => '7.5',
            'affiliate_default_physical_type' => 'percentage',
            'affiliate_default_digital' => '12',
            'affiliate_default_digital_type' => 'percentage',
            'affiliate_min_withdrawal' => '1000',
            'affiliate_terms_note' => 'Commission will be added after delivery.',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('7.5', Setting::get('affiliate_default_physical'));
        $this->assertEquals('12', Setting::get('affiliate_default_digital'));
        $this->assertEquals('1000', Setting::get('affiliate_min_withdrawal'));
    }

    public function test_admin_can_update_category_wise_affiliate_commissions(): void
    {
        $cat1 = Category::factory()->create([
            'name' => 'Smart Watches',
            'slug' => 'smart-watches',
        ]);

        $cat2 = Category::factory()->create([
            'name' => 'AI Subscription',
            'slug' => 'ai-subscription',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.settings.affiliate.categories'), [
            'categories' => [
                [
                    'id' => $cat1->id,
                    'physical' => '8.00',
                    'physical_type' => 'percentage',
                    'digital' => '0',
                    'digital_type' => 'fixed',
                ],
                [
                    'id' => $cat2->id,
                    'physical' => '0',
                    'physical_type' => 'fixed',
                    'digital' => '15.00',
                    'digital_type' => 'percentage',
                ],
            ],
        ]);

        $response->assertSessionHas('success');

        $cat1->refresh();
        $this->assertEquals('8.00', (string) $cat1->affiliate_commission_physical);
        $this->assertEquals('percentage', $cat1->affiliate_commission_type_physical);

        $cat2->refresh();
        $this->assertEquals('15.00', (string) $cat2->affiliate_commission_digital);
        $this->assertEquals('percentage', $cat2->affiliate_commission_type_digital);
    }
}
