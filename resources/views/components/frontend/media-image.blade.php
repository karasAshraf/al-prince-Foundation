@props([
    'src'        => null,
    'alt'        => '',
    'aspectRatio'=> '4/3',
    'rounded'    => 'rounded-2xl',
    'objectFit'  => 'object-cover',
    'fallback'   => null,
    'overlay'    => false,
])

@php
    $aspectClass = match ($aspectRatio) {
        '1/1'  => 'aspect-square',
        '16/9' => 'aspect-video',
        '4/3'  => 'aspect-[4/3]',
        '3/4'  => 'aspect-[3/4]',
        default => 'aspect-[4/3]',
    };
@endphp

<div {{ $attributes->merge(['class' => "overflow-hidden {$rounded} bg-secondary-light/10 dark:bg-gray-800/50 {$aspectClass} w-full relative group/media"]) }}>
    @if ($src)
        <img src="{{ $src }}"
             alt="{{ $alt }}"
             loading="lazy"
             class="w-full h-full {{ $objectFit }} transition-transform duration-700 ease-out group-hover/media:scale-105">
             
        @if ($overlay)
            <div class="absolute inset-0 bg-gradient-to-t from-primary/70 via-primary/10 to-transparent pointer-events-none z-10"></div>
        @endif
    @elseif ($fallback)
        {{ $fallback }}
    @else
        <div class="w-full h-full flex flex-col items-center justify-center text-primary/30 dark:text-gray-700 gap-2">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
    @endif
</div>
