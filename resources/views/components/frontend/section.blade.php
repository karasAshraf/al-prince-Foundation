@props([
    'badge' => null,
    'title' => null,
    'description' => null,
    'index' => 0,
    'align' => 'center',
    'id' => null,
])

@php
    // Color Rhythm distribution: Even indexes use Soft Gray (#E1DFDD via default body or F5F5F5 base)
    // Here we enforce alternating section backgrounds:
    // Even (0, 2, 4...) -> bg-[#F5F5F5] (White backdrop canvas)
    // Odd (1, 3, 5...) -> bg-white (or Light Gray #EAEAE9 depending on layout hierarchy, we use bg-[#EAEAE9] for clear section-level rhythm variation)
    $bgClass = ($index % 2 === 0) ? 'bg-[#F5F5F5]' : 'bg-white';
@endphp

<section 
    @if($id) id="{{ $id }}" @endif
    {{ $attributes->merge(['class' => "py-16 md:py-20 border-b border-border/10 transition-colors duration-200 {$bgClass}"]) }}
>
    <x-frontend.container>
        @if($badge || $title || $description)
            <div class="mb-10 md:mb-12">
                <x-frontend.section-title 
                    :badge="$badge"
                    :title="$title"
                    :description="$description"
                    :align="$align"
                />
            </div>
        @endif

        <div class="w-full">
            {{ $slot }}
        </div>
    </x-frontend.container>
</section>
