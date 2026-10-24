<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BlogPostController extends Controller
{
    /**
     * Display a listing of blog posts.
     */
    public function index(Request $request): View
    {
        $query = BlogPost::with('category');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->boolean('is_featured'));
        }

        $posts = $query->latest()->paginate(10)->withQueryString();
        $categories = BlogCategory::where('status', 'active')->orderBy('name')->get();

        return view('admin.blog.posts.index', compact('posts', 'categories'));
    }

    /**
     * Show the form for creating a new blog post.
     */
    public function create(): View
    {
        $categories = BlogCategory::where('status', 'active')->orderBy('name')->get();

        return view('admin.blog.posts.create', compact('categories'));
    }

    /**
     * Store a newly created blog post in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:blog_posts,slug'],
            'category_id' => ['nullable', 'exists:blog_categories,id'],
            'thumbnail_img' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:3072'],
            'detailes' => ['required', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = BlogPost::generateUniqueSlug($validated['title']);
        }

        $validated['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('thumbnail_img')) {
            $path = $request->file('thumbnail_img')->store('blog', 'public');
            $validated['thumbnail_img'] = Storage::url($path);
        }

        BlogPost::create($validated);

        return redirect()->route('admin.blog.posts.index')
            ->with('success', 'Blog post created successfully.');
    }

    /**
     * Show the form for editing the specified blog post.
     */
    public function edit(BlogPost $post): View
    {
        $categories = BlogCategory::where('status', 'active')->orderBy('name')->get();

        return view('admin.blog.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified blog post in storage.
     */
    public function update(Request $request, BlogPost $post): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('blog_posts', 'slug')->ignore($post->id)],
            'category_id' => ['nullable', 'exists:blog_categories,id'],
            'thumbnail_img' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:3072'],
            'detailes' => ['required', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = BlogPost::generateUniqueSlug($validated['title'], $post->id);
        }

        $validated['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('thumbnail_img')) {
            // Delete old thumbnail if local
            if ($post->thumbnail_img && str_starts_with($post->thumbnail_img, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $post->thumbnail_img);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('thumbnail_img')->store('blog', 'public');
            $validated['thumbnail_img'] = Storage::url($path);
        }

        $post->update($validated);

        return redirect()->route('admin.blog.posts.index')
            ->with('success', 'Blog post updated successfully.');
    }

    /**
     * Remove the specified blog post from storage.
     */
    public function destroy(BlogPost $post): RedirectResponse
    {
        if ($post->thumbnail_img && str_starts_with($post->thumbnail_img, '/storage/')) {
            $oldPath = str_replace('/storage/', '', $post->thumbnail_img);
            Storage::disk('public')->delete($oldPath);
        }

        $post->delete();

        return redirect()->route('admin.blog.posts.index')
            ->with('success', 'Blog post deleted successfully.');
    }
}
