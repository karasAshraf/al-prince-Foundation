<?php

namespace App\Http\Requests;

use App\Models\MediaLibrary;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MediaLibraryRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function prepareForValidation(): void
    {
        if ($this->hasFile('file')) {
            $valid = collect($this->file('file'))->filter(fn($f) => $f && $f->isValid())->all();
            $this->files->set('file', $valid);
        }
    }

    public function rules(): array
    {
        $itemId = $this->route('media_library')?->id;

        return [
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('media_libraries', 'slug')->ignore($itemId)],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'category' => ['nullable', Rule::in(array_keys(MediaLibrary::categories()))],

            // ملف أو رابط خارجي — كلاهما اختياري لكن يفضّل توفر واحد منهما
            'file' => ['nullable'],
            'file.*' => ['file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,png,jpg,jpeg,webp,mp4,mov,webm', 'mimetypes:video/mp4,video/quicktime,video/webm,application/octet-stream,application/pdf,application/msword,image/png,image/jpeg,image/webp', 'max:102400'],
            'external_link' => ['nullable'],
            'external_link.*' => ['nullable', 'url', 'max:2048'],

            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],

            'meta_title_ar' => ['nullable', 'string', 'max:255'],
            'meta_title_en' => ['nullable', 'string', 'max:255'],
            'meta_description_ar' => ['nullable', 'string'],
            'meta_description_en' => ['nullable', 'string'],
            'meta_keywords'       => ['nullable', 'string', 'max:255'],
            'canonical_url'       => ['nullable', 'url', 'max:500'],
            'og_image'            => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.*.mimes' => 'صيغة الملف غير مدعومة.',
            'file.*.mimetypes' => 'صيغة الملف غير مدعومة.',
            'file.*.max' => 'حجم الملف يتجاوز الحد المسموح به.',
            'external_link.url' => 'الرجاء إدخال رابط صحيح',
        ];
    }
}
