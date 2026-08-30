<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use App\Services\ServiceService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct(protected ServiceService $service) {}

    public function index(Request $request)
    {
        $services = $this->service->list($request->only('is_active'));
        return view('dashboard.services.index', compact('services'));
    }

    public function create()
    {
        return view('dashboard.services.create', ['serviceItem' => new Service()]);
    }

    public function store(ServiceRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('dashboard.services.index')->with('success', 'تم إضافة الخدمة بنجاح');
    }

    public function show(Service $service)
    {
        return view('dashboard.services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        $service->load('seoMeta');
        return view('dashboard.services.edit', compact('service'));
    }

    public function update(ServiceRequest $request, Service $service)
    {
        $this->service->update($service, $request->validated());
        return redirect()->route('dashboard.services.index')->with('success', 'تم تحديث الخدمة بنجاح');
    }

    public function destroy(Service $service)
    {
        $this->service->delete($service);
        return back()->with('success', 'تم حذف الخدمة بنجاح');
    }

    public function toggleStatus(Request $request, Service $service)
    {
        $newStatus = $request->has('is_active') ? $request->boolean('is_active') : !$service->is_active;
        $service->is_active = $newStatus;
        $service->save();

        \Illuminate\Support\Facades\Cache::forget('home.active_services');

        return response()->json([
            'success' => true,
            'is_active' => (bool)$service->is_active,
            'message' => 'تم تحديث الحالة بنجاح',
        ]);
    }
}
