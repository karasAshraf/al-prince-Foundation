<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_ar'             => ['required', 'string', 'max:255'],
            'name_en'             => ['nullable', 'string', 'max:255'],
            'external_link'       => ['nullable', 'url', 'max:500'],
            'media_external_link' => ['nullable', 'url', 'max:500'],
            'remove_media'        => ['nullable', 'boolean'],
            'image'               => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'order'               => ['nullable', 'integer', 'min:0'],
            'is_active'           => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name_ar.required'  => 'اسم الشريك بالعربي مطلوب',
            'image.file'        => 'يجب أن يكون الشعار ملفاً صحيحاً',
            'image.mimes'       => 'صيغة الملف غير مدعومة. الصيغ المدعومة: jpg, jpeg, png, webp',
            'image.max'         => 'حجم الشعار يجب ألا يتجاوز 10 ميجابايت',
            'external_link.url' => 'يجب إدخال رابط صحيح',
        ];
    }
}
