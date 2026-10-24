@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between gap-4 py-3">
        {{-- Mobile View --}}
        <div class="flex items-center justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-3.5 py-1.5 text-xs font-semibold text-slate-400 bg-slate-100 dark:bg-slate-800 dark:text-slate-500 rounded-lg cursor-not-allowed">
                    <i class="fa-solid fa-chevron-left mr-1.5 text-[10px]"></i> Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-3.5 py-1.5 text-xs font-semibold text-slate-700 bg-white dark:bg-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    <i class="fa-solid fa-chevron-left mr-1.5 text-[10px]"></i> Previous
                </a>
            @endif

            <span class="text-xs text-slate-500 font-medium">
                Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-3.5 py-1.5 text-xs font-semibold text-slate-700 bg-white dark:bg-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    Next <i class="fa-solid fa-chevron-right ml-1.5 text-[10px]"></i>
                </a>
            @else
                <span class="inline-flex items-center px-3.5 py-1.5 text-xs font-semibold text-slate-400 bg-slate-100 dark:bg-slate-800 dark:text-slate-500 rounded-lg cursor-not-allowed">
                    Next <i class="fa-solid fa-chevron-right ml-1.5 text-[10px]"></i>
                </span>
            @endif
        </div>

        {{-- Desktop / Tablet View --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Showing
                    @if ($paginator->firstItem())
                        <span class="font-bold text-slate-800 dark:text-slate-200">{{ $paginator->firstItem() }}</span>
                        to
                        <span class="font-bold text-slate-800 dark:text-slate-200">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    of
                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $paginator->total() }}</span>
                    results
                </p>
            </div>

            <div>
                <ul class="inline-flex items-center gap-1 text-xs">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li aria-disabled="true">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-300 dark:text-slate-600 bg-slate-50 dark:bg-slate-800/50 cursor-not-allowed">
                                <i class="fa-solid fa-chevron-left text-[11px]"></i>
                            </span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-blue-600 dark:hover:text-blue-400 transition" aria-label="Previous">
                                <i class="fa-solid fa-chevron-left text-[11px]"></i>
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- Dots Separator --}}
                        @if (is_string($element))
                            <li aria-disabled="true">
                                <span class="inline-flex items-center justify-center w-8 h-8 text-slate-400 dark:text-slate-500">…</span>
                            </li>
                        @endif

                        {{-- Array of page links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li aria-current="page">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg font-bold text-white bg-blue-600 shadow-xs">
                                            {{ $page }}
                                        </span>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ $url }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                            {{ $page }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li>
                            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-blue-600 dark:hover:text-blue-400 transition" aria-label="Next">
                                <i class="fa-solid fa-chevron-right text-[11px]"></i>
                            </a>
                        </li>
                    @else
                        <li aria-disabled="true">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-300 dark:text-slate-600 bg-slate-50 dark:bg-slate-800/50 cursor-not-allowed">
                                <i class="fa-solid fa-chevron-right text-[11px]"></i>
                            </span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@endif
