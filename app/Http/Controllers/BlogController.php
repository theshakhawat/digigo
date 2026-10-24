<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Display public listing of blog posts.
     */
    public function index(Request $request): View
    {
        $query = BlogPost::with('category')
            ->where('status', 'active');

        if ($categorySlug = $request->query('category')) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('detailes', 'like', "%{$search}%");
            });
        }

        $posts = $query->latest()->paginate(9)->withQueryString();
        $categories = BlogCategory::where('status', 'active')->withCount(['posts' => function ($q) {
            $q->where('status', 'active');
        }])->get();

        $featuredPosts = BlogPost::where('status', 'active')
            ->where('is_featured', true)
            ->latest()
            ->take(3)
            ->get();

        return view('blog.index', compact('posts', 'categories', 'featuredPosts'));
    }

    /**
     * Display a single blog post.
     */
    public function show(string $slug): View
    {
        $post = BlogPost::with('category')
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $relatedPosts = BlogPost::where('status', 'active')
            ->where('id', '!=', $post->id)
            ->when($post->category_id, fn ($q) => $q->where('category_id', $post->category_id))
            ->latest()
            ->take(3)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}
