<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $url = $this->input('google_maps_url');
        if (filled($url) && (str_contains($url, '<iframe') || str_contains($url, 'src='))) {
            if (preg_match('/src="([^"]+)"/', $url, $matches)) {
                $this->merge(['google_maps_url' => $matches[1]]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'name_ar'         => ['required', 'string', 'max:255'],
            'name_en'         => ['nullable', 'string', 'max:255'],
            'email'           => ['required', 'email', 'max:255'],
            'phone_numbers'   => ['nullable', 'array'],
            'phone_numbers.*' => ['nullable', 'string', 'max:50'],
            'address_ar'      => ['nullable', 'string', 'max:500'],
            'address_en'      => ['nullable', 'string', 'max:500'],
            'description_ar'  => ['nullable', 'string', 'max:1000'],
            'description_en'  => ['nullable', 'string', 'max:1000'],
            'description'     => ['nullable', 'string', 'max:1000'],
            'copyright_ar'    => ['nullable', 'string', 'max:500'],
            'copyright_en'    => ['nullable', 'string', 'max:500'],
            'copyright'       => ['nullable', 'string', 'max:500'],
            'location_name_ar' => ['nullable', 'string', 'max:255'],
            'location_name_en' => ['nullable', 'string', 'max:255'],
            'google_maps_url'  => ['nullable', 'url', 'max:1000'],
            'logo'            => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:4096'],
            'logo_external'   => ['nullable', 'string', 'max:1000'],
            'social_links'    => ['nullable', 'array'],
            'social_links.*'  => ['nullable'],
            'facebook'        => ['nullable', 'url'],
            'twitter'         => ['nullable', 'url'],
            'instagram'       => ['nullable', 'url'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone_numbers.array' => 'يجب أن تكون أرقام الهواتف مصفوفة',
            'email.required'       => 'البرديد الإلكتروني مطلوب',
            'email.email'          => 'يرجى إدخال بريد إلكتروني صحيح',
        ];
    }
}