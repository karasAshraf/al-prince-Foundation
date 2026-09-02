<x-frontend-layout title="{{ __('frontend.activities') }}">

    <div x-data="{
        open: false,
        activity: { title: '', desc: '', img: '', gallery: [] },
        openModal(data) {
            this.activity = data;
            this.open = true;
            document.body.style.overflow = 'hidden';
        },
        closeModal() {
            this.open = false;
            document.body.style.overflow = '';
        }
    }">

        {{-- ═══════════════════════════════════════════════════════════
             TWO-TONE SECTION HEADING
        ═══════════════════════════════════════════════════════════ --}}
        <div class="text-center mb-12 space-y-3">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-secondary/60 text-primary text-xs font-semibold tracking-widest uppercase">
                @if(app()->getLocale() === 'ar')
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    {{ __('frontend.our_activities') }}
                @else
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    {{ __('frontend.our_activities') }}
                @endif
            </span>

          

           

            {{-- Decorative underline bar --}}
            <div class="flex items-center justify-center gap-2 pt-1">
                <span class="h-px w-12 bg-secondary/40 rounded-full"></span>
                <span class="w-2 h-2 rounded-full bg-primary"></span>
                <span class="h-px w-24 bg-primary/60 rounded-full"></span>
                <span class="w-2 h-2 rounded-full bg-primary"></span>
                <span class="h-px w-12 bg-secondary/40 rounded-full"></span>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════
             ACTIVITIES GRID — Service Catalog concept
        ═══════════════════════════════════════════════════════════ --}}
        @if ($activities->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-16"
                 x-data="{ inView: false }"
                 x-intersect.once="inView = true">

                @foreach ($activities as $index => $item)
                    @php
                        $locale     = app()->getLocale();
                        $title      = $locale === 'ar' ? ($item->title_ar ?: '') : ($item->title_en ?: $item->title_ar);
                        $desc       = $locale === 'ar' ? ($item->description_ar ?: '') : ($item->description_en ?: $item->description_ar);
                        $img        = \App\Helpers\MediaHelper::url($item, 'featured_image', 'image', 'card');
                        $detailImg  = \App\Helpers\MediaHelper::url($item, 'featured_image', 'image', 'detail');
                        $thumbImg   = \App\Helpers\MediaHelper::url($item, 'featured_image', 'image', 'thumb');

                        // Fallback SVG placeholder
                        $svgPlaceholder = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='150' height='150' viewBox='0 0 150 150'><rect width='100%' height='100%' fill='%23EAEAE9'/><circle cx='75' cy='58' r='22' fill='%23B49C6E' opacity='.5'/><rect x='45' y='88' width='60' height='8' rx='4' fill='%23A38B54' opacity='.3'/><rect x='55' y='102' width='40' height='6' rx='3' fill='%23A38B54' opacity='.2'/></svg>";

                        $badgeImg    = $thumbImg ?: $img ?: $svgPlaceholder;
                        $displayImg  = $detailImg ?: $img ?: $svgPlaceholder;

                        $gallery = $item->getMedia('gallery')->map(function($mediaItem) {
                            return [
                                'url'   => $mediaItem->getUrl(),
                                'thumb' => $mediaItem->hasGeneratedConversion('gallery_thumb') ? $mediaItem->getUrl('gallery_thumb') : $mediaItem->getUrl(),
                                'name'  => $mediaItem->name
                            ];
                        })->toArray();
                    @endphp

                    {{-- Outer wrapper: provides the -mt-10 space for the overlapping badge --}}
                    <div class="relative mt-10"
                         :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                         style="transition: opacity 700ms ease, transform 700ms ease; transition-delay: {{ $index * 100 }}ms;">

                        {{-- ── Floating Circular Badge ── --}}
                        <div class="absolute -top-10 start-1/2 -translate-x-1/2 z-10 w-20 h-20 rounded-full border-4 border-background shadow-lg overflow-hidden ring-2 ring-primary-light/30 transition-transform duration-300 group-hover:scale-110">
                            <img src="{{ $badgeImg }}"
                                 alt="{{ $title }}"
                                 loading="lazy"
                                 class="w-full h-full object-cover">
                        </div>

                        {{-- ── Card Body ── --}}
                        <article class="group rounded-3xl bg-background border border-secondary/10 shadow-sm
                                        hover:shadow-lg hover:-translate-y-1
                                        transition-all duration-300 overflow-hidden flex flex-col h-full">

                            {{-- Zone 1: Title area (pt-14 to clear the badge) --}}
                            <div class="px-6 pt-14 pb-4 text-center">
                                <button type="button"
                                        @click="openModal({
                                            title: '{{ e($title) }}',
                                            desc: `{{ e($desc) }}`,
                                            img: '{{ $displayImg }}',
                                            gallery: {{ json_encode($gallery) }}
                                        })"
                                        class="group/title focus:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded-lg">
                                    <h3 class="text-lg font-bold text-text-primary leading-snug line-clamp-2
                                                group-hover/title:text-primary transition-colors duration-200">
                                        {{ $title }}
                                    </h3>
                                </button>
                            </div>

                            {{-- Thin divider --}}
                            <div class="mx-6 border-t border-secondary/15"></div>

                            {{-- Zone 2: Description + CTA (card "footer") --}}
                            <div class="px-6 pt-4 pb-6 flex flex-col flex-1 gap-4">
                                @if ($desc)
                                    <p class="text-sm text-text-primary/70 leading-relaxed line-clamp-3 flex-1">
                                        {{ $desc }}
                                    </p>
                                @else
                                    <div class="flex-1"></div>
                                @endif

                                <x-frontend.button
                                    type="button"
                                    @click="openModal({
                                        title: '{{ e($title) }}',
                                        desc: `{{ e($desc) }}`,
                                        img: '{{ $displayImg }}',
                                        gallery: {{ json_encode($gallery) }}
                                    })"
                                    variant="outline"
                                    size="sm"
                                    class="w-full justify-center">
                                    {{ __('frontend.activity_details') }}
                                </x-frontend.button>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>

            <div class="mt-16">
                <x-frontend.pagination :paginator="$activities" />
            </div>

        @else
            <x-frontend.empty-state
                :title="__('frontend.no_activities_available')"
                :description="__('frontend.activities_coming_soon')"
            />
        @endif

        {{-- ═══════════════════════════════════════════════════════════
             ACTIVITY DETAILS MODAL (unchanged)
        ═══════════════════════════════════════════════════════════ --}}
        <div x-show="open"
             class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;"
             x-cloak>
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"
                 x-show="open"
                 x-transition.opacity
                 @click="closeModal()"></div>

            {{-- Modal Wrapper --}}
            <div class="flex min-h-screen items-center justify-center p-4 sm:p-6 md:p-10">
                <div class="relative bg-background dark:bg-gray-800 border border-secondary/20 rounded-3xl w-full max-w-4xl shadow-2xl overflow-hidden transition-all duration-300 transform"
                     x-show="open"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                     @click.outside="closeModal()">

                    {{-- Close Button --}}
                    <button @click="closeModal()"
                            class="absolute top-4 end-4 z-10 p-2 rounded-full bg-black/10 hover:bg-black/20 text-text-primary dark:text-background transition-colors"
                            aria-label="{{ app()->getLocale() === 'ar' ? 'إغلاق' : 'Close' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    {{-- Modal Content --}}
                    <div class="p-6 sm:p-10 space-y-6">
                        <template x-if="activity.img">
                            <div class="overflow-hidden rounded-2xl aspect-video">
                                <img :src="activity.img" :alt="activity.title" class="w-full h-full object-cover">
                            </div>
                        </template>

                        <div class="space-y-4">
                            <h2 class="text-2xl sm:text-3xl font-bold text-text-primary dark:text-background" x-text="activity.title"></h2>
                            <p class="text-base text-text-primary/85 dark:text-gray-200 leading-relaxed whitespace-pre-line pt-4 border-t border-secondary/20" x-text="activity.desc"></p>
                        </div>

                        {{-- Gallery --}}
                        <template x-if="activity.gallery && activity.gallery.length > 0">
                            <div class="pt-6 border-t border-secondary/20">
                                <h3 class="text-lg font-bold text-text-primary dark:text-background mb-4">{{ __('frontend.activity_gallery') }}</h3>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                    <template x-for="mediaItem in activity.gallery" :key="mediaItem.url">
                                        <a :href="mediaItem.url" target="_blank" class="block aspect-square overflow-hidden rounded-xl border border-background dark:border-gray-700 shadow-sm group">
                                            <img :src="mediaItem.thumb" :alt="mediaItem.name" class="w-full h-full object-cover transition duration-300 group-hover:scale-105">
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-frontend-layout>
