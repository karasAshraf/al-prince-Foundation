@props([
    'name',
    'label' => null,
    'required' => false,
    'currentUrl' => null,
    'accept' => '.pdf',
    'hint' => 'PDF فقط — حتى 10 ميجابايت',
])

<div
    x-data="{
        fileName: null,
        fileSize: null,
        handleFile(file) {
            if (!file) return;
            this.fileName = file.name;
            this.fileSize = (file.size / 1024 / 1024).toFixed(2) + ' ميجابايت';
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

    {{-- Current file (when editing) --}}
    @if($currentUrl)
        <div class="mb-2 flex items-center justify-between rounded-lg border border-[#B49C6E]/30 bg-[#EAEAE9]/30 px-3 py-2 text-sm">
            <a href="{{ $currentUrl }}" target="_blank" class="flex items-center gap-2 text-[#3D342A] hover:text-[#A38B54]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>عرض الملف الحالي</span>
            </a>
        </div>
    @endif

    {{-- Upload zone --}}
    <label
        for="{{ $name }}"
        class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-lg border-2 border-dashed border-[#B49C6E]/40 px-4 py-6 text-center transition-colors hover:bg-[#EAEAE9]/20"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#A38B54]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
        </svg>

        <span class="text-sm text-[#3D342A]">
            اسحب الملف هنا أو <span class="font-medium text-[#A38B54]">اختر من جهازك</span>
        </span>

        @if($hint)
            <span class="text-xs text-[#3D342A]/50">{{ $hint }}</span>
        @endif

        {{-- Shows newly selected file info --}}
        <div x-show="fileName" class="mt-1 flex items-center gap-2 text-xs font-medium text-[#A38B54]">
            <span x-text="fileName"></span>
            <span class="text-[#3D342A]/40" x-text="fileSize"></span>
        </div>

        <input
            type="file"
            name="{{ $name }}"
            id="{{ $name }}"
            accept="{{ $accept }}"
            @change="handleFile($event.target.files[0])"
            {{ $required && !$currentUrl ? 'required' : '' }}
            class="hidden"
        >
    </label>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>