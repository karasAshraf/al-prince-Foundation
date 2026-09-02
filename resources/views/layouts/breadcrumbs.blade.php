{{--
    Breadcrumbs — reads a $breadcrumbs array optionally passed by the child view.
    If a page doesn't define one, only "الرئيسية" shows by default.

    Usage in a child view, BEFORE @section('content'):
    @php
        $breadcrumbs = [
            ['label' => 'الأخبار', 'url' => route('dashboard.news.index')],
            ['label' => 'تعديل خبر', 'url' => null], // null = current page, no link
        ];
    @endphp
--}}
<nav class="flex-none shrink-0 z-10 border-b border-[#B7B5B3]/50 bg-background px-4 py-3 sm:px-6 lg:px-8" aria-label="مسار التنقل">
    <ol class="flex flex-wrap items-center gap-2 text-sm text-[#5C5450]">
        <li class="flex items-center gap-2">
            <a href="{{ route('dashboard.home') }}" class="flex items-center gap-1 hover:text-[#A38B54]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10" />
                </svg>
                <span>{{ __('dashboard.common.home') }}</span>
            </a>

            @if(!empty($breadcrumbs ?? []))
                <span class="text-[#3D342A]/30">/</span>
            @endif
        </li>

        @foreach ($breadcrumbs ?? [] as $index => $crumb)
            <li class="flex items-center gap-2">
                @if ($crumb['url'] ?? null)
                    <a href="{{ $crumb['url'] }}" class="hover:text-[#A38B54]">
                        {{ $crumb['label'] }}
                    </a>
                @else
                    {{-- Current page — no link, distinct styling --}}
                    <span class="font-medium text-[#3D342A]" aria-current="page">
                        {{ $crumb['label'] }}
                    </span>
                @endif

                @if (!$loop->last)
                    <span class="text-[#3D342A]/30">/</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>