<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MaterialController extends Controller
{
    public function index(Request $request): View
    {
        $student = Auth::user()->student;
        $class = $student->schoolClass;

        $classSubjectIds = $request->filled('subject')
            ? $class->classSubjects()->where('subject_id', (int) $request->query('subject'))->pluck('class_subjects.id')
            : $class->classSubjects()->pluck('class_subjects.id');

        $materials = Material::query()
            ->with(['classSubject.subject', 'teacher.user'])
            ->whereIn('class_subject_id', $classSubjectIds)
            ->published()
            ->latest('published_at')
            ->get();

        return view('student.materials.index', [
            'materials' => $materials,
            'classSubjects' => $class->classSubjects()->with('subject')->orderBy('subject_id')->get(),
            'activeSubject' => $request->query('subject'),
        ]);
    }

    public function show(Material $material): View
    {
        $this->authorize('view', $material);

        abort_unless($material->isPublished(), 404);

        return view('student.materials.show', [
            'material' => $material->load(['classSubject.subject', 'classSubject.schoolClass', 'teacher.user']),
        ]);
    }
}
