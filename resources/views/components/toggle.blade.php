@props([
    'name',
    'label' => null,
    'checked' => false,
    'value' => '1',
])

<div
    x-data="{ on: {{ old($name, $checked) ? 'true' : 'false' }} }"
>
    <label class="flex items-center gap-3 cursor-pointer">

        {{-- Hidden real checkbox — this is what actually gets submitted with the form --}}
        <input
            type="checkbox"
            name="{{ $name }}"
            value="{{ $value }}"
            x-model="on"
            class="sr-only peer"
        >

        {{-- Visual slider — purely decorative, controlled by Alpine's "on" state --}}
        <span
            @click="on = !on"
            :class="on ? 'bg-[#A38B54]' : 'bg-[#B49C6E]/30'"
            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
        >
            <span
                :class="on ? 'translate-x-5 rtl:-translate-x-5' : 'translate-x-1'"
                class="inline-block h-4 w-4 transform rounded-full bg-[#EAEAE9] transition-transform"
            ></span>
        </span>

        <span class="text-sm text-[#3D342A]">{{ $label }}</span>
    </label>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>