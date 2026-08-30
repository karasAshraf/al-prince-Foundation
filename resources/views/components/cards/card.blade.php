@props([
    'label',
    'value',
    'icon' => 'chart',
    'color' => 'primary', // primary | accent | secondary
    'url' => null,
])

@php
    $colorClasses = match($color) {
        'accent' => 'bg-[#B49C6E]/20 text-[#A38B54]',
        'secondary' => 'bg-[#EAEAE9]/40 text-[#3D342A]',
        default => 'bg-[#A38B54]/10 text-[#A38B54]',
    };

    $icons = [
        'newspaper' => 'M12 7v14m0-14a4 4 0 00-4-4H3v14h5a4 4 0 014 4M12 7a4 4 0 014-4h5v14h-5a4 4 0 00-4 4',
        'folder'    => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z',
        'flag'      => 'M5 3v18M5 4h11l-2 4 2 4H5',
        'users'     => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m5-3.13a4 4 0 100-8 4 4 0 000 8zm6 4a4 4 0 00-3-3.87',
        'mail'      => 'M3 6h18v12H3V6zm0 0l9 7 9-7',
        'clipboard' => 'M9 3h6a1 1 0 011 1v1H8V4a1 1 0 011-1zM6 5h12a1 1 0 011 1v14a1 1 0 01-1 1H6a1 1 0 01-1-1V6a1 1 0 011-1z',
        'chart'     => 'M3 3v18h18M7 15l4-4 3 3 5-6',
    ];
@endphp

@php $content = null; @endphp
@php ob_start(); @endphp

<div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5 transition-shadow hover:shadow-sm">
    <div class="flex items-center gap-4">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg {{ $colorClasses }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$icon] ?? $icons['chart'] }}" />
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-[#3D342A]">{{ $value }}</p>
            <p class="text-sm text-[#3D342A]/60">{{ $label }}</p>
        </div>
    </div>
</div>

@php $content = ob_get_clean(); @endphp

@if($url)
    <a href="{{ $url }}" class="block">{!! $content !!}</a>
@else
    {!! $content !!}
@endif
