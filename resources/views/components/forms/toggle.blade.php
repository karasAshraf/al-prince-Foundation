@props([
    'name',
    'label'   => null,
    'value'   => false,
    'checked' => false,
    'disabled'=> false,
])

@php $isChecked = old($name, $value) || $checked; @endphp

<div>
    <div class="flex items-center gap-3">
        <div
            x-data="{ on: {{ $isChecked ? 'true' : 'false' }} }"
            class="relative"
        >
            <input
                type="hidden"
                name="{{ $name }}"
                :value="on ? '1' : '0'"
            >
            <button
                type="button"
                @click="on = !on"
                :class="on ? 'bg-[#A38B54]' : 'bg-[#3D342A]/20'"
                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[#A38B54] focus-visible:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                role="switch"
                :aria-checked="on.toString()"
                {{ $disabled ? 'disabled' : '' }}
            >
                <span
                    :class="on ? (document.documentElement.dir === 'rtl' ? '-translate-x-5' : 'translate-x-5') : 'translate-x-0'"
                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-background shadow ring-0 transition duration-200 ease-in-out"
                ></span>
            </button>
        </div>

        @if($label)
            <span class="text-sm font-medium text-[#3D342A] cursor-pointer select-none">{{ $label }}</span>
        @endif
    </div>

    @error($name)
        <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
    @enderror
</div>
