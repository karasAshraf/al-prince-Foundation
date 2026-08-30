<?php

namespace App\Http\Controllers;

use App\Models\Industry;

class IndustryFrontController extends Controller
{
    public function index()
    {
        $industries = Industry::active()->with('media')->paginate(12);
        return view('frontend.industries.index', compact('industries'));
    }

    public function show(Industry $industry)
    {
        return redirect()->route('industries.index');
    }
}