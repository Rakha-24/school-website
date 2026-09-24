<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Material;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $teacher = Auth::user()->teacher;
        $classSubjectIds = $teacher->classSubjects()->pluck('class_subjects.id');

        $today = Carbon::now();

        $todaysSchedule = $teacher->schedules()
            ->with(['subject', 'schoolClass'])
            ->where('day', $today->isoWeekday())
            ->orderBy('start_time')
            ->get();

        $pendingGrading = Assignment::query()
            ->whereIn('class_subject_id', $classSubjectIds)
            ->withCount(['submissions as submitted_count' => fn ($q) => $q->whereNull('score')])
            ->latest('due_at')
            ->get()
            ->filter(fn ($a) => $a->submitted_count > 0)
            ->take(6);

        $materialCount = Material::query()->whereIn('class_subject_id', $classSubjectIds)->count();

        $assignmentCount = Assignment::query()->whereIn('class_subject_id', $classSubjectIds)->count();

        $todayAttendance = Attendance::query()
            ->whereIn('class_id', $teacher->homeroomClasses()->pluck('classes.id'))
            ->whereDate('date', $today)
            ->count();

        $announcements = Announcement::query()->published()->latest('published_at')->get()
            ->filter(fn ($a) => $a->visibleToRoles(['teacher']))
            ->take(4);

        return view('teacher.dashboard', [
            'teacher' => $teacher,
            'todaySchedule' => $todaysSchedule,
            'pendingGrading' => $pendingGrading,
            'materialCount' => $materialCount,
            'assignmentCount' => $assignmentCount,
            'todayAttendance' => $todayAttendance,
            'announcements' => $announcements,
            'todayLabel' => $today->translatedFormat('l, d F Y'),
        ]);
    }
}
