@props([
    'placeholder' => 'بحث...',
    'name' => 'search',
])

<form method="GET" class="relative w-full sm:max-w-xs">
    {{-- Preserve other query params (like filters) already in the URL --}}
    @foreach(request()->except($name, 'page') as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach

    <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute start-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#3D342A]/40" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
    </svg>

    <input
        type="search"
        name="{{ $name }}"
        value="{{ request($name) }}"
        placeholder="{{ $placeholder }}"
        class="w-full rounded-lg border border-[#B49C6E]/40 bg-secondary ps-9 pe-3 py-2 text-sm text-[#3D342A] focus:border-[#A38B54] focus:outline-none focus:ring-1 focus:ring-[#A38B54]"
    >
</form>

