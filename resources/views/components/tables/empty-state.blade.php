{{-- Alias: delegates to empty.state.blade.php content --}}
@props([
    'title'       => 'لا توجد بيانات',
    'message'     => 'لم يتم إضافة أي عناصر بعد.',
    'actionLabel' => null,
    'actionUrl'   => null,
])

<div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-[#B49C6E]/40 bg-secondary/10 px-6 py-16 text-center">
    <svg xmlns="http://www.w3.org/2000/svg" class="mb-3 h-12 w-12 text-[#B49C6E]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
    </svg>
    <h3 class="mb-1 text-sm font-semibold text-[#3D342A]">{{ $title }}</h3>
    <p class="mb-4 text-sm text-[#3D342A]/60">{{ $message }}</p>
    @if($actionLabel && $actionUrl)
        <a href="{{ $actionUrl }}">
            <x-buttons.primary>{{ $actionLabel }}</x-buttons.primary>
        </a>
    @endif
</div>
