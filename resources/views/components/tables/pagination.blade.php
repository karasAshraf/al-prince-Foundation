@props(['paginator'])

@if($paginator->hasPages())
    <div class="mt-4 flex flex-col items-center justify-between gap-3 border-t border-[#B49C6E]/20 px-2 pt-4 sm:flex-row">

        <p class="text-sm text-[#3D342A]/60">
            عرض {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }} من {{ $paginator->total() }} نتيجة
        </p>

        <div class="flex items-center gap-1">
            {{-- Previous --}}
            @if($paginator->onFirstPage())
                <span class="rounded-md px-3 py-1.5 text-sm text-[#3D342A]/30">السابق</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="rounded-md px-3 py-1.5 text-sm text-[#3D342A] hover:bg-[#EAEAE9]/40">السابق</a>
            @endif

            {{-- Page numbers --}}
            @foreach($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                <a
                    href="{{ $url }}"
                    @class([
                        'rounded-md px-3 py-1.5 text-sm',
                        'bg-[#A38B54] text-[#EAEAE9]' => $page == $paginator->currentPage(),
                        'text-[#3D342A] hover:bg-[#EAEAE9]/40' => $page != $paginator->currentPage(),
                    ])
                >
                    {{ $page }}
                </a>
            @endforeach

            {{-- Next --}}
            @if($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="rounded-md px-3 py-1.5 text-sm text-[#3D342A] hover:bg-[#EAEAE9]/40">التالي</a>
            @else
                <span class="rounded-md px-3 py-1.5 text-sm text-[#3D342A]/30">التالي</span>
            @endif
        </div>
    </div>
@endif