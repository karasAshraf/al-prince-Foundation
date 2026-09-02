@props([
    'badge' => null,
    'title' => null,
    'description' => null,
    'align' => 'center',
])

@php
$alignClasses = match ($align) {
    'start' => 'text-start items-start',
    'end' => 'text-end items-end',
    'center' => 'text-center items-center mx-auto',
    default => 'text-center items-center mx-auto',
};
@endphp

<div {{ $attributes->merge(['class' => "flex flex-col max-w-3xl space-y-3 mb-10 md:mb-14 {$alignClasses}"]) }}>
    @if ($badge || isset($badgeSlot))
        <x-frontend.badge variant="secondary" size="md" class="w-fit">
            {{ $badge ?? $badgeSlot }}
        </x-frontend.badge>
    @endif

    @if ($title || isset($titleSlot))
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-text-primary dark:text-background tracking-tight leading-tight">
            {{ $title ?? $titleSlot }}
        </h2>
    @endif

    @if ($description || isset($descriptionSlot))
        <p class="text-base sm:text-lg text-text-primary/75 dark:text-gray-300 leading-relaxed font-medium">
            {{ $description ?? $descriptionSlot }}
        </p>
    @endif

    {{ $slot }}
</div>
