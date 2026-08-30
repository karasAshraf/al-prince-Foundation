<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Program;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(protected ProjectService $service) {}

    public function index(Request $request)
    {
        $projects = $this->service->list(
            $request->only(['program_id', 'project_status'])
        );
        $programs = Program::orderBy('order')->pluck('title_ar', 'id');

        return view('dashboard.projects.index', compact('projects', 'programs'));
    }

    public function create()
    {
        $programs = Program::orderBy('order')->pluck('title_ar', 'id');
        return view('dashboard.projects.create', [
            'project'  => new Project(),
            'programs' => $programs,
        ]);
    }

    public function store(ProjectRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('dashboard.projects.index')->with('success', 'تم إضافة المشروع بنجاح');
    }

    public function show(Project $project)
    {
        $project->load('program');
        return view('dashboard.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $project->load('seoMeta');
        $programs = Program::orderBy('order')->pluck('title_ar', 'id');
        return view('dashboard.projects.edit', compact('project', 'programs'));
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $this->service->update($project, $request->validated());
        return redirect()->route('dashboard.projects.index')->with('success', 'تم تحديث المشروع بنجاح');
    }

    public function destroy(Project $project)
    {
        $this->service->delete($project);
        return back()->with('success', 'تم حذف المشروع بنجاح');
    }

    public function toggleStatus(Request $request, Project $project)
    {
        $newStatus = $request->has('is_active') ? $request->boolean('is_active') : !$project->is_active;
        $project->is_active = $newStatus;
        $project->save();

        \Illuminate\Support\Facades\Cache::forget('dashboard.projects_count');

        return response()->json([
            'success' => true,
            'is_active' => (bool)$project->is_active,
            'message' => 'تم تحديث الحالة بنجاح',
        ]);
    }
}