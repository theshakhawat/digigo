@extends('layouts.admin')

@section('title', 'Edit Banner Slide')

@section('admin_content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Top Action & Breadcrumb Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600 transition">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.appearance.banners.index') }}" class="hover:text-brand-600 transition">Banners</a>
                <span>/</span>
                <span class="text-slate-800 dark:text-slate-200 font-medium">Edit Slide #{{ $banner->id }}</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Edit Hero Banner Slide</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Update graphics, promotional headline, CTA links, and carousel sorting.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.appearance.banners.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 shadow-sm transition">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Back to Banners</span>
            </a>
            <button type="submit" form="editBannerForm" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-sm transition cursor-pointer">
                <i class="fa-solid fa-check text-xs"></i>
                <span>Save Changes</span>
            </button>
        </div>
    </div>

    <!-- Flash Error Notifications -->
    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm shadow-sm space-y-1">
            <div class="flex items-center gap-2 font-bold text-rose-700 dark:text-rose-400">
                <i class="fa-solid fa-triangle-exclamation text-base"></i>
                <span>Please correct the errors below:</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-0.5 text-rose-600 dark:text-rose-300 pl-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="editBannerForm" action="{{ route('admin.appearance.banners.update', $banner) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Card 1: Banner Graphic & Visual Preview -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-5 sm:p-6 shadow-xs space-y-5">
            <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">1. Banner Graphic</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Replace current graphic with a new file or external URL (or leave blank to keep current).</p>
                </div>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-brand-50 text-brand-700 dark:bg-brand-950 dark:text-brand-300">1600 &times; 500 px recommended</span>
            </div>

            <!-- Live Banner Preview Mockup -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wider">
                    Live Banner Mockup Preview
                </label>
                <div class="relative w-full h-56 sm:h-64 rounded-2xl overflow-hidden bg-slate-950 border border-slate-300 dark:border-slate-700 flex items-center shadow-md">
                    <!-- Background / Cover Image Preview -->
                    <img id="bannerImagePreview"
                         src="{{ $banner->image_url }}"
                         alt="{{ $banner->title }}"
                         class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent"></div>

                    <!-- Foreground Copy Preview -->
                    <div class="relative z-10 p-6 sm:p-8 max-w-lg space-y-2">
                        <h3 id="previewTitleText" class="text-xl sm:text-2xl font-black text-white leading-tight drop-shadow-md">
                            {{ $banner->title }}
                        </h3>
                        <p id="previewSubtitleText" class="text-xs sm:text-sm text-slate-200 line-clamp-2 drop-shadow">
                            {{ $banner->subtitle ?? 'Your descriptive promotional copy will appear here gracefully.' }}
                        </p>
                        <div class="pt-2">
                            <span id="previewButtonText" class="inline-block px-5 py-2 rounded-full bg-blue-600 text-white text-xs font-bold shadow-md uppercase tracking-wider">
                                {{ $banner->button_text ?? 'ORDER NOW' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2">
                <!-- Option A: File Upload -->
                <div>
                    <label for="image_file" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">
                        Upload New Graphic File
                    </label>
                    <input type="file"
                           id="image_file"
                           name="image_file"
                           accept="image/*"
                           class="w-full text-xs text-slate-600 dark:text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 dark:file:bg-slate-700 dark:file:text-slate-200 hover:file:bg-brand-100 cursor-pointer border border-slate-300 dark:border-slate-600 rounded-lg p-1.5">
                    <p class="text-[11px] text-slate-400 mt-1">Leave empty to keep existing image.</p>
                </div>

                <!-- Option B: External URL -->
                <div>
                    <label for="image_url" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">
                        Or External Image URL
                    </label>
                    <input type="url"
                           id="image_url"
                           name="image_url"
                           value="{{ old('image_url', str_starts_with($banner->image, 'http') ? $banner->image : '') }}"
                           placeholder="https://images.unsplash.com/..."
                           class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">
                    <p class="text-[11px] text-slate-400 mt-1">Enter direct image URL to replace.</p>
                </div>
            </div>
        </div>

        <!-- Card 2: Headline Copy & Content -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-5 sm:p-6 shadow-xs space-y-5">
            <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3">
                <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">2. Headline & Copy Details</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Edit headline, sub-headline, and CTA action.</p>
            </div>

            <!-- Title / Headline -->
            <div>
                <label for="title" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">
                    Banner Headline / Title <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       id="title"
                       name="title"
                       value="{{ old('title', $banner->title) }}"
                       required
                       placeholder="e.g. Smart Devices ⌚ & Wearable Tech"
                       class="w-full px-3.5 py-2.5 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">
            </div>

            <!-- Subtitle / Promotional Description -->
            <div>
                <label for="subtitle" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">
                    Sub-Headline / Description Text
                </label>
                <textarea id="subtitle"
                          name="subtitle"
                          rows="3"
                          placeholder="e.g. Discover the latest smartwatches, fitness trackers..."
                          class="w-full px-3.5 py-2.5 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">{{ old('subtitle', $banner->subtitle) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Button Text -->
                <div>
                    <label for="button_text" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">
                        Button Label / CTA <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="button_text"
                           name="button_text"
                           value="{{ old('button_text', $banner->button_text) }}"
                           required
                           placeholder="e.g. ORDER NOW, SHOP NOW"
                           class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">
                </div>

                <!-- Button Target Link -->
                <div>
                    <label for="button_link" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">
                        Button Target Link (URL) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="button_link"
                           name="button_link"
                           value="{{ old('button_link', $banner->button_link) }}"
                           required
                           placeholder="e.g. #smartwatches or /category/electronics"
                           class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500 font-mono">
                </div>
            </div>
        </div>

        <!-- Card 3: Display Sequence & Publishing Status -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-5 sm:p-6 shadow-xs space-y-5">
            <div class="border-b border-slate-100 dark:border-slate-700/60 pb-3">
                <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">3. Sequence & Visibility</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Control slide ordering and live visibility.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Sequence Order -->
                <div>
                    <label for="order" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">
                        Sort Sequence Order
                    </label>
                    <input type="number"
                           id="order"
                           name="order"
                           min="0"
                           max="9999"
                           value="{{ old('order', $banner->order) }}"
                           class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500">
                    <p class="text-[11px] text-slate-400 mt-1">Lower numbers appear first (e.g. 1 is first slide).</p>
                </div>

                <!-- Status Select -->
                <div>
                    <label for="status" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">
                        Publishing Status <span class="text-rose-500">*</span>
                    </label>
                    <select id="status"
                            name="status"
                            class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-brand-500 cursor-pointer">
                        <option value="active" {{ old('status', $banner->status) === 'active' ? 'selected' : '' }}>Active (Shown in Hero Carousel)</option>
                        <option value="inactive" {{ old('status', $banner->status) === 'inactive' ? 'selected' : '' }}>Inactive (Draft / Hidden)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Sticky Submit Actions -->
        <div class="p-4 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-between shadow-xs">
            <a href="{{ route('admin.appearance.banners.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-sm transition cursor-pointer">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Update Banner Slide</span>
            </button>
        </div>

    </form>

</div>

<!-- Real-time Live Preview Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const titleInput = document.getElementById('title');
    const subtitleInput = document.getElementById('subtitle');
    const buttonTextInput = document.getElementById('button_text');
    const fileInput = document.getElementById('image_file');
    const urlInput = document.getElementById('image_url');

    const previewImg = document.getElementById('bannerImagePreview');
    const previewTitle = document.getElementById('previewTitleText');
    const previewSubtitle = document.getElementById('previewSubtitleText');
    const previewBtn = document.getElementById('previewButtonText');

    titleInput?.addEventListener('input', () => {
        previewTitle.textContent = titleInput.value || 'Your Headline Here';
    });

    subtitleInput?.addEventListener('input', () => {
        previewSubtitle.textContent = subtitleInput.value || '';
    });

    buttonTextInput?.addEventListener('input', () => {
        previewBtn.textContent = buttonTextInput.value || 'ORDER NOW';
    });

    fileInput?.addEventListener('change', (e) => {
        const file = e.target.files?.[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (evt) => {
                previewImg.src = evt.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    urlInput?.addEventListener('input', () => {
        if (urlInput.value && (urlInput.value.startsWith('http://') || urlInput.value.startsWith('https://'))) {
            previewImg.src = urlInput.value;
        }
    });
});
</script>
@endsection
