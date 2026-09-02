@props([
    'item',
])

@php
    $locale = app()->getLocale();
    $title = $locale === 'ar' ? ($item->title_ar ?: '') : ($item->title_en ?: $item->title_ar);
    $desc = $locale === 'ar' ? ($item->description_ar ?: '') : ($item->description_en ?: $item->description_ar);

    $mediaFiles = $item->getMedia('media_library_files');
    $links = [];
    if ($item->external_link) {
        $decoded = json_decode($item->external_link, true);
        $links = is_array($decoded) ? $decoded : [$item->external_link];
    }

    $totalAttached = count($mediaFiles) + count($links);

    // Build modal payload (categorised by type)
    $modalImages   = [];
    $modalVideos   = [];
    $modalPdfs     = [];
    $modalDocs     = [];
    $modalLinks    = [];

    foreach ($mediaFiles as $mf) {
        $mime = $mf->mime_type ?? '';
        $url  = $mf->getUrl();
        $name = $mf->file_name;
        $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        if (str_starts_with($mime, 'image/')) {
            $modalImages[] = ['url' => $url, 'name' => $name];
        } elseif (str_starts_with($mime, 'video/') || in_array($ext, ['mp4','webm','ogg','mov'])) {
            $modalVideos[] = ['url' => $url, 'name' => $name, 'ytId' => null];
        } elseif ($mime === 'application/pdf' || $ext === 'pdf') {
            $modalPdfs[] = ['url' => $url, 'name' => $name];
        } else {
            $modalDocs[] = ['url' => $url, 'name' => $name, 'ext' => strtoupper($ext) ?: 'FILE'];
        }
    }

    foreach ($links as $link) {
        $ytId = null;
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $link, $m)) {
            $ytId = $m[1];
        }
        $path = strtolower(parse_url($link, PHP_URL_PATH) ?? '');

        if ($ytId || preg_match('/\.(mp4|webm|ogg)$/', $path)) {
            $modalVideos[] = ['url' => $link, 'name' => parse_url($link, PHP_URL_HOST) ?: $link, 'ytId' => $ytId];
        } elseif (preg_match('/\.(jpg|jpeg|png|webp|gif|svg)$/', $path)) {
            $modalImages[] = ['url' => $link, 'name' => basename($path)];
        } elseif (preg_match('/\.pdf$/', $path)) {
            $modalPdfs[] = ['url' => $link, 'name' => basename($path) ?: $link];
        } else {
            $modalLinks[] = ['url' => $link, 'label' => parse_url($link, PHP_URL_HOST) ?: $link];
        }
    }

    $modalPayload = [
        'title'     => $title,
        'images'    => $modalImages,
        'videos'    => $modalVideos,
        'pdfs'      => $modalPdfs,
        'documents' => $modalDocs,
        'links'     => $modalLinks,
    ];

    // Determine primary file/link type for format description badge
    $type = 'external_url';
    $primaryUrl = null;
    $primaryName = null;
    $fileSize = null;

    if ($mediaFiles->isNotEmpty()) {
        $firstMedia = $mediaFiles->first();
        $mime = $firstMedia->mime_type;
        $primaryUrl  = $firstMedia->getUrl();
        $primaryName = $firstMedia->file_name;
        $fileSize = $firstMedia->human_readable_size;

        if (str_starts_with($mime, 'image/'))          { $type = 'image'; }
        elseif (str_starts_with($mime, 'video/'))       { $type = 'video'; }
        elseif ($mime === 'application/pdf' || str_ends_with(strtolower($firstMedia->file_name), '.pdf')) { $type = 'pdf'; }
        else                                             { $type = 'document'; }
    } elseif (!empty($links)) {
        $firstLink   = $links[0];
        $primaryUrl  = $firstLink;
        $primaryName = parse_url($firstLink, PHP_URL_HOST) ?: $firstLink;

        if (str_contains($firstLink, 'youtube.com') || str_contains($firstLink, 'youtu.be') || str_contains($firstLink, 'vimeo.com') || preg_match('/\.(mp4|webm|ogg)$/i', parse_url($firstLink, PHP_URL_PATH) ?? '')) {
            $type = 'video';
        } elseif (preg_match('/\.(jpg|jpeg|png|webp|gif|svg)$/i', parse_url($firstLink, PHP_URL_PATH) ?? '')) {
            $type = 'image';
        } elseif (str_ends_with(strtolower(parse_url($firstLink, PHP_URL_PATH) ?? ''), '.pdf')) {
            $type = 'pdf';
        } else {
            $type = 'external_url';
        }
    }

    // Determine thumbnail visual image
    $thumbnailUrl = null;

    // 1. Prioritize any image file in Spatie Media Library
    $imageMedia = $mediaFiles->first(fn($m) => str_starts_with($m->mime_type ?? '', 'image/'));
    if ($imageMedia) {
        $thumbnailUrl = $imageMedia->getUrl();
    }

    // 2. If no Spatie image, check external link images
    if (!$thumbnailUrl) {
        foreach ($links as $link) {
            $path = strtolower(parse_url($link, PHP_URL_PATH) ?? '');
            if (preg_match('/\.(jpg|jpeg|png|webp|gif|svg)$/', $path)) {
                $thumbnailUrl = $link;
                break;
            }
        }
    }

    // 3. Check YouTube video thumbnail if still no image
    if (!$thumbnailUrl) {
        $youtubeVideo = null;
        foreach ($modalVideos as $mv) {
            if (!empty($mv['ytId'])) {
                $youtubeVideo = $mv;
                break;
            }
        }
        if ($youtubeVideo) {
            $thumbnailUrl = "https://img.youtube.com/vi/{$youtubeVideo['ytId']}/hqdefault.jpg";
        }
    }
@endphp

<div class="group flex flex-col justify-between h-full bg-background dark:bg-gray-800 rounded-xl border border-secondary/15 hover:border-primary/30 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
    
    <!-- Thumbnail/Icon Top Area (Fixed Aspect Ratio) -->
    <div class="relative w-full aspect-video flex items-center justify-center bg-secondary border-b border-secondary/10 overflow-hidden shrink-0">
        
        <!-- Category Badge: Absolute Top-Start (Consistent Gold styling site-wide) -->
        <span class="absolute top-3 start-3 z-10 px-2.5 py-1 rounded-full text-xs font-bold shadow-sm tracking-wide bg-secondary text-background">
            {{ __('frontend.media_' . $item->category) }}
        </span>

        @if ($thumbnailUrl)
            <!-- Prioritized Image Preview -->
            <img src="{{ $thumbnailUrl }}" alt="{{ $title }}" class="w-full h-full object-cover transition-transform group-hover:scale-105 duration-300">
            @if ($type === 'video')
                <!-- Play overlay icon for videos -->
                <div class="absolute inset-0 flex items-center justify-center bg-black/10">
                    <div class="w-12 h-12 rounded-full bg-background/95 dark:bg-gray-900/95 flex items-center justify-center text-primary shadow-md group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                </div>
            @endif
        @else
            <!-- Standardized Fallback SVG Icon (Document/File icon in Gold #AC8321 on Warm Gray #D5D3CE) -->
            <svg class="w-16 h-16 text-secondary transition-transform group-hover:scale-110 duration-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
            </svg>
        @endif

        @if ($totalAttached > 1)
            <div class="absolute bottom-3 end-3 z-10">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-primary text-background text-[10px] font-bold shadow-sm">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    {{ $totalAttached }}
                </span>
            </div>
        @endif
    </div>

    <!-- Body Information -->
    <div class="p-5 flex-1 flex flex-col justify-between">
        <div class="space-y-2">
            <!-- Format type & File size info line -->
            <div class="flex items-center justify-between text-xs text-text-primary dark:text-gray-400 font-medium">
                <span class="uppercase tracking-wider">
                    {{ __('frontend.' . $type) }}
                </span>
                @if ($fileSize)
                    <span>{{ $fileSize }}</span>
                @endif
            </div>

            <!-- Title: Consistent Line Clamp & Color -->
            <h3 class="text-base font-bold text-text-primary dark:text-background line-clamp-2 leading-snug group-hover:text-primary transition-colors">
                {{ $title }}
            </h3>

            <!-- Short Description: Consistent Line Clamp (Maintains height when empty) -->
            @if ($desc)
                <p class="text-xs text-text-primary dark:text-gray-300 leading-relaxed line-clamp-2">
                    {{ $desc }}
                </p>
            @else
                <p class="text-xs text-transparent select-none leading-relaxed line-clamp-2">&nbsp;<br>&nbsp;</p>
            @endif
        </div>

        <!-- Action / Download / View Buttons: Pinned to bottom -->
        <div class="mt-auto pt-3 border-t border-secondary/10 flex flex-col gap-2">
            @if ($totalAttached > 0)
                <button type="button"
                        @click="openModal({{ json_encode($modalPayload, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) }})"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold text-primary bg-primary/10 hover:bg-primary hover:text-background border border-primary/20 hover:border-primary transition-all duration-200"
                        aria-label="{{ __('frontend.preview_media') }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span>{{ __('frontend.preview_media') }}</span>
                    @if ($totalAttached > 1)
                        <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-primary/20 text-[9px] font-bold">{{ $totalAttached }}</span>
                    @endif
                </button>
            @endif

            @if ($mediaFiles->isNotEmpty() && $totalAttached === 1 && $type !== 'image')
                <x-frontend.button :href="$primaryUrl" download variant="outline" size="sm" class="w-full justify-center gap-1.5" aria-label="{{ __('frontend.download') }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>{{ __('frontend.download') }}</span>
                </x-frontend.button>
            @endif
        </div>
    </div>
</div>
