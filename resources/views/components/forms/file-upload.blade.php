@props([
    'name',
    'label'    => null,
    'accept'   => null,
    'required' => false,
    'helpText' => null,
])

@php
    $hasError = $errors->has($name);
    $borderClasses = $hasError
        ? 'border-2 border-red-500'
        : 'border-2 border-dashed border-[#B49C6E]/40 hover:border-[#A38B54]/60';
@endphp

<div x-data="{
    fileName: '',
    dragging: false,
    handleFile(event) {
        const file = event.target.files[0] ?? event.dataTransfer?.files[0];
        if (file) {
            this.fileName = file.name;
            if (event.target !== this.$refs.fileInput) {
                const dt = new DataTransfer();
                dt.items.add(file);
                this.$refs.fileInput.files = dt.files;
            }
        }
    },
    removeFile() {
        this.fileName = '';
        if (this.$refs.fileInput) {
            this.$refs.fileInput.value = '';
        }
    }
}">
    @if($label)
        <label class="mb-1.5 block text-sm font-medium text-[#3D342A]">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    <div
        @dragover.prevent="dragging = true"
        @dragleave.prevent="dragging = false"
        @drop.prevent="dragging = false; handleFile($event)"
        :class="dragging ? 'border-[#A38B54] bg-[#EAEAE9]/30' : 'bg-[#EAEAE9] {{ $borderClasses }}'"
        class="relative flex cursor-pointer flex-col items-center justify-center rounded-xl px-4 py-8 text-center transition duration-200 focus-within:ring-2 focus-within:ring-[#A38B54]/30"
        @click="$refs.fileInput.click()"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="mb-2 h-8 w-8 text-[#A38B54]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13" />
        </svg>

        <p class="text-sm text-[#3D342A]/70">
            <span class="font-semibold text-[#A38B54] underline">اضغط للرفع</span> أو اسحب الملف هنا
        </p>

        <template x-if="fileName">
            <div class="mt-3 flex items-center gap-2 rounded-lg bg-[#EAEAE9]/50 px-3 py-1.5 border border-[#B49C6E]/40" @click.stop>
                <span x-text="fileName" class="text-xs font-semibold text-[#3D342A]"></span>
                <button type="button" @click="removeFile()" class="text-red-600 hover:text-red-800 text-xs font-bold px-1" title="إزالة">✕</button>
            </div>
        </template>

        @if($helpText)
            <p class="mt-1.5 text-xs text-[#3D342A]/50">{{ $helpText }}</p>
        @endif

        <input
            x-ref="fileInput"
            type="file"
            name="{{ $name }}"
            class="sr-only"
            {{ $accept ? "accept={$accept}" : '' }}
            {{ $required ? 'required' : '' }}
            @change="handleFile($event)"
        >
    </div>

    @error($name)
        <p class="mt-1.5 text-xs font-medium text-red-600 flex items-center gap-1">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ $message }}</span>
        </p>
    @enderror
</div>
