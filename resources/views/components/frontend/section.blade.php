@props([
    'badge' => null,
    'title' => null,
    'description' => null,
    'index' => 0,
    'align' => 'center',
    'id' => null,
])

@php
    $bgClass = 'bg-background';
@endphp

<section 
    @if($id) id="{{ $id }}" @endif
    {{ $attributes->merge(['class' => "py-16 md:py-20 border-b border-secondary/10 transition-colors duration-200 {$bgClass}"]) }}
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
