@props([
    'hoverable' => true,
    'padding' => 'md',
    'variant' => 'default',
])

@php
$baseClasses = 'bg-background dark:bg-gray-800/90 border border-secondary/20 rounded-2xl overflow-hidden transition-all duration-300 ease-out focus-within:outline-none focus-within:ring-2 focus-within:ring-secondary focus-within:ring-offset-2 focus-within:ring-offset-secondary dark:focus-within:ring-offset-gray-900 motion-reduce:transition-none motion-reduce:transform-none motion-reduce:hover:transform-none';

$hoverClasses = $hoverable ? 'hover:shadow-lg hover:shadow-[#A38B54]/10 hover:-translate-y-1 hover:border-[#A38B54]/40 hover:bg-secondary/10 active:scale-[0.98] active:duration-150 active:bg-secondary/20' : 'shadow-sm';

$paddingClasses = match ($padding) {
    'none' => 'p-0',
    'sm' => 'p-4',
    'md' => 'p-6',
    'lg' => 'p-8',
    default => 'p-6',
};

$negativeMarginClasses = match ($padding) {
    'none' => '',
    'sm' => '-mx-4 -mb-4',
    'md' => '-mx-6 -mb-6',
    'lg' => '-mx-8 -mb-8',
    default => '-mx-6 -mb-6',
};

$classes = "{$baseClasses} {$hoverClasses}";
@endphp

<article {{ $attributes->merge(['class' => $classes]) }}>
    @if (isset($header))
        <div class="border-b border-secondary/20 pb-4 mb-4">
            {{ $header }}
        </div>
    @endif

    <div class="{{ $paddingClasses }}">
        {{ $slot }}
    </div>

    @if (isset($footer))
        <div class="border-t border-secondary/20 pt-4 mt-4 bg-background/50 dark:bg-gray-900/50 px-6 py-3 {{ $negativeMarginClasses }}">
            {{ $footer }}
        </div>
    @endif
</article>
