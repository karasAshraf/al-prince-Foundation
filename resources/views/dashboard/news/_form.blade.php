@csrf

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    {{-- ============ MAIN COLUMN ============ --}}
    <div class="space-y-5 lg:col-span-2">

        <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-forms.input
                    name="title_ar"
                    label="{{ __('dashboard.news.news_title') }} (AR)"
                    :value="old('title_ar', $news->title_ar ?? '')"
                    required
                />
                <x-forms.input
                    name="title_en"
                    label="{{ __('dashboard.news.news_title') }} (EN)"
                    :value="old('title_en', $news->title_en ?? '')"
                />
            </div>

            <div class="mt-4">
                <x-forms.slug-input
                    name="slug"
                    :value="old('slug', $news->slug ?? '')"
                    source-field="title_ar"
                />
            </div>


            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-forms.textarea
                    name="excerpt_ar"
                    label="{{ __('dashboard.news.summary') }} (AR)"
                    :value="old('excerpt_ar', $news->excerpt_ar ?? '')"
                    rows="3"
                />
                <x-forms.textarea
                    name="excerpt_en"
                    label="{{ __('dashboard.news.summary') }} (EN)"
                    :value="old('excerpt_en', $news->excerpt_en ?? '')"
                    rows="3"
                />
            </div>
        </div>

        <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5">
            <div class="mb-4">
                <x-forms.rich-editor
                    name="content_ar"
                    label="{{ __('dashboard.news.content') }} (AR)"
                    :value="old('content_ar', $news->content_ar ?? '')"
                    required
                />
            </div>
            <x-forms.rich-editor
                name="content_en"
                label="{{ __('dashboard.news.content') }} (EN)"
                :value="old('content_en', $news->content_en ?? '')"
            />
        </div>

        <x-forms.seo-fields :seo-meta="$news->seoMeta ?? null" />
    </div>

    {{-- ============ SIDEBAR COLUMN ============ --}}
    <div class="space-y-5">

        <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5">
            <h3 class="mb-4 text-sm font-semibold text-[#3D342A]">{{ __('dashboard.news.status') }}</h3>

            <div class="space-y-4">
                <x-forms.select
                    name="status"
                    label="{{ __('dashboard.common.status') }}"
                    :options="['draft' => __('dashboard.news.draft'), 'published' => __('dashboard.news.published')]"
                    :selected="old('status', $news->status ?? 'draft')"
                    required
                />

                <x-forms.date-picker
                    name="published_at"
                    label="{{ __('dashboard.news.published_at') }}"
                    :value="old('published_at', $news->published_at?->format('Y-m-d'))"
                />
            </div>
        </div>

        <x-media-upload
            name="image"
            url-name="external_link"
            :current-url="isset($news) ? \App\Helpers\MediaHelper::url($news, 'news_images', 'image') : null"
            :current-external-url="$news->external_link ?? null"
        />



    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <x-buttons.primary type="submit">{{ __('dashboard.common.save') }}</x-buttons.primary>
    <a href="{{ route('dashboard.news.index') }}">
        <x-buttons.secondary type="button">{{ __('dashboard.common.cancel') }}</x-buttons.secondary>
    </a>
</div>
