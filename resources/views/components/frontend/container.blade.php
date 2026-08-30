@props([
    'fluid' => false,
])

<div {{ $attributes->merge(['class' => ($fluid ? 'w-full px-4 sm:px-6 lg:px-8' : 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full')]) }}>
    {{ $slot }}
</div>
