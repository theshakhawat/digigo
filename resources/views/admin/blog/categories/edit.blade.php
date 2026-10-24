@extends('layouts.admin')

@section('title', 'Edit Blog Category - DigiGo')

@section('admin_content')
<!-- Page Header -->
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.blog.categories.index') }}" class="w-9 h-9 rounded-lg border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800 transition">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white">
                Edit Blog Category
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Update category details and URL slug.
            </p>
        </div>
    </div>
</div>

<div class="max-w-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xs p-6 sm:p-8">
    <form action="{{ route('admin.blog.categories.update', $category->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                Category Name <span class="text-rose-500">*</span>
            </label>
            <input type="text"
                   name="name"
                   id="name"
                   value="{{ old('name', $category->name) }}"
                   required
                   class="w-full px-4 py-2.5 bg-slate-50 border @error('name') border-rose-500 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-white focus:outline-none focus:border-brand-600 dark:focus:border-brand-500 transition">
            @error('name')
                <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <!-- Slug -->
        <div>
            <label for="slug" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                Slug <span class="text-slate-400 font-normal lowercase">(Unique URL path)</span>
            </label>
            <input type="text"
                   name="slug"
                   id="slug"
                   value="{{ old('slug', $category->slug) }}"
                   class="w-full px-4 py-2.5 bg-slate-50 border @error('slug') border-rose-500 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-white focus:outline-none focus:border-brand-600 dark:focus:border-brand-500 transition font-mono">
            @error('slug')
                <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <!-- Status -->
        <div>
            <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                Status <span class="text-rose-500">*</span>
            </label>
            <select name="status"
                    id="status"
                    required
                    class="w-full px-4 py-2.5 bg-slate-50 border @error('status') border-rose-500 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-white focus:outline-none focus:border-brand-600 dark:focus:border-brand-500 transition">
                <option value="active" {{ old('status', $category->status) === 'active' ? 'selected' : '' }}>Active (Visible)</option>
                <option value="inactive" {{ old('status', $category->status) === 'inactive' ? 'selected' : '' }}>Inactive (Hidden)</option>
            </select>
            @error('status')
                <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <!-- Form Actions -->
        <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
            <a href="{{ route('admin.blog.categories.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 rounded-xl text-xs font-bold transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-brand-800 hover:bg-brand-700 text-white dark:bg-brand-600 dark:hover:bg-brand-700 rounded-xl text-xs font-bold shadow-md transition cursor-pointer">
                Update Category
            </button>
        </div>
    </form>
</div>
@endsection

