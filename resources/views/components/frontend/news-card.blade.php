@props([
    'news',
])

@php
    $locale = app()->getLocale();
    $title = $locale === 'ar' ? ($news->title_ar ?: '') : ($news->title_en ?: $news->title_ar ?: '');
    $excerpt = $locale === 'ar' ? ($news->excerpt_ar ?: '') : ($news->excerpt_en ?: $news->excerpt_ar ?: '');
    $img = \App\Helpers\MediaHelper::url($news, 'news_images', 'image', 'card');
    
    // Custom brand-aligned fallback image using our approved palette (Gold #AC8321 / Warm Gold #B8974F)
    $displayImg = $img ?: "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='800' height='450' viewBox='0 0 800 450'><rect width='100%' height='100%' fill='%23EAEAE9'/><circle cx='400' cy='180' r='60' fill='%23B8974F' opacity='.4'/><rect x='280' y='260' width='240' height='12' rx='6' fill='%23AC8321' opacity='.25'/><rect x='320' y='284' width='160' height='8' rx='4' fill='%23AC8321' opacity='.15'/></svg>";
    $articleUrl = route('news.show', $news->slug);
    $pubDate = $news->published_at ?? $news->created_at;
@endphp

<article class="flex flex-col h-full bg-background dark:bg-gray-800 border border-secondary rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1 group">
    {{-- Image aspect ratio locked to 16:9 --}}
    <div class="relative overflow-hidden shrink-0 aspect-[16/9] bg-gray-150">
        <a href="{{ $articleUrl }}" class="block w-full h-full">
            <img src="{{ $displayImg }}" alt="{{ $title }}" loading="lazy"
                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        </a>
    </div>

    {{-- Content Area --}}
    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
        <div class="space-y-3">
            <span class="text-xs font-semibold text-primary">
                {{ $pubDate?->translatedFormat('d M Y') }}
            </span>
            <h3 class="text-lg font-bold text-text-primary dark:text-background line-clamp-2 leading-snug">
                <a href="{{ $articleUrl }}" class="hover:text-primary transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded-md">
                    {{ $title }}
                </a>
            </h3>
            @if ($excerpt)
                <p class="text-sm text-text-primary dark:text-gray-300 leading-relaxed line-clamp-2">
                    {{ $excerpt }}
                </p>
            @endif
        </div>

        <div class="pt-4 border-t border-background dark:border-gray-700/50 mt-auto">
            <a href="{{ $articleUrl }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:text-secondary transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded-md">
                <span>{{ __('frontend.read_more') }}</span>
                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</article>
