<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminReportsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_reports(): void
    {
        $response = $this->get(route('admin.reports.sales'));
        $response->assertRedirect(route('login'));

        $responseGrowth = $this->get(route('admin.reports.customer-growth'));
        $responseGrowth->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_reports(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.reports.sales'));
        $response->assertRedirect(route('user.dashboard'));

        $responseGrowth = $this->actingAs($user)->get(route('admin.reports.customer-growth'));
        $responseGrowth->assertRedirect(route('user.dashboard'));
    }

    public function test_admin_can_view_sales_report_with_different_periods_and_custom_range(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Create orders
        Order::create([
            'order_number' => 'ORD-REPORT-001',
            'customer_name' => 'Report Customer',
            'customer_email' => 'report@example.com',
            'customer_phone' => '01700000000',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'street_address' => 'Mirpur',
            'subtotal' => 1250.00,
            'discount' => 0.00,
            'delivery_charge' => 0.00,
            'shipping_charge' => 0.00,
            'total' => 1250.00,
            'payment_method' => 'dgepay',
            'payment_status' => 'paid',
            'order_status' => 'completed',
            'created_at' => now(),
        ]);

        // Monthly view
        $response = $this->actingAs($admin)->get(route('admin.reports.sales', ['period' => 'month']));
        $response->assertOk();
        $response->assertSee('Sales Revenue Report');
        $response->assertSee('1,250.00');

        // Daily view
        $responseDaily = $this->actingAs($admin)->get(route('admin.reports.sales', ['period' => 'daily']));
        $responseDaily->assertOk();

        // Custom date range view
        $responseCustom = $this->actingAs($admin)->get(route('admin.reports.sales', [
            'period' => 'custom',
            'start_date' => now()->subDays(5)->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
        ]));
        $responseCustom->assertOk();
    }

    public function test_admin_can_export_sales_report_to_excel_csv(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Order::create([
            'order_number' => 'ORD-EXPORT-TEST',
            'customer_name' => 'Export Customer',
            'customer_email' => 'export@example.com',
            'customer_phone' => '01700000000',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'street_address' => 'Mirpur',
            'subtotal' => 990.00,
            'discount' => 0.00,
            'delivery_charge' => 0.00,
            'shipping_charge' => 0.00,
            'total' => 990.00,
            'payment_method' => 'dgepay',
            'payment_status' => 'paid',
            'order_status' => 'completed',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.reports.sales.export', ['period' => 'month']));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('attachment; filename="sales_report_', $response->headers->get('Content-Disposition'));
    }

    public function test_admin_can_view_customer_growth_report(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        User::factory()->count(3)->create([
            'role' => 'user',
            'created_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.reports.customer-growth', ['period' => 'month']));

        $response->assertOk();
        $response->assertSee('Customer Growth Analytics');
        $response->assertSee('New Customer Registrations Trend');
    }

    public function test_admin_can_export_customer_growth_to_excel_csv(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        User::factory()->create([
            'name' => 'Report User Export',
            'email' => 'report.export@test.com',
            'role' => 'user',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.reports.customer-growth.export', ['period' => 'month']));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('attachment; filename="customer_growth_', $response->headers->get('Content-Disposition'));
    }
}
