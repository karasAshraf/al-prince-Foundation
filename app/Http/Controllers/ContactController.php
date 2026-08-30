<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactMessageRequest;
use App\Services\ContactMessageService;

class ContactController extends Controller
{
    public function __construct(protected ContactMessageService $service) {}

    public function create()
    {
        $companyInfo = \App\Models\Setting::group('company_info');
        $locale = app()->getLocale();

        $siteEmail = $companyInfo['email'] ?? 'info@alathar.org.sa';
        
        $siteAddress = ($locale === 'en' ? ($companyInfo['address_en'] ?? null) : null) 
            ?? $companyInfo['address_ar'] 
            ?? __('frontend.address_fallback');

        // Extract first phone number from phone_numbers array
        $phones = $companyInfo['phone_numbers'] ?? [];
        $sitePhone = $phones[0] ?? '+966 50 000 0000';

        // Get location and Google Maps URL
        $locationName = ($locale === 'en' ? ($companyInfo['location_name_en'] ?? null) : null) 
            ?? $companyInfo['location_name_ar'] 
            ?? ($locale === 'en' ? 'Head Office' : 'المقر الرئيسي');
        
        $googleMapsUrl = $companyInfo['google_maps_url'] ?? null;

        $mapIframeUrl = $this->getEmbedUrl($googleMapsUrl, $siteAddress);

        return view('frontend.contact.index', compact(
            'sitePhone',
            'siteEmail',
            'siteAddress',
            'locationName',
            'googleMapsUrl',
            'mapIframeUrl'
        ));
    }

    public function store(ContactMessageRequest $request)
    {
        $this->service->create($request->validated());
        return back()->with('success', 'شكرًا لتواصلك معنا، سيتم الرد عليك قريبًا');
    }

    private function getEmbedUrl(?string $url, string $address): string
    {
        if (empty($url)) {
            return 'https://maps.google.com/maps?q=' . urlencode($address) . '&output=embed';
        }

        // 1. Already an embed URL
        if (str_contains($url, '/embed') || str_contains($url, 'output=embed')) {
            return $url;
        }

        // 2. Parse standard Google Maps place links
        // e.g. https://www.google.com/maps/place/Saudi+Arabia/@24.1374945,32.8120612,5z/data=...
        if (preg_match('/maps\/place\/([^\/\?]+)/', $url, $matches)) {
            return 'https://maps.google.com/maps?q=' . $matches[1] . '&output=embed';
        }

        // 3. Parse coordinates from standard format
        // e.g. https://www.google.com/maps/@24.1374945,32.8120612,15z
        if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $matches)) {
            return 'https://maps.google.com/maps?q=' . $matches[1] . ',' . $matches[2] . '&z=15&output=embed';
        }

        // 4. Parse query parameters if present
        // e.g. https://www.google.com/maps?q=Saudi+Arabia
        $parsedUrl = parse_url($url);
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
            if (isset($queryParams['q'])) {
                return 'https://maps.google.com/maps?q=' . urlencode($queryParams['q']) . '&output=embed';
            }
        }

        // 5. Fallback to address query
        return 'https://maps.google.com/maps?q=' . urlencode($address) . '&output=embed';
    }
}