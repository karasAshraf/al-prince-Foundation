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
    // Primary: Mustard Gold bg, Warm Off-White text
    'primary'   => 'bg-primary text-background hover:bg-primary/90 focus-visible:ring-primary active:bg-primary/95 shadow-sm hover:shadow-md',

    // Secondary: Warm Beige bg, Deep Brown text
    'secondary' => 'bg-secondary text-text-primary hover:bg-secondary/80 focus:bg-secondary focus-visible:ring-primary active:bg-secondary/90 shadow-sm',

    // Accent: Deep Brown bg, Warm Off-White text
    'accent'    => 'bg-accent text-background hover:bg-accent/90 focus-visible:ring-accent active:bg-accent/95 shadow-sm',

    // Outline: Transparent with Gold border and text
    'outline'   => 'bg-transparent border-2 border-primary text-primary hover:bg-primary hover:text-background focus-visible:ring-primary active:bg-primary/90',

    // Ghost: Transparent with Deep Brown text
    'ghost'     => 'bg-transparent text-text-primary hover:bg-secondary/50 hover:text-primary focus-visible:ring-primary active:bg-secondary/60',

    default     => 'bg-primary text-background hover:bg-primary/90 focus-visible:ring-primary active:bg-primary/95 shadow-sm hover:shadow-md',
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
