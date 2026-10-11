@extends('layouts.admin')

@section('title', 'Add New Category - DigiGo')

@section('admin_content')
<!-- Page Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <nav class="flex items-center gap-2 text-xs text-slate-500 mb-1" aria-label="Breadcrumb">
            <a href="{{ route('admin.categories.index') }}" class="hover:text-brand-600 transition">Categories</a>
            <span>/</span>
            <span class="text-slate-800 dark:text-slate-200 font-semibold">New Category</span>
        </nav>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white">
            Add New Category
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
            Create a category to group digital software, gift cards, or accounts.
        </p>
    </div>

    <div>
        <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 text-slate-700 dark:text-slate-200 text-xs sm:text-sm font-semibold rounded-lg shadow-xs transition">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            <span>Back to Categories</span>
        </a>
    </div>
</div>

<!-- Create Form Card -->
<div class="max-w-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs p-6 sm:p-8">
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Category Name -->
        <div>
            <label for="name" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                Category Name <span class="text-rose-500">*</span>
            </label>
            <input type="text"
                   name="name"
                   id="name"
                   value="{{ old('name') }}"
                   placeholder="e.g. Artificial Intelligence (AI)"
                   class="w-full h-11 px-3.5 text-sm bg-slate-50 border @error('name') border-rose-500 @else border-slate-200 @enderror rounded-lg focus:outline-none focus:border-brand-600 focus:bg-white text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 dark:focus:border-brand-500 transition"
                   required
                   autofocus>
            @error('name')
                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Slug -->
        <div>
            <label for="slug" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                Slug <span class="text-slate-400 font-normal text-xs">(Optional - auto-generated if left blank)</span>
            </label>
            <div class="relative">
                <input type="text"
                       name="slug"
                       id="slug"
                       value="{{ old('slug') }}"
                       placeholder="e.g. artificial-intelligence"
                       class="w-full h-11 px-3.5 font-mono text-sm bg-slate-50 border @error('slug') border-rose-500 @else border-slate-200 @enderror rounded-lg focus:outline-none focus:border-brand-600 focus:bg-white text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 dark:focus:border-brand-500 transition">
            </div>
            @error('slug')
                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-[11px] text-slate-400 mt-1">Unique URL identifier used for customer storefront navigation.</p>
        </div>

        <!-- Category Image (Optional) -->
        <div>
            <label class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                Category Image <span class="text-slate-400 font-normal text-xs">(Optional, saved into /storage)</span>
            </label>
            
            <div class="flex items-start gap-4">
                <!-- Preview Thumbnail Box -->
                <div id="imagePreviewContainer" class="w-20 h-20 rounded-lg border-2 border-dashed border-slate-200 dark:border-slate-700 flex items-center justify-center bg-slate-50 dark:bg-slate-800 shrink-0 overflow-hidden text-slate-400">
                    <img id="imagePreview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                    <i id="imagePlaceholderIcon" class="fa-regular fa-image text-xl"></i>
                </div>

                <div class="flex-1">
                    <input type="file"
                           name="image"
                           id="image"
                           accept="image/*"
                           class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 dark:file:bg-slate-800 dark:file:text-brand-400 file:cursor-pointer cursor-pointer border border-slate-200 dark:border-slate-700 rounded-lg p-1.5">
                    <p class="text-[11px] text-slate-400 mt-1">Allowed formats: PNG, JPG, WEBP, SVG. Max file size: 2MB.</p>
                    @error('image')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Status -->
        <div>
            <label for="status" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                Status <span class="text-rose-500">*</span>
            </label>
            <select name="status"
                    id="status"
                    class="w-full sm:w-60 h-11 px-3.5 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-600 text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100">
                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active (Visible in Store)</option>
                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive (Hidden)</option>
            </select>
            @error('status')
                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Featured Category Toggle -->
        <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40 flex items-center justify-between">
            <div>
                <span class="block text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200">Featured Category (Top Circles)</span>
                <span class="block text-xs text-slate-500 dark:text-slate-400 mt-0.5">Show this category in the homepage top circular slider (Maximum 10 categories)</span>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox"
                       name="is_featured"
                       value="1"
                       class="sr-only peer"
                       {{ old('is_featured') ? 'checked' : '' }}>
                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-brand-600"></div>
            </label>
        </div>

        <!-- Action Buttons -->
        <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 bg-brand-800 hover:bg-brand-700 text-white dark:bg-brand-600 dark:hover:bg-brand-700 text-sm font-semibold rounded-lg shadow-sm transition cursor-pointer">
                Save Category
            </button>
            <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-300 text-sm font-semibold rounded-lg transition">
                Cancel
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Live Image Preview
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const placeholderIcon = document.getElementById('imagePlaceholderIcon');

    imageInput?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('hidden');
                placeholderIcon.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            imagePreview.classList.add('hidden');
            placeholderIcon.classList.remove('hidden');
        }
    });

    // Auto-generate slug suggestion from name if slug input is untouched
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    let slugManual = false;

    slugInput?.addEventListener('input', () => {
        slugManual = slugInput.value.trim().length > 0;
    });

    nameInput?.addEventListener('input', () => {
        if (!slugManual) {
            slugInput.value = nameInput.value
                .toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
    });
</script>
@endpush
@endsection
