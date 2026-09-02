@props([
    'variant' => 'primary',
    'size' => 'md',
])

@php
$baseClasses = 'inline-flex items-center gap-1.5 font-semibold rounded-full tracking-wide transition-colors duration-150';

$variantClasses = match ($variant) {
    'primary' => 'bg-primary text-background',
    'secondary' => 'bg-secondary text-text-primary border border-secondary/40',
    'accent' => 'bg-secondary/30 text-text-primary dark:text-secondary border border-secondary/50',
    'outline' => 'bg-transparent border border-primary text-primary dark:text-secondary',
    default => 'bg-primary text-background',
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
