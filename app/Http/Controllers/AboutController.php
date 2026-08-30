<?php

namespace App\Http\Controllers;

use App\Models\AboutSection;
use App\Models\TeamMember;

class AboutController extends Controller
{
    public function index()
    {
        $aboutSections = AboutSection::published()->orderBy('order')->with('media')->get();
        return view('frontend.about.index', compact('aboutSections'));
    }

    public function board()
    {
        $boardMembers = TeamMember::board()->with('media')->get();
        return view('frontend.about.board', compact('boardMembers'));
    }

    public function executiveTeam()
    {
        $executiveMembers = TeamMember::executive()->with('media')->get();
        return view('frontend.about.executive-team', compact('executiveMembers'));
    }
}
