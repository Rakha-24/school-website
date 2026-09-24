<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(): View
    {
        $teacher = Auth::user()->teacher;

        $week = collect(range(1, 6))->map(fn ($day) => [
            'number' => $day,
            'label' => Schedule::DAYS[$day],
            'date' => Carbon::now()->isoWeekday($day),
            'items' => $teacher->schedules()
                ->with(['subject', 'schoolClass'])
                ->where('day', $day)
                ->orderBy('start_time')
                ->get(),
        ]);

        return view('teacher.schedule', [
            'week' => $week,
            'teacher' => $teacher,
        ]);
    }
}
