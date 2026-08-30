<?php

namespace App\Http\Controllers;

use App\Models\Program;

class ProgramFrontController extends Controller
{
    public function index()
    {
        $programs = Program::active()->with('media')->paginate(12);
        return view('frontend.programs.index', compact('programs'));
    }

    public function show(Program $program)
    {
        abort_if(!$program->is_active, 404);
        $projects = $program->projects()->published()->get();
        return view('frontend.programs.show', compact('program', 'projects'));
    }
}
