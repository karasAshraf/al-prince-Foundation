<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\GovernanceDocumentRequest;
use App\Models\GovernanceDocument;
use App\Services\GovernanceDocumentService;
use Illuminate\Http\Request;

class GovernanceDocumentController extends Controller
{
    public function __construct(protected GovernanceDocumentService $service) {}

    public function index(Request $request)
    {
        $documents = $this->service->list($request->only('fiscal_year', 'category'));
        return view('dashboard.governance-documents.index', compact('documents'));
    }

    public function create()
    {
        return view('dashboard.governance-documents.create', ['document' => new GovernanceDocument()]);
    }

    public function store(GovernanceDocumentRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('dashboard.governance-documents.index')->with('success', 'تم الإضافة بنجاح');
    }

    public function show(GovernanceDocument $governanceDocument)
    {
        return view('dashboard.governance-documents.show', ['document' => $governanceDocument]);
    }

    public function edit(GovernanceDocument $governanceDocument)
    {
        return view('dashboard.governance-documents.edit', ['document' => $governanceDocument]);
    }

    public function update(GovernanceDocumentRequest $request, GovernanceDocument $governanceDocument)
    {
        $this->service->update($governanceDocument, $request->validated());
        return redirect()->route('dashboard.governance-documents.index')->with('success', 'تم التحديث بنجاح');
    }

    public function destroy(GovernanceDocument $governanceDocument)
    {
        $this->service->delete($governanceDocument);
        return back()->with('success', 'تم الحذف بنجاح');
    }

    public function toggleStatus(Request $request, GovernanceDocument $governanceDocument)
    {
        $newStatus = $request->has('is_active') ? $request->boolean('is_active') : !$governanceDocument->is_active;
        $governanceDocument->is_active = $newStatus;
        $governanceDocument->save();

        return response()->json([
            'success' => true,
            'is_active' => (bool)$governanceDocument->is_active,
            'message' => 'تم تحديث الحالة بنجاح',
        ]);
    }
}