@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center space-x-1 mt-4">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-400 dark:text-slate-500 bg-gray-100 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-md cursor-not-allowed">
                &laquo;
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 dark:text-white bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-md hover:bg-gray-100 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                &laquo;
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-400 dark:text-slate-500 bg-gray-100 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-md">
                    {{ $element }}
                </span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span
                            class="inline-flex items-center px-3 py-1.5 text-sm font-bold text-white bg-blue-600 border border-blue-600 rounded-md cursor-default">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 dark:text-white bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-md hover:bg-gray-100 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 dark:text-white bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-md hover:bg-gray-100 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                &raquo;
            </a>
        @else
            <span
                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-400 dark:text-slate-500 bg-gray-100 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-md cursor-not-allowed">
                &raquo;
            </span>
        @endif
    </nav>
@endif
