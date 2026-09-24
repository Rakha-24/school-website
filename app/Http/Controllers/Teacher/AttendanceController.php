<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\AttendanceRequest;
use App\Models\Attendance;
use App\Models\SchoolClass;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(): View
    {
        $teacher = Auth::user()->teacher;

        $classes = SchoolClass::query()
            ->withCount('students')
            ->where('status', 'active')
            ->where(fn ($q) => $q
                ->where('homeroom_teacher_id', $teacher->id)
                ->orWhereIn('id', $teacher->classSubjects()->pluck('class_subjects.class_id')->unique()))
            ->orderBy('name')
            ->get();

        return view('teacher.attendance.index', [
            'classes' => $classes,
            'date' => today()->format('Y-m-d'),
            'roster' => collect(),
            'selectedClass' => null,
            'existing' => collect(),
            'statuses' => Attendance::STATUSES,
        ]);
    }

    public function roster(): View
    {
        $teacher = Auth::user()->teacher;
        $class = SchoolClass::with('students.user')->findOrFail((int) request()->query('class'));
        $date = request()->query('date', today()->format('Y-m-d'));

        $this->authorize('manageClass', $class);

        $dateCarbon = Carbon::parse($date);
        abort_if($dateCarbon->isAfter(today()), 403, 'Tidak dapat mencatat presensi di masa depan.');

        $existing = Attendance::query()
            ->where('class_id', $class->id)
            ->whereDate('date', $dateCarbon)
            ->get()
            ->keyBy('student_id');

        return view('teacher.attendance.index', [
            'classes' => SchoolClass::query()
                ->withCount('students')
                ->where('status', 'active')
                ->where(fn ($q) => $q
                    ->where('homeroom_teacher_id', $teacher->id)
                    ->orWhereIn('id', $teacher->classSubjects()->pluck('class_subjects.class_id')->unique()))
                ->orderBy('name')
                ->get(),
            'date' => $date,
            'roster' => $class->students,
            'selectedClass' => $class,
            'existing' => $existing,
            'statuses' => Attendance::STATUSES,
        ]);
    }

    public function store(AttendanceRequest $request): RedirectResponse
    {
        $class = SchoolClass::findOrFail((int) $request->input('class_id'));

        $date = null;
        foreach ($request->input('records', []) as $studentId => $record) {
            $date = Carbon::parse($request->input('date'));

            Attendance::query()->updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_id' => $class->id,
                    'date' => $date->toDateString(),
                ],
                [
                    'status' => $record['status'],
                    'note' => $record['note'] ?? null,
                    'recorded_by' => Auth::id(),
                ]
            );
        }

        return redirect()
            ->route('portal.teacher.attendance.index', ['class' => $class->id, 'date' => $request->input('date')])
            ->with('status', 'Presensi tanggal '.$date?->translatedFormat('d F Y').' berhasil disimpan.');
    }
}
