@props([
    'paginator' => null,
])

@if ($paginator && $paginator->hasPages())
    <nav class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-8 mt-8 border-t border-secondary/20"
         aria-label="{{ __('frontend.pagination') }}">

        <p class="text-sm text-text-primary/70 dark:text-gray-400">
            {{ __('frontend.showing') }}
            <span class="font-semibold text-text-primary dark:text-gray-200">{{ $paginator->firstItem() }}</span>
            {{ __('frontend.to') }}
            <span class="font-semibold text-text-primary dark:text-gray-200">{{ $paginator->lastItem() }}</span>
            {{ __('frontend.of') }}
            <span class="font-semibold text-text-primary dark:text-gray-200">{{ $paginator->total() }}</span>
            {{ __('frontend.results') }}
        </p>

        <div class="flex items-center gap-2">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 rounded-lg text-sm font-medium text-text-primary/30 cursor-not-allowed inline-flex items-center justify-center">
                    <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium text-text-primary/80 dark:text-gray-300 hover:bg-secondary/40 hover:text-primary transition-colors inline-flex items-center justify-center"
                   aria-label="{{ __('frontend.previous_page') }}">
                    <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            @endif

            {{-- Page numbers --}}
            @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="px-3.5 py-2 rounded-lg text-sm font-semibold bg-primary text-background shadow-sm">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}"
                       class="px-3.5 py-2 rounded-lg text-sm font-medium text-text-primary/80 dark:text-gray-300 hover:bg-secondary/40 hover:text-primary transition-colors">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium text-text-primary/80 dark:text-gray-300 hover:bg-secondary/40 hover:text-primary transition-colors inline-flex items-center justify-center"
                   aria-label="{{ __('frontend.next_page') }}">
                    <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @else
                <span class="px-3 py-2 rounded-lg text-sm font-medium text-text-primary/30 cursor-not-allowed inline-flex items-center justify-center">
                    <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
