@props([
    'name',
    'label' => null,
    'accept' => '*',
    'required' => false,
    'currentUrl' => null,
    'hint' => null,
])

<div
    x-data="{
        fileName: null,
        dragging: false,
        handleFile(file) {
            this.fileName = file ? file.name : null;
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

    {{-- Show current file if editing an existing record --}}
    @if($currentUrl)
        <div class="mb-2 flex items-center gap-2 rounded-lg border border-[#B49C6E]/30 bg-[#EAEAE9]/30 px-3 py-2 text-sm text-[#3D342A]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span class="truncate">ملف حالي: {{ basename($currentUrl) }}</span>
        </div>
    @endif

    {{-- Drag & drop zone --}}
    <label
        for="{{ $name }}"
        @dragover.prevent="dragging = true"
        @dragleave.prevent="dragging = false"
        @drop.prevent="dragging = false; $refs.fileInput.files = $event.dataTransfer.files; handleFile($event.dataTransfer.files[0])"
        :class="dragging ? 'border-[#A38B54] bg-[#EAEAE9]/40' : 'border-[#B49C6E]/40'"
        class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-lg border-2 border-dashed px-4 py-6 text-center transition-colors hover:bg-[#EAEAE9]/20"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#A38B54]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9m0 0l-3.75 3.75M12 9l3.75 3.75M3 17.25V19.5A2.25 2.25 0 005.25 21.75h13.5A2.25 2.25 0 0021 19.5v-2.25" />
        </svg>

        <span class="text-sm text-[#3D342A]">
            اسحب الملف هنا أو <span class="font-medium text-[#A38B54]">اختر من جهازك</span>
        </span>

        @if($hint)
            <span class="text-xs text-[#3D342A]/50">{{ $hint }}</span>
        @endif

        {{-- Shows the newly selected file name --}}
        <span x-show="fileName" x-text="fileName" class="mt-1 text-xs font-medium text-[#A38B54]"></span>

        <input
            type="file"
            name="{{ $name }}"
            id="{{ $name }}"
            accept="{{ $accept }}"
            x-ref="fileInput"
            @change="handleFile($event.target.files[0])"
            {{ $required && !$currentUrl ? 'required' : '' }}
            class="hidden"
        >
    </label>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>