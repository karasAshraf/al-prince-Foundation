@props(['type' => 'button'])

<button
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center justify-center gap-2 rounded-lg bg-[#B49C6E] px-4 py-2.5 text-sm font-semibold text-[#3D342A] transition-colors hover:bg-[#B49C6E]/80 focus:outline-none focus:ring-2 focus:ring-[#B49C6E] focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50'
    ]) }}
>
    {{ $slot }}
</button>