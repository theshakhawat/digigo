<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminBrandCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->user = User::factory()->create([
            'role' => 'user',
            'status' => 'active',
        ]);
    }

    public function test_guests_cannot_access_brand_management(): void
    {
        $response = $this->get(route('admin.brands.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_regular_users_cannot_access_brand_management(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.brands.index'));
        $response->assertRedirect(route('user.dashboard'));
    }

    public function test_admin_can_view_brands_list(): void
    {
        Brand::factory()->create(['name' => 'OpenAI Solutions']);

        $response = $this->actingAs($this->admin)->get(route('admin.brands.index'));

        $response->assertStatus(200);
        $response->assertSee('Brands Management');
        $response->assertSee('OpenAI Solutions');
    }

    public function test_admin_can_view_create_brand_form(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.brands.create'));

        $response->assertStatus(200);
        $response->assertSee('Add New Brand');
    }

    public function test_admin_can_create_brand_without_image(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.brands.store'), [
            'name' => 'Microsoft Corporation',
            'slug' => 'microsoft',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.brands.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('brands', [
            'name' => 'Microsoft Corporation',
            'slug' => 'microsoft',
            'status' => 'active',
            'image' => null,
        ]);
    }

    public function test_admin_can_create_brand_with_image_in_storage(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('brand_logo.png', 400, 400);

        $response = $this->actingAs($this->admin)->post(route('admin.brands.store'), [
            'name' => 'Canva Graphics',
            'slug' => 'canva',
            'image' => $file,
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.brands.index'));

        $brand = Brand::where('slug', 'canva')->first();
        $this->assertNotNull($brand);
        $this->assertNotNull($brand->image);

        Storage::disk('public')->assertExists($brand->image);
    }

    public function test_admin_can_view_edit_brand_form(): void
    {
        $brand = Brand::factory()->create(['name' => 'Netflix Premium']);

        $response = $this->actingAs($this->admin)->get(route('admin.brands.edit', $brand));

        $response->assertStatus(200);
        $response->assertSee('Edit Brand');
        $response->assertSee('Netflix Premium');
    }

    public function test_admin_can_update_brand(): void
    {
        $brand = Brand::factory()->create([
            'name' => 'Old Brand Name',
            'slug' => 'old-brand-name',
            'status' => 'inactive',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.brands.update', $brand), [
            'name' => 'Updated Brand Name',
            'slug' => 'updated-brand-name',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.brands.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('brands', [
            'id' => $brand->id,
            'name' => 'Updated Brand Name',
            'slug' => 'updated-brand-name',
            'status' => 'active',
        ]);
    }

    public function test_admin_can_delete_brand(): void
    {
        Storage::fake('public');

        $brand = Brand::factory()->create([
            'image' => 'brands/brand_delete.png',
        ]);

        Storage::disk('public')->put('brands/brand_delete.png', 'fake content');

        $response = $this->actingAs($this->admin)->delete(route('admin.brands.destroy', $brand));

        $response->assertRedirect(route('admin.brands.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('brands', [
            'id' => $brand->id,
        ]);

        Storage::disk('public')->assertMissing('brands/brand_delete.png');
    }
}
