<x-frontend-layout title="{{ __('frontend.media_library') }}">

{{-- ===================================================================
     FULL-MEDIA PREVIEW MODAL — Alpine.js
     Receives all media items for a selected library record.
     No extra AJAX: data is JSON-encoded from already eager-loaded media.
==================================================================== --}}
<div x-data="mediaPreviewModal()" class="w-full">

    {{-- Modal Overlay --}}
    <div x-show="open"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="close()"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         role="dialog" aria-modal="true" aria-labelledby="modal-title">

        {{-- Modal Panel --}}
        <div x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             @click.outside="close()"
             class="relative w-full max-w-4xl max-h-[90vh] overflow-y-auto bg-background dark:bg-gray-900 rounded-2xl shadow-2xl border border-secondary/20 dark:border-gray-700">

            {{-- Modal Header --}}
            <div class="sticky top-0 z-10 flex items-center justify-between gap-4 px-6 py-4 bg-background/95 dark:bg-gray-900/95 backdrop-blur-sm border-b border-secondary/10 dark:border-gray-700/50">
                <div class="flex-1 min-w-0">
                    <h2 id="modal-title" class="text-lg font-bold text-text-primary dark:text-background truncate" x-text="title"></h2>
                    <p class="text-xs text-text-primary/50 dark:text-gray-400 mt-0.5">
                        <span x-text="totalCount"></span>
                        <span>{{ app()->getLocale() === 'ar' ? ' وسيط/ملف مرفق' : ' attached media items' }}</span>
                    </p>
                </div>
                <button @click="close()" type="button" class="shrink-0 w-9 h-9 rounded-xl flex items-center justify-center text-text-primary/60 hover:text-text-primary hover:bg-secondary/50 dark:hover:bg-gray-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Modal Content --}}
            <div class="p-6 space-y-8">

                {{-- Empty State --}}
                <template x-if="totalCount === 0">
                    <div class="py-16 flex flex-col items-center gap-3 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-secondary/30 flex items-center justify-center text-primary/60">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <p class="text-text-primary/60 dark:text-gray-400 text-sm font-medium">{{ __('frontend.no_media_available') }}</p>
                    </div>
                </template>

                {{-- Images --}}
                <template x-if="images.length > 0">
                    <div>
                        <h3 class="text-sm font-bold text-text-primary/70 dark:text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ app()->getLocale() === 'ar' ? 'الصور' : 'Images' }}
                            <span class="font-normal normal-case tracking-normal text-xs text-text-primary/40" x-text="'(' + images.length + ')'"></span>
                        </h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <template x-for="(img, idx) in images" :key="idx">
                                <a :href="img.url" target="_blank" rel="noopener noreferrer" class="group relative aspect-video overflow-hidden rounded-xl border border-secondary/15 dark:border-gray-700 bg-background dark:bg-gray-800 hover:border-primary/40 hover:shadow-lg transition-all duration-200">
                                    <img :src="img.url" :alt="img.name" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center opacity-0 group-hover:opacity-100">
                                        <svg class="w-6 h-6 text-background drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- Videos --}}
                <template x-if="videos.length > 0">
                    <div>
                        <h3 class="text-sm font-bold text-text-primary/70 dark:text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ app()->getLocale() === 'ar' ? 'الفيديوهات' : 'Videos' }}
                            <span class="font-normal normal-case tracking-normal text-xs text-text-primary/40" x-text="'(' + videos.length + ')'"></span>
                        </h3>
                        <div class="space-y-4">
                            <template x-for="(vid, idx) in videos" :key="idx">
                                <div class="rounded-xl overflow-hidden border border-secondary/15 dark:border-gray-700 bg-black">
                                    <template x-if="vid.ytId">
                                        <div class="aspect-video">
                                            <iframe :src="'https://www.youtube-nocookie.com/embed/' + vid.ytId" class="w-full h-full" allowfullscreen loading="lazy" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                                        </div>
                                    </template>
                                    <template x-if="!vid.ytId">
                                        <div class="aspect-video"><video :src="vid.url" controls preload="metadata" class="w-full h-full"></video></div>
                                    </template>
                                    <div class="px-4 py-2.5 bg-gray-900/80 flex items-center justify-between gap-2">
                                        <span class="text-xs text-gray-300 truncate" x-text="vid.name"></span>
                                        <a :href="vid.url" target="_blank" rel="noopener noreferrer" class="shrink-0 inline-flex items-center gap-1 text-xs text-secondary hover:text-secondary transition-colors font-medium">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            {{ app()->getLocale() === 'ar' ? 'فتح' : 'Open' }}
                                        </a>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- PDFs --}}
                <template x-if="pdfs.length > 0">
                    <div>
                        <h3 class="text-sm font-bold text-text-primary/70 dark:text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            {{ app()->getLocale() === 'ar' ? 'ملفات PDF' : 'PDF Documents' }}
                            <span class="font-normal normal-case tracking-normal text-xs text-text-primary/40" x-text="'(' + pdfs.length + ')'"></span>
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <template x-for="(pdf, idx) in pdfs" :key="idx">
                                <div class="flex items-center gap-3 p-4 rounded-xl border border-red-200/60 dark:border-red-900/40 bg-red-50/50 dark:bg-red-950/20">
                                    <div class="shrink-0 w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/40 flex items-center justify-center text-red-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-text-primary dark:text-background truncate" x-text="pdf.name"></p>
                                        <span class="text-xs font-bold text-red-500 uppercase tracking-wider">PDF</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <a :href="pdf.url" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors">{{ app()->getLocale() === 'ar' ? 'عرض' : 'View' }}</a>
                                        <a :href="pdf.url" :download="pdf.name" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-primary text-background hover:bg-primary/90 transition-colors">{{ app()->getLocale() === 'ar' ? 'تحميل' : 'Download' }}</a>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- Documents --}}
                <template x-if="documents.length > 0">
                    <div>
                        <h3 class="text-sm font-bold text-text-primary/70 dark:text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            {{ app()->getLocale() === 'ar' ? 'المستندات' : 'Documents' }}
                            <span class="font-normal normal-case tracking-normal text-xs text-text-primary/40" x-text="'(' + documents.length + ')'"></span>
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <template x-for="(doc, idx) in documents" :key="idx">
                                <div class="flex items-center gap-3 p-4 rounded-xl border border-blue-200/60 dark:border-blue-900/40 bg-blue-50/50 dark:bg-blue-950/20">
                                    <div class="shrink-0 w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-text-primary dark:text-background truncate" x-text="doc.name"></p>
                                        <span class="text-xs font-bold text-blue-500 uppercase tracking-wider" x-text="doc.ext || 'DOC'"></span>
                                    </div>
                                    <a :href="doc.url" :download="doc.name" class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-primary text-background hover:bg-primary/90 transition-colors">{{ app()->getLocale() === 'ar' ? 'تحميل' : 'Download' }}</a>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- External Links --}}
                <template x-if="links.length > 0">
                    <div>
                        <h3 class="text-sm font-bold text-text-primary/70 dark:text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            {{ app()->getLocale() === 'ar' ? 'الروابط الخارجية' : 'External Links' }}
                            <span class="font-normal normal-case tracking-normal text-xs text-text-primary/40" x-text="'(' + links.length + ')'"></span>
                        </h3>
                        <div class="space-y-2">
                            <template x-for="(link, idx) in links" :key="idx">
                                <a :href="link.url" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 p-4 rounded-xl border border-secondary/20 dark:border-gray-700 bg-background dark:bg-gray-800 hover:border-primary/40 hover:shadow-md group transition-all duration-200">
                                    <div class="shrink-0 w-10 h-10 rounded-lg bg-secondary/50 dark:bg-gray-700 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-background transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-text-primary dark:text-background group-hover:text-primary dark:group-hover:text-secondary transition-colors truncate" x-text="link.label"></p>
                                        <p class="text-xs text-text-primary/50 dark:text-gray-500 truncate" x-text="link.url"></p>
                                    </div>
                                    <svg class="w-4 h-4 text-text-primary/30 group-hover:text-primary shrink-0 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </template>
                        </div>
                    </div>
                </template>

            </div>{{-- /modal content --}}
        </div>{{-- /modal panel --}}
    </div>{{-- /modal overlay --}}

    {{-- ============================================================
         HERO HEADER
         ============================================================ --}}
  

    {{-- ============================================================
         MAIN CONTAINER
         ============================================================ --}}
    <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
        {{-- Search & Filter Bar --}}
        <div class="mb-10">
            @php
                $formats = [
                    'pdf' => app()->getLocale() === 'ar' ? 'ملفات PDF' : 'PDF Documents',
                    'image' => app()->getLocale() === 'ar' ? 'الصور' : 'Images',
                    'video' => app()->getLocale() === 'ar' ? 'الفيديوهات' : 'Videos',
                    'document' => app()->getLocale() === 'ar' ? 'المستندات' : 'Documents',
                    'external' => app()->getLocale() === 'ar' ? 'روابط خارجية' : 'External Links',
                ];
            @endphp
            <form method="GET" action="{{ route('media-library.index') }}" 
                  class="w-full flex flex-col md:flex-row items-stretch md:items-center gap-4 bg-background dark:bg-gray-800 p-4 rounded-xl border border-secondary/15 dark:border-gray-700 shadow-sm">
                
                    {{-- Search text field --}}
                <div class="relative flex-[2]">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="{{ __('frontend.search_media') }}"
                           @input.debounce.500ms="$el.form.requestSubmit()"
                           class="w-full ps-10 pe-4 py-2.5 text-sm rounded-lg border border-gray-200
                                  dark:border-gray-700 bg-background dark:bg-gray-900 focus:outline-none
                                  focus:border-primary focus:ring-2 focus:ring-primary/20
                                  hover:border-primary/40 transition-colors duration-150
                                  dark:text-gray-200">
                    <span class="absolute inset-y-0 start-3 flex items-center text-gray-400 pointer-events-none">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                </div>

                {{-- Category Select --}}
                <div class="relative flex-1">
                    <select name="category" @change="$el.form.requestSubmit()"
                            class="w-full ps-4 pe-10 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-background dark:bg-gray-900 text-sm text-text-primary dark:text-gray-200 font-medium focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 hover:border-primary/40 transition-colors duration-150 appearance-none cursor-pointer shadow-sm">
                        <option value="all" {{ request('category') === 'all' || !request('category') ? 'selected' : '' }}>{{ __('frontend.all_categories') }}</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 end-3 flex items-center pointer-events-none text-text-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- Format Select --}}
                <div class="relative flex-1">
                    <select name="format" @change="$el.form.requestSubmit()"
                            class="w-full ps-4 pe-10 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-background dark:bg-gray-900 text-sm text-text-primary dark:text-gray-200 font-medium focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 hover:border-primary/40 transition-colors duration-150 appearance-none cursor-pointer shadow-sm">
                        <option value="all" {{ request('format') === 'all' || !request('format') ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'جميع التنسيقات' : 'All Formats' }}</option>
                        {{-- Show only 'external' and 'pdf' — the only formats with actual data in the DB.
                             image/video/document options are hidden until media of those types is uploaded.
                             Each available option still shows an empty-state when no results match. --}}
                        <option value="pdf" {{ request('format') === 'pdf' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'ملفات PDF' : 'PDF Documents' }}</option>
                        <option value="image" {{ request('format') === 'image' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'الصور' : 'Images' }}</option>
                        <option value="video" {{ request('format') === 'video' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'الفيديوهات' : 'Videos' }}</option>
                        <option value="document" {{ request('format') === 'document' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'المستندات' : 'Documents' }}</option>
                        <option value="external" {{ request('format') === 'external' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'روابط خارجية' : 'External Links' }}</option>
                    </select>
                    <div class="absolute inset-y-0 end-3 flex items-center pointer-events-none text-text-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

            </form>
        </div>

        @if ($items->count() > 0)
            
            {{-- Carousel Section (Feature Highlight) --}}
            <div class="mb-14" x-data="{
                atStart: true,
                atEnd: false,
                checkScroll() {
                    const track = this.$refs.track;
                    if (!track) return;
                    const scrollLeft = Math.abs(track.scrollLeft);
                    const scrollWidth = track.scrollWidth;
                    const clientWidth = track.clientWidth;
                    this.atStart = scrollLeft < 5;
                    this.atEnd = (scrollLeft + clientWidth) >= (scrollWidth - 5);
                },
                scrollByCard(dir) {
                    const track = this.$refs.track;
                    const card = track.querySelector('[data-card]');
                    if (!card) return;
                    const width = card.offsetWidth + 16;
                    const isRtl = document.documentElement.dir === 'rtl' || document.documentElement.classList.contains('rtl');
                    const directionMultiplier = isRtl ? -1 : 1;
                    track.scrollBy({ left: dir * width * directionMultiplier, behavior: 'smooth' });
                }
            }" x-init="$nextTick(() => { checkScroll(); })" @resize.window.debounce.100ms="checkScroll()">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-text-primary dark:text-background flex items-center gap-2">
                        <span class="w-2.5 h-6 bg-primary rounded-full"></span>
                        {{ app()->getLocale() === 'ar' ? 'أحدث الإضافات' : 'Featured Resources' }}
                    </h2>
                    
                    {{-- Carousel arrows --}}
                    <div class="flex items-center gap-2">
                        <button @click="scrollByCard(-1)" type="button" 
                                :disabled="atStart"
                                :class="atStart ? 'opacity-40 pointer-events-none' : ''"
                                class="w-9 h-9 rounded-full bg-background dark:bg-gray-800 shadow-sm border border-secondary/20 flex items-center justify-center text-primary hover:bg-primary hover:text-background transition-all" 
                                aria-label="Previous items">
                            <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button @click="scrollByCard(1)" type="button" 
                                :disabled="atEnd"
                                :class="atEnd ? 'opacity-40 pointer-events-none' : ''"
                                class="w-9 h-9 rounded-full bg-background dark:bg-gray-800 shadow-sm border border-secondary/20 flex items-center justify-center text-primary hover:bg-primary hover:text-background transition-all" 
                                aria-label="Next items">
                            <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Horizontal scroll snap row --}}
                <div x-ref="track" @scroll.passive="checkScroll()" class="flex gap-4 overflow-x-auto snap-x snap-mandatory py-4 px-1 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden scroll-smooth">
                    @foreach ($items as $item)
                        <div data-card class="shrink-0 w-[85%] sm:w-[45%] lg:w-[30%] snap-start snap-center">
                            <x-media-card :item="$item" />
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Full Catalog Grid --}}
            <div class="space-y-6">
                <h2 class="text-xl font-bold text-text-primary dark:text-background flex items-center gap-2">
                    <span class="w-2.5 h-6 bg-primary rounded-full"></span>
                    {{ app()->getLocale() === 'ar' ? 'دليل الملفات الكامل' : 'All Resources' }}
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($items as $item)
                        <x-media-card :item="$item" />
                    @endforeach
                </div>

                {{-- Pagination Links --}}
                <div class="pt-6">
                    <x-frontend.pagination :paginator="$items" />
                </div>
            </div>

        @else
            {{-- Empty State --}}
            <div class="py-20 text-center flex flex-col items-center justify-center bg-background dark:bg-gray-800 rounded-xl border border-secondary/15 dark:border-gray-700 shadow-sm">
                <div class="w-16 h-16 rounded-full bg-secondary/40 flex items-center justify-center text-primary mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 8.75h18.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-text-primary dark:text-background mb-1">
                    {{ app()->getLocale() === 'ar' ? 'لا توجد نتائج مطابقة' : 'No matching results' }}
                </h3>
                <p class="text-sm text-text-primary dark:text-gray-400 max-w-xs">
                    {{ app()->getLocale() === 'ar' ? 'جرب البحث بكلمات أخرى أو تغيير خيارات التصفية' : 'Try searching with other keywords or modifying your filter choices' }}
                </p>
            </div>
        @endif

    </div>{{-- /container --}}

</div>{{-- /Alpine mediaPreviewModal wrapper --}}

<script>
function mediaPreviewModal() {
    return {
        open: false,
        title: '',
        images: [],
        videos: [],
        pdfs: [],
        documents: [],
        links: [],
        get totalCount() {
            return this.images.length + this.videos.length + this.pdfs.length + this.documents.length + this.links.length;
        },
        openModal(payload) {
            this.title     = payload.title     || '';
            this.images    = payload.images    || [];
            this.videos    = payload.videos    || [];
            this.pdfs      = payload.pdfs      || [];
            this.documents = payload.documents || [];
            this.links     = payload.links     || [];
            this.open = true;
            document.body.style.overflow = 'hidden';
        },
        close() {
            this.open = false;
            document.body.style.overflow = '';
        },
    };
}
</script>

</x-frontend-layout>
