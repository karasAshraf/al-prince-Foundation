@props(['type' => 'button'])

<button
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center justify-center gap-2 rounded-lg border border-[#B49C6E]/40 bg-[#EAEAE9] px-4 py-2.5 text-sm font-semibold text-[#3D342A] transition-colors hover:bg-[#EAEAE9]/40 focus:outline-none focus:ring-2 focus:ring-[#B49C6E] focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50'
    ]) }}
>
    {{ $slot }}
</button>