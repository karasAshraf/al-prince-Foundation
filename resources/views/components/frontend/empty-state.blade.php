@props([
    'title' => __('frontend.no_data_available'),
    'description' => null,
    'icon' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center text-center p-8 sm:p-12 rounded-2xl bg-background/50 dark:bg-gray-800/50 border border-dashed border-secondary/40 space-y-4 my-6']) }}>
    <div class="w-16 h-16 rounded-2xl bg-secondary/40 text-primary flex items-center justify-center p-3 shadow-inner">
        @if ($icon)
            {{ $icon }}
        @else
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
        @endif
    </div>

    <div class="space-y-1 max-w-md">
        <h3 class="text-lg font-bold text-text-primary dark:text-background">
            {{ $title }}
        </h3>
        @if ($description)
            <p class="text-sm text-text-primary/70 dark:text-gray-400">
                {{ $description }}
            </p>
        @endif
    </div>

    @if (isset($action))
        <div class="pt-2">
            {{ $action }}
        </div>
    @endif
</div>
