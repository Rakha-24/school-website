<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\ClassSubject;
use App\Models\Extracurricular;
use App\Models\SchoolClass;
use App\Models\Teacher;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        return view('public.about', [
            'teachingStaff' => Teacher::query()
                ->where('status', 'active')
                ->with('user')
                ->orderBy('id')
                ->take(12)
                ->get(),
            'achievementCount' => Achievement::query()->published()->count(),
            'activeExtracurriculars' => Extracurricular::query()->active()->count(),
            'classCount' => SchoolClass::query()->where('status', 'active')->count(),
            'classSubjects' => ClassSubject::query()
                ->with(['schoolClass', 'subject', 'teacher.user'])
                ->whereHas('schoolClass', fn ($q) => $q->where('status', 'active'))
                ->get()
                ->groupBy(fn ($cs) => $cs->schoolClass->name),
        ]);
    }
}
