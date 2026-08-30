@props([
    'seoMeta' => null, // the related SeoMeta model, or null if none saved yet
])

<div class="rounded-xl border border-[#B49C6E]/30 bg-[#EAEAE9]/10 p-5">

    <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold text-[#3D342A]">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#A38B54]" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
        </svg>
        إعدادات SEO (اختياري)
    </h3>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

        <x-forms.input
            name="meta_title_ar"
            label="عنوان الميتا (عربي)"
            :value="old('meta_title_ar', $seoMeta->meta_title_ar ?? '')"
            placeholder="سيُستخدم العنوان الأساسي تلقائيًا إذا تُرك فارغًا"
        />

        <x-forms.input
            name="meta_title_en"
            label="عنوان الميتا (انجليزي)"
            :value="old('meta_title_en', $seoMeta->meta_title_en ?? '')"
        />

        <div class="sm:col-span-2">
            <x-forms.textarea
                name="meta_description_ar"
                label="وصف الميتا (عربي)"
                :value="old('meta_description_ar', $seoMeta->meta_description_ar ?? '')"
                rows="2"
                placeholder="نص قصير يظهر في نتائج البحث بجوجل (150-160 حرف تقريبًا)"
            />
        </div>

        <div class="sm:col-span-2">
            <x-forms.textarea
                name="meta_description_en"
                label="وصف الميتا (انجليزي)"
                :value="old('meta_description_en', $seoMeta->meta_description_en ?? '')"
                rows="2"
            />
        </div>

        <x-forms.input
            name="meta_keywords"
            label="كلمات مفتاحية"
            :value="old('meta_keywords', $seoMeta->meta_keywords ?? '')"
            placeholder="افصل بين الكلمات بفاصلة, مثل: مؤسسة, تنمية, برامج"
        />

        <x-forms.input
            name="canonical_url"
            label="الرابط الأساسي (Canonical URL)"
            :value="old('canonical_url', $seoMeta->canonical_url ?? '')"
            placeholder="اتركه فارغًا في الوضع الطبيعي"
        />

        <div class="sm:col-span-2">
            <x-forms.media-picker
                name="og_image"
                label="صورة المشاركة على السوشيال ميديا (Open Graph)"
                :selected-url="old('og_image', $seoMeta->og_image ?? null)"
            />
        </div>

    </div>
</div>