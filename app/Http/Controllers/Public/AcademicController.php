<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ClassSubject;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\View\View;

class AcademicController extends Controller
{
    public function show(): View
    {
        $subjects = Subject::query()->orderBy('code')->withCount('classSubjects')->get();

        $classes = SchoolClass::query()
            ->where('status', 'active')
            ->with(['homeroomTeacher.user', 'classSubjects.subject', 'classSubjects.teacher.user'])
            ->orderBy('name')
            ->get();

        $matrix = ClassSubject::query()
            ->with(['schoolClass', 'subject', 'teacher.user'])
            ->get()
            ->sortBy(fn ($cs) => $cs->schoolClass?->name.' '.$cs->subject?->code)
            ->groupBy(fn ($cs) => $cs->schoolClass?->name);

        return view('public.academic', [
            'subjects' => $subjects,
            'classes' => $classes,
            'matrix' => $matrix,
        ]);
    }
}
