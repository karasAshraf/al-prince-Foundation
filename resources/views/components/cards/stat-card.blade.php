@props([
    'label',
    'value',
    'icon'  => 'chart',
    'url'   => null,
])

@php
    $icons = [
        'newspaper' => 'M12 7v14m0-14a4 4 0 00-4-4H3v14h5a4 4 0 014 4M12 7a4 4 0 014-4h5v14h-5a4 4 0 00-4 4',
        'folder'    => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z',
        'flag'      => 'M5 3v18M5 4h11l-2 4 2 4H5',
        'users'     => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m5-3.13a4 4 0 100-8 4 4 0 000 8zm6 4a4 4 0 00-3-3.87',
        'mail'      => 'M3 6h18v12H3V6zm0 0l9 7 9-7',
        'clipboard' => 'M9 3h6a1 1 0 011 1v1H8V4a1 1 0 011-1zM6 5h12a1 1 0 011 1v14a1 1 0 01-1 1H6a1 1 0 01-1-1V6a1 1 0 011-1z',
        'chart'     => 'M3 3v18h18M7 15l4-4 3 3 5-6',
    ];

    // Spec: white card, 0.5px border #B7B5B3, border-radius 12px, padding 16px
    // Icon: 36x36px rounded square, bg #EAEAE9, icon color #A38B54
    // Number: 22px, weight 500, #3D342A | Label: 12px, #5C5450
    $cardHtml = '<div class="rounded-xl border border-[#B7B5B3]/60 bg-white p-4 transition-all duration-300 hover:shadow-md">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#EAEAE9] text-[#A38B54]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="' . ($icons[$icon] ?? $icons['chart']) . '" />
                </svg>
            </div>
            <div>
                <p class="text-[22px] font-medium leading-tight text-[#3D342A]">' . $value . '</p>
                <p class="text-[12px] text-[#5C5450] mt-0.5">' . $label . '</p>
            </div>
        </div>
    </div>';
@endphp

@if($url)
    <a href="{{ $url }}" class="block">
        {!! $cardHtml !!}
    </a>
@else
    {!! $cardHtml !!}
@endif
