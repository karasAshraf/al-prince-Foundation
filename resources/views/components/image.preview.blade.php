@props([
    'url' => null,
    'alt' => 'صورة',
    'size' => 'md', // sm | md | lg
])

@php
    $sizeClasses = match($size) {
        'sm' => 'h-16 w-16',
        'lg' => 'h-40 w-40',
        default => 'h-24 w-24',
    };
@endphp

<div class="{{ $sizeClasses }} shrink-0 overflow-hidden rounded-lg border border-[#B49C6E]/30 bg-secondary/20">
    @if($url)
        <img src="{{ $url }}" alt="{{ $alt }}" class="h-full w-full object-cover">
    @else
        <div class="flex h-full w-full items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#B49C6E]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M2.25 12V4.5A2.25 2.25 0 014.5 2.25h15a2.25 2.25 0 012.25 2.25v15a2.25 2.25 0 01-2.25 2.25H4.5A2.25 2.25 0 012.25 19.5V12z" />
            </svg>
        </div>
    @endif
</div>