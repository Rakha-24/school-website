<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Learning\MaterialRequest;
use App\Models\ClassSubject;
use App\Models\Material;
use App\Traits\HandlesUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MaterialController extends Controller
{
    use HandlesUploads;

    public function index(): View
    {
        $teacher = Auth::user()->teacher;

        $materials = Material::query()
            ->with(['classSubject.subject', 'classSubject.schoolClass'])
            ->where('teacher_id', $teacher->id)
            ->latest('published_at')
            ->get();

        return view('teacher.materials.index', [
            'materials' => $materials,
        ]);
    }

    public function create(): View
    {
        return view('teacher.materials.form', [
            'material' => null,
            'classSubjects' => $this->teachableSubjects(),
            'statuses' => ['draft' => 'Draf', 'published' => 'Terbitkan'],
        ]);
    }

    public function store(MaterialRequest $request): RedirectResponse
    {
        $teacher = Auth::user()->teacher;
        $classSubjectId = (int) $request->input('class_subject_id');

        $this->authorize('createFor', ClassSubject::findOrFail($classSubjectId));

        $data = $request->safe()->except(['file']);
        if ($request->hasFile('file')) {
            $data['file_path'] = $this->storeSanitizedFile($request->file('file'), 'materials');
        }
        $data['teacher_id'] = $teacher->id;
        $data['published_at'] = $request->input('status') === 'published' ? now() : null;

        Material::query()->create($data);

        return redirect()->route('portal.teacher.materials.index')->with('status', 'Materi berhasil dibuat.');
    }

    public function show(Material $material): View
    {
        $this->authorize('update', $material);

        return view('teacher.materials.show', [
            'material' => $material->load(['classSubject.subject', 'classSubject.schoolClass']),
        ]);
    }

    public function edit(Material $material): View
    {
        $this->authorize('update', $material);

        return view('teacher.materials.form', [
            'material' => $material,
            'classSubjects' => $this->teachableSubjects(),
            'statuses' => ['draft' => 'Draf', 'published' => 'Terbitkan'],
        ]);
    }

    public function update(MaterialRequest $request, Material $material): RedirectResponse
    {
        $this->authorize('update', $material);

        $data = $request->safe()->except(['file']);
        if ($request->hasFile('file')) {
            $this->deleteStoredFile($material->file_path);
            $data['file_path'] = $this->storeSanitizedFile($request->file('file'), 'materials');
        }
        $data['published_at'] = $request->input('status') === 'published' ? ($material->published_at ?? now()) : null;

        $material->update($data);

        return redirect()->route('portal.teacher.materials.index')->with('status', 'Materi berhasil diperbarui.');
    }

    public function destroy(Material $material): RedirectResponse
    {
        $this->authorize('delete', $material);

        $this->deleteStoredFile($material->file_path);
        $material->delete();

        return redirect()->route('portal.teacher.materials.index')->with('status', 'Materi telah dihapus.');
    }

    private function teachableSubjects()
    {
        return Auth::user()->teacher->classSubjects()
            ->with(['subject', 'schoolClass'])
            ->orderBy('class_id')
            ->get();
    }
}
