<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubjectRequest;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(): View
    {
        return view('admin.subjects.index', [
            'subjects' => Subject::query()->withCount('classSubjects')->orderBy('code')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.subjects.form', [
            'subject' => null,
        ]);
    }

    public function store(SubjectRequest $request): RedirectResponse
    {
        Subject::query()->create($request->validated());

        return redirect()->route('portal.admin.subjects.index')->with('status', 'Mata pelajaran berhasil dibuat.');
    }

    public function edit(Subject $subject): View
    {
        return view('admin.subjects.form', [
            'subject' => $subject,
        ]);
    }

    public function update(SubjectRequest $request, Subject $subject): RedirectResponse
    {
        $subject->update($request->validated());

        return redirect()->route('portal.admin.subjects.index')->with('status', 'Mata pelajaran diperbarui.');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        abort_if($subject->classSubjects()->exists(), 409, 'Mapel ini masih digunakan pada kelas, hentikan dulu pemakaiannya.');

        $subject->delete();

        return redirect()->route('portal.admin.subjects.index')->with('status', 'Mata pelajaran telah dihapus.');
    }
}
