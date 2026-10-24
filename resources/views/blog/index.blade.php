@extends('layouts.site')

@section('title', 'Blog & Knowledge Base - ' . setting('site_name', 'DigiGo'))

@section('content')
<div class="max-w-[1440px] mx-auto px-4 sm:px-6 py-10">

    <!-- Header Breadcrumb & Title -->
    <div class="mb-10 text-center max-w-2xl mx-auto">
        <nav class="flex items-center justify-center gap-2 text-xs sm:text-sm text-slate-500 mb-4">
            <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Home</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">Blog & Articles</span>
        </nav>

        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-3">
            Latest News, Guides & Insights
        </h1>
        <p class="text-sm sm:text-base text-slate-500 leading-relaxed">
            Discover tips, tutorials, updates on digital subscriptions, AI workflows, and software keys from the DigiGo team.
        </p>
    </div>

    <!-- Category Pills Filter & Search -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-10 pb-6 border-b border-slate-200">
        <div class="flex items-center gap-2 overflow-x-auto w-full sm:w-auto pb-2 sm:pb-0">
            <a href="{{ route('blog.index') }}" class="px-4 py-2 rounded-full text-xs font-bold transition shrink-0 {{ !request('category') ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                All Articles
            </a>
            @foreach($categories as $category)
                <a href="{{ route('blog.index', ['category' => $category->slug]) }}" class="px-4 py-2 rounded-full text-xs font-bold transition shrink-0 {{ request('category') === $category->slug ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    {{ $category->name }} ({{ $category->posts_count }})
                </a>
            @endforeach
        </div>

        <form action="{{ route('blog.index') }}" method="GET" class="w-full sm:w-72 relative">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Search blog..."
                   class="w-full h-10 pl-9 pr-4 rounded-xl border border-slate-200 text-xs bg-white focus:outline-none focus:border-blue-600 text-slate-900">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-slate-400 text-xs"></i>
        </form>
    </div>

    <!-- Posts Grid -->
    @if($posts->isEmpty())
        <div class="py-16 text-center text-slate-500 bg-white rounded-2xl border border-slate-200 p-8">
            <i class="fa-solid fa-newspaper text-5xl text-slate-300 mb-4 block"></i>
            <h3 class="text-lg font-bold text-slate-800 mb-1">No articles found</h3>
            <p class="text-xs text-slate-500 mb-6">Check back soon or try searching for another topic.</p>
            <a href="{{ route('blog.index') }}" class="inline-flex px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition">
                Reset Filter
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @foreach($posts as $post)
                <article class="bg-white rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition duration-300 overflow-hidden flex flex-col group">
                    <a href="{{ route('blog.show', $post->slug) }}" class="relative aspect-[16/10] overflow-hidden bg-slate-100 block">
                        @if($post->thumbnail_img)
                            <img src="{{ $post->thumbnail_img }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400 bg-gradient-to-br from-slate-100 to-slate-200">
                                <i class="fa-regular fa-newspaper text-3xl"></i>
                            </div>
                        @endif
                        @if($post->category)
                            <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-blue-600 text-white text-[11px] font-bold shadow-sm">
                                {{ $post->category->name }}
                            </span>
                        @endif
                    </a>

                    <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-xs text-slate-400 mb-2.5">
                                <span><i class="fa-regular fa-calendar mr-1"></i>{{ $post->created_at->format('M d, Y') }}</span>
                                <span>&bull;</span>
                                <span>{{ max(1, ceil(str_word_count(strip_tags($post->detailes)) / 200)) }} min read</span>
                            </div>
                            <h2 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-blue-600 transition line-clamp-2 leading-snug mb-2">
                                <a href="{{ route('blog.show', $post->slug) }}">
                                    {{ $post->title }}
                                </a>
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-500 line-clamp-3 leading-relaxed">
                                {{ Str::limit(strip_tags($post->detailes), 130) }}
                            </p>
                        </div>

                        <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                            <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-800 transition">
                                <span>Read Full Article</span>
                                <i class="fa-solid fa-angle-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        @if($posts->hasPages())
            <div class="mt-12">
                {{ $posts->links() }}
            </div>
        @endif
    @endif

</div>
@endsection

