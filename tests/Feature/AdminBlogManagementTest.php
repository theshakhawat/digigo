<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminBlogManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@digigo.com.bd',
        ]);
    }

    public function test_admin_sidebar_displays_blog_dropdown_menu(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Blog');
        $response->assertSee(route('admin.blog.categories.index'));
        $response->assertSee(route('admin.blog.posts.index'));
    }

    public function test_admin_can_manage_blog_categories_crud(): void
    {
        // 1. View categories index
        $indexResponse = $this->actingAs($this->admin)->get(route('admin.blog.categories.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Blog Categories');

        // 2. View create page
        $createResponse = $this->actingAs($this->admin)->get(route('admin.blog.categories.create'));
        $createResponse->assertStatus(200);

        // 3. Store category
        $storeResponse = $this->actingAs($this->admin)->post(route('admin.blog.categories.store'), [
            'name' => 'Artificial Intelligence',
            'slug' => '',
            'status' => 'active',
        ]);
        $storeResponse->assertRedirect(route('admin.blog.categories.index'));
        $storeResponse->assertSessionHas('success');

        $this->assertDatabaseHas('blog_categories', [
            'name' => 'Artificial Intelligence',
            'slug' => 'artificial-intelligence',
            'status' => 'active',
        ]);

        $category = BlogCategory::where('slug', 'artificial-intelligence')->first();

        // 4. View edit page
        $editResponse = $this->actingAs($this->admin)->get(route('admin.blog.categories.edit', $category->id));
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Artificial Intelligence');

        // 5. Update category
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.blog.categories.update', $category->id), [
            'name' => 'AI & Machine Learning',
            'slug' => 'ai-and-ml',
            'status' => 'active',
        ]);
        $updateResponse->assertRedirect(route('admin.blog.categories.index'));
        $updateResponse->assertSessionHas('success');

        $this->assertDatabaseHas('blog_categories', [
            'id' => $category->id,
            'name' => 'AI & Machine Learning',
            'slug' => 'ai-and-ml',
        ]);

        // 6. Delete category
        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.blog.categories.destroy', $category->id));
        $deleteResponse->assertRedirect(route('admin.blog.categories.index'));
        $deleteResponse->assertSessionHas('success');

        $this->assertDatabaseMissing('blog_categories', [
            'id' => $category->id,
        ]);
    }

    public function test_admin_can_manage_blog_posts_crud(): void
    {
        Storage::fake('public');

        $category = BlogCategory::create([
            'name' => 'Tech Guides',
            'slug' => 'tech-guides',
            'status' => 'active',
        ]);

        // 1. View posts index
        $indexResponse = $this->actingAs($this->admin)->get(route('admin.blog.posts.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Blog Posts Management');

        // 2. View create page
        $createResponse = $this->actingAs($this->admin)->get(route('admin.blog.posts.create'));
        $createResponse->assertStatus(200);

        // 3. Store blog post with thumbnail and rich text
        $fakeImage = UploadedFile::fake()->image('thumbnail.jpg', 800, 500);

        $storeResponse = $this->actingAs($this->admin)->post(route('admin.blog.posts.store'), [
            'title' => 'Top 5 AI Subscription Tools for Productivity',
            'slug' => '',
            'category_id' => $category->id,
            'thumbnail_img' => $fakeImage,
            'detailes' => '<p>These are the best AI tools in 2026 for productivity.</p>',
            'status' => 'active',
            'is_featured' => '1',
        ]);

        $storeResponse->assertRedirect(route('admin.blog.posts.index'));
        $storeResponse->assertSessionHas('success');

        $this->assertDatabaseHas('blog_posts', [
            'title' => 'Top 5 AI Subscription Tools for Productivity',
            'slug' => 'top-5-ai-subscription-tools-for-productivity',
            'category_id' => $category->id,
            'is_featured' => 1,
            'status' => 'active',
        ]);

        $post = BlogPost::where('slug', 'top-5-ai-subscription-tools-for-productivity')->first();
        $this->assertNotNull($post->thumbnail_img);

        // 4. View edit page
        $editResponse = $this->actingAs($this->admin)->get(route('admin.blog.posts.edit', $post->id));
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Top 5 AI Subscription Tools for Productivity');

        // 5. Update post
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.blog.posts.update', $post->id), [
            'title' => 'Updated Top 5 AI Tools',
            'slug' => 'updated-top-5-ai-tools',
            'category_id' => $category->id,
            'detailes' => '<p>Updated content.</p>',
            'status' => 'active',
            'is_featured' => '0',
        ]);

        $updateResponse->assertRedirect(route('admin.blog.posts.index'));
        $updateResponse->assertSessionHas('success');

        $this->assertDatabaseHas('blog_posts', [
            'id' => $post->id,
            'title' => 'Updated Top 5 AI Tools',
            'slug' => 'updated-top-5-ai-tools',
            'is_featured' => 0,
        ]);

        // 6. Delete post
        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.blog.posts.destroy', $post->id));
        $deleteResponse->assertRedirect(route('admin.blog.posts.index'));
        $deleteResponse->assertSessionHas('success');

        $this->assertDatabaseMissing('blog_posts', [
            'id' => $post->id,
        ]);
    }

    public function test_featured_blog_posts_show_above_footer_on_homepage(): void
    {
        $category = BlogCategory::create([
            'name' => 'News',
            'slug' => 'news',
            'status' => 'active',
        ]);

        // Create 4 featured posts with distinct timestamps
        for ($i = 1; $i <= 4; $i++) {
            BlogPost::create([
                'title' => "Featured Blog Post {$i}",
                'slug' => "featured-blog-post-{$i}",
                'category_id' => $category->id,
                'detailes' => "<p>Detailed description for post {$i}</p>",
                'status' => 'active',
                'is_featured' => true,
                'created_at' => now()->addMinutes($i),
                'updated_at' => now()->addMinutes($i),
            ]);
        }

        $response = $this->get(route('homepage'));
        $response->assertStatus(200);
        $response->assertSee('From Our Blog and Resources');

        // Only the latest 3 featured posts should show (max 3)
        $response->assertSee('Featured Blog Post 4');
        $response->assertSee('Featured Blog Post 3');
        $response->assertSee('Featured Blog Post 2');
        $response->assertDontSee('Featured Blog Post 1');
    }

    public function test_public_blog_index_and_details_pages(): void
    {
        $category = BlogCategory::create([
            'name' => 'Guides',
            'slug' => 'guides',
            'status' => 'active',
        ]);

        $post = BlogPost::create([
            'title' => 'How to Activate ChatGPT Plus',
            'slug' => 'how-to-activate-chatgpt-plus',
            'category_id' => $category->id,
            'detailes' => '<p>Step 1: Open DigiGo account. Step 2: Redeem key.</p>',
            'status' => 'active',
            'is_featured' => true,
        ]);

        // Public index
        $indexResponse = $this->get(route('blog.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('How to Activate ChatGPT Plus');

        // Public single post detail
        $showResponse = $this->get(route('blog.show', $post->slug));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('How to Activate ChatGPT Plus');
        $showResponse->assertSee('Step 1: Open DigiGo account');
    }
}
