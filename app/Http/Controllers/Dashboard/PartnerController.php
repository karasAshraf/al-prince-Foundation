<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartnerRequest;
use App\Models\Partner;
use App\Services\PartnerService;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function __construct(protected PartnerService $service) {}

    public function index(Request $request)
    {
        $partners = $this->service->list($request->only('is_active'));
        return view('dashboard.partners.index', compact('partners'));
    }

    public function create()
    {
        return view('dashboard.partners.create', ['partner' => new Partner()]);
    }

    public function store(PartnerRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('dashboard.partners.index')->with('success', 'تم إضافة الشريك بنجاح');
    }

    public function show(Partner $partner)
    {
        return view('dashboard.partners.show', compact('partner'));
    }

    public function edit(Partner $partner)
    {
        return view('dashboard.partners.edit', compact('partner'));
    }

    public function update(PartnerRequest $request, Partner $partner)
    {
        $this->service->update($partner, $request->validated());
        return redirect()->route('dashboard.partners.index')->with('success', 'تم تحديث الشريك بنجاح');
    }

    public function destroy(Partner $partner)
    {
        $this->service->delete($partner);
        return back()->with('success', 'تم حذف الشريك بنجاح');
    }

    public function toggleStatus(Request $request, Partner $partner)
    {
        $newStatus = $request->has('is_active') ? $request->boolean('is_active') : !$partner->is_active;
        $partner->is_active = $newStatus;
        $partner->save();

        \Illuminate\Support\Facades\Cache::forget('home.active_partners');

        return response()->json([
            'success' => true,
            'is_active' => (bool)$partner->is_active,
            'message' => 'تم تحديث الحالة بنجاح',
        ]);
    }
}
