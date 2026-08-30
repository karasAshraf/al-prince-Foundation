<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HeroSlideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('hero_slide')?->id ?? $this->route('hero_slide');

        return [
            'title_ar'        => ['nullable', 'string', 'max:255'],
            'title_en'        => ['nullable', 'string', 'max:255'],
            'subtitle_ar'     => ['nullable', 'string'],
            'subtitle_en'     => ['nullable', 'string'],
            'button_text_ar'  => ['nullable', 'string', 'max:255'],
            'button_text_en'  => ['nullable', 'string', 'max:255'],
            'button_url'      => ['nullable', 'url', 'max:500'],
            'placement'       => ['required', 'string', Rule::in(array_keys(\App\Helpers\NavigationHelper::getPlacements()))],
            'order'           => ['required', 'integer', 'min:0'],
            'is_active'       => ['boolean'],
            'image'           => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm,mkv,ogg', 'max:51200'], // Max 50MB files
            'media_external_link' => ['nullable', 'string', 'max:1000'],
            'external_link'   => ['nullable', 'string', 'max:1000'],
            'remove_media'    => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->has('is_active'),
        ]);
    }

    public function messages(): array
    {
        return [
            'title_ar.required' => 'العنوان بالعربي مطلوب',
            'placement.required' => 'تحديد مكان العرض مطلوب',
            'placement.in' => 'مكان العرض المحدد غير صالح',
            'image.file' => 'يجب أن يكون الملف المرفق صورة صحيحة',
            'image.mimes' => 'صيغة الصورة غير مدعومة. الصيغ المدعومة هي: jpg, jpeg, png, webp',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 10 ميجابايت',
            'button_url.url' => 'يجب إدخال رابط زر صحيح',
        ];
    }
}
