<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClassRequest;
use App\Models\SchoolClass;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClassController extends Controller
{
    public function index(): View
    {
        return view('admin.classes.index', [
            'classes' => SchoolClass::query()
                ->with(['homeroomTeacher.user', 'classSubjects.subject', 'students'])
                ->withCount(['students', 'classSubjects', 'schedules'])
                ->orderBy('name')
                ->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.classes.form', [
            'class' => null,
            'teachers' => Teacher::query()->where('status', 'active')->with('user')->orderBy('id')->get(),
            'statuses' => ['active' => 'Aktif', 'archived' => 'Arsip'],
        ]);
    }

    public function store(ClassRequest $request): RedirectResponse
    {
        SchoolClass::query()->create($request->validated());

        return redirect()->route('portal.admin.classes.index')->with('status', 'Kelas berhasil dibuat.');
    }

    public function edit(SchoolClass $class): View
    {
        return view('admin.classes.form', [
            'class' => $class,
            'teachers' => Teacher::query()->where('status', 'active')->with('user')->orderBy('id')->get(),
            'statuses' => ['active' => 'Aktif', 'archived' => 'Arsip'],
        ]);
    }

    public function update(ClassRequest $request, SchoolClass $class): RedirectResponse
    {
        $class->update($request->validated());

        return redirect()->route('portal.admin.classes.index')->with('status', 'Data kelas diperbarui.');
    }

    public function destroy(SchoolClass $class): RedirectResponse
    {
        abort_if($class->classSubjects()->exists() || $class->students()->exists(), 409, 'Kelas memiliki data mengajar atau siswa, arsipkan saja.');

        $class->delete();

        return redirect()->route('portal.admin.classes.index')->with('status', 'Kelas telah dihapus.');
    }
}
