@props(['type' => 'button'])

<button
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center justify-center gap-2 rounded-lg bg-[#A38B54] px-4 py-2.5 text-sm font-semibold text-secondary transition-colors hover:bg-[#A38B54]/90 focus:outline-none focus:ring-2 focus:ring-[#A38B54] focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50'
    ]) }}
>
    {{ $slot }}
</button>
