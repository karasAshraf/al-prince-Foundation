<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class FrontendLayout extends Component
{
    public function __construct(
        public ?string $title = null,
        public ?string $metaDescription = null,
        public ?string $metaKeywords = null,
        public ?string $ogImage = null,
        public ?string $canonicalUrl = null,
        public $seoMeta = null,
        public $model = null,
    ) {}

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.frontend');
    }
}
