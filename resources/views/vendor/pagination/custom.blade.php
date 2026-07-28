@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col sm:flex-row items-center justify-between gap-4">

        {{-- Results summary --}}
        <p class="text-sm text-gray-500 order-2 sm:order-1">
            Showing
            <span class="font-semibold text-gray-700">{{ $paginator->firstItem() }}</span>
            to
            <span class="font-semibold text-gray-700">{{ $paginator->lastItem() }}</span>
            of
            <span class="font-semibold text-gray-700">{{ $paginator->total() }}</span>
            results
        </p>

        {{-- Page links --}}
        <div class="flex items-center gap-1.5 order-1 sm:order-2">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous page"
                   class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 text-gray-600 bg-white hover:bg-gray-50 hover:border-gray-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
            @endif

            {{-- Numbers --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span aria-hidden="true" class="inline-flex items-center justify-center w-9 h-9 text-gray-400 select-none">…</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="inline-flex items-center justify-center min-w-9 h-9 px-3 rounded-lg bg-[#CD571B] text-white text-sm font-semibold shadow-sm shadow-orange-200">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" aria-label="Go to page {{ $page }}"
                               class="inline-flex items-center justify-center min-w-9 h-9 px-3 rounded-lg border border-gray-200 text-gray-600 bg-white hover:bg-gray-50 hover:border-[#CD571B]/40 hover:text-[#CD571B] text-sm font-medium transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next page"
                   class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 text-gray-600 bg-white hover:bg-gray-50 hover:border-gray-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span aria-disabled="true" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif
        </div>
    </nav>
@endif
