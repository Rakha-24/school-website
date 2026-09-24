<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Learning\SubmissionRequest;
use App\Models\Assignment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function index(Request $request): View
    {
        $student = Auth::user()->student;
        $class = $student->schoolClass;

        $query = Assignment::query()
            ->with(['classSubject.subject', 'teacher.user', 'submissions'])
            ->whereIn('class_subject_id', $class->classSubjects()->pluck('class_subjects.id'));

        if ($request->query('filter') === 'open') {
            $query->where('due_at', '>=', now());
        } elseif ($request->query('filter') === 'done') {
            $query->whereHas('submissions', fn ($q) => $q->where('student_id', $student->id));
        }

        return view('student.assignments.index', [
            'assignments' => $query->latest('due_at')->get(),
            'ownSubmissions' => $student->submissions()->pluck('score', 'assignment_id'),
            'student' => $student,
            'activeFilter' => $request->query('filter'),
        ]);
    }

    public function show(Assignment $assignment): View
    {
        $this->authorize('view', $assignment);

        $student = Auth::user()->student;

        return view('student.assignments.show', [
            'assignment' => $assignment->load(['classSubject.subject', 'classSubject.schoolClass', 'teacher.user']),
            'submission' => $assignment->submissionFor($student),
            'student' => $student,
        ]);
    }

    public function submit(Assignment $assignment, SubmissionRequest $request): RedirectResponse
    {
        $this->authorize('view', $assignment);

        $student = Auth::user()->student;

        abort_unless($assignment->isOpen(), 403, 'Tugas sudah melewati tenggat waktu.');

        $data = $request->validated();
        unset($data['answer_text']);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('submissions');
            $data['answer_text'] = null;
        } else {
            $data['answer_text'] = $request->input('answer_text');
        }

        $data['student_id'] = $student->id;
        $data['submitted_at'] = now();
        $data['score'] = null;
        $data['feedback'] = null;
        $data['graded_at'] = null;

        $submission = $assignment->submissions()->updateOrCreate(['assignment_id' => $assignment->id, 'student_id' => $student->id], $data);

        return redirect()->route('portal.student.assignments.show', $assignment)
            ->with('status', 'Jawaban Anda berhasil '.($submission->wasRecentlyCreated ? 'dikirim' : 'diperbarui').'.');
    }
}
