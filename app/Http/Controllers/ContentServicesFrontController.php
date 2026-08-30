<?php

namespace App\Http\Controllers;

class ContentServicesFrontController extends Controller
{
    public function index()
    {
        return view('frontend.content-services.index');
    }
}
