<x-frontend-layout title="{{ __('frontend.governance_and_transparency') }}">

    @php
        $categoryNames = [
            \App\Models\GovernanceDocument::CATEGORY_POLICIES    => __('frontend.regulations_and_policies'),
            \App\Models\GovernanceDocument::CATEGORY_FINANCIAL   => __('frontend.financial_reports'),
            \App\Models\GovernanceDocument::CATEGORY_ACHIEVEMENT => __('frontend.impact_reports'),
        ];

        $availableYears = $availableYears ?? \App\Models\GovernanceDocument::availableYears();

        // Query active document titles for clean autocomplete list
        $allTitles = \App\Models\GovernanceDocument::active()
            ->get()
            ->map(fn($d) => app()->getLocale() === 'ar' ? $d->title_ar : ($d->title_en ?? $d->title_ar))
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    @endphp

    <!-- Search & Filter Bar -->
    <div class="max-w-5xl mx-auto -mt-2 sm:-mt-3 md:-mt-4 relative z-10 mb-8 bg-white p-2 rounded-xl sm:rounded-full border border-primary-light/20 shadow-sm hover:shadow-md transition-shadow duration-200 focus-within:ring-2 focus-within:ring-primary/20">
        <form method="GET" action="{{ route('governance.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
            
            {{-- Search Input with Custom Autocomplete Suggestions --}}
            <div class="relative flex-1"
                 x-data="{
                     searchQuery: '{{ request('search') }}',
                     showSuggestions: false,
                     suggestions: @js($allTitles),
                     get filteredSuggestions() {
                         if (!this.searchQuery) return this.suggestions.slice(0, 6);
                         const query = this.searchQuery.toLowerCase();
                         return this.suggestions.filter(s => s.toLowerCase().includes(query)).slice(0, 6);
                     },
                     selectSuggestion(val) {
                         this.searchQuery = val;
                         this.showSuggestions = false;
                         this.$nextTick(() => { $el.closest('form').submit(); });
                     }
                 }"
                 @click.outside="showSuggestions = false">
                <input
                    type="text"
                    name="search"
                    x-model="searchQuery"
                    @focus="showSuggestions = true"
                    @input.debounce.500ms="$el.form.requestSubmit()"
                    placeholder="{{ app()->getLocale() === 'ar' ? 'ابحث في المستندات...' : 'Search documents...' }}"
                    class="w-full ps-10 pe-4 py-2.5 bg-transparent border-0 text-text-primary text-sm focus:outline-none focus:ring-0 focus:border-0"
                    autocomplete="off"
                />
                <span class="absolute inset-y-0 start-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>

                {{-- Autocomplete Suggestions Dropdown --}}
                <div x-show="showSuggestions && filteredSuggestions.length > 0"
                     x-cloak
                     class="absolute z-30 mt-2 w-full bg-white border border-primary-light/20 rounded-xl shadow-lg py-1 start-0 overflow-hidden">
                    <template x-for="(suggestion, idx) in filteredSuggestions" :key="idx">
                        <button type="button" 
                                @click="selectSuggestion(suggestion)"
                                class="w-full text-start px-4 py-2.5 text-xs sm:text-sm text-text-primary hover:bg-[#EAEAE9] transition-colors font-medium">
                            <span x-text="suggestion"></span>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Dropdowns & Reset container --}}
            <div class="flex items-center gap-2 w-full sm:w-auto">
                
                {{-- Separator Line on Desktop --}}
                <div class="hidden sm:block h-6 w-px bg-gray-200"></div>

                {{-- Category Filter --}}
                <div class="relative flex-1 sm:flex-initial sm:min-w-[160px]">
                    <select name="category" onchange="this.form.submit()"
                            class="appearance-none w-full ps-4 pe-10 py-2 bg-white border border-gray-200 rounded-lg text-sm text-text-primary font-medium focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 hover:border-primary/40 transition-colors duration-150 cursor-pointer shadow-sm">
                        <option value="">{{ app()->getLocale() === 'ar' ? 'جميع التصنيفات' : 'All Categories' }}</option>
                        @foreach ($categoryNames as $key => $name)
                            <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 end-3 flex items-center pointer-events-none text-text-muted">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- Separator Line on Desktop --}}
                <div class="hidden sm:block h-6 w-px bg-gray-200"></div>

                {{-- Year Filter --}}
                <div class="relative flex-1 sm:flex-initial sm:min-w-[120px]">
                    <select name="year" onchange="this.form.submit()"
                            class="w-full ps-4 pe-10 py-2 bg-white border border-gray-200 rounded-lg text-sm text-text-primary font-medium focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 hover:border-primary/40 transition-colors duration-150 appearance-none cursor-pointer shadow-sm">
                        <option value="">{{ app()->getLocale() === 'ar' ? 'جميع السنوات' : 'All Years' }}</option>
                        @foreach ($availableYears as $y)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 end-3 flex items-center pointer-events-none text-text-muted">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- Reset Button --}}
                @if(request('search') || request('category') || request('year'))
                    <a
                        href="{{ route('governance.index') }}"
                        class="p-2 rounded-full hover:bg-gray-100 flex items-center justify-center shrink-0 text-red-500"
                        title="{{ app()->getLocale() === 'ar' ? 'إعادة ضبط' : 'Reset' }}"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Main Content Layout (Sidebar + Main Area) -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start max-w-5xl mx-auto mt-4 px-4 sm:px-0">
        
        <!-- Sidebar Navigation (Vertical list on desktop, horizontal chips on mobile) -->
        <div class="lg:col-span-1 space-y-4 lg:sticky lg:top-6">
            {{-- Mobile Chip Row --}}
            <div class="flex items-center gap-2 overflow-x-auto pb-2 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden lg:hidden">
                <a href="{{ route('governance.index', request()->except('category')) }}"
                   class="shrink-0 px-4 py-2 rounded-full text-xs font-bold transition-all {{ !request('category') ? 'bg-[#A38B54] text-white shadow-sm' : 'bg-white text-text-primary border border-primary-light/20 hover:bg-[#EAEAE9]/50' }}">
                    {{ app()->getLocale() === 'ar' ? 'جميع المستندات' : 'All Documents' }}
                    <span class="ms-1 px-2 py-0.5 rounded-full text-[10px] {{ !request('category') ? 'bg-white/20 text-white' : 'bg-gray-100 text-text-muted' }}">
                        {{ \App\Models\GovernanceDocument::active()->count() }}
                    </span>
                </a>
                @foreach ($categoryNames as $catKey => $catName)
                    @php
                        $catCount = \App\Models\GovernanceDocument::active()->where('category', $catKey)->count();
                        $isActive = request('category') === $catKey;
                    @endphp
                    <a href="{{ route('governance.index', array_merge(request()->query(), ['category' => $catKey])) }}"
                       class="shrink-0 px-4 py-2 rounded-full text-xs font-bold transition-all {{ $isActive ? 'bg-[#A38B54] text-white shadow-sm' : 'bg-white text-text-primary border border-primary-light/20 hover:bg-[#EAEAE9]/50' }}">
                        {{ $catName }}
                        <span class="ms-1 px-2 py-0.5 rounded-full text-[10px] {{ $isActive ? 'bg-white/20 text-white' : 'bg-gray-100 text-text-muted' }}">
                            {{ $catCount }}
                        </span>
                    </a>
                @endforeach
            </div>

            {{-- Desktop Sidebar Navigation --}}
            <div class="hidden lg:block bg-white p-5 rounded-2xl border border-primary-light/20 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-[#3D342A] border-b border-primary-light/15 pb-2">
                    {{ app()->getLocale() === 'ar' ? 'تصنيفات المستندات' : 'Document Categories' }}
                </h3>
                <nav class="flex flex-col gap-1">
                    <a href="{{ route('governance.index', request()->except('category')) }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ !request('category') ? 'bg-[#A38B54] text-white shadow-sm' : 'text-text-primary hover:bg-[#EAEAE9]/50' }}">
                        <span>{{ app()->getLocale() === 'ar' ? 'جميع المستندات' : 'All Documents' }}</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] {{ !request('category') ? 'bg-white/20 text-white' : 'bg-gray-100 text-text-muted' }}">
                            {{ \App\Models\GovernanceDocument::active()->count() }}
                        </span>
                    </a>
                    @foreach ($categoryNames as $catKey => $catName)
                        @php
                            $catCount = \App\Models\GovernanceDocument::active()->where('category', $catKey)->count();
                            $isActive = request('category') === $catKey;
                        @endphp
                        <a href="{{ route('governance.index', array_merge(request()->query(), ['category' => $catKey])) }}"
                           class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ $isActive ? 'bg-[#A38B54] text-white shadow-sm' : 'text-text-primary hover:bg-[#EAEAE9]/50' }}">
                            <span>{{ $catName }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $isActive ? 'bg-white/20 text-white' : 'bg-gray-100 text-text-muted' }}">
                                {{ $catCount }}
                            </span>
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="lg:col-span-3 space-y-8">
            @php
                $hasResults = false;
                foreach ($categoryNames as $catKey => $catName) {
                    if (!request('category') || request('category') == $catKey) {
                        if ($documents->has($catKey) && $documents->get($catKey)->isNotEmpty()) {
                            $hasResults = true;
                            break;
                        }
                    }
                }
            @endphp

            @if ($hasResults)
                @foreach ($categoryNames as $catKey => $catName)
                    @if (!request('category') || request('category') == $catKey)
                        @if ($documents->has($catKey) && $documents->get($catKey)->isNotEmpty())
                            <section class="space-y-4">
                                {{-- Category Header with circular badge numbering style --}}
                                <div class="flex items-center gap-3 border-b border-primary-light/20 pb-2">
                                    <div class="w-7 h-7 rounded-full bg-[#A38B54] text-white flex items-center justify-center text-xs font-bold shadow-sm">
                                        {{ $loop->iteration }}
                                    </div>
                                    <h2 class="text-lg font-bold text-text-primary">
                                        {{ $catName }}
                                    </h2>
                                    <span class="ms-auto text-[10px] font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                                        {{ $documents->get($catKey)->count() }}
                                    </span>
                                </div>

                                {{-- Category Items --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach ($documents->get($catKey) as $doc)
                                        @php
                                            $locale = app()->getLocale();
                                            $title  = $locale === 'ar' ? $doc->title_ar : ($doc->title_en ?? $doc->title_ar);
                                            $sizeMb = $doc->file_size ? round($doc->file_size / 1024 / 1024, 2) . ' MB' : null;

                                            // Resolve direct download URL
                                            $downloadUrl = null;
                                            if (!empty($doc->file_path)) {
                                                $path = trim($doc->file_path);
                                                if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                                                    $downloadUrl = $path;
                                                } else {
                                                    if (str_starts_with($path, '/storage/')) {
                                                        $path = substr($path, 9);
                                                    } elseif (str_starts_with($path, 'storage/')) {
                                                        $path = substr($path, 8);
                                                    }
                                                    $downloadUrl = asset('storage/' . ltrim($path, '/'));
                                                }
                                            }

                                            // Background and badge colors matching the categories
                                            $bgClass = 'bg-primary/10 border-primary/20 text-primary';
                                            $badgeClass = 'bg-primary text-white';
                                            if ($catKey === \App\Models\GovernanceDocument::CATEGORY_POLICIES) {
                                                $bgClass = 'bg-primary-light/20 border-primary-light/30 text-text-primary';
                                                $badgeClass = 'bg-primary-light text-text-primary border border-primary/10';
                                            } elseif ($catKey === \App\Models\GovernanceDocument::CATEGORY_FINANCIAL) {
                                                $bgClass = 'bg-primary/10 border-primary/20 text-primary';
                                                $badgeClass = 'bg-primary text-white';
                                            } elseif ($catKey === \App\Models\GovernanceDocument::CATEGORY_ACHIEVEMENT) {
                                                $bgClass = 'bg-secondary-light/30 border-secondary-light/40 text-text-primary';
                                                $badgeClass = 'bg-secondary-light text-text-primary border border-primary/15';
                                            }
                                        @endphp

                                        <div class="group flex flex-col justify-between h-full bg-white rounded-xl border border-primary-light/15 hover:border-primary/30 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                                            
                                            <!-- Thumbnail Area: fallback to PDF icon in colored background block -->
                                            <div class="relative w-full aspect-video flex items-center justify-center border-b border-primary-light/10 transition-colors {{ $bgClass }}">
                                                
                                                <!-- Year Badge: Absolute Top-Start -->
                                                @if ($doc->fiscal_year)
                                                    <span class="absolute top-3 start-3 px-2.5 py-1 rounded-full text-[10px] font-bold shadow-sm tracking-wide {{ $badgeClass }}">
                                                        📅 {{ __('frontend.fiscal_year') }} {{ $doc->fiscal_year }}
                                                    </span>
                                                @endif

                                                <!-- PDF Icon -->
                                                <svg class="w-12 h-12 transition-transform group-hover:scale-110 duration-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                                </svg>
                                            </div>

                                            <!-- Body Info -->
                                            <div class="p-5 flex-1 flex flex-col justify-between">
                                                <div class="space-y-2">
                                                    <div class="flex items-center justify-between text-[10px] text-text-muted font-medium">
                                                        <span class="uppercase tracking-wider">PDF</span>
                                                        @if ($sizeMb)
                                                            <span>{{ $sizeMb }}</span>
                                                        @endif
                                                    </div>

                                                    <h3 class="text-sm font-bold text-text-primary line-clamp-2 leading-snug group-hover:text-primary transition-colors">
                                                        {{ $title }}
                                                    </h3>
                                                </div>

                                                <!-- Download Button -->
                                                @if ($downloadUrl)
                                                    <div class="mt-4 pt-3 border-t border-primary-light/10 flex flex-col gap-2">
                                                        <x-frontend.button :href="$downloadUrl" download variant="outline" size="sm" class="w-full justify-center gap-1.5" aria-label="{{ __('frontend.download') }}">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                            </svg>
                                                            <span>{{ __('frontend.download') }}</span>
                                                        </x-frontend.button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        @endif
                    @endif
                @endforeach
            @else
                {{-- Empty state UI for zero filtered results --}}
                <div class="py-16 text-center flex flex-col items-center justify-center bg-white rounded-xl border border-primary-light/15 shadow-sm">
                    <div class="w-16 h-16 rounded-full bg-secondary-light/40 flex items-center justify-center text-primary mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 8.75h18.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-text-primary mb-1">
                        {{ app()->getLocale() === 'ar' ? 'لا توجد نتائج مطابقة' : 'No matching documents' }}
                    </h3>
                    <p class="text-xs text-text-muted max-w-xs px-4">
                        {{ app()->getLocale() === 'ar' ? 'جرب البحث بكلمات أخرى أو تغيير خيارات التصفية' : 'Try searching with other keywords or modifying your filter choices' }}
                    </p>
                </div>
            @endif
        </div>
    </div>

</x-frontend-layout>
