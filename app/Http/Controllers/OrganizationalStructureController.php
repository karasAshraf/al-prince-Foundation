<?php

namespace App\Http\Controllers;

use App\Services\OrganizationalStructureService;

class OrganizationalStructureController extends Controller
{
    public function __construct(
        protected OrganizationalStructureService $service
    ) {}

    public function index()
    {
        $structure = $this->service->getActive();

        return view('frontend.about.organizational-structure', [
            'structure' => $structure,
        ]);
    }
}