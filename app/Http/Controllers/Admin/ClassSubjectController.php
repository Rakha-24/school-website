<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClassSubjectRequest;
use App\Models\ClassSubject;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClassSubjectController extends Controller
{
    public function index(): View
    {
        return view('admin.class-subjects.index', [
            'rows' => ClassSubject::query()
                ->with(['schoolClass', 'subject', 'teacher.user'])
                ->withCount(['materials', 'assignments'])
                ->orderBy('class_id')
                ->get()
                ->sortBy(fn ($cs) => $cs->schoolClass?->name.' '.($cs->subject?->code ?? ''))
                ->groupBy(fn ($cs) => $cs->schoolClass?->name),
        ]);
    }

    public function create(): View
    {
        return view('admin.class-subjects.form', [
            'row' => null,
            'classes' => SchoolClass::query()->where('status', 'active')->orderBy('name')->get(),
            'subjects' => Subject::query()->orderBy('code')->get(),
            'teachers' => Teacher::query()->where('status', 'active')->with('user')->orderBy('id')->get(),
        ]);
    }

    public function store(ClassSubjectRequest $request): RedirectResponse
    {
        ClassSubject::query()->create($request->validated());

        return redirect()->route('portal.admin.subjects.index')->with('status', 'Relasi kelas–mapel berhasil ditambahkan.');
    }

    public function edit(ClassSubject $classSubject): View
    {
        return view('admin.class-subjects.form', [
            'row' => $classSubject,
            'classes' => SchoolClass::query()->where('status', 'active')->orderBy('name')->get(),
            'subjects' => Subject::query()->orderBy('code')->get(),
            'teachers' => Teacher::query()->where('status', 'active')->with('user')->orderBy('id')->get(),
        ]);
    }

    public function update(ClassSubjectRequest $request, ClassSubject $classSubject): RedirectResponse
    {
        $classSubject->update($request->validated());

        return redirect()->route('portal.admin.subjects.index')->with('status', 'Relasi kelas–mapel diperbarui.');
    }

    public function destroy(ClassSubject $classSubject): RedirectResponse
    {
        abort_if($classSubject->materials()->exists() || $classSubject->assignments()->exists(), 409, 'Terdapat materi atau tugas terkait. Hapus data terkait terlebih dahulu.');

        $classSubject->delete();

        return redirect()->route('portal.admin.subjects.index')->with('status', 'Relasi kelas–mapel telah dihapus.');
    }
}
