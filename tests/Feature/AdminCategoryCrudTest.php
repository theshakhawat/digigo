<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCategoryCrudTest extends TestCase
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

    public function test_guests_cannot_access_category_management(): void
    {
        $response = $this->get(route('admin.categories.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_regular_users_cannot_access_category_management(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.categories.index'));
        $response->assertRedirect(route('user.dashboard'));
    }

    public function test_admin_can_view_categories_list(): void
    {
        Category::factory()->create(['name' => 'Streaming Services']);

        $response = $this->actingAs($this->admin)->get(route('admin.categories.index'));

        $response->assertStatus(200);
        $response->assertSee('Categories Management');
        $response->assertSee('Streaming Services');
    }

    public function test_admin_can_view_create_category_form(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.categories.create'));

        $response->assertStatus(200);
        $response->assertSee('Add New Category');
    }

    public function test_admin_can_create_category_without_image(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Artificial Intelligence',
            'slug' => 'artificial-intelligence',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'name' => 'Artificial Intelligence',
            'slug' => 'artificial-intelligence',
            'status' => 'active',
            'image' => null,
        ]);
    }

    public function test_admin_can_create_category_with_image_in_storage(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('category_thumb.jpg', 400, 400);

        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Game & Gift Card',
            'slug' => 'game-gift-card',
            'image' => $file,
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.categories.index'));

        $category = Category::where('slug', 'game-gift-card')->first();
        $this->assertNotNull($category);
        $this->assertNotNull($category->image);

        Storage::disk('public')->assertExists($category->image);
    }

    public function test_admin_can_view_edit_category_form(): void
    {
        $category = Category::factory()->create(['name' => 'Office Software']);

        $response = $this->actingAs($this->admin)->get(route('admin.categories.edit', $category));

        $response->assertStatus(200);
        $response->assertSee('Edit Category');
        $response->assertSee('Office Software');
    }

    public function test_admin_can_update_category(): void
    {
        $category = Category::factory()->create([
            'name' => 'Old Category Name',
            'slug' => 'old-category-name',
            'status' => 'inactive',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.categories.update', $category), [
            'name' => 'Updated Category Name',
            'slug' => 'updated-category-name',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category Name',
            'slug' => 'updated-category-name',
            'status' => 'active',
        ]);
    }

    public function test_admin_can_delete_category(): void
    {
        Storage::fake('public');

        $category = Category::factory()->create([
            'image' => 'categories/sample_delete.jpg',
        ]);

        Storage::disk('public')->put('categories/sample_delete.jpg', 'content');

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);

        Storage::disk('public')->assertMissing('categories/sample_delete.jpg');
    }
}
