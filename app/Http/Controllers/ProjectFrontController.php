<?php

namespace App\Http\Controllers;

use App\Models\Project;

class ProjectFrontController extends Controller
{
    public function index()
    {
        $projects = Project::published()->with(['media', 'program'])->latest()->paginate(12);
        return view('frontend.projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        abort_if(!$project->is_active, 404);
        $project->load('program');
        return view('frontend.projects.show', compact('project'));
    }
}
