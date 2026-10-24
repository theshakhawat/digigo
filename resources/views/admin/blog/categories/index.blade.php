@extends('layouts.admin')

@section('title', 'Blog Categories - DigiGo')

@section('admin_content')
<!-- Page Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white">
            Blog Categories
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            Manage categories for your blog articles, guides, and tutorials.
        </p>
    </div>

    <div>
        <a href="{{ route('admin.blog.categories.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-800 hover:bg-brand-700 text-white dark:bg-brand-600 dark:hover:bg-brand-700 text-sm font-semibold rounded-lg shadow-sm transition cursor-pointer">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Add New Category</span>
        </a>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="mb-6 p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs">
    <form action="{{ route('admin.blog.categories.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center gap-3">
        <!-- Search Input -->
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </div>
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Search category by name or slug..."
                   class="w-full h-10 pl-9 pr-4 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 focus:bg-white text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 dark:focus:border-brand-500 transition">
        </div>

        <!-- Status Filter -->
        <div class="w-full sm:w-36">
            <select name="status" class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-700 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="flex items-center gap-2 shrink-0">
            <button type="submit" class="px-4 h-10 bg-slate-800 hover:bg-slate-900 text-white text-xs sm:text-sm font-semibold rounded-lg transition dark:bg-slate-700 dark:hover:bg-slate-600">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.blog.categories.index') }}" class="px-3 h-10 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs sm:text-sm font-medium rounded-lg flex items-center justify-center transition dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700">
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

<!-- Categories Table -->
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600 dark:text-slate-400">
            <thead class="bg-slate-50 dark:bg-slate-800/60 text-xs uppercase font-bold text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                <tr>
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Slug</th>
                    <th class="px-6 py-4 text-center">Posts Count</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($categories as $category)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900 dark:text-white">
                                {{ $category->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-slate-500">
                            {{ $category->slug }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                {{ $category->posts_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($category->status === 'active')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.blog.categories.edit', $category->id) }}" class="p-2 text-slate-600 hover:text-brand-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-brand-400 dark:hover:bg-slate-800 rounded-lg transition" title="Edit Category">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('admin.blog.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category? All posts in it will be unassigned.');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-600 hover:text-rose-600 hover:bg-rose-50 dark:text-slate-400 dark:hover:text-rose-400 dark:hover:bg-rose-950/40 rounded-lg transition cursor-pointer" title="Delete Category">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <i class="fa-solid fa-folder-open text-4xl mb-3 text-slate-300 dark:text-slate-600 block"></i>
                            <p class="font-medium">No blog categories found.</p>
                            <a href="{{ route('admin.blog.categories.create') }}" class="text-brand-600 hover:underline text-xs mt-1 inline-block">Create the first category</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($categories->hasPages())
        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection

