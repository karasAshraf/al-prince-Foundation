<?php

namespace App\Helpers;

class NavigationHelper
{
    /**
     * Get all available page placements for Hero Slides with localized labels
     * based on the existing backend/sidebar navigation keys.
     */
    public static function getPlacements(): array
    {
        return [
            'home' => __('dashboard.sidebar.home_sections') ?: 'الرئيسية',
            'about' => __('dashboard.sidebar.about_us') ?: 'من نحن',
            'activities' => __('dashboard.sidebar.activities') ?: 'الأنشطة',
            'industries' => __('dashboard.sidebar.industries') ?: 'القطاعات',
            'events' => __('dashboard.sidebar.events') ?: 'الفعاليات',
            'programs' => __('dashboard.sidebar.programs') ?: 'البرامج',
            'projects' => __('dashboard.sidebar.projects') ?: 'المشاريع',
            'services' => __('dashboard.sidebar.services') ?: 'الخدمات',
            'solutions' => __('dashboard.sidebar.solutions') ?: 'الحلول',
            'news' => __('dashboard.sidebar.news') ?: 'الأخبار',
            'surveys' => __('dashboard.sidebar.surveys') ?: 'الاستبيانات',
            'contact' => __('dashboard.sidebar.contact') ?: 'اتصل بنا',
            'governance' => __('dashboard.sidebar.governance_center') ?: 'مركز الحوكمة',
            'media-library' => __('dashboard.sidebar.media_library') ?: 'مكتبة الوسائط',
        ];
    }
}
