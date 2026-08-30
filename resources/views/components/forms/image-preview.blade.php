@props([
    'url'      => null,
    'size'     => 'md', // sm | md | lg
    'mimeType' => null, // optional: pass the mime type from Spatie media if available
])

@php
    $sizeClasses = match($size) {
        'sm' => 'h-10 w-10',
        'lg' => 'h-40 w-full',
        default => 'h-20 w-20',
    };

    /**
     * Determine media category from URL extension or mime type.
     * Never render PDFs or videos as <img>.
     */
    $mediaCategory = 'image'; // default assumption

    if ($mimeType) {
        if (str_starts_with($mimeType, 'image/'))       $mediaCategory = 'image';
        elseif ($mimeType === 'application/pdf')         $mediaCategory = 'pdf';
        elseif (str_starts_with($mimeType, 'video/'))   $mediaCategory = 'video';
        else                                             $mediaCategory = 'unknown';
    } elseif ($url) {
        $ext = strtolower(pathinfo(strtok($url, '?'), PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'bmp']))  $mediaCategory = 'image';
        elseif ($ext === 'pdf')                                                     $mediaCategory = 'pdf';
        elseif (in_array($ext, ['mp4', 'mov', 'avi', 'webm', 'mkv', 'ogg']))      $mediaCategory = 'video';
        // No extension / unknown → keep 'image' only if URL does not look like a doc
        else                                                                        $mediaCategory = 'image';
    }
@endphp

@if($url)
    @if($mediaCategory === 'image')
        <img
            src="{{ $url }}"
            alt=""
            {{ $attributes->merge(['class' => "{$sizeClasses} rounded-lg object-cover border border-[#B49C6E]/20"]) }}
            loading="lazy"
        >
    @elseif($mediaCategory === 'pdf')
        {{-- Show a PDF icon instead of a broken <img> --}}
        <div {{ $attributes->merge(['class' => "{$sizeClasses} flex items-center justify-center rounded-lg border border-red-200 bg-red-50"]) }}
             title="{{ basename(strtok($url, '?')) }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $size === 'sm' ? 'h-5 w-5' : 'h-7 w-7' }} text-red-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
        </div>
    @elseif($mediaCategory === 'video')
        {{-- Show a video thumbnail placeholder --}}
        <div {{ $attributes->merge(['class' => "{$sizeClasses} flex items-center justify-center rounded-lg border border-blue-200 bg-blue-50"]) }}>
            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $size === 'sm' ? 'h-5 w-5' : 'h-7 w-7' }} text-blue-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.277A1 1 0 0121 8.68v6.638a1 1 0 01-1.447.894L15 14M4 6h8a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2z" />
            </svg>
        </div>
    @else
        {{-- Unknown — plain image placeholder, do not render as <img> --}}
        <div {{ $attributes->merge(['class' => "{$sizeClasses} flex items-center justify-center rounded-lg border border-dashed border-[#B49C6E]/30 bg-[#EAEAE9]/20"]) }}>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#B49C6E]/60" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
            </svg>
        </div>
    @endif
@else
    {{-- No URL at all — empty placeholder --}}
    <div {{ $attributes->merge(['class' => "{$sizeClasses} flex items-center justify-center rounded-lg border border-dashed border-[#B49C6E]/30 bg-[#EAEAE9]/20"]) }}>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#B49C6E]/60" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
        </svg>
    </div>
@endif
