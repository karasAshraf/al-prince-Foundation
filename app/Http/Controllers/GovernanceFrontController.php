<?php

namespace App\Http\Controllers;

use App\Models\GovernanceDocument;

use Illuminate\Http\Request;

class GovernanceFrontController extends Controller
{
    public function index(Request $request)
    {
        $search   = $request->input('search');
        $year     = $request->input('year');
        $category = $request->input('category');

        $query = GovernanceDocument::active();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title_ar', 'like', "%{$search}%")
                  ->orWhere('title_en', 'like', "%{$search}%");
            });
        }

        if (!empty($year)) {
            $query->where('fiscal_year', $year);
        }

        if (!empty($category)) {
            $query->where('category', $category);
        }

        $documents = $query->orderByDesc('fiscal_year')->orderBy('order')->get()->groupBy('category');
        $availableYears = GovernanceDocument::availableYears();

        return view('frontend.governance.index', compact('documents', 'availableYears', 'search', 'year', 'category'));
    }
}
