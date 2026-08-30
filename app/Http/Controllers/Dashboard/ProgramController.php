<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProgramRequest;
use App\Models\Program;
use App\Services\ProgramService;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function __construct(protected ProgramService $service) {}

    public function index(Request $request)
    {
        $programs = $this->service->list($request->only('status'));
        return view('dashboard.programs.index', compact('programs'));
    }

    public function create()
    {
        return view('dashboard.programs.create', ['program' => new Program()]);
    }

    public function store(ProgramRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('dashboard.programs.index')->with('success', 'تم الإضافة بنجاح');
    }

    public function show(Program $program)
    {
        $program->loadMissing(['projects.media'])->loadCount('projects');
        return view('dashboard.programs.show', compact('program'));
    }

    public function edit(Program $program)
    {
        $program->load('seoMeta');
        return view('dashboard.programs.edit', compact('program'));
    }

    public function update(ProgramRequest $request, Program $program)
    {
        $this->service->update($program, $request->validated());
        return redirect()->route('dashboard.programs.index')->with('success', 'تم التحديث بنجاح');
    }

    public function destroy(Program $program)
    {
        try {
            $this->service->delete($program);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'تم الحذف بنجاح');
    }

    public function toggleStatus(Request $request, Program $program)
    {
        $newStatus = $request->has('is_active') ? $request->boolean('is_active') : !$program->is_active;
        $program->is_active = $newStatus;
        $program->save();

        \Illuminate\Support\Facades\Cache::forget('dashboard.programs_count');

        return response()->json([
            'success' => true,
            'is_active' => (bool)$program->is_active,
            'message' => 'تم تحديث الحالة بنجاح',
        ]);
    }
}