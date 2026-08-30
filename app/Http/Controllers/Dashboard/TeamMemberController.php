<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeamMemberRequest;
use App\Models\TeamMember;
use App\Services\TeamMemberService;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function __construct(protected TeamMemberService $service) {}

    public function index(Request $request)
    {
        $type = $request->get('type', 'board');
        $members = $this->service->list($type);
        return view('dashboard.team-members.index', compact('members', 'type'));
    }

    public function create()
    {
        return view('dashboard.team-members.create', ['member' => new TeamMember()]);
    }

    public function store(TeamMemberRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('dashboard.team-members.index')->with('success', 'تم الإضافة بنجاح');
    }

    public function show(TeamMember $teamMember)
    {
        return view('dashboard.team-members.show', ['item' => $teamMember]);
    }

    public function edit(TeamMember $teamMember)
    {
        return view('dashboard.team-members.edit', ['member' => $teamMember]);
    }

    public function update(TeamMemberRequest $request, TeamMember $teamMember)
    {
        $this->service->update($teamMember, $request->validated());
        return redirect()->route('dashboard.team-members.index')->with('success', 'تم التحديث بنجاح');
    }

    public function destroy(TeamMember $teamMember)
    {
        $this->service->delete($teamMember);
        return back()->with('success', 'تم الحذف بنجاح');
    }

    public function toggleStatus(Request $request, TeamMember $teamMember)
    {
        $newStatus = $request->has('is_active') ? $request->boolean('is_active') : !$teamMember->is_active;
        $teamMember->is_active = $newStatus;
        $teamMember->save();

        return response()->json([
            'success' => true,
            'is_active' => (bool)$teamMember->is_active,
            'message' => 'تم تحديث الحالة بنجاح',
        ]);
    }
}