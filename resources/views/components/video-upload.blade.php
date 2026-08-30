@props([
    'name',
    'label' => null,
    'required' => false,
    'currentUrl' => null,
    'hint' => 'MP4, WEBM — حتى 20 ميجابايت',
])

<div
    x-data="{
        preview: @js($currentUrl),
        handlePreview(file) {
            if (!file) return;
            this.preview = URL.createObjectURL(file);
        }
    }"
>
    @if($label)
        <label class="mb-1.5 block text-sm font-medium text-[#3D342A]">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    {{-- Video preview --}}
    <template x-if="preview">
        <video :src="preview" controls class="mb-3 max-h-56 w-full rounded-lg border border-[#B49C6E]/30 bg-black"></video>
    </template>

    {{-- Upload zone --}}
    <label
        for="{{ $name }}"
        class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-lg border-2 border-dashed border-[#B49C6E]/40 px-4 py-6 text-center transition-colors hover:bg-[#EAEAE9]/20"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#A38B54]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
        </svg>

        <span class="text-sm text-[#3D342A]">
            اسحب الفيديو هنا أو <span class="font-medium text-[#A38B54]">اختر من جهازك</span>
        </span>

        @if($hint)
            <span class="text-xs text-[#3D342A]/50">{{ $hint }}</span>
        @endif

        <input
            type="file"
            name="{{ $name }}"
            id="{{ $name }}"
            accept="video/*"
            @change="handlePreview($event.target.files[0])"
            {{ $required && !$currentUrl ? 'required' : '' }}
            class="hidden"
        >
    </label>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>