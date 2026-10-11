@extends('layouts.admin')

@section('title', 'Categories Management - DigiGo')

@section('admin_content')
<!-- Page Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white">
            Categories Management
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            Organize digital products, configure SEO slugs, images, and visibility.
        </p>
    </div>

    <div>
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-800 hover:bg-brand-700 text-white dark:bg-brand-600 dark:hover:bg-brand-700 text-sm font-semibold rounded-lg shadow-sm transition cursor-pointer">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Add New Category</span>
        </a>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="mb-6 p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs">
    <form action="{{ route('admin.categories.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center gap-3">
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

        <!-- Featured Filter -->
        <div class="w-full sm:w-36">
            <select name="is_featured" class="w-full h-10 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-700 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                <option value="">All Types</option>
                <option value="1" {{ request('is_featured') === '1' ? 'selected' : '' }}>Featured</option>
                <option value="0" {{ request('is_featured') === '0' ? 'selected' : '' }}>Standard</option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="flex items-center gap-2 shrink-0">
            <button type="submit" class="px-4 h-10 bg-slate-800 hover:bg-slate-900 text-white text-xs sm:text-sm font-semibold rounded-lg transition dark:bg-slate-700 dark:hover:bg-slate-600">
                Filter
            </button>
            @if(request('search') || request('status') || request()->filled('is_featured'))
                <a href="{{ route('admin.categories.index') }}" class="px-3 h-10 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm rounded-lg transition dark:bg-slate-800 dark:text-slate-300">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Categories Table Card -->
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs sm:text-sm">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 dark:bg-slate-950 dark:border-slate-800 dark:text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                <tr>
                    <th class="px-5 py-3.5">Image</th>
                    <th class="px-5 py-3.5">Category Name</th>
                    <th class="px-5 py-3.5">Slug</th>
                    <th class="px-5 py-3.5">Status</th>
                    <th class="px-5 py-3.5">Created Date</th>
                    <th class="px-5 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse ($categories as $category)
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                        <!-- Image Column -->
                        <td class="px-5 py-3.5">
                            @if ($category->image_url)
                                <img src="{{ $category->image_url }}"
                                     alt="{{ $category->name }}"
                                     class="w-10 h-10 rounded-lg object-cover border border-slate-200 dark:border-slate-700 shadow-xs">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-400">
                                    <i class="fa-regular fa-image text-sm"></i>
                                </div>
                            @endif
                        </td>

                        <!-- Name Column -->
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-bold text-slate-900 dark:text-white">{{ $category->name }}</span>
                                @if($category->is_featured)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/50 dark:text-amber-300 dark:border-amber-800" title="Featured on homepage circle slider">
                                        <i class="fa-solid fa-star text-[9px] text-amber-500"></i>
                                        <span>Featured</span>
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Slug Column -->
                        <td class="px-5 py-3.5">
                            <span class="font-mono text-xs text-slate-500 bg-slate-100 dark:bg-slate-800 dark:text-slate-300 px-2 py-1 rounded">
                                {{ $category->slug }}
                            </span>
                        </td>

                        <!-- Status Column -->
                        <td class="px-5 py-3.5">
                            @if ($category->status === 'active')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-[11px] font-bold uppercase bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 rounded-full border border-emerald-200 dark:border-emerald-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-[11px] font-bold uppercase bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 rounded-full border border-slate-200 dark:border-slate-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    Inactive
                                </span>
                            @endif
                        </td>

                        <!-- Created At -->
                        <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400 text-xs whitespace-nowrap">
                            {{ $category->created_at ? $category->created_at->format('M d, Y') : '—' }}
                        </td>

                        <!-- Actions -->
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="p-2 text-slate-500 hover:text-brand-600 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition" title="Edit Category">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <button type="button"
                                        class="js-custom-delete p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition cursor-pointer"
                                        data-action="{{ route('admin.categories.destroy', $category) }}"
                                        data-title="Delete Category: {{ $category->name }}"
                                        data-message="Are you sure you want to delete '{{ $category->name }}'? Any associated image will also be removed from storage."
                                        title="Delete Category">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-solid fa-layer-group text-3xl mb-3 text-slate-300 dark:text-slate-600"></i>
                                <p class="text-base font-semibold text-slate-700 dark:text-slate-300">No categories found</p>
                                <p class="text-xs text-slate-400 mt-1">Get started by adding your first product category.</p>
                                <a href="{{ route('admin.categories.create') }}" class="mt-4 px-4 py-2 bg-brand-800 text-white rounded-lg text-xs font-semibold hover:bg-brand-700 transition">
                                    Add Category
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($categories->hasPages())
        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection
