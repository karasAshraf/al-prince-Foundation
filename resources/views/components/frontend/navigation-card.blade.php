@props([
    'href',
    'icon',
    'title',
    'description',
    'btnText',
    'number' => null,
])

<a href="{{ $href }}"
   class="group relative flex flex-col justify-between h-full overflow-hidden rounded-2xl
          bg-background dark:bg-gray-800/90
          border border-secondary/15 dark:border-gray-700
          shadow-sm p-8
          transition-all duration-300 ease-out
          hover:border-primary/40 hover:shadow-lg hover:-translate-y-1.5
          active:scale-[0.99]
          focus:outline-none focus-visible:ring-2 focus-visible:ring-primary"
   aria-label="{{ $title }}">
   
    {{-- Watermark Number Overlay --}}
    @if($number)
        <span class="absolute bottom-4 end-6 text-7xl sm:text-8xl font-black text-primary/5 dark:text-primary/10 select-none pointer-events-none z-0">
            {{ $number }}
        </span>
    @endif

    {{-- Content --}}
    <div class="relative z-10 flex-1 flex flex-col justify-between space-y-6">
        <div class="space-y-4">
            {{-- Icon Badge --}}
            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-secondary-light/60 to-primary-light/30 dark:from-secondary-light/20 dark:to-primary-light/15
                        flex items-center justify-center text-primary dark:text-secondary
                        transition-all duration-300 ease-out
                        group-hover:from-primary group-hover:to-primary/80 group-hover:text-background group-hover:scale-110">
                <i data-lucide="{{ $icon }}" class="w-7 h-7"></i>
            </div>
            
            <h3 class="text-xl font-bold text-text-primary dark:text-background group-hover:text-primary transition-colors duration-200">
                {{ $title }}
            </h3>
            
            <p class="text-sm text-text-primary/65 dark:text-gray-300 leading-relaxed font-normal">
                {{ $description }}
            </p>
        </div>

        {{-- Lightweight Text CTA --}}
        <div class="pt-4 border-t border-secondary/10 flex items-center">
            <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary dark:text-secondary group-hover:text-primary/80 transition-colors">
                <span>{{ $btnText }}</span>
                <i data-lucide="arrow-left" class="w-4 h-4 shrink-0 transition-transform duration-300 group-hover:translate-x-1.5 rtl:group-hover:-translate-x-1.5 rtl:rotate-180"></i>
            </span>
        </div>
    </div>
</a>
