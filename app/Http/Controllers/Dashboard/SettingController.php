<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Services\SettingService;

class SettingController extends Controller
{
    public function __construct(protected SettingService $service) {}

    public function edit()
    {
        $companyInfo = $this->service->getGroup('company_info');
        $socialLinks = $this->service->getGroup('social_links');

        return view('dashboard.settings.edit', compact('companyInfo', 'socialLinks'));
    }

    public function update(SettingRequest $request)
    {
        $companyData = $request->only([
            'name_ar', 'name_en', 'email', 'address_ar', 'address_en',
            'description_ar', 'description_en', 'description',
            'copyright_ar', 'copyright_en', 'copyright',
            'location_name_ar', 'location_name_en', 'google_maps_url',
        ]);

        // Process dynamic phone numbers array
        $rawPhones = $request->input('phone_numbers', []);
        if (is_string($rawPhones)) {
            $rawPhones = [$rawPhones];
        }
        $phoneNumbers = array_values(array_filter(
            (array) $rawPhones,
            fn($p) => is_string($p) && filled(trim($p))
        ));
        $companyData['phone_numbers'] = $phoneNumbers;

        if ($request->filled('logo_external')) {
            $companyData['logo'] = $request->input('logo_external');
        } elseif ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logo', 'public');
            $companyData['logo'] = $path;
        } else {
            $existingCompanyInfo = $this->service->getGroup('company_info');
            if (isset($existingCompanyInfo['logo'])) {
                $companyData['logo'] = $existingCompanyInfo['logo'];
            }
        }

        $this->service->saveGroup('company_info', $companyData);

        // Process social links
        $socialInput = $request->input('social_links', []);
        $socialData = [];

        if (is_array($socialInput)) {
            foreach ($socialInput as $key => $val) {
                if (is_array($val) && isset($val['url']) && filled(trim($val['url']))) {
                    $platform = !empty($val['platform']) ? $val['platform'] : ('link_' . $key);
                    $socialData[$platform] = trim($val['url']);
                } elseif (is_string($val) && filled(trim($val))) {
                    $socialData[$key] = trim($val);
                }
            }
        }

        // Support legacy or explicit individual inputs
        foreach (['facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'tiktok', 'whatsapp', 'telegram'] as $platform) {
            if ($request->filled($platform)) {
                $socialData[$platform] = trim($request->input($platform));
            }
        }

        $this->service->saveGroup('social_links', $socialData);

        return back()->with('success', 'تم حفظ الإعدادات بنجاح');
    }
}