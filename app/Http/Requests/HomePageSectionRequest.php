<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HomePageSectionRequest extends FormRequest
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
        return [
            'type'           => ['required', Rule::in(['slider', 'home_section', 'counter', 'latest_news', 'hero_slider', 'counters', 'service_section', 'about_preview', 'projects_preview', 'cta'])],
            'title_ar'       => ['nullable', 'string', 'max:255'],
            'title_en'       => ['nullable', 'string', 'max:255'],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'label_ar'       => ['nullable', 'string', 'max:255'],
            'label_en'       => ['nullable', 'string', 'max:255'],
            'extra_link'     => ['nullable', 'url', 'max:500'],
            'external_link'  => ['nullable', 'url', 'max:500'],
            'data'           => ['nullable', 'array'],
            'counter_number' => ['nullable', 'string', 'max:255'],
            'counter_icon'   => ['nullable', 'string', 'max:255'],
            'person_name_ar' => ['nullable', 'string', 'max:255'],
            'person_name_en' => ['nullable', 'string', 'max:255'],
            'order'          => ['nullable', 'integer', 'min:0'],
            'is_active'      => ['nullable', 'boolean'],
            'remove_media'   => ['nullable', 'boolean'],
            'image'          => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm,mkv,ogg', 'max:51200'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required'     => 'نوع القسم مطلوب',
            'type.in'           => 'نوع القسم المحدد غير صالح',
            'image.file'        => 'يجب أن يكون ملف الوسائط ملفاً صحيحاً',
            'image.mimes'       => 'صيغة الملف غير مدعومة. الصيغ المدعومة للصور: jpg, jpeg, png, webp وللفيديو: mp4, mov, avi, webm, mkv',
            'image.max'         => 'حجم الملف يجب ألا يتجاوز 50 ميجابايت',
            'extra_link.url'    => 'يجب إدخال رابط خارجي صحيح',
            'external_link.url' => 'يجب إدخال رابط خارجي صحيح',
        ];
    }
}

