@props([
    'name',
    'label' => null,
    'required' => false,
    'currentUrl' => null,
    'hint' => 'JPG, PNG, WEBP — حتى 2 ميجابايت',
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

    <div class="flex flex-col gap-3 sm:flex-row sm:items-start">

        {{-- Preview thumbnail --}}
        <div class="flex h-28 w-28 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-[#B49C6E]/30 bg-secondary/20">
            <template x-if="preview">
                <img :src="preview" alt="معاينة الصورة" class="h-full w-full object-cover">
            </template>
            <template x-if="!preview">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#B49C6E]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M2.25 12V4.5A2.25 2.25 0 014.5 2.25h15a2.25 2.25 0 012.25 2.25v15a2.25 2.25 0 01-2.25 2.25H4.5A2.25 2.25 0 012.25 19.5V12z" />
                </svg>
            </template>
        </div>

        {{-- Upload zone (drag & drop) --}}
        <div class="flex-1">
            <label
                for="{{ $name }}"
                class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-lg border-2 border-dashed border-[#B49C6E]/40 px-4 py-6 text-center transition-colors hover:bg-secondary/20"
            >
                <span class="text-sm text-[#3D342A]">
                    اسحب الصورة هنا أو <span class="font-medium text-[#A38B54]">اختر من جهازك</span>
                </span>

                @if($hint)
                    <span class="text-xs text-[#3D342A]/50">{{ $hint }}</span>
                @endif

                <input
                    type="file"
                    name="{{ $name }}"
                    id="{{ $name }}"
                    accept="image/*"
                    @change="handlePreview($event.target.files[0])"
                    {{ $required && !$currentUrl ? 'required' : '' }}
                    class="hidden"
                >
            </label>
        </div>
    </div>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>