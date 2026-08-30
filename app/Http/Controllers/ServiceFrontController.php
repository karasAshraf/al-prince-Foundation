<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServiceFrontController extends Controller
{
    public function index()
    {
        $services = Service::active()->with('media')->paginate(12);
        return view('frontend.services.index', compact('services'));
    }

    public function show(Service $service)
    {
        return redirect()->route('services.index');
    }
}
