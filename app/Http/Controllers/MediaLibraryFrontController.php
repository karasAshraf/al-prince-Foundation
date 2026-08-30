<?php

namespace App\Http\Controllers;

use App\Models\MediaLibrary;

class MediaLibraryFrontController extends Controller
{
    public function index()
    {
        $categories = MediaLibrary::categories();
        $selectedCategory = request('category');
        $selectedFormat = request('format');

        // Clean values for query checking: treat 'all' or empty strings as null/ignored
        $filterCategory = ($selectedCategory && $selectedCategory !== 'all') ? $selectedCategory : null;
        $filterFormat = ($selectedFormat && $selectedFormat !== 'all') ? $selectedFormat : null;

        $items = MediaLibrary::active()
            ->when($filterCategory, function($q, $c) {
                $q->where('category', $c);
            })
            ->when(request('search'), function($q, $s) {
                $q->where(function($sub) use ($s) {
                    $sub->where('title_ar', 'like', "%{$s}%")
                       ->orWhere('title_en', 'like', "%{$s}%")
                       ->orWhere('description_ar', 'like', "%{$s}%")
                       ->orWhere('description_en', 'like', "%{$s}%");
                });
            })
            ->when($filterFormat, function($q, $f) {
                if ($f === 'external') {
                    $q->whereNotNull('external_link')->where('external_link', '!=', '');
                } else {
                    $q->where(function($sub) use ($f) {
                        // 1. Spatie media library files
                        $sub->whereHas('media', function($mq) use ($f) {
                            if ($f === 'pdf') {
                                $mq->where('mime_type', 'application/pdf')
                                   ->orWhere('file_name', 'like', '%.pdf');
                            } elseif ($f === 'image') {
                                $mq->where('mime_type', 'like', 'image/%');
                            } elseif ($f === 'video') {
                                $mq->where('mime_type', 'like', 'video/%');
                            } elseif ($f === 'document') {
                                $mq->where('mime_type', 'not like', 'image/%')
                                   ->where('mime_type', 'not like', 'video/%')
                                   ->where('mime_type', '!=', 'application/pdf');
                            }
                        });

                        // 2. External links matching extensions
                        if ($f === 'pdf') {
                            $sub->orWhere('external_link', 'like', '%.pdf%');
                        } elseif ($f === 'image') {
                            $sub->orWhere(function($imgQ) {
                                foreach (['.jpg', '.jpeg', '.png', '.webp', '.gif', '.svg'] as $ext) {
                                    $imgQ->orWhere('external_link', 'like', "%{$ext}%");
                                }
                            });
                        } elseif ($f === 'video') {
                            $sub->orWhere('external_link', 'like', '%youtube.com%')
                               ->orWhere('external_link', 'like', '%youtu.be%')
                               ->orWhere('external_link', 'like', '%vimeo.com%')
                               ->orWhere('external_link', 'like', '%.mp4%')
                               ->orWhere('external_link', 'like', '%.webm%')
                               ->orWhere('external_link', 'like', '%.ogg%');
                        }
                    });
                }
            })
            ->with('media')
            ->paginate(12)
            ->withQueryString();

        return view('frontend.media-library.index', compact('items', 'categories', 'selectedCategory', 'selectedFormat'));
    }
}
