@props([
    'name'             => 'file',
    'urlName'          => 'external_link',
    'removeMediaName'  => 'remove_media',
    'label'            => null,
    'currentUrl'       => null,
    'currentExternalUrl' => null,
    'allowVideo'       => true,
    'allowPdf'         => true,
    'allowExternal'    => true,
    'required'         => false,
    'multiple'         => false,
    'mediaItems'       => null,
    'externalLinks'    => null,
])

@php
    use App\Helpers\MediaHelper;
    $displayLabel = $label ?? __('dashboard.common.upload_file');

    // Compile list of existing items for multiple mode
    $existingItems = [];
    if ($multiple) {
        if ($mediaItems) {
            foreach ($mediaItems as $media) {
                $existingItems[] = [
                    'id' => $media->id,
                    'name' => $media->file_name,
                    'size' => number_format($media->size / 1024 / 1024, 2) . ' MB',
                    'url' => $media->getUrl(),
                    'type' => 'local',
                ];
            }
        }
        if ($externalLinks) {
            $decoded = json_decode($externalLinks, true);
            $linksArray = is_array($decoded) ? $decoded : [$externalLinks];
            foreach ($linksArray as $link) {
                if ($link) {
                    $existingItems[] = [
                        'id' => null,
                        'name' => $link,
                        'size' => null,
                        'url' => $link,
                        'type' => 'external',
                    ];
                }
            }
        }
    }

    $initialSource = 'upload';
    if (!$multiple) {
        if (!empty($currentExternalUrl)) {
            $initialSource = 'url';
        } elseif (!empty($currentUrl) && MediaHelper::isExternal($currentUrl)) {
            $initialSource = 'url';
        }
    }
    $initialUrl = $currentExternalUrl ?: $currentUrl;
@endphp

<div
    x-data="{
        multiple: @js($multiple),
        sourceMode: '{{ $initialSource }}',
        
        // Single Mode properties
        previewUrl: @js($initialUrl),
        externalUrl: @js($currentExternalUrl ?: ''),
        mediaType: 'none',
        embedUrl: null,
        fileName: null,
        fileSize: null,
        isRemoved: false,

        // Multiple Mode properties
        items: @js($existingItems),
        deletedMediaIds: [],
        linkInput: '',
        isDragging: false,
        errorMessage: null,

        init() {
            if (!this.multiple && this.previewUrl) {
                this.detectSingleMediaType(this.previewUrl);
            }
        },

        detectSingleMediaType(urlOrPath) {
            this.mediaType = this.getMediaType(urlOrPath);
            if (this.mediaType === 'youtube' || this.mediaType === 'vimeo') {
                this.embedUrl = this.getEmbedUrl(urlOrPath, this.mediaType);
            } else {
                this.embedUrl = null;
            }
        },

        getMediaType(urlOrPath) {
            if (!urlOrPath) return 'none';
            const urlStr = String(urlOrPath).trim().toLowerCase();
            const cleanPath = urlStr.split('?')[0];

            if (urlStr.match(/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/i)) {
                return 'youtube';
            }
            if (urlStr.match(/vimeo\.com\/(?:video\/)?(\d+)/i)) {
                return 'vimeo';
            }
            if (/\.(mp4|mov|avi|webm|mkv|ogg)$/.test(cleanPath) || urlStr.startsWith('data:video/')) {
                return 'video';
            }
            if (/\.pdf$/.test(cleanPath) || urlStr.startsWith('data:application/pdf')) {
                return 'pdf';
            }
            if (/\.(jpg|jpeg|png|webp|gif|svg|bmp|tiff?)$/.test(cleanPath) || urlStr.startsWith('data:image/')) {
                return 'image';
            }
            if (/\.(docx?|xlsx?|pptx?|csv|txt|zip|tar|gz|rar)$/.test(cleanPath)) {
                return 'document';
            }
            return 'unknown';
        },

        getEmbedUrl(urlStr, type) {
            if (type === 'youtube') {
                const match = urlStr.match(/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/i);
                return match ? 'https://www.youtube.com/embed/' + match[1] : null;
            }
            if (type === 'vimeo') {
                const match = urlStr.match(/vimeo\.com\/(?:video\/)?(\d+)/i);
                return match ? 'https://player.vimeo.com/video/' + match[1] : null;
            }
            return null;
        },

        handleExternalUrlChange() {
            this.errorMessage = null;
            this.isRemoved    = false;
            const url = this.externalUrl ? this.externalUrl.trim() : '';
            if (!url) {
                this.previewUrl = null;
                this.mediaType  = 'none';
                this.embedUrl   = null;
                return;
            }

            if (!url.match(/^https?:\/\//i)) {
                this.errorMessage = 'يرجى إدخال رابط صحيح يبدأ بـ http:// أو https://';
                return;
            }

            this.previewUrl = url;
            this.detectSingleMediaType(url);
        },

        handleFileSelect(e) {
            const files = e.target.files ? e.target.files : (e.dataTransfer ? e.dataTransfer.files : []);
            if (files.length === 0) return;

            this.errorMessage = null;
            this.isRemoved    = false;

            if (this.multiple) {
                // Multi mode
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    
                    // Accept all mixed types (Image, Video, PDF, Docs)
                    const isAllowed = file.type.startsWith('image/') || 
                                      file.type.startsWith('video/') || 
                                      file.type === 'application/pdf' ||
                                      /\.(jpg|jpeg|png|webp|mp4|mov|webm|pdf|doc|docx|xls|xlsx|ppt|pptx|zip|txt|csv)$/i.test(file.name);

                    if (!isAllowed) {
                        this.errorMessage = 'صيغة الملف غير مدعومة';
                        continue;
                    }

                    if (file.size > 50 * 1024 * 1024) {
                        this.errorMessage = 'الحد الأقصى لحجم الملف 50 ميجابايت';
                        continue;
                    }

                    // Push to Alpine items list
                    this.items.push({
                        id: null,
                        name: file.name,
                        size: (file.size / (1024 * 1024)).toFixed(2) + ' MB',
                        url: URL.createObjectURL(file),
                        type: 'local',
                        fileObject: file
                    });
                }
                
                // Re-sync file input list
                this.syncFilesInput();
            } else {
                // Single mode
                const file = files[0];
                this.fileName = file.name;
                this.fileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                this.previewUrl = URL.createObjectURL(file);
                this.detectSingleMediaType(file.name);
            }
        },

        addMultiLink() {
            this.errorMessage = null;
            const url = this.linkInput ? this.linkInput.trim() : '';
            if (!url) return;

            if (!url.match(/^https?:\/\//i)) {
                this.errorMessage = 'يرجى إدخال رابط صحيح يبدأ بـ http:// أو https://';
                return;
            }

            this.items.push({
                id: null,
                name: url,
                size: null,
                url: url,
                type: 'external'
            });

            this.linkInput = '';
        },

        removeListItem(index) {
            const item = this.items[index];
            if (item.id) {
                this.deletedMediaIds.push(item.id);
            }
            this.items.splice(index, 1);
            this.syncFilesInput();
        },

        syncFilesInput() {
            // Helper to sync selected file list dynamically to the native file input element
            const dataTransfer = new DataTransfer();
            this.items.forEach(item => {
                if (item.fileObject) {
                    dataTransfer.items.add(item.fileObject);
                }
            });
            if (this.$refs.fileInput) {
                this.$refs.fileInput.files = dataTransfer.files;
            }
        },

        removeSingle() {
            this.previewUrl   = null;
            this.externalUrl  = '';
            this.fileName     = null;
            this.fileSize     = null;
            this.errorMessage = null;
            this.mediaType    = 'none';
            this.embedUrl     = null;
            this.isRemoved    = true;
            if (this.$refs.fileInput) {
                this.$refs.fileInput.value = '';
            }
        },

        switchSource(mode) {
            if (this.sourceMode !== mode) {
                this.sourceMode   = mode;
                this.errorMessage = null;
                if (!this.multiple && mode === 'url' && this.externalUrl) {
                    this.handleExternalUrlChange();
                }
            }
        }
    }"
    class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5 shadow-sm space-y-4"
>
    {{-- Single Mode Hidden Removal Input --}}
    <input type="hidden" name="{{ $removeMediaName }}" :value="isRemoved ? '1' : '0'">

    {{-- Multiple Mode Hidden Removal Inputs --}}
    <template x-for="mediaId in deletedMediaIds">
        <input type="hidden" name="remove_media_ids[]" :value="mediaId">
    </template>

    {{-- Header & Source Selector Tabs --}}
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between border-b border-[#B49C6E]/20 pb-3">
        <h3 class="text-sm font-semibold text-[#3D342A]">{{ $displayLabel }}</h3>

        @if($allowExternal)
            <div class="inline-flex rounded-lg bg-secondary/50 p-1 border border-[#B49C6E]/30">
                <button
                    type="button"
                    @click="switchSource('upload')"
                    :class="sourceMode === 'upload' ? 'bg-[#A38B54] text-secondary shadow-sm' : 'text-[#3D342A]/70 hover:text-[#3D342A]'"
                    class="flex items-center gap-1.5 rounded-md px-3 py-1 text-xs font-semibold transition"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <span>{{ __('dashboard.common.choose_file') }}</span>
                </button>

                <button
                    type="button"
                    @click="switchSource('url')"
                    :class="sourceMode === 'url' ? 'bg-[#A38B54] text-secondary shadow-sm' : 'text-[#3D342A]/70 hover:text-[#3D342A]'"
                    class="flex items-center gap-1.5 rounded-md px-3 py-1 text-xs font-semibold transition"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                    <span>{{ __('dashboard.media_library.external_link') }}</span>
                </button>
            </div>
        @endif
    </div>

    {{-- Error Message Display --}}
    <template x-if="errorMessage">
        <div class="rounded-lg bg-red-50 p-3 text-xs text-red-600 border border-red-200">
            <span x-text="errorMessage"></span>
        </div>
    </template>

    {{-- Mode 1: Device File Upload --}}
    <div x-show="sourceMode === 'upload'" class="space-y-3">
        <div
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="isDragging = false; handleFileSelect($event)"
            :class="isDragging ? 'border-[#A38B54] bg-secondary/40' : 'border-[#B49C6E]/40 bg-secondary'"
            class="relative flex flex-col items-center justify-center rounded-xl border-2 border-dashed p-6 transition"
        >
            <input
                x-ref="fileInput"
                type="file"
                name="{{ $name }}{{ $multiple ? '[]' : '' }}"
                :multiple="multiple"
                accept="image/*,video/*,application/pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.txt,.csv"
                @change="handleFileSelect($event)"
                class="hidden"
                {{ $required && !$currentUrl ? 'required' : '' }}
            >

            <div
                @click="$refs.fileInput.click()"
                class="flex flex-col items-center justify-center cursor-pointer space-y-2 py-3 text-center w-full"
            >
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-secondary/60 text-[#A38B54]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                </div>

                <p class="text-sm font-medium text-[#3D342A]">
                    {{ __('dashboard.common.drop_file_here') }}
                </p>
                <p class="text-xs text-[#3D342A]/50">
                    الصور، الفيديوهات، ملفات PDF والمستندات (Word, Excel) — حتى 50MB
                </p>
            </div>
        </div>

        {{-- Single Mode Preview Panel --}}
        <div x-show="!multiple">
            <template x-if="previewUrl && sourceMode === 'upload'">
                <div class="w-full space-y-3 text-center">
                    <div class="relative mx-auto overflow-hidden rounded-lg border border-[#B49C6E]/30 bg-black/5 max-h-60 flex items-center justify-center">
                        <template x-if="mediaType === 'image'">
                            <img :src="previewUrl" alt="Media Preview" class="max-h-60 w-auto object-contain rounded-lg">
                        </template>
                        <template x-if="mediaType === 'video'">
                            <video :src="previewUrl" controls class="max-h-60 w-full rounded-lg"></video>
                        </template>
                        <template x-if="mediaType === 'pdf'">
                            <div class="flex flex-col items-center justify-center gap-3 py-6 px-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                <div>
                                    <p class="text-sm font-semibold text-[#3D342A]" x-text="fileName || 'PDF'"></p>
                                    <p class="text-xs text-[#3D342A]/60" x-text="fileSize"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="flex items-center justify-center gap-3">
                        <button type="button" @click="removeSingle()" class="inline-flex items-center gap-1 text-xs font-semibold text-red-600 hover:underline">
                            <span>{{ __('dashboard.common.delete') }}</span>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- Mode 2: External Media URL --}}
    <div x-show="sourceMode === 'url'" class="space-y-4">
        <template x-if="!multiple">
            <div>
                <label class="mb-1 block text-xs font-medium text-[#3D342A]">رابط الصورة أو الفيديو الخارجي:</label>
                <input
                    type="url"
                    name="{{ $urlName }}"
                    x-model="externalUrl"
                    @input.debounce.400ms="handleExternalUrlChange()"
                    placeholder="https://example.com/file.pdf"
                    class="w-full rounded-lg border border-[#B49C6E]/40 bg-secondary px-3.5 py-2 text-sm text-[#3D342A] focus:border-[#A38B54] focus:outline-none"
                >
            </div>
        </template>

        <template x-if="multiple">
            <div class="flex gap-2">
                <input
                    type="url"
                    x-model="linkInput"
                    placeholder="أدخل الرابط الخارجي واضغط إضافة..."
                    class="flex-1 rounded-lg border border-[#B49C6E]/40 bg-secondary px-3.5 py-2 text-sm text-[#3D342A] focus:border-[#A38B54] focus:outline-none"
                >
                <x-buttons.primary type="button" @click="addMultiLink()">إضافة</x-buttons.primary>
            </div>
        </template>

        {{-- Single Mode Live Preview for External URL --}}
        <div x-show="!multiple">
            <template x-if="previewUrl && sourceMode === 'url'">
                <div class="space-y-2">
                    <div class="relative overflow-hidden rounded-lg border border-[#B49C6E]/30 bg-black/5 flex items-center justify-center p-3">
                        <template x-if="mediaType === 'image'">
                            <img :src="previewUrl" alt="External Preview" class="max-h-60 w-auto object-contain rounded-lg">
                        </template>
                        <template x-if="mediaType === 'video'">
                            <video :src="previewUrl" controls class="max-h-60 w-full rounded-lg"></video>
                        </template>
                        <template x-if="mediaType === 'youtube' || mediaType === 'vimeo'">
                            <div class="w-full aspect-video">
                                <iframe :src="embedUrl" class="w-full h-full rounded-lg" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </template>
                        <template x-if="mediaType === 'pdf'">
                            <div class="flex flex-col items-center justify-center gap-3 py-6 px-4 w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                <span class="text-xs text-[#3D342A]/60 break-all" x-text="previewUrl"></span>
                            </div>
                        </template>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" @click="removeSingle()" class="inline-flex items-center gap-1 text-xs font-semibold text-red-600 hover:underline">
                            <span>إزالة الرابط</span>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- Multiple Mode mixed files and links preview grid --}}
    <template x-if="multiple && items.length > 0">
        <div class="border-t border-[#B49C6E]/20 pt-4">
            <h4 class="text-xs font-semibold text-[#3D342A] mb-3">الوسائط والمستندات المضافة:</h4>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                <template x-for="(item, idx) in items" :key="idx">
                    <div class="relative rounded-xl border border-[#B49C6E]/20 bg-secondary/20 p-3 text-center flex flex-col justify-between items-center group h-40">
                        {{-- Remove button --}}
                        <button
                            type="button"
                            @click="removeListItem(idx)"
                            class="absolute top-1.5 end-1.5 p-1 bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition z-10"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <div class="w-full flex-1 flex flex-col items-center justify-center gap-2 overflow-hidden">
                            {{-- Previews depending on auto-detected type --}}
                            
                            {{-- 1. Images --}}
                            <template x-if="getMediaType(item.url) === 'image'">
                                <img :src="item.url" class="max-h-24 w-auto rounded-lg object-contain">
                            </template>

                            {{-- 2. Videos --}}
                            <template x-if="getMediaType(item.url) === 'video'">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    <span class="text-[11px] text-[#3D342A]/70 truncate max-w-[120px] mt-1" x-text="item.name"></span>
                                </div>
                            </template>

                            {{-- 3. PDFs --}}
                            <template x-if="getMediaType(item.url) === 'pdf'">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                    <span class="text-[11px] text-[#3D342A]/70 truncate max-w-[120px] mt-1" x-text="item.name"></span>
                                </div>
                            </template>

                            {{-- 4. Documents (docx, xlsx, zip, etc.) --}}
                            <template x-if="getMediaType(item.url) === 'document'">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    <span class="text-[11px] text-[#3D342A]/70 truncate max-w-[120px] mt-1" x-text="item.name"></span>
                                </div>
                            </template>

                            {{-- 5. External URLs --}}
                            <template x-if="item.type === 'external' || getMediaType(item.url) === 'unknown'">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-[#A38B54]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                                    <span class="text-[11px] text-blue-600 underline truncate max-w-[120px] mt-1" x-text="item.name"></span>
                                </div>
                            </template>
                        </div>

                        {{-- Hidden inputs to submit values for multiple links --}}
                        <template x-if="item.type === 'external'">
                            <input type="hidden" name="{{ $urlName }}[]" :value="item.url">
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </template>

    @error($name)
        <div class="rounded-lg bg-red-50 p-3 text-xs text-red-600 border border-red-200 mt-2">
            <span>{{ $message }}</span>
        </div>
    @enderror
    @error($name . '.*')
        <div class="rounded-lg bg-red-50 p-3 text-xs text-red-600 border border-red-200 mt-2">
            <span>{{ $message }}</span>
        </div>
    @enderror
    @error($urlName)
        <div class="rounded-lg bg-red-50 p-3 text-xs text-red-600 border border-red-200 mt-2">
            <span>{{ $message }}</span>
        </div>
    @enderror
    @error($urlName . '.*')
        <div class="rounded-lg bg-red-50 p-3 text-xs text-red-600 border border-red-200 mt-2">
            <span>{{ $message }}</span>
        </div>
    @enderror
</div>
