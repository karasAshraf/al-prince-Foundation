<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AboutSectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
     public function rules(): array
    {
        $id = $this->route('about_section')?->id ?? $this->route('about_section');

        return [
            'title_ar'        => ['required', 'string', 'max:255'],
            'title_en'        => ['nullable', 'string', 'max:255'],
            'slug'            => ['nullable', 'string', 'max:255', Rule::unique('about_sections', 'slug')->ignore($id)],
            'description_ar'  => ['required', 'string'],
            'description_en'  => ['nullable', 'string'],
            'status'          => ['required', Rule::in(['draft', 'published'])],
            'video'           => ['nullable', 'url', 'max:500'],
            'external_link'   => ['nullable', 'url', 'max:500'],
            'media_external_link' => ['nullable', 'url', 'max:500'],
            'remove_media'    => ['nullable', 'boolean'],
            'image'           => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm,mkv,ogg', 'max:51200'],
            'order'           => ['nullable', 'integer', 'min:0'],
            'meta_title_ar'       => ['nullable', 'string', 'max:255'],
            'meta_title_en'       => ['nullable', 'string', 'max:255'],
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
            'title_ar.required'       => 'العنوان بالعربي مطلوب',
            'description_ar.required' => 'الوصف بالعربي مطلوب',
            'image.file'              => 'يجب أن يكون ملف الوسائط ملفاً صحيحاً',
            'image.mimes'             => 'صيغة الملف غير مدعومة. الصيغ المدعومة للصور: jpg, jpeg, png, webp وللفيديو: mp4, mov, avi, webm, mkv',
            'image.max'               => 'حجم الملف يجب ألا يتجاوز 50 ميجابايت',
            'external_link.url'       => 'يجب إدخال رابط خارجي صحيح',
            'video.url'               => 'يجب إدخال رابط فيديو صحيح',
        ];
    }
}

