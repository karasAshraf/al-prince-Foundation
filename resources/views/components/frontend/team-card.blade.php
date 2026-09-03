@props([
    'member',           // TeamMember model instance
    'layout' => 'grid', // 'grid' (executive) | 'list' (board)
])

@php
    $name     = $member->localizedName();
    $position = $member->localizedPosition();
    $bio      = $member->localizedBio();
    $img      = \App\Helpers\MediaHelper::url($member, 'team_photos', 'image', 'card');
    $isRtl    = app()->getLocale() === 'ar';
    $bioId    = 'bio-' . $member->id;
    $btnId    = 'bio-btn-' . $member->id;
    $hasBio   = !empty(trim($bio));
    $longBio  = $hasBio && mb_strlen($bio) > 120;
@endphp

@if ($layout === 'list')
    {{-- ─── LIST LAYOUT (Board of Directors) ─────────────────────────────── --}}
    <article
        x-data="{ expanded: false }"
        {{ $attributes->merge(['class' => 'group relative bg-background border border-secondary/40 rounded-2xl overflow-hidden shadow-sm transition-all duration-300 hover:shadow-md hover:border-primary/40 hover:-translate-y-0.5 focus-within:ring-2 focus-within:ring-primary/40 motion-reduce:transition-none motion-reduce:transform-none']) }}
    >
        <div class="flex flex-col sm:flex-row items-start gap-6 p-6 sm:p-8">

            {{-- Avatar --}}
            <div class="relative shrink-0 mx-auto sm:mx-0">
                <div class="relative w-24 h-24 sm:w-28 sm:h-28 md:w-[7.5rem] md:h-[7.5rem] rounded-full overflow-hidden ring-4 ring-offset-2 ring-primary/20 group-hover:ring-primary/50 group-hover:scale-[1.02] transition-all duration-300 bg-secondary/20 shrink-0">
                    @if ($img)
                        <img
                            src="{{ $img }}"
                            alt="{{ $name }}"
                            loading="lazy"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                        >
                    @else
                        <div class="w-full h-full flex items-center justify-center text-primary/40">
                            <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0 space-y-2 text-center sm:text-start">
                {{-- Name --}}
                <h3 class="text-xl font-bold text-text-primary leading-snug break-words">
                    {{ $name }}
                </h3>

                {{-- Divider --}}
                <div class="w-12 h-[2px] bg-secondary/60 rounded-full my-1.5 mx-auto sm:mx-0 transition-all duration-300 group-hover:w-20 group-hover:bg-primary" aria-hidden="true"></div>

                {{-- Position --}}
                @if ($position)
                    <p class="text-sm font-semibold text-primary tracking-wide">
                        {{ $position }}
                    </p>
                @endif

                {{-- Bio with expand/collapse --}}
                @if ($hasBio)
                    <div class="pt-1">
                        @if ($longBio)
                            <div x-data="{ expanded: false }">
                                <p
                                    x-show="!expanded"
                                    class="text-sm text-text-primary/80 leading-relaxed line-clamp-2 break-words"
                                >{{ $bio }}</p>
                                <p
                                    x-show="expanded"
                                    x-cloak
                                    class="text-sm text-text-primary/80 leading-relaxed break-words"
                                >{{ $bio }}</p>
                                <button
                                    id="{{ $btnId }}"
                                    @click="expanded = !expanded"
                                    :aria-expanded="expanded"
                                    :aria-controls="'{{ $bioId }}'"
                                    class="mt-1.5 text-xs font-semibold text-primary hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded cursor-pointer"
                                >
                                    <span x-show="!expanded">{{ app()->getLocale() === 'ar' ? 'اقرأ المزيد ↓' : 'Read more ↓' }}</span>
                                    <span x-show="expanded" x-cloak>{{ app()->getLocale() === 'ar' ? 'أخفِ ↑' : 'Show less ↑' }}</span>
                                </button>
                            </div>
                        @else
                            <p class="text-sm text-text-primary/80 leading-relaxed break-words">{{ $bio }}</p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Decorative accent bar --}}
            <div class="absolute top-0 start-0 h-full w-1 bg-primary opacity-0 group-hover:opacity-100 transition-opacity duration-300" aria-hidden="true"></div>
        </div>
    </article>

@else
    {{-- ─── GRID LAYOUT (Executive Team) ──────────────────────────────────── --}}
    <article
        {{ $attributes->merge(['class' => 'group relative flex flex-col items-center text-center bg-background border border-secondary/40 rounded-2xl overflow-hidden shadow-sm transition-all duration-300 hover:shadow-md hover:border-primary/40 hover:-translate-y-1 focus-within:ring-2 focus-within:ring-primary/40 motion-reduce:transition-none motion-reduce:transform-none h-full']) }}
    >
        {{-- Top decorative gradient band --}}
        <div class="w-full h-1 bg-gradient-to-r from-primary via-secondary to-primary/80 opacity-70 group-hover:opacity-100 transition-opacity duration-300" aria-hidden="true"></div>

        <div class="flex flex-col items-center p-6 sm:p-8 flex-1 w-full">

            {{-- Avatar --}}
            <div class="relative mb-5 shrink-0">
                <div class="relative w-28 h-28 sm:w-32 sm:h-32 rounded-2xl overflow-hidden ring-4 ring-offset-2 ring-primary/20 group-hover:ring-primary/50 transition-all duration-300 bg-secondary/20 mx-auto">
                    @if ($img)
                        <img
                            src="{{ $img }}"
                            alt="{{ $name }}"
                            loading="lazy"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                        >
                    @else
                        <div class="w-full h-full flex items-center justify-center text-primary/40">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Name --}}
            <h3 class="text-xl font-bold text-text-primary leading-snug break-words w-full px-1">
                {{ $name }}
            </h3>

            {{-- Divider --}}
            <div class="w-12 h-[2px] bg-secondary/60 rounded-full my-2.5 mx-auto transition-all duration-300 group-hover:w-20 group-hover:bg-primary" aria-hidden="true"></div>

            {{-- Position --}}
            @if ($position)
                <p class="text-sm font-semibold text-primary tracking-wide mb-3 break-words px-1">
                    {{ $position }}
                </p>
            @endif

            {{-- Bio with expand/collapse --}}
            @if ($hasBio)
                <div class="w-full mt-auto pt-2">
                    @if ($longBio)
                        <div x-data="{ expanded: false }">
                            <p
                                x-show="!expanded"
                                class="text-sm text-text-primary/80 leading-relaxed line-clamp-3 break-words {{ $isRtl ? 'text-right' : 'text-left' }} sm:text-center"
                            >{{ $bio }}</p>
                            <p
                                x-show="expanded"
                                x-cloak
                                class="text-sm text-text-primary/80 leading-relaxed break-words {{ $isRtl ? 'text-right' : 'text-left' }} sm:text-center"
                            >{{ $bio }}</p>
                            <button
                                id="{{ $btnId }}"
                                @click="expanded = !expanded"
                                :aria-expanded="expanded"
                                class="mt-2 text-xs font-semibold text-primary hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded cursor-pointer"
                            >
                                <span x-show="!expanded">{{ $isRtl ? 'اقرأ المزيد ↓' : 'Read more ↓' }}</span>
                                <span x-show="expanded" x-cloak>{{ $isRtl ? 'أخفِ ↑' : 'Show less ↑' }}</span>
                            </button>
                        </div>
                    @else
                        <p class="text-sm text-text-primary/80 leading-relaxed break-words {{ $isRtl ? 'text-right' : 'text-left' }} sm:text-center">{{ $bio }}</p>
                    @endif
                </div>
            @endif
        </div>
    </article>
@endif
