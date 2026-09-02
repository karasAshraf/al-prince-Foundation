@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
    'disabled' => false,
])

@php
$baseClasses = 'inline-flex items-center justify-center font-semibold rounded-xl transition-all duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-offset-surface dark:focus-visible:ring-offset-gray-900 disabled:opacity-50 disabled:cursor-not-allowed disabled:pointer-events-none disabled:shadow-none aria-disabled:opacity-50 aria-disabled:pointer-events-none cursor-pointer gap-2 select-none active:scale-[0.98] motion-reduce:transition-none motion-reduce:scale-100 motion-reduce:transform-none';

$variantClasses = match ($variant) {
    // Primary: bronze bg, white text — hover shifts to surface, focus uses ring-surface, active uses surface/90
    'primary'   => 'bg-primary text-background hover:bg-secondary hover:text-text-primary hover:border-secondary focus-visible:ring-secondary active:bg-secondary/90 active:text-text-primary shadow-sm hover:shadow-md',

    // Secondary: light yellow bg, dark text — hover shifts to accent tint, focus stays yellow, active stays yellow
    // CRITICAL: explicit focus:bg-secondary prevents browser-default white bleed
    'secondary'  => 'bg-secondary text-text-primary hover:bg-secondary/40 focus:bg-secondary focus-visible:bg-secondary focus-visible:ring-primary active:bg-secondary/50 shadow-sm',

    // Accent: green-tint bg, dark text
    'accent'     => 'bg-secondary text-text-primary hover:bg-accent focus:bg-secondary focus-visible:bg-secondary focus-visible:ring-primary active:bg-accent shadow-sm',

    // Outline: transparent with border — hover uses surface, focus uses ring-surface, active uses surface/90
    'outline'    => 'bg-transparent border-2 border-primary text-primary hover:bg-secondary hover:text-text-primary hover:border-secondary focus-visible:ring-secondary active:bg-secondary/90 active:text-text-primary',

    // Ghost: transparent, no border — hover adds faint yellow tint, focus stays transparent
    'ghost'      => 'bg-transparent text-text-primary dark:text-gray-200 hover:bg-secondary/50 dark:hover:bg-gray-800 hover:text-primary focus:bg-transparent focus-visible:bg-transparent dark:focus-visible:bg-transparent focus-visible:ring-primary active:bg-secondary/60',

    default      => 'bg-primary text-background hover:bg-secondary hover:text-text-primary hover:border-secondary focus-visible:ring-secondary active:bg-secondary/90 active:text-text-primary shadow-sm hover:shadow-md',
};

$sizeClasses = match ($size) {
    'sm'    => 'px-3.5 py-2 text-xs min-h-[36px]',
    'md'    => 'px-5 py-2.5 text-sm min-h-[44px]',
    'lg'    => 'px-7 py-3.5 text-base min-h-[48px]',
    default => 'px-5 py-2.5 text-sm min-h-[44px]',
};

$classes = "{$baseClasses} {$variantClasses} {$sizeClasses}";
@endphp

@if ($href)
    <a href="{{ $disabled ? '#' : $href }}"
       @if($disabled) aria-disabled="true" tabindex="-1" @endif
       {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}"
            @if($disabled) disabled aria-disabled="true" @endif
            {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
