<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function index(): View
    {
        $student = Auth::user()->student;
        $class = $student->schoolClass;

        $rows = Assignment::query()
            ->with(['classSubject.subject', 'teacher.user'])
            ->whereIn('class_subject_id', $class->classSubjects()->pluck('class_subjects.id'))
            ->with(['submissions' => fn ($q) => $q->where('student_id', $student->id)])
            ->orderByDesc('due_at')
            ->get()
            ->filter(fn ($a) => $a->submissions->isNotEmpty() && $a->submissions->first()->score !== null)
            ->map(function ($a) {
                return [
                    'assignment' => $a,
                    'submission' => $a->submissions->first(),
                ];
            });

        return view('student.grades', [
            'rows' => $rows,
            'student' => $student,
        ]);
    }
}
