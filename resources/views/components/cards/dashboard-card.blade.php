@props([
    'title'       => null,
    'description' => null,
    'footer'      => null,
    'padding'     => true,
])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] shadow-sm']) }}>
    @if($title)
        <div class="border-b border-[#B49C6E]/10 px-5 py-4">
            <h3 class="text-sm font-semibold text-[#3D342A]">{{ $title }}</h3>
            @if($description)
                <p class="mt-0.5 text-xs text-[#3D342A]/50">{{ $description }}</p>
            @endif
        </div>
    @endif

    <div @class(['p-5' => $padding])>
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="border-t border-[#B49C6E]/10 px-5 py-3">
            {{ $footer }}
        </div>
    @endif
</div>
