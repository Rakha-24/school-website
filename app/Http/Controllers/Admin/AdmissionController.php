<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdmissionInformationRequest;
use App\Models\AdmissionInformation;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdmissionController extends Controller
{
    public function index(): View
    {
        return view('admin.admission.index', [
            'items' => AdmissionInformation::query()->orderBy('sort_order')->orderBy('id')->paginate(20),
            'types' => AdmissionInformation::TYPES,
        ]);
    }

    public function create(): View
    {
        return view('admin.admission.form', [
            'item' => null,
            'types' => AdmissionInformation::TYPES,
        ]);
    }

    public function store(AdmissionInformationRequest $request): RedirectResponse
    {
        AdmissionInformation::query()->create($request->validated());

        return redirect()->route('portal.admin.admission.index')->with('status', 'Konten PPDB berhasil ditambahkan.');
    }

    public function edit(AdmissionInformation $admission): View
    {
        return view('admin.admission.form', [
            'item' => $admission,
            'types' => AdmissionInformation::TYPES,
        ]);
    }

    public function update(AdmissionInformationRequest $request, AdmissionInformation $admission): RedirectResponse
    {
        $admission->update($request->validated());

        return redirect()->route('portal.admin.admission.index')->with('status', 'Konten PPDB berhasil diperbarui.');
    }

    public function destroy(AdmissionInformation $admission): RedirectResponse
    {
        $admission->delete();

        return redirect()->route('portal.admin.admission.index')->with('status', 'Konten PPDB telah dihapus.');
    }
}
