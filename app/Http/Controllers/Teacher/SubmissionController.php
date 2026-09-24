<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Learning\GradeSubmissionRequest;
use App\Models\Assignment;
use App\Models\Submission;
use App\Notifications\SubmissionGraded;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class SubmissionController extends Controller
{
    public function index(): View
    {
        $teacher = auth()->user()->teacher;

        $assignments = Assignment::query()
            ->with(['classSubject.subject', 'classSubject.schoolClass'])
            ->withCount(['submissions', 'submissions as pending_count' => fn ($q) => $q->whereNull('score')])
            ->where('teacher_id', $teacher->id)
            ->latest('due_at')
            ->get();

        return view('teacher.submissions.index', [
            'assignments' => $assignments,
        ]);
    }

    public function show(Assignment $assignment): View
    {
        $this->authorize('viewAny', [Submission::class, $assignment]);

        return view('teacher.submissions.show', [
            'assignment' => $assignment->load([
                'classSubject.subject',
                'classSubject.schoolClass.students.user',
                'submissions.student.user',
            ]),
        ]);
    }

    public function grade(Submission $submission, GradeSubmissionRequest $request): RedirectResponse
    {
        $this->authorize('grade', $submission);

        $submission->update([
            'score' => $request->input('score'),
            'feedback' => $request->input('feedback'),
            'graded_at' => now(),
        ]);

        Notification::send($submission->student->user, new SubmissionGraded($submission));

        return back()->with('status', 'Nilai untuk '.$submission->student->user->name.' telah disimpan.');
    }
}
