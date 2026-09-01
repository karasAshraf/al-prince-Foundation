@props([
    'active' => false,
])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center px-2 py-2 font-navbar text-[15px] font-bold text-text-primary dark:text-white border-b-2 border-primary dark:border-primary-light transition-all duration-150 ease-in-out focus:outline-none'
    : 'inline-flex items-center px-2 py-2 font-navbar text-[15px] font-bold text-text-secondary dark:text-gray-300 hover:text-primary-light dark:hover:text-primary-light transition-all duration-150 ease-in-out focus:outline-none';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} @if($active) aria-current="page" @endif>
    {{ $slot }}
</a>
