<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ScheduleRequest;
use App\Models\Schedule;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(): View
    {
        $schedules = Schedule::query()
            ->with(['schoolClass', 'subject', 'teacher.user'])
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('admin.schedules.index', [
            'week' => collect(Schedule::DAYS)->map(fn ($label, $day) => [
                'label' => $label,
                'items' => $schedules->where('day', $day)->values(),
            ]),
            'total' => $schedules->count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.schedules.form', [
            'schedule' => null,
            'classes' => SchoolClass::query()->where('status', 'active')->orderBy('name')->get(),
            'subjects' => Subject::query()->orderBy('code')->get(),
            'teachers' => Teacher::query()->where('status', 'active')->with('user')->orderBy('id')->get(),
            'days' => Schedule::DAYS,
        ]);
    }

    public function store(ScheduleRequest $request): RedirectResponse
    {
        Schedule::query()->create($request->validated());

        return redirect()->route('portal.admin.schedules.index')->with('status', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Schedule $schedule): View
    {
        return view('admin.schedules.form', [
            'schedule' => $schedule,
            'classes' => SchoolClass::query()->where('status', 'active')->orderBy('name')->get(),
            'subjects' => Subject::query()->orderBy('code')->get(),
            'teachers' => Teacher::query()->where('status', 'active')->with('user')->orderBy('id')->get(),
            'days' => Schedule::DAYS,
        ]);
    }

    public function update(ScheduleRequest $request, Schedule $schedule): RedirectResponse
    {
        $schedule->update($request->validated());

        return redirect()->route('portal.admin.schedules.index')->with('status', 'Jadwal diperbarui.');
    }

    public function destroy(Schedule $schedule): RedirectResponse
    {
        $schedule->delete();

        return redirect()->route('portal.admin.schedules.index')->with('status', 'Jadwal telah dihapus.');
    }
}
