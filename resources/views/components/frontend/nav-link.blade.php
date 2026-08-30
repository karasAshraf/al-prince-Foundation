@props([
    'active' => false,
])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center px-2 py-2 font-navbar text-[15px] font-semibold text-primary dark:text-primary-light bg-primary/10 dark:bg-primary/20 rounded-xl transition-all duration-150 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2'
    : 'inline-flex items-center px-2 py-2 font-navbar text-[15px] font-semibold text-text-primary/80 dark:text-gray-300 hover:text-primary dark:hover:text-primary-light hover:bg-secondary-light/40 dark:hover:bg-gray-800/60 rounded-xl transition-all duration-150 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} @if($active) aria-current="page" @endif>
    {{ $slot }}
</a>
