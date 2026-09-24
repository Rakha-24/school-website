<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\AttendanceRequest;
use App\Models\Attendance;
use App\Models\SchoolClass;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(): View
    {
        $class = request()->filled('class')
            ? SchoolClass::with('students.user')->findOrFail((int) request()->query('class'))
            : null;

        $date = request()->query('date', today()->format('Y-m-d'));

        $existing = collect();
        if ($class) {
            $existing = Attendance::query()
                ->where('class_id', $class->id)
                ->whereDate('date', Carbon::parse($date))
                ->get()
                ->keyBy('student_id');
        }

        return view('admin.attendance.index', [
            'classes' => SchoolClass::query()->withCount('students')->where('status', 'active')->orderBy('name')->get(),
            'selectedClass' => $class,
            'date' => $date,
            'existing' => $existing,
            'roster' => $class?->students,
            'statuses' => Attendance::STATUSES,
        ]);
    }

    public function store(AttendanceRequest $request): RedirectResponse
    {
        $class = SchoolClass::findOrFail((int) $request->input('class_id'));
        $date = Carbon::parse($request->input('date'));
        abort_if($date->isAfter(today()), 403, 'Tidak dapat mencatat presensi di masa depan.');

        foreach ($request->input('records', []) as $studentId => $record) {
            Attendance::query()->updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_id' => $class->id,
                    'date' => $date->toDateString(),
                ],
                [
                    'status' => $record['status'],
                    'note' => $record['note'] ?? null,
                    'recorded_by' => auth()->id(),
                ]
            );
        }

        return redirect()
            ->route('portal.admin.attendance.index', ['class' => $class->id, 'date' => $date->toDateString()])
            ->with('status', 'Presensi '.$date->translatedFormat('d F Y').' untuk '.$class->name.' berhasil disimpan.');
    }
}
