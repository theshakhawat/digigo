@extends('layouts.admin')

@section('title', 'Blog Posts - DigiGo')

@section('admin_content')
<!-- Page Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white">
            Blog Posts Management
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            Write, edit, feature, and manage all blog articles and announcements.
        </p>
    </div>

    <div>
        <a href="{{ route('admin.blog.posts.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-800 hover:bg-brand-700 text-white dark:bg-brand-600 dark:hover:bg-brand-700 text-sm font-semibold rounded-lg shadow-sm transition cursor-pointer">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Add New Post</span>
        </a>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="mb-6 p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs">
    <form action="{{ route('admin.blog.posts.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center gap-3">
        <!-- Search Input -->
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </div>
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Search post by title or slug..."
                   class="w-full h-10 pl-9 pr-4 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 focus:bg-white text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 dark:focus:border-brand-500 transition">
        </div>

        <!-- Category Filter -->
        <div class="w-full sm:w-44">
            <select name="category_id" class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-700 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Status Filter -->
        <div class="w-full sm:w-36">
            <select name="status" class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-700 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <!-- Featured Filter -->
        <div class="w-full sm:w-36">
            <select name="is_featured" class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-700 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                <option value="">All Posts</option>
                <option value="1" {{ request('is_featured') === '1' ? 'selected' : '' }}>Featured</option>
                <option value="0" {{ request('is_featured') === '0' ? 'selected' : '' }}>Standard</option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="flex items-center gap-2 shrink-0">
            <button type="submit" class="px-4 h-10 bg-slate-800 hover:bg-slate-900 text-white text-xs sm:text-sm font-semibold rounded-lg transition dark:bg-slate-700 dark:hover:bg-slate-600">
                Filter
            </button>
            @if(request()->hasAny(['search', 'category_id', 'status', 'is_featured']))
                <a href="{{ route('admin.blog.posts.index') }}" class="px-3 h-10 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs sm:text-sm font-medium rounded-lg flex items-center justify-center transition dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700">
                    Clear
                </a>
            @endif
        </div>
    </form>
</div>

@if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-2 text-sm dark:bg-emerald-950/50 dark:border-emerald-800 dark:text-emerald-300">
        <i class="fa-solid fa-circle-check text-emerald-600"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<!-- Posts Table -->
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600 dark:text-slate-400">
            <thead class="bg-slate-50 dark:bg-slate-800/60 text-xs uppercase font-bold text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                <tr>
                    <th class="px-6 py-4">Thumbnail & Title</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4 text-center">Featured</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4">Created Date</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($posts as $post)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-14 h-10 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden shrink-0 flex items-center justify-center">
                                    @if($post->thumbnail_img)
                                        <img src="{{ $post->thumbnail_img }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fa-regular fa-image text-slate-400 text-xs"></i>
                                    @endif
                                </div>
                                <div class="min-w-0 max-w-sm">
                                    <div class="font-bold text-slate-900 dark:text-white truncate">
                                        {{ $post->title }}
                                    </div>
                                    <div class="font-mono text-[11px] text-slate-400 truncate">
                                        /{{ $post->slug }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($post->category)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300">
                                    {{ $post->category->name }}
                                </span>
                            @else
                                <span class="text-xs text-slate-400">Uncategorized</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($post->is_featured)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300">
                                    <i class="fa-solid fa-star text-[10px] text-amber-500"></i> Featured
                                </span>
                            @else
                                <span class="text-xs text-slate-400">&mdash;</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($post->status === 'active')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 whitespace-nowrap">
                            {{ $post->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.blog.posts.edit', $post->id) }}" class="p-2 text-slate-600 hover:text-brand-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800 rounded-lg transition" title="Edit Post">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('admin.blog.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this blog post?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-600 hover:text-rose-600 hover:bg-rose-50 dark:text-slate-400 dark:hover:text-rose-400 dark:hover:bg-rose-950/40 rounded-lg transition cursor-pointer" title="Delete Post">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            <i class="fa-solid fa-newspaper text-4xl mb-3 text-slate-300 dark:text-slate-600 block"></i>
                            <p class="font-medium">No blog posts found.</p>
                            <a href="{{ route('admin.blog.posts.create') }}" class="text-brand-600 hover:underline text-xs mt-1 inline-block">Create your first blog post</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($posts->hasPages())
        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection

