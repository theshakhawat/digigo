<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of blog categories.
     */
    public function index(Request $request): View
    {
        $query = BlogCategory::withCount('posts');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $categories = $query->latest()->paginate(10)->withQueryString();

        return view('admin.blog.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new blog category.
     */
    public function create(): View
    {
        return view('admin.blog.categories.create');
    }

    /**
     * Store a newly created blog category in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:blog_categories,slug'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = BlogCategory::generateUniqueSlug($validated['name']);
        }

        BlogCategory::create($validated);

        return redirect()->route('admin.blog.categories.index')
            ->with('success', 'Blog category created successfully.');
    }

    /**
     * Show the form for editing the specified blog category.
     */
    public function edit(BlogCategory $category): View
    {
        return view('admin.blog.categories.edit', compact('category'));
    }

    /**
     * Update the specified blog category in storage.
     */
    public function update(Request $request, BlogCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('blog_categories', 'slug')->ignore($category->id)],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = BlogCategory::generateUniqueSlug($validated['name'], $category->id);
        }

        $category->update($validated);

        return redirect()->route('admin.blog.categories.index')
            ->with('success', 'Blog category updated successfully.');
    }

    /**
     * Remove the specified blog category from storage.
     */
    public function destroy(BlogCategory $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.blog.categories.index')
            ->with('success', 'Blog category deleted successfully.');
    }
}
