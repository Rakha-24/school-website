<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Learning\AssignmentRequest;
use App\Models\Assignment;
use App\Models\ClassSubject;
use App\Notifications\AssignmentPublished;
use App\Traits\HandlesUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    use HandlesUploads;

    public function index(): View
    {
        $teacher = Auth::user()->teacher;

        $assignments = Assignment::query()
            ->with(['classSubject.subject', 'classSubject.schoolClass'])
            ->withCount('submissions')
            ->where('teacher_id', $teacher->id)
            ->latest('due_at')
            ->get();

        return view('teacher.assignments.index', [
            'assignments' => $assignments,
        ]);
    }

    public function create(): View
    {
        return view('teacher.assignments.form', [
            'assignment' => null,
            'classSubjects' => $this->teachableSubjects(),
        ]);
    }

    public function store(AssignmentRequest $request): RedirectResponse
    {
        $teacher = Auth::user()->teacher;
        $classSubject = ClassSubject::findOrFail((int) $request->input('class_subject_id'));

        $this->authorize('createFor', $classSubject);

        $data = $request->safe()->except(['attachment', 'submitted_at']);
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $this->storeSanitizedFile($request->file('attachment'), 'assignments');
        }
        $data['teacher_id'] = $teacher->id;

        $assignment = Assignment::query()->create($data);

        if ($assignment->isOpen()) {
            Notification::send(
                $classSubject->schoolClass->students()->with('user')->get()->pluck('user'),
                new AssignmentPublished($assignment)
            );
        }

        return redirect()->route('portal.teacher.assignments.index')->with('status', 'Tugas berhasil dipublikasikan.');
    }

    public function show(Assignment $assignment): View
    {
        $this->authorize('update', $assignment);

        return view('teacher.assignments.show', [
            'assignment' => $assignment->load(['classSubject.subject', 'classSubject.schoolClass', 'submissions.student.user']),
        ]);
    }

    public function edit(Assignment $assignment): View
    {
        $this->authorize('update', $assignment);

        return view('teacher.assignments.form', [
            'assignment' => $assignment,
            'classSubjects' => $this->teachableSubjects(),
        ]);
    }

    public function update(AssignmentRequest $request, Assignment $assignment): RedirectResponse
    {
        $this->authorize('update', $assignment);

        $data = $request->safe()->except(['attachment', 'submitted_at']);
        if ($request->hasFile('attachment')) {
            $this->deleteStoredFile($assignment->attachment);
            $data['attachment'] = $this->storeSanitizedFile($request->file('attachment'), 'assignments');
        }

        $assignment->update($data);

        return redirect()->route('portal.teacher.assignments.index')->with('status', 'Tugas berhasil diperbarui.');
    }

    public function destroy(Assignment $assignment): RedirectResponse
    {
        $this->authorize('delete', $assignment);

        $this->deleteStoredFile($assignment->attachment);
        $assignment->delete();

        return redirect()->route('portal.teacher.assignments.index')->with('status', 'Tugas telah dihapus.');
    }

    private function teachableSubjects()
    {
        return Auth::user()->teacher->classSubjects()
            ->with(['subject', 'schoolClass'])
            ->orderBy('class_id')
            ->get();
    }
}
