@props([
    'variant' => 'primary',
    'size' => 'md',
])

@php
$baseClasses = 'inline-flex items-center gap-1.5 font-semibold rounded-full tracking-wide transition-colors duration-150';

$variantClasses = match ($variant) {
    'primary' => 'bg-primary text-white',
    'secondary' => 'bg-secondary-light text-text-primary border border-primary-light/40',
    'accent' => 'bg-primary-light/30 text-text-primary dark:text-primary-light border border-primary-light/50',
    'outline' => 'bg-transparent border border-primary text-primary dark:text-primary-light',
    default => 'bg-primary text-white',
};

$sizeClasses = match ($size) {
    'sm' => 'px-2.5 py-0.5 text-xs',
    'md' => 'px-3.5 py-1 text-xs sm:text-sm',
    default => 'px-3.5 py-1 text-xs sm:text-sm',
};

$classes = "{$baseClasses} {$variantClasses} {$sizeClasses}";
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
