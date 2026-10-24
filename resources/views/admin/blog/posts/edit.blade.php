@extends('layouts.admin')

@section('title', 'Edit Blog Post - DigiGo')

@push('styles')
<!-- Quill.js Rich Text Editor CSS -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
<style>
    .ql-toolbar.ql-snow {
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
        border-color: #e2e8f0;
        background-color: #f8fafc;
    }
    .ql-container.ql-snow {
        border-bottom-left-radius: 0.75rem;
        border-bottom-right-radius: 0.75rem;
        border-color: #e2e8f0;
        min-height: 280px;
        font-family: inherit;
        font-size: 0.95rem;
    }
    .dark .ql-toolbar.ql-snow {
        border-color: #334155;
        background-color: #1e293b;
    }
    .dark .ql-container.ql-snow {
        border-color: #334155;
        color: #f8fafc;
        background-color: #0f172a;
    }
    .dark .ql-snow .ql-stroke { stroke: #94a3b8; }
    .dark .ql-snow .ql-fill { fill: #94a3b8; }
    .dark .ql-snow .ql-picker { color: #94a3b8; }
</style>
@endpush

@section('admin_content')
<!-- Page Header -->
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.blog.posts.index') }}" class="w-9 h-9 rounded-lg border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800 transition">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white">
                Edit Blog Post
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Update article content, media, status, or featured priority.
            </p>
        </div>
    </div>
</div>

<form action="{{ route('admin.blog.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" id="blogPostForm">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Main Content (8 cols) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xs p-6 space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                        Post Title <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           name="title"
                           id="title"
                           value="{{ old('title', $post->title) }}"
                           required
                           class="w-full px-4 py-2.5 bg-slate-50 border @error('title') border-rose-500 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-white focus:outline-none focus:border-brand-600 dark:focus:border-brand-500 transition">
                    @error('title')
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
                           value="{{ old('slug', $post->slug) }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border @error('slug') border-rose-500 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-white focus:outline-none focus:border-brand-600 dark:focus:border-brand-500 transition font-mono">
                    @error('slug')
                        <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Details (Rich Text Quill Editor) -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                        Post Content / Details <span class="text-rose-500">*</span>
                    </label>

                    <input type="hidden" name="detailes" id="hiddenDetailes" value="{{ old('detailes', $post->detailes) }}">

                    <div id="quillEditorContainer">
                        {!! old('detailes', $post->detailes) !!}
                    </div>

                    @error('detailes')
                        <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Sidebar / Meta Options (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Publishing Meta Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xs p-6 space-y-5">
                <h3 class="font-bold text-slate-900 dark:text-white text-sm border-b border-slate-100 dark:border-slate-800 pb-3">
                    Publishing Settings
                </h3>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                        Status <span class="text-rose-500">*</span>
                    </label>
                    <select name="status" id="status" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-white focus:outline-none focus:border-brand-600">
                        <option value="active" {{ old('status', $post->status) === 'active' ? 'selected' : '' }}>Active (Published)</option>
                        <option value="inactive" {{ old('status', $post->status) === 'inactive' ? 'selected' : '' }}>Inactive (Draft)</option>
                    </select>
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                        Category
                    </label>
                    <select name="category_id" id="category_id" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 dark:bg-slate-800 dark:border-slate-700 dark:text-white focus:outline-none focus:border-brand-600">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Featured Toggle -->
                <div class="pt-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $post->is_featured) ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                        <div>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Featured Article</span>
                            <p class="text-[11px] text-slate-400">Highlight this post in the footer blog showcase section.</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Thumbnail Image Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xs p-6 space-y-4">
                <h3 class="font-bold text-slate-900 dark:text-white text-sm border-b border-slate-100 dark:border-slate-800 pb-3">
                    Thumbnail Image
                </h3>

                <div class="space-y-3">
                    <div id="imagePreviewContainer" class="{{ $post->thumbnail_img ? '' : 'hidden' }} w-full aspect-video rounded-xl overflow-hidden bg-slate-100 border border-slate-200 dark:bg-slate-800 dark:border-slate-700">
                        <img id="imagePreview" src="{{ $post->thumbnail_img }}" alt="Thumbnail Preview" class="w-full h-full object-cover">
                    </div>

                    <input type="file"
                           name="thumbnail_img"
                           id="thumbnail_img"
                           accept="image/*"
                           onchange="previewThumbnail(event)"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 dark:file:bg-slate-800 dark:file:text-brand-400">
                    <p class="text-[11px] text-slate-400">Leave unchanged to keep current thumbnail.</p>
                    @error('thumbnail_img')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xs p-5 flex items-center justify-between gap-3">
                <a href="{{ route('admin.blog.posts.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300 rounded-xl text-xs font-bold transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-brand-800 hover:bg-brand-700 text-white dark:bg-brand-600 dark:hover:bg-brand-700 rounded-xl text-xs font-bold shadow-md transition cursor-pointer">
                    Update Post
                </button>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
    const quill = new Quill('#quillEditorContainer', {
        theme: 'snow',
        placeholder: 'Write your post detailed content here...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                ['blockquote', 'code-block'],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    const form = document.getElementById('blogPostForm');
    const hiddenDesc = document.getElementById('hiddenDetailes');

    form.addEventListener('submit', function (e) {
        if (quill.getText().trim().length === 0 && !quill.root.querySelector('img')) {
            hiddenDesc.value = '';
        } else {
            hiddenDesc.value = quill.root.innerHTML;
        }
    });

    function previewThumbnail(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('imagePreviewContainer').classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush
@endsection

