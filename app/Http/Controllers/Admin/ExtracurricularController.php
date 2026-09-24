<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExtracurricularRequest;
use App\Models\Extracurricular;
use App\Traits\HandlesUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ExtracurricularController extends Controller
{
    use HandlesUploads;

    public function index(): View
    {
        return view('admin.extracurriculars.index', [
            'extracurriculars' => Extracurricular::query()->orderBy('name')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.extracurriculars.form', [
            'extracurricular' => null,
        ]);
    }

    public function store(ExtracurricularRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['photo']);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        if ($request->hasFile('photo')) {
            $data['photo'] = $this->storeSanitizedFile($request->file('photo'), 'extracurriculars');
        }

        Extracurricular::query()->create($data);

        return redirect()->route('portal.admin.extracurriculars.index')->with('status', 'Ekstrakurikuler berhasil ditambahkan.');
    }

    public function edit(Extracurricular $extracurricular): View
    {
        return view('admin.extracurriculars.form', [
            'extracurricular' => $extracurricular,
        ]);
    }

    public function update(ExtracurricularRequest $request, Extracurricular $extracurricular): RedirectResponse
    {
        $data = $request->safe()->except(['photo']);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        if ($request->hasFile('photo')) {
            $this->deleteStoredFile($extracurricular->photo);
            $data['photo'] = $this->storeSanitizedFile($request->file('photo'), 'extracurriculars');
        }

        $extracurricular->update($data);

        return redirect()->route('portal.admin.extracurriculars.index')->with('status', 'Ekstrakurikuler diperbarui.');
    }

    public function destroy(Extracurricular $extracurricular): RedirectResponse
    {
        $this->deleteStoredFile($extracurricular->photo);
        $extracurricular->delete();

        return redirect()->route('portal.admin.extracurriculars.index')->with('status', 'Ekstrakurikuler telah dihapus.');
    }
}
