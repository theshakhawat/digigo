@extends('layouts.site')

@section('title', $post->title . ' - DigiGo Blog')

@push('styles')
<style>
    .post-content {
        color: #334155;
        font-size: 1.05rem;
        line-height: 1.85;
    }
    .post-content p {
        margin-bottom: 1.25rem;
    }
    .post-content h1,
    .post-content h2,
    .post-content h3,
    .post-content h4 {
        color: #0f172a;
        font-weight: 800;
        margin-top: 2rem;
        margin-bottom: 0.75rem;
        line-height: 1.3;
    }
    .post-content h2 { font-size: 1.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem; }
    .post-content h3 { font-size: 1.25rem; }
    .post-content ul {
        list-style-type: disc !important;
        margin-left: 1.75rem !important;
        margin-bottom: 1.25rem !important;
    }
    .post-content ol {
        list-style-type: decimal !important;
        margin-left: 1.75rem !important;
        margin-bottom: 1.25rem !important;
    }
    .post-content li {
        margin-bottom: 0.5rem;
    }
    .post-content img {
        max-width: 100%;
        height: auto;
        border-radius: 1rem;
        margin: 1.5rem 0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .post-content blockquote {
        border-left: 4px solid #2563eb;
        padding-left: 1.25rem;
        font-style: italic;
        color: #475569;
        margin: 1.5rem 0;
        background: #f8fafc;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
        border-radius: 0 0.75rem 0.75rem 0;
    }
</style>
@endpush

@section('content')
<div class="max-w-[1000px] mx-auto px-4 sm:px-6 py-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-6 flex-wrap">
        <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Home</a>
        <span>/</span>
        <a href="{{ route('blog.index') }}" class="hover:text-blue-600 transition">Blog</a>
        <span>/</span>
        @if($post->category)
            <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="hover:text-blue-600 transition">{{ $post->category->name }}</a>
            <span>/</span>
        @endif
        <span class="text-slate-900 font-semibold truncate max-w-xs">{{ $post->title }}</span>
    </nav>

    <!-- Article Header -->
    <header class="mb-8">
        @if($post->category)
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold uppercase tracking-wider mb-4">
                {{ $post->category->name }}
            </span>
        @endif

        <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight mb-4">
            {{ $post->title }}
        </h1>

        <div class="flex items-center gap-4 text-xs sm:text-sm text-slate-500 pb-6 border-b border-slate-200">
            <span class="flex items-center gap-1.5"><i class="fa-regular fa-calendar"></i> {{ $post->created_at->format('F d, Y') }}</span>
            <span>&bull;</span>
            <span class="flex items-center gap-1.5"><i class="fa-regular fa-clock"></i> {{ max(1, ceil(str_word_count(strip_tags($post->detailes)) / 200)) }} min read</span>
            @if($post->is_featured)
                <span>&bull;</span>
                <span class="inline-flex items-center gap-1 text-amber-600 font-bold"><i class="fa-solid fa-star text-xs"></i> Featured Article</span>
            @endif
        </div>
    </header>

    <!-- Main Featured Image -->
    @if($post->thumbnail_img)
        <div class="rounded-2xl overflow-hidden mb-10 shadow-sm border border-slate-200 aspect-[16/9] bg-slate-100">
            <img src="{{ $post->thumbnail_img }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
        </div>
    @endif

    <!-- Article Content -->
    <div class="post-content bg-white rounded-2xl p-6 sm:p-10 border border-slate-200 shadow-xs mb-12">
        {!! $post->detailes !!}
    </div>

    <!-- Related Articles -->
    @if(isset($relatedPosts) && $relatedPosts->isNotEmpty())
        <div class="mt-16 pt-12 border-t border-slate-200">
            <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight mb-6">
                Related Articles
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedPosts as $rel)
                    <article class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md transition">
                        <a href="{{ route('blog.show', $rel->slug) }}" class="block aspect-video bg-slate-100 overflow-hidden">
                            @if($rel->thumbnail_img)
                                <img src="{{ $rel->thumbnail_img }}" alt="{{ $rel->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                    <i class="fa-regular fa-newspaper text-xl"></i>
                                </div>
                            @endif
                        </a>
                        <div class="p-4">
                            <h4 class="font-bold text-sm text-slate-900 line-clamp-2 hover:text-blue-600">
                                <a href="{{ route('blog.show', $rel->slug) }}">{{ $rel->title }}</a>
                            </h4>
                            <span class="text-[11px] text-slate-400 block mt-2">{{ $rel->created_at->format('M d, Y') }}</span>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection

