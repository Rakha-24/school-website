<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Material;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $student = Auth::user()->student->load('schoolClass');
        $class = $student->schoolClass;
        $classSubjectIds = $class?->classSubjects()->pluck('class_subjects.id') ?? collect();

        $statistics = [
            'attendance' => $student->attendanceRecords()->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'materials' => Material::query()
                ->whereIn('class_subject_id', $classSubjectIds)
                ->published()
                ->count(),
            'grades' => $student->submissions()->whereNotNull('score')->count(),
        ];

        $recentAnnouncements = Announcement::query()
            ->published()
            ->get()
            ->filter(fn ($a) => $a->visibleToRoles(['student']))
            ->take(4);

        $today = Carbon::now();

        $todaysSchedule = $class
            ? $class->schedules()->with(['subject', 'teacher.user'])->where('day', $today->isoWeekday())->orderBy('start_time')->get()
            : collect();

        $upcomingAssignments = Assignment::query()
            ->whereIn('class_subject_id', $classSubjectIds)
            ->with(['classSubject.subject', 'teacher.user'])
            ->where('due_at', '>=', $today->copy()->startOfDay())
            ->orderBy('due_at')
            ->take(5)
            ->get();

        $recentGrades = $student->submissions()->with(['assignment.classSubject.subject'])->whereNotNull('score')->orderByDesc('graded_at')->take(5)->get();

        return view('student.dashboard', [
            'student' => $student,
            'statistics' => $statistics,
            'announcements' => $recentAnnouncements,
            'todaySchedule' => $todaysSchedule,
            'upcomingAssignments' => $upcomingAssignments,
            'recentGrades' => $recentGrades,
            'todayLabel' => $today->translatedFormat('l, d F Y'),
        ]);
    }
}
